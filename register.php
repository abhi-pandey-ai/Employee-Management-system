

<?php
include 'dbconn.php';
$errors = [];

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $fname = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate name
    if (empty($fname)) {
        $errors['name'] = "Name is required.";
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 4) {
        $errors['password'] = "Password must be at least 4 characters.";
    }

    if (empty($errors)) {
        $check_query = "SELECT * FROM forms WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $errors['email'] = "This email is already registered.";
        } else {
            $insert_query = "INSERT INTO forms (name, email, password) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sss", $fname, $email, $password);
            $success = mysqli_stmt_execute($stmt);

            if ($success) {
                echo '<div class="alert alert-success" role="alert">
                        <h4 class="alert-heading">Success!</h4>
                        <p>You have successfully registered TO AdminLTE.</p>
                        <hr>
                      </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
                        <strong>Error!</strong> Could not register user.
                      </div>';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>AdminLTE 4 | Register Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Styles -->
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

  <body class="register-page bg-body-secondary">
    <div class="register-box">
      <div class="register-logo">
        <a href="#"><b>Admin</b>LTE</a>
      </div>

      <div class="card">
        <div class="card-body register-card-body">
          <p class="register-box-msg">Register a new membership</p>

          <!-- Show general message if there are any errors -->
          <?php if (!empty($errors)) : ?>
            <div class="text-danger mb-3 fw-bold">
              * required field
            </div>
          <?php endif; ?>

          <form method="POST">
            <!-- Name -->
            <div class="input-group mb-3">
              
              <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" name="name" placeholder="Full Name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" />
              <div class="input-group-text"><span class="bi bi-person"></span></div>
              <?php if (isset($errors['name'])) : ?>
                <div class="invalid-feedback d-block">
                  <?php echo $errors['name']; ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="input-group mb-3">
              
              <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
              <div class="input-group-text"><span class="bi bi-envelope"></span></div>
              <?php if (isset($errors['email'])) : ?>
                <div class="invalid-feedback d-block">
                  <?php echo $errors['email']; ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="input-group mb-3">
              
              <input type="password" class="form-control <?php echo isset($errors['password']) ? 'is-invalid' : ''; ?>" name="password" placeholder="Password" />
              <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
              <?php if (isset($errors['password'])) : ?>
                <div class="invalid-feedback d-block">
                  <?php echo $errors['password']; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="row">
              <div class="col-8">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="terms" />
                  <label class="form-check-label" for="terms">
                    I agree to the <a href="#">terms</a>
                  </label>
                </div>
              </div>
              <div class="col-4">
                <div class="d-grid">
                  <button type="submit" name="submit" class="btn btn-primary">Sign Up</button>
                </div>
              </div>
            </div>
          </form>

          <div class="social-auth-links text-center mb-3 d-grid gap-2 mt-3">
            <p>- OR -</p>
            <a href="#" class="btn btn-primary">
              <i class="bi bi-facebook me-2"></i> Sign in using Facebook
            </a>
            <a href="#" class="btn btn-danger">
              <i class="bi bi-google me-2"></i> Sign in using Google+
            </a>
          </div>

          <p class="mb-0">
            <a href="login.php" class="text-center">I already have a membership</a>
          </p>
        </div>
      </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
