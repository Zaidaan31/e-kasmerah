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
if ($level !== 'admin' && $level !== 'cost control') {
    header("Location: ../home.php");
    exit();
}

// Update user functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $COA= $_POST['COA'];
    $KETCOA = $_POST['KETCOA'];

    $sql = "UPDATE COA SET COA= ?, KETCOA = ? WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ssi", $COA, $KETCOA, $id);

    if ($stmt->execute()) {
        // Success message
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'Vendor updated successfully!'];
    } else {
        // Error message
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Error updating Vendor.'];
    }

    $stmt->close();
    $config->close();
    header("Location: ../dataCOA.php");
    exit();
}
