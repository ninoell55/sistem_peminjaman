<?php
require_once '../../../config/functions.php';

if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    header('Location: peminjaman.php');
    exit;
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

if ($aksi === 'acc') {
    $status = 'dipinjam';
} elseif ($aksi === 'tolak') {
    $status = 'ditolak';
} else {
    header('Location: peminjaman.php');
    exit;
}

$query = "UPDATE peminjaman SET status = '$status' WHERE id_peminjaman = $id";
$result = mysqli_query($connection, $query);

if ($result) {
    header('Location: peminjaman.php?msg=success');
} else {
    header('Location: peminjaman.php?msg=error');
}
exit;
