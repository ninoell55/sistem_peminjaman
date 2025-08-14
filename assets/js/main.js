// Icon library initialization
lucide.createIcons();

// Sidebar toggle functionality
document.addEventListener("DOMContentLoaded", function () {
  const sidebar = document.getElementById("sidebar");
  const toggleButton = document.getElementById("toggleSidebar");

  function openSidebar() {
    sidebar.classList.remove("-translate-x-full");
    sidebar.classList.add("translate-x-0");
  }

  function closeSidebar() {
    sidebar.classList.add("-translate-x-full");
    sidebar.classList.remove("translate-x-0");
  }

  toggleButton.addEventListener("click", function (e) {
    e.stopPropagation(); // Mencegah klik tombol dianggap klik luar
    if (sidebar.classList.contains("-translate-x-full")) {
      openSidebar();
    } else {
      closeSidebar();
    }
  });

  // Deteksi klik di luar sidebar
  document.addEventListener("click", function (e) {
    const isClickInsideSidebar = sidebar.contains(e.target);
    const isClickOnToggle = toggleButton.contains(e.target);

    if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 768) {
      closeSidebar();
    }
  });
});

// ADMIN ------->>
// Modal edit admin: klik di luar area modal untuk close
var modalEditAdminBg = document.getElementById("modalEditAdminBg");
if (modalEditAdminBg) {
  modalEditAdminBg.addEventListener("click", function () {
    document.getElementById("modalEditAdmin").classList.add("hidden");
  });
}
// Fungsi untuk buka modal edit dan isi data
function openEditAdminModal(data) {
  document.getElementById("modalEditAdmin").classList.remove("hidden");
  document.getElementById("edit_id_admin").value = data.id_admin;
  document.getElementById("edit_nama_admin").value = data.nama_admin;
  document.getElementById("edit_username").value = data.username;
  document.getElementById("edit_password").value = "";
  document.getElementById("edit_role").value = data.role;
}
var modalAdminBg = document.getElementById("modalAdminBg");
if (modalAdminBg) {
  modalAdminBg.addEventListener("click", function () {
    document.getElementById("modalAdmin").classList.add("hidden");
  });
}
// end-ADMIN <<-------

// PENGGUNA ------->>
// Modal tambah pengguna: klik di luar area modal untuk close
var modalEditPenggunaBg = document.getElementById("modalEditPenggunaBg");
if (modalEditPenggunaBg) {
  modalEditPenggunaBg.addEventListener("click", function () {
    document.getElementById("modalEditPengguna").classList.add("hidden");
  });
}
// Tampilkan field kelas, jurusan, nip_nis hanya jika role siswa (tambah)
function toggleSiswaFields() {
  var role = document.getElementById("role_tambah").value.toLowerCase();
  var siswaFields = document.getElementById("siswaFields");
  if (role === "siswa") {
    siswaFields.style.display = "block";
  } else {
    siswaFields.style.display = "none";
    document.getElementById("field_kelas").value = "";
    document.getElementById("field_jurusan").value = "";
  }
}
// Tampilkan field kelas, jurusan, nip_nis hanya jika role siswa (edit)
function toggleEditSiswaFields() {
  var role = document.getElementById("edit_role").value.toLowerCase();
  var siswaFields = document.getElementById("editSiswaFields");
  if (role === "siswa") {
    siswaFields.style.display = "block";
  } else {
    siswaFields.style.display = "none";
    document.getElementById("edit_kelas").value = "";
    document.getElementById("edit_jurusan").value = "";
  }
}
// Fungsi untuk buka modal edit dan isi data
function openEditPenggunaModal(data) {
  document.getElementById("modalEditPengguna").classList.remove("hidden");
  document.getElementById("edit_id_pengguna").value = data.id_pengguna;
  document.getElementById("edit_nama_pengguna").value = data.nama_pengguna;
  document.getElementById("edit_username").value = data.username;
  document.getElementById("edit_password").value = "";
  document.getElementById("edit_role").value = data.role;
  document.getElementById("edit_kelas").value = data.kelas ?? "";
  document.getElementById("edit_jurusan").value = data.jurusan ?? "";
  document.getElementById("edit_nip_nis").value = data.nip_nis;
  toggleEditSiswaFields();
}
// Modal edit pengguna: klik di luar area modal untuk close
var modalPenggunaBg = document.getElementById("modalPenggunaBg");
if (modalPenggunaBg) {
  modalPenggunaBg.addEventListener("click", function () {
    document.getElementById("modalPengguna").classList.add("hidden");
  });
}
// end-PENGGUNA <<-------

// datatable initialization
$(document).ready(function () {
  $("#dataTable").DataTable({
    responsive: true,
    languange: {
      search: "Cari:",
      lengthMenu: "Tampilkan _MENU_ entri",
      info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
      infoEmpty: "Tidak ada entri yang ditemukan",
      infoFiltered: "(difilter dari _MAX_ total entri)",
      paginate: {
        first: "Pertama",
        last: "Terakhir",
        next: "Selanjutnya",
        previous: "Sebelumnya"
      }
    },
    pageLength: 10,
    lengthMenu: [10, 25, 50, 100],
    order: [[0, "asc"]],
    columnDefs: [
      { orderable: false, targets: -1 }, // Nonaktifkan sorting pada kolom terakhir (aksi)
      { className: "text-center", targets: "_all" } // Tambahkan kelas text-center ke semua kolom
    ],
    });
});
// end-datatable <<-------
