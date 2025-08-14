<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Sistem Peminjaman Sekolah'; ?></title>
    <!-- ICON-Website -->
    <link rel="icon" type="image/x-icon" href="<?= $base_url ?>assets/images/logo_smk.png" />
    <!-- MyCSS -->
    <link href="<?= $base_url; ?>/assets/css/output.css" rel="stylesheet">
    <!-- Icon -->
    <script src="<?= $base_url ?>assets/js/lucide.min.js"></script>
    <!-- Chart -->
    <script src="<?= $base_url ?>assets/js/chart.js"></script>
</head>

<body class="bg-gray-900 text-white">
    <header class="bg-gray-800 text-white p-4 shadow-md fixed top-0 left-0 right-0 z-50">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2">
                <button id="toggleSidebar"
                    class="md:hidden text-gray-700 dark:text-gray-200 focus:outline-none mr-2">
                    <!-- Icon hamburger -->
                    <i class="w-5 h-5" data-lucide="menu"></i>
                </button>
                <?php if (isset($_SESSION['login_admin'])): ?>
                    <a href="<?= $base_url; ?>pages/admin/dashboard.php" class="text-sm md:text-xl font-semibold">SMKN 1 KOTA CIREBON</a>
                <?php elseif (isset($_SESSION['login_pengguna'])): ?>
                    <a href="<?= $base_url; ?>pages/pengguna/dashboard.php" class="text-sm md:text-xl font-semibold">SMKN 1 KOTA CIREBON</a>
                <?php else: ?>
                    <a href="<?= $base_url; ?>" class="text-sm md:text-xl font-semibold">Sistem Peminjaman Sekolah</a>
                <?php endif; ?>
            </div>
            <div class="text-sm text-gray-300">
                <?php if (isset($_SESSION['username'])): ?>
                    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'administrator' || $_SESSION['role'] === 'petugas')): ?>
                        Logged in as <span class="font-medium"><?= htmlspecialchars($_SESSION['nama_admin']); ?></span>
                    <?php elseif (isset($_SESSION['role']) && ($_SESSION['role'] === 'siswa' || $_SESSION['role'] === 'guru' || $_SESSION['role'] === 'staff')): ?>
                        Logged in as <span class="font-medium"><?= htmlspecialchars($_SESSION['nama_pengguna']); ?></span>
                    <?php else: ?>
                        <span class="text-yellow-400">Unknown Role</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-red-400">Not logged in</span>
                <?php endif; ?>
            </div>
        </div>
    </header>