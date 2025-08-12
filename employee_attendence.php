<?php
include_once('dbconn.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Attendance Table</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap & AdminLTE CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php 
      include_once('header.php');
      include_once('sidebar.php');
    ?>

    <main class="app-main">   
      <div class="container mt-4">   
        <h3 class="mb-4">Attendance Table</h3>

        <!-- Search Form -->
        <form action="" method="GET" class="mb-3">
          <div class="input-group">
            <input type="text" name="Filter_value" class="form-control" placeholder="Search by Employee ID"
              value="<?php echo isset($_GET['Filter_value']) ? htmlspecialchars($_GET['Filter_value']) : ''; ?>">
            <button type="submit" name="filter_btn" class="btn btn-primary">Search</button>
          </div>
        </form>

        <!-- Attendance Table -->
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
                $limit = 7;
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
          if (isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value']))) {
            $filter_value = mysqli_real_escape_string($conn, $_GET['Filter_value']);
            $sql1 = "SELECT * FROM attendence WHERE emp_id LIKE '%$filter_value%'";
          } else {
            $sql1 = "SELECT * FROM attendence";
          }

          $result1 = mysqli_query($conn, $sql1) or die("Query Failed.");

          if (mysqli_num_rows($result1) > 0) {
            $total_records = mysqli_num_rows($result1);
            $total_page = ceil($total_records / $limit);

            echo '<nav><ul class="pagination justify-content-center">';
            for ($i = 1; $i <= $total_page; $i++) {
              $active = ($i == $page) ? 'active' : '';
              echo "<li class='page-item $active'>
                      <a class='page-link' href='employee_attendence.php?page=$i'>" . $i . "</a>
                    </li>";
            }
            echo '</ul></nav>';
          }
        ?>
      </div>
    </main>

    <?php include_once('footer.php'); ?>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="/adminlte/dist/js/adminlte.js"></script>
</body>
</html>
