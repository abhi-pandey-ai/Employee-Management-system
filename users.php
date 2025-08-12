<?php
    session_start();
    include 'dbconn.php';

    // Check if user is logged in
    if (!isset($_SESSION['SignIn']) || !isset($_SESSION['name'])) {
        header('Location: login.php');
        exit();
    }

    $username = $_SESSION['name']; // Logged-in user's name

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id = intval($_GET['id']);
        $query = "DELETE FROM forms WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        $deleted = mysqli_stmt_execute($stmt);

        if ($deleted) {
            echo '<div style="max-width: 400px;" class="alert alert-success m-2">Deleted successfully.</div>';
        } else {
            echo '<div class="alert alert-danger text-center m-3">This data could not be deleted. Please try again.</div>';
        }
    }

?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE v4 | Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
  <!-- Navbar -->
 

  <?php 
    include_once('header.php');
    include_once('sidebar.php'); 
  ?>
    <main class="app-main"> 
        <div class="app-content">
            <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Users Record Table</h3>
                        <div class="d-flex justify-content-between align-items-center mb-3 mt-3 text-end">
                        <form method="GET" action="" class="d-flex me-2">
                            <input type="text" name="Filter_value" class="form-control me-2" placeholder="Search by Name or Email"
                            value="<?php echo isset($_GET['Filter_value']) ? htmlspecialchars($_GET['Filter_value']) : ''; ?>">
                            <div class="col-auto">
                                <button type="submit" name="filter_btn" class="btn btn-primary">Search</button>
                            </div>
                        </form>
                        </div>

                        <a href="add-users.php" class="btn btn-danger">Add user</a>
                    </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover text-center align-middle">
                        <thead class="table-light">
                                <tr>
                                <th>S.no</th>
                                
                                <th>Name</th>
                                <th>Email</th>
                                <!-- <th>Password</th> -->
                                <th>Created-At</th>
                                <th colspan = "3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $limit = 10;
                            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                            $offset = ($page -1) * $limit;
                            $sno = $offset+1;
                            

                            if (isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value']))) {
                            $filter_value = mysqli_real_escape_string($conn, $_GET['Filter_value']);
                            $query = "SELECT * FROM forms WHERE name LIKE '%$filter_value%' OR email LIKE '%$filter_value%' LIMIT {$offset},{$limit}";
                            } else {
                                $query = "SELECT * FROM forms LIMIT {$offset},{$limit}";
                            }
                            $data = mysqli_query($conn, $query) or die("query Failed.");


                                if (mysqli_num_rows($data) > 0) {
                                    $alldata = mysqli_fetch_all($data, MYSQLI_ASSOC);
                                    foreach ($alldata as $row) {
                                        
                            ?>
                            
                            <tr>  
                                <td><?php echo $sno++?></td>
                                    <!-- <td>
                                    <?php if (!empty($row['img_name'])):?>
                                    <img src = "imagess/<?php echo $row['img_name'];?>"width="60"height="60" style="object-fit:cover; border-radius:5px;">
                                    <?php else: ?>
                                    no image
                                    <?php endif; ?>
                                </td> -->

                                <td class="text-start"><?php echo $row['name'] ?></td>
                                <td class="text-start"><?php echo $row['email']; ?></td>
                                <!-- comment a password section  -->
                                <!-- <td><?php echo $row['password']; ?></td> -->
                                <td><?php echo $row['reg_date'];?></td>

                                <td><a href="show_users.php?id=<?php echo $row['id']; ?>"><i class="bi bi-eye text-primary"></i></a></td>
                                <td><a href="user_edit.php?id=<?php echo $row['id']; ?>"><i class="bi bi-pencil text-success"></i></a></td>
                                <td><a onclick="return confirm('Are you sure you want to delete?')"  href="dashboard.php?id=<?php echo $row['id']; ?>"><i class="bi bi-trash text-danger"></i></a></td>    
                            </tr> 
                            <?php
                                    }
                                } else {
                            ?>
                            <tr>
                                <td colspan="4">No record found</td>
                            </tr>
                            <?php
                                }
                            ?>
                            
                        </tbody>
                    </table>
                    <?php
                            // Pagination total record count
                            if (isset($_GET['filter_btn']) && !empty(trim($_GET['Filter_value']))) {
                                $filter_value = mysqli_real_escape_string($conn, $_GET['Filter_value']);
                                $countQuery = "SELECT COUNT(*) as total FROM forms WHERE name LIKE '%$filter_value%' OR email LIKE '%$filter_value%'";
                            } else {
                                $countQuery = "SELECT COUNT(*) as total FROM forms";
                            }

                            $countResult = mysqli_query($conn, $countQuery);
                            $rowCount = mysqli_fetch_assoc($countResult);
                            $totalRecords = $rowCount['total'];
                            $totalPages = ceil($totalRecords / $limit);

                            // Generate pagination links
                            if ($totalPages > 1) {
                                echo '<nav><ul class="pagination justify-content-center">';
                                for ($i = 1; $i <= $totalPages; $i++) {
                                    $active = ($i == $page) ? 'active' : '';
                                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="users.php?page=' . $i;

                                    // maintain filter during pagination
                                    if (isset($_GET['filter_btn'])) {
                                        echo '&Filter_value=' . urlencode($_GET['Filter_value']) . '&filter_btn=';
                                    }

                                    echo '">' . $i . '</a></li>';
                                }
                                echo '</ul></nav>';
                            }
                            ?>

                </div>
            </div>
        </div>
    </main>
    
  <?php include 'footer.php';?>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/adminlte/dist/js/adminlte.js"></script>
</body>
</html>
