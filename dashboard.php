<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: url('images/hero_2.jpg') no-repeat center center fixed;
            background-size: cover;  
            font-family: 'Poppins', sans-serif;
            color: #e6edf3; /* Light text color */
            margin: 0;
            padding: 0;
        }

        .card {
            background-color: #2a2a2a;
            border: none;
        }
        .card-header {
            background-color: #333;
            color: #fff;
        }
        .btn-danger {
            background-color: #ff4d4d;
            border: none;
        }
        .btn-danger:hover {
            background-color: #e60000;
        }
        a {
            color: #1e90ff;
            text-decoration: none;
        }
        a:hover {
            color: #00bfff;
            text-decoration: underline;
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
        .modal-content {
            background-color: #161b22;
            color: #e6edf3;
            border-radius: 15px;
        }

        .modal-header {
            border-bottom: 1px solid #30363d;
        }

        .modal-footer {
            border-top: 1px solid #30363d;
        }
    </style>
</head>
<body>
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

<div class="container">
    <h1 class="text-center mb-4">Dashboard Admin</h1>

    <!-- Kelola Pengguna -->
    <div class="card mb-4">
        <div class="card-header">
            <h3>Kelola Pengguna</h3>
        </div>
        <div class="card-body">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "koneksi.php";
                    $query = "SELECT UserID, Username, Email, Role FROM user";
                    $result = mysqli_query($con, $query);

                    while ($user = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['UserID']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['Username']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['Email']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['Role']) . "</td>";
                        echo "<td><button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#confirmDeleteModal' data-userid='" . $user['UserID'] . "'>Hapus</button></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Kelola Galeri -->
    <div class="card">
        <div class="card-header">
            <h3>Kelola Galeri</h3>
        </div>
        <div class="card-body">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>ID Foto</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Album</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT foto.FotoID, foto.JudulFoto, foto.DeskripsiFoto, album.NamaAlbum 
                              FROM foto 
                              LEFT JOIN album ON foto.AlbumID = album.AlbumID";
                    $result = mysqli_query($con, $query);

                    while ($foto = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($foto['FotoID']) . "</td>";
                        echo "<td>" . htmlspecialchars($foto['JudulFoto']) . "</td>";
                        echo "<td>" . htmlspecialchars($foto['DeskripsiFoto']) . "</td>";
                        echo "<td>" . htmlspecialchars($foto['NamaAlbum']) . "</td>";
                        echo "<td><a href='delete-gallery.php?id=" . $foto['FotoID'] . "' class='btn btn-danger btn-sm'>Hapus</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah kamu yakin ingin menghapus data ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <a href="#" id="deleteBtn" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  // Menangani pengaturan URL Hapus berdasarkan UserID
  $('#confirmDeleteModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var userId = button.data('userid'); // Ambil UserID dari tombol yang ditekan
    var deleteUrl = 'delete-user.php?id=' + userId;
    $('#deleteBtn').attr('href', deleteUrl); // Set link Hapus dengan URL yang benar
  });
</script>



</body>
</html>
