<?php
require_once '../../../config/functions.php';
$pageTitle = 'Laporan Peminjaman';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['login_admin'])) {
    header('Location: ../../../auth/login_admin/login.php');
    exit;
}

$where = "";
if (!empty($_GET['tanggal_dari']) && !empty($_GET['tanggal_sampai'])) {
    $dari = $_GET['tanggal_dari'];
    $sampai = $_GET['tanggal_sampai'];
    $where = "WHERE waktu_pinjam BETWEEN '$dari' AND '$sampai'";
}

$laporan = query(
    "SELECT 
        p.*, u.*, b.nama_barang, d.jumlah
            FROM peminjaman p
                JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                JOIN barang b ON d.id_barang = b.id_barang
            $where
            ORDER BY p.waktu_pinjam DESC"
);

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold"><?= $pageTitle; ?>.</h1>
                <p class="text-gray-400 tracking-widest italic">~ Halaman <?= $pageTitle; ?>.</p>
            </div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b bg-indigo-600 rounded-t-lg">
                <span class="text-lg font-semibold text-white">Filter Laporan</span>
            </div>
            <div class="p-6">
                <form method="GET" class="bg-gray-800 p-4 rounded-lg shadow-md mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                        <!-- Dari Tanggal -->
                        <div>
                            <label for="tanggal_dari" class="block text-sm font-medium text-gray-200 mb-1">Dari Tanggal</label>
                            <input type="date"
                                class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                id="tanggal_dari"
                                name="tanggal_dari"
                                value="<?php echo isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : ''; ?>">
                        </div>

                        <!-- Sampai Tanggal -->
                        <div>
                            <label for="tanggal_sampai" class="block text-sm font-medium text-gray-200 mb-1">Sampai Tanggal</label>
                            <input type="date"
                                class="w-full border border-gray-600 bg-gray-900 text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                id="tanggal_sampai"
                                name="tanggal_sampai"
                                value="<?php echo isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : ''; ?>">
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="flex gap-2">
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-500 text-white font-semibold px-4 py-2 rounded shadow">
                                Cari
                            </button>
                            <a href="laporan.php"
                                class="flex-1 text-center bg-gray-700 hover:bg-gray-600 text-gray-200 font-medium px-4 py-2 rounded shadow">
                                Reset Filter
                            </a>
                        </div>

                        <!-- Export -->
                        <div class="flex gap-2">
                            <button type="button" class="flex-1 bg-green-600 text-white px-4 py-2 rounded shadow opacity-60 cursor-not-allowed" disabled>
                                Export Excel
                            </button>
                            <button type="button" class="flex-1 bg-red-600 text-white px-4 py-2 rounded shadow opacity-60 cursor-not-allowed" disabled>
                                Export PDF
                            </button>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                        <thead>
                            <tr class="bg-gray-700 text-left">
                                <th class="px-4 py-3">#</th>
                                <th class="px-4 py-3">Nama Pengguna</th>
                                <th class="px-4 py-3">Komoditas</th>
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Waktu Pinjam</th>
                                <th class="px-4 py-3">Waktu Kembali</th>
                                <th class="px-4 py-3">Petugas</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($laporan) > 0): $no = 1;
                                foreach ($laporan as $row): ?>
                                    <tr class="border-t border-gray-700 hover:bg-gray-700">
                                        <td class="px-4 py-3"><?= $no++; ?></td>
                                        <td class="px-4 py-3">
                                            <span class="bg-indigo-700 text-white px-2 py-1 rounded">
                                                <?= htmlspecialchars($row['nama_pengguna']); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']); ?></td>

                                        <?php
                                        $datetime = $row['waktu_pinjam'];

                                        // format tanggal 
                                        $date = new DateTime($datetime);
                                        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                                        $dayName = $days[(int)$date->format('w')];
                                        $day = $date->format('d');
                                        $monthName = $months[(int)$date->format('m') - 1];
                                        $year = $date->format('Y');

                                        $formattedDate = "$dayName, $day $monthName $year";
                                        ?>

                                        <!-- Tanggal -->
                                        <td class="px-4 py-3"><?= $formattedDate ?></td>

                                        <!-- Waktu Pinjam -->
                                        <td class="px-4 py-3 font-bold">
                                            <span class="inline-flex items-center gap-1 bg-gray-700 px-2 py-1 rounded">
                                                <i data-lucide="clock" class="w-4 h-4"></i>
                                                <?= $row['waktu_pinjam']; ?>
                                            </span>
                                        </td>

                                        <!-- Waktu Kembali -->
                                        <td class="px-4 py-3 font-bold">
                                            <?php if (empty($row['waktu_kembali'])): ?>
                                                <span class="inline-flex items-center gap-1 bg-yellow-700 px-2 py-1 rounded">
                                                    <i data-lucide="loader" class="w-4 h-4 animate-spin"></i>
                                                    <span>Masih berlangsung</span>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1 bg-gray-700 px-2 py-1 rounded">
                                                    <i data-lucide="clock" class="w-4 h-4"></i>
                                                    <?= $row['waktu_kembali']; ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Petugas -->
                                        <td class="px-4 py-3">
                                            <?php if ($row['status'] == 'menunggu'): ?>
                                                <button disabled title="Menunggu validasi petugas"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-yellow-500 hover:bg-yellow-600 text-white transition">
                                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                                </button>
                                            <?php elseif ($row['status'] == 'dipinjam' || $row['status'] == 'dikembalikan'): ?>
                                                <button disabled title="Sudah divalidasi oleh petugas"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-700 text-white">
                                                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                                                </button>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Aksi -->
                                        <td class="px-4 py-3">
                                            <?php if ($row['status'] == 'menunggu'): ?>
                                                <a title="Validasi?" href="acc_peminjaman.php?id=<?= $row['id_peminjaman']; ?>&aksi=acc" onclick="return confirm('Setujui peminjaman ini?')"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-500 hover:bg-blue-600 text-white transition">
                                                    <i data-lucide="user-lock" class="w-4 h-4"></i>
                                                </a>
                                            <?php endif; ?>
                                            <button title="Detail laporan" onclick="document.getElementById('detailModal').classList.remove('hidden')" type="button"
                                                class="inline-flex items-center gap-1 w-8 h-8 bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded text-xs">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
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
        <!-- Modal Overlay -->
        <div id="detailModal" class="fixed inset-0 items-center justify-center z-50 hidden" style="background: rgba(0,0,0,0.5);">
            <div class="flex items-center justify-center min-h-screen" id="detailModalBg">
                <!-- Modal Content -->
                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-2xl w-11/12 max-w-5xl p-6" onclick="event.stopPropagation();">
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
                        <h2 class="text-xl font-semibold">Detail Peminjaman</h2>
                        <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-200">
                            <i data-lucide="x"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div>
                            <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4">
                                Data di bawah adalah detail data pengguna.
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Nomor Identitas Pengguna</p>
                                    <div class="bg-gray-800 px-3 py-2 rounded-md"><?= $row['nip_nis']; ?></div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Nama Pengguna</p>
                                    <div class="bg-gray-800 px-3 py-2 rounded-md"><?= $row['nama_pengguna']; ?></div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Jurusan</p>
                                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                        <i data-lucide="book-open" class="w-4 h-4"></i> <?= $row['jurusan']; ?>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Kelas</p>
                                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                        <i data-lucide="building-2" class="w-4 h-4"></i> <?= $row['kelas']; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div>
                            <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4">
                                Data di bawah adalah detail data riwayat peminjaman.
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Nama Komoditas</p>
                                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                        <i data-lucide="package" class="w-4 h-4"></i> <?= $row['nama_barang']; ?>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Jumlah</p>
                                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                        <i data-lucide="hash" class="w-4 h-4"></i> <?= $row['jumlah']; ?>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Waktu Pinjam</p>
                                        <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md min-h-[38px]">
                                            <i data-lucide="clock" class="w-4 h-4"></i> <?= $row['waktu_pinjam']; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Waktu Kembali</p>
                                        <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md min-h-[38px]">
                                            <i data-lucide="clock" class="w-4 h-4"></i> <?= $row['waktu_kembali']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Status</p>
                                    <div class="bg-gray-800 px-3 py-2 rounded-md"><?= $row['status']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-400">Catatan</p>
                        <textarea disabled class="w-full bg-gray-800 text-white px-3 py-2 rounded-md" rows="4"><?= $row['catatan']; ?></textarea>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end mt-6 border-t border-gray-700 pt-4">
                        <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    document.getElementById('detailModalBg').addEventListener('click', function() {
        document.getElementById('detailModal').classList.add('hidden');
    });
</script>
<?php require_once '../../../includes/footer.php'; ?>