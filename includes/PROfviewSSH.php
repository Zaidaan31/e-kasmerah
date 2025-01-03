<?php
session_start();
include 'config.php';


if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'head section') {
    header("Location: ../home.php");
    exit();
}

if (!isset($_SESSION['id'])) {
    echo "User ID is not set. Debugging Information:";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit();
}

$id_user = $_SESSION['id'];
$approval_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mendapatkan data dari tabel `approval`
$approvalQuery = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept ,users.username as user
                    FROM approval
                    LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                    LEFT JOIN users ON approval.id_user = users.id WHERE approval.id = '$approval_id' AND id_user = '$id_user'";
$approvalResult = mysqli_query($config, $approvalQuery);

if (mysqli_num_rows($approvalResult) > 0) {
    $data = mysqli_fetch_assoc($approvalResult);
    $id_approval = $data['id'];

    // Query untuk mendapatkan data dari tabel `lov` berdasarkan `id_approval`
    $lovQuery = "SELECT lov.*, keterangan.nama as keterangan FROM lov LEFT JOIN keterangan on lov.id_ket = keterangan.id
     WHERE id_approval = '$id_approval'";
    $lovResult = mysqli_query($config, $lovQuery);
}
?>