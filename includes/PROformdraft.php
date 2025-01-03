<?php
session_start();
include 'config.php'; // Ensure this file initializes $conn properly

// Memeriksa apakah parameter 'id' ada di POST
// if (isset($_POST['id']) && !empty($_POST['id'])) {
//     $approval_id = intval($_POST['id']); // Mengambil id dari POST
//     var_dump($approval_id); // Memeriksa nilai approval_id setelah assignment
// } else {
//     echo "ID parameter is missing or invalid.";
//     exit();
// }

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'user') {
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
// Debugging: check the values of $approval_id and $id_user
// var_dump($approval_id);  // Periksa nilai approval_id
// var_dump($id_user);      // Periksa nilai id_user
// Query untuk mengambil departemen dari tabel users
$query = "SELECT dept FROM users WHERE id = ?";
$stmt = $config->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $departemen = $row['dept']; // Ambil nilai departemen
} else {
    echo "Departemen tidak ditemukan untuk id_user: $id_user";
    exit();
}

$stmt->close();

$approvalQuery = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept 
                  FROM approval
                  LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                  LEFT JOIN users ON approval.id_user = users.id
                  WHERE approval.id = ? AND approval.id_user = ?";
$stmt = $config->prepare($approvalQuery);
$stmt->bind_param("ii", $approval_id, $id_user);
$stmt->execute();
$approvalResult = $stmt->get_result();

if ($approvalResult->num_rows > 0) {
    $data = $approvalResult->fetch_assoc();
    $id_approval = $data['id'];
    // var_dump($approval_id);
    // Query untuk mendapatkan data dari tabel lov berdasarkan id_approval
    $lovQuery = "SELECT lov.*, keterangan.id as ket_id, keterangan.nama as keterangan 
                 FROM lov 
                 LEFT JOIN keterangan on lov.id_ket = keterangan.id
                 WHERE id_approval = ?";
    $stmt_lov = $config->prepare($lovQuery);
    $stmt_lov->bind_param("i", $id_approval);
    $stmt_lov->execute();
    $lovResult = $stmt_lov->get_result();
    // $lovResult2 = $stmt_lov->get_result();
} else {
    echo "No approval data found.";
    exit();
}
$stmt->close();


?>