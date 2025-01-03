<?php
session_start();
include 'config.php';
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}


$level = $_SESSION['level'];
$id_user = $_SESSION['id']; // Assuming you have this in the session
$dept = $_SESSION['dept'];

// Hanya user dan admin yang dapat mengakses halaman ini
if ($level !== 'admin' && $level !== 'head section') {
    // Jika tidak sesuai, arahkan kembali ke home
    header("Location: ../home.php");
    exit();
}



$APcount = $config->query("SELECT COUNT(*) AS count FROM approval LEFT JOIN users ON approval.id_user = users.id WHERE status ='head section'  AND users.dept = '$dept'")->fetch_assoc()['count'];
$SCcount = $config->query("SELECT COUNT(*) AS count FROM approval LEFT JOIN users ON approval.id_user = users.id WHERE status IN ('success', 'cost control') AND users.dept = '$dept'")->fetch_assoc()['count'];

?>