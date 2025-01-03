<?php include "includes/PROfrevisi.php" ?>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
    <link rel="stylesheet" href="assets/select/dist/css/select2.css">
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
        <form id="myForm" method="POST" enctype="multipart/form-data">
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
                        <input type="text" name="invoice" value="<?php echo $data['invoice'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <div style="display: flex; gap: 10px;">
                            <!-- Menggunakan flex untuk mengatur jarak antara input -->
                            <input type="text" id="startPeriode" name="periodeAwal" placeholder="Start Date"
                                value="<?php echo $data['periodeAwal'] ?>">
                            <input type="text" id="endPeriode" name="periodeAkhir" placeholder="End Date"
                                value="<?php echo $data['periodeAkhir'] ?>">

                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggalPengajuan">Tanggal Pengajuan:</label>
                        <input type="text" id="tanggalPengajuan" name="tanggalPengajuan"
                            value="<?php echo $data['tanggalPengajuan'] ?>" placeholder="Tanggal Pengajuan">
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
                                    <!-- Placeholder if image is not present -->
                                    <div class="upload-placeholder" id="placeholder-kronologi"
                                        onclick="document.getElementById('upload-kronologi').click()">
                                        <p><?php echo $imageName; ?></p>
                                    </div>
                                </div>
                                <!-- File input -->
                                <input type="file" id="upload-kronologi" name="kronologi" multiple
                                    onchange="handleFileSelect(event, 'icon-kronologi', 'preview-kronologi', 'placeholder-kronologi', 'download-kronologi', 'reset-kronologi')" />

                                <a class="download-button-r" id="download-kronologi" href="<?php echo $imageUrl; ?>"
                                    download><i class="fa-solid fa-download"></i></a>
                                <button style="width: 30px;" type="button" class="reset-button-r" id="reset-kronologi"
                                    onclick="resetFileInput('upload-kronologi', 'icon-kronologi', 'preview-kronologi', 'placeholder-kronologi', 'download-kronologi')"><i
                                        class="fa-solid fa-arrows-rotate"></i></button>
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
                                        <i class="fa-regular fa-file upload-icon" id="icon-dispo"
                                            style="<?php echo !empty($data['dispo']) ? 'display: none;' : ''; ?>"></i>
                                        <img id="preview-dispo" class="image-preview" src="<?php echo $imageUrl; ?>"
                                            onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                            style="<?php echo !empty($data['dispo']) ? 'display: block;' : 'display: none;'; ?>" />
                                    </div>
                                    <div class="upload-placeholder" id="placeholder-dispo"
                                        onclick="document.getElementById('upload-dispo').click()">
                                        <p><?php echo $imageName; ?></p>
                                    </div>
                                </div>
                                <input type="file" id="upload-dispo" name="dispo" multiple
                                    onchange="handleFileSelect(event, 'icon-dispo', 'preview-dispo', 'placeholder-dispo', 'download-dispo', 'reset-dispo')" />
                                <a class="download-button-r" id="download-dispo" href="<?php echo $imageUrl; ?>"
                                    download><i class="fa-solid fa-download"></i></a>
                                <button style="width: 30px;" type="button" class="reset-button-r" id="reset-dispo"
                                    onclick="resetFileInput('upload-dispo', 'icon-dispo', 'preview-dispo', 'placeholder-dispo', 'download-dispo')"><i
                                        class="fa-solid fa-arrows-rotate"></i></button>
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
                                        <i class="fa-regular fa-file upload-icon" id="icon-buktipesan"
                                            style="<?php echo !empty($data['buktipesan']) ? 'display: none;' : ''; ?>"></i>
                                        <img id="preview-buktipesan" class="image-preview"
                                            src="<?php echo $imageUrl; ?>"
                                            onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                            style="<?php echo !empty($data['buktipesan']) ? 'display: block;' : 'display: none;'; ?>" />
                                    </div>
                                    <div class="upload-placeholder" id="placeholder-buktipesan"
                                        onclick="document.getElementById('upload-buktipesan').click()">
                                        <p><?php echo $imageName; ?></p>
                                    </div>
                                </div>
                                <input type="file" id="upload-buktipesan" name="buktipesan" multiple
                                    onchange="handleFileSelect(event, 'icon-buktipesan', 'preview-buktipesan', 'placeholder-buktipesan', 'download-buktipesan', 'reset-buktipesan')" />
                                <a class="download-button-r" id="download-buktipesan" href="<?php echo $imageUrl; ?>"
                                    download><i class="fa-solid fa-download"></i></a>
                                <button style="width: 30px;" type="button" class="reset-button-r" id="reset-buktipesan"
                                    onclick="resetFileInput('upload-buktipesan', 'icon-buktipesan', 'preview-buktipesan', 'placeholder-buktipesan', 'download-buktipesan'); document.getElementsByName('upload-buktipesan_reset')[0].value = '1';">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                </button>
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
                                    <th>Jumlah</th>
                                    <th>Bukti</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($lovResult)): ?>

                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <!-- Input untuk id_lov -->
                                        <input type="hidden" name="id_lov_update[]" value="<?php echo $row['id']; ?>">
                                        <input type="hidden" name="id_lov_insert[]" value="<?php echo $row['id']; ?>">

                                        <!-- Input untuk id_ket -->
                                        <input type="hidden" name="id_ket[]" value="<?php echo $row['id_ket']; ?>">
                                        <td>
                                        <select class="select-box" name="keterangan[]"
                                                id="keterangan-<?php echo $row['id']; ?>"
                                                onchange="updateBuktiType(this, <?php echo $row['id']; ?>)">
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
                                                placeholder="Deskripsi"><?php echo $row['deskripsi'] ?></textarea>
                                        </td>

                                        <td>
                                            <div class="input-container">
                                                <!-- Textbox jumlah 1 for id_ket other than 1, 9, 14, 13 -->
                                                <input type="number" name="jumlah[]" id="jumlah1-<?php echo $row['id']; ?>"
                                                    class="form-control jumlah-1" value="<?php echo $row['jumlah']; ?>">
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
                                                            <img id="preview-bukti-<?php echo $rowId; ?>"
                                                                class="image-preview" src="<?php echo $imageUrl; ?>"
                                                                alt="Preview"
                                                                onclick="openModal('<?php echo $imageUrl; ?>', '<?php echo $imageName; ?>')"
                                                                style="<?php echo !empty($row['bukti']) ? 'display: block;' : 'display: none;'; ?>" />

                                                        </div>

                                                        <!-- Placeholder if no file is uploaded -->
                                                        <div class="upload-placeholder"
                                                            id="placeholder-bukti-<?php echo $rowId; ?>"
                                                            onclick="document.getElementById('upload-bukti-<?php echo $rowId; ?>').click()">
                                                            <p><?php echo $imageName; ?></p>
                                                        </div>
                                                    </div>

                                                    <!-- File input -->
                                                    <input type="file" id="upload-bukti-<?php echo $rowId; ?>"
                                                        name="bukti[]"
                                                        onchange="handleFileSelect(event, 'icon-bukti-<?php echo $rowId; ?>', 'preview-bukti-<?php echo $rowId; ?>', 'placeholder-bukti-<?php echo $rowId; ?>', 'download-bukti-<?php echo $rowId; ?>', 'reset-bukti-<?php echo $rowId; ?>')">
                                                    <a class="download-button-r" id="download-bukti-<?php echo $rowId; ?>"
                                                        href="<?php echo $imageUrl; ?>" download><i
                                                            class="fa-solid fa-download"></i></a>
                                                    <button style="width: 30px;" type="button" class="reset-button-r"
                                                        id="reset-bukti-<?php echo $rowId; ?>"
                                                        onclick="resetFileInput('upload-bukti-<?php echo $rowId; ?>', 'icon-bukti-<?php echo $rowId; ?>', 'preview-bukti-<?php echo $rowId; ?>', 'placeholder-bukti-<?php echo $rowId; ?>', 'download-bukti-<?php echo $rowId; ?>')">
                                                        <i class="fa-solid fa-arrows-rotate"></i>
                                                    </button>
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
                                            <button type="button" class="btn btn-danger btn-sm delete-lov-btn"
                                                data-id="<?php echo $row['id']; ?>">
                                                <i class="fa-solid fa-trash"></i>

                                        </td>



                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="3">Jumlah Total</td>
                                    <td colspan="1">
                                        <span id="grandTotal" name="jumlahtotal_new" class="currency">Rp.
                                            <?php echo htmlspecialchars(number_format($data['jumlahtotal'], 2), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <input type="hidden" id="hiddenTotal" name="jumlahtotal_new"
                                            value="<?php echo htmlspecialchars($data['jumlahtotal']); ?>">
                                    </td>
                                    <td colspan="2">
                                        <div class="total-words">
                                            <input type="hidden" id="hiddenTerbilang" name="terbilang_new"
                                                value="<?php echo htmlspecialchars($data['terbilang']); ?>">
                                            <span id="grandTotalWords" name="terbilang_new"
                                                class="currency"><?php echo $data['terbilang'] ?></span>
                                        </div>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="btn-group">
                            <button type="button" class="btnt" onclick="addRow(); addth();"><i
                                    class="fas fa-plus"></i></button>
                        </div>
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
                                    <th>Action</th>
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
                                                    placeholder="Deskripsi"><?php echo $buktiRow['deskripsi'] ?></textarea>
                                            </td>
                                            <td>
                                                <div class="input-container">
                                                    <span class="currency-label">Rp.</span>
                                                    <input type="number" name="biayabukti[]" class="form-control"
                                                        oninput="updateRowTotalbukti(this)" step="any" placeholder="Biaya"
                                                        value="<?php echo $buktiRow['biaya'] ?>">
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
                                                            <div class="upload-placeholder"
                                                                id="placeholder-bukti-detail-<?php echo $rowId; ?>"
                                                                onclick="document.getElementById('upload-bukti-detail-<?php echo $rowId; ?>').click()">
                                                                <p><?php echo $imageName; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- File input -->
                                                        <div class="upload-wrapper">
                                                            <input type="file" id="upload-bukti-detail-<?php echo $rowId; ?>"
                                                                name="bukti_detail[]"
                                                                onchange="handleFileSelect(event, 'icon-bukti-detail-<?php echo $rowId; ?>', 'preview-bukti-detail-<?php echo $rowId; ?>', 'placeholder-bukti-detail-<?php echo $rowId; ?>', 'download-bukti-detail-<?php echo $rowId; ?>', 'reset-bukti-detail-<?php echo $rowId; ?>')" />
                                                        </div>


                                                        <!-- Download button -->
                                                        <a class="download-button-r"
                                                            id="download-bukti-detail-<?php echo $rowId; ?>"
                                                            href="<?php echo $imageUrl; ?>" download>
                                                            <i class="fa-solid fa-download"></i>
                                                        </a>

                                                        <!-- Reset button -->
                                                        <button style="width: 30px;" type="button" class="reset-button-r"
                                                            id="reset-bukti-detail-<?php echo $rowId; ?>"
                                                            onclick="resetFileInput('upload-bukti-detail-<?php echo $rowId; ?>', 'icon-bukti-detail-<?php echo $rowId; ?>', 'preview-bukti-detail-<?php echo $rowId; ?>', 'placeholder-bukti-detail-<?php echo $rowId; ?>', 'download-bukti-detail-<?php echo $rowId; ?>')">
                                                            <i class="fa-solid fa-arrows-rotate"></i>
                                                        </button>
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
                                                <button type="button" class="btn btn-danger btn-sm delete-bukti-btn"
                                                    data-id="<?php echo $buktiRow['id']; ?>">
                                                    <i class="fa-solid fa-trash"></i></button>
                                            </td>


                                        </tr>
                                    <?php endwhile; ?>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2">Jumlah Total</td>
                                    <td colspan="3"><span id="grandTotalbukti" class="currency">Rp.
                                            <?php echo htmlspecialchars(number_format($data['jumlah_totalBukti']), ENT_QUOTES, 'UTF-8'); ?>
                                        </span>

                                        <input type="text" id="hiddenTotalbukti" name="jumlahtotalbukti"
                                            value="<?php echo $data['jumlah_totalBukti']; ?>" readonly>
                                    </td>

                                </tr>
                            </tfoot>
                        </table>

                        <div class="btn-group">
                            <button type="button" class="btnt" onclick="addRowbukti()"><i
                                    class="fas fa-plus"></i></button>

                        </div>
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
                                    <th>Action</th>
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
                                                <select name="gerbangTol[]" style="width:200px" class="select-box">
                                                    <option value="#" disabled>Pilih Gerbang Tol</option>
                                                    <?php
                                                    $queryg = mysqli_query($config, "SELECT id, nama_gerbang, tarif FROM tol");
                                                    while ($datag = mysqli_fetch_array($queryg)) {
                                                        // Tentukan apakah option ini adalah nilai yang tersimpan di database
                                                        $selected = $datag['id'] == $tolRow['id_tol'] ? 'selected' : '';
                                                        echo "<option value=\"{$datag['id']}\" data-tarif=\"{$datag['tarif']}\" $selected>{$datag['nama_gerbang']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="input-container">
                                                    <span class="currency-label">Rp.</span>
                                                    <input type="number" name="tarif[]" class="form-control"
                                                        oninput="updateRowTotalTol(this)" step="any"
                                                        value="<?php echo $tolRow['biaya']; ?>">
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $imageNameTol1 = !empty($tolRow['file']) ? basename($tolRow['file']) : 'No files uploaded!';
                                                $imageUrlTol1 = !empty($tolRow['file']) ? 'assets/image/' . htmlspecialchars($tolRow['file'], ENT_QUOTES, 'UTF-8') : '#';
                                                $rowIdTol1 = $tolRow['id']; // Assuming 'id' is available in $tolRow
                                                ?>

                                                <div id="upload-wrapper-tol1-<?php echo $rowIdTol1; ?>" class="upload-wrapper"
                                                    style="width: 20rem;">
                                                    <div class="upload-section">
                                                        <div class="upload-content">
                                                            <div>
                                                                <!-- Icon for file if no file is present -->
                                                                <i class="fa-regular fa-file upload-icon"
                                                                    id="icon-tol1-<?php echo $rowIdTol1; ?>"
                                                                    style="<?php echo !empty($tolRow['file']) ? 'display: none;' : ''; ?>"></i>

                                                                <!-- Image preview if file is present -->
                                                                <img id="preview-tol1-<?php echo $rowIdTol1; ?>"
                                                                    class="image-preview" src="<?php echo $imageUrlTol1; ?>"
                                                                    alt="Preview"
                                                                    onclick="openModal('<?php echo $imageUrlTol1; ?>', '<?php echo $imageNameTol1; ?>')"
                                                                    style="<?php echo !empty($tolRow['file']) ? 'display: block;' : 'display: none;'; ?>" />
                                                            </div>

                                                            <!-- Placeholder if no file is uploaded -->
                                                            <div class="upload-placeholder"
                                                                id="placeholder-tol1-<?php echo $rowIdTol1; ?>"
                                                                onclick="document.getElementById('upload-tol1-<?php echo $rowIdTol1; ?>').click()">
                                                                <p><?php echo $imageNameTol1; ?></p>
                                                            </div>
                                                        </div>

                                                        <!-- File input -->
                                                        <input type="file" id="upload-tol1-<?php echo $rowIdTol1; ?>"
                                                            name="buktitol[]"
                                                            onchange="handleFileSelect(event, 'icon-tol1-<?php echo $rowIdTol1; ?>', 'preview-tol1-<?php echo $rowIdTol1; ?>', 'placeholder-tol1-<?php echo $rowIdTol1; ?>', 'download-tol1-<?php echo $rowIdTol1; ?>', 'reset-tol1-<?php echo $rowIdTol1; ?>')" />

                                                        <!-- Download button -->
                                                        <a class="download-button-r"
                                                            id="download-tol1-<?php echo $rowIdTol1; ?>"
                                                            href="<?php echo $imageUrlTol1; ?>" download>
                                                            <i class="fa-solid fa-download"></i>
                                                        </a>

                                                        <!-- Reset button -->
                                                        <button style="width: 30px;" type="button" class="reset-button-r"
                                                            id="reset-tol1-<?php echo $rowIdTol1; ?>"
                                                            onclick="resetFileInput('upload-tol1-<?php echo $rowIdTol1; ?>', 'icon-tol1-<?php echo $rowIdTol1; ?>', 'preview-tol1-<?php echo $rowIdTol1; ?>', 'placeholder-tol1-<?php echo $rowIdTol1; ?>', 'download-tol1-<?php echo $rowIdTol1; ?>')">
                                                            <i class="fa-solid fa-arrows-rotate"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Modal Structure -->
                                                <div id="imageModalTol1" class="modal">
                                                    <span class="close" onclick="closeModal()">&times;</span>
                                                    <img class="modal-content" id="modalImageTol1">
                                                    <div id="modalCaptionTol1"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-bukti-btn"
                                                    data-id="<?php echo $tolRow['id']; ?>">
                                                    <i class="fa-solid fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endwhile; ?>
                            </tbody>
                            <tfoot>
                                <tr class="total-row">
                                    <td colspan="2">Jumlah Total</td>
                                    <td colspan="3"><span id="grandTotalTol" name="jumlahtotalTol" class="currency">Rp.
                                            <?php echo htmlspecialchars(number_format($data['jumlah_totalTol'], 2), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <input type="hidden" id="hiddenTotalTol" name="jumlahtotalTol">
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="btn-group">
                            <button type="button" class="btnt" onclick="addRowTol()"><i
                                    class="fas fa-plus"></i></button>

                        </div>
                    </div>



                    <div class="btn-s" style="margin-left: 30%;">
                        <button type="submit" class="btn-style">Kirim</button>
                    </div>
        </form>
    </div>
    </div>




<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="assets/js/frevisi.js"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script> -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
   $(document).on("change", 'select[name="gerbangTol[]"]', function () {
    var selectedOption = this.options[this.selectedIndex];
    var biaya = selectedOption.dataset.tarif; // Ambil tarif dari data-tarif

    // Set nilai input terkait berdasarkan tarif yang dipilih
    if (biaya) {
        $(this).closest("tr").find('input[name="tarif[]"]').val(biaya);
    } else {
        $(this).closest("tr").find('input[name="tarif[]"]').val(0); // Set 0 jika tidak ada data-tarif
    }

    // Panggil fungsi untuk menghitung total
    calculateGrandTotalTol();
});
</script>

<script>
    
$(document).ready(function () {
  // Initialize Select2 on all select boxes
  $(".select-box").select2({
    width: "100%",
    placeholder: "Select an option",
    allowClear: true,
  });

  // Event listener for changes on gerbangTol select boxes
  $(document).on("change", 'select[name="gerbangTol_New[]"]', function () {
    var selectedOption = $(this).find("option:selected");
    var tarif = selectedOption.data("tarif");

    // Set the corresponding input value
    $(this).closest("tr").find('input[name="tarif_New[]"]').val(tarif);

    // Calculate the grand total whenever a tarif is updated
    calculateGrandTotalTol();
  });

  // Event listener for changes in tarif inputs
  $(document).on("input", 'input[name="tarif_New[]"]', function () {
    // Calculate the grand total whenever the input changes
    calculateGrandTotalTol();
  });
});
</script>

    <script>
        $('.delete-lov-btn').click(function () {
            var lovID = $(this).data('id');
            var row = $(this).closest('tr');

            console.log("Lov ID:", lovID); // Debug untuk memastikan ID ada
            if (lovID === undefined || lovID === null) {
                console.error("Lov ID is not set!");
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
                        url: 'delete_lov.php', // Ganti dengan file PHP yang sesuai
                        data: {
                            id: lovID
                        },
                        success: function (response) {
                            console.log("Server Response:", response); // Debug respons dari server
                            if (response.trim() === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Lov deleted successfully.',
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
                                    'Failed to delete Lov.',
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
    <script>
        $('.delete-bukti-btn').click(function () {
            var buktiID = $(this).data('id');
            var row = $(this).closest('tr');

            console.log("Bukti ID:", buktiID); // Debug untuk memastikan ID ada
            if (buktiID === undefined || buktiID === null) {
                console.error("Bukti ID is not set!");
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
                        url: 'delete_bukti.php', // Ganti dengan file PHP yang sesuai
                        data: {
                            id: buktiID
                        },
                        success: function (response) {
                            console.log("Server Response:", response); // Debug respons dari server
                            if (response.trim() === 'success') {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Bukti deleted successfully.',
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
                                    'Failed to delete Bukti.',
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

    <script>
        jQuery(document).ready(function () {
            jQuery('.select-box').select2({
                width: '100%',
                placeholder: "Select an option", // Placeholder untuk dropdown
                allowClear: true // Memungkinkan pengguna untuk mengosongkan pilihan
            });
        });



        const showLoading = function () {
            Swal.fire({
                title: 'Now loading',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                },
                timer: 2000
            }).then(() => {
                Swal.fire({
                    title: 'Finished!',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        };

        // Add an event listener for form submission
        document.getElementById("myForm").addEventListener("submit", function (event) {
            event.preventDefault(); // Prevent default form submission

            // Show the loading alert
            showLoading();

            // Submit the form after showing the alert
            setTimeout(() => {
                this.submit(); // Submit the form after the alert shows for 2 seconds
            }, 2000); // Matches the timer in showLoading()
        });


        jQuery(document).ready(function () {
            // Initialize flatpickr for the startDate and endDate fields
            jQuery("#startPeriode").flatpickr({
                dateFormat: "Y-m-d", // Specify the date format you need
                allowInput: true // Allow manual input if required
            });

            jQuery("#endPeriode").flatpickr({
                dateFormat: "Y-m-d", // Specify the date format you need
                allowInput: true // Allow manual input if required
            });

            jQuery("#tanggalPengajuan").flatpickr({
                dateFormat: "Y-m-d", // Specify the date format you need
                allowInput: true // Allow manual input if required
            });
        });


         // Function to initialize the button visibility on page load
    function initializeButtons() {
        // Loop through each select element and update the buttons based on the initial selection
        const selectElements = document.querySelectorAll('select[name="keterangan[]"]');
        selectElements.forEach(selectElement => {
            const rowId = selectElement.id.split('-')[1]; // Get rowId from the id of the select element
            updateBuktiType(selectElement, rowId); // Run the update function immediately
        });
    }

    // Call the function on page load
    window.onload = initializeButtons;

    </script>
</body>

</html>