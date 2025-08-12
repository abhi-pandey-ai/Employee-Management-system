<?php
include_once('dbconn.php');

$nameErr = $emailErr = $passwordErr = $dateErr = "";
$name = $email = $password = $reg_date = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

  // Name validation
  if (empty($_POST['name'])) {
    $nameErr = "Name is required";
  } else {
    $name = htmlspecialchars($_POST['name']);
  }

  // Email validation
  if (empty($_POST['email'])) {
    $emailErr = "Email is required";
  } else {
    $email = htmlspecialchars($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }

  // Password validation
  if (empty($_POST['password'])) {
    $passwordErr = "Password is required";
  } else {
    $password = htmlspecialchars($_POST['password']);
  }

  // Set registration date
  $reg_date = date("Y-m-d");

  // Check for duplicate email if no email error
  if ($emailErr == "") {
    $checkQuery = "SELECT * FROM forms WHERE email = '$email'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
      $emailErr = "This user already exists";
    }
  }

  // If no validation errors, insert into database
  if ($nameErr == "" && $emailErr == "" && $passwordErr == "" && $dateErr == "") {
    $query = "INSERT INTO forms (name,email,password,reg_date) VALUES('$name','$email','$password','$reg_date')";
    $data = mysqli_query($conn, $query);

    if ($data) {
      $successMsg = '<div class="alert alert-success m-2">Data inserted successfully.</div>';
      $name = $email = $password = $reg_date = "";
    } else {
      echo '<div class="alert alert-danger m-2">Database insert failed: ' . mysqli_error($conn) . '</div>';
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE v4 | Add Employee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS -->
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
          <div class="col-md-12">

            <?= $successMsg ?>
            
              <div class="card card-warning card-outline mb-4">
                <div class="card-header"><div class="card-title">Add users</div></div>
                <form method="POST">
                  <div class="card-body">

                  <div class="row mb-3">
                      <label class="col-sm-2 col-form-label">Name</label>
                      <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>" />
                        <small style="color:red;"><?= $nameErr ?></small>
                      </div>
                    </div>

                    <!-- Email -->
                    <div class="row mb-3">
                      <label class="col-sm-2 col-form-label">Email</label>
                      <div class="col-sm-10">
                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" />
                        <small style="color:red;"><?= $emailErr ?></small>
                      </div>
                    </div>

                    <!-- password  -->
                      <div class="row mb-3">
                      <label class="col-sm-2 col-form-label">password</label>
                      <div class="col-sm-10">
                        <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($password) ?>" />
                        <small style="color:red;"><?= $passwordErr ?></small>
                      </div>
                    </div>

                  </div>

                  <div class="card-footer">
                    <button type="submit" name="submit" class="btn btn-warning float-end">Add Entry</button>
                    <a href="users.php" class="btn btn-danger">Cancel</a>
                  </div>
                </form>
              </div>
        </div>

        </div>
      </div>
   </main>  
  <?php include_once('footer.php'); ?>
</div>
</body>
</html>  
