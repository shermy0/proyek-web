<?php
include "koneksi.php";

// Ambil data dari form
$username = $_POST['Username'] ?? '';
$password = md5($_POST['Password'] ?? ''); // Enkripsi password
$email = $_POST['Email'] ?? '';
$namaLengkap = $_POST['NamaLengkap'] ?? '';
$alamat = $_POST['Alamat'] ?? '';

// Query untuk menyimpan data pengguna baru
$query = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat, Role) VALUES (?, ?, ?, ?, ?, 'user')";
$stmt = $con->prepare($query);
$stmt->bind_param("sssss", $username, $password, $email, $namaLengkap, $alamat);

if ($stmt->execute()) {
    header("Location: index.php?success=1"); // Redirect ke halaman login dengan notifikasi sukses
} else {
    header("Location: register.php?error=1"); // Redirect ke halaman register dengan notifikasi error
}
$stmt->close();
?>
