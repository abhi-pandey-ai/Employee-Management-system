<?php
include_once('dbconn.php');

$nameErr = $emailErr = $phnumErr = $genderErr = $addressErr = $salaryErr = $dateErr = "";
$name = $email = $phnum = $gender = $address = $salary = $created_at = "";
$successMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {

  // Name
  if (empty($_POST['name'])) {
    $nameErr = "Name is required";
  } else {
    $name = htmlspecialchars($_POST['name']);
  }

  // Email
  if (empty($_POST['email'])) {
    $emailErr = "Email is required";
  } else {
    $email = htmlspecialchars($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }
  }

  // Phone number
  if (empty($_POST['phnum'])) {
    $phnumErr = "Phone number is required";
  } else {
    $phnum = htmlspecialchars($_POST['phnum']);
  }

  // Gender
  if (empty($_POST['gender'])) {
    $genderErr = "Gender is required";
  } else {
    $gender = $_POST['gender'];
  }

  // Address
  if (empty($_POST['address'])) {
    $addressErr = "Address is required";
  } else {
    $address = htmlspecialchars($_POST['address']);
  }

  // Salary
  if (empty($_POST['salary'])) {
    $salaryErr = "Salary is required";
  } else {
    $salary = htmlspecialchars($_POST['salary']);
  }

  // Created at
  // if (empty($_POST['created_at'])) {
  //   $dateErr = "Date is required";
  // } else {
  //   $created_at = $_POST['created_at'];
  // }

  // Check for duplicate email or phone
  if ($emailErr == "" && $phnumErr == "") {
    $checkQuery = "SELECT * FROM employe WHERE email = '$email' OR phnum = '$phnum'";
    $checkResult = mysqli_query($conn, $checkQuery);
    if (mysqli_num_rows($checkResult) > 0) {
      while ($existing = mysqli_fetch_assoc($checkResult)) {
        if ($existing['email'] == $email) {
          $emailErr = "Email already exists";
        }
        if ($existing['phnum'] == $phnum) {
          $phnumErr = "Phone number already exists";
        }
      }
    }
  }

  // Insert if no errors
  if (
    $nameErr == "" && $emailErr == "" && $phnumErr == "" &&
    $genderErr == "" && $addressErr == "" && $salaryErr == "" && $dateErr == ""
  ) {
    $query = "INSERT INTO employe (name,email,phnum,gender,address,salary,created_at)
              VALUES ('$name','$email','$phnum','$gender','$address','$salary','$created_at')";
    $data = mysqli_query($conn, $query);

    if ($data) {
      $successMsg = '<div class="alert alert-success m-2">Data inserted successfully.</div>';
      $name = $email = $phnum = $gender = $address = $salary = $created_at = "";
    } else {
      echo '<div class="alert alert-danger m-2">Database insert failed.</div>';
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

        <?= $successMsg ?>

        <div class="col-md-12">
          <div class="card card-warning card-outline mb-4">
            <div class="card-header"><div class="card-title">Add Employee New Entry</div></div>
            <form method="POST">
              <div class="card-body">

                <!-- Name -->
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

                <!-- Phone -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Number</label>
                  <div class="col-sm-10">
                    <input type="number" name="phnum" class="form-control" value="<?= htmlspecialchars($phnum) ?>" />
                    <small style="color:red;"><?= $phnumErr ?></small>
                  </div>
                </div>

                <!-- Gender -->
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Gender</legend>
                  <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="gender" value="male" <?= ($gender == 'male') ? 'checked' : '' ?> />
                      <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="gender" value="female" <?= ($gender == 'female') ? 'checked' : '' ?> />
                      <label class="form-check-label">Female</label>
                    </div>
                    <small style="color:red;"><?= $genderErr ?></small>
                  </div>
                </fieldset>

                <!-- Address -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Address</label>
                  <div class="col-sm-10">
                    <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($address) ?>" />
                    <small style="color:red;"><?= $addressErr ?></small>
                  </div>
                </div>

                <!-- Salary -->
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Salary</label>
                  <div class="col-sm-10">
                    <input type="text" name="salary" class="form-control" value="<?= htmlspecialchars($salary) ?>" />
                    <small style="color:red;"><?= $salaryErr ?></small>
                  </div>
                </div>

                <!-- Created At -->
                <!-- <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Created At</label>
                  <div class="col-sm-10">
                    <input type="date" name="created_at" class="form-control" value="<?= htmlspecialchars($created_at) ?>" />
                    <small style="color:red;"><?= $dateErr ?></small>
                  </div>
                </div> -->

              </div>

              <div class="card-footer">
                <button type="submit" name="submit" class="btn btn-warning float-end">Add Entry</button>
                <a href="employee-data.php" class="btn btn-danger">Cancel</a>
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
