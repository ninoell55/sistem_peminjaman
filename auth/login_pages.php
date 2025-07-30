<?php
require_once '../config/functions.php';

$pageTitle = 'Login - Sistem Peminjaman Barang';

if (isset($_SESSION['login_admin'])) {
    header('Location: ../pages/admin/dashboard.php');
    exit;
} elseif (isset($_SESSION['login_pengguna'])) {
    header('Location: ../pages/pengguna/dashboard.php');
    exit;
}

?>

<?php include '../includes/header.php'; ?>
<div class="bg-gray-900 min-h-screen flex items-center justify-center px-4">
    <div class="bg-gray-800 shadow-2xl rounded-2xl p-10 w-full max-w-lg">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-semibold text-white mb-2">Sistem Peminjaman Barang</h1>
            <p class="text-sm text-gray-400 italic">Silakan pilih jenis login sesuai peran Anda</p>
        </div>

        <div class="space-y-4">
            <a href="login_admin/login.php"
                class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl text-lg font-medium transition duration-200 shadow-sm">
                Login Admin / Petugas
            </a>
            <a href="login_pengguna/login.php"
                class="block text-center bg-gray-700 hover:bg-gray-600 text-white py-3 rounded-xl text-lg font-medium transition duration-200 shadow-sm">
                Login Pengguna (Siswa / Guru / Staff)
            </a>
        </div>

        <div class="mt-8 text-center text-gray-500 text-xs">
            &copy; <?= date('Y'); ?> SMK Peminjaman App. All rights reserved.
        </div>
    </div>

    <footer class="bg-gray-800/15 text-gray-300 fixed bottom-0 left-0 right-0 w-screen flex justify-center items-center text-center text-sm p-4">
        &copy; <?= date('Y'); ?> Sistem Peminjaman Sekolah
    </footer>
</div>
<?php include '../includes/footer.php'; ?>