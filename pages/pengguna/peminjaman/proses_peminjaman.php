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
    $waktu_pinjam = $_POST['waktu_pinjam'] ?? date('Y-m-d');
    $jumlah = $_POST['jumlah'] ?? 1;
    $status = 'menunggu';
    $catatan = $_POST['catatan'] ?? '';

    // Validasi input
    if (!$id_pengguna || !$id_barang) {
        header('Location: peminjaman.php?success=invalid');
        exit;
    }

    // Watku pinjam tidak boleh kurang dari tanggal hari ini dan jam sekarang dan waktu pinjam tidak boleh lebih dari 7 hari dari tanggal hari ini
    $jamSekarang = date('H:i:s');
    $tanggalHariIni = date('Y-m-d');
    $tanggalMaksimal = date('Y-m-d', strtotime('+7 days'));
    if ($waktu_pinjam < $tanggalHariIni . ' ' . $jamSekarang || $waktu_pinjam > $tanggalMaksimal) {
        header('Location: peminjaman.php?success=invalid');
        exit;
    }

    // Jumlah pinjam minimal 1
    if ($jumlah < 1) {
        header('Location: peminjaman.php?success=invalid');
        exit;
    }

    // Jumlah pinjam tidak boleh melebih dari jumlah tersedia
    $sqlCek = "SELECT jumlah_tersedia FROM barang WHERE id_barang = ?";
    $stmtCek = $connection->prepare($sqlCek);
    $stmtCek->bind_param("i", $id_barang);
    $stmtCek->execute();
    $resultCek = $stmtCek->get_result();
    if ($resultCek->num_rows > 0) {
        $dataCek = $resultCek->fetch_assoc();
        if ($jumlah > $dataCek['jumlah_tersedia']) {
            header('Location: peminjaman.php?success=invalid');
            exit;
        }
    } else {
        header('Location: peminjaman.php?success=error');
        exit;
    }

    // Insert data peminjaman
    $sql = "INSERT INTO peminjaman (id_pengguna, waktu_pinjam, waktu_kembali, status, catatan) VALUES (?, ?, ?, ?, NULLIF(?, ''))";
    $stmt = $connection->prepare($sql);
    $waktu_kembali = NULL;
    $stmt->bind_param("issss", $id_pengguna, $waktu_pinjam, $waktu_kembali, $status, $catatan);

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
                header('Location: peminjaman.php?success=tambah');
                exit;
            } else {
                header('Location: peminjaman.php?success=error');
                exit;
            }
        } else {
            header('Location: peminjaman.php?success=error');
            exit;
        }
    } else {
        header('Location: peminjaman.php?success=error');
        exit;
    }
}
