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
    <div class="col-span-full text-center text-gray-500 py-8">Tidak ada barang yang ditemukan.</div>
    <?php else:
    foreach ($q_barang as $barang): ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-2xl transition-all duration-300 overflow-hidden group flex flex-col">
            <!-- Gambar kotak -->
            <div class="w-full aspect-square relative overflow-hidden">
                <img src="../../../assets/uploads/<?= $barang['image']; ?>"
                    alt="Gambar <?= htmlspecialchars($barang['nama_barang']) ?> tidak tersedia"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                    onerror="this.style.display='none'">

                <!-- Overlay saat hover -->
                <div class="absolute inset-0 bg-opacity-20 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                    <span class="text-white text-lg font-extrabold px-3 py-1 rounded bg-black/20 bg-opacity-50">
                        <?= htmlspecialchars($barang['nama_barang']) ?>
                    </span>
                </div>

                <!-- Badge Kategori -->
                <span class="absolute top-3 left-3 bg-indigo-600 text-white text-xs font-semibold px-2 py-1 rounded shadow-md">
                    <?= htmlspecialchars($mapKategori[$barang['id_kategori']] ?? '-') ?>
                </span>
            </div>

            <!-- Konten Barang -->
            <div class="p-4 flex flex-col flex-grow">
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2 truncate"><?= htmlspecialchars($barang['nama_barang']) ?></h2>

                <!-- Lokasi dengan icon -->
                <p class="flex items-center text-gray-500 dark:text-gray-400 mb-3 text-sm gap-1">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                    <?= htmlspecialchars($barang['lokasi'] ?? '-') ?>
                </p>

                <!-- Stok & Kondisi -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <span class="flex items-center justify-center text-sm font-semibold px-3 py-1 rounded bg-indigo-100 text-indigo-700 shadow-sm">
                        <i data-lucide="box" class="w-4 h-4 mr-1"></i>
                        <?= htmlspecialchars($barang['jumlah_tersedia']) ?> Stok
                    </span>
                    <span class="flex items-center justify-center text-sm font-semibold px-3 py-1 rounded bg-green-100 text-green-700 shadow-sm">
                        <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                        <?= htmlspecialchars($barang['kondisi'] ?? '-') ?>
                    </span>
                </div>

                <!-- Tombol Pinjam -->
                <a href="../peminjaman/peminjaman.php?id_barang=<?= $barang['id_barang'] ?>"
                    class="mt-auto inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md text-sm font-medium transition">
                    <i data-lucide="arrow-right-circle" class="w-4 h-4 mr-2"></i> Pinjam Sekarang
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>