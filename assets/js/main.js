document.addEventListener("DOMContentLoaded", () => {
  lucide.createIcons();

  // lanjut semua fitur lain
});

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
    const sidebar = document.querySelector("#sidebar");
    const toggleButton = document.querySelector("#toggleButton");

    const isClickInsideSidebar = sidebar ? sidebar.contains(e.target) : false;
    const isClickOnToggle = toggleButton
      ? toggleButton.contains(e.target)
      : false;

    if (!isClickInsideSidebar && !isClickOnToggle && window.innerWidth < 768) {
      closeSidebar();
    }
  });
});

// ===== DataTables =====
$(document).ready(function () {
  const table = $("#dataTables").DataTable({
    responsive: true,
    language: {
      paginate: {
        previous: "←",
        next: "→",
      },
      search: "",
      searchPlaceholder: "Pencarian ... ",
      lengthMenu: "_MENU_",
      emptyTable: "Tidak ada data tersedia",
      zeroRecords: "Tidak ada data yang cocok",
      info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
      infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
      infoFiltered: "(disaring dari _MAX_ total entri)"
    },
    initComplete: function () {
      const wrapper = $("#dataTables_wrapper");
      const length = wrapper.find(".dataTables_length");
      const filter = wrapper.find(".dataTables_filter");

      const topContainer = $(`
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center mb-4">
        </div>
      `);

      // Style dropdown jumlah data
      length
        .find("select")
        .addClass(
          "px-3 py-1.5 text-sm text-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        );

      // Style input search
      filter
        .find("input")
        .addClass(
          "px-4 py-2 text-sm placeholder:text-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64"
        );

      topContainer.append(length).append(filter);
      wrapper.find(".dataTables_length, .dataTables_filter").remove();
      wrapper.prepend(topContainer);

      // Pagination styling
      wrapper
        .find(".dataTables_paginate a")
        .addClass(
          "inline-block border border-gray-300 rounded-md px-3 py-1 mx-1 text-sm text-gray-700 bg-white hover:bg-blue-100 hover:text-blue-600 transition duration-200 ease-in-out mt-2"
        );

      // Active page styling
      wrapper
        .find(".dataTables_paginate .current")
        .removeClass("bg-white")
        .addClass("bg-blue-500 text-white hover:bg-blue-600");

      // Info text styling
      wrapper.find(".dataTables_info").addClass("text-sm italic mt-2");
    },
  });
});

// ADMIN ------->>
// Modal edit admin: klik di luar area modal untuk close
let modalEditAdminBg = document.getElementById("modalEditAdminBg");
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
let modalAdminBg = document.getElementById("modalAdminBg");
if (modalAdminBg) {
  modalAdminBg.addEventListener("click", function () {
    document.getElementById("modalAdmin").classList.add("hidden");
  });
}
// end-ADMIN <<-------

// PENGGUNA ------->>
// Modal tambah pengguna: klik di luar area modal untuk close
let modalEditPenggunaBg = document.getElementById("modalEditPenggunaBg");
if (modalEditPenggunaBg) {
  modalEditPenggunaBg.addEventListener("click", function () {
    document.getElementById("modalEditPengguna").classList.add("hidden");
  });
}
// Tampilkan field kelas, jurusan, nip_nis hanya jika role siswa (tambah)
function toggleSiswaFields() {
  let role = document.getElementById("role_tambah").value.toLowerCase();
  let siswaFields = document.getElementById("siswaFields");
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
  let role = document.getElementById("edit_role").value.toLowerCase();
  let siswaFields = document.getElementById("editSiswaFields");
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
let modalPenggunaBg = document.getElementById("modalPenggunaBg");
if (modalPenggunaBg) {
  modalPenggunaBg.addEventListener("click", function () {
    document.getElementById("modalPengguna").classList.add("hidden");
  });
}
// end-PENGGUNA <<-------

// BARANG ------->>
function loadBarang() {
  const searchInput = document.getElementById("search");
  const kategoriSelect = document.getElementById("kategori");
  const dataBarang = document.getElementById("dataBarang");

  // kalau salah satu elemen nggak ada, stop fungsi
  if (!searchInput || !kategoriSelect || !dataBarang) return;

  let search = searchInput.value;
  let kategori = kategoriSelect.value;

  let xhr = new XMLHttpRequest();
  xhr.open(
    "GET",
    "fetch_barang.php?search=" + search + "&kategori=" + kategori,
    true
  );
  xhr.onload = function () {
    if (this.status == 200) {
      dataBarang.innerHTML = this.responseText;

      // render ulang lucide icons setelah data dimuat
      if (window.lucide) {
        lucide.createIcons();
      }
    }
  };
  xhr.send();
}

// load pertama kali
window.addEventListener("load", loadBarang);

// jalankan saat ketik / ubah filter
const searchInput = document.getElementById("search");
const kategoriSelect = document.getElementById("kategori");

if (searchInput) {
  searchInput.addEventListener("keyup", loadBarang);
}
if (kategoriSelect) {
  kategoriSelect.addEventListener("change", loadBarang);
}
// end-BARANG <<-------

// DETAIL BARANG ------->>
// Tutup modal kategori (hanya kalau ada elemen)
const modalKategoriBg = document.getElementById("modalKategoriBg");
if (modalKategoriBg) {
  modalKategoriBg.addEventListener("click", function () {
    const modalKategori = document.getElementById("modalKategori");
    if (modalKategori) {
      modalKategori.classList.add("hidden");
    }
  });
}

// Fungsi openDetailModal (cek semua elemen dulu)
function openDetailModal(
  komoditas,
  kategori,
  total,
  tersedia,
  lokasi,
  kondisi,
  image,
  deskripsi
) {
  const detailModal = document.getElementById("detailModal");
  if (!detailModal) return; // kalau modal tidak ada, langsung keluar

  const komoditasEl = document.getElementById("detailKomoditas");
  const kategoriEl = document.getElementById("detailKategori");
  const totalEl = document.getElementById("detailTotal");
  const tersediaEl = document.getElementById("detailTersedia");
  const lokasiEl = document.getElementById("detailLokasi");
  const kondisiEl = document.getElementById("detailKondisi");
  const deskripsiEl = document.getElementById("detailDeskripsi");
  const imgContainer = document.getElementById("detailImage");

  if (komoditasEl) komoditasEl.textContent = komoditas || "-";
  if (kategoriEl) kategoriEl.textContent = kategori || "-";
  if (totalEl) totalEl.textContent = total || "-";
  if (tersediaEl) tersediaEl.textContent = tersedia || "-";
  if (lokasiEl) lokasiEl.textContent = lokasi || "-";
  if (kondisiEl) kondisiEl.textContent = kondisi || "-";
  if (deskripsiEl) deskripsiEl.textContent = deskripsi || "-";

  // Handle gambar
  if (imgContainer) {
    imgContainer.innerHTML = "";
    if (image) {
      let img = document.createElement("img");
      img.src = image;
      img.alt = komoditas;
      img.classList.add("max-h-32", "rounded-md");
      imgContainer.appendChild(img);
    } else {
      imgContainer.textContent = "-";
    }
  }

  detailModal.classList.remove("hidden");
}

// supaya bisa tutup modal klik background (jika ada)
const detailModalBg = document.getElementById("detailModalBg");
if (detailModalBg) {
  detailModalBg.addEventListener("click", function () {
    const detailModal = document.getElementById("detailModal");
    if (detailModal) {
      detailModal.classList.add("hidden");
    }
  });
}
// end-DETAIl BARANG <<-------

// DETAIL PEMINJAMAN ------->>
document.addEventListener("DOMContentLoaded", () => {
  const detailButtons = document.querySelectorAll(".openDetail");
  const modal = document.getElementById("detailModal");
  const modalBg = document.getElementById("detailModalBg");

  if (detailButtons.length > 0 && modal && modalBg) {
    detailButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        document.getElementById("detailNO").textContent = this.dataset.nip;

        const role = this.dataset.role;

        // Tambahkan ikon di nama
        document.getElementById("detailNama").textContent = this.dataset.nama;

        if (role === "siswa") {
          document.getElementById("detailJurusan").innerHTML = `
          <i data-lucide="book-open" class="w-4 h-4"></i> ${this.dataset.jurusan}
        `;
          document.getElementById("detailKelas").innerHTML = `
          <i data-lucide="building-2" class="w-4 h-4"></i> ${this.dataset.kelas}
        `;
        } else {
          document.getElementById("detailJurusan").textContent = "-";
          document.getElementById("detailKelas").textContent = "-";
        }

        document.getElementById("detailRole").innerHTML = `
          <i data-lucide="contact" class="w-4 h-4"></i> ${this.dataset.role}
        `;

        document.getElementById("detailBarang").innerHTML = `
          <i data-lucide="package" class="w-4 h-4"></i> ${this.dataset.barang}
        `;

        document.getElementById("detailJumlah").innerHTML = `
          <i data-lucide="hash" class="w-4 h-4"></i> ${this.dataset.jumlah}
        `;

        document.getElementById("detailTanggal").innerHTML = `
          <i data-lucide="calendar" class="w-4 h-4"></i> ${this.dataset.tanggal}
        `;

        document.getElementById("detailPinjam").innerHTML = `
          <i data-lucide="clock" class="w-4 h-4"></i> ${this.dataset.pinjam}
        `;

        document.getElementById("detailKembali").innerHTML = `
          <i data-lucide="clock" class="w-4 h-4"></i> ${this.dataset.kembali}
        `;

        document.getElementById("detailStatus").textContent =
          this.dataset.status;
        document.getElementById("detailCatatan").textContent =
          this.dataset.catatan;

        // Render ikon Lucide
        if (window.lucide) {
          lucide.createIcons();
        }

        modal.classList.remove("hidden");
      });
    });

    // klik area bg untuk tutup modal
    modalBg.addEventListener("click", () => {
      modal.classList.add("hidden");
    });
  }
});
// end-DETAIl PEMINJAMAN <<-------

// Tutup modal peminjaman (jika ada)
const modalPeminjamanBg = document.getElementById("modalPeminjamanBg");
if (modalPeminjamanBg) {
  modalPeminjamanBg.addEventListener("click", function () {
    const modal = document.getElementById("modalPeminjaman");
    if (modal) modal.classList.add("hidden");
  });
}

// Tutup modal detail (jika ada)
const detailModalPeminjamanBg = document.getElementById("detailModalBg");
if (detailModalPeminjamanBg) {
  detailModalPeminjamanBg.addEventListener("click", function () {
    const modal = document.getElementById("detailModal");
    if (modal) modal.classList.add("hidden");
  });
}

// Tutup modal edit peminjaman (jika ada)
const modalEditPeminjamanBg = document.getElementById("modalEditPeminjamanBg");
if (modalEditPeminjamanBg) {
  modalEditPeminjamanBg.addEventListener("click", function () {
    const modal = document.getElementById("modalEditPeminjaman");
    if (modal) modal.classList.add("hidden");
  });
}

// Fungsi buka modal edit peminjaman
function openEditPeminjamanModal(button) {
  try {
    let data = JSON.parse(button.getAttribute("data-row"));

    const modal = document.getElementById("modalEditPeminjaman");
    if (!modal) return; // aman, kalau modal nggak ada

    modal.classList.remove("hidden");

    // isi form kalau field ada
    const idPeminjaman = document.getElementById("edit_id_peminjaman");
    if (idPeminjaman) idPeminjaman.value = data.id_peminjaman;

    const waktuPinjam = document.getElementById("edit_waktu_pinjam");
    if (waktuPinjam) waktuPinjam.value = formatDateTime(data.waktu_pinjam);

    const idBarang = document.getElementById("edit_id_barang");
    if (idBarang) idBarang.value = data.id_barang;

    const jumlah = document.getElementById("edit_jumlah");
    if (jumlah) jumlah.value = data.jumlah;

    const catatan = document.getElementById("edit_catatan");
    if (catatan) catatan.value = data.catatan ?? "";
  } catch (e) {
    console.error("Gagal membuka modal edit peminjaman:", e);
  }
}

// fungsi format datetime-local
function formatDateTime(datetimeString) {
  if (!datetimeString) return "";
  const d = new Date(datetimeString);
  if (isNaN(d)) return datetimeString; // fallback
  const year = d.getFullYear();
  const month = String(d.getMonth() + 1).padStart(2, "0");
  const day = String(d.getDate()).padStart(2, "0");
  const hours = String(d.getHours()).padStart(2, "0");
  const minutes = String(d.getMinutes()).padStart(2, "0");
  return `${year}-${month}-${day}T${hours}:${minutes}`;
}

// Sweet-Alert confirm to DELETE
$(document).ready(function () {
  if (!$.fn.DataTable.isDataTable("#dataTables")) {
    $("#dataTables").DataTable({
      responsive: true,
    });
  }

  // Event hapus dengan SweetAlert (sama seperti sebelumnya)
  $(document).on("click", ".btn-hapus", function (e) {
    e.preventDefault();
    const url = $(this).attr("href");

    Swal.fire({
      title: "Yakin ingin menghapus?",
      text: "Data yang dihapus akan diarsipkan dan bisa dikembalikan jika diperlukan.",
      icon: "warning",
      background: "#1f2937",
      color: "#f9fafb",
      showCancelButton: true,
      confirmButtonColor: "#3b82f6",
      cancelButtonColor: "#ef4444",
      confirmButtonText: "Ya, hapus!",
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = url;
      }
    });
  });
});

// Sweet-Alert confirm to LOGOUT
$(document).on("click", ".btn-logout", function (e) {
  e.preventDefault();
  const url = $(this).attr("href");

  Swal.fire({
    title: "Keluar dari sistem?",
    text: "Anda akan keluar dari akun saat ini.",
    icon: "warning",
    background: "#1f2937",
    color: "#f9fafb",
    showCancelButton: true,
    confirmButtonColor: "#3b82f6",
    cancelButtonColor: "#ef4444",
    confirmButtonText: "Ya, Logout",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
});

// Sweet-Alert confirm to ACCEPT peminjaman
$(document).on("click", ".btn-setujui", function (e) {
  e.preventDefault();
  const url = $(this).attr("href");
  const jumlah = $(this).data("jumlah");
  const komoditas = $(this).data("komoditas");
  const peminjam = $(this).data("peminjam");

  Swal.fire({
    title: "Setujui peminjaman?",
    html: `Anda akan menyetujui peminjaman <strong>${jumlah} ${komoditas}</strong> oleh <strong>${peminjam}</strong>.`,
    icon: "question",
    background: "#1f2937",
    color: "#f9fafb",
    showCancelButton: true,
    confirmButtonColor: "#3b82f6",
    cancelButtonColor: "#ef4444",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = url;
    }
  });
});

// Sweet-Alert confirm to ACCEPT pengembalian
$(document).on("click", ".btn-setujui-pengembalian", function (e) {
  e.preventDefault();
  const url = $(this).attr("href");
  const jumlah = $(this).data("jumlah");
  const komoditas = $(this).data("komoditas");
  const peminjam = $(this).data("peminjam");
  Swal.fire({
    title: "Setujui pengembalian?",
    html: `Anda akan menyetujui pengembalian <strong>${jumlah} ${komoditas}</strong> oleh <strong>${peminjam}</strong>.`,
    icon: "question",
    background: "#1f2937",
    color: "#f9fafb",
    showCancelButton: true,
    confirmButtonColor: "#3b82f6",
    cancelButtonColor: "#ef4444",
    confirmButtonText: "Ya, setujui",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location = url;
    }
  });
});

// Sweet-Alert confirm to aJUKAN pengembalian
$(document).on("click", ".btn-ajukan-pengembalian", function (e) {
  e.preventDefault();
  const url = $(this).attr("href");
  const jumlah = $(this).data("jumlah");
  const komoditas = $(this).data("komoditas");
  const peminjam = $(this).data("peminjam");
  Swal.fire({
    title: "Ajukan pengembalian?",
    html: `Anda akan mengajukan pengembalian <strong>${jumlah} ${komoditas}</strong> oleh <strong>${peminjam}</strong>.`,
    icon: "question",
    background: "#1f2937",
    color: "#f9fafb",
    showCancelButton: true,
    confirmButtonColor: "#3b82f6",
    cancelButtonColor: "#ef4444",
    confirmButtonText: "Ya, ajukan",
    cancelButtonText: "Batal",
  }).then((result) => {
    if (result.isConfirmed) {
      window.location = url;
    }
  });
});
// end-SweetAlert <<-------
