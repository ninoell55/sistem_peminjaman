<?php
require_once '../../../config/functions.php';
$pageTitle = 'Data Kategori Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

$kategori = query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

include '../../../includes/header.php';
include '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Data Kategori Komoditas</h1>
                <p class="text-gray-400">Daftar seluruh kategori barang.</p>
            </div>
            <a href="create.php" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-full text-sm font-semibold text-white shadow">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah Kategori
            </a>
        </div>

        <div class="overflow-x-auto rounded-2xl shadow">
            <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-left">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nama Kategori</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kategori as $row): ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-3"><?= $no++ ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['nama_kategori']) ?></td>
                            <td class="px-4 py-3 flex gap-2">
                                <a href="update.php?id=<?= $row['id_kategori'] ?>" class="text-yellow-400 hover:underline text-sm flex items-center">
                                    <i data-lucide="rotate-ccw-square" class="w-4 h-4 mr-1"></i>Edit
                                </a>
                                <a href="delete.php?id=<?= $row['id_kategori'] ?>" class="text-red-400 hover:underline text-sm flex items-center" onclick="return confirm('Yakin ingin menghapus kategori ini?');">
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