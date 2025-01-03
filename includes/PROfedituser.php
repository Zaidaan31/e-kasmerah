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
    $username = $_POST['username'];
    $email = $_POST['email'];
    $dept = $_POST['dept'];
    $level = $_POST['level'];

    $sql = "UPDATE users SET username = ?, email = ?, dept = ?, level = ? WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("ssssi", $username, $email, $dept, $level, $id);

    if ($stmt->execute()) {
        // Success message
        $_SESSION['flash_message'] = ['type' => 'success', 'message' => 'User updated successfully!'];
    } else {
        // Error message
        $_SESSION['flash_message'] = ['type' => 'error', 'message' => 'Error updating user.'];
    }

    $stmt->close();
    $config->close();
    header("Location: ../datauser.php");
    exit();
}
