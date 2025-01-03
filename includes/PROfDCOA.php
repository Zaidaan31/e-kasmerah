<?php
session_start(); // Memulai session

include 'config.php'; // Menghubungkan ke file koneksi

// Mengambil data dari form
$COA = $_POST['COA'];
$KETCOA = $_POST['KETCOA'];


// Memeriksa apakah COA atau KETCOA sudah ada
$check_sql = "SELECT * FROM COA WHERE COA = ? OR KETCOA = ?";
$check_stmt = $config->prepare($check_sql);
$check_stmt->bind_param("ss", $COA, $KETCOA);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Jika ada COA atau KETCOA yang sama, atur pesan error
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'COA atau KETCOA sudah terdaftar!'];
} else {
    // Jika tidak ada, lakukan insert
    $sql = "INSERT INTO COA  (COA, KETCOA) VALUES (?, ?)";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ss", $COA, $KETCOA);

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
header("Location: ../fDCOA.php");
exit();
?>
