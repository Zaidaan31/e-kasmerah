<?php
session_start();

// Check user session and level
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];

// Hanya user dan admin yang dapat mengakses halaman ini
if ($level !== 'admin' && $level !== 'cost control') {
    // Jika tidak sesuai, arahkan kembali ke home
    header("Location: home.php");
    exit();
}

?>