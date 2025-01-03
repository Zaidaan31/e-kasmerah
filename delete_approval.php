<?php
include 'includes/config.php';

if (isset($_POST['id'])) {
    $id_approval = $_POST['id'];

    // Mulai transaksi
    mysqli_begin_transaction($config);

    try {
        // Hapus data di tabel lov
        $query_lov = "DELETE FROM lov WHERE id_approval = ?";
        $stmt_lov = mysqli_prepare($config, $query_lov);
        if (!$stmt_lov) {
            throw new Exception("Prepare failed for LOV: " . mysqli_error($config));
        }
        mysqli_stmt_bind_param($stmt_lov, "i", $id_approval);
        if (!mysqli_stmt_execute($stmt_lov)) {
            throw new Exception("Execute failed for LOV: " . mysqli_stmt_error($stmt_lov));
        }

        // Hapus data di tabel approval
        $query_approval = "DELETE FROM approval WHERE id = ?";
        $stmt_approval = mysqli_prepare($config, $query_approval);
        if (!$stmt_approval) {
            throw new Exception("Prepare failed for Approval: " . mysqli_error($config));
        }
        mysqli_stmt_bind_param($stmt_approval, "i", $id_approval);
        if (!mysqli_stmt_execute($stmt_approval)) {
            throw new Exception("Execute failed for Approval: " . mysqli_stmt_error($stmt_approval));
        }

        // Commit transaksi
        mysqli_commit($config);

        echo "success";
    } catch (Exception $e) {
        // Rollback jika terjadi error
        mysqli_rollback($config);
        echo "error: " . $e->getMessage();
    }
}
?>
