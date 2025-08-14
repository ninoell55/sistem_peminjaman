<?php
require_once '../../../config/functions.php';
$pageTitle = 'Riwayat Peminjaman';

$riwayat = query("SELECT 
                    p.*, u.nama_pengguna, u.role, b.nama_barang, d.jumlah
                        FROM peminjaman p
                            JOIN pengguna u ON p.id_pengguna = u.id_pengguna 
                            JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                            JOIN barang b ON d.id_barang = b.id_barang
                        ORDER BY p.created_at DESC");

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold"><?= $pageTitle; ?></h1>
                <p class="text-gray-400">Berikut adalah riwayat peminjaman yang pernah terjadi di sistem</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="px-6 py-4 border-b bg-indigo-600 rounded-t-lg">
                <span class="text-lg font-semibold text-white">Riwayat Peminjaman</span>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-700 text-left">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Nama Pengguna</th>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Tanggal Pinjam</th>
                            <th class="px-4 py-3">Tanggal Kembali</th>
                            <th class="px-4 py-3">Catatan</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Info</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($riwayat) > 0): $no = 1;
                            foreach ($riwayat as $row): ?>
                                <tr class="border-t border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-3"><?= $no++; ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_pengguna']); ?> - (<?= strtoupper($row['role']); ?>)</td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']); ?></td>
                                    <td class="px-4 py-3 font-extralight"><?= htmlspecialchars($row['jumlah']); ?></td>
                                    <td class="px-4 py-3 font-bold"><?= $row['tanggal_pinjam']; ?></td>
                                    <td class="px-4 py-3 font-bold"><?= $row['tanggal_kembali']; ?></td>
                                    <td class="px-4 py-3 italic"><?= htmlspecialchars($row['catatan']); ?> ~</td>
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
                                            <?= $status_label; ?>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <?php if ($row['status'] == 'dipinjam' || $row['status'] == 'dikembalikan' || $row['status'] == 'ditolak'): ?>
                                            <span class="text-xs italic text-gray-400">DONE</span>
                                        <?php else: ?>
                                            <a href="acc_peminjaman.php?id=<?= $row['id_peminjaman']; ?>&aksi=acc" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs mr-1" onclick="return confirm('Setujui peminjaman ini?')">Terima</a>

                                            <a href="acc_peminjaman.php?id=<?= $row['id_peminjaman']; ?>&aksi=tolak" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs" onclick="return confirm('Tolak peminjaman ini?')">Tolak</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="6" class="px-4 py-3 text-center text-gray-400">Belum ada riwayat peminjaman.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php require_once '../../../includes/footer.php';