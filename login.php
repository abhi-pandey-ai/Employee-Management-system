<?php
session_start();
include 'dbconn.php';

$errors = [];

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        $query = "SELECT * FROM forms WHERE email = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) === 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['SignIn'] = true;
            $_SESSION['email'] = $row['email'];
            $_SESSION['name'] = $row['name'];
            header("Location: /adminlte/users.php");
            exit;
        } else {
            $errors['invalid'] = "Please enter valid credentials.";
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>AdminLTE 4 | Login Page</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="/adminlte/dist/css/adminlte.css" />
  <style>
    .text-danger-star {
      color: red;
      margin-left: 4px;
    }
  </style>
</head>
<body class="login-page bg-body-secondary">
  <div class="login-box">
    <div class="login-logo">
      <a href="#"><b>Admin</b>LTE</a>
    </div>

    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <!-- Display generic required field message -->
        <?php if (!empty($errors)) : ?>
          <div class="text-danger mb-3 fw-bold">* required field</div>
        <?php endif; ?>

        <!-- Invalid credentials error -->
        <?php if (isset($errors['invalid'])) : ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo $errors['invalid']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        <?php endif; ?>

        <form method="post" action="">
          <!-- Email -->
          <div class="input-group mb-3">
            
            <input type="email" name="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
            <div class="input-group-text"><span class="bi bi-envelope"></span></div>
            <?php if (isset($errors['email'])) : ?>
              <div class="invalid-feedback d-block"><?php echo $errors['email']; ?></div>
            <?php endif; ?>
          </div>

          <!-- Password -->
          <div class="input-group mb-3">
            
            <input type="password" name="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" placeholder="Password" />
            <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
            <?php if (isset($errors['password'])) : ?>
              <div class="invalid-feedback d-block"><?php echo $errors['password']; ?></div>
            <?php endif; ?>
          </div>

          <div class="row">
            <div class="col-8">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" />
                <label class="form-check-label" for="remember">Remember Me</label>
              </div>
            </div>
            <div class="col-4">
              <div class="d-grid gap-2">
                <button type="submit" name="submit" class="btn btn-primary">Sign In</button>
              </div>
            </div>
          </div>
        </form>

        <div class="social-auth-links text-center mb-3 d-grid gap-2 mt-3">
          <p>- OR -</p>
          <a href="#" class="btn btn-primary"><i class="bi bi-facebook me-2"></i> Sign in using Facebook</a>
          <a href="#" class="btn btn-danger"><i class="bi bi-google me-2"></i> Sign in using Google+</a>
        </div>

        <p class="mb-0">
          <a href="register.php" class="text-center">Register a new membership</a>
        </p>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
