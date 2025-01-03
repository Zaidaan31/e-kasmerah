<?php
// session_start();
?>
<?php include "includes/PROform.php" ?>
<?php include "navbar.php" ?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/form/form.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="assets/select/dist/css/select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">


    <style>
        #uploadForm {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            /* max-width: 500px; */
            margin: 20px 100px;
        }

        .upload-box {
            border: 2px dashed black;
            padding: 20px;
            width: 100%;
            /* max-width: 500px; */
            border-radius: 10px;
            text-align: center;
            background-color: #f9f9f9;
        }

        .file-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
        }

        .file-label:hover {
            background-color: #0056b3;
        }

        #images {
            display: none;
            /* Sembunyikan input file */
        }

        .file-count {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Styling untuk kotak preview gambar */
        #preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
            justify-content: center;
        }

        .preview-box {
            width: 80px;
            height: 80px;
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .preview-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="historyU.php" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">Form</h1>
    </div>
    <div class="card">
        <form id="myForm" action="includes/PROform.php" method="post" enctype="multipart/form-data">
            <div class="card2">
                <div class="form-row">
                    <div class="form-group">
                        <label for="departemen">Departemen:</label>
                        <input type="text" name="departemen" placeholder="departemen"
                            value="<?php echo htmlspecialchars($departemen); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="vendor">Nama Vendor:</label>
                        <div class="form-group">
                            <select class="select-box" name="dbyrkpd" required>
                                <option value="" selected>Select</option>
                                <?php
                                $query = mysqli_query($config, "SELECT id, nama FROM vendor ORDER BY nama ASC");
                                while ($data = mysqli_fetch_array($query)) {
                                    echo "<option value=$data[id]> $data[nama] </option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="invoice">Invoice:</label>
                        <input type="text" name="invoice" placeholder="Invoice">
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <div style="display: flex; gap: 10px;">
                            <!-- Menggunakan flex untuk mengatur jarak antara input -->
                            <input type="text" id="startPeriode" name="startPeriode" placeholder="Start Date" required>
                            <input type="text" id="endPeriode" name="endPeriode" placeholder="End Date" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tanggalPengajuan">Tanggal Pengajuan:</label>
                        <input type="text" id="tanggalPengajuan" name="tanggalPengajuan" placeholder="Tanggal Pengajuan"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="kronologi">Kronologi:</label>
                        <div class="upload-wrapper">
                            <div class="upload-section">
                                <div class="upload-content">
                                    <div>
                                        <i class="fa-regular fa-file upload-icon" id="icon-kronologi"></i>
                                        <img id="preview-kronologi" class="image-preview" src="#" alt="Preview"
                                            onclick="openModal('preview-kronologi')" />
                                    </div>
                                    <div class="upload-placeholder" id="placeholder-kronologi"
                                        onclick="document.getElementById('upload-kronologi').click()">
                                        <p>Upload !</p>
                                    </div>
                                </div>
                                <input type="file" id="upload-kronologi" name="kronologi" multiple
                                    onchange="handleFileSelect(event, 'icon-kronologi', 'preview-kronologi', 'placeholder-kronologi', 'download-kronologi', 'reset-kronologi')" />
                                <a class="download-button" id="download-kronologi" href="#" download><i
                                        class="fa-solid fa-download"></i></a>
                                <button style="width: 30px;" type="button" class="reset-button" id="reset-kronologi"
                                    onclick="resetUpload('icon-kronologi', 'preview-kronologi', 'placeholder-kronologi', 'download-kronologi', 'upload-kronologi')"><i
                                        class="fa-solid fa-arrows-rotate"></i></button>
                            </div>

                            <small class="catatan">*Catatan : Hanya format yang didukung*</small>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="dispo">Dispo:</label>
                        <div class="upload-wrapper">
                            <div class="upload-section">
                                <div class="upload-content">
                                    <div>
                                        <i class=" fa-regular fa-file upload-icon" id="icon-dispo"></i>
                                        <img id="preview-dispo" class="image-preview" src="#" alt="Preview"
                                            onclick="openModal('preview-dispo')" />
                                    </div>
                                    <div class="upload-placeholder" id="placeholder-dispo"
                                        onclick="document.getElementById('upload-dispo').click()">
                                        <p>Upload !</p>
                                    </div>
                                </div>
                                <input type="file" id="upload-dispo" name="dispo" multiple
                                    onchange="handleFileSelect(event, 'icon-dispo', 'preview-dispo', 'placeholder-dispo', 'download-dispo', 'reset-dispo')">
                                <a class="download-button" id="download-dispo" href="#" download><i
                                        class="fa-solid fa-download"></i></a>
                                <button style="width: 30px;" type="button" class="reset-button" id="reset-dispo"
                                    onclick="resetUpload('icon-dispo', 'preview-dispo', 'placeholder-dispo', 'download-dispo', 'upload-dispo')"><i
                                        class="fa-solid fa-arrows-rotate"></i></button>
                            </div>

                            <small class="catatan">*Catatan : Hanya format yang didukung*</small>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="buktiWA">Bukti WA / Email:</label>
                        <div class="upload-wrapper">
                            <div class="upload-section">
                                <div class="upload-content">
                                    <div>
                                        <i class="fa-regular fa-file upload-icon" id="icon-bukti-wa"></i>
                                        <img id="preview-bukti-wa" class="image-preview" src="#" alt="Preview"
                                            onclick="openModal('preview-bukti-wa')" />
                                    </div>
                                    <div class="upload-placeholder" id="placeholder-bukti-wa"
                                        onclick="document.getElementById('upload-bukti-wa').click()">
                                        <p>Upload !</p>
                                    </div>
                                </div>
                                <input type="file" id="upload-bukti-wa" name="buktipesan" multiple
                                    onchange="handleFileSelect(event, 'icon-bukti-wa', 'preview-bukti-wa', 'placeholder-bukti-wa', 'download-bukti-wa', 'reset-bukti-wa')" />
                                <a class="download-button" id="download-bukti-wa" href="#" download><i
                                        class="fa-solid fa-download"></i></i></a>
                                <button style="width: 30px;" type="button" class="reset-button" id="reset-bukti-wa"
                                    onclick="resetUpload('icon-bukti-wa', 'preview-bukti-wa', 'placeholder-bukti-wa', 'download-bukti-wa', 'upload-bukti-wa')"><i
                                        class="fa-solid fa-arrows-rotate"></i></button>
                            </div>

                        </div>
                        <small class="catatan">*Catatan : Hanya format yang didukung*</small>

                    </div>
                </div>


                <div class="table-wrapper" style="margin-top: 15px;">
                    <span class="title">List Of Value :</span>
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
                        </tbody>
                        <tfoot>
                            <tr class="total-row">
                                <td colspan="3">Jumlah Total</td>
                                <td colspan="1"><span id="grandTotal" name="jumlahtotal" class="currency">Rp. </span>
                                    <input type="hidden" id="hiddenTotal" name="jumlahtotal">
                                </td>
                                <td colspan="2">
                                    <div class="total-words">
                                        <span id="grandTotalWords" class="currency" name="terbilang">(Nol)</span>
                                        <input type="hidden" id="hiddenTerbilang" name="terbilang" value="">
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>

                <div class="btn-group">
                    <button type="button" class="btnt" onclick="addRow()"><i class="fas fa-plus"></i></button>
                    <button type="button" class="btnt btnt-reset" onclick="resetTable()"><i
                            class="fas fa-sync-alt"></i></button>
                </div>

                <div class="table-wrapper" id="buktiTable" style="margin-top: 15px; display: none;">
                    <div class="upload-box">
                        <!-- Label kustom untuk input file -->
                        <label class="file-label" for="images">Upload Multiple Images Bukti</label>
                        <input type="file" id="images" name="imagesbukti[]" multiple accept="image/*"
                            onchange="previewImages()">
                        <div class="file-count" id="fileCount">Belum ada file yang dipilih</div>
                        <div id="preview"></div>
                    </div>
                </div>

                <!-- <div class="table-wrapper" id="tolTable" style="margin-top: 15px; display: none;">
                  
                </div> -->


                <small style="font-size: 11px; color: #666;">
                    <div style="background-color: #e9ecef; padding: 10px; border-radius: 8px; border: 1px solid #ccc;">
                        <strong style="display: block; margin-bottom: 5px;">Catatan Pengisian List Of Value:</strong>
                        <ul style="margin: 0; padding-left: 20px; list-style-type: disc;">
                            <li><strong>Keterangan:</strong> Pilih kategori biaya pengajuan dari yang sudah di sediakan.
                                Jika jenis biaya tidak tersedia dalam daftar seperti PPN, PPh, atau biaya lain, pilih
                                opsi "Lainnya" dan jelaskan rincian tersebut di kolom deskripsi.</li>
                            <li><strong>Deskripsi:</strong> Berikan deskripsi singkat mengenai biaya yang diajukan. Jika
                                Anda memilih "Lainnya" pada jenis biaya, pastikan untuk menyebutkan jenis biaya spesifik
                                seperti PPN, PPh, atau biaya tambahan lainnya.</li>
                            <li><strong>Jumlah:</strong> Masukkan jumlah biaya yang sesuai. Jika ada potongan atau
                                pengurangan, harap gunakan tanda minus (-) sebelum nilai nominal (misal: -5000 untuk
                                potongan sebesar 5000). Pastikan angka yang dimasukkan menggunakan format yang benar
                                tanpa tanda baca kecuali titik desimal (misal: 10000.00).</li>
                            <li><strong>Bukti:</strong> Unggah file yang berfungsi sebagai bukti transaksi atau dokumen
                                pendukung. Jenis file yang diperbolehkan meliputi JPG, PNG, PDF, DOC, dan EXCEL.
                                Pastikan file yang diunggah jelas dan sesuai dengan format yang telah ditentukan.</li>
                        </ul>
                    </div>
                </small>
                <div class="btn-s">
                    <button id="submitTolForm" type="button" class="btn-style" onclick="confirmSubmit()">Submit</button>
                </div>
            </div>
        </form>
    </div>
</body>


<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="modalCaption"></div>
</div>








<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="assets/js/form.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    function confirmSubmit() {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, submit it!"
        }).then((result) => {
            if (result.isConfirmed) {
                // If confirmed, submit the form
                document.getElementById('myForm').submit();
                
                // Show success alert after submission
                Swal.fire({
                    title: "Submitted!",
                    text: "Your form has been submitted successfully.",
                    icon: "success"
                });
            }
        });
    }
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
</script>
<script>
    function previewImages() {
        const preview = document.getElementById('preview');
        const fileCountText = document.getElementById('fileCount');
        const files = document.getElementById('images').files;

        // Tampilkan jumlah file yang dipilih
        fileCountText.textContent = files.length + " file dipilih";

        // Reset preview
        preview.innerHTML = '';

        // Tampilkan setiap gambar yang dipilih
        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const previewBox = document.createElement('div');
                previewBox.classList.add('preview-box');

                const img = document.createElement('img');
                img.src = e.target.result;

                previewBox.appendChild(img);
                preview.appendChild(previewBox);
            }
            reader.readAsDataURL(file);
        });
    }
</script>
</body>

</html>