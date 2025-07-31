<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['updateAdmin'])) {
    $id_admin = intval($_POST['id_admin'] ?? 0);
    $nama_admin = trim($_POST['nama_admin'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Validasi input
    if ($id_admin === 0 || $nama_admin === '' || $username === '' || $role === '') {
        echo '<script>alert("Semua field wajib diisi!");history.back();</script>';
        exit;
    }

    // Cek duplikasi username (kecuali milik sendiri)
    $stmt_cek = $connection->prepare("SELECT COUNT(*) FROM admin WHERE username = ? AND id_admin != ?");
    $stmt_cek->bind_param("si", $username, $id_admin);
    $stmt_cek->execute();
    $stmt_cek->bind_result($total);
    $stmt_cek->fetch();
    $stmt_cek->close();
    if ($total > 0) {
        echo '<script>alert("Username sudah digunakan!");history.back();</script>';
        exit;
    }

    // Update data
    if ($password !== '') {
        $stmt = $connection->prepare("UPDATE admin SET nama_admin=?, username=?, password=?, role=? WHERE id_admin=?");
        $stmt->bind_param("ssssi", $nama_admin, $username, $password, $role, $id_admin);
    } else {
        $stmt = $connection->prepare("UPDATE admin SET nama_admin=?, username=?, role=? WHERE id_admin=?");
        $stmt->bind_param("sssi", $nama_admin, $username, $role, $id_admin);
    }

    if ($stmt->execute()) {
        echo '<script>alert("Admin berhasil diupdate!");window.location.href="read.php";</script>';
        exit;
    } else {
        echo '<script>alert("Gagal update admin: ' . htmlspecialchars($stmt->error) . '");history.back();</script>';
    }
    $stmt->close();
} else {
    echo '<script>alert("Akses tidak valid.");history.back();</script>';
}
