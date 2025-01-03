<?php
include 'includes/config.php'; // Include your database connection

if (isset($_POST['id'])) {
    $userId = $_POST['id'];

    // Prepare and execute the delete statement
    $stmt = $config->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
} else {
    echo 'error';
}

$config->close();
?>
