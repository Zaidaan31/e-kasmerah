<?php
// Include database connection
include 'config.php';

// Query untuk mengambil opsi dari database
$query = mysqli_query($config, "SELECT id, nama_gerbang, tarif FROM tol ORDER BY id ASC");
$options1 = "";


while ($data = mysqli_fetch_array($query)) {
    $options1 .= "<option value=\"$data[id]\" data-tarif=\"{$data['tarif']}\">$data[nama_gerbang]</option>";
}

// Tampilkan opsi sebagai HTML
echo $options1;
?>
