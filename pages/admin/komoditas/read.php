<?php
require_once '../../../config/functions.php';
$pageTitle = 'Data Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

// Ambil data barang + kategori
$komoditas = query("SELECT barang.*, 
                        kategori.nama_kategori 
                    FROM barang 
                    JOIN kategori ON barang.id_kategori = kategori.id_kategori 
                        ORDER BY barang.created_at DESC");

include '../../../includes/header.php';
include '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Data Komoditas</h1>
                <p class="text-gray-400">Berikut adalah daftar seluruh barang yang tersedia.</p>
            </div>
            <a href="create.php" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-full text-sm font-semibold text-white shadow">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Barang
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl shadow">
            <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-left">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nama Barang</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Jumlah</th>
                        <th class="px-4 py-3">Lokasi</th>
                        <th class="px-4 py-3">Kondisi</th>
                        <th class="px-4 py-3">Image</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($komoditas as $row): ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-3"><?= $no++ ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td class="px-4 py-3"><?= $row['jumlah_tersedia'] ?> / <?= $row['jumlah_total'] ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['lokasi']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['kondisi']) ?></td>
                            <td class="px-4 py-3">
                                <?php if (!empty($row['image'])): ?>
                                    <div class="flex items-center justify-center w-20 h-20">
                                        <img src="../../../assets/uploads/<?= htmlspecialchars($row['image']); ?>"
                                            alt="Foto <?= htmlspecialchars($row['nama_barang']); ?>"
                                            class="object-cover rounded-lg border-2 border-gray-600 shadow-md bg-white" />
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center justify-center w-20 h-20 rounded-lg border-2 border-gray-600 bg-gray-200 text-gray-500 text-xs">
                                        Tidak ada gambar
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['deskripsi']) ?></td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="update.php?id=<?= $row['id_barang'] ?>" class="text-yellow-400 hover:underline text-sm flex items-center">
                                    <i data-lucide="rotate-ccw-square" class="w-4 h-4 mr-1"></i>Edit
                                </a>
                                <a href="delete.php?id=<?= $row['id_barang'] ?>" class="text-red-400 hover:underline text-sm flex items-center"
                                onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<?php include '../../../includes/footer.php'; ?>        