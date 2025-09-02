<?php
require_once '../../../config/functions.php';

if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    header('Location: peminjaman.php');
    exit;
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

if ($aksi === 'acc') {
    // ACC peminjaman → status jadi dipinjam + kurangi stok
    $sql = "SELECT id_barang, jumlah FROM detail_peminjaman WHERE id_peminjaman = $id";
    $res = mysqli_query($connection, $sql);

    mysqli_query($connection, "UPDATE peminjaman SET status = 'dipinjam' WHERE id_peminjaman = $id");
    header('Location: peminjaman.php?success');
    exit;
} elseif ($aksi === 'acc_pengembalian') {
    // ACC pengembalian → status jadi dikembalikan + update stok
    $sql = "SELECT id_barang, jumlah FROM detail_peminjaman WHERE id_peminjaman = $id";
    $res = mysqli_query($connection, $sql);

    while ($row = mysqli_fetch_assoc($res)) {
        $id_barang = $row['id_barang'];
        $jumlah = $row['jumlah'];
        mysqli_query($connection, "UPDATE barang SET jumlah_tersedia = jumlah_tersedia + $jumlah WHERE id_barang = $id_barang");
    }

    mysqli_query($connection, "UPDATE peminjaman SET status = 'dikembalikan' WHERE id_peminjaman = $id");
    header('Location: peminjaman.php?success');
    exit;
}

header('Location: peminjaman.php?success=error');
exit;
