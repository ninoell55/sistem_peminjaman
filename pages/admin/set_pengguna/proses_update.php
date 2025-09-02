<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['updatePengguna'])) {
    $id_pengguna = intval($_POST['id_pengguna'] ?? 0);
    $nama_pengguna = trim($_POST['nama_pengguna'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $kelas = trim($_POST['kelas'] ?? '');
    $jurusan = trim($_POST['jurusan'] ?? '');
    $nip_nis = trim($_POST['nip_nis'] ?? '');

    // Validasi input
    if ($id_pengguna === 0 || $nama_pengguna === '' || $username === '' || $role === '') {
        header("Location: read.php?success=error");
        exit;
    }

    // Cek duplikasi username (kecuali milik sendiri)
    $stmt_cek = $connection->prepare("SELECT COUNT(*) FROM pengguna WHERE username = ? AND id_pengguna != ?");
    $stmt_cek->bind_param("si", $username, $id_pengguna);
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

    // Update data
    if ($password !== '') {
        $stmt = $connection->prepare("UPDATE pengguna SET nama_pengguna=?, username=?, password=?, role=?, kelas=?, jurusan=?, nip_nis=? WHERE id_pengguna=?");
        $stmt->bind_param("sssssssi", $nama_pengguna, $username, $password, $role, $kelas, $jurusan, $nip_nis, $id_pengguna);
    } else {
        $stmt = $connection->prepare("UPDATE pengguna SET nama_pengguna=?, username=?, role=?, kelas=?, jurusan=?, nip_nis=? WHERE id_pengguna=?");
        $stmt->bind_param("ssssssi", $nama_pengguna, $username, $role, $kelas, $jurusan, $nip_nis, $id_pengguna);
    }

    if ($stmt->execute()) {
        header("Location: read.php?success=edit");
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
