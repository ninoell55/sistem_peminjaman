<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['tambahKategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    // Validasi input
    if ($nama_kategori === '') {
        header("Location: read.php?success=error");
        exit;
    }
    // Sanitasi input
    $nama_kategori = htmlspecialchars($nama_kategori, ENT_QUOTES, 'UTF-8');
    // Cek duplikasi dengan prepared statement
    $stmt_cek = $connection->prepare("SELECT COUNT(*) FROM kategori WHERE nama_kategori = ?");
    $stmt_cek->bind_param("s", $nama_kategori);
    $stmt_cek->execute();
    $stmt_cek->bind_result($total);
    $stmt_cek->fetch();
    $stmt_cek->close();
    if ($total > 0) {
        header("Location: read.php?success=twin");
        exit;
    }
    // Insert kategori baru
    $stmt = $connection->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
    $stmt->bind_param("s", $nama_kategori);
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
