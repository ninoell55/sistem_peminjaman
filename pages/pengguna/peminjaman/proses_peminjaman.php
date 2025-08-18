<?php
require_once '../../../config/functions.php';
$pageTitle = 'Proses Peminjaman Barang';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../../auth/login_pengguna/login.php');
    exit;
}

// Proses form peminjaman
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_pengguna = $_SESSION['id_pengguna'] ?? null;
    $id_barang = $_POST['id_barang'] ?? null;
    $tanggal_pinjam = $_POST['tanggal_pinjam'] ?? date('Y-m-d');
    $tanggal_kembali = $_POST['tanggal_kembali'] ?? date('Y-m-d', strtotime('+7 days'));
    $jumlah = $_POST['jumlah'] ?? 1;
    $status = 'menunggu';
    $catatan = $_POST['catatan'] ?? '';

    // Insert data peminjaman
    $sql = "INSERT INTO peminjaman (id_pengguna, tanggal_pinjam, tanggal_kembali, status, catatan) VALUES (?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("issss", $id_pengguna, $tanggal_pinjam, $tanggal_kembali, $status, $catatan);

    // execute the statement peminjaman
    if ($stmt->execute()) {
        $id_peminjaman = $connection->insert_id;
        
        // Insert detail peminjaman
        $sqlDetail = "INSERT INTO detail_peminjaman (id_peminjaman, id_barang, jumlah) VALUES (?, ?, ?)";
        $stmtDetail = $connection->prepare($sqlDetail);
        $stmtDetail->bind_param("iii", $id_peminjaman, $id_barang, $jumlah);
        
        // execute the statement detail peminjaman
        if ($stmtDetail->execute()) {
            // Update jumlah barang
            $sqlUpdate = "UPDATE barang SET jumlah_tersedia = jumlah_tersedia - ? WHERE id_barang = ?";
            $stmtUpdate = $connection->prepare($sqlUpdate);
            $stmtUpdate->bind_param("ii", $jumlah, $id_barang);
            
            // execute the statement update barang
            if ($stmtUpdate->execute()) {
                header('Location: peminjaman.php?success=1');
                exit;
            } else {
                header('Location: peminjaman.php?error=update_barang');
                exit;
            }
        } else {
            header('Location: peminjaman.php?error=insert_detail');
            exit;
        }
    } else {
        header('Location: peminjaman.php?error=insert_peminjaman');
        exit;
    }
}
