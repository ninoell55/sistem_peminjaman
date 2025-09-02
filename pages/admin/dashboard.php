<?php
require_once '../../config/functions.php';
$pageTitle = 'Dashboard Admin';

// Cek apakah pengguna sudah login (admin atau pengguna)
if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

// Ambil total admin
$totalAdmin = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM admin"))['total'];

// Ambil total pengguna
$totalPengguna = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM pengguna"))['total'];

// Ambil total barang
$totalBarang = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM barang"))['total'];

// Data admin yang sedang login
$adminUsername = $_SESSION['username'] ?? '-';
$dataAdmin = mysqli_fetch_assoc(mysqli_query($connection, "SELECT * FROM admin WHERE username='$adminUsername'"));

// Ambil pengguna terbaru yang meminjam
// Menggunakan query untuk mendapatkan pengguna terbaru yang meminjam di hari ini
$latestPeminjamQuery = "SELECT 
                            p.*, pg.nama_pengguna, pg.username 
                        FROM peminjaman p 
                        JOIN pengguna pg ON p.id_pengguna = pg.id_pengguna
                            WHERE p.waktu_pinjam = CURDATE() 
                            AND p.status = 'dipinjam' 
                                ORDER BY p.created_at DESC 
                        LIMIT 1";

$latestPeminjam = mysqli_fetch_assoc(mysqli_query($connection, $latestPeminjamQuery));

$selectedYear = isset($_GET['tahun']) ? (int)$_GET['tahun'] : date('Y');

$query = "SELECT 
            MONTH(waktu_pinjam) AS bulan, COUNT(*) AS total 
          FROM peminjaman 
            WHERE YEAR(waktu_pinjam) = $selectedYear 
          GROUP BY bulan 
          ORDER BY bulan";

$result = mysqli_query($connection, $query);

$chartData = array_fill(1, 12, 0); // Awal: semua bulan 0

while ($row = mysqli_fetch_assoc($result)) {
    $chartData[(int)$row['bulan']] = (int)$row['total'];
}

require_once '../../includes/header.php';
require_once '../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-16 md:pt-24">

    <main class="flex-1 md:p-6">
        <h1 class="text-3xl font-bold mb-2">Beranda,</h1>
        <p class="text-gray-400 mb-6">Halaman Beranda.</p>

        <!-- Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">

            <!-- Total Admin -->
            <div class="bg-gray-800 rounded-2xl p-5 shadow flex flex-col justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-700 p-2 rounded-full">
                        <i data-lucide="shield-check" class="text-indigo-400 w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Administrator</p>
                        <h2 class="text-2xl font-bold text-white"><?= $totalAdmin ?></h2>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="<?= $base_url; ?>pages/admin/set_admin/read.php" title="Lihat detail" class="text-gray-400 hover:text-white transition">
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Total Pengguna -->
            <div class="bg-gray-800 rounded-2xl p-5 shadow flex flex-col justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-700 p-2 rounded-full">
                        <i data-lucide="user" class="text-green-400 w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Pengguna</p>
                        <h2 class="text-2xl font-bold text-white"><?= $totalPengguna ?></h2>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="<?= $base_url; ?>pages/admin/set_pengguna/read.php" title="Lihat detail" class="text-gray-400 hover:text-white transition">
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Total Komoditas -->
            <div class="bg-gray-800 rounded-2xl p-5 shadow flex flex-col justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-gray-700 p-2 rounded-full">
                        <i data-lucide="bookmark" class="text-red-400 w-6 h-6"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Total Komoditas</p>
                        <h2 class="text-2xl font-bold text-white"><?= $totalBarang ?></h2>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="<?= $base_url; ?>pages/admin/komoditas/read.php" title="Lihat detail" class="text-gray-400 hover:text-white transition">
                        <i data-lucide="arrow-right" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Pengguna Terbaru Meminjam -->
            <div class="bg-gray-800 rounded-2xl p-5 shadow flex flex-col justify-between text-white">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Pengguna Terbaru Meminjam</p>
                    <p class="text-lg font-semibold"><?= $latestPeminjam['nama_pengguna'] ?? '-' ?></p>
                </div>
                <div class="flex justify-between items-center mt-4">
                    <a href="#" title="Lihat detail" class="text-gray-400 hover:text-white transition">
                        <p class="text-sm text-gray-500"><?= $latestPeminjam['username'] ?? '-' ?></p>
                    </a>
                    <a href="<?= $base_url; ?>pages/admin/peminjaman/peminjaman.php" class="text-sm px-4 py-1 border border-gray-400 text-gray-300 rounded-full hover:bg-white hover:text-gray-900 transition">
                        Lihat Daftar
                    </a>
                </div>
            </div>
        </div>

        <!-- Grafik -->
        <div class="bg-gray-800 p-6 rounded-2xl shadow-md mt-6">
            <h3 class="text-lg font-semibold mb-4">Grafik Peminjaman Tahun <?= $selectedYear ?></h3>

            <form method="GET" class="mb-4 flex items-center gap-2">
                <input type="number" name="tahun" value="<?= $selectedYear ?>" class="bg-gray-900 border border-gray-700 text-white px-3 py-2 rounded w-32" placeholder="Tahun" />
                <button type="submit" class="bg-indigo-500 hover:bg-indigo-600 px-4 py-2 rounded text-sm">Tampilkan</button>
            </form>

            <canvas style="height: 350px;" id="peminjamanChart" class="w-full h-full"></canvas>
        </div>

    </main>
</div>

<script>
    const ctx = document.getElementById('peminjamanChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= json_encode(array_values($chartData)) ?>,
                backgroundColor: '#6366f1',
                borderRadius: 6,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#cbd5e1'
                    },
                    grid: {
                        color: '#475569'
                    }
                },
                x: {
                    ticks: {
                        color: '#cbd5e1'
                    },
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        color: '#cbd5e1'
                    }
                }
            }
        }
    });
</script>

<?php require_once '../../includes/footer.php'; ?>

<?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: 'Selamat datang di dashboard admin dan petugas.',
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