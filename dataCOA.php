<?php include 'includes/PROdataCOA.php'; ?>
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
        <h1 class="title-form">Data COA</h1>
    </div>
    <div class="card">
        <div class="card2">
            <div class="table-container">
                <table id="myTable" class="display nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>COA</th>
                            <th>KetCOA</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>";
                                echo "<td>" . $row['COA'] . "</td>";
                                echo "<td>" . $row['KETCOA'] . "</td>";
                                echo "<td>
                                    <a href='formeditCOA.php?id=" . htmlspecialchars($row['id']) . "' class='btn btn-warning'>
                                        <i class='fa-solid fa-pen-to-square'></i>
                                    </a>
                                    <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>
                                        <i class='fas fa-trash'></i>
                                    </button>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>No COA found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <a href="fCOA.php" style="margin-bottom: 10px;" class="btn btn-outline-danger rounded-circle">
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
            console.log("jQuery loaded and ready.");
            console.log("Tombol hapus ditemukan: ", $('.delete-btn').length);

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

            // Menggunakan event delegation untuk tombol hapus
            $(document).on('click', '.delete-btn', function() {
                console.log("Tombol hapus diklik"); // Log saat tombol di klik
                var userID = $(this).data('id');
                var row = $(this).closest('tr');

                if (confirm("Apakah Anda yakin ingin menghapus COA ini?")) {
                    $.ajax({
                        type: 'POST',
                        url: 'dataCOA.php', // Pastikan ini adalah file yang benar
                        data: {
                            id: userID
                        },
                        success: function(response) {
                            console.log("Respon dari server: ", response); // Log respon untuk debugging
                            if (response.trim() === 'success') {
                                alert("COA berhasil dihapus.");
                                row.remove();
                            } else {
                                alert("Gagal menghapus COA: " + response);
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error("Kesalahan AJAX: ", textStatus, errorThrown); // Log kesalahan AJAX
                            alert("Kesalahan dalam permintaan AJAX: " + textStatus + ", " + errorThrown);
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>