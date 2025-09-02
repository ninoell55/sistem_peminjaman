<?php
require_once '../config/functions.php';

// ===========================
// PAGE CONFIG
// ===========================
$pageTitle = 'Login - Sistem Peminjaman Barang';

// Redirect jika sudah login
if (isset($_SESSION['login_admin'])) {
    header('Location: ../pages/admin/dashboard.php');
    exit;
} elseif (isset($_SESSION['login_pengguna'])) {
    header('Location: ../pages/pengguna/dashboard.php');
    exit;
}

// ===========================
// FETCH STATISTICS
// ===========================
$totalBarang = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM barang"))['total'];
$totalPengguna = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM pengguna"))['total'];
$totalPeminjaman = mysqli_fetch_assoc(mysqli_query($connection, "SELECT COUNT(*) as total FROM peminjaman"))['total'];

?>
<?php include '../includes/header.php'; ?>

<div class="bg-gradient-to-br from-indigo-900 via-gray-900 to-gray-800 min-h-screen px-6 pt-20 font-sans">

    <!-- HERO SECTION -->
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 items-center md:mt-20 mb-16 md:mb-24 text-center lg:text-left">
        <!-- Ilustrasi -->
        <div class="hidden lg:block">
            <img src="<?= $base_url; ?>assets/images/ilustrasi.png" alt="Ilustrasi Peminjaman" class="w-full max-w-md mx-auto drop-shadow-2xl transition-transform duration-500 hover:scale-105">
        </div>

        <!-- Judul & Deskripsi -->
        <div class="text-white">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight tracking-tight">
                Sistem Peminjaman <br><span class="text-indigo-400">Barang Sekolah</span>
            </h2>
            <p class="text-gray-300 text-base md:text-lg leading-relaxed mb-8">
                Aplikasi web untuk membantu proses peminjaman dan pengembalian barang inventaris secara praktis dan efisien untuk siswa, guru, dan petugas.
            </p>

            <!-- Tombol CTA -->
            <div class="flex flex-wrap gap-4 justify-center lg:justify-start">
                <a href="login_pengguna/login.php" class="bg-gradient-to-r from-indigo-600 to-indigo-500 hover:from-indigo-500 hover:to-indigo-600 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transform transition duration-300 hover:scale-105">
                    Login Sekarang
                </a>
                <a href="https://wa.me/6287740864657" target="_blank" class="border border-gray-400 hover:bg-white hover:text-gray-900 text-gray-200 px-6 py-3 rounded-xl shadow transition duration-300 font-medium">
                    Kontak Petugas
                </a>
            </div>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="max-w-7xl mx-auto grid sm:grid-cols-3 gap-6 text-center mb-16 md:mb-24">
        <?php
        $stats = [
            ['value' => $totalBarang, 'label' => 'Barang Tersedia'],
            ['value' => $totalPengguna, 'label' => 'Pengguna Terdaftar'],
            ['value' => $totalPeminjaman, 'label' => 'Peminjaman Dilakukan']
        ];
        foreach ($stats as $stat): ?>
            <div class="bg-gray-800 rounded-2xl p-8 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                <h3 class="text-4xl font-bold text-indigo-400"><?= $stat['value']; ?>+</h3>
                <p class="text-gray-300 mt-2 text-sm"><?= $stat['label']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- PILIH LOGIN -->
    <div class="max-w-7xl mx-auto text-white text-center mb-10">
        <h2 class="text-2xl md:text-3xl font-semibold mb-4">Masuk sebagai:</h2>
    </div>

    <div class="max-w-4xl mx-auto grid sm:grid-cols-2 gap-6">
        <!-- Card Admin -->
        <a href="login_admin/login.php" class="group bg-gray-800 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 flex flex-col justify-between">
            <div>
                <h3 class="text-white text-xl md:text-2xl font-semibold mb-2 group-hover:text-white">Login Admin / Petugas</h3>
                <p class="text-sm md:text-base text-gray-400 group-hover:text-gray-200">
                    Kelola data barang, peminjaman, pengembalian, dan laporan.
                </p>
            </div>
            <div class="mt-6 text-right">
                <span class="inline-block bg-indigo-700 group-hover:bg-white text-white group-hover:text-indigo-700 px-4 py-2 rounded-full text-sm md:text-base font-medium transition duration-300">
                    Masuk
                </span>
            </div>
        </a>

        <!-- Card Pengguna -->
        <a href="login_pengguna/login.php" class="group bg-gray-800 p-6 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105 flex flex-col justify-between">
            <div>
                <h3 class="text-white text-xl md:text-2xl font-semibold mb-2 group-hover:text-white">Login Pengguna</h3>
                <p class="text-sm md:text-base text-gray-400 group-hover:text-gray-200">
                    Digunakan oleh siswa, guru, dan staff untuk peminjaman barang.
                </p>
            </div>
            <div class="mt-6 text-right">
                <span class="inline-block bg-gray-600 group-hover:bg-white text-white group-hover:text-gray-900 px-4 py-2 rounded-full text-sm md:text-base font-medium transition duration-300">
                    Masuk
                </span>
            </div>
        </a>
    </div>

    <!-- FOOTER -->
    <footer class="mt-20 text-center text-gray-400 text-sm md:text-base py-6 border-t border-gray-700">
        &copy; <?= date('Y'); ?> SMK Negeri 1 CIREBON – Sistem Peminjaman Barang Sekolah.
    </footer>
</div>

<?php include '../includes/footer.php'; ?>