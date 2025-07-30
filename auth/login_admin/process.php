<?php
require_once '../../config/functions.php';

if (isset($_POST['login_admin'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_result = login_admin($connection, $username, $password);

    if ($login_result['success']) {
        header("Location: ../../pages/admin/dashboard.php");
        exit;
    } else {
        $_SESSION['login_error'] = $login_result['message'];
        header("Location: login.php");
        exit;
    }
} else {
    header("Location: login.php");
    exit;
}
