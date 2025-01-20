<?php
session_start();
include "koneksi.php";

// Ambil data foto berdasarkan ID
$fotoID = intval($_GET['id']);
$query = "SELECT foto.LokasiFoto, foto.JudulFoto, foto.DeskripsiFoto, album.NamaAlbum, user.Username, user.profile_pic 
          FROM foto 
          LEFT JOIN album ON foto.AlbumID = album.AlbumID
          LEFT JOIN user ON foto.UserID = user.UserID
          WHERE foto.FotoID = $fotoID";
$result = mysqli_query($con, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    echo "Foto tidak ditemukan.";
    exit;
}

$foto = mysqli_fetch_assoc($result);

// Handle komentar dan like
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['Username'])) {
        echo "<script>
            alert('Silakan login terlebih dahulu!');
            window.location.href = 'detail.php?id=$fotoID';
        </script>";
        exit;
    }

    $userID = intval($_SESSION['UserID']);

    // Proses jika tombol "Kirim Komentar" ditekan
    if (isset($_POST['submit_comment'])) {
        $comment = mysqli_real_escape_string($con, $_POST['comment']);
        if (!empty($comment)) {
            $query = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) 
                      VALUES ('$fotoID', '$userID', '$comment', NOW())";
            if (!mysqli_query($con, $query)) {
                echo "Error: " . mysqli_error($con);
            }
        } else {
            echo "<script>window.onload = function() { alert('Komentar tidak boleh kosong!'); };</script>";
        }
    }
    // Proses jika tombol "Like" ditekan
    elseif (isset($_POST['like'])) {
        // Cek apakah user sudah menyukai foto ini
        $checkLikeQuery = "SELECT * FROM likefoto WHERE FotoID = '$fotoID' AND UserID = '$userID'";
        $likeResult = mysqli_query($con, $checkLikeQuery);

        if (mysqli_num_rows($likeResult) === 0) {
            $query = "INSERT INTO likefoto (FotoID, UserID, TanggalLike) VALUES ('$fotoID', '$userID', NOW())";
            if (!mysqli_query($con, $query)) {
                echo "Error: " . mysqli_error($con);
            }
        } else {
            echo "<script>window.onload = function() { alert('Anda sudah menyukai foto ini.'); };</script>";
        }
    }

    // Refresh halaman untuk memperbarui data
    echo "<script>window.location.href = 'detail.php?id=$fotoID';</script>";
    exit;
}

// Hitung jumlah like
$likeCountQuery = "SELECT COUNT(*) as totalLikes FROM likefoto WHERE FotoID = '$fotoID'";
$likeCountResult = mysqli_query($con, $likeCountQuery);
$likeCount = mysqli_fetch_assoc($likeCountResult)['totalLikes'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Foto</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
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

        .container {
            margin-top: 30px;
        }
        .profile-info {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }
        .profile-info img {
            border-radius: 50%;
            width: 60px;
            height: 60px;
        }
        .img-fluid {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            max-width: 100%;
            height: auto;
            max-height: 450px;
            object-fit: cover;
        }
        .title-photo{
            margin-top:75px;
        }
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 15px;
        }
        .action-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50px;
            padding: 10px;
        }
        .btn-primary {
            background-color: #1e88e5;
            border: none;
        }
        .btn-primary:hover {
            background-color: #1565c0;
        }
        .btn-secondary {
            background-color: #616161;
            border: none;
        }
        .btn-secondary:hover {
            background-color: #424242;
        }
        .btn-success {
            background-color: #43a047;
            border: none;
        }
        .btn-success:hover {
            background-color: #2e7d32;
        }
        .btn-info {
            background-color: #039be5;
            border: none;
        }
        .btn-info:hover {
            background-color: #0288d1;
        }
        textarea {
            background-color: #212121;
            color: #e0e0e0;
            border: 1px solid #424242;
            border-radius: 10px;
        }
        textarea:focus {
            border-color: #1e88e5;
            outline: none;
            box-shadow: 0 0 5px #1e88e5;
        }
        hr {
            border-color: #424242;
        }
        .modal-content {
            background-color: #212121;
            color: #e0e0e0;
            border: none;
        }
        .modal-header {
            border-bottom: 1px solid #424242;
        }
        .modal-footer {
            border-top: 1px solid #424242;
        }
        .comment-section {
            max-height: 200px; /* Membatasi tinggi komentar */
            overflow-y: auto; /* Menambahkan scrollbar jika komentar terlalu panjang */
            padding: 10px;
            background-color: #212121;
            border-radius: 10px;
            border: 1px solid #424242;
        }
        .content-row {
            display: flex;
            gap: 30px;
        }
        /* .content-row .left {
            flex: 1;
        } */
        .content-row .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        /* Styling tombol kembali */
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

    <div class="content-row">
        <!-- Left Content: Profile and Image -->
         
        <div class="left">
            <div class="profile-info">
                <img src="images/<?php echo htmlspecialchars($foto['profile_pic'] ?? 'default.png'); ?>" alt="Profile Picture">
                <h5><?php echo htmlspecialchars($foto['Username']); ?></h5>
            </div>
            <img src="terupload/<?php echo $foto['LokasiFoto']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($foto['JudulFoto']); ?>">
        </div>

        <!-- Right Content: Actions and Comments -->
        <div class="right">
        <div class="title-photo">
            <h2><?php echo htmlspecialchars($foto['JudulFoto']); ?></h2>
            <p><strong>Album:</strong> <?php echo htmlspecialchars($foto['NamaAlbum']); ?></p>
            <p><?php echo nl2br(htmlspecialchars($foto['DeskripsiFoto'])); ?></p>
            </div>
            <div class="action-buttons">
                <form method="POST" style="display: inline;">
                    <button name="like" class="btn btn-primary">
                        <i class="fas fa-thumbs-up"></i> 
                    </button>
                </form>
                <?php echo $likeCount; ?>
                <button class="btn btn-success" data-toggle="modal" data-target="#commentModal">
                    <i class="fas fa-comment"></i> 
                </button>
                <button class="btn btn-info" data-toggle="modal" data-target="#shareModal">
                    <i class="fas fa-share"></i> 
                </button>
                <a href="terupload/<?php echo $foto['LokasiFoto']; ?>" download class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                </a>
            </div>

            <h4>Komentar</h4>
            <div class="comment-section">
                <?php
                $commentQuery = "SELECT komentarfoto.IsiKomentar, komentarfoto.TanggalKomentar, user.Username 
                                 FROM komentarfoto
                                 INNER JOIN user ON komentarfoto.UserID = user.UserID
                                 WHERE komentarfoto.FotoID = $fotoID 
                                 ORDER BY komentarfoto.TanggalKomentar DESC";
                $commentResult = mysqli_query($con, $commentQuery);

                if (mysqli_num_rows($commentResult) > 0) {
                    while ($comment = mysqli_fetch_assoc($commentResult)) {
                        echo "<p><strong>" . htmlspecialchars($comment['Username']) . ":</strong> " . htmlspecialchars($comment['IsiKomentar']) . "</p>";
                        echo "<p><small>" . htmlspecialchars($comment['TanggalKomentar']) . "</small></p><hr>";
                    }
                } else {
                    echo "<p>Belum ada komentar.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Share -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Bagikan Tautan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Salin tautan di bawah ini untuk dibagikan:</p>
                <input type="text" class="form-control" value="<?php echo 'http://' . $_SERVER['HTTP_HOST'] . '/detail.php?id=' . $fotoID; ?>" readonly>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Comment -->
<div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentModalLabel">Tulis Komentar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <textarea class="form-control" name="comment" placeholder="Tulis komentar..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" name="submit_comment" class="btn btn-success">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
