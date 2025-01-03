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
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $config->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // User not found
        echo "User not found!";
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
        <a href="datauser.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">Form Edit User</h1>
    </div>
    <div class="card">
    <div class="card2">
        <form action="includes/PROfedituser.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="dept">Departemen:</label>
                <select id="dept" name="dept" class="form-control" required>
                    <option value="FINANCE, ACCOUNTING, IT" <?php echo $user['dept'] === 'FINANCE, ACCOUNTING, IT' ? 'selected' : ''; ?>>Finance, Accounting, IT</option>
                    <option value="HUMAN CAPITAL" <?php echo $user['dept'] === 'HUMAN CAPITAL' ? 'selected' : ''; ?>>Human Capital</option>
                    <option value="GENERAL AFFAIR" <?php echo $user['dept'] === 'GENERAL AFFAIR' ? 'selected' : ''; ?>>General Affair</option>
                    <option value="OUTBOND" <?php echo $user['dept'] === 'OUTBOND' ? 'selected' : ''; ?>>Outbond</option>
                    <option value="INBOND" <?php echo $user['dept'] === 'INBOND' ? 'selected' : ''; ?>>Inbond</option>
                    <option value="DELIVERY" <?php echo $user['dept'] === 'DELIVERY' ? 'selected' : ''; ?>>Delivery</option>
                    <option value="COSTUMER SERVICE" <?php echo $user['dept'] === 'COSTUMER SERVICE' ? 'selected' : ''; ?>>Costumer Service</option>
                    <option value="SALES & MARKETING" <?php echo $user['dept'] === 'SALES & MARKETING' ? 'selected' : ''; ?>>Sales & Marketing</option>
                </select>
            </div>
            <div class="form-group">
                <label for="level">Level:</label>
                <select id="level" name="level" class="form-control" required>
                    <option value="admin" <?php echo $user['level'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    <option value="user" <?php echo $user['level'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="head section" <?php echo $user['level'] === 'head section' ? 'selected' : ''; ?>>Head Section</option>
                    <option value="cost control" <?php echo $user['level'] === 'cost control' ? 'selected' : ''; ?>>Cost Control</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="datauser.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    </div>
    </div>
</body>
</html>
