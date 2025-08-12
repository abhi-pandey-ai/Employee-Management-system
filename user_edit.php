<?php
session_start();
if (!isset($_SESSION['SignIn']) || !isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}
$username = $_SESSION['name'];

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

include_once('dbconn.php');
$id = $_GET['id'];

$nameErr = $emailErr = $passwordErr = "";
$name = $email = $password = "";
$successMsg="";

$select = "SELECT * FROM forms WHERE id = '$id'";
$data = mysqli_query($conn, $select);
$row = mysqli_fetch_array($data);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
      $nameErr = "Only letters and white space allowed";
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }

  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
    if (strlen($password) < 6) {
      $passwordErr = "Password must be at least 6 characters";
    }
  }

  // If no validation errors
  if ($nameErr == "" && $emailErr == "" && $passwordErr == "") {
    $imgUpdateSQL = "";
    if (isset($_FILES['img_name']) && $_FILES['img_name']['error'] == 0) {
      $imgName = $_FILES['img_name']['name'];
      $tmpName = $_FILES['img_name']['tmp_name'];
      $destination = "imagess/" . $imgName;
      move_uploaded_file($tmpName, $destination);
      $imgUpdateSQL = ", img_name = '$imgName'";
    }

    $update = "UPDATE forms SET name = '$name', password = '$password' $imgUpdateSQL WHERE id = '$id'";
    $alldata = mysqli_query($conn, $update);

    if ($alldata) {
     $successMsg ='<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Data updated successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>';
      // data update ho rhaa hai but naya wala data show nji ho rhaa hai uske liye dubra select query , 
      $select = "SELECT * FROM forms WHERE id = '$id'";
      $data = mysqli_query($conn,$select);
      $row = mysqli_fetch_array($data);
      
    } else {
      echo "Database update error: " . mysqli_error($conn);
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE v4 | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
  <?php 
    include_once('header.php');
    include_once('sidebar.php'); 
  ?>
  <main class="app-main">
    <div class="app-content">
      <div class="container-fluid">
        <?php echo $successMsg; ?>
        <div class="col-md-12">
          <div class="card card-warning card-outline mb-4" style="">
            <div class="card-header"><div class="card-title"> Users edit page ! </div></div>
            <form method="POST" enctype="multipart/form-data">
              <div class="card-body">
                <div class="row mb-3">
                  <label for="name" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input value="<?php echo $row['name'] ?>" type="text" name="name" class="form-control" id="name" />
                  </div>
                  <div class="col-sm-10 offset-sm-2">
                    <small style="color:red;"><?php echo $nameErr;?></small>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input value="<?php echo $row['email'] ?>" type="email" name="email" readonly class="form-control" id="inputEmail3" />
                  </div>
                  <div class="col-sm-10 offset-sm-2">
                    <small style="color:red;"><?php echo $emailErr;?></small>
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="pass" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input value="<?php echo $row['password'] ?>" type="password" name="password" class="form-control" id="pass" />
                  </div>
                  <div class="col-sm-10 offset-sm-2">
                    <small style="color:red;"><?php echo $passwordErr;?></small>
                  </div>
                </div>              

                <div class="row mb-3">
                  <div class="col-sm-10 offset-sm-2">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" onclick="sho()" id="gridCheck1" />
                      <label class="form-check-label" for="gridCheck1"> Show password </label>
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="img_name" class="col-sm-2 col-form-label">Image</label>
                  <div class="col-sm-10">
                    <input type="file" name="img_name" class="form-control" />
                    <?php if (!empty($row['img_name'])): ?>
                      <img src="imagess/<?php echo $row['img_name']; ?>" width="120" height="100" style="margin-top:10px;" />
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              

              <div class="card-footer">
                <button type="submit" name="update" class="btn btn-warning">Update</button>
                <a href="users.php" class="btn btn-danger float-end">Cancel</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </main>
  <?php include_once('footer.php'); ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/adminlte/dist/js/adminlte.js"></script>
<script>
function sho() {
  var a = document.getElementById('pass');
  if (a.type === "password") {
    a.type = "text";
  } else {
    a.type = "password";
  }
}
</script>
</body>
</html>
