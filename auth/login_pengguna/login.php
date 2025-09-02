<?php
require_once '../../config/functions.php';

$pageTitle = 'Login Pengguna - Sistem Peminjaman Barang';

if (isset($_SESSION['login_pengguna'])) {
    header('Location: ../../pages/pengguna/dashboard.php');
    exit;
}
?>

<?php include '../../includes/header.php'; ?>
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-gray-900 to-gray-800 flex items-center justify-center px-4">
    <div class="bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md p-10">
        <h2 class="text-3xl font-extrabold text-white mb-8 text-center underline decoration-indigo-400 decoration-4">
            Login Pengguna
        </h2>

        <form action="process.php" method="POST" class="space-y-6">
            <!-- Username -->
            <div class="relative">
                <label for="username" class="block text-gray-300 mb-1 font-medium">Username</label>
                <div class="relative">
                    <i data-lucide="user" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                    <input type="text" name="username" id="username" placeholder="Masukkan NIP / NIS (Siswa)..." autocomplete="off" required
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:italic">
                </div>
            </div>

            <!-- Password -->
            <div class="relative">
                <label for="password" class="block text-gray-300 mb-1 font-medium">Password</label>
                <div class="relative">
                    <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                    <input type="password" name="password" id="password" placeholder="Masukkan Password Acak..." autocomplete="off" required
                        class="w-full pl-10 pr-4 py-2 rounded-xl bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:italic">
                </div>
            </div>

            <!-- Tombol Login -->
            <button type="submit" name="login_pengguna"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 rounded-xl transition duration-200 shadow-lg hover:shadow-xl">
                Masuk
            </button>
        </form>

        <!-- Link kembali -->
        <div class="mt-6 text-center text-sm text-gray-400">
            <a href="../login_pages.php" class="hover:underline text-indigo-400">&larr; Kembali ke Pilihan Login</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800/30 text-gray-300 fixed bottom-0 left-0 right-0 w-screen flex justify-center items-center text-center text-sm p-3">
        &copy; <?= date('Y'); ?> Sistem Peminjaman Sekolah
    </footer>
</div>
<?php include '../../includes/footer.php'; ?>

<?php if (isset($_SESSION['login_error'])) : ?>
    <script>
        Swal.fire({
            title: 'Login Gagal!',
            text: '<?= $_SESSION['login_error'] ?>',
            icon: 'error',
            confirmButtonColor: '#3b82f6',
            confirmButtonText: 'Coba Lagi'
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>
<?php endif; ?>

<?php if (isset($_GET['logout']) && $_GET['logout'] === 'success') : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Logout',
            text: 'Anda telah keluar dari sistem.',
            timer: 2000,
            showConfirmButton: false,
            timerProgressBar: true,
            background: "#1f2937",
            color: "#f9fafb"
        }).then(() => {
            window.history.replaceState(null, null, window.location.pathname);
        });
    </script>
<?php endif; ?>