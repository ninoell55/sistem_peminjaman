<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_pengguna = intval($_GET['id']);
    // Hapus data pengguna
    $stmt = $connection->prepare("DELETE FROM pengguna WHERE id_pengguna = ?");
    $stmt->bind_param("i", $id_pengguna);
    if ($stmt->execute()) {
        echo '<script>alert("Pengguna berhasil dihapus!");window.location.href="read.php";</script>';
        exit;
    } else {
        echo '<script>alert("Tidak boleh menghapus data pengguna yang sudah melakukan peminjaman di sistem ini.");history.back();</script>';
    }
    $stmt->close();
} else {
    echo '<script>alert("ID pengguna tidak ditemukan.");history.back();</script>';
}
