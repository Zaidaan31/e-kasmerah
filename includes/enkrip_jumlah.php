<?php
include 'config.php';
// Kunci enkripsi (harus rahasia dan tetap)
define('ENCRYPTION_KEY', 'my_secret_key_12345');

// Fungsi untuk enkripsi data
function encryptData($data) {
    $key = ENCRYPTION_KEY;
    $cipher = 'AES-128-CTR';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted); // Gabungkan IV dan data terenkripsi
}

// Fungsi untuk dekripsi data
function decryptData($encryptedData) {
    $key = ENCRYPTION_KEY;
    $cipher = 'AES-128-CTR';
    $data = base64_decode($encryptedData);
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $iv_length);
    $encrypted = substr($data, $iv_length);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}

// Fungsi untuk memeriksa apakah data terenkripsi (base64 format)
function isEncrypted($data) {
    return base64_decode($data, true) !== false;
}

// Update data di tabel approval untuk kolom jumlahtotal
$queryApproval = "SELECT id, jumlahtotal FROM approval";
$resultApproval = $config->query($queryApproval);

if ($resultApproval->num_rows > 0) {
    while ($row = $resultApproval->fetch_assoc()) {
        $id = $row['id'];
        
        // Cek apakah jumlahtotal sudah terenkripsi
        $jumlahtotal = isEncrypted($row['jumlahtotal']) ? $row['jumlahtotal'] : encryptData($row['jumlahtotal']);

        // Update data ke database untuk tabel approval
        $updateQueryApproval = $config->prepare("UPDATE approval SET jumlahtotal = ? WHERE id = ?");
        $updateQueryApproval->bind_param("si", $jumlahtotal, $id);

        if (!$updateQueryApproval->execute()) {
            echo "Error updating jumlahtotal with ID: $id<br>";
        }
    }
    echo "Data jumlahtotal di tabel approval berhasil dienkripsi!<br>";
} else {
    echo "Tidak ada data jumlahtotal untuk diperbarui di tabel approval.<br>";
}

// Update data di tabel lov untuk kolom jumlah
$queryLov = "SELECT id, jumlah FROM lov";
$resultLov = $config->query($queryLov);

if ($resultLov->num_rows > 0) {
    while ($row = $resultLov->fetch_assoc()) {
        $id = $row['id'];
        
        // Cek apakah jumlah sudah terenkripsi
        $jumlah = isEncrypted($row['jumlah']) ? $row['jumlah'] : encryptData($row['jumlah']);

        // Update data ke database untuk tabel lov
        $updateQueryLov = $config->prepare("UPDATE lov SET jumlah = ? WHERE id = ?");
        $updateQueryLov->bind_param("si", $jumlah, $id);

        if (!$updateQueryLov->execute()) {
            echo "Error updating jumlah with ID: $id<br>";
        }
    }
    echo "Data jumlah di tabel lov berhasil dienkripsi!<br>";
} else {
    echo "Tidak ada data jumlah untuk diperbarui di tabel lov.<br>";
}

$config->close();
?>
