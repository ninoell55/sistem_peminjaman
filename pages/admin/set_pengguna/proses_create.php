<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['tambahPengguna'])) {
    $nama_pengguna = trim($_POST['nama_pengguna'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $nip_nis = trim($_POST['nip_nis'] ?? '');

    // Validasi input
    if ($nama_pengguna === '' || $username === '' || $password === '' || $role === '') {
        header("Location: read.php?success=error");
        exit;
    }

    // Cek duplikasi username
    $stmt_cek = $connection->prepare("SELECT COUNT(*) FROM pengguna WHERE username = ?");
    $stmt_cek->bind_param("s", $username);
    $stmt_cek->execute();
    $stmt_cek->bind_result($total);
    $stmt_cek->fetch();
    $stmt_cek->close();
    if ($total > 0) {
        header("Location: read.php?success=twin");
        exit;
    }

    // Jika bukan siswa, kosongkan kelas dan jurusan
    if (strtolower($role) !== 'siswa') {
        $kelas = '';
        $jurusan = '';
    }

    // Insert pengguna baru
    $stmt = $connection->prepare("INSERT INTO pengguna (nama_pengguna, username, password, role, kelas, jurusan, nip_nis) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nama_pengguna, $username, $password, $role, $kelas, $jurusan, $nip_nis);
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
