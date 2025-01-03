<?php
// Include koneksi database
include 'includes/config.php';

// Mengecek apakah `id_lov` dikirim via POST
if (isset($_POST['id'])) {
    $id_lov = $_POST['id'];

    // Mulai transaksi
    mysqli_begin_transaction($config);

    try {
        // Query untuk menghapus data di tabel lov berdasarkan id_lov
        $query_lov = "DELETE FROM lov WHERE id = ?";
        $stmt_lov = mysqli_prepare($config, $query_lov);
        
        // Bind parameter id_lov ke prepared statement
        mysqli_stmt_bind_param($stmt_lov, "i", $id_lov);

        // Eksekusi query
        mysqli_stmt_execute($stmt_lov);

        // Commit transaksi jika berhasil
        mysqli_commit($config);

        // Jika berhasil, kirim respons success
        echo "success";
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi error
        mysqli_rollback($config);
        echo "error";
    }
}
?>
