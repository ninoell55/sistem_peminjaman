<?php
// Start the session
session_start();

// base url for the application
$base_url = '/sistem_peminjaman/';

// Database connection settings
$hostname = 'localhost';
$username = 'root';
$password = '';
$dbname = 'sistem_peminjaman';

// Create a connection to the database
$connection = new mysqli($hostname, $username, $password, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}


// <<< SIDEBAR-isActive
function isActive($target)
{
    $currentFile = basename($_SERVER['PHP_SELF']);
    $currentUri = $_SERVER['REQUEST_URI'];


    if (strpos($target, '/') !== false) {
        return strpos($currentUri, $target) !== false;
    }

    return $currentFile === $target;
}
// SIDEBAR-isActive >>>


// Function -- LOGIN >>
function login_admin($connection, $username, $password)
{
    $query = "SELECT * FROM admin WHERE username = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['password'] === $password) {
            $_SESSION['id_admin'] = $row['id_admin'];
            $_SESSION['nama_admin'] = $row['nama_admin'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['login_admin'] = true;
            $_SESSION['login_success'] = true;

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Password salah. Silakan coba lagi.'];
        }
    } else {
        return ['success' => false, 'message' => 'Username tidak ditemukan.'];
    }
}


function login_pengguna($connection, $username, $password)
{
    $query = "SELECT * FROM pengguna WHERE username = ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        if ($row['password'] === $password) {
            $_SESSION['id_pengguna'] = $row['id_pengguna'];
            $_SESSION['nama_pengguna'] = $row['nama_pengguna'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['login_pengguna'] = true;
            $_SESSION['login_success'] = true;

            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Password salah. Silakan coba lagi.'];
        }
    } else {
        return ['success' => false, 'message' => 'Username tidak ditemukan.'];
    }
}
// Function -- LOGIN--end >>


// <<< SELECT DATA
function query($query)
{
    global $connection;
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query error: " . mysqli_error($connection)); // tampilkan error MySQL
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}
// SELECT DATA >>>


// <<< PESAN SUKSES
function showSuccessAlert()
{
    if (!isset($_GET['success'])) return;

    $type = $_GET['success'];
    $message = match ($type) {
        'tambah' => 'Data berhasil ditambahkan!',
        'edit' => 'Data berhasil diedit!',
        'hapus' => 'Data berhasil dihapus!',
        'invalid' => 'Something wrong... data berkaitan dengan data yang lain, hubungi admin jika ingin melakukan aksi!',
        'twin' => 'Data sudah tersedia, silahkan melakukan perubahan data.',
        'error' => 'Error! Gagal melakukan aksi.',
        default => 'Aksi berhasil dilakukan.'
    };

    $icon = ($type === 'hapus' || $type === 'invalid' || $type === 'error' || $type === 'twin') ? 'error' : 'success';

    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$message',
                icon: '$icon',
                background: '#1f2937',     // dark background
                color: '#f9fafb',          // light text
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6', // blue
            }).then(() => {
                window.history.replaceState(null, null, window.location.pathname);
            }).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    icon: '$icon',
                    title: '$message',
                    background: '#1f2937',  // dark toast
                    color: '#f9fafb',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
            });
        });
    </script>
    ";
}
// PESAN SUKSES >>>
