<?php
session_start();
include 'config.php';
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}


$level = $_SESSION['level'];
$id_user = $_SESSION['id']; // Assuming you have this in the session

// Hanya user dan admin yang dapat mengakses halaman ini
if ($level !== 'admin' && $level !== 'cost control') {
    // Jika tidak sesuai, arahkan kembali ke home
    header("Location: ../home.php");
    exit();
}



$APcount = $config->query("SELECT COUNT(*) AS count FROM approval WHERE status ='cost control' ")->fetch_assoc()['count'];
$SCcount = $config->query("SELECT COUNT(*) AS count FROM approval WHERE status ='success' ")->fetch_assoc()['count'];
?>