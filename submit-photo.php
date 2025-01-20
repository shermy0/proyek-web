<?php
include "koneksi.php";

// Mulai sesi
session_start();
$UserID = $_SESSION['UserID']; // Pastikan UserID disimpan dalam sesi

// Ambil data dari form
$JudulFoto = $_POST['JudulFoto'];
$DeskripsiFoto = $_POST['DeskripsiFoto'];
$TanggalUnggah = $_POST['TanggalUnggah'];
$AlbumID = $_POST['AlbumID'];


// Proses upload file
$namaFile = $_FILES['LokasiFoto']['name'];
$namaSementara = $_FILES['LokasiFoto']['tmp_name'];

// Tentukan lokasi file akan dipindahkan
$dirUpload = "terupload/";

// Pindahkan file yang diupload ke folder tujuan
$terupload = move_uploaded_file($namaSementara, $dirUpload . $namaFile);

if ($terupload) {
    // Jika upload berhasil, simpan data ke database
    $query = mysqli_query($con, "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFoto, AlbumID, UserID)
     VALUES ('$JudulFoto', '$DeskripsiFoto', '$TanggalUnggah', '$namaFile', '$AlbumID', '$UserID')");

    if ($query) {
        header("Location: portfolio.php");
        exit(); // Hentikan eksekusi setelah redirect
    } else {
        echo "Gagal menyimpan ke database: " . mysqli_error($con);
    }
} else {
    echo "Upload Gagal!";
}
?>
