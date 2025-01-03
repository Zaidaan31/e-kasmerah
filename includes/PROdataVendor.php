<?php
include 'config.php';
session_start();

// Check user session and level
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];

// Hanya admin yang dapat mengakses halaman ini
if ($level !== 'admin') {
    header("Location: ../home.php");
    exit();
}


// Delete vendor functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $config->real_escape_string($_POST['id']); // Sanitize the user ID
    $sql = "DELETE FROM vendor WHERE id = '$id'"; // Query to delete the user

    if ($config->query($sql)) {
        echo 'success'; // Success response for AJAX
    } else {
        echo 'error'; // Error response for AJAX
    }
    $config->close(); // Close the DB connection
    exit(); // Stop further execution
}


// Retrieve vendor for display
$sql = "SELECT * FROM vendor";
$result = $config->query($sql);
?>