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
        header("Location: read.php?success=error");
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
        header("Location: read.php?success=twin");
        exit;
    }

    // Insert admin baru
    $stmt = $connection->prepare("INSERT INTO admin (nama_admin, username, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama_admin, $username, $password, $role);
    if ($stmt->execute()) {
        header("Location: read.php?success=tambah");
        exit;
    } else {
        header("Location: read.php?success=error");
        exit;
    }
    $stmt->close();
} else {
    header("Location: read.php?success=invalid");
    exit;
}
