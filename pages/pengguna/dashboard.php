<?php
require_once '../../config/functions.php';
$pageTitle = 'Dashboard Pengguna';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../auth/login_pengguna/login.php');
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'] ?? null;
$nama_pengguna = $_SESSION['nama_pengguna'] ?? '';

// Query statistik
$q_total = mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjaman WHERE id_pengguna='$id_pengguna'");
$active = mysqli_fetch_assoc($q_total)['total'] ?? 0;

$q_done = mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjaman WHERE id_pengguna='$id_pengguna' AND status='dikembalikan'");
$done = mysqli_fetch_assoc($q_done)['total'] ?? 0;

$q_undone = mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjaman WHERE id_pengguna='$id_pengguna' AND status='dipinjam'");
$undone = mysqli_fetch_assoc($q_undone)['total'] ?? 0;

// Query 5 peminjaman terakhir
$q_last = query("SELECT * FROM peminjaman WHERE id_pengguna='$id_pengguna' ORDER BY created_at DESC LIMIT 5");

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-16 md:pt-24">

    <main class="flex-1 md:p-6">
        <?php
        $pesan = [
            'Semoga harimu menyenangkan dan proses peminjaman berjalan lancar 😊',
            'Selamat beraktivitas, jangan lupa cek status peminjamanmu!',
            'Semoga semua barang yang kamu pinjam bermanfaat!',
            'Tetap semangat belajar dan berkarya!',
            'Jaga barang pinjaman dengan baik ya!',
            'Semoga hari ini penuh keberuntungan untukmu!',
            'Gunakan fasilitas sekolah dengan bijak dan bertanggung jawab.'
        ];
        $pesan_acak = $pesan[array_rand($pesan)];
        ?>
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold mb-1 text-gray-900 dark:text-white">Selamat datang, <span class="text-indigo-600 dark:text-indigo-400"><?= htmlspecialchars($nama_pengguna) ?></span>!</h1>
                <p class="text-gray-600 dark:text-gray-300 text-base italic flex items-center gap-2">
                    <i data-lucide="smile" class="w-5 h-5 text-indigo-400"></i>
                    <?= $pesan_acak ?>
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-indigo-600 rounded-lg p-6 shadow text-white">
                <div class="text-lg font-semibold">Total Peminjaman Saya</div>
                <div class="text-3xl font-bold mt-2"><?= $active ?></div>
            </div>
            <div class="bg-green-600 rounded-lg p-6 shadow text-white">
                <div class="text-lg font-semibold">Peminjaman Sudah Dikembalikan</div>
                <div class="text-3xl font-bold mt-2"><?= $done ?></div>
            </div>
            <div class="bg-red-600 rounded-lg p-6 shadow text-white">
                <div class="text-lg font-semibold">Peminjaman Belum Dikembalikan</div>
                <div class="text-3xl font-bold mt-2"><?= $undone ?></div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <i data-lucide="clock" class="h-6 w-6 text-indigo-500"></i>
                    Peminjaman Terakhir
                </h2>
            </div>
            <div class="p-3 overflow-x-auto">
                <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                    <thead>
                        <tr class="bg-gray-700 text-left">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Tanggal Pinjam</th>
                            <th class="px-4 py-3">Tanggal Kembali</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($q_last) > 0): $no = 1;
                            foreach ($q_last as $row): ?>
                                <tr class="border-t border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-3"><?= $no++ ?></td>
                                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($row['tanggal_pinjam']) ?></td>
                                    <td class="px-4 py-3 font-light"><?= htmlspecialchars($row['tanggal_kembali']) ?></td>
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
                                    <td class="px-4 py-3 font-extralight"><?= htmlspecialchars($row['catatan'] ?? '-') ?></td>
                                </tr>
                            <?php endforeach;   
                        else: ?>
                            <tr>
                                <td colspan="5" class="py-4 px-5 text-center text-gray-500 dark:text-gray-400">Tidak ada data peminjaman terakhir.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<?php require_once '../../includes/footer.php' ?>