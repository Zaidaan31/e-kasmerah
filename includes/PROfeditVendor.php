<?php
include 'config.php';
session_start();

// Check user session and level
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];

// Only allow admin to access this page
if ($level !== 'admin') {
    header("Location: ../home.php");
    exit();
}

// Update user functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $no_vendor = $_POST['no_vendor'];
    $nama = $_POST['nama'];

    $sql = "UPDATE vendor SET no_vendor = ?, nama = ? WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ssi", $no_vendor, $nama, $id);

    if ($stmt->execute()) {
        // Success message
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Vendor updated successfully!'];
    } else {
        // Error message
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Error updating Vendor.'];
    }

    $stmt->close();
    $config->close();
    header("Location: ../dataVendor.php");
    exit();
}
