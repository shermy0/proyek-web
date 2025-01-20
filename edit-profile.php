<?php
session_start();
include "koneksi.php";

// Pastikan pengguna sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: index.php");
    exit;
}

// Ambil data dari form
$UserID = $_SESSION['UserID'];
$namaLengkap = mysqli_real_escape_string($con, $_POST['NamaLengkap']);
$profilePic = $_FILES['profilePic'];

// Cek apakah pengguna mengunggah foto baru
if ($profilePic['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'images/';
    $fileName = basename($profilePic['name']);
    $targetFilePath = $uploadDir . $fileName;

    // Validasi tipe file (hanya gambar)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($profilePic['type'], $allowedTypes)) {
        header("Location: profile.php?status=invalid_file");
        exit;
    }

    // Pindahkan file ke folder tujuan
    if (move_uploaded_file($profilePic['tmp_name'], $targetFilePath)) {
        // Hapus foto lama jika ada
        $query = mysqli_query($con, "SELECT profile_pic FROM user WHERE UserID='$UserID'");
        $user = mysqli_fetch_assoc($query);
        if ($user['profile_pic'] !== 'default.png' && file_exists($uploadDir . $user['profile_pic'])) {
            unlink($uploadDir . $user['profile_pic']);
        }

        // Perbarui nama lengkap dan foto profil di database
        $updateQuery = "UPDATE user SET NamaLengkap='$namaLengkap', profile_pic='$fileName' WHERE UserID='$UserID'";
    } else {
        header("Location: profile.php?status=upload_failed");
        exit;
    }
} else {
    // Jika tidak ada foto baru, hanya perbarui nama lengkap
    $updateQuery = "UPDATE user SET NamaLengkap='$namaLengkap' WHERE UserID='$UserID'";
}

// Eksekusi query
if (mysqli_query($con, $updateQuery)) {
    header("Location: profile.php?status=success");
} else {
    header("Location: profile.php?status=error");
}

exit;
?>
