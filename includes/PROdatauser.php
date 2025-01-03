<?php
include 'config.php';
session_start();

// Check user session and level
if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];

// Hanya user dan admin yang dapat mengakses halaman ini
if ($level !== 'admin') {
    header("Location: ../home.php");
    exit();
}


// Delete user functionality
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $config->real_escape_string($_POST['id']); // Sanitize the user ID
    $sql = "DELETE FROM users WHERE id = '$id'"; // Query to delete the user

    if ($config->query($sql)) {
        echo 'success'; // Success response for AJAX
    } else {
        echo 'error'; // Error response for AJAX
    }
    $config->close(); // Close the DB connection
    exit(); // Stop further execution
}


// Retrieve users for display
$sql = "SELECT id, username, email, password, dept, level FROM users";
$result = $config->query($sql);
?>