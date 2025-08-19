<?php
require_once '../../../config/functions.php';
$pageTitle = 'Riwayat Peminjaman Barang';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../../auth/login_pengguna/login.php');
    exit;
}    

$id_pengguna = $_SESSION['id_pengguna'] ?? null;
$nama_pengguna = $_SESSION['nama_pengguna'] ?? '';

$riwayat = query("SELECT 
                    p.*, b.nama_barang, d.jumlah, p.status, p.waktu_pinjam, p.waktu_kembali
                        FROM peminjaman p
                                JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                                JOIN barang b ON d.id_barang = b.id_barang
                            WHERE p.id_pengguna = '$id_pengguna'
                        ORDER BY p.created_at DESC");

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>

<?php require_once '../../../includes/footer.php'; ?>