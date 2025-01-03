<?php include "includes/PROfviewrevisi.php" ?>
<?php include "navbar.php" ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Parkir</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/form/form.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .pr {
            margin-top: 1rem;
            margin-left: 3rem;
            font-size: 12px;
            color: grey !important;
            position: absolute;
            width: 100%;
            text-decoration: underline;
        }
    </style>
</head>

<body id="body">

    <div class="header">
        <a href="historyU.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form"><?php echo $data['no_invoice'] ?></h1>
    </div>
    <div class="card">
        <form id="myForm">
            <div class="card2">
                <div class="form-row">
                    <div class="form-group">
                        <label for="departemen">Departemen:</label>
                        <input type="text" name="departemen" value="<?php echo $data['dept'] ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="vendor">Nama Vendor:</label>
                        <div class="form-group">
                            <input type="text" name="dbyrkpd" value="<?php echo $data['vendor'] ?>" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="invoice">Invoice:</label>
                        <input type="text" name="invoice" value="<?php echo $data['invoice'] ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <div style="display: flex; gap: 10px;">
                            <!-- Menggunakan flex untuk mengatur jarak antara input -->
                            <input type="text" id="startPeriode" name="startPeriode"
                                value="<?php echo $data['periodeAwal'] ?>" placeholder="Start Periode" disabled>
                            <input type="text" id="endPeriode" name="endPeriode"
                                value="<?php echo $data['periodeAkhir'] ?>" placeholder="End Periode" disabled>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggalPengajuan">Tanggal Pengajuan:</label>
                        <input type="text" id="tanggalPengajuan" name="tanggalPengajuan"
                            value="<?php echo $data['tanggalPengajuan'] ?>" placeholder="Tanggal Pengajuan" disabled>
                    </div>
                    <div class="form-group">
                        <?php
                        // Ensure that $data['kronologi'] contains the filename only
                        $imageName = !empty($data['kronologi']) ? basename($data['kronologi']) : 'No files uploaded !';
                        $imageUrl = !empty($data['kronologi']) ? 'assets/image/' . htmlspecialchars($data['kronologi'], ENT_QUOTES, 'UTF-8') : '#';
                        ?>
                        <label for="kronologi">Kronologi:</label>
                        <div class="upload-wrapper">
                            <div class="upload-section">
                                <div class="upload-content">
                                    <div>
                                        <!-- Icon for upload if no image is present -->
                                        <i class="fa-regular fa-file upload-icon" id="icon-kronologi"
                                            style="<?php echo !empty($data['kronologi']) ? 'display: none;' : ''; ?>"></i>
                                        <!-- Preview image -->
                                        <img id="preview-kronologi" class="image-preview" src="<?php echo $imageUrl; ?>"
                                            onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                            style="<?php echo !empty($data['kronologi']) ? 'display: block;' : 'display: none;'; ?>" />
                                    </div>
                                    <div class="pr" id="placeholder-kronologi"
                                        onclick="document.getElementById('upload-kronologi').click()">
                                        <p><?php echo $imageName; ?></p>
                                    </div>
                                </div>
                                <input type="file" id="upload-kronologi" name="kronologi"
                                    onchange="handleFileSelect(event, 'icon-kronologi', 'preview-kronologi', 'placeholder-kronologi', 'download-kronologi')"
                                    disabled>
                                <!-- Download link -->
                                <a class="btndwld" id="download-kronologi" href="<?php echo $imageUrl; ?>" download><i
                                        class="fa-solid fa-download"></i></a>
                            </div>

                            <small class="catatan">*Catatan : Hanya format yang didukung*</small>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <?php
                        // Ensure that $data['dispo'] contains the filename only
                        $imageName = !empty($data['dispo']) ? basename($data['dispo']) : 'No files uploaded !';
                        $imageUrl = !empty($data['dispo']) ? 'assets/image/' . htmlspecialchars($data['dispo'], ENT_QUOTES, 'UTF-8') : '#';
                        ?>
                        <label for="dispo">Dispo:</label>
                        <div class="upload-wrapper">
                            <div class="upload-section">
                                <div class="upload-content">
                                    <div>
                                        <!-- Icon for upload if no image is present -->
                                        <i class="fa-regular fa-file upload-icon" id="icon-dispo"
                                            style="<?php echo !empty($data['dispo']) ? 'display: none;' : ''; ?>"></i>
                                        <!-- Preview image -->
                                        <img id="preview-dispo" class="image-preview" src="<?php echo $imageUrl; ?>"
                                            onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                            style="<?php echo !empty($data['dispo']) ? 'display: block;' : 'display: none;'; ?>" />
                                    </div>
                                    <!-- Placeholder if image is not present -->
                                    <div class="pr" id="placeholder-dispo"
                                        onclick="document.getElementById('upload-dispo').click()">
                                        <p><?php echo $imageName; ?></p>
                                    </div>
                                </div>
                                <!-- File input -->
                                <input type="file" id="upload-dispo" name="dispo" multiple
                                    onchange="handleFileSelect(event, 'icon-dispo', 'preview-dispo', 'placeholder-dispo', 'download-dispo', 'reset-dispo')"
                                    disabled />
                                <!-- Download link -->
                                <a class="btndwld" id="download-dispo" href="<?php echo $imageUrl; ?>" download><i
                                        class="fa-solid fa-download"></i></a>
                            </div>

                            <small class="catatan">*Catatan : Hanya format yang didukung*</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <?php
                        // Ensure that $data['buktipesan'] contains the filename only
                        $imageName = !empty($data['buktipesan']) ? basename($data['buktipesan']) : 'No files uploaded !';
                        $imageUrl = !empty($data['buktipesan']) ? 'assets/image/' . htmlspecialchars($data['buktipesan'], ENT_QUOTES, 'UTF-8') : '#';
                        ?>
                        <label for="buktiWA">Bukti WA / Email:</label>
                        <div class="upload-wrapper">
                            <div class="upload-section">
                                <div class="upload-content">
                                    <div>
                                        <!-- Icon for upload if no image is present -->
                                        <i class="fa-regular fa-file upload-icon" id="icon-bukti-wa"
                                            style="<?php echo !empty($data['buktipesan']) ? 'display: none;' : ''; ?>"></i>
                                        <!-- Preview image -->
                                        <img id="preview-bukti-wa" class="image-preview" src="<?php echo $imageUrl; ?>"
                                            onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                            style="<?php echo !empty($data['buktipesan']) ? 'display: block;' : 'display: none;'; ?>" />
                                    </div>
                                    <!-- Placeholder if image is not present -->
                                    <div class="pr" id="placeholder-bukti-wa"
                                        onclick="document.getElementById('upload-bukti-wa').click()">
                                        <p><?php echo $imageName; ?></p>
                                    </div>
                                </div>
                                <!-- File input -->
                                <input type="file" id="upload-bukti-wa" name="buktipesan" multiple
                                    onchange="handleFileSelect(event, 'icon-bukti-wa', 'preview-bukti-wa', 'placeholder-bukti-wa', 'download-bukti-wa', 'reset-bukti-wa')"
                                    disabled />
                                <!-- Download link -->
                                <a class="btndwld" id="download-bukti-wa" href="<?php echo $imageUrl; ?>" download><i
                                        class="fa-solid fa-download"></i></a>
                            </div>

                        </div>
                        <small class="catatan">*Catatan : Hanya format yang didukung*</small>

                    </div>
                </div>


                <div class="table-wrapper" style="margin-top: 15px;">
                    <span class="title"> List Of Value :</span>
                    <table id="barangTable" style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Keterangan</th>
                                <th>Deskripsi</th>
                                <th style="width: 20%;">Jumlah</th>
                                <th style="width: 30%;">Bukti</th>
                                <th style="width: 8%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php while ($row = mysqli_fetch_assoc($lovResult)): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <!-- Input untuk id_lov -->
                                    <input type="hidden" name="id_lov[]" value="<?php echo $row['id']; ?>">

                                    <!-- Input untuk id_ket -->
                                    <input type="hidden" name="id_ket[]" value="<?php echo $row['id_ket']; ?>">
                                    <td>
                                        <select class="select-box" name="keterangan[]"
                                            id="keterangan-<?php echo $row['id']; ?>"
                                            onchange="updateBuktiType(this, <?php echo $row['id']; ?>)" disabled>
                                            <option value="" disabled>Select</option>
                                            <?php
                                            // Get all keterangan options from the database
                                            $query1 = mysqli_query($config, "SELECT id, nama FROM keterangan ORDER BY nama ASC");
                                            while ($data2 = mysqli_fetch_array($query1)) {
                                                // Set the selected option based on the database value
                                                $selected = ($row['id_ket'] == $data2['id']) ? 'selected' : '';
                                                echo "<option value='{$data2['id']}' {$selected}> {$data2['nama']} </option>";
                                            }
                                            ?>
                                        </select>
                                    </td>

                                    <td>
                                        <textarea class="form-control" name="deskripsi[]" class="deskripsi"
                                            placeholder="Deskripsi" disabled>
                                                        <?php echo $row['deskripsi'] ?>
                                                    </textarea>
                                    </td>



                                    <td>
                                        <div class="input-container">
                                            <!-- Textbox jumlah 1 for id_ket other than 1, 9, 14, 13 -->
                                            <span class="currency-label">Rp.</span>
                                            <input type="number" name="jumlah[]" id="jumlah1-<?php echo $row['id']; ?>"
                                                class="form-control jumlah-1" value="<?php echo $row['jumlah']; ?>"
                                                disabled>
                                        </div>
                                    </td>


                                    <!-- value="<?php echo $row['jumlah'] ?>" -->


                                    <td>
                                        <?php
                                        $imageName = !empty($row['bukti']) ? basename($row['bukti']) : 'No files uploaded!';
                                        $imageUrl = !empty($row['bukti']) ? 'assets/image/' . htmlspecialchars($row['bukti'], ENT_QUOTES, 'UTF-8') : '#';
                                        $rowId = $row['id']; // Menggunakan ID unik dari row (misalnya dari database)
                                        ?>

                                        <div id="upload-wrapper-<?php echo $rowId; ?>" class="upload-wrapper"
                                            style="width: 20rem;">
                                            <div class="upload-section">
                                                <div class="upload-content">
                                                    <div>
                                                        <!-- Icon for file if no file is present -->
                                                        <i class="fa-regular fa-file upload-icon"
                                                            id="icon-bukti-<?php echo $rowId; ?>"
                                                            style="<?php echo !empty($row['bukti']) ? 'display: none;' : ''; ?>"></i>

                                                        <!-- Image preview if file is present -->
                                                        <img id="preview-bukti-<?php echo $rowId; ?>" class="image-preview"
                                                            src="<?php echo $imageUrl; ?>" alt="Preview"
                                                            onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                                            style="<?php echo !empty($row['bukti']) ? 'display: block;' : 'display: none;'; ?>" />

                                                    </div>

                                                    <!-- Placeholder if no file is uploaded -->
                                                    <div class="pr" id="placeholder-bukti   -<?php echo $rowId; ?>"
                                                        onclick="document.getElementById('upload-bukti-<?php echo $rowId; ?>').click()">
                                                        <p><?php echo $imageName; ?></p>
                                                    </div>
                                                </div>

                                                <!-- File input -->
                                                <input type="file" id="upload-bukti-<?php echo $rowId; ?>" name="bukti[]"
                                                    disabled
                                                    onchange="handleFileSelect(event, 'icon-bukti-<?php echo $rowId; ?>', 'preview-bukti-<?php echo $rowId; ?>', 'placeholder-bukti-<?php echo $rowId; ?>', 'download-bukti-<?php echo $rowId; ?>', 'reset-bukti-<?php echo $rowId; ?>')">
                                                <a class="download-button-r" id="download-bukti-<?php echo $rowId; ?>"
                                                    href="<?php echo $imageUrl; ?>" download><i
                                                        class="fa-solid fa-download"></i></a>

                                            </div>
                                        </div>

                                        <a href="javascript:void(0);" id="detail-bukti-<?php echo $row['id']; ?>"
                                            class="btn btn-primary" style="display: none;"
                                            onclick="showDetailBukti(<?php echo $row['id']; ?>)">Lihat Detail Bukti</a>

                                        <a href="javascript:void(0);" id="upload-tol-<?php echo $row['id']; ?>"
                                            class="btn btn-warning" style="display: none;"
                                            onclick="showDetailTol(<?php echo $row['id']; ?>)">Lihat Detail Bukti
                                            Tol</a>


                                        <!-- Modal Structure -->
                                        <div id="imageModal" class="modal">
                                            <span class="close" onclick="closeModal()">&times;</span>
                                            <img class="modal-content" id="modalImage" />
                                            <div id="modalCaption"></div>
                                        </div>
                                    </td>

                                    <td>

                                        <?php if ($row['status'] === 'success' || $row['status'] === 'cost control'): ?>
                                            <span class="badge badge-success">SUCCESS</span>
                                        <?php elseif ($row['status'] === 'revisi'): ?>
                                            <span class="badge badge-warning">REVISI</span>
                                        <?php else: ?>
                                            <form method="POST" action="">
                                                <input type="hidden" name="id_lov" value="<?php echo $row['id']; ?>">


                                            </form>
                                            <form method="POST" action="">
                                                <input type="hidden" name="id_lov" value="<?php echo $row['id']; ?>">

                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3">Jumlah Total</td>
                                <td colspan="1">
                                    <span id="grandTotal" name="jumlahtotal" class="currency">Rp.
                                        <?php echo htmlspecialchars(number_format($data['jumlahtotal']), ENT_QUOTES, 'UTF-8'); ?></span>
                                </td>
                                <td colspan="2">
                                    <div class="total-words">
                                        <span id="grandTotalWords"
                                            class="currency"><?php echo $data['terbilang'] ?></span>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>



                <div id="buktiTable" class="table-wrapper" style="margin-top: 15px;display:none">
                    <span class="title">List Bukti:</span>
                    <table style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th>Biaya</th>
                                <th>Bukti</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $noBukti = 1;
                            mysqli_data_seek($lovResult, 0); // Kembalikan ke awal hasil $lovResult
                            while ($lovRow = $lovResult->fetch_assoc()):
                                ?>
                                <?php
                                $buktiQuery = "SELECT * FROM bukti WHERE id_lov = ?";
                                $stmt_bukti = $config->prepare($buktiQuery);
                                $stmt_bukti->bind_param("i", $lovRow['id']);
                                $stmt_bukti->execute();
                                $buktiResult = $stmt_bukti->get_result();
                                while ($buktiRow = $buktiResult->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $noBukti++; ?></td>
                                        <td>
                                            <input type="hidden" name="id_bukti[]" class="form-control"
                                                value="<?php echo $buktiRow['id'] ?>">
                                            <textarea class="form-control" name="deskripsi_bukti[]" class="deskripsi"
                                                placeholder="Deskripsi"
                                                disabled><?php echo $buktiRow['deskripsi'] ?> </textarea>
                                        </td>
                                        <td>
                                            <div class="input-container">
                                                <span class="currency-label">Rp.</span>
                                                <input type="number" name="biayabukti[]" class="form-control"
                                                    oninput="updateRowTotalbukti(this)" step="any" placeholder="Biaya"
                                                    value="<?php echo $buktiRow['biaya'] ?>" disabled>
                                            </div>
                                        </td>

                                        <td>
                                            <?php
                                            $imageName = !empty($buktiRow['file']) ? basename($buktiRow['file']) : 'No files uploaded!';
                                            $imageUrl = !empty($buktiRow['file']) ? 'assets/image/' . htmlspecialchars($buktiRow['file'], ENT_QUOTES, 'UTF-8') : '#';
                                            $rowId = $buktiRow['id']; // Assuming 'id' is available in $buktiRow
                                            ?>

                                            <div id="upload-wrapper-<?php echo $rowId; ?>" class="upload-wrapper"
                                                style="width: 20rem;">
                                                <div class="upload-section">
                                                    <div class="upload-content">
                                                        <div>
                                                            <!-- Icon for file if no file is present -->
                                                            <i class="fa-regular fa-file upload-icon"
                                                                id="icon-bukti-detail-<?php echo $rowId; ?>"
                                                                style="<?php echo !empty($buktiRow['file']) ? 'display: none;' : ''; ?>"></i>

                                                            <!-- Image preview if file is present -->
                                                            <img id="preview-bukti-detail-<?php echo $rowId; ?>"
                                                                class="image-preview" src="<?php echo $imageUrl; ?>"
                                                                alt="Preview"
                                                                onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                                                style="<?php echo !empty($buktiRow['file']) ? 'display: block;' : 'display: none;'; ?>" />
                                                        </div>

                                                        <!-- Placeholder if no file is uploaded -->
                                                        <div class="pr" id="placeholder-bukti-detail-<?php echo $rowId; ?>"
                                                            onclick="document.getElementById('upload-bukti-detail-<?php echo $rowId; ?>').click()">
                                                            <p><?php echo $imageName; ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- File input -->
                                                    <div class="upload-wrapper">
                                                        <input type="file" id="upload-bukti-detail-<?php echo $rowId; ?>"
                                                            name="bukti_detail[]" disabled
                                                            onchange="handleFileSelect(event, 'icon-bukti-detail-<?php echo $rowId; ?>', 'preview-bukti-detail-<?php echo $rowId; ?>', 'placeholder-bukti-detail-<?php echo $rowId; ?>', 'download-bukti-detail-<?php echo $rowId; ?>', 'reset-bukti-detail-<?php echo $rowId; ?>')" />
                                                    </div>


                                                    <!-- Download button -->
                                                    <a class="download-button-r"
                                                        id="download-bukti-detail-<?php echo $rowId; ?>"
                                                        href="<?php echo $imageUrl; ?>" download>
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>


                                                </div>
                                            </div>

                                            <!-- Modal Structure -->
                                            <div id="imageModal" class="modal">
                                                <span class="close" onclick="closeModal()">&times;</span>
                                                <img class="modal-content" id="modalImage" />
                                                <div id="modalCaption"></div>
                                            </div>
                                        </td>
                                        <td>

                                            <?php if ($buktiRow['status'] === 'success' || $buktiRow['status'] === 'cost control'): ?>
                                                <span class="badge badge-success">SUCCESS</span>
                                            <?php elseif ($buktiRow['status'] === 'revisi'): ?>
                                                <span class="badge badge-warning">REVISI</span>
                                            <?php else: ?>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_lov" value="<?php echo $buktiRow['id']; ?>">


                                                </form>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_lov" value="<?php echo $buktiRow['id']; ?>">

                                                </form>
                                            <?php endif; ?>
                                        </td>


                                    </tr>
                                <?php endwhile; ?>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3">Jumlah Total</td>
                                <td colspan="2">
                                    <!-- Grand Total in Number -->
                                    <span id="grandTotal" class="currency">Rp.
                                        <?php echo htmlspecialchars(number_format($data['jumlah_totalBukti'], 2), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <input type="hidden" id="hiddenTotal" name="jumlahtotalbukti"
                                        value="<?php echo htmlspecialchars($data['jumlah_totalBukti']); ?>">
                                </td>

                            </tr>
                        </tfoot>
                    </table>


                </div>


                <div id="tolTable" class="table-wrapper" style="margin-top: 15px;display:none">
                    <span class="title">List Tol:</span>
                    <table style="margin-top: 10px;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gerbang</th>
                                <th>Tarif</th>
                                <th>Bukti</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $noTol = 1;
                            mysqli_data_seek($lovResult, 0); // Reset result pointer
                            while ($lovRow = $lovResult->fetch_assoc()):
                                ?>
                                <?php
                                // Query to fetch toll and bukti data
                                $tolQuery = "SELECT bukti.*, tol.nama_gerbang FROM bukti LEFT JOIN tol ON bukti.id_tol = tol.id
                                     WHERE id_lov = ?";
                                $stmt_tol = $config->prepare($tolQuery);
                                $stmt_tol->bind_param("i", $lovRow['id']);
                                $stmt_tol->execute();
                                $tolResult = $stmt_tol->get_result();
                                while ($tolRow = $tolResult->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?php echo $noTol++; ?></td>
                                        <td>
                                            <input type="text" value="<?php echo $tolRow['nama_gerbang'] ?>" disabled>
                                        </td>
                                        <td>
                                            <div class="input-container">
                                                <span class="currency-label">Rp.</span>
                                                <input type="number" name="tarif[]" class="form-control"
                                                    oninput="updateRowTotalTol(this)" step="any"
                                                    value="<?php echo $tolRow['biaya'] ?>" disabled>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $imageNameTol = !empty($tolRow['file']) ? basename($tolRow['file']) : 'No files uploaded!';
                                            $imageUrlTol = !empty($tolRow['file']) ? 'assets/image/' . htmlspecialchars($tolRow['file'], ENT_QUOTES, 'UTF-8') : '#';
                                            $rowIdTol = $tolRow['id']; // Assuming 'id' is available in $tolRow
                                            ?>

                                            <div id="upload-wrapper-tol-<?php echo $rowIdTol; ?>" class="upload-wrapper"
                                                style="width: 20rem;">
                                                <div class="upload-section">
                                                    <div class="upload-content">
                                                        <div>
                                                            <!-- Icon for file if no file is present -->
                                                            <i class="fa-regular fa-file upload-icon"
                                                                id="icon-tol-<?php echo $rowIdTol; ?>"
                                                                style="<?php echo !empty($tolRow['file']) ? 'display: none;' : ''; ?>"></i>

                                                            <!-- Image preview if file is present -->
                                                            <img id="preview-tol-<?php echo $rowIdTol; ?>" class="image-preview"
                                                                src="<?php echo $imageUrlTol; ?>" alt="Preview"
                                                                onclick="openModal('<?php echo $imageUrlTol; ?>', '<?php echo $imageNameTol; ?>')"
                                                                style="<?php echo !empty($tolRow['file']) ? 'display: block;' : 'display: none;'; ?>" />
                                                        </div>

                                                        <!-- Placeholder if no file is uploaded -->
                                                        <div class="pr" id="placeholder-tol-<?php echo $rowIdTol; ?>"
                                                            onclick="document.getElementById('upload-tol-<?php echo $rowIdTol; ?>').click()">
                                                            <p><?php echo $imageNameTol; ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- File input -->
                                                    <input type="file" id="upload-tol-<?php echo $rowIdTol; ?>"
                                                        name="buktitol[]" disabled
                                                        onchange="handleFileSelect(event, 'icon-tol-<?php echo $rowIdTol; ?>', 'preview-tol-<?php echo $rowIdTol; ?>', 'placeholder-tol-<?php echo $rowIdTol; ?>', 'download-tol-<?php echo $rowIdTol; ?>', 'reset-tol-<?php echo $rowIdTol; ?>')" />

                                                    <!-- Download button -->
                                                    <a class="download-button-r" id="download-tol-<?php echo $rowIdTol; ?>"
                                                        href="<?php echo $imageUrlTol; ?>" download>
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>


                                                </div>
                                            </div>

                                            <!-- Anchor link to open file -->
                                            <!-- <a href="<?php echo $imageUrlTol; ?>" target="_blank"><?php echo htmlspecialchars($imageNameTol); ?></a> -->

                                            <!-- Modal Structure -->
                                            <div id="imageModalTol" class="modal">
                                                <span class="close" onclick="closeModal()">&times;</span>
                                                <img class="modal-content" id="modalImageTol">
                                                <div id="modalCaptionTol"></div>
                                            </div>
                                        </td>
                                        <td>

                                            <?php if ($tolRow['status'] === 'success' || $tolRow['status'] === 'cost control'): ?>
                                                <span class="badge badge-success">SUCCESS</span>
                                            <?php elseif ($tolRow['status'] === 'revisi'): ?>
                                                <span class="badge badge-warning">REVISI</span>
                                            <?php else: ?>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_lov" value="<?php echo $tolRow['id']; ?>">


                                                </form>
                                                <form method="POST" action="">
                                                    <input type="hidden" name="id_lov" value="<?php echo $tolRow['id']; ?>">

                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="2">Jumlah Total</td>
                                <td colspan="3"><span id="grandTotalTol" name="jumlahtotalTol"
                                        class="currency">Rp.<?php echo htmlspecialchars(number_format($data['jumlah_totalTol'], 2), ENT_QUOTES, 'UTF-8'); ?>
                                    </span>
                                    <input type="hidden" id="hiddenTotalTol" name="jumlahtotalTol"
                                        value="<?php echo htmlspecialchars($data['jumlah_totalTol']); ?>">
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

                <div class="category-group">
                    <span class="title">Catatan Revisi :</span>
                    <div>
                        <textarea name="catatan" class="text-area" disabled> <?php echo $data['catatan'] ?> </textarea>
                    </div>

                    <div class="form-row" style="justify-content: center;">

                        <div class="btn-s">
                            <a href="frevisi.php?id=<?php echo $data['id']; ?>">
                                <button type="button" class="btn-style">
                                    <i>REVISI</i>
                                </button>
                            </a>

                            <button class="btn-style delete-approval-btn" type="button"
                                data-id="<?php echo $data['id']; ?>">
                                <i class='fas fa-trash'></i>
                            </button>
                        </div>
                    </div>



        </form>
    </div>
    </div>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        <div id="modalCaption"></div>
    </div>

    <script src="assets/js/fview.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $('.delete-approval-btn').click(function () {
            var approvalId = $(this).data('id');
            var row = $(this).closest('tr');

            console.log("Approval ID:", approvalId); // Debug untuk memastikan ID ada
            if (approvalId === undefined || approvalId === null) {
                console.error("Approval ID is not set!");
                return; // Hentikan proses jika ID tidak valid
            }

            // SweetAlert konfirmasi
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request AJAX untuk menghapus data
                    $.ajax({
                        type: 'POST',
                        url: 'delete_approval.php', // Ganti dengan file PHP yang sesuai
                        data: {
                            id: approvalId
                        },
                        success: function (response) {
                            console.log("Server Response:", response); // Debug respons dari server
                            if (response.trim() === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Approval and related data deleted successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        // Redirect ke halaman historyU.php setelah konfirmasi
                                        window.location.href = 'historyU.php';
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Failed to delete approval and related data.',
                                    'error'
                                );
                            }
                        },
                        error: function () {
                            Swal.fire(
                                'Error!',
                                'Error in AJAX request.',
                                'error'
                            );
                        }
                    });
                }
            });
        });


    </script>
</body>

</html>