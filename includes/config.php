<?php
$host = 'localhost'; // Ganti dengan host Anda
$user = 'root'; // Ganti dengan username database Anda
$pass = ''; // Ganti dengan password database Anda
$dbname = 'kasmerah_b'; // Ganti dengan nama database Anda

// Koneksi ke database
$config = mysqli_connect($host, $user, $pass, $dbname);

// Periksa koneksi
if (!$config) {
    die("Connection failed: " . mysqli_connect_error());
}

$config->set_charset("utf8mb4");
?>
