<?php
include_once('dbconn.php');

// Get the ID from query string
$id = $_GET['id'] ?? '';

// Initialize variables
$nameErr = $emailErr = $phnumErr = $genderErr = $addressErr = $salaryErr = $dateErr = "";
$name = $email = $phnum = $gender = $address = $salary =
$successMsg = "";

// Fetch employee data for the given ID
$select = "SELECT * FROM employe WHERE id = '$id'";
$data = mysqli_query($conn, $select);
$row = mysqli_fetch_array($data);

if ($row) {
    $name = $row['name'];
    $email = $row['email'];
    $phnum = $row['phnum'];
    $gender = $row['gender'];
    $address = $row['address'];
    $salary = $row['salary'];
    // $updated_at = $row['updated_at'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['name'])) {
        $nameErr = "Name is required";
    } else {
        $name = htmlspecialchars($_POST['name']);
    }

    if (empty($_POST['phnum'])) {
        $phnumErr = "Phone number is required";
    } else {
        $phnum = htmlspecialchars($_POST['phnum']);
    }

    if (empty($_POST['gender'])) {
        $genderErr = "Gender is required";
    } else {
        $gender = $_POST['gender'];
    }

    if (empty($_POST['address'])) {
        $addressErr = "Address is required";
    } else {
        $address = htmlspecialchars($_POST['address']);
    }

    if (empty($_POST['salary'])) {
        $salaryErr = "Salary is required";
    } else {
        $salary = htmlspecialchars($_POST['salary']);
    }

    if (empty($_POST['updated_at'])) {
        $dateErr = "Date is required";
    } else {
        $updated_at = $_POST['updated_at'];
    }

    // If no errors, update the record
    if (
        $nameErr == "" && $phnumErr == "" && $genderErr == "" &&
        $addressErr == "" && $salaryErr == "" 
    ) {
        $update = "UPDATE employe SET name = '$name', gender = '$gender', phnum = '$phnum', address = '$address', salary = '$salary', updated_at = '$updated_at' WHERE id = '$id'";
        $data = mysqli_query($conn, $update);

        if ($data) {
            $successMsg = '<div class="alert alert-success m-2">Data updated successfully.</div>';
        } else {
            echo '<div class="alert alert-danger m-2">Database update failed.</div>';
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
            <div class="card card-warning card-outline mb-4">
              <div class="card-header"><div class="card-title">Update Employee Data</div></div>
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
                      <input type="email" name="email" readonly class="form-control" value="<?= htmlspecialchars($email) ?>" />
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

                  <!-- Updated At -->
                  <!-- <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Updated At</label>
                    <div class="col-sm-10">
                      <input type="date" name="updated_at" class="form-control" value="<?= htmlspecialchars($updated_at) ?>" />
                      <small style="color:red;"><?= $dateErr ?></small>
                    </div>
                  </div> -->
                </div>

                <div class="card-footer">
                  <button type="submit" name="update" class="btn btn-warning float-end">Update</button>
                  <a href="employee-data.php" class="btn btn-danger ">Cancel</a>
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
