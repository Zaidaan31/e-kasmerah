<?php include 'includes/PROfcoa.php'; ?>
<?php include "navbar.php" ?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir Parkir</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/form/form.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/select/dist/css/select2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body id="body">
    <div class="header">
        <a href="fviewsuccess.php?id=<?php echo $approval_id; ?>" class="back-button">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="title-form">INPUT COA <?php echo $data['no_invoice'] ?></h1>
    </div>
    <div class="card">
        <div class="card2">
            <input type="hidden" name="id_approval" value="<?php echo $data ? $data['id'] : ''; ?>">
            <form id="form" method="POST" enctype="multipart/form-data">

                <div class="table-wrapper" style="margin-top: 15px;">
                    <span class="title"> List Of Value :</span>
                    <div style="overflow-x: auto; margin-top: 10px;">
                        <table id="barangTable" style="min-width: 600px;">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">No</th>
                                    <th>Keterangan</th>
                                    <th>Deskripsi</th>
                                    <th >COA</th>
                                    <th>ketCOA</th>
            
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($lovResult)): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>

                                        <td>
                                            <input  type="text" name="keterangan[]"
                                                class="form-control" value="<?php echo $row['keterangan'] ?>" readonly>
                                        </td>

                                        <td>
                                            <input type="hidden" name="id[]" value="<?php echo $row['id']; ?>">
                                            <textarea style="height: 40px" name="deskripsi[]" class="deskripsi"
                                                placeholder="Deskripsi" disabled><?php echo $row['deskripsi']; ?></textarea>
                                        </td>

                                        <td>
                                            <select  class="select-box" name="COA[]">
                                                <option value="" selected>Select COA</option>
                                                <?php
                                                // Query untuk mendapatkan data COA dan KETCOA dari database
                                                $query1 = mysqli_query($config, "SELECT id, COA, KETCOA FROM coa ORDER BY id ASC");
                                                while ($data1 = mysqli_fetch_array($query1)) {
                                                    echo "<option value='{$data1['id']}' data-ketcoa='{$data1['KETCOA']}'> {$data1['COA']} </option>";
                                                }
                                                ?>
                                            </select>
                                        </td>

                                        <td>
                                            <select style="width: 20rem;" class="select-box" name="KETCOA[]">
                                                <option value="" selected>Select KETCOA</option>
                                                <?php
                                                // Query untuk mendapatkan data KETCOA dari database
                                                $query2 = mysqli_query($config, "SELECT id, COA, KETCOA FROM coa ORDER BY id ASC");
                                                while ($data2 = mysqli_fetch_array($query2)) {
                                                    echo "<option value='{$data2['id']}' data-coa='{$data2['COA']}'> {$data2['KETCOA']} </option>";
                                                }
                                                ?>
                                            </select>
                                        </td>
                                        
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="btn-s">
                    <button type="submit" class="btn-style">Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script src="assets/js/fcoa.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     // Event listener untuk perubahan select KETCOA
        //     $('.ketcoa-select').on('change', function() {
        //         // Ambil COA dari atribut data-coa pada opsi yang dipilih
        //         var selectedCoa = $(this).find('option:selected').data('coa');

        //         // Cari input COA yang sesuai di baris yang sama
        //         var correspondingCoaInput = $(this).closest('tr').find('.coa-input');

        //         // Isi input COA dengan nilai COA dari pilihan KETCOA
        //         correspondingCoaInput.val(selectedCoa);

        //         // Aktifkan input COA (kalau sebelumnya disabled)
        //         correspondingCoaInput.prop('disabled', false);
        //     });
        //     jQuery('.select-box').select2({
        //         width: '100%',
        //         placeholder: "Select an option", // Placeholder untuk dropdown
        //         allowClear: true // Memungkinkan pengguna untuk mengosongkan pilihan
        //     });
        // });

        // jQuery(document).ready(function() {

        // });


        // $(document).ready(function() {
        //     // Event listener untuk perubahan select KETCOA
        //     $('.coa-select').on('change', function() {
        //         // Ambil COA dari atribut data-coa pada opsi yang dipilih
        //         var selectedKetCoa = $(this).find('option:selected').data('ketcoa');

        //         // Cari input COA yang sesuai di baris yang sama
        //         var correspondingKetCoaInput = $(this).closest('tr').find('.ketcoa-input');

        //         // Isi input COA dengan nilai COA dari pilihan KETCOA
        //         correspondingKetCoaInput.val(selectedKetCoa);

        //         // Aktifkan input COA (kalau sebelumnya disabled)
        //         correspondingKetCoaInput.prop('disabled', false);
        //     });
        //     jQuery('.select-box').select2({
        //         width: '100%',
        //         placeholder: "Select an option", // Placeholder untuk dropdown
        //         allowClear: true // Memungkinkan pengguna untuk mengosongkan pilihan
        //     });
        // });

        // jQuery(document).ready(function() {

        // });


        $(document).ready(function () {
            // Saat dropdown COA berubah
            $('select[name="COA[]"]').on('change', function () {
                var selectedId = $(this).val();
                var ketcoaSelect = $(this).closest('tr').find('select[name="KETCOA[]"]');

                // Pilih opsi yang sesuai di dropdown KETCOA
                ketcoaSelect.val(selectedId).trigger('change.select2');
            });

            // Saat dropdown KETCOA berubah
            $('select[name="KETCOA[]"]').on('change', function () {
                var selectedId = $(this).val();
                var coaSelect = $(this).closest('tr').find('select[name="COA[]"]');

                // Pilih opsi yang sesuai di dropdown COA
                coaSelect.val(selectedId).trigger('change.select2');
            });

            // Inisialisasi select2 pada semua select-box
            $('.select-box').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });
        });
    </script>

</body>

</html>