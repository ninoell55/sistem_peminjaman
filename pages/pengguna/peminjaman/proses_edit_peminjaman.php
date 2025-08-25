<?php
require_once '../../../config/functions.php';
$pageTitle = 'Proses Edit Peminjaman';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../../auth/login_pengguna/login.php');
    exit;
}

// Proses edit peminjaman
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_peminjaman = $_POST['id_peminjaman'] ?? null;
    $id_barang_baru = $_POST['id_barang'] ?? null;
    $jumlah_baru = $_POST['jumlah'] ?? 1;
    $waktu_pinjam_baru = $_POST['waktu_pinjam'] ?? date('Y-m-d');
    $catatan_baru = $_POST['catatan'] ?? '';

    if (!$id_peminjaman || !$id_barang_baru) {
        header('Location: peminjaman.php?error=invalid_data');
        exit;
    }

    // Ambil data lama peminjaman
    $sqlLama = "SELECT dp.id_barang, dp.jumlah 
                FROM detail_peminjaman dp
                WHERE dp.id_peminjaman = ?";
    $stmtLama = $connection->prepare($sqlLama);
    $stmtLama->bind_param("i", $id_peminjaman);
    $stmtLama->execute();
    $resultLama = $stmtLama->get_result();

    if ($resultLama->num_rows > 0) {
        $dataLama = $resultLama->fetch_assoc();
        $id_barang_lama = $dataLama['id_barang'];
        $jumlah_lama = $dataLama['jumlah'];

        // Kembalikan stok lama terlebih dahulu
        $sqlKembali = "UPDATE barang SET jumlah_tersedia = jumlah_tersedia + ? WHERE id_barang = ?";
        $stmtKembali = $connection->prepare($sqlKembali);
        $stmtKembali->bind_param("ii", $jumlah_lama, $id_barang_lama);
        $stmtKembali->execute();
    }

    // Update tabel peminjaman
    $sqlUpdatePeminjaman = "UPDATE peminjaman 
                            SET waktu_pinjam = ?, catatan = NULLIF(?, '') 
                            WHERE id_peminjaman = ? AND status = 'menunggu'";
    $stmtUpdate = $connection->prepare($sqlUpdatePeminjaman);
    $stmtUpdate->bind_param("ssi", $waktu_pinjam_baru, $catatan_baru, $id_peminjaman);

    if ($stmtUpdate->execute()) {
        // Update detail_peminjaman
        $sqlUpdateDetail = "UPDATE detail_peminjaman 
                            SET id_barang = ?, jumlah = ? 
                            WHERE id_peminjaman = ?";
        $stmtDetail = $connection->prepare($sqlUpdateDetail);
        $stmtDetail->bind_param("iii", $id_barang_baru, $jumlah_baru, $id_peminjaman);

        if ($stmtDetail->execute()) {
            // Kurangi stok baru
            $sqlKurang = "UPDATE barang SET jumlah_tersedia = jumlah_tersedia - ? WHERE id_barang = ?";
            $stmtKurang = $connection->prepare($sqlKurang);
            $stmtKurang->bind_param("ii", $jumlah_baru, $id_barang_baru);

            if ($stmtKurang->execute()) {
                header('Location: peminjaman.php?edit_success=1');
                exit;
            } else {
                header('Location: peminjaman.php?error=update_stok');
                exit;
            }
        } else {
            header('Location: peminjaman.php?error=update_detail');
            exit;
        }
    } else {
        header('Location: peminjaman.php?error=update_peminjaman');
        exit;
    }
}
