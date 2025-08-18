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

$search   = $_GET['search'] ?? '';
$kategori = $_GET['kategori'] ?? '';

// Query dasar
$sql = "SELECT * FROM barang WHERE jumlah_tersedia > 0";

// filter pencarian
if (!empty($search)) {
    $sql .= " AND nama_barang LIKE '%$search%'";
}

// filter kategori
if (!empty($kategori)) {
    $sql .= " AND id_kategori = '$kategori'";
}

$sql .= " ORDER BY nama_barang ASC";

$q_barang = query($sql);

// Ambil semua kategori untuk mapping nama
$q_kategori = query("SELECT id_kategori, nama_kategori FROM kategori");
$mapKategori = [];
foreach ($q_kategori as $kat) {
    $mapKategori[$kat['id_kategori']] = $kat['nama_kategori'];
}
?>

<?php if (count($q_barang) === 0): ?>
    <div class="col-span-full text-center text-gray-500 py-8">Tidak ada barang yang ditemukan.</div>;
    <?php else:
    foreach ($q_barang as $barang): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group">
            <img src="../../../assets/uploads/<?= $barang['image']; ?>"
                alt="Gambar <?= htmlspecialchars($barang['nama_barang']) ?> tidak tersedia"
                class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                onerror="this.style.display='none'">

            <div class="p-4 flex flex-col flex-grow">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1 truncate"><?= htmlspecialchars($barang['nama_barang']) ?></h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori: <?= htmlspecialchars($mapKategori[$barang['id_kategori']] ?? '-') ?></p>
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