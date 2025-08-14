<?php
require_once '../../../config/functions.php';
$pageTitle = 'Laporan Peminjaman';

$where = "";
if (!empty($_GET['tanggal_dari']) && !empty($_GET['tanggal_sampai'])) {
    $dari = $_GET['tanggal_dari'];
    $sampai = $_GET['tanggal_sampai'];
    $where = "WHERE tanggal_pinjam BETWEEN '$dari' AND '$sampai'";
}

$laporan = query("SELECT 
                    p.*, u.nama_pengguna, u.role, b.nama_barang, d.jumlah
                        FROM peminjaman p
                            JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                            JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                            JOIN barang b ON d.id_barang = b.id_barang
                        $where
                    ORDER BY p.tanggal_pinjam DESC");

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold"><?= $pageTitle; ?></h1>
                <p class="text-gray-400">Berikut adalah laporan peminjaman berdasarkan filter tanggal.</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b bg-indigo-600 rounded-t-lg">
                <span class="text-lg font-semibold text-white">Filter Laporan</span>
            </div>
            <div class="p-6">
                <form method="GET" class="flex flex-wrap gap-4 items-end mb-4">
                    <div>
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-200 mb-1">Dari Tanggal</label>
                        <input type="date" class="border border-gray-600 bg-gray-900 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" id="tanggal_dari" name="tanggal_dari" value="<?php echo isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : ''; ?>">
                    </div>
                    <div>
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-200 mb-1">Sampai Tanggal</label>
                        <input type="date" class="border border-gray-600 bg-gray-900 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" id="tanggal_sampai" name="tanggal_sampai" value="<?php echo isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : ''; ?>">
                    </div>
                    <div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded shadow">Filter</button>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="bg-green-600 text-white px-4 py-2 rounded shadow opacity-60 cursor-not-allowed" disabled>Export Excel</button>
                        <button type="button" class="bg-red-600 text-white px-4 py-2 rounded shadow opacity-60 cursor-not-allowed" disabled>Export PDF</button>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-700 text-left">
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Nama Pengguna</th>
                                <th class="px-4 py-3">Nama Barang</th>
                                <th class="px-4 py-3">Tanggal Pinjam</th>
                                <th class="px-4 py-3">Tanggal Kembali</th>
                                <th class="px-4 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($laporan) > 0): $no = 1;
                                foreach ($laporan as $row): ?>
                                    <tr class="border-t border-gray-700 hover:bg-gray-700">
                                        <td class="px-4 py-3"><?= $no++ ?></td>
                                        <td class="px-4 py-3"><?= htmlspecialchars($row['nama_pengguna']) ?> - (<?= strtoupper($row['role']); ?>)</td>
                                        <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                        <td class="px-4 py-3 font-bold"><?= htmlspecialchars($row['tanggal_pinjam']) ?></td>
                                        <td class="px-4 py-3 font-bold"><?= htmlspecialchars($row['tanggal_kembali']) ?></td>
                                        <td class="px-4 py-3">
                                            <?php
                                            $status = $row['status'];
                                            $status_label = '';
                                            $status_class = '';
                                            if ($status == 'dipinjam') {
                                                $status_label = 'Dipinjam';
                                                $status_class = 'bg-yellow-700 text-yellow-200';
                                            } elseif ($status == 'dikembalikan') {
                                                $status_label = 'Dikembalikan';
                                                $status_class = 'bg-green-700 text-green-200';
                                            } elseif ($status == 'ditolak') {
                                                $status_label = 'Ditolak';
                                                $status_class = 'bg-red-700 text-red-200';
                                            } else {
                                                $status_label = 'Menunggu';
                                                $status_class = 'bg-gray-600 text-gray-200';
                                            }
                                            ?>
                                            <span class="px-2 py-1 rounded text-xs font-semibold <?= $status_class; ?>">
                                                <?= $status_label ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-gray-400">Tidak ada data peminjaman untuk tanggal yang dipilih.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>
<?php require_once '../../../includes/footer.php'; ?>