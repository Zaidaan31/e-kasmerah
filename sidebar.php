<?php

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/sidebar/sidebar.css" rel="stylesheet" />
    <style>
        .topbar {
            position: fixed; 
            z-index: 90;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: var(--first-color);
            display: flex;
            align-items: center;
            padding: 0 1rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            justify-content: space-between;
            
            /* Distribute space between logo and account section */
        }

        .topbar__logo {
            color: var(--white-color);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .topbar__account {
            display: flex;
            align-items: center;
            /* Align items vertically in the center */
        }

        .topbar__account-name {
            color: var(--white-color);
            font-size: 1rem;
            margin-right: 1rem;
            /* Space between account name and dropdown button */
        }

        .topbar__dropdown-toggle {
            background: none;
            border: none;
            color: var(--white-color);
            font-size: 1.25rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: transform 0.3s ease;
        }

        .topbar__dropdown-toggle.rotate {
            transform: rotate(180deg);
        }


        .topbar__dropdown-menu {
            display: none;
            position: absolute;
            top: 60px;
            right: 10px;
            background: linear-gradient(135deg, #5218133a, #57031063);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            width: 150px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transform: scale(0.9);
            transition: opacity 0.3s ease, transform 0.3s ease, visibility 0.3s ease;
        }

        .topbar__dropdown-menu.show {
            display: block;
            opacity: 1;
            visibility: visible;
            transform: scale(1);
        }


        .topbar__dropdown-item {
            display: block;
            padding: 0.5rem 1rem;
            color: #FFFFFF;
            text-decoration: none;
            border-radius: 0.25rem;
            transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
        }

        .topbar__dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: var(--second-color);
            transform: scale(1.05);
        }

        .topbar__dropdown-item:not(:last-child) {
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
    <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body id="body">
    <div class="topbar">
        <
            <div class="topbar__account">

            <span class="topbar__account-name"><i style="margin-right: 10px;" class="bx bx-user "></i><?= htmlspecialchars($_SESSION['username']); ?></span>
            <button class="topbar__dropdown-toggle" id="topbar-dropdown-toggle">
                <i class='bx bx-chevron-down'></i>
            </button>
            <ul class="topbar__dropdown-menu" id="topbar-dropdown-menu">
                <li><a href="logout.php" class="topbar__dropdown-item">Log Out</a></li>
            </ul>
    </div>
    </div>
    <div class="l-navbar" id="navbar">
        <nav class="nav">
            <div>
                <a href="home.php" class="nav__logo">
                    <i class='bx bx-book-open icon nav__icon'></i>
                    <span class="nav__logo-name">Kas Merah</span>
                </a>
                <div class="nav__toggle" id="nav-toggle">
                    <i class='bx bx-chevron-right'></i>
                </div>
                
                <ul class="nav__list">
                    <li><a href="home.php" class="nav__link">
                            <i class='bx bx-grid-alt nav__icon'></i>
                            <span class="nav__text">Home</span>
                        </a></li>
                        <div style="margin-top:20px;"></div>
                <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'user'): ?>
                    <div>
                        <span class="ulv">User</span>
                        <li><a href="historyU.php" class="nav__link">
                            <i class='bx bx-history nav__icon'></i>
                            <span class="nav__text">History</span>
                        </a></li>
                        <li><a href="form.php" class="nav__link">
                            <i class='bx bx-file nav__icon'></i>
                            <span class="nav__text">Form Pengajuan</span>
                        </a></li>
                    </div>
                    <div style="margin-top:20px;"></div>
                <?php endif; ?>
                <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'head section' || $_SESSION['level'] == 'head section tol'): ?>
                    <div>
                        <span class="ulv">Section Head</span>
                        <li><a href="approvalSH.php" class="nav__link">
                            <i class='bx bx-check-circle nav__icon'></i>
                            <span class="nav__text">Approval</span>
                        </a></li>
                    </div>
                    <div style="margin-top:20px;"></div>
                <?php endif; ?>
                <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'cost control'): ?>
                    <div>
                        <span class="ulv">Cost Control</span>
                        <li><a href="approvalCC.php" class="nav__link">
                            <i class='bx bx-dollar-circle nav__icon'></i>
                            <span class="nav__text">Approval</span>
                        </a></li>
                    </div>
                    <div style="margin-top:20px;"></div>
                <?php endif; ?>
                <?php if ($_SESSION['level'] == 'admin'): ?>
                    <div>
                        <span class="ulv">Admin</span>
                        <li><a href="datauser.php" class="nav__link">
                            <i class='bx bx-user-circle nav__icon'></i>
                            <span class="nav__text">Data Account</span>
                        </a></li>
                        <li><a href="formuser.php" class="nav__link">
                            <i class='bx bx-file nav__icon'></i>
                            <span class="nav__text">Form Account</span>
                        </a></li>
                        <li><a href="fvendor.php" class="nav__link">
                            <i class='bx bx-file nav__icon'></i>
                            <span class="nav__text">Form Vendor</span>
                        </a></li>
                        
                    </div>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>


    <script src="assets/js/sidebar.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <script>
        document.getElementById('topbar-dropdown-toggle').addEventListener('click', function() {
            var menu = document.getElementById('topbar-dropdown-menu');
            var toggle = document.getElementById('topbar-dropdown-toggle');

            // Toggle the dropdown menu visibility
            menu.classList.toggle('show');

            // Toggle the rotation animation
            toggle.classList.toggle('rotate');
        });

        document.addEventListener('click', function(e) {
            var toggle = document.getElementById('topbar-dropdown-toggle');
            var menu = document.getElementById('topbar-dropdown-menu');

            // Hide the dropdown menu and reset rotation if clicked outside
            if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
                toggle.classList.remove('rotate');
            }
        });
    </script>
</body>

</html>