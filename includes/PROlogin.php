<?php
session_start();
include 'config.php'; // Sambungkan ke database

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = trim($_POST['email']); // Bisa email atau username
    $password = trim($_POST['password']);

    try {
        // Query menggunakan prepared statement untuk keamanan
        $query = "SELECT * FROM users WHERE email = ? OR username = ? OR nik = ?";
        $stmt = $config->prepare($query);
        $stmt->bind_param("sss", $input, $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Periksa password menggunakan password_verify
            if (password_verify($password, $user['password'])) {
                // Jika password cocok, set sesi
                $_SESSION['email'] = $user['email'];
                $_SESSION['id'] = $user['id'];
                $_SESSION['level'] = $user['level'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['nik'] = $user['nik'];
                $_SESSION['dept'] = $user['dept'];

                // Redirect ke halaman home
                header("Location: ../home.php");
                exit();
            } else {
                // Jika password salah
                $error = "Password salah";
                header("Location: ../login.php?error=" . urlencode($error) . "&email=" . urlencode($input));
                exit();
            }
        } else {
            // Jika email atau username tidak ditemukan
            $error = "Email atau username salah";
            header("Location: ../login.php?error=" . urlencode($error) . "&email=" . urlencode($input));
            exit();
        }
    } finally {
        // Tutup statement dan koneksi jika sudah digunakan
        if (isset($stmt)) $stmt->close();
        $config->close();
    }
} else {
    // Jika bukan metode POST, redirect kembali ke halaman login
    header("Location: ../login.php");
    exit();
}
?>
