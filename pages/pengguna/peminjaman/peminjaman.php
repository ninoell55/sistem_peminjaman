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

$id_pengguna = $_SESSION['id_pengguna'] ?? null;
$role_pengguna = $_SESSION['role'] ?? '';

// Ambil data peminjaman hari ini
$peminjamanHariIni = query(
    "SELECT 
        p.*, u.*, b.nama_barang, d.id_barang, d.jumlah
            FROM peminjaman p
                JOIN detail_peminjaman d ON p.id_peminjaman = d.id_peminjaman
                JOIN barang b ON d.id_barang = b.id_barang
                JOIN pengguna u ON p.id_pengguna = u.id_pengguna
        WHERE p.id_pengguna = '$id_pengguna' 
            AND p.waktu_pinjam >= CURDATE()
            AND p.waktu_pinjam < CURDATE() + INTERVAL 1 DAY"
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
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nama Pengguna</th>
                        <th class="px-4 py-3">Komoditas</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Waktu Pinjam</th>
                        <th class="px-4 py-3">Waktu Kembali</th>
                        <th class="px-4 py-3">Petugas</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-gray-900 divide-y divide-gray-700">
                    <?php if (count($peminjamanHariIni) > 0): $no = 1; ?>
                        <?php foreach ($peminjamanHariIni as $row): ?>
                            <tr>
                                <td class="px-4 py-3"><?= $no++; ?></td>
                                <td class="px-4 py-3">
                                    <span class="bg-indigo-700 text-white px-2 py-1 rounded">
                                        <?= htmlspecialchars($row['nama_pengguna']); ?>
                                    </span>
                                </td>
                                <!-- Nama Barang -->
                                <td class="px-4 py-3"><?= htmlspecialchars($row['nama_barang']); ?></td>

                                <!-- format tanggal -->
                                <?php
                                $datetime = $row['waktu_pinjam'];

                                // format tanggal 
                                $date = new DateTime($datetime);
                                $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

                                $dayName = $days[(int)$date->format('w')];
                                $day = $date->format('d');
                                $monthName = $months[(int)$date->format('m') - 1];
                                $year = $date->format('Y');

                                $formattedDate = "$dayName, $day $monthName $year";
                                ?>

                                <!-- Tanggal -->
                                <td class="px-4 py-3"><?= $formattedDate; ?></td>

                                <!-- Waktu Pinjam -->
                                <td class="px-4 py-3 font-bold">
                                    <span class="inline-flex items-center gap-1 bg-gray-700 px-2 py-1 rounded">
                                        <i data-lucide="clock" class="w-4 h-4"></i>
                                        <?= $row['waktu_pinjam']; ?>
                                    </span>
                                </td>

                                <!-- Waktu Kembali -->
                                <td class="px-4 py-3 font-bold">
                                    <?php if (empty($row['waktu_kembali'])): ?>
                                        <span class="inline-flex items-center gap-1 bg-yellow-700 px-2 py-1 rounded">
                                            <i data-lucide="loader" class="w-4 h-4 animate-spin"></i>
                                            <span>Masih berlangsung</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center gap-1 bg-gray-700 px-2 py-1 rounded">
                                            <i data-lucide="clock" class="w-4 h-4"></i>
                                            <?= $row['waktu_kembali']; ?>
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <!-- Petugas -->
                                <td class="px-4 py-3">
                                    <?php if ($row['status'] == 'menunggu'): ?>
                                        <button disabled title="Menunggu validasi petugas"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-yellow-500 hover:bg-yellow-600 text-white transition">
                                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                        </button>
                                    <?php elseif ($row['status'] == 'dipinjam' || $row['status'] == 'dikembalikan'): ?>
                                        <button disabled title="Sudah divalidasi oleh petugas"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-green-700 text-white">
                                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>

                                <!-- Aksi -->
                                <td class="px-4 py-3">
                                    <?php if ($row['status'] == 'menunggu'): ?>
                                        <a title="Validasi?" href="acc_peminjaman.php?id=<?= $row['id_peminjaman']; ?>&aksi=acc" onclick="return confirm('Setujui peminjaman ini?')"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded bg-blue-500 hover:bg-blue-600 text-white transition">
                                            <i data-lucide="user-lock" class="w-4 h-4"></i>
                                        </a>
                                    <?php endif; ?>
                                    <button title="Detail peminjaman" onclick="document.getElementById('detailModal').classList.remove('hidden')" type="button"
                                        class="inline-flex items-center gap-1 w-8 h-8 bg-green-700 hover:bg-green-800 text-white px-2 py-1 rounded text-xs">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach;
                    else: ?>
                        <tr>
                            <td colspan="11" class="py-4 px-5 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data peminjaman hari ini.
                            </td>
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
                            <label for="waktu_pinjam" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Waktu Pinjam</label>
                            <input type="datetime-local" name="waktu_pinjam" id="waktu_pinjam" required
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
                            <label for="catatan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan (Opsiona)</label>
                            <textarea name="catatan" id="catatan" rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            </textarea>
                        </div>
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">Ajukan Peminjaman</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Overlay -->
        <div id="detailModal" class="fixed inset-0 items-center justify-center z-50 hidden" style="background: rgba(0,0,0,0.5);">
            <div class="flex items-center justify-center min-h-screen" id="detailModalBg">
                <!-- Modal Content -->
                <div class="bg-gray-900 text-gray-100 rounded-xl shadow-2xl w-11/12 max-w-5xl p-6" onclick="event.stopPropagation();">
                    <!-- Header -->
                    <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
                        <h2 class="text-xl font-semibold">Detail Peminjaman</h2>
                        <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                            class="text-gray-400 hover:text-gray-200">
                            <i data-lucide="x"></i>
                        </button>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div>
                            <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4">
                                Data di bawah adalah detail data pengguna.
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Nomor Identitas Pengguna</p>
                                    <div class="bg-gray-800 px-3 py-2 rounded-md"><?= $row['nip_nis']; ?></div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Nama Pengguna</p>
                                    <div class="bg-gray-800 px-3 py-2 rounded-md"><?= $row['nama_pengguna']; ?></div>
                                </div>
                                <div>
                                    <?php if ($role_pengguna == 'siswa'): ?>
                                        <p class="text-sm text-gray-400">Jurusan</p>
                                        <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                            <i data-lucide="book-open" class="w-4 h-4"></i> <?= $row['jurusan']; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="hidden"><!-- Jika selain siswa, tidak ada kolom jurusan --></div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php if ($role_pengguna == 'siswa'): ?>
                                        <p class="text-sm text-gray-400">Kelas</p>
                                        <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                            <i data-lucide="building-2" class="w-4 h-4"></i> <?= $row['kelas']; ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="hidden"><!-- Jika selain siswa, tidak ada kolom kelas --></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div>
                            <div class="bg-indigo-700 text-sm p-3 rounded-md mb-4">
                                Data di bawah adalah detail data peminjaman.
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-400">Nama Komoditas</p>
                                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                        <i data-lucide="package" class="w-4 h-4"></i> <?= $row['nama_barang']; ?>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Jumlah</p>
                                    <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md">
                                        <i data-lucide="hash" class="w-4 h-4"></i> <?= $row['jumlah']; ?>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-sm text-gray-400">Waktu Pinjam</p>
                                        <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md min-h-[38px]">
                                            <i data-lucide="clock" class="w-4 h-4"></i> <?= $row['waktu_pinjam']; ?>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-400">Waktu Kembali</p>
                                        <div class="flex items-center gap-2 bg-gray-800 px-3 py-2 rounded-md min-h-[38px]">
                                            <i data-lucide="clock" class="w-4 h-4"></i> <?= $row['waktu_kembali']; ?>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-400">Status</p>
                                    <div class="bg-gray-800 px-3 py-2 rounded-md"><?= $row['status']; ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-400">Catatan</p>
                        <textarea disabled class="w-full bg-gray-800 text-white px-3 py-2 rounded-md" rows="4"><?= $row['catatan']; ?></textarea>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end mt-6 border-t border-gray-700 pt-4">
                        <button onclick="document.getElementById('detailModal').classList.add('hidden')"
                            class="bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2 rounded-md">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('modalPeminjamanBg').addEventListener('click', function() {
        document.getElementById('modalPeminjaman').classList.add('hidden');
    });

    document.getElementById('detailModalBg').addEventListener('click', function() {
        document.getElementById('detailModal').classList.add('hidden');
    });
</script>

<?php require_once '../../../includes/footer.php'; ?>