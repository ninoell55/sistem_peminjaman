<?php
require_once '../../../config/functions.php';
$pageTitle = 'Tambah Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

// Ambil data kategori
$kategori = query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold mb-1"><?= $pageTitle; ?></h1>
            <p class="text-gray-400">Isi formulir di bawah untuk menambahkan data barang baru.</p>
        </div>

        <form action="proses_create.php" method="POST" enctype="multipart/form-data" class="bg-gray-800 rounded-2xl shadow p-6 space-y-5">
            <div>
                <label class="block mb-1 font-medium">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block mb-1 font-medium">Kategori</label>
                <select name="id_kategori" id="id_kategori" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $kat): ?>
                        <option value="<?= $kat['id_kategori'] ?>"><?= htmlspecialchars($kat['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Jumlah Total</label>
                    <input type="number" name="jumlah_total" id="jumlah_total" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block mb-1 font-medium">Jumlah Tersedia</label>
                    <input type="number" name="jumlah_tersedia" id="jumlah_tersedia" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-1 font-medium">Lokasi</label>
                    <input type="text" name="lokasi" id="lokasi" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block mb-1 font-medium">Kondisi</label>
                    <select name="kondisi" id="kondisi" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Pilih Kondisi --</option>
                        <option value="Baik">Baik</option>
                        <option value="Rusak Ringan">Rusak Ringan</option>
                        <option value="Rusak Berat">Rusak Berat</option>
                    </select>
                </div>
            </div>

            <!-- create input for file image -->
            <div>
                <label class="block mb-1 font-medium">Image</label>
                <input type="file" name="image" id="image" accept="image/*" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="previewFile(this)">
                <!-- Nama File -->
                <span id="nama-file" class="text-sm text-gray-600 mt-2">Belum ada file yang dipilih</span>
            </div>

            <div>
                <label class="block mb-1 font-medium">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
            </div>

            <div class="pt-4">
                <button type="submit" name="tambahKomoditas" class="inline-flex items-center px-5 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-full text-sm font-semibold text-white shadow">
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
        const fileName = file ? file.name : 'Belum ada file yang dipilih';
        document.getElementById('nama-file').textContent = fileName;

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // cari apakah sudah ada img preview di parent
                let oldPreview = input.parentNode.querySelector("img.preview");
                if (oldPreview) {
                    oldPreview.remove(); // hapus yang lama
                }

                // buat img baru
                const img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("preview"); // kasih class biar mudah dicari
                img.style.maxWidth = "200px";
                img.style.marginTop = "10px";

                input.parentNode.appendChild(img);
            };
            reader.readAsDataURL(file);
        }
    }
</script>

<?php require_once '../../../includes/footer.php'; ?>