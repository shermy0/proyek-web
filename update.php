<?php
include "koneksi.php";

// Ambil data dari form
$FotoID = $_POST['FotoID'];
$JudulFoto = $_POST['JudulFoto'];
$DeskripsiFoto = $_POST['DeskripsiFoto'];
$TanggalUnggah = $_POST['TanggalUnggah'];
$AlbumID = $_POST['AlbumID'];
$LokasiFotoLama = $_POST['LokasiFotoLama']; // Nama file lama

// Proses file upload
$LokasiFoto = $LokasiFotoLama; // Default gunakan file lama
if (isset($_FILES['LokasiFoto']['name']) && $_FILES['LokasiFoto']['name'] != "") {
    $file_name = $_FILES['LokasiFoto']['name'];
    $file_tmp = $_FILES['LokasiFoto']['tmp_name'];
    $upload_dir = "terupload/";

    // Pindahkan file ke folder upload
    if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
        $LokasiFoto = $file_name;

        // Hapus file lama jika ada file baru
        if (file_exists($upload_dir . $LokasiFotoLama)) {
            unlink($upload_dir . $LokasiFotoLama);
        }
    } else {
        die("Gagal mengunggah file baru.");
    }
}

// Query update data
$query = "UPDATE foto SET 
    LokasiFoto='$LokasiFoto',
    JudulFoto='$JudulFoto',
    DeskripsiFoto='$DeskripsiFoto',
    TanggalUnggah='$TanggalUnggah',
    AlbumID='$AlbumID'
    WHERE FotoID='$FotoID'";

if (mysqli_query($con, $query)) {
    // Jika berhasil, redirect ke dashboard
    header("Location: profile.php?pesan=sukses");
    exit();
} else {
    // Jika gagal, tampilkan pesan error
    echo "Error: " . mysqli_error($con);
}
?>
