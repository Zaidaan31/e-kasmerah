<?php
// Include koneksi database
include 'includes/config.php';

// Mengecek apakah `id_bukti` dikirim via POST
if (isset($_POST['id'])) {
    $id_bukti = $_POST['id'];

    // Mulai transaksi
    mysqli_begin_transaction($config);

    try {
        // Query untuk menghapus data di tabel lov berdasarkan id_bukti
        $query_bukti = "DELETE FROM bukti WHERE id = ?";
        $stmt_bukti = mysqli_prepare($config, $query_bukti);
        
        // Bind parameter id_bukti ke prepared statement
        mysqli_stmt_bind_param($stmt_bukti, "i", $id_bukti);

        // Eksekusi query
        mysqli_stmt_execute($stmt_bukti);

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
