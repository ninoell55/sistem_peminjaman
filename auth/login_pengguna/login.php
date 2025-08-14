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
    <div class="bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md p-8">
        <h2 class="text-2xl underline font-extrabold tracking-tighter text-white mb-6 text-center">Login Pengguna</h2>

        <?php if (isset($_SESSION['login_error'])): ?>
            <div class="bg-red-700 text-white text-sm p-3 mb-4 rounded-md text-center">
                <?= $_SESSION['login_error'];
                unset($_SESSION['login_error']); ?>
            </div>
        <?php endif; ?>

        <form action="process.php" method="POST" class="space-y-5">
            <div>
                <label for="username" class="block text-gray-300 mb-1">Username</label>
                <input type="text" name="username" id="username" placeholder="Masukkan NIP / NIS (Siswa)..." autocomplete="off" required
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:italic">
            </div>

            <div>
                <label for="password" class="block text-gray-300 mb-1">Password</label>
                <input type="password" name="password" id="password" placeholder="Masukkan Password Acak..." autocomplete="off" required
                    class="w-full px-4 py-2 rounded-md bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder:italic">
            </div>

            <button type="submit" name="login_pengguna"
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-md transition duration-200">
                Masuk
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-400">
            <a href="../login_pages.php" class="hover:underline text-indigo-400">&larr; Kembali ke Pilihan Login</a>
        </div>
    </div>

    <footer class="bg-gray-800/15 text-gray-300 fixed bottom-0 left-0 right-0 w-screen flex justify-center items-center text-center text-sm p-4">
        &copy; <?= date('Y'); ?> Sistem Peminjaman Sekolah
    </footer>
</div>
<?php include '../../includes/footer.php'; ?>