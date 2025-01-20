<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $fotoID = $_GET['id'];

    // Hapus komentar terkait foto
    $deleteCommentsQuery = "DELETE FROM komentarfoto WHERE FotoID = ?";
    $stmt = $con->prepare($deleteCommentsQuery);
    $stmt->bind_param("i", $fotoID);
    $stmt->execute();
    $stmt->close();

    // Hapus like terkait foto
    $deleteLikesQuery = "DELETE FROM likefoto WHERE FotoID = ?";
    $stmt = $con->prepare($deleteLikesQuery);
    $stmt->bind_param("i", $fotoID);
    $stmt->execute();
    $stmt->close();

    // Hapus foto
    $deleteFotoQuery = "DELETE FROM foto WHERE FotoID = ?";
    $stmt = $con->prepare($deleteFotoQuery);
    $stmt->bind_param("i", $fotoID);
    if ($stmt->execute()) {
        header("Location: dashboard.php?status=success");
    } else {
        echo "Gagal menghapus foto.";
    }
    $stmt->close();
}
?>
