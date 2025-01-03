<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard E-KAS MERAH</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    />
    <link href="assets/css/styles.css" rel="stylesheet" />
    
  </head>
  <body>
   

      <div class="dashboard-container">
        <div>
          <div class="hi-admin">Hi, <?= htmlspecialchars($_SESSION['username']); ?>!</div>
          <div class="welcome-title">Welcome Back </div>
          <div class="subtitle">TO E-KAS MERAH!!</div>

          <?php if ($_SESSION['level'] == 'admin' ): ?>
                <div class="buttons">
                <a href="datauser.php"><button class="btn btn-primary">Get Started</button>
            </div>
            <?php endif; ?>
            <?php if ($_SESSION['level'] == 'user' ): ?>
                <div class="buttons">
                <a href="historyU.php"><button class="btn btn-primary">HISTORY PENGAJUAN</button>
            </div>
                <?php endif; ?>
                <?php if ($_SESSION['level'] == 'head section' ): ?>
                <div class="buttons">
                <a href="approvalSH.php"><button class="btn btn-primary">APPROVAL</button></a>
            </div>
                <?php endif; ?>
                <?php if ($_SESSION['level'] == 'cost control' ): ?>
                <div class="buttons">
                <a href="approvalCC.php"><button class="btn btn-primary">APPROVAL</button></a>
            </div>
                <?php endif; ?>
        </div>
        <img src="assets/bill.png" alt="Dashboard Image" />
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  
  </body>
</html>
