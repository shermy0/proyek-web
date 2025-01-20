<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GalleryPhoto</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Bootstrap 4 -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<style>
body {
    background: url('images/hero_2.jpg') no-repeat center center fixed;
    background-size: cover;  
    font-family: 'Poppins', sans-serif;
    color: #e6edf3; /* Light text color */
    margin: 0;
    padding: 0;
}

.container-form{
    display: flex;
    margin: 80px;
    background: #161b22;
    border-radius: 15px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
    padding: 20px;
}

.form-control {
    background-color: #21262d;
    border: 1px solid #30363d;
    color: #e6edf3;
    border-radius: 5px;
    padding: 10px;
}

.form-control:focus {
    background-color: #1c2128;
    border-color: #58a6ff;
    box-shadow: none;
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
</style>
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

<div class="container-form mt-5">
    <div class="col-md-12">
    <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['NamaAlbum'])) {
    // Pastikan pengguna login dan UserID tersedia di sesi
    if (!isset($_SESSION['UserID'])) {
        echo "<script>alert('Anda harus login untuk menambah album.');</script>";
        exit;
    }

    $userID = $_SESSION['UserID']; // Ambil UserID dari sesi
    $namaAlbum = trim($_POST['NamaAlbum']);
    $deskripsiAlbum = trim($_POST['DeskripsiAlbum']);

    if (!empty($namaAlbum) && !empty($deskripsiAlbum)) {
        // Masukkan album ke database
        $query = "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) VALUES (?, ?, NOW(), ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssi", $namaAlbum, $deskripsiAlbum, $userID);

        if ($stmt->execute()) {
            echo "<script>alert('Album berhasil ditambahkan!');</script>";
        } else {
            echo "<script>alert('Gagal menyimpan album ke database.');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Nama album dan deskripsi tidak boleh kosong.');</script>";
    }
}
?>


<form action="submit-photo.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="LokasiFoto">Upload Foto:</label>
        <input type="file" name="LokasiFoto" id="LokasiFoto" accept="image/*" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="JudulFoto">Judul Foto:</label>
        <input type="text" name="JudulFoto" id="JudulFoto" class="form-control" placeholder="Judul Foto" required>
    </div>
    <div class="form-group">
        <label for="DeskripsiFoto">Deskripsi Foto:</label>
        <textarea name="DeskripsiFoto" id="DeskripsiFoto" class="form-control" rows="3" placeholder="Deskripsi" required></textarea>
    </div>
    <div class="form-group">
        <label for="TanggalUnggah">Tanggal Unggah:</label>
        <input type="date" name="TanggalUnggah" id="TanggalUnggah" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="AlbumID">Album:</label>
        <div class="d-flex">
            <select name="AlbumID" id="AlbumID" class="form-control mr-2" required>
                <?php
                $query = "SELECT AlbumID, NamaAlbum FROM album";
                $result = mysqli_query($con, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $row['AlbumID'] . '">' . htmlspecialchars($row['NamaAlbum']) . '</option>';
                }
                ?>
            </select>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addAlbumModal">Tambah Album</button>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<!-- Modal Tambah Album -->
<div class="modal fade" id="addAlbumModal" tabindex="-1" role="dialog" aria-labelledby="addAlbumModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAlbumModalLabel">Tambah Album</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" id="addAlbumForm">
          <div class="form-group">
            <label for="NamaAlbum">Nama Album:</label>
            <input type="text" name="NamaAlbum" id="NamaAlbum" class="form-control" placeholder="Nama Album" required>
          </div>
          <div class="form-group">
            <label for="DeskripsiAlbum">Deskripsi Album:</label>
            <textarea name="DeskripsiAlbum" id="DeskripsiAlbum" class="form-control" rows="3" placeholder="Deskripsi" required></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" form="addAlbumForm" class="btn btn-primary">Simpan</button>
      </div>
    </div>
  </div>
</div>


<!-- jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
