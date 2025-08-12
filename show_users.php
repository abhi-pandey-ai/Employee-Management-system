<?php
    include_once('dbconn.php');

    if(isset($_GET['id'])&& is_numeric($_GET['id'])){
      $id = mysqli_real_escape_string($conn,$_GET['id']);
    }else{
      echo "<div class='alert-danger'>missing URL.</div>";
      exit();
    }
    // data update ho rhaa hai but naya wala data show nji ho rhaa hai uske liye dubra select query , 
    $select = "SELECT * FROM forms WHERE id = '$id'";
    $data = mysqli_query($conn,$select);
    $row = mysqli_fetch_array($data);
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
        
        <div class="col-md-12">
          <div class="card card-warning card-outline mb-4" style="">
            <div class="card-header"><div class="card-title">  Users Information </div></div>
            <form method="GET" enctype="multipart/form-data">
              <div class="card-body">
                <div class="row mb-3">
                  <label for="name" class="col-sm-2 col-form-label">Name</label>
                  <div class="col-sm-10">
                    <input value="<?php echo $row['name'] ?>" type="text" name="name" readonly class="form-control" id="name" />
                  </div>
                </div>

                <div class="row mb-3">
                  <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input value="<?php echo $row['email'] ?>" type="email" name="email" readonly class="form-control" id="inputEmail3" />
                  </div>
                 
                </div>

                <div class="row mb-3">
                  <label for="pass" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input value="<?php echo $row['password'] ?>" type="password" name="password" readonly class="form-control" id="pass" />
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
                      <img src="imagess/<?php echo $row['img_name']; ?>" width="120" height="100" readonly style="margin-top:10px;" />
                    <?php endif; ?>
                  </div>
                </div>
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
