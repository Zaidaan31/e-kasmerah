<?php include 'includes/PROapCC.php'; ?>
<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Cost Control</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.3.2/css/rowReorder.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.css" rel="stylesheet">
    <link href="assets/css/approval/approvalCC.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .title-filter {
            width: 100%;
            height: 25px;
            background-color: #e7e7e76a;
            color: grey !important;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: 600;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .filter {
            display: none;
            padding: 20px;
            margin-top: 10rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            position: absolute;
            top: 16%;
            left: 2.5%;
            width: 24rem;
            z-index: 9999;
            background-color: white;
        }

        .form-row {
            display: flex;
            flex-wrap: nowrap;
        }

        .form-row .col {
            flex: 1;
            margin-right: 10px;
        }

        .form-row .col:last-child {
            margin-right: 0;
        }

        .btn-f {
            background: #b3b3b36a;
            font-weight: 600;
            color: #282828;
            border: none;
            border-radius: 5px;
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-f:hover {
            background: #939393;
        }

        .btn-r {
            background: #b3b3b36a;
            font-weight: 600;
            color: #282828;
            border: none;
            border-radius: 5px;
            padding: 8px 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-r:hover {
            background: #939393;
        }

        .btn-a {
            background: #00aaff;
            font-weight: 600;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 108px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-a:hover {
            background: #00537d;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body id="body">
    <div class="header">
        <a href="home.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">APPROVAL COST CONTROL</h1>
    </div>
    <div class="card">
        <div class="nav-buttons-container">
            <div class="nav-buttons">
                <button onclick="showPage(1)" id="btn-1" class="nav-btn"><span style="font-size: 10px;">Approval<br>(
                        <?php echo $APcount; ?> )</span></button>
                <button onclick="showPage(2)" id="btn-2" class="nav-btn"><span style="font-size: 15px;">Success <br>(
                        <?php echo $SCcount; ?> )</span></button>
            </div>
        </div>

        <div class="card2">
            <div id="page-1" class="table-page">
                <div class="table-container">
                    <div>
                        <button id="showF1" class="btn-f" style="margin-bottom: 1rem;"><i
                                class="fa-solid fa-filter"></i> Filter</button>

                        <form id="filterForm1" action="" method="get">
                            <div id="cardContainer1" class="filter">
                                <label class="title-filter">FILTER</label>
                                <div class="form-group">
                                    <label for="dateRange">Date :</label>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="startDate1" name="startDate"
                                                placeholder="Start Date">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="endDate1" name="endDate"
                                                placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice">No Invoice:</label>
                                    <input type="text" name="Finvoice" class="form-control" id="inputBox1"
                                        placeholder="No Invoice">
                                </div>
                                <div class="form-group">
                                    <label for="vendor">Vendor:</label>
                                    <input type="text" name="Fvendor" class="form-control" id="inputBox1"
                                        placeholder="Nama Vendor">
                                </div>
                                <div class="form-group">
                                    <label for="departemen">Departemen:</label>
                                    <input type="text" name="Fdepartemen" class="form-control" id="inputBox1"
                                        placeholder="Departemen">
                                </div>
                                <div class="form-group">
                                    <button type="reset" id="resetFilter1" class="btn-r">Reset</button>
                                    <button type="submit" class="btn-a">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table id="myTable1" class="display nowrap" style="width:100%">
                        <thead>
                            <tr class="fw-semibold fs-6 bg-body-secondary opacity-75 ">
                                <th style="height: 50px;width: 40px; padding-top:1rem">No</th>
                                <th>No Invoice</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Departemen</th>
                                <th>Vendor</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Connect to the database
                            include 'includes/config.php'; // Ensure this file contains your database connection setup

                            // Initialize filter variables
                            $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : '';
                            $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : '';
                            $invoice = isset($_GET['Finvoice']) ? $_GET['Finvoice'] : '';
                            $vendor = isset($_GET['Fvendor']) ? $_GET['Fvendor'] : '';
                            $departemen = isset($_GET['Fdepartemen']) ? $_GET['Fdepartemen'] : '';

                            // Build the query with filters
                            $query = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept ,users.username as user
                            FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id WHERE status = 'cost control'";

                            if ($startDate && $endDate) {
                                $query .= " AND date >= '$startDate' AND date < DATE_ADD('$endDate', INTERVAL 1 DAY)";
                            }

                            if ($invoice) {
                                $query .= " AND no_invoice LIKE '%$invoice%'";
                            }

                            if ($vendor) {
                                $query .= " AND vendor.nama LIKE '%$vendor%'";
                            }

                            if ($departemen) {
                                $query .= " AND users.dept LIKE '%$departemen%'";
                            }

                            $query .= " ORDER BY date DESC";

                            // Execute the query
                            $result = mysqli_query($config, $query);

                            // Check for errors
                            if (!$result) {
                                die("Query failed: " . mysqli_error($config));
                            }

                            // Display the results
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr class="clickable-row1 text-muted  " style="font-size: 14px;"
                                    data-id="<?php echo $row['id']; ?>">
                                    <td style="height: 50px;"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['no_invoice']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td style="width: 10rem;"><?php echo htmlspecialchars($row['user']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="page-2" class="table-page" style="display: none;">
                <div class="table-container">
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <button id="showF2" class="btn-f" style="margin-bottom: 1rem;"><i
                                    class="fa-solid fa-filter"></i> Filter</button>


                            <form method="POST" action="excel.php" class="form-inline">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="startDate" name="startDate" placeholder="Start Date" />
                                </div>
                                <div class="form-group mx-2">
                                    <label for="date2" class="control-label">To</label>
                                    <input type="text" class="form-control" id="endDate" name="endDate" placeholder="End Date" />
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-success" name="submit">
                                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Excel
                                    </button>
                                </div>
                            </form>
                        </div>

                        <form id="filterForm2" action="" method="get">
                            <div id="cardContainer2" class="filter">
                                <label class="title-filter">FILTER</label>
                                <div class="form-group">
                                    <label for="dateRange">Date :</label>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="startDate2" name="startDate2"
                                                placeholder="Start Date">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="endDate2" name="endDate2"
                                                placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice">No Invoice:</label>
                                    <input type="text" name="Finvoice2" class="form-control" id="inputBox2"
                                        placeholder="No Invoice">
                                </div>
                                <div class="form-group">
                                    <label for="vendor">Vendor:</label>
                                    <input type="text" name="Fvendor2" class="form-control" id="inputBox2"
                                        placeholder="Nama Vendor">
                                </div>
                                <div class="form-group">
                                    <label for="departemen">Departemen:</label>
                                    <input type="text" name="Fdepartemen2" class="form-control" id="inputBox2"
                                        placeholder="Departemen">
                                </div>
                                <div class="form-group">
                                    <button type="reset" id="resetFilter2" class="btn-r">Reset</button>
                                    <button type="submit" class="btn-a">Apply</button>
                                </div>
                            </div>
                        </form>

                    </div>



                    <table id="myTable2" class="display nowrap" style="width:100%">
                        <thead>
                            <tr class="fw-semibold fs-6 bg-body-secondary opacity-75 ">
                                <th style="height: 50px;width: 40px; padding-top:1rem">No</th>
                                <th>No Invoice</th>
                                <th>Date</th>
                                <th>User</th>
                                <th>Departemen</th>
                                <th>Vendor</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Initialize filter variables
                            $startDate = isset($_GET['startDate2']) ? $_GET['startDate2'] : '';
                            $endDate = isset($_GET['endDate2']) ? $_GET['endDate2'] : '';
                            $invoice = isset($_GET['Finvoice2']) ? $_GET['Finvoice2'] : '';
                            $vendor = isset($_GET['Fvendor2']) ? $_GET['Fvendor2'] : '';
                            $departemen = isset($_GET['Fdepartemen2']) ? $_GET['Fdepartemen2'] : '';

                            // Build the query with filters
                            $query2 = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept ,users.username as user
                             FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id WHERE status = 'success'";

                            if ($startDate && $endDate) {
                                $query2 .= " AND date >= '$startDate' AND date < DATE_ADD('$endDate', INTERVAL 1 DAY)";
                            }

                            if ($invoice) {
                                $query2 .= " AND no_invoice LIKE '%$invoice%'";
                            }

                            if ($vendor) {
                                $query2 .= " AND vendor.nama LIKE '%$vendor%'";
                            }

                            if ($departemen) {
                                $query2 .= " AND users.dept LIKE '%$departemen%'";
                            }

                            $query2 .= " ORDER BY date DESC";

                            // Execute the query
                            $result2 = mysqli_query($config, $query2);

                            // Check for errors
                            if (!$result2) {
                                die("Query failed: " . mysqli_error($config));
                            }

                            // Display the results
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result2)) {
                            ?>
                                <tr class="clickable-row2 text-muted  " style="font-size: 14px;"
                                    data-id="<?php echo $row['id']; ?>">
                                    <td style="height: 50px;"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['no_invoice']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td style="width: 10rem;"><?php echo htmlspecialchars($row['user']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/approvalCC.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.3.2/js/dataTables.rowReorder.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script>
        // Cek dan tampilkan alert jika ada pesan sukses

        $(document).ready(function() {
            <?php if (isset($_SESSION['success_message'])): ?>
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "<?php echo $_SESSION['success_message']; ?>",
                    showConfirmButton: false,
                    timer: 1500
                });
                <?php unset($_SESSION['success_message']); // Hapus pesan setelah ditampilkan 
                ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>