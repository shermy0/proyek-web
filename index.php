<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GalleryPhoto</title>

  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <link rel="stylesheet" href="css/style-login.css">

</head>
<body>
  <div class="login-box">
    <!-- Header -->
    <div class="card-header">
      <b>Shutter</b>Keeper
    </div>

    <!-- Body -->
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <!-- Form -->
      <form action="ceklogin.php" method="post">
        <div class="input-group mb-3">
          <input name="Username" type="text" class="form-control" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="Password" type="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
      </form>

      <!-- Register Link -->
      <div class="register-text">
        <p>Belum punya akun? <a href="register.php" class="text-primary">Register disini</a>.</p>
        <a href="home.php?role=guest" class="text-primary">Masuk Sebagai Tamu</a>

      </div>
    </div>
  </div>
</body>
</html>
