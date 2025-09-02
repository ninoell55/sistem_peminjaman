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
