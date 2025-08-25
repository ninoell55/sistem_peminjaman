<?php
require_once '../../../config/functions.php';

if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    header('Location: peminjaman.php');
    exit;
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

if ($aksi === 'ajukan') {
    // update status dan waktu_kembali
    $sql = "UPDATE peminjaman 
            SET status = 'menunggu_pengembalian',
                waktu_kembali = NOW()
            WHERE id_peminjaman = $id AND status = 'dipinjam'";
    $res = mysqli_query($connection, $sql);

    if ($res) {
        header('Location: peminjaman.php?msg=ajukan_pengembalian');
    } else {
        header('Location: peminjaman.php?msg=error');
    }
    exit;
}
