<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];
$id_user = $_SESSION['id'];

// Hanya user dan admin yang dapat mengakses halaman ini
if ($level !== 'admin' && $level !== 'user') {
    header("Location: ../home.php");
    exit();
}

// Pastikan $id_user terisi
if (isset($id_user) && !empty($id_user)) {

    // Jalankan query dan cek apakah berhasil
    $querySH = "SELECT COUNT(*) AS count FROM approval WHERE status ='head section' AND id_user = $id_user";
    $resultSH = $config->query($querySH);
    if ($resultSH === false) {
        echo "Error: " . $config->error;
    } else {
        $SHcount = $resultSH->fetch_assoc()['count'];
    }

    $queryCC = "SELECT COUNT(*) AS count FROM approval WHERE status ='cost control' AND id_user = $id_user";
    $resultCC = $config->query($queryCC);
    if ($resultCC === false) {
        echo "Error: " . $config->error;
    } else {
        $CCcount = $resultCC->fetch_assoc()['count'];
    }

    $queryS = "SELECT COUNT(*) AS count FROM approval WHERE status ='success' AND id_user = $id_user";
    $resultS = $config->query($queryS);
    if ($resultS === false) {
        echo "Error: " . $config->error;
    } else {
        $Scount = $resultS->fetch_assoc()['count'];
    }

    $queryR = "SELECT COUNT(*) AS count FROM approval WHERE status ='revisi' AND id_user = $id_user";
    $resultR = $config->query($queryR);
    if ($resultR === false) {
        echo "Error: " . $config->error;
    } else {
        $Rcount = $resultR->fetch_assoc()['count'];
    }

    $queryD = "SELECT COUNT(*) AS count FROM approval WHERE status ='draft' AND id_user = $id_user";
    $resultD = $config->query($queryD);
    if ($resultD === false) {
        echo "Error: " . $config->error;
    } else {
        $Dcount = $resultD->fetch_assoc()['count'];
    }

    // Menampilkan hasil perhitungan
    // echo "Head Section Count: " . $SHcount . "<br>";
    // echo "Cost Control Count: " . $CCcount . "<br>";
    // echo "Success Count: " . $Scount . "<br>";
    // echo "Revisi Count: " . $Rcount . "<br>";

} else {
    echo "ID user tidak ditemukan di session.";
}
?>
