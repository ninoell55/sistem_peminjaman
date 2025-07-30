<?php
session_start();

// Cek login session admin
if (!isset($_SESSION['login_admin'])) {
    header("Location: login.php");
    exit;
}

$_SESSION = [];
session_unset();
session_destroy();

header("Location: login.php?logout=success");
exit;
