<?php
require_once '../../../config/functions.php';
$pageTitle = 'Delete Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

if (isset($_GET['id'])) {
    $id_barang = intval($_GET['id']);

    // Ambil nama file gambar dari database
    $stmt_select = $connection->prepare("SELECT image FROM barang WHERE id_barang = ?");
    $stmt_select->bind_param("i", $id_barang);
    $stmt_select->execute();
    $stmt_select->bind_result($image);
    $stmt_select->fetch();
    $stmt_select->close();

    // Hapus file gambar jika ada
    if (!empty($image)) {
        $image_path = '../../../assets/uploads/' . $image;
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Hapus data barang berdasarkan ID
    $stmt = $connection->prepare("DELETE FROM barang WHERE id_barang = ?");
    $stmt->bind_param("i", $id_barang);

    if ($stmt->execute()) {
        echo '<script>alert("Barang berhasil dihapus!");window.location.href="read.php";</script>';
        exit;
    } else {
        echo '<script>alert("Tidak boleh menghapus data barang yang sudah pernah dipinjam di sistem ini.");history.back();</script>';
    }

    $stmt->close();
} else {
    echo "ID barang tidak ditemukan.";
}