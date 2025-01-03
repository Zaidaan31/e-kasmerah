<?php
session_start(); // Memulai session

include 'config.php'; // Menghubungkan ke file koneksi

// Mengambil data dari form
$username = $_POST['username'];
$NIK = $_POST['nik'];
$email = $_POST['email'];
$password = $_POST['password']; 
$dept = $_POST['dept'];
$level = $_POST['level'];

// Memeriksa apakah username atau email sudah ada
$check_sql = "SELECT * FROM users WHERE username = ? OR email = ?";
$check_stmt = $config->prepare($check_sql);
$check_stmt->bind_param("ss", $username, $email);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    // Jika ada username atau email yang sama, atur pesan error
    $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Username atau email sudah terdaftar!'];
} else {
    // Hash password menggunakan password_hash
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Jika tidak ada, lakukan insert
    $sql = "INSERT INTO users (username, nik, email, password,dept, level) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ssssss", $username, $NIK, $email, $hashed_password, $dept,$level);

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
header("Location: ../formuser.php");
exit();
?>
