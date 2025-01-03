<?php
include 'includes/config.php';
session_start();

// Check user session and level
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$level = $_SESSION['level'];

// Only allow admin to access this page
if ($level !== 'admin') {
    header("Location: home.php");
    exit();
}

// Retrieve the user data based on ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM vendor WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // User not found
        echo "Vendor not found!";
        exit();
    }
    $user = $result->fetch_assoc();
} else {
    // ID not provided
    echo "No ID specified!";
    exit();
}
?>

<?php include 'navbar.php'; ?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/fuser/fuser.css" rel="stylesheet" />
</head>
<body>
<div class="header">
        <a href="dataVendor.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">Form Edit User</h1>
    </div>
    <div class="card">
    <div class="card2">
        <form action="includes/PROfeditVendor.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="no_vendor">No. Vendor:</label>
                <input type="text" id="no_vendor" name="no_vendor" class="form-control" value="<?php echo $user['no_vendor']; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama Vendor:</label>
                <input type="nama" id="nama" name="nama" class="form-control" value="<?php echo $user['nama']; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="dataVendor.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
        </div>
    </div>
    </div>
</body>
</html>
