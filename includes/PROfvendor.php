<?php
session_start(); // Memulai session

include 'config.php'; // Menghubungkan ke file koneksi

// Mengambil data dari form
$no_vendor = $_POST['no_vendor'];
$nama = $_POST['nama'];


// Memeriksa apakah no_vendor atau nama sudah ada
$check_sql = "SELECT * FROM vendor WHERE no_vendor = ? OR nama = ?";
$check_stmt = $config->prepare($check_sql);
$check_stmt->bind_param("ss", $no_vendor, $nama);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Jika ada no_vendor atau nama yang sama, atur pesan error
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'no_vendor atau nama sudah terdaftar!'];
} else {
    // Jika tidak ada, lakukan insert
    $sql = "INSERT INTO vendor  (no_vendor, nama) VALUES (?, ?)";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ss", $no_vendor, $nama);

    if ($stmt->execute()) {
        // Mengatur pesan sukses di sesi
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Data berhasil ditambahkan!'];
    } else {
        // Mengatur pesan error di sesi
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Terjadi kesalahan saat menambahkan data.'];
    }

    $stmt->close();
}

// Menutup koneksi
$check_stmt->close();
$config->close();

// Mengalihkan kembali ke form input
header("Location: ../fvendor.php");
exit();
?>
