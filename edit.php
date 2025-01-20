<?php
session_start();
include "koneksi.php";

// Check if the 'id' parameter is provided
if (isset($_GET['id'])) {
    $FotoID = mysqli_real_escape_string($con, $_GET['id']);

    // Perform query with error handling
    $query = mysqli_query($con, "SELECT * FROM foto WHERE FotoID='$FotoID'") 
        or die("Query failed: " . mysqli_error($con));

    // Check if the query returned any rows
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
?>
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

.btn-primary {
    background-color: #238636;
    border: none;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background-color: #2ea043;
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
.back-button-container {
        margin: 20px;
        }

        .back-button {
        display: inline-flex;
        align-items: center;
        padding: 20px;
        color: #fff;
        text-decoration: none;
        font-size: 30px;
        border-radius: 100px;
        transition: background-color 0.3s ease;
        }



        .back-button:hover {
        background-color: #0056b3; /* Warna biru lebih gelap saat hover */
        }
</style>
</head>
<body>
<div class="back-button-container">
    
    <a href="portfolio.php" class="back-button">
    <i class="fas fa arrow-left"></i> <
    </a>
</div>
<div class="container">
<div class="container mt-5">
    <div class="col-md-12">
        <form action="update.php" method="POST" enctype="multipart/form-data">

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
            <input type="hidden" name="FotoID" value="<?php echo $FotoID; ?>">

            <div class="form-group">
                <label for="LokasiFoto">Upload Foto:</label>
                <input type="file" name="LokasiFoto" id="LokasiFoto" accept="image/*" class="form-control">
                <small class="text-muted">File sebelumnya: <?php echo $row['LokasiFoto']; ?></small>
                <input type="hidden" name="LokasiFotoLama" value="<?php echo $row['LokasiFoto']; ?>">
            </div>

            <div class="form-group">
                <label for="JudulFoto">Judul Foto:</label>
                <input type="text" name="JudulFoto" value="<?php echo htmlspecialchars($row['JudulFoto']); ?>" id="JudulFoto" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="DeskripsiFoto">Deskripsi Foto:</label>
                <textarea name="DeskripsiFoto" id="DeskripsiFoto" class="form-control" rows="3" required><?php echo htmlspecialchars($row['DeskripsiFoto']); ?></textarea>
            </div>

            <div class="form-group">
                <label for="TanggalUnggah">Tanggal Unggah:</label>
                <input type="date" name="TanggalUnggah" value="<?php echo htmlspecialchars($row['TanggalUnggah']); ?>" id="TanggalUnggah" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="AlbumID">Album:</label>
                <div class="d-flex">
                    <select name="AlbumID" id="AlbumID" class="form-control mr-2" required>
                        <?php
                        $albumQuery = "SELECT AlbumID, NamaAlbum FROM album";
                        $albumResult = mysqli_query($con, $albumQuery);

                        while ($albumRow = mysqli_fetch_assoc($albumResult)) {
                            echo '<option value="' . $albumRow['AlbumID'] . '">' . htmlspecialchars($albumRow['NamaAlbum']) . '</option>';
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
    </div>
</div>

<!-- jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
    } else {
        // If no rows are found, display a message
        echo "No record found for FotoID: " . htmlspecialchars($FotoID);
    }
} else {
    // If 'id' parameter is not set, display an error message
    echo "Invalid request: FotoID not provided.";
}
?>
