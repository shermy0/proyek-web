<!doctype html>
<html lang="en">

  <head>
    <title>Noxen &mdash; Website Template by Colorlib</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <link rel="stylesheet" href="css/owl.theme.default.min.css">
    <link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>

      body{
        background-color:rgb(0, 0, 0);
      }
      h2{
        color:rgb(255, 255, 255);
      }
      footer{
        background-color: rgb(0, 0, 0);
      }
      .btn-primary{
        background-color:  rgb(24, 147, 36);
      }
       .navbar {
        background-color:rgba(22, 27, 34, 0.6);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 20px 0;
        position: sticky;
        top: 0;
        z-index: 10;
        }

        .navbar-brand img {
            max-height: 50px;
        }

        .navbar-nav {
            margin-left: auto;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-link {
            font-weight: 600;
            font-size: 18px;
            color:rgb(49, 117, 195) !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color:rgb(213, 223, 236) !important;
        }
        a{
          color:rgb(49, 117, 195);
        }
    </style>

  </head>

  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">

    
   <!-- Navbar -->
   <?php
     session_start();
    include "koneksi.php"; // Pastikan file koneksi disertakan
    if (isset($_SESSION['Username'])) {
        $username = $_SESSION['Username'];
        $query = "SELECT profile_pic FROM user WHERE Username = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $profilePic = $user['profile_pic'] ?? 'default.png'; // Jika profile_pic kosong, gunakan default.png
        $stmt->close();
    }
    ?>
    <?php
    if (isset($_GET['role']) && $_GET['role'] === 'guest') {
        $_SESSION['Role'] = 'guest';
    }
    ?>

     <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="images/logo.png" alt="Logo">ShutterKeeper
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="portfolio.php">Portfolio</a>
                    </li>
                    <li class="nav-item">
                        <?php if (isset($_SESSION['Username'])): ?>
                            <a class="nav-link" href="upload-photo.php">Upload</a>
                        <?php else: ?>
                            <a class="nav-link" href="home.php" onclick="alert('Silakan login terlebih dahulu!')">Upload</a>
                        <?php endif; ?>
                    </li>
                    <li>
                    <?php if (isset($_SESSION['Role']) && $_SESSION['Role'] === 'admin'): ?>
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    <?php endif; ?>
                    </li>                    
                </ul>

                <?php if (isset($_SESSION['Username'])) { ?>
                    <a class="nav-link dropdown-toggle text-white" href="profile.php" id="userProfileDropdown">
                        <img src="images/<?php echo htmlspecialchars($profilePic); ?>" class="rounded-circle" width="35" height="35" alt="Profile">
                    </a>
                <?php } else { ?>
                    <a href="index.php" class="btn btn-outline-primary ml-3">Sign In</a>
                    <a href="register.php" class="btn btn-primary ml-3">Sign Up</a>
                <?php } ?>
            </div>
        </div>
    </nav>
      </header>

    <div class="ftco-blocks-cover-1">
      <div class="site-section-cover overlay" data-stellar-background-ratio="0.5" style="background-image: url('images/camera.png')">
        <div class="container">
          <div class="row align-items-center ">
            <div class="col-md-5 mt-5 pt-5">
              <h1 class="mb-3">Selamat Datang di ShutterKeeper</h1>
              <p>Menyimpan, mengatur, dan membagikan kenangan Anda kini lebih mudah.</p>
              <p class="mt-5"><a href="portfolio.php" class="btn btn-primary">Let's Go!</a></p>
            </div>
            <div class="col-md-6 ml-auto">
              <div class="white-dots">
                <img src="images/img_2.jpg" alt="" class="img-fluid">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="site-section">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-md-7 mb-5">
            <h2>A creative digital agency with excellence services</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="feature-1">
              <span class="wrap-icon">
                <span class="icon-photo"></span>
              </span>
              <h3>Organisasi Foto</h3>
              <p>Kelompokkan foto Anda berdasarkan acara, tema, atau kategori lainnya.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="feature-1">
              <span class="wrap-icon">
                <span class="icon-face"></span>
              </span>
              <h3>Bagikan Dengan Kerabat Anda</h3>
              <p>Bagikan album atau foto dengan teman dan keluarga dalam hitungan detik.</p>
            </div>
          </div>
          <div class="col-md-6 col-lg-4 mb-4 mb-lg-0">
            <div class="feature-1">
              <span class="wrap-icon">
                <span class="icon-camera"></span>
              </span>
              <h3>Inspirasi untuk Fotografi</h3>
              <p>Website ini memberikan akses ke berbagai galeri foto berkualitas tinggi yang dapat menjadi inspirasi bagi para fotografer, baik pemula maupun profesional.</p>
            </div>
          </div>
        </div>
      </div>
    </div>





    <div class="site-section section-3" data-stellar-background-ratio="0.5" style="background-image: url('images/hero_2.jpg');">
      <div class="container">
        <div class="row justify-content-center text-center">
          <div class="col-7 text-center mb-5">
          <h2 class="text-white">Mulai Sekarang</h2>

            <p class="lead text-white">Siap untuk menjaga kenangan Anda? </p>
          </div>
        </div>
        
      </div>
    </div>




    

    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/jquery-migrate-3.0.0.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/jquery.waypoints.min.js"></script>
    <script src="js/jquery.animateNumber.min.js"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.stellar.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
    <script src="js/aos.js"></script>

    <script src="js/main.js"></script>

  </body>

</html>

