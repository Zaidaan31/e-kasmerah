<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard E-KAS MERAH</title>
    <link href="assets/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
        .btndrop {
            background: transparent;
            /* Makes the background transparent */
            color: white;
            /* Sets the text color to white */
            border: none;
            /* Removes the border */
            padding: 10px 15px;
            /* Adjusts padding as needed */
            font-size: 16px;
            /* Adjusts font size as needed */
            cursor: pointer;
            /* Changes cursor to pointer */
            display: flex;
            /* Aligns items in a flex container */
            align-items: center;
            /* Centers items vertically */
        }

        .btndrop i {
            margin-right: 10px; 
            /* Adds space between icon and text */
        }

        .btndrop span {
            margin-right: 10px; 
            /* Adds space between icon and text */
        }



        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            min-width: 160px;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            z-index: 1000;
        }

        .dropdown-item {
            padding: 10px 20px;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-toggle::after {
            content: '\f078';
            /* FontAwesome down arrow */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            margin-left: 5px;
            transition: transform 10s ease-in-out;
            /* Smooth spin animation */
        }

        /* Rotated arrow class for 180-degree spin */
        .rotate-spin {
            transform: rotate(180deg);
            /* 180-degree rotation */
        }

        .dropdown.show .dropdown-menu {
            display: block;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="brand">
        <i class="fa-solid fa-book"></i>
            <span class="brand-text">E-KAS MERAH</span>
        </div>
        <a href="home.php"><i class="fas fa-home"></i><span class="text">Home</span></a>
        <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'user'): ?>
            <div>
                <div class="user-header sidebar-header">User </div>
                <a href="historyU.php"><i class="fa-solid fa-clock-rotate-left"></i><span class="text">History</span></a>
                <a href="form.php"><i class="fa-regular fa-file"></i><span class="text">Form</span></a>
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'head section' || $_SESSION['level'] == 'head section tol'): ?>
            <div>
                <div class="user-header sidebar-header">Section Head </div>
                <a href="approvalSH.php"><i class="fa-regular fa-circle-check"></i><span class="text">Approval</span></a>
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'cost control'): ?>
            <div>
                <div class="user-header sidebar-header">Cost Control </div>
                <a href="approvalCC.php"><i class="fa-regular fa-circle-check"></i><span class="text">Approval</span></a>
                <a href="dataCOA.php"><i class="fa-solid fa-database"></i><span class="text">Data COA</span></a>
                <a href="fDCOA.php"><i class="fa-regular fa-file"></i><span class="text">Form COA</span></a>
            </div>
        <?php endif; ?>
        <?php if ($_SESSION['level'] == 'admin'): ?>
            <div>
                <div class="user-header sidebar-header">Admin</div>
                <a href="datauser.php"><i class="fa-regular fa-user"></i><span class="text">Data User</span></a>
                <a href="formuser.php"><i class="fa-regular fa-file"></i><span class="text">Form User</span></a>
                <a href="dataVendor.php"><i class="fa-solid fa-database"></i><span class="text">Data Vendor</span></a>
                <a href="fvendor.php"><i class="fa-regular fa-file"></i><span class="text">Form Vendor</span></a>
            </div>
        <?php endif; ?>
    </div>
    </div>

    <div class="content">
        <div class="topbar">
            <div class="topbar-left">
                <!-- Additional topbar content, if any -->
            </div>
            <div class="topbar-right">
                <div class="dropdown">
                    <button type="button" class="btndrop" id="dropdownMenuButton">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['username']); ?></span><span>(<?= htmlspecialchars($_SESSION['dept']); ?>)</span>
                        <i class="fas fa-chevron-down" id="dropdownArrow"></i> <!-- Arrow icon -->
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#" id="logoutButton">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="hamburger">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>


        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.querySelector('.hamburger').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.toggle('active');
                document.querySelector('.content').classList.toggle('active');
                document.querySelector('.hamburger').classList.toggle('active');
            });

            document.querySelector('#dropdownMenuButton').addEventListener('click', function() {
                var dropdown = document.querySelector('.dropdown');
                var arrow = document.querySelector('#dropdownArrow');
                dropdown.classList.toggle('show');
                arrow.classList.toggle('rotate-spin'); // Toggle the 180-degree spin class
            });

            document.addEventListener('click', function(event) {
                var dropdown = document.querySelector('.dropdown');
                var arrow = document.querySelector('#dropdownArrow');
                if (!dropdown.contains(event.target) && !event.target.matches('#dropdownMenuButton')) {
                    dropdown.classList.remove('show');
                    arrow.classList.remove('rotate-spin'); // Remove rotation when closing
                }
            });

            // SweetAlert2 for logout confirmation
            document.querySelector('#logoutButton').addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                Swal.fire({
                    title: "Are you sure to Logout?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, logout!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Redirect to logout URL if confirmed
                        window.location.href = 'includes/PROlogout.php';
                    }
                });
            });
        </script>
</body>

</html>