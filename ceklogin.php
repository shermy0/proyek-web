<?php
session_start();
include "koneksi.php";

// Jika pengguna belum login, set role sebagai guest
if (!isset($_SESSION['Role'])) {
    $_SESSION['Role'] = 'guest'; // Default untuk tamu
}

// Ambil data dari form login
$Username = $_POST['Username'] ?? '';
$Password = md5($_POST['Password'] ?? '');

// Query untuk mencari user
$query = mysqli_query($con, "SELECT * FROM user WHERE Username ='$Username' AND Password ='$Password'");
$hasilquery = mysqli_num_rows($query);

if ($hasilquery == 1) {
    while ($row = mysqli_fetch_assoc($query)) {
        // Simpan ke sesi
        $_SESSION['UserID'] = $row['UserID'];
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['Role'] = $row['Role'];
        $_SESSION['profile_pic'] = $row['profile_pic'] ?? 'default.png'; // Default jika kosong
    }
    header("Location: home.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}
