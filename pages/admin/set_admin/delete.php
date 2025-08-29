<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_admin = intval($_GET['id']);
    // Hapus data admin
    $stmt = $connection->prepare("DELETE FROM admin WHERE id_admin = ?");
    $stmt->bind_param("i", $id_admin);
    if ($stmt->execute()) {
        echo '<script>alert("Admin berhasil dihapus!");window.location.href="read.php";</script>';
        exit;
    } else {
        echo '<script>alert("Tidak boleh menghapus data admin/petugas yang sudah melakukan perizinan akses di sistem ini.");history.back();</script>';
    }
    $stmt->close();
} else {
    echo '<script>alert("ID admin tidak ditemukan.");history.back();</script>';
}
