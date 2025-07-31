<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['tambahAdmin'])) {
    $nama_admin = trim($_POST['nama_admin'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');

    // Validasi input
    if ($nama_admin === '' || $username === '' || $password === '' || $role === '') {
        echo '<script>alert("Semua field wajib diisi!");history.back();</script>';
        exit;
    }

    // Cek duplikasi username
    $stmt_cek = $connection->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
    $stmt_cek->bind_param("s", $username);
    $stmt_cek->execute();
    $stmt_cek->bind_result($total);
    $stmt_cek->fetch();
    $stmt_cek->close();
    if ($total > 0) {
        echo '<script>alert("Username sudah digunakan!");history.back();</script>';
        exit;
    }

    // Insert admin baru
    $stmt = $connection->prepare("INSERT INTO admin (nama_admin, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_admin, $username, $password, $role);
    if ($stmt->execute()) {
        echo '<script>alert("Admin berhasil ditambahkan!");window.location.href="read.php";</script>';
        exit;
    } else {
        echo '<script>alert("Gagal tambah admin: ' . htmlspecialchars($stmt->error) . '");history.back();</script>';
    }
    $stmt->close();
} else {
    echo '<script>alert("Akses tidak valid.");history.back();</script>';
}
