<?php
require_once '../../../config/functions.php';
$pageTitle = 'Data Komoditas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

// Ambil data barang + kategori
$komoditas = query("SELECT barang.*, 
                        kategori.nama_kategori 
                    FROM barang 
                    JOIN kategori ON barang.id_kategori = kategori.id_kategori 
                        ORDER BY barang.created_at DESC");

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-16 md:pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold"><?= $pageTitle; ?>.</h1>
                <p class="text-gray-400 tracking-widest italic">~ Halaman Daftar <?= $pageTitle; ?>.</p>
            </div>
        </div>

        <?php showSuccessAlert(); ?>

        <div class="w-full max-w-full bg-gray-800 shadow-lg rounded-xl p-6">
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-3.5">
                <div>
                    <p class="text-gray-400 italic font-bold">Berikut adalah daftar seluruh komoditas yang tersedia.</p>
                    <h1 class="text-2xl font-bold">---</h1>
                </div>

                <div class="flex flex-wrap gap-3">
                    <!-- Tambah Kategori -->
                    <button
                        onclick="document.getElementById('modalKategori').classList.remove('hidden')"
                        type="button"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 active:bg-gray-800 rounded-lg text-sm font-medium text-white shadow transition-all duration-200 ease-in-out">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Tambah Kategori
                    </button>

                    <!-- Tambah Barang -->
                    <a
                        href="create.php"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 rounded-lg text-sm font-medium text-white shadow transition-all duration-200 ease-in-out">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Tambah Barang
                    </a>
                </div>
            </div>

            <div class="rounded-2xl">
                <table id="dataTables" class="overflow-x-auto min-w-full bg-gray-800 text-sm text-white table-auto border-collapse display responsive nowrap">
                    <thead>
                        <tr class="bg-gray-700 text-left">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Komoditas</th>
                            <th class="px-4 py-3">Jumlah</th>
                            <th class="px-4 py-3">Lokasi</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($komoditas) > 0): $no = 1; ?>
                            <?php foreach ($komoditas as $row): ?>
                                <tr class="border-t border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-3"><?= $no++ ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']) ?></td>
                                    <td class="px-4 py-3"><?= $row['jumlah_tersedia'] ?> / <?= $row['jumlah_total'] ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['lokasi']) ?></td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <!-- Link Edit -->
                                        <a href="update.php?id=<?= $row['id_barang'] ?>"
                                            class="text-yellow-400 hover:underline text-sm flex items-center">
                                            <i data-lucide="rotate-ccw-square" class="w-4 h-4 mr-1"></i>Edit
                                        </a>

                                        <!-- Link Hapus -->
                                        <a href="delete.php?id=<?= $row['id_barang'] ?>"
                                            class="btn-hapus text-red-400 hover:underline text-sm flex items-center">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>Hapus
                                        </a>

                                        <!-- Link Detail -->
                                        <a href="#"
                                            class="text-blue-400 hover:underline text-sm flex items-center"
                                            onclick="openDetailModal(
                                        '<?= $row['nama_barang'] ?>',
                                        '<?= $row['nama_kategori'] ?>',
                                        '<?= $row['jumlah_total'] ?>',
                                        '<?= $row['jumlah_tersedia'] ?>',
                                        '<?= $row['lokasi'] ?>',
                                        '<?= $row['kondisi'] ?>',
                                        '../../../assets/uploads/<?= $row['image'] ?>',
                                        '<?= $row['deskripsi'] ?>'
                                    )">
                                            <i data-lucide="info" class="w-4 h-4 mr-1"></i>Detail
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        else: ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal Tambah Kategori -->
    <div id="modalKategori" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen" id="modalKategoriBg">
            <div class="bg-gray-800 bg-opacity-90 rounded-xl shadow-lg p-8 w-full max-w-md relative" onclick="event.stopPropagation();">
                <button onclick="document.getElementById('modalKategori').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-bold mb-4 text-white underline">Tambah Kategori Komoditas</h2>
                <form action="proses_create_kategori.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="submit" name="tambahKategori" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold">Simpan</button>
                        <button type="button" onclick="document.getElementById('modalKategori').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded text-white">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Barang -->
    <div id="detailModal" class="fixed inset-0 items-center justify-center z-50 hidden" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen" id="detailModalBg">
            <!-- Modal Content -->
            <div class="bg-gray-900 text-gray-100 rounded-xl shadow-2xl w-11/12 max-w-5xl p-6" onclick="event.stopPropagation();">
                <!-- Header -->
                <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
                    <h2 class="text-xl font-semibold flex items-center gap-2">
                        <i data-lucide="package"></i> Detail Barang
                    </h2>
                    <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                        class="text-gray-400 hover:text-gray-200">
                        <i data-lucide="x"></i>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div>
                        <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4 flex items-center gap-2">
                            <i data-lucide="info"></i>
                            <span>Data di bawah adalah detail data komoditas.</span>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-400 flex items-center gap-1">Komoditas</p>
                                <div id="detailKomoditas" class="bg-gray-800 px-3 py-2 rounded-md"></div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 flex items-center gap-1">Kategori</p>
                                <div id="detailKategori" class="bg-gray-800 px-3 py-2 rounded-md"></div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <p class="text-sm text-gray-400 flex items-center gap-1">Jumlah Total</p>
                                    <div id="detailTotal" class="bg-gray-800 px-3 py-2 rounded-md"></div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400 flex items-center gap-1">Jumlah Tersedia</p>
                                    <div id="detailTersedia" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 flex items-center gap-1">Lokasi</p>
                                <div id="detailLokasi" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div>
                        <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4 flex items-center gap-2">
                            <i data-lucide="info"></i>
                            <span>Data di bawah adalah detail kondisi & gambar.</span>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-400 flex items-center gap-1">Kondisi</p>
                                <div id="detailKondisi" class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md"></div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 flex items-center gap-1">Image</p>
                                <div id="detailImage" class="bg-gray-800 p-3 rounded-md flex justify-center items-center min-h-[185px]">
                                    <img src="" alt="Image Barang" class="max-h-56 object-contain rounded-md hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- Tutup grid -->

                <!-- Deskripsi Full Width -->
                <div class="mt-6">
                    <p class="text-sm text-gray-400 flex items-center gap-1">Deskripsi</p>
                    <div id="detailDeskripsi" class="bg-gray-800 px-3 py-3 rounded-md min-h-[120px]"></div>
                </div>

                <!-- Footer -->
                <div class="flex justify-end mt-6 border-t border-gray-700 pt-4">
                    <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                        class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md flex items-center gap-2">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../../includes/footer.php'; ?>