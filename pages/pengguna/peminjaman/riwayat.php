<?php
require_once '../../../config/functions.php';
$pageTitle = 'Riwayat Peminjaman Saya';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../../auth/login_pengguna/login.php');
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'] ?? null;
$nama_pengguna = $_SESSION['nama_pengguna'] ?? '';

$where = "WHERE p.id_pengguna = $id_pengguna";
if (!empty($_GET['tanggal_dari']) && !empty($_GET['tanggal_sampai'])) {
    $dari = $_GET['tanggal_dari'];
    $sampai = $_GET['tanggal_sampai'];
    $where = " AND waktu_pinjam BETWEEN '$dari' AND '$sampai'";
}

$riwayat = query(
    "SELECT 
        p.*, u.*, b.nama_barang, d.jumlah
            FROM peminjaman p
                JOIN pengguna u ON p.id_pengguna = u.id_pengguna
                JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                JOIN barang b ON d.id_barang = b.id_barang
            $where
        ORDER BY p.created_at DESC"
);

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-16 md:pt-24">
    <main class="flex-1 md:p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold"><?= $pageTitle; ?>.</h1>
                <p class="text-gray-400 tracking-widest italic">~ Halaman Daftar <?= $pageTitle; ?>.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
            <!-- Judul -->
            <h1 class="text-2xl font-semibold mb-6"><?= $pageTitle; ?></h1>
            <!-- Info Box -->
            <div class="bg-blue-400 text-gray-900 p-4 rounded-lg mb-6 border-l-4 border-blue-200">
                <p class="mb-2">
                    Tabel di bawah adalah daftar riwayat peminjaman yang sudah dilakukan oleh Anda.
                </p>
            </div>

            <!-- Filter Box -->
            <div class="bg-gray-800 p-4 rounded-lg shadow-md">
                <form method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Dari -->
                        <div>
                            <label class="block mb-1">Dari Tanggal</label>
                            <input type="date" class="w-full p-2 rounded bg-gray-700 text-gray-200 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500" id="tanggal_dari" name="tanggal_dari" value="<?php echo isset($_GET['tanggal_dari']) ? $_GET['tanggal_dari'] : ''; ?>">
                        </div>

                        <!-- Sampai -->
                        <div>
                            <label class="block mb-1">Sampai Tanggal</label>
                            <input type="date" class="w-full p-2 rounded bg-gray-700 text-gray-200 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-500" id="tanggal_sampai" name="tanggal_sampai" value="<?php echo isset($_GET['tanggal_sampai']) ? $_GET['tanggal_sampai'] : ''; ?>">
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="mt-4 flex gap-2">
                        <button class="flex-1 bg-blue-600 hover:bg-blue-500 text-white p-2 rounded">Cari</button>
                        <a href="riwayat.php" class="bg-yellow-600 hover:bg-yellow-500 text-gray-900 p-2 rounded">Reset Filter</a>
                    </div>
                </form>
            </div>

            <!-- Tabel -->
            <div class="p-6 overflow-x-auto">
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
                        <?php if (count($riwayat) > 0): $no = 1; ?>
                            <?php foreach ($riwayat as $row): ?>
                                <tr class="border-t border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-3"><?= $no++; ?></td>
                                    <td class="px-4 py-3">
                                        <span class="bg-indigo-700 text-white px-2 py-1 rounded">
                                            <?= htmlspecialchars($row['nama_pengguna']); ?>
                                        </span>
                                    </td>
                                    <!-- Nama Barang -->
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']); ?></td>

                                    <!-- format tanggal -->
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
                                    <td class="px-4 py-3"><?= $formattedDate; ?></td>

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
                                        <?php if ($row['status'] == 'menunggu' || $row['status'] == 'menunggu_pengembalian'): ?>
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
                                    <td class="px-4 py-3 flex items-center gap-2">

                                        <button title="Detail peminjaman" type="button"
                                            class="openDetail cursor-pointer inline-flex items-center justify-center w-8 h-8 rounded bg-green-700 hover:bg-green-800 text-white transition"
                                            data-nip="<?= $row['nip_nis']; ?>"
                                            data-nama="<?= $row['nama_pengguna']; ?>"
                                            data-role="<?= $row['role'] ?>"
                                            data-jurusan="<?= $row['jurusan']; ?>"
                                            data-kelas="<?= $row['kelas']; ?>"
                                            data-barang="<?= $row['nama_barang']; ?>"
                                            data-jumlah="<?= $row['jumlah']; ?>"
                                            data-tanggal="<?= $formattedDate; ?>"
                                            data-pinjam="<?= $row['waktu_pinjam']; ?>"
                                            data-kembali="<?= $row['waktu_kembali']; ?>"
                                            data-status="<?= $row['status']; ?>"
                                            data-catatan="<?= $row['catatan']; ?>">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </button>

                                        <?php if ($row['status'] == 'dipinjam'): ?>
                                            <a title="Ajukan Pengembalian"
                                                href="ajukan_pengembalian.php?id=<?= $row['id_peminjaman']; ?>&aksi=ajukan"
                                                onclick="return confirm('Ajukan pengembalian untuk peminjaman ini?')"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-yellow-500 hover:bg-yellow-600 text-white transition">
                                                <i data-lucide="check" class="w-4 h-4 bg-black rounded-full"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($row['status'] == 'menunggu' || $row['status'] == 'dipinjam'): ?>
                                            <button
                                                type="button"
                                                title="Edit Peminjaman"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-700 hover:bg-green-800 text-white transition"
                                                data-row='<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>'
                                                onclick="openEditPeminjamanModal(this)">
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="11" class="py-4 px-5 text-center text-gray-500 dark:text-gray-400">
                                    Tidak ada data riwayat peminjaman.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
                            <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4 flex items-center gap-2">
                                <i data-lucide="info"></i>
                                <span>Data di bawah adalah detail data pengguna.</span>
                            </div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Nomor Identitas Pengguna</p>
                                        <div id="detailNO" class="bg-gray-800 px-3 py-2 rounded-md"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Nama Pengguna</p>
                                        <div id="detailNama" class="bg-gray-800 px-3 py-2 rounded-md"></div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Jurusan</p>
                                        <div id="detailJurusan" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Kelas</p>
                                        <div id="detailKelas" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Role</p>
                                    <div id="detailRole" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div>
                            <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4 flex items-center gap-2">
                                <i data-lucide="info"></i>
                                <span>Data di bawah adalah detail data peminjaman.</span>
                            </div>
                            <div class="space-y-3">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Nama Komoditas</p>
                                        <div id="detailBarang" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Jumlah</p>
                                        <div id="detailJumlah" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Tanggal</p>
                                    <div id="detailTanggal" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Waktu Pinjam</p>
                                        <div id="detailPinjam" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md min-h-[38px]"></div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Waktu Kembali</p>
                                        <div id="detailKembali" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md min-h-[38px]"></div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Status</p>
                                    <div id="detailStatus" class="bg-gray-800 px-3 py-2 rounded-md"></div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Catatan</p>
                                    <textarea id="detailCatatan" class="w-full bg-gray-800 text-white px-3 py-2 rounded-md" rows="4" disabled></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer di luar grid -->
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

<?php require_once '../../../includes/footer.php'; ?>