<?php
require_once '../../../config/functions.php';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

if (isset($_POST['updateKomoditas'])) {
    $id_barang = intval($_POST['id_barang']);
    $nama_barang = trim($_POST['nama_barang']);
    $id_kategori = $_POST['id_kategori'];
    $jumlah_total = $_POST['jumlah_total'];
    $jumlah_tersedia = $_POST['jumlah_tersedia'];
    $lokasi = $_POST['lokasi'];
    $kondisi = $_POST['kondisi'];
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validasi input kosong
    if (empty($nama_barang) || empty($id_kategori) || empty($jumlah_total) || empty($jumlah_tersedia) || empty($lokasi) || empty($kondisi)) {
        header("Location: read.php?success=error");
        exit;
    }

    // Pastikan jumlah_total dan jumlah_tersedia >= 0
    if ($jumlah_total < 0 || $jumlah_tersedia < 0 || $jumlah_tersedia > $jumlah_total) {
        header("Location: read.php?success=error");
        exit;
    }

    // Cek apakah nama_barang sudah dipakai barang lain
    $stmt_check_name = $connection->prepare("SELECT id_barang FROM barang WHERE nama_barang = ? AND id_barang != ?");
    $stmt_check_name->bind_param("si", $nama_barang, $id_barang);
    $stmt_check_name->execute();
    $stmt_check_name->store_result();

    if ($stmt_check_name->num_rows > 0) {
        $stmt_check_name->close();
        header("Location: read.php?success=twin");
        exit;
    }
    $stmt_check_name->close();

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
            // Cek apakah nama image sudah dipakai barang lain
            $stmt_check_img = $connection->prepare("SELECT id_barang FROM barang WHERE image = ? AND id_barang != ?");
            $stmt_check_img->bind_param("si", $image, $id_barang);
            $stmt_check_img->execute();
            $stmt_check_img->store_result();

            if ($stmt_check_img->num_rows > 0) {
                $stmt_check_img->close();
                header("Location: read.php?success=twin");
                exit;
            }
            $stmt_check_img->close();

            // Upload file
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                // Hapus gambar lama jika ada
                if (!empty($old_image)) {
                    $old_path = $target_dir . $old_image;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
            } else {
                header("Location: read.php?success=error");
                exit;
            }
        } else {
            header("Location: read.php?success=error");
            exit;
        }
    }

    // Update data barang
    $stmt = $connection->prepare("UPDATE barang 
        SET nama_barang=?, id_kategori=?, jumlah_total=?, jumlah_tersedia=?, lokasi=?, kondisi=?, deskripsi=?, image=? 
        WHERE id_barang=?");
    $stmt->bind_param("siisssssi", $nama_barang, $id_kategori, $jumlah_total, $jumlah_tersedia, $lokasi, $kondisi, $deskripsi, $image, $id_barang);

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
