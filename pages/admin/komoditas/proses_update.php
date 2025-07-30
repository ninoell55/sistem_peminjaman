<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['updateKomoditas'])) {
    $id_barang = intval($_POST['id_barang']);
    $nama_barang = $_POST['nama_barang'];
    $id_kategori = $_POST['id_kategori'];
    $jumlah_total = $_POST['jumlah_total'];
    $jumlah_tersedia = $_POST['jumlah_tersedia'];
    $lokasi = $_POST['lokasi'];
    $kondisi = $_POST['kondisi'];
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Ambil gambar lama
    $stmt_select = $connection->prepare("SELECT image FROM barang WHERE id_barang = ?");
    $stmt_select->bind_param("i", $id_barang);
    $stmt_select->execute();
    $stmt_select->bind_result($old_image);
    $stmt_select->fetch();
    $stmt_select->close();

    $image = $old_image;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = '../../../assets/uploads/';
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image = uniqid('img_', true) . '.' . $ext;
        $target_file = $target_dir . $image;
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
            // Hapus gambar lama jika ada
            if (!empty($old_image)) {
                $old_path = $target_dir . $old_image;
                if (file_exists($old_path)) {
                    unlink($old_path);
                }
            }
        } else {
            echo "Tipe file gambar tidak valid!";
            exit;
        }
    }

    // Update data barang
    $stmt = $connection->prepare("UPDATE barang SET nama_barang=?, id_kategori=?, jumlah_total=?, jumlah_tersedia=?, lokasi=?, kondisi=?, deskripsi=?, image=? WHERE id_barang=?");
    $stmt->bind_param("siisssssi", $nama_barang, $id_kategori, $jumlah_total, $jumlah_tersedia, $lokasi, $kondisi, $deskripsi, $image, $id_barang);

    if ($stmt->execute()) {
        header("Location: read.php?update=1");
        exit;
    } else {
        echo "Gagal update data: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Data tidak valid!";
}
