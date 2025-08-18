<?php
require_once '../../../config/functions.php';
$pageTitle = 'Peminjaman Barang';

// Pastikan user sudah login
if (!isset($_SESSION['login_pengguna'])) {
    header('Location: ../../../auth/login_pengguna/login.php');
    exit;
}

// Ambil daftar barang yang bisa dipinjam
$daftar_barang = query("SELECT * FROM barang WHERE jumlah_tersedia > 0 ORDER BY nama_barang ASC");
$id_barang_selected = isset($_GET['id_barang']) ? intval($_GET['id_barang']) : '';

// Ambil data peminjaman hari ini
$id_pengguna = $_SESSION['id_pengguna'] ?? null;
$peminjamanHariIni = query(
    "SELECT 
        p.id_peminjaman, p.tanggal_pinjam, p.tanggal_kembali, p.status, p.catatan, d.id_barang, d.jumlah, b.nama_barang, u.nama_pengguna
            FROM peminjaman p
                JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                JOIN barang b ON d.id_barang = b.id_barang
                JOIN pengguna u ON p.id_pengguna = u.id_pengguna
        WHERE p.id_pengguna = $id_pengguna 
            AND p.tanggal_pinjam >= CURDATE()
            AND p.tanggal_pinjam < CURDATE() + INTERVAL 1 DAY"
);

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-16 md:pt-24">
    <main class="flex-1 md:p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-white">Peminjaman Saya Hari Ini.</h1>
            <p class="text-gray-400">Halaman Daftar Peminjaman Saya Hari Ini.</p>
        </div>

        <!-- Alert -->
        <div class="bg-yellow-700 text-yellow-200 p-4 rounded-lg mb-6">
            <p class="flex items-center gap-2">
                <i data-lucide="message-square-warning"></i>
                Data di bawah hanya akan tampil data peminjaman pada hari ini saja.
                Jika ingin melihat riwayat data peminjaman yang sudah anda pinjam bisa pergi ke menu riwayat peminjaman pada daftar menu.
            </p>
            <p class="font-bold mt-2">
                Diharapkan setiap peminjaman yang sudah selesai mohon lakukan pengubahan data pada tombol Pengembalian.
            </p>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between mb-4">
            <div class="flex space-x-2">
                <a href="../barang/daftar_barang.php" class="flex items-center gap-2 bg-green-600 hover:bg-green-500 px-4 py-2 rounded-lg text-sm font-semibold">
                    <i data-lucide="package"></i>
                    Daftar Komoditas Yang Tersedia
                </a>
                <button onclick="document.getElementById('modalPeminjaman').classList.remove('hidden')" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-500 px-4 py-2 rounded-lg text-sm font-semibold">
                    <i data-lucide="plus"></i>
                    Tambah Peminjaman
                </button>
            </div>

            <!-- Search -->
            <div>
                <input
                    type="text"
                    id="search"
                    placeholder="Cari..."
                    class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>

        <!-- Tabel -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="bg-gray-800 text-gray-300">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Nama Pengguna</th>
                        <th class="px-4 py-2">Komoditas</th>
                        <th class="px-4 py-2">Tanggal Pinjam</th>
                        <th class="px-4 py-2">Tanggal Kembali</th>
                        <th class="px-4 py-2">Info</th>
                        <th class="px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php if (count($peminjamanHariIni) > 0): $no = 1; ?>
                        <?php foreach ($peminjamanHariIni as $pToday): ?>
                            <tr>
                                <td class="px-4 py-2">1</td>
                                <td class="px-4 py-2"><span class="bg-indigo-700 text-white px-2 py-1 rounded"><?= $pToday['nama_pengguna']; ?></span></td>
                                <td class="px-4 py-2"><?= $pToday['nama_barang']; ?></td>
                                <td class="px-4 py-2"><span class="bg-gray-700 px-2 py-1 rounded"><?= $pToday['tanggal_pinjam']; ?></span></td>
                                <td class="px-4 py-2"><span class="bg-gray-700 px-2 py-1 rounded"><?= $pToday['tanggal_kembali']; ?></span></td>
                                <td class="px-4 py-2">ACC</td>
                                <td class="px-4 py-2">
                                    <button class="bg-green-700 hover:bg-green-600 px-3 py-1 rounded"><i data-lucide="eye"></i></button>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="11" class="py-4 px-5 text-center text-gray-500 dark:text-gray-400">Tidak ada data peminjaman hari ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Modal Peminjaman -->
        <div id="modalPeminjaman" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5);">
            <div class="flex items-center justify-center min-h-screen" id="modalPeminjamanBg">
                <div class="bg-gray-800 bg-opacity-90 rounded-xl shadow-lg p-8 w-full max-w-md relative" onclick="event.stopPropagation();">
                    <button onclick="document.getElementById('modalPeminjaman').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-white">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                    <h2 class="text-xl font-bold mb-4 text-white underline">Form Peminjaman Barang</h2>
                    <?php if (isset($error)): ?>
                        <div class="bg-red-600 text-white p-3 rounded mb-4">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>
                    <form action="proses_peminjaman.php" method="POST" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                        <div class="mb-4">
                            <label for="tanggal_pinjam" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Pinjam</label>
                            <input type="datetime-local" name="tanggal_pinjam" id="tanggal_pinjam" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div class="mb-4">
                            <label for="id_barang" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Barang</label>
                            <select name="id_barang" id="id_barang" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach ($daftar_barang as $barang): ?>
                                    <option value="<?= $barang['id_barang'] ?>" <?= ($id_barang_selected == $barang['id_barang']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($barang['nama_barang']) ?> (Stok: <?= $barang['jumlah_tersedia'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="jumlah" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jumlah</label>
                            <input type="number" name="jumlah" id="jumlah" min="1" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                        </div>
                        <div class="mb-4">
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan</label>
                            <textarea name="catatan" id="catatan" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            </textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Ajukan Peminjaman</button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>
<script>
    document.getElementById('modalPeminjamanBg').addEventListener('click', function() {
        document.getElementById('modalPeminjaman').classList.add('hidden');
    });
</script>
<?php require_once '../../../includes/footer.php'; ?>