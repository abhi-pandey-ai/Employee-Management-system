<?php
include_once('dbconn.php');

// Sanitize input
function test_input($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

$emp_idErr = $dateErr = $statusErr = "";
$emp_id = $date = $status = "";
$alertMsg = "";

// Get employee ID from URL if available
$id = $_GET['id'] ?? '';
$row = ['id' => '', 'name' => 'N/A'];

if (!empty($id)) {
  $select = "SELECT * FROM employe WHERE id = '$id'";
  $result = mysqli_query($conn, $select);
  if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_array($result);
  }
}

// Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
  $emp_id = $id;

  if (empty($_POST['date'])) {
    $dateErr = "Please select date";
  } else {
    $date = test_input($_POST["date"]);
  }

  if (empty($_POST['status'])) {
    $statusErr = "Please select status";
  } else {
    $status = test_input($_POST["status"]);
  }

  if ($dateErr == "" && $statusErr == "") {
    $query = "INSERT INTO attendence (emp_id, date, status) VALUES ('$emp_id', '$date', '$status')";
    $insert = mysqli_query($conn, $query);

    if ($insert) {
      $alertMsg = "<div class='alert alert-success text-center'>Attendance added successfully!</div>";
      $date = $status = "";
    } else {
      $alertMsg = "<div class='alert alert-danger text-center'>Error inserting data.</div>";
    }
  }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Employee Attendance Entry</title>
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
        <div class="col-md-12">

          <?= $alertMsg ?>

          <!-- Attendance Entry Form -->
          <div class="card card-warning card-outline mt-4">
            <div class="card-header">
              <h3 class="card-title">Employee Attendance Entry</h3>
            </div>

            <form method="POST">
              <div class="card-body">
                <input type="hidden" name="emp_id" value="<?= $row['id'] ?>">

                <div class="row g-3">
                  <div class="col-md-4">
                    <label class="form-label">Employee Name</label>
                    <input type="text" class="form-control" value="<?= $row['name'] ?>" disabled>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label">Attendance Date</label>
                    <input type="date" class="form-control" name="date" 
                      value="<?= htmlspecialchars($date ?: date('Y-m-d')) ?>" />
                    <span class="text-danger"><?= $dateErr ?></span>
                  </div>

                  <div class="col-md-4">
                    <label class="form-label d-block">Status</label>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" value="present" id="present"
                        <?= ($status == 'present') ? 'checked' : '' ?>>
                      <label class="form-check-label" for="present">Present</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" value="absent" id="absent"
                        <?= ($status == 'absent') ? 'checked' : '' ?>>
                      <label class="form-check-label" for="absent">Absent</label>
                    </div>
                    <span class="text-danger d-block"><?= $statusErr ?></span>
                  </div>
                </div>
              </div>

              <div class="card-footer text-end">
                <button type="submit" name="submit" class="btn btn-warning">Submit</button>
                <!-- <a href="employee_attendence.php" class="btn btn-success">Employee Data</a> -->
              </div>
            </form>
          </div>

          <!-- Attendance Table Below -->
          <div class="card mt-5">
            <div class="card-header">
              <h4>Attendance Table</h4>
            </div>
            <div class="card-body">

              <!-- Search Form -->
              <form method="GET" class="mb-3">
                <div class="input-group">
                  <input type="text" name="Filter_value" class="form-control" placeholder="Search by Employee ID"
                    value="<?= isset($_GET['Filter_value']) ? htmlspecialchars($_GET['Filter_value']) : '' ?>">
                  <button type="submit" name="filter_btn" class="btn btn-primary">Search</button>
                </div>
              </form>

              <!-- Table -->
              <div class="table-responsive">
                <table class="table table-bordered table-hover text-center align-middle">
                  <thead class="table-dark">
                    <tr>
                      <th>Serial Number</th>
                      <th>Employee ID</th>
                      <th>Attendance Date</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $limit = 10;
                      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                      $offset = ($page - 1) * $limit;
                      $sno = $offset + 1;

                      if (isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value']))) {
                        $filter_value = mysqli_real_escape_string($conn, $_GET['Filter_value']);
                        $query = "SELECT * FROM attendence WHERE emp_id LIKE '%$filter_value%' LIMIT {$offset}, {$limit}";
                      } else {
                        $query = "SELECT * FROM attendence LIMIT {$offset}, {$limit}";
                      }

                      $data = mysqli_query($conn, $query) or die("Query Failed.");

                      if (mysqli_num_rows($data) > 0) {
                        while ($row = mysqli_fetch_assoc($data)) {
                          echo "<tr>
                                  <td>{$sno}</td>
                                  <td>{$row['emp_id']}</td>
                                  <td>{$row['date']}</td>
                                  <td>{$row['status']}</td>
                                </tr>";
                          $sno++;
                        }
                      } else {
                        echo "<tr><td colspan='4'>No Record Found</td></tr>";
                      }
                    ?>
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              <?php
                $count_query = isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value'])) ?
                  "SELECT COUNT(*) FROM attendence WHERE emp_id LIKE '%$filter_value%'" :
                  "SELECT COUNT(*) FROM attendence";

                $count_result = mysqli_query($conn, $count_query);
                $total_records = mysqli_fetch_row($count_result)[0];
                $total_pages = ceil($total_records / $limit);

                if ($total_pages > 1) {
                  echo '<nav><ul class="pagination justify-content-center">';
                  for ($i = 1; $i <= $total_pages; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo "<li class='page-item $active'>
                            <a class='page-link' href='?page=$i'>" . $i . "</a>
                          </li>";
                  }
                  echo '</ul></nav>';
                }
              ?>
            </div>
          </div>

        </div>
      </div>
    </div>
  </main>

  <?php include_once('footer.php'); ?> 
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></sc
