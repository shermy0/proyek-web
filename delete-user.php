<?php
session_start();
include "koneksi.php"; // Pastikan file koneksi disertakan

// Periksa apakah ada parameter 'id' pada URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Hapus data terkait di tabel album
    $query = "DELETE FROM album WHERE UserID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Hapus foto terkait pengguna dari tabel foto
    $query = "DELETE FROM foto WHERE UserID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Hapus data terkait di tabel likefoto
    $query = "DELETE FROM likefoto WHERE UserID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Hapus data terkait di tabel komentarfoto
    $query = "DELETE FROM komentarfoto WHERE UserID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->close();

    // Query untuk menghapus user berdasarkan UserID
    $query = "DELETE FROM user WHERE UserID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        // Penghapusan berhasil, redirect ke dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal menghapus pengguna.";
    }

    $stmt->close();
} else {
    // Jika tidak ada ID, redirect ke dashboard
    header("Location: dashboard.php");
    exit();
}
?>
