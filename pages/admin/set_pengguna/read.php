<?php
require_once '../../../config/functions.php';
$pageTitle = 'Data Pengguna';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php"); // redirect ke halaman awal login
    exit;
}

// Ambil data pengguna
$pengguna = query("SELECT * FROM pengguna 
                ORDER BY created_at DESC");

$roles = array();
$result = query("SELECT DISTINCT role FROM pengguna ORDER BY role ASC");
foreach ($result as $r) {
    $roles[] = $r['role'];
}

include '../../../includes/header.php';
include '../../../includes/sidebar.php';
?>

<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold">Data Pengguna</h1>
                <p class="text-gray-400">Berikut adalah daftar seluruh pengguna yang tersedia.</p>
            </div>

            <button onclick="document.getElementById('modalPengguna').classList.remove('hidden')" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-full text-sm font-semibold text-white shadow">
                <i data-lucide="plus" class="w-5 h-5"></i>
            </button>

        </div>

        <div class="overflow-x-auto rounded-2xl shadow">
            <table class="min-w-full bg-gray-800 text-sm text-white table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-left">
                        <th class="px-4 py-3">#</th>
                        <th class="px-4 py-3">Nama Pengguna</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Password</th>
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Kelas</th>
                        <th class="px-4 py-3">Jurusan</th>
                        <th class="px-4 py-3">NIP / NIS</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($pengguna as $row): ?>
                        <tr class="border-t border-gray-700 hover:bg-gray-700">
                            <td class="px-4 py-3"><?= $no++ ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['nama_pengguna']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['username']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['password']) ?></td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['role']) ?></td>
                            <td class="px-4 py-3">
                                <?php if (strtolower($row['role']) === 'siswa'): ?>
                                    <?= htmlspecialchars($row['kelas']) ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (strtolower($row['role']) === 'siswa'): ?>
                                    <?= htmlspecialchars($row['jurusan']) ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3"><?= htmlspecialchars($row['nip_nis']) ?></td>
                            <td class="px-4 py-3 flex gap-2">
                                <button type="button" class="text-yellow-400 hover:underline text-sm flex items-center" onclick='openEditPenggunaModal(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                    <i data-lucide="rotate-ccw-square" class="w-4 h-4 mr-1"></i>Edit
                                </button>
                                <a href="delete.php?id=<?= $row['id_pengguna'] ?>" class="text-red-400 hover:underline text-sm flex items-center"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                    <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
    <!-- Modal Edit Pengguna -->
    <div id="modalEditPengguna" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen" id="modalEditPenggunaBg">
            <div class="bg-gray-800 bg-opacity-90 rounded-xl shadow-lg p-8 w-full max-w-md relative" onclick="event.stopPropagation();">
                <button onclick="document.getElementById('modalEditPengguna').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-bold mb-4 text-white underline">Edit Pengguna</h2>
                <form action="proses_update.php" method="POST" class="space-y-4">
                    <input type="hidden" name="id_pengguna" id="edit_id_pengguna">
                    <div>
                        <label class="block mb-1 font-medium">Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" id="edit_nama_pengguna" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Username</label>
                        <input type="text" name="username" id="edit_username" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="text" name="password" id="edit_password" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Role</label>
                        <select name="role" id="edit_role" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="toggleEditSiswaFields()">
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="editSiswaFields" style="display:none;">
                        <div>
                            <label class="block mb-1 font-medium">Kelas</label>
                            <input type="text" name="kelas" id="edit_kelas" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Jurusan</label>
                            <input type="text" name="jurusan" id="edit_jurusan" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">NIP / NIS</label>
                        <input type="text" name="nip_nis" id="edit_nip_nis" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="submit" name="updatePengguna" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold">Simpan</button>
                        <button type="button" onclick="document.getElementById('modalEditPengguna').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded text-white">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Pengguna -->
    <div id="modalPengguna" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen" id="modalPenggunaBg">
            <div class="bg-gray-800 bg-opacity-90 rounded-xl shadow-lg p-8 w-full max-w-md relative" onclick="event.stopPropagation();">
                <button onclick="document.getElementById('modalPengguna').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-bold mb-4 text-white underline">Tambah Pengguna</h2>
                <form action="proses_create.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium">Nama Pengguna</label>
                        <input type="text" name="nama_pengguna" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Username</label>
                        <input type="text" name="username" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Password</label>
                        <input type="text" name="password" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Role</label>
                        <select name="role" id="role_tambah" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="toggleSiswaFields()">
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div id="siswaFields" style="display:none;">
                        <div>
                            <label class="block mb-1 font-medium">Kelas</label>
                            <input type="text" name="kelas" id="field_kelas" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block mb-1 font-medium">Jurusan</label>
                            <input type="text" name="jurusan" id="field_jurusan" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">NIP / NIS</label>
                        <input type="text" name="nip_nis" id="field_nip_nis" class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="submit" name="tambahPengguna" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold">Simpan</button>
                        <button type="button" onclick="document.getElementById('modalPengguna').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded text-white">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../../../includes/footer.php'; ?>