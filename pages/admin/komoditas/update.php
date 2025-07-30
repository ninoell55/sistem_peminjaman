<?php
require_once '../../../config/functions.php';
$pageTitle = 'Update Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

// Ambil data kategori
$kategori = query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Ambil data barang berdasarkan ID
$id_barang = isset($_GET['id']) ? intval($_GET['id']) : 0;
$barang = query("SELECT * FROM barang WHERE id_barang = $id_barang");
if (!$barang) {
    echo "<div class='text-red-500'>Data barang tidak ditemukan.</div>";
    exit;
}
$data = $barang[0];

include '../../../includes/header.php';
include '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-1">Update Komoditas</h1>
            <p class="text-gray-400">Ubah data barang di bawah ini.</p>
        </div>

        <form action="proses_update.php" method="POST" enctype="multipart/form-data" class="bg-gray-800 rounded-2xl shadow p-6 space-y-5">
            <input type="hidden" name="id_barang" value="<?= $data['id_barang'] ?>">
            <div>
                <label class="block mb-1 font-medium">Nama Barang</label>
                <input type="text" name="nama_barang" required value="<?= htmlspecialchars($data['nama_barang']) ?>" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block mb-1 font-medium">Kategori</label>
                <select name="id_kategori" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['id_kategori'] ?>" <?= $kat['id_kategori'] == $data['id_kategori'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($kat['nama_kategori']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Jumlah Total</label>
                    <input type="number" name="jumlah_total" required value="<?= $data['jumlah_total'] ?>" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Jumlah Tersedia</label>
                    <input type="number" name="jumlah_tersedia" required value="<?= $data['jumlah_tersedia'] ?>" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Lokasi</label>
                    <input type="text" name="lokasi" required value="<?= htmlspecialchars($data['lokasi']) ?>" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Kondisi</label>
                    <select name="kondisi" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik" <?= $data['kondisi'] == 'Baik' ? 'selected' : '' ?>>Baik</option>
                        <option value="Rusak Ringan" <?= $data['kondisi'] == 'Rusak Ringan' ? 'selected' : '' ?>>Rusak Ringan</option>
                        <option value="Rusak Berat" <?= $data['kondisi'] == 'Rusak Berat' ? 'selected' : '' ?>>Rusak Berat</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">Image</label>
                <?php if (!empty($data['image'])): ?>
                    <div class="mb-2">
                        <img src="../../../assets/uploads/<?= htmlspecialchars($data['image']) ?>" alt="Foto Barang" class="w-24 h-24 object-cover rounded border border-gray-600">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" accept="image/*" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="previewFile(this)">
                <small class="text-gray-400">Kosongkan jika tidak ingin mengganti gambar.</small>
            </div>

            <div>
                <label class="block mb-1 font-medium">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" name="updateKomoditas" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-full text-sm font-semibold text-white shadow">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i> Simpan
                </button>
                <a href="read.php" class="ml-2 inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-full text-sm text-white">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Batal
                </a>
            </div>
        </form>
    </main>
</div>

<script>
    function previewFile(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-24 h-24 object-cover rounded border border-gray-600';
                document.querySelector('div.mb-2').innerHTML = '';
                document.querySelector('div.mb-2').appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
</script>

<?php include '../../../includes/footer.php'; ?>