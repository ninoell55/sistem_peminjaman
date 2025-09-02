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
        header("Location: read.php?success=hapus");
        exit;
    } else {
        header("Location: read.php?success=invalid");
        exit;
    }
    $stmt->close();
} else {
    header("Location: read.php?success=error");
    exit;
}
