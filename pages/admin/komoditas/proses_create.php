<?php
require_once '../../../config/functions.php';
$pageTitle = 'Proses Tambah Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

if (isset($_POST['tambahKomoditas'])) {
    // Get form data
    $nama_barang = $_POST['nama_barang'];
    $id_kategori = $_POST['id_kategori'];
    $jumlah_total = $_POST['jumlah_total'];
    $jumlah_tersedia = $_POST['jumlah_tersedia'];
    $lokasi = $_POST['lokasi'];
    $kondisi = $_POST['kondisi'];
    // Optional: Deskripsi field
    $deskripsi = $_POST['deskripsi'] ?? '';

    // Validate inputs
    if (empty($nama_barang) || empty($id_kategori) || empty($jumlah_total) || empty($jumlah_tersedia) || empty($lokasi) || empty($kondisi)) {
        header("Location: read.php?success=error");
        exit;
    }

    // Pastikan jumlah_total dan jumlah_tersedia adalah integer dan tidak kurang dari 0
    if ($jumlah_total < 0 || $jumlah_tersedia < 0) {
        header("Location: read.php?success=error");
        exit;
    }   

    // Cek duplikasi barang
    $stmt_cek = $connection->prepare("SELECT COUNT(*) FROM barang WHERE nama_barang = ?");
    $stmt_cek->bind_param("s", $nama_barang);
    $stmt_cek->execute();
    $stmt_cek->bind_result($total);
    $stmt_cek->fetch();
    $stmt_cek->close();
    if ($total > 0) {
        header("Location: read.php?success=twin");
        exit;
    } else {
        $image = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $target_dir = '../../../assets/uploads/';
            $image = uniqid() . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $target_files = $target_dir . $image;
            // Validasi tipe file
            $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                move_uploaded_file($_FILES['image']['tmp_name'], $target_files);
            } else {
                header("Location: read.php?success=error");
                exit;
            }
        }

        // Prepare and execute the insert query
        $stmt = $connection->prepare("INSERT INTO barang (nama_barang, id_kategori, jumlah_total, jumlah_tersedia, lokasi, kondisi, image, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siisssss", $nama_barang, $id_kategori, $jumlah_total, $jumlah_tersedia, $lokasi, $kondisi, $image, $deskripsi);

        // Execute the statement and check for success
        if ($stmt->execute()) {
            header("Location: read.php?success=tambah");
            exit;
        } else {
            header("Location: read.php?success=error");
            exit;
        }

        // Close the statement
        $stmt->close();
    }
}
