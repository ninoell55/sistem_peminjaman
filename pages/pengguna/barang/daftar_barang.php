<?php
require_once '../../../config/functions.php';
$pageTitle = 'Daftar Barang Peminjaman';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../../auth/login_pengguna/login.php');
    exit;
}

$id_pengguna = $_SESSION['id_pengguna'] ?? null;
$nama_pengguna = $_SESSION['nama_pengguna'] ?? '';

// Query daftar barang yang bisa dipinjam
$q_barang = query("SELECT * FROM barang WHERE jumlah_tersedia > 0 ORDER BY nama_barang ASC");

// Query kategori barang
$q_kategori = query("SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori ASC");

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-16 md:pt-24">
    <main class="flex-1 md:p-6">
        <!-- Header + Search -->
        <div class="bg-white/5 dark:bg-gray-800/50 backdrop-blur-sm border border-gray-700 rounded-xl p-4 mb-6 shadow-lg">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Judul -->
                <h1 class="text-3xl font-extrabold tracking-tight text-indigo-400 drop-shadow-sm flex items-center gap-2">
                    <i data-lucide="box" class="w-6 h-6"></i>
                    Daftar Barang
                </h1>

                <!-- Search & Filter -->
                <div class="flex flex-wrap gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input type="text" id="search" placeholder="Cari barang..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-900/60 border border-gray-700 text-gray-100 placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <i data-lucide="search" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                    </div>
                    <div>
                        <select id="kategori"
                            class="px-4 py-2 rounded-lg bg-gray-900/60 border border-gray-700 text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Kategori</option>
                            <?php foreach ($q_kategori as $kategori): ?>
                                <option value="<?= $kategori['id_kategori'] ?>"><?= htmlspecialchars($kategori['nama_kategori']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid Barang -->
        <div id="dataBarang" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php if (count($q_barang) === 0): ?>
                <div class="col-span-full text-center text-gray-500 py-8">
                    Tidak ada barang yang tersedia untuk dipinjam.
                </div>
            <?php else: ?>
                <?php foreach ($q_barang as $barang): ?>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
                        <img src="../../../assets/uploads/<?= $barang['image']; ?>" alt="Gambar <?= htmlspecialchars($barang['nama_barang']) ?> tidak tersedia"
                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                            onerror="this.style.display='none'">

                        <div class="p-4 flex flex-col flex-grow">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1 truncate"><?= htmlspecialchars($barang['nama_barang']) ?></h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori: <?= htmlspecialchars($kategori[$barang['id_kategori']] ?? '-') ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Lokasi: <?= htmlspecialchars($barang['lokasi'] ?? '-') ?></p>

                            <div class="flex justify-between mb-4">
                                <span class="px-2 py-1 rounded bg-indigo-100 text-indigo-700 text-xs font-semibold">
                                    Stok: <?= htmlspecialchars($barang['jumlah_tersedia']) ?>
                                </span>
                                <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-xs font-semibold">
                                    Kondisi: <?= htmlspecialchars($barang['kondisi'] ?? '-') ?>
                                </span>
                            </div>

                            <a href="../peminjaman/peminjaman.php?id_barang=<?= $barang['id_barang'] ?>"
                                class="mt-auto inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium transition">
                                <i data-lucide="arrow-right-circle" class="w-4 h-4 mr-2"></i> Pinjam Sekarang
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>
</div>
<?php require_once '../../../includes/footer.php'; ?>