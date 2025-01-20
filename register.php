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
      <p class="login-box-msg">Sign up to create your account</p>

      <!-- Form -->
      <form action="submit-register.php" method="post">
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
        <div class="input-group mb-3">
          <input name="Email" type="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="NamaLengkap" type="text" class="form-control" placeholder="Nama Lengkap" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-keyboard"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="Alamat" type="text" class="form-control" placeholder="Alamat" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-location-dot"></span>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
      </form>

      <!-- Register Link -->
      <div class="register-text">
        <p>Sudah punya akun? <a href="index.php" class="text-primary">Login disini</a>.</p>
        <a href="home.php" class="text-primary">Masuk Sebagai Tamu</a>.

        <!-- feedback -->
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Akun berhasil dibuat! Silakan login.
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                Gagal membuat akun. Silakan coba lagi.
            </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</body>
</html>
