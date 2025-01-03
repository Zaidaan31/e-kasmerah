<?php
// Include database connection
include 'config.php';

// Query untuk mengambil opsi dari database
$query = mysqli_query($config, "SELECT id, nama FROM keterangan ORDER BY nama ASC");
$options = "";


while ($data = mysqli_fetch_array($query)) {
    $options .= "<option value=\"$data[id]\">$data[nama]</option>";
}

// Tampilkan opsi sebagai HTML
echo $options;
?>
