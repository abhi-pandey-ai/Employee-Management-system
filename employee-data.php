<!doctype html>
<html lang="en">
<head>
<!-- pagination styling  -->
 <meta name="viewport" content="width=device-width, initial-scale=1">



  <meta charset="utf-8" /> 
  <title>Employee Detail</title>
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0" /> -->
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include_once('sidebar.php'); ?>
    <main class="app-main">
      <div class="app-content">
        <?php
          include_once('dbconn.php');
          include_once('header.php');
          // include_once('sidebar.php');
        ?>
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Employee Details</h3>
             <div class="d-flex justify-content-between align-items-center mb-3 mt-3 text-end">
              <form method="GET" action="" class="d-flex me-2">
                <input type="text" name="Filter_value" class="form-control me-2" placeholder="Search by Name or Email"
                  value="<?php echo isset($_GET['Filter_value']) ? htmlspecialchars($_GET['Filter_value']) : ''; ?>">
                  <div class="col-auto">
                     <button type="submit" name="filter_btn" class="btn btn-primary">Search</button>
                  </div>
              </form>
            </div>

            <a href="employee-entry.php" class="btn btn-danger">Add Employee</a>
          </div>
           

          <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
              <thead class="table-light">
                <tr>
                  <th>Sno</th>
                  <!-- <th>Image</th> -->
                  <th>Name</th>
                  <th>Email</th>
                  <th>Gender</th>
                  <th>Ph-no</th>
                  <th>Address</th>
                  <th>Salary</th>
                  <th>Created</th>
                  <th>Updated</th>
                  <th colspan="2">Muntiple Actions.</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $limit = 10;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

                $offset = ($page -1) * $limit;
                $sno = $offset+ 1;
               if (isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value']))) {
                $filter_value = mysqli_real_escape_string($conn, $_GET['Filter_value']);
                $query = "SELECT * FROM employe WHERE name LIKE '%$filter_value%' OR email LIKE '%$filter_value%' LIMIT {$offset},{$limit}";
              } else {
                $query = "SELECT * FROM employe LIMIT {$offset},{$limit}";
              }

                $data = mysqli_query($conn, $query) or die("Query Failed.");

                if (mysqli_num_rows($data) > 0) {
                  $alldata = mysqli_fetch_all($data, MYSQLI_ASSOC);
                  foreach ($alldata as $row) {
                ?>
                    <tr>
                      <td><?php echo $sno++; ?></td>
                      <!-- <td>
                        <img src="imagess/<?php echo $row['img_name']; ?>" alt="Employee Image" width="50" height="50">
                      </td> -->
                      <td class="text-start"><?php echo $row['name']; ?></td>
                      <td class="text-start"><?php echo $row['email']; ?></td>
                      <td class="text-start"><?php echo $row['gender']; ?></td>
                      <td class="text-start"><?php echo $row['phnum']; ?></td>
                      <td class="text-start"><?php echo $row['address']; ?></td>
                      <td class="text-start"><?php echo $row['salary']; ?></td>
                      <td class="text-start"><?php echo $row['created_at']; ?></td>
                      <td class="text-start"><?php echo $row['updated_at']; ?></td>
                      <td>
                        <a href="employee-data-edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Update</a>
                      </td>
                      <td>
                        <a href="add-employee-attendence.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">Attendence</a>
                      </td>
                      <!-- <td>
                        <a href="add-employee-attendence.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary"><i class="bi bi-envelope-arrow-up "></i></a>
                      </td> -->
                    </tr>
                <?php
                  }
                } else {
                ?>
                  <tr>
                    <td colspan="10">No record found</td>
                  </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
           <?php
             if (isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value']))) {
              $filter_value = mysqli_real_escape_string($conn, $_GET['Filter_value']);
              $sql1 = "SELECT * FROM employe WHERE name LIKE '%$filter_value%' OR email LIKE '%$filter_value%'";
            } else {
              $sql1 = "SELECT * FROM employe";
            }

              $result1 = mysqli_query($conn, $sql1) or die("Query Failed.");

              if (mysqli_num_rows($result1) > 0) {
                $total_records = mysqli_num_rows($result1);
                $total_page = ceil($total_records / $limit);

                echo '<nav><ul class="pagination justify-content-center">';
                for ($i = 1; $i <= $total_page; $i++) {
                  $active = ($i == $page) ? 'active' : '';
                  echo '<li class="page-item ' . $active . '">
                          <a class="page-link" href="employee-data.php?page=' . $i . '">' . $i . '</a>
                        </li>';
                }
                echo '</ul></nav>';
              }
            ?>

        </div>
      </div>
    </main>
    
    <?php include_once('footer.php'); ?>
  </div>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/adminlte/dist/js/adminlte.js"></script>

        </ul>
      </div>

</body>
</html>
