<?php
session_start();
include 'config.php'; // Pastikan file ini menginisialisasi $conn dengan benar

// Check user session and level
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'cost control') {
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

// Handle form submission for updating COA and ketCOA
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['id'];
    $coa_values = $_POST['KETCOA'];

    foreach ($ids as $index => $id) {
        $coa = $coa_values[$index];

        date_default_timezone_set('Asia/Jakarta');
$timestamp = date("Y-m-d H:i:s");  // Menghasilkan waktu dengan timezone Asia/Jakarta
$updateQuery = "UPDATE lov SET id_coa = ?, tgl_coa = ? WHERE id = ?";
$stmt_update = $config->prepare($updateQuery);
$stmt_update->bind_param("isi", $coa, $timestamp, $id);
$stmt_update->execute();    }

    header("Location: approvalCC.php");
    exit(); 
}

// Query to get data from `approval` table
$approvalQuery = "SELECT * FROM approval WHERE id = ? ";
$stmt = $config->prepare($approvalQuery);
$stmt->bind_param("i", $approval_id);
$stmt->execute();
$approvalResult = $stmt->get_result();

if ($approvalResult->num_rows > 0) {
    $data = $approvalResult->fetch_assoc();
    $id_approval = $data['id'];

    // Query to get data from `lov` table based on `id_approval`
    $lovQuery = "SELECT lov.*, keterangan.nama as keterangan, coa.COA, coa.KETCOA FROM lov 
    LEFT JOIN keterangan on lov.id_ket = keterangan.id
    LEFT JOIN coa on lov.id_coa = coa.id
     WHERE id_approval = ?";
    $stmt_lov = $config->prepare($lovQuery);
    $stmt_lov->bind_param("i", $id_approval);
    $stmt_lov->execute();
    $lovResult = $stmt_lov->get_result();
} else {
    echo "No approval data found.";
    exit();
}
?>