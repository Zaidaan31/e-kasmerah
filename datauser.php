<?php include 'includes/PROdatauser.php'; ?>
<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/rowreorder/1.3.2/css/rowReorder.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.css" rel="stylesheet">
    <link href="assets/css/fuser/fuser.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body id="body">
<div class="header">
        <a href="home.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">Data User</h1>
    </div>
    <div class="card">
        <div class="card2">
            <div class="table-container">
                <table id="myTable" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <!-- <th>Password</th> -->
                            <th>Departemen</th>
                            <th>Level</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1; // Initialize counter
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>"; // Incremental numbering for each user
                                echo "<td>" . htmlspecialchars($row['username']) . "</td>"; // Escape output for security
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>"; // Escape output for security
                                // echo "<td>" . htmlspecialchars($row['password']) . "</td>"; // Consider not displaying passwords
                                echo "<td>" . htmlspecialchars($row['dept']) . "</td>"; // Escape output for security
                                echo "<td>" . htmlspecialchars($row['level']) . "</td>"; // Escape output for security
                                echo "<td>
                                    <a href='formedituser.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning'>
                                        <i class='fa-solid fa-pen-to-square'></i>
                                    </a>
                                    <button class='btn btn-danger delete-btn' data-id='" . htmlspecialchars($row['id']) . "'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                </td>";
                                echo "</tr>";
                            }

                           
                        } else {
                            echo "<tr><td colspan='5'>No users found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="formuser.php" style="margin-bottom: 10px;" class="btn btn-outline-danger rounded-circle">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/rowreorder/1.3.2/js/dataTables.rowReorder.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                scrollX: true,
                info: false
            });

            $('.dataTables_length').each(function() {
                $(this).html(
                    '<label style="display: flex; align-items: center;">' +
                    '<i class="fa fa-list" style="margin-right: 8px; color: #888;"></i>' +
                    $(this).find('select').prop('outerHTML') +
                    '</label>'
                );
            });

            // Delete user functionality
            $('.delete-btn').click(function() {
                var userId = $(this).data('id');
                var row = $(this).closest('tr');

                if (confirm("Are you sure you want to delete this user?")) {
                    $.ajax({
                        type: 'POST',
                        url: 'datauser.php', // Change this to the correct PHP file or leave as is if it's the same file
                        data: {
                            id: userId
                        },
                        success: function(response) {
                            if (response.trim() === 'success') { // .trim() ensures no whitespace issues
                                alert("User deleted successfully.");
                                row.remove();
                            } else {
                                alert("Failed to delete user.");
                            }
                        },
                        error: function() {
                            alert("Error in AJAX request.");
                        }
                    });
                }
            });

        });
    </script>
</body>

</html>