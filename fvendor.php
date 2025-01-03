<?php include 'includes/PROfnewvendor.php'; ?>
<?php include 'navbar.php'; ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Input Pengguna</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="assets/css/fuser/fuser.css" rel="stylesheet" />
</head>

<body id="body">

<div class="header">
        <a href="dataVendor.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">Form Vendor</h1>
    </div>
    <div class="card">
        <div class="card2">
            <div class="card-body">
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <?php
                    $flash = $_SESSION['flash_message'];
                    $alertType = $flash['type'] == 'success' ? 'alert-success' : 'alert-danger';
                    ?>
                    <div id="notification" class="alert <?php echo $alertType; ?>" role="alert">
                        <?php echo $flash['message']; ?>
                    </div>
                    <?php
                    // Hapus pesan setelah ditampilkan
                    unset($_SESSION['flash_message']);
                    ?>
                <?php endif; ?>
                <form action="includes/PROfvendor.php" method="post">
                    <div class="form-group">
                        <label for="username">Nomor Vendor:</label>
                        <input type="text" id="no_vendor" name="no_vendor" class="form-control" placeholder="No Vendor" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Nama Vendor:</label>
                        <input type="text" id="nama" name="nama" class="form-control" placeholder="Nama Vendor" required>
                    </div>
                    <div class="form-group ">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script> <!-- Custom scripts -->

    <script>
        // Script untuk menghapus notifikasi setelah beberapa detik
        document.addEventListener('DOMContentLoaded', function() {
            var notification = document.getElementById('notification');
            if (notification) {
                setTimeout(function() {
                    notification.style.display = 'none';
                }, 20000);
            }
        });
    </script>
</body>

</html>