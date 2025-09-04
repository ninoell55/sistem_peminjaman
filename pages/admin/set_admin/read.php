<?php
require_once '../../../config/functions.php';
$pageTitle = 'Data Admin & Petugas';

if (!isset($_SESSION['login_admin'])) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

// HALAMAN HANYA UNTUK ROLE ADMINISTRATOR
if (isset($_SESSION['role']) && ($_SESSION['role'] == 'petugas')) {
    header("Location: ../../../auth/login_admin/login.php");
    exit;
}

// Ambil data admin
$admin = query("SELECT * FROM admin ORDER BY created_at DESC");

$roles = array();
$result = query("SELECT DISTINCT role FROM admin ORDER BY role ASC");
foreach ($result as $r) {
    $roles[] = $r['role'];
}

require_once '../../../includes/header.php';
require_once '../../../includes/sidebar.php';
?>
<div class="md:ml-64 min-h-screen bg-gray-900 text-white p-6 pt-24">
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold"><?= $pageTitle; ?>.</h1>
                <p class="text-gray-400 tracking-widest italic">~ Halaman Daftar <?= $pageTitle; ?>.</p>
            </div>
        </div>

        <?php showSuccessAlert(); ?>

        <div class="w-full max-w-full bg-gray-800 shadow-lg rounded-xl p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-gray-400 italic font-bold">Berikut adalah daftar seluruh pengelola sistem peminjaman yang tersedia.</p>
                    <h1 class="text-2xl font-bold">---</h1>
                </div>

                <button onclick="document.getElementById('modalAdmin').classList.remove('hidden')" type="button" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded-full text-sm font-semibold text-white shadow">
                    <i data-lucide="plus" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="rounded-2xl">
                <table id="dataTables" class="overflow-x-auto min-w-full bg-gray-800 text-sm text-white table-auto border-collapse display responsive nowrap">
                    <thead>
                        <tr class="text-left bg-gray-700 hover:bg-gray-600">
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Nama Admin</th>
                            <th class="px-4 py-3">Username</th>
                            <th class="px-4 py-3">Password</th>
                            <th class="px-4 py-3">Role</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($admin) > 0): $no = 1; ?>
                            <?php foreach ($admin as $row): ?>
                                <tr class="border-t border-gray-700 hover:bg-gray-700">
                                    <td class="px-4 py-3"><?= $no++ ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['nama_admin']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['username']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['password']) ?></td>
                                    <td class="px-4 py-3"><?= htmlspecialchars($row['role']) ?></td>
                                    <td class="px-4 py-3 flex gap-2">
                                        <button type="button" class="text-yellow-400 hover:underline text-sm flex items-center" onclick='openEditAdminModal(<?= json_encode($row, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)'>
                                            <i data-lucide="rotate-ccw-square" class="w-4 h-4 mr-1"></i>Edit
                                        </button>
                                        <a href="delete.php?id=<?= $row['id_admin'] ?>" class="btn-hapus text-red-400 hover:underline text-sm flex items-center">
                                            <i data-lucide="trash-2" class="w-4 h-4 mr-1"></i>Hapus
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
    <!-- Modal Edit Admin -->
    <div id="modalEditAdmin" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen" id="modalEditAdminBg">
            <div class="bg-gray-800 bg-opacity-90 rounded-xl shadow-lg p-8 w-full max-w-md relative" onclick="event.stopPropagation();">
                <button onclick="document.getElementById('modalEditAdmin').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-bold mb-4 text-white underline">Edit Admin</h2>
                <form action="proses_update.php" method="POST" class="space-y-4">
                    <input type="hidden" name="id_admin" id="edit_id_admin">
                    <div>
                        <label class="block mb-1 font-medium">Nama Admin</label>
                        <input type="text" name="nama_admin" id="edit_nama_admin" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                        <select name="role" id="edit_role" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="submit" name="updateAdmin" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold">Simpan</button>
                        <button type="button" onclick="document.getElementById('modalEditAdmin').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded text-white">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Tambah Admin -->
    <div id="modalAdmin" class="fixed inset-0 z-50 hidden" style="background: rgba(0,0,0,0.5);">
        <div class="flex items-center justify-center min-h-screen" id="modalAdminBg">
            <div class="bg-gray-800 bg-opacity-90 rounded-xl shadow-lg p-8 w-full max-w-md relative" onclick="event.stopPropagation();">
                <button onclick="document.getElementById('modalAdmin').classList.add('hidden')" class="absolute top-2 right-2 text-gray-400 hover:text-white">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-bold mb-4 text-white underline">Tambah Admin</h2>
                <form action="proses_create.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium">Nama Admin</label>
                        <input type="text" name="nama_admin" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
                        <select name="role" required class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?= htmlspecialchars($role) ?>"><?= htmlspecialchars($role) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="submit" name="tambahAdmin" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 rounded text-white font-semibold">Simpan</button>
                        <button type="button" onclick="document.getElementById('modalAdmin').classList.add('hidden')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded text-white">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../../includes/footer.php'; ?>