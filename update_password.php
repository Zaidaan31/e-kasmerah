<?php
include 'includes/config.php'; // Koneksi ke database

echo "Proses hash password dimulai...<br>";

// Ambil semua data user dari database
$query = "SELECT id, password FROM users";
$result = $config->query($query);

if ($result) {
    $updatedCount = 0;
    $skippedCount = 0;

    while ($user = $result->fetch_assoc()) {
        $id = $user['id'];
        $password = $user['password'];

        // Cek apakah password sudah di-hash (hash bcrypt memiliki panjang 60 karakter)
        if (strlen($password) === 60 && preg_match('/^\$2y\$[0-9]{2}\$.{53}$/', $password)) {
            // Jika password sudah dalam format hash bcrypt, lewati
            $skippedCount++;
            echo "ID $id: Password sudah di-hash, dilewati.<br>";
        } else {
            // Jika belum di-hash, hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Update password di database
            $updateQuery = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $config->prepare($updateQuery);
            $stmt->bind_param("si", $hashed_password, $id);

            if ($stmt->execute()) {
                $updatedCount++;
                echo "ID $id: Password berhasil di-hash dan diperbarui.<br>";
            } else {
                echo "ID $id: Gagal memperbarui password - " . $stmt->error . "<br>";
            }
        }
    }

    echo "<br>Proses selesai!<br>";
    echo "Total password diperbarui: $updatedCount<br>";
    echo "Total password dilewati: $skippedCount<br>";
} else {
    echo "Error: " . $config->error;
}

// Tutup koneksi
$config->close();
?>
