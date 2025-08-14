<aside id="sidebar"
    class="fixed top-14 md:top-16 bottom-0 left-0 w-64 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-200 border-r border-gray-200 dark:border-gray-700 shadow-md flex flex-col justify-between px-4 py-6
           transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-50">

    <!-- Bagian Atas Sidebar -->
    <div class="space-y-10 overflow-y-auto">
        <!-- Logo -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400">Inventrack</h1>
            <span class="text-sm text-gray-400 dark:text-gray-500">v1.0.0</span>
        </div>

        <!-- Menu Utama -->
        <?php if ($_SESSION['role'] === 'administrator' || $_SESSION['role'] === 'petugas'): ?>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Menu</p>
                <a href="<?= $base_url ?>pages/admin/dashboard.php" class="flex items-center px-4 py-2 rounded-md <?= isActive('dashboard.php') ? 'bg-indigo-600 text-white hover:bg-indigo-800 transition ease-in-out' : 'dark:hover:bg-gray-800' ?>">
                    <i data-lucide="home" class="w-5 h-5 mr-2"></i>
                    Beranda
                </a>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Data Master</p>
                <div class="space-y-3">
                    <!-- Komoditas -->
                    <a href="<?= $base_url ?>pages/admin/komoditas/read.php" class="flex items-center px-4 py-2 rounded-md <?= isActive('/komoditas/') ? 'bg-indigo-600 text-white hover:bg-indigo-800 transition ease-in-out' : 'dark:hover:bg-gray-800' ?>">
                        <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                        Komoditas
                    </a>
                    <!-- Dropdown Peminjaman -->
                    <details class="group" <?php if (isActive('/peminjaman/')) echo 'open'; ?>>
                        <summary class="flex items-center justify-between cursor-pointer px-4 py-2 rounded-md transition ease-in-out
                            <?php echo isActive('/peminjaman/') ? 'bg-indigo-600 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                            <span class="flex items-center">
                                <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                                Peminjaman
                            </span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-10 mt-3 space-y-3 text-sm">
                            <li>
                                <a href="<?= $base_url ?>pages/admin/peminjaman/peminjaman.php" class="block px-2 py-1 rounded transition ease-in-out <?= isActive('/peminjaman/peminjaman.php') ? 'bg-indigo-600/40 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                                    Peminjaman Hari ini
                                </a>
                            </li>
                            <li>
                                <a href="<?= $base_url ?>pages/admin/peminjaman/riwayat.php" class="block px-2 py-1 rounded transition ease-in-out <?= isActive('/peminjaman/riwayat.php') ? 'bg-indigo-600/40 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                                    Riwayat Peminjaman
                                </a>
                            </li>
                            <li>
                                <a href="<?= $base_url ?>pages/admin/peminjaman/laporan.php" class="block px-2 py-1 rounded transition ease-in-out <?= isActive('/peminjaman/laporan.php') ? 'bg-indigo-600/40 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                                    Laporan
                                </a>
                            </li>
                        </ul>
                    </details>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Manajemen Akun</p>
                <div class="space-y-3">
                    <a href="<?= $base_url; ?>pages/admin/set_admin/read.php" class="flex items-center px-4 py-2 rounded-md <?= isActive('/set_admin/') ? 'bg-indigo-600 text-white hover:bg-indigo-800 transition ease-in-out' : 'dark:hover:bg-gray-800' ?>">
                        <i data-lucide="shield" class="w-5 h-5 mr-2"></i>
                        Administrator
                    </a>
                    <a href="<?= $base_url; ?>pages/admin/set_pengguna/read.php" class="flex items-center px-4 py-2 rounded-md <?= isActive('/set_pengguna/') ? 'bg-indigo-600 text-white hover:bg-indigo-800 transition ease-in-out' : 'dark:hover:bg-gray-800' ?>">
                        <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                        Pengguna
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Menu</p>
                <a href="<?= $base_url ?>pages/pengguna/dashboard.php" class="flex items-center px-4 py-2 rounded-md <?= isActive('dashboard.php') ? 'bg-indigo-600 text-white hover:bg-indigo-800 transition ease-in-out' : 'dark:hover:bg-gray-800' ?>">
                    <i data-lucide="home" class="w-5 h-5 mr-2"></i>
                    Beranda
                </a>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase text-gray-500 dark:text-gray-400 mb-3">Daftar Menu</p>
                <div class="space-y-3">
                    <!-- Barang -->
                    <a href="<?= $base_url ?>pages/pengguna/peminjaman/daftar_barang.php" class="flex items-center px-4 py-2 rounded-md <?= isActive('daftar_barang.php') ? 'bg-indigo-600 text-white hover:bg-indigo-800 transition ease-in-out' : 'dark:hover:bg-gray-800' ?>">
                        <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                        Daftar Barang
                    </a>
                    <!-- Dropdown Peminjaman -->
                    <details class="group" <?php if (isActive('/peminjaman/')) echo 'open'; ?>>
                        <summary class="flex items-center justify-between cursor-pointer px-4 py-2 rounded-md transition ease-in-out
                            <?php echo isActive('/peminjaman/') ? 'bg-indigo-600 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                            <span class="flex items-center">
                                <i data-lucide="clipboard-list" class="w-5 h-5 mr-2"></i>
                                Peminjaman
                            </span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform group-open:rotate-180"></i>
                        </summary>
                        <ul class="pl-10 mt-3 space-y-3 text-sm">
                            <li>
                                <a href="<?= $base_url ?>pages/pengguna/peminjaman/peminjaman.php" class="block px-2 py-1 rounded transition ease-in-out <?= isActive('peminjaman.php') ? 'bg-indigo-600/40 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                                    Peminjaman Saya Hari ini
                                </a>
                            </li>
                            <li>
                                <a href="<?= $base_url ?>pages/pengguna/peminjaman/riwayat.php" class="block px-2 py-1 rounded transition ease-in-out <?= isActive('/peminjaman/riwayat.php') ? 'bg-indigo-600/40 text-white hover:bg-indigo-800' : 'hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                                    Riwayat Peminjaman
                                </a>
                            </li>
                        </ul>
                    </details>
                </div>
                <!-- Divider dan Info Login Pengguna -->
                <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['siswa', 'guru', 'staff'])): ?>
                    <div class="my-6 border-t border-gray-300 dark:border-gray-700"></div>
                    <div class="flex items-center gap-2 px-2 py-3 text-xs text-gray-500 dark:text-gray-400">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Login sebagai:
                        <span class="font-semibold text-indigo-600 dark:text-indigo-400"><?= htmlspecialchars($_SESSION['nama_pengguna'] ?? '-') ?></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tombol Logout di Bawah -->
    <div class="border-t border-gray-400 pt-4">
        <!-- cek untuk logout admin atau pengguna -->
        <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['administrator', 'petugas'])): ?>
            <a href="<?= $base_url ?>auth/login_admin/logout.php" class="flex items-center px-4 py-2 rounded-md text-red-600 hover:bg-red-100 dark:hover:bg-red-800" onclick="return confirm('Yakin ingin keluar?');">
                <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                Keluar
            </a>
        <?php elseif (isset($_SESSION['role']) && in_array($_SESSION['role'], ['siswa', 'guru', 'staff'])): ?>
            <a href="<?= $base_url ?>auth/login_pengguna/logout.php" class="flex items-center px-4 py-2 rounded-md text-red-600 hover:bg-red-100 dark:hover:bg-red-800" onclick="return confirm('Yakin ingin keluar?');">
                <i data-lucide="log-out" class="w-5 h-5 mr-2"></i>
                Keluar
            </a>
        <?php endif; ?>
    </div>
</aside>