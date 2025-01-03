<?php include 'includes/PROhistoryU.php'; ?>
<?php include 'navbar.php'; ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.3.2/css/rowReorder.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.css" rel="stylesheet">
    <link href="assets/css/historyuser/historyuser.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <style>

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body id="body">


    <div style="display: flex; align-items: center; color: #c8c8c8;" class="rail">
        <a href="home.php" style="margin-left: 4rem; color: #c8c8c8;">Dashboard &nbsp;</a>
        <p style="margin: 0;"> >&nbsp;</p>
        <a href="historyU.php" style="color: #aba9a9;font-size:20px">History </a>
    </div>
    <?php
    // $SHcount = $config->query("SELECT COUNT(*) AS count FROM approval WHERE status ='head section' and id_user = $id_user")->fetch_assoc()['count'];
    // $CCcount = $config->query("SELECT COUNT(*) AS count FROM approval WHERE status ='cost control' and id_user = $id_user")->fetch_assoc()['count'];
    // $Scount = $config->query("SELECT COUNT(*) AS count FROM approval WHERE status ='success' and id_user = $id_user")->fetch_assoc()['count'];
    // $Rcount = $config->query("SELECT COUNT(*) AS count FROM approval WHERE status ='revisi' and id_user = $id_user")->fetch_assoc()['count'];
    

    ?>
    <div class="card">
        <h3 style="color:#aba9a9">History</h3>
        <div class="nav-buttons-container">
            <div class="nav-buttons">
                <button onclick="showPage(5)" id="btn-5" class="nav-btn"><span>Draft <br>( <?php echo $Dcount; ?>
                        )</span></button>
                <button onclick="showPage(1)" id="btn-1" class="nav-btn"><span>Approval Section Head <br>(
                        <?php echo $SHcount; ?> )</span></button>
                <button onclick="showPage(2)" id="btn-2" class="nav-btn"><span>Approval Cost Control <br>(
                        <?php echo $CCcount; ?> )</span></button>
                <button onclick="showPage(3)" id="btn-3" class="nav-btn"><span>Approved Successfully <br>(
                        <?php echo $Scount; ?> )</span></button>
                <button onclick="showPage(4)" id="btn-4" class="nav-btn"><span>Revisi <br>( <?php echo $Rcount; ?>
                        )</span></button>
            </div>
        </div>

        <div class="card2">
            <div style="display: flex; align-items: center; margin-bottom: 20px;">

            </div>
            <div class="table-page" id="page-5">
                <div class="table-container">
                    <div>
                        <button type="button" class="btn-f" onclick="window.location.href='form.php'">
                            <i class="fas fa-plus"></i> Add
                        </button>

                        <button id="showF5" class="btn-f" style="margin-bottom: 1rem;"><i
                                class="fa-solid fa-filter"></i> Filter</button>

                        <form id="filterForm5" action="" method="get">
                            <div id="cardContainer5" class="filter">
                                <label class="title-filter">FILTER</label>
                                <div class="form-group">
                                    <label for="dateRange">Date :</label>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="startDate5" name="startDate"
                                                placeholder="Start Date">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="endDate5" name="endDate"
                                                placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice">No Invoice:</label>
                                    <input type="text" name="Finvoice" class="form-control" id="inputBox5"
                                        placeholder="No Invoice">
                                </div>
                                <div class="form-group">
                                    <label for="vendor">Vendor:</label>
                                    <input type="text" name="Fvendor" class="form-control" id="inputBox5"
                                        placeholder="Nama Vendor">
                                </div>
                                <div class="form-group">
                                    <label for="departemen">Departemen:</label>
                                    <input type="text" name="Fdepartemen" class="form-control" id="inputBox5"
                                        placeholder="Departemen">
                                </div>
                                <div class="form-group">
                                    <button type="reset" id="resetFilter5" class="btn-r">Reset</button>
                                    <button type="submit" class="btn-a">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table id="myTable5" class="display nowrap" style="width:100%">
                        <thead>
                            <tr class="fw-semibold fs-6 bg-body-secondary opacity-75 ">
                                <th style="height: 50px;width: 40px; padding-top:1rem">No</th>
                                <th>No Invoice</th>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Departemen</th>
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
                            $query = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id  WHERE status = 'draft' AND id_user = '$id_user'";

                            if ($startDate && $endDate) {
                                $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
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

                            $query .= " ORDER BY id DESC";

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
                                <tr class="clickable-row5 text-muted" style="font-size: 14px;"
                                    data-id="<?php echo $row['id']; ?>">
                                    <td style="height: 50px;"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['no_invoice']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>

                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- table 1 -->
            <div class="table-page" id="page-1">
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
                                <th>Vendor</th>
                                <th>Departemen</th>
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
                            $query = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id  WHERE status = 'head section' AND id_user = '$id_user'";

                            if ($startDate && $endDate) {
                                $query .= " AND date BETWEEN '$startDate' AND '$endDate'";
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

                            $query .= " ORDER BY id DESC";

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
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>

                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-page" id="page-2">
                <div class="table-container">
                    <div>
                        <button id="showF2" class="btn-f" style="margin-bottom: 1rem;"><i
                                class="fa-solid fa-filter"></i> Filter</button>

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
                                <th>Vendor</th>
                                <th>Departemen</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Connect to the database
                            include 'includes/config.php'; // Ensure this file contains your database connection setup
                            
                            // Initialize filter variables
                            $startDate = isset($_GET['startDate2']) ? $_GET['startDate2'] : '';
                            $endDate = isset($_GET['endDate2']) ? $_GET['endDate2'] : '';
                            $invoice = isset($_GET['Finvoice2']) ? $_GET['Finvoice2'] : '';
                            $vendor = isset($_GET['Fvendor2']) ? $_GET['Fvendor2'] : '';
                            $departemen = isset($_GET['Fdepartemen2']) ? $_GET['Fdepartemen2'] : '';

                            // Build the query with filters
                            $query2 = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id WHERE status = 'cost control' AND id_user = '$id_user'";

                            if ($startDate && $endDate) {
                                $query2 .= " AND date BETWEEN '$startDate' AND '$endDate'";
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

                            $query2 .= " ORDER BY id DESC";

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
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>

                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table-page" id="page-3">
                <div class="table-container">
                    <div>
                        <button id="showF3" class="btn-f" style="margin-bottom: 1rem;"><i
                                class="fa-solid fa-filter"></i> Filter</button>

                        <form id="filterForm3" action="" method="get">
                            <div id="cardContainer3" class="filter">
                                <label class="title-filter">FILTER</label>
                                <div class="form-group">
                                    <label for="dateRange">Date :</label>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="startDate3" name="startDate3"
                                                placeholder="Start Date">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="endDate3" name="endDate3"
                                                placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice">No Invoice:</label>
                                    <input type="text" name="Finvoice3" class="form-control" id="inputBox3"
                                        placeholder="No Invoice">
                                </div>
                                <div class="form-group">
                                    <label for="vendor">Vendor:</label>
                                    <input type="text" name="Fvendor3" class="form-control" id="inputBox3"
                                        placeholder="Nama Vendor">
                                </div>
                                <div class="form-group">
                                    <label for="departemen">Departemen:</label>
                                    <input type="text" name="Fdepartemen3" class="form-control" id="inputBox3"
                                        placeholder="Departemen">
                                </div>
                                <div class="form-group">
                                    <button type="reset" id="resetFilter3" class="btn-r">Reset</button>
                                    <button type="submit" class="btn-a">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table id="myTable3" class="display nowrap" style="width:100%">
                        <thead>
                            <tr class="fw-semibold fs-6 bg-body-secondary opacity-75 ">
                                <th style="height: 50px;width: 40px; padding-top:1rem">No</th>
                                <th>No Invoice</th>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Departemen</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Connect to the database
                            include 'includes/config.php'; // Ensure this file contains your database connection setup
                            
                            // Initialize filter variables
                            $startDate = isset($_GET['startDate3']) ? $_GET['startDate3'] : '';
                            $endDate = isset($_GET['endDate3']) ? $_GET['endDate3'] : '';
                            $invoice = isset($_GET['Finvoice3']) ? $_GET['Finvoice3'] : '';
                            $vendor = isset($_GET['Fvendor3']) ? $_GET['Fvendor3'] : '';
                            $departemen = isset($_GET['Fdepartemen3']) ? $_GET['Fdepartemen3'] : '';

                            // Build the query with filters
                            $query3 = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id WHERE status = 'success' AND id_user = '$id_user'";

                            if ($startDate && $endDate) {
                                $query3 .= " AND date BETWEEN '$startDate' AND '$endDate'";
                            }

                            if ($invoice) {
                                $query3 .= " AND no_invoice LIKE '%$invoice%'";
                            }

                            if ($vendor) {
                                $query3 .= " AND vendor.nama LIKE '%$vendor%'";
                            }

                            if ($departemen) {
                                $query3 .= " AND users.dept LIKE '%$departemen%'";
                            }

                            $query3 .= " ORDER BY id DESC";

                            // Execute the query
                            $result3 = mysqli_query($config, $query3);

                            // Check for errors
                            if (!$result3) {
                                die("Query failed: " . mysqli_error($config));
                            }

                            // Display the results
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result3)) {
                                ?>
                                <tr class="clickable-row3 text-muted  " style="font-size: 14px;"
                                    data-id="<?php echo $row['id']; ?>">
                                    <td style="height: 50px;"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['no_invoice']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>

                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table-page" id="page-4">
                <div class="table-container">
                    <div>
                        <button id="showF4" class="btn-f" style="margin-bottom: 1rem;"><i
                                class="fa-solid fa-filter"></i> Filter</button>

                        <form id="filterForm4" action="" method="get">
                            <div id="cardContainer4" class="filter">
                                <label class="title-filter">FILTER</label>
                                <div class="form-group">
                                    <label for="dateRange">Date :</label>
                                    <div class="form-row">
                                        <div class="col">
                                            <input type="text" class="form-control" id="startDate4" name="startDate4"
                                                placeholder="Start Date">
                                        </div>
                                        <div class="col">
                                            <input type="text" class="form-control" id="endDate4" name="endDate4"
                                                placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="invoice">No Invoice:</label>
                                    <input type="text" name="Finvoice4" class="form-control" id="inputBox4"
                                        placeholder="No Invoice">
                                </div>
                                <div class="form-group">
                                    <label for="vendor">Vendor:</label>
                                    <input type="text" name="Fvendor4" class="form-control" id="inputBox4"
                                        placeholder="Nama Vendor">
                                </div>
                                <div class="form-group">
                                    <label for="departemen">Departemen:</label>
                                    <input type="text" name="Fdepartemen4" class="form-control" id="inputBox4"
                                        placeholder="Departemen">
                                </div>
                                <div class="form-group">
                                    <button type="reset" id="resetFilter4" class="btn-r">Reset</button>
                                    <button type="submit" class="btn-a">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <table id="myTable4" class="display nowrap" style="width:100%">
                        <thead>
                            <tr class="fw-semibold fs-6 bg-body-secondary opacity-75 ">
                                <th style="height: 50px;width: 40px; padding-top:1rem">No</th>
                                <th>No Invoice</th>
                                <th>Date</th>
                                <th>Vendor</th>
                                <th>Departemen</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Connect to the database
                            include 'includes/config.php'; // Ensure this file contains your database connection setup
                            
                            // Initialize filter variables
                            $startDate = isset($_GET['startDate4']) ? $_GET['startDate4'] : '';
                            $endDate = isset($_GET['endDate4']) ? $_GET['endDate4'] : '';
                            $invoice = isset($_GET['Finvoice4']) ? $_GET['Finvoice4'] : '';
                            $vendor = isset($_GET['Fvendor4']) ? $_GET['Fvendor4'] : '';
                            $departemen = isset($_GET['Fdepartemen4']) ? $_GET['Fdepartemen4'] : '';

                            // Build the query with filters
                            $query4 = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id WHERE status = 'revisi' AND id_user = '$id_user'";

                            if ($startDate && $endDate) {
                                $query4 .= " AND date BETWEEN '$startDate' AND '$endDate'";
                            }

                            if ($invoice) {
                                $query4 .= " AND no_invoice LIKE '%$invoice%'";
                            }

                            if ($vendor) {
                                $query4 .= " AND vendor.nama LIKE '%$vendor%'";
                            }

                            if ($departemen) {
                                $query4 .= " AND users.dept LIKE '%$departemen%'";
                            }

                            $query4 .= " ORDER BY id DESC";

                            // Execute the query
                            $result4 = mysqli_query($config, $query4);

                            // Check for errors
                            if (!$result4) {
                                die("Query failed: " . mysqli_error($config));
                            }

                            // Display the results
                            $no = 1;
                            while ($row = mysqli_fetch_assoc($result4)) {
                                ?>
                                <tr class="clickable-row4 text-muted  " style="font-size: 14px;"
                                    data-id="<?php echo $row['id']; ?>">
                                    <td style="height: 50px;"><?php echo $no++; ?></td>
                                    <td><?php echo htmlspecialchars($row['no_invoice']); ?></td>
                                    <td><?php echo htmlspecialchars($row['date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vendor']); ?></td>
                                    <td><?php echo htmlspecialchars($row['dept']); ?></td>
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

    <script src="assets/js/historyU.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.3.2/js/dataTables.rowReorder.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    

</body>

</html>