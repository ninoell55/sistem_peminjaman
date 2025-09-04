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
$q_last = query(
    "SELECT p.*, b.nama_barang 
        FROM peminjaman p
            JOIN detail_peminjaman d
                ON d.id_peminjaman = p.id_peminjaman
            JOIN barang b
                ON d.id_barang = b.id_barang
        WHERE id_pengguna='$id_pengguna' 
    ORDER BY created_at DESC LIMIT 5"
);

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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Peminjaman -->
            <div class="bg-indigo-600 rounded-xl p-6 shadow-lg flex items-center gap-4 hover:scale-105 transition-transform duration-200">
                <div class="p-3 bg-indigo-700 rounded-full flex items-center justify-center">
                    <i data-lucide="clipboard-list" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-white text-sm font-medium">Total Peminjaman Saya</span>
                    <span class="text-white text-3xl font-bold mt-1"><?= $active ?></span>
                </div>
            </div>

            <!-- Peminjaman Sudah Dikembalikan -->
            <div class="bg-green-600 rounded-xl p-6 shadow-lg flex items-center gap-4 hover:scale-105 transition-transform duration-200">
                <div class="p-3 bg-green-700 rounded-full flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-white text-sm font-medium">Peminjaman Sudah Dikembalikan</span>
                    <span class="text-white text-3xl font-bold mt-1"><?= $done ?></span>
                </div>
            </div>

            <!-- Peminjaman Belum Dikembalikan -->
            <div class="bg-red-600 rounded-xl p-6 shadow-lg flex items-center gap-4 hover:scale-105 transition-transform duration-200">
                <div class="p-3 bg-red-700 rounded-full flex items-center justify-center">
                    <i data-lucide="alert-circle" class="w-6 h-6 text-white"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-white text-sm font-medium">Peminjaman Belum Dikembalikan</span>
                    <span class="text-white text-3xl font-bold mt-1"><?= $undone ?></span>
                </div>
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
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Waktu Pinjam</th>
                            <th class="px-4 py-3">Waktu Kembali</th>
                            <th class="px-4 py-3">Komoditas</th>
                            <th class="px-4 py-3">Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($q_last) > 0): $no = 1;
                            foreach ($q_last as $row): ?>
                                <tr class="border-t border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-3"><?= $no++ ?></td>

                                    <!-- Tanggal -->
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

                                    <!-- Komoditas -->
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']); ?></td>

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
                                </tr>
                            <?php endforeach;
                        else: ?>
                            <tr>
                                <td colspan="6" class="py-4 px-5 text-center text-gray-500 dark:text-gray-400">Tidak ada data peminjaman terakhir.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php require_once '../../includes/footer.php' ?>

<?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: 'Selamat datang di sistem peminjaman SMKN 1 CIREBON.',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
            background: "#1f2937",
            color: "#f9fafb"
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>
    <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>