<?php
session_start();
include 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'user') {
    header("Location: ../home.php");
    exit();
}

if (!isset($_SESSION['id'])) {
    echo "User ID is not set. Debugging Information:";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit();
}

function processFileUpload($file_input_name, $index, $upload_dir, $db_connection = null)
{
    if (
        isset($_FILES[$file_input_name]['name'][$index]) &&
        $_FILES[$file_input_name]['error'][$index] === UPLOAD_ERR_OK &&
        !empty($_FILES[$file_input_name]['name'][$index])
    ) {
        echo "Processing File: " . $_FILES[$file_input_name]['name'][$index] . "\n";
        $file_name = $_FILES[$file_input_name]['name'][$index];
        $file_tmp = $_FILES[$file_input_name]['tmp_name'][$index];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (in_array(strtolower($file_ext), $allowed_ext)) {
            $file_base_name = pathinfo($file_name, PATHINFO_FILENAME);

            // Inisialisasi default untuk $file_exists_count
            $file_exists_count = 0;

            // Validasi ke database untuk memastikan file belum ada
            if ($db_connection) {
                $stmt = $db_connection->prepare("SELECT COUNT(*) FROM bukti WHERE file = ?");
                $stmt->bind_param("s", $file_name);
                $stmt->execute();
                $stmt->bind_result($file_exists_count);
                $stmt->fetch();
                $stmt->close();

                if ($file_exists_count > 0) {
                    throw new Exception("File already exists in the database: $file_name");
                }
            }

            $upload_path = $upload_dir . $file_base_name . '.' . $file_ext;
            $counter = 1;

            while (file_exists($upload_path)) {
                $upload_path = $upload_dir . $file_base_name . '_' . $counter . '.' . $file_ext;
                $counter++;
            }

            if (move_uploaded_file($file_tmp, $upload_path)) {
                return $file_base_name . '.' . $file_ext;
            }
        } else {
            throw new Exception("File extension not allowed: $file_ext");
        }
    }
    return null;
}

$id_user = $_SESSION['id'];
$approval_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil departemen dari tabel users
$query = "SELECT dept FROM users WHERE id = ?";
$stmt = $config->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $departemen = $row['dept']; // Ambil nilai departemen
} else {
    echo "Departemen tidak ditemukan untuk id_user: $id_user";
    exit();
}

$stmt->close();

$approvalQuery = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept FROM approval
                        LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                        LEFT JOIN users ON approval.id_user = users.id
                    WHERE approval.id = ? AND approval.id_user = ?";
$stmt = $config->prepare($approvalQuery);
$stmt->bind_param("ii", $approval_id, $id_user);
$stmt->execute();
$approvalResult = $stmt->get_result();

if ($approvalResult->num_rows > 0) {
    $data = $approvalResult->fetch_assoc();
    $id_approval = $data['id'];

    // Query to get data from `lov` table based on `id_approval`
    $lovQuery = "SELECT lov.*,keterangan.id as ket_id, keterangan.nama as keterangan FROM lov LEFT JOIN keterangan on lov.id_ket = keterangan.id
        WHERE id_approval = ?";
    $stmt_lov = $config->prepare($lovQuery);
    $stmt_lov->bind_param("i", $id_approval);
    $stmt_lov->execute();
    $lovResult = $stmt_lov->get_result();
} else {
    echo "No approval data found.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // echo '<pre>';
    // print_r($_POST);
    // print_r($_FILES);
    // print_r($_POST['deskripsi_buktiNew'] ?? []);
    // print_r($_POST['biayabukti_New'] ?? []);
    // print_r($_POST['tarif_New'] ?? []);
    // print_r($_POST['gerbangTol_New'] ?? []);
    // echo '</pre>';

    // Retrieve existing data from POST or use existing data
    $id_approve = $_POST['id_approval'] ?? $data['id'];
    $invoice = $_POST['invoice'] ?? $data['invoice'];
    $periodeAwal = $_POST['periodeAwal'] ?? $data['periodeAwal'];
    $periodeAkhir = $_POST['periodeAkhir'] ?? $data['periodeAkhir'];
    $tanggalPengajuan = $_POST['tanggalPengajuan'] ?? $data['tanggalPengajuan'];
    $dbyrkpd = $_POST['dbyrkpd'] ?? $data['dbyrkpd'];
    $jumlahtotal = $_POST['jumlahtotal_new'] ?? $data['jumlahtotal_new'];
    $terbilang = $_POST['terbilang_new'] ?? $data['terbilang_new'];
    $jumlahBukti = isset($_POST['jumlahtotalbukti']) && $_POST['jumlahtotalbukti'] !== '' ? (float) $_POST['jumlahtotalbukti'] : null;
    $jumlahTol = isset($_POST['jumlahtotalTol']) && $_POST['jumlahtotalTol'] !== '' ? (float) $_POST['jumlahtotalTol'] : null;

    // Handle file uploads
    $upload_dir = 'assets/image/';

    // Array untuk menyimpan nama file
    $file_names = [
        'kronologi' => isset($_POST['upload-kronologi_reset']) ? null : (!empty($_FILES['kronologi']['name']) ? basename($_FILES['kronologi']['name']) : $data['kronologi']),
        'buktipesan' => isset($_POST['upload-buktipesan_reset']) ? null : (!empty($_FILES['buktipesan']['name']) ? basename($_FILES['buktipesan']['name']) : $data['buktipesan']),
        'dispo' => isset($_POST['upload-dispo_reset']) ? null : (!empty($_FILES['dispo']['name']) ? basename($_FILES['dispo']['name']) : $data['dispo']),
    ];

    // Proses setiap upload file
    foreach ($file_names as $key => $file_name) {
        // Jika reset dipanggil, kita sudah set $file_name ke null
        if ($file_name === null) {
            continue; // Lewati upload jika file di-reset
        }

        // Pastikan file di-upload dan tidak ada error
        if (!empty($_FILES[$key]['name'])) {
            if ($_FILES[$key]['error'] === UPLOAD_ERR_OK) {
                // Move uploaded file ke direktori yang diinginkan
                if (!move_uploaded_file($_FILES[$key]['tmp_name'], $upload_dir . $file_name)) {
                    echo "Error uploading file: " . $_FILES[$key]['name'];
                    exit();
                }
            } else {
                echo "Error with file upload for $key: " . $_FILES[$key]['error'];
                exit();
            }
        }
    }

    // Update the approval table
    date_default_timezone_set('Asia/Jakarta');
    $timestamp = date("Y-m-d H:i:s");  // Menghasilkan waktu dengan timezone Asia/Jakarta

    // Inisialisasi query dinamis
    $setClauses = [
        "date = ?",
        "invoice = ?",
        "periodeAwal = ?",
        "periodeAkhir = ?",
        "tanggalPengajuan = ?",
        "kronologi = ?",
        "buktipesan = ?",
        "dispo = ?",
        "jumlahtotal = ?",
        "terbilang = ?",
        "status = 'head section'",
        "revisi_by = NULL",
        "catatan = NULL"
    ];
    $params = [$timestamp, $invoice, $periodeAwal, $periodeAkhir, $tanggalPengajuan, $file_names['kronologi'], $file_names['buktipesan'], $file_names['dispo'], $jumlahtotal, $terbilang];
    $types = "ssssssssss";

    // Tambahkan jumlah_totalBukti jika tidak NULL
    if ($jumlahBukti !== null) {
        $setClauses[] = "jumlah_totalBukti = ?";
        $params[] = $jumlahBukti;
        $types .= "d";
    }

    // Tambahkan jumlah_totalTol jika tidak NULL
    if ($jumlahTol !== null) {
        $setClauses[] = "jumlah_totalTol = ?";
        $params[] = $jumlahTol;
        $types .= "d";
    }

    // Tambahkan kondisi WHERE
    $params[] = $id_approve;
    $types .= "i";

    // Bangun query akhir
    $sql_approval = "UPDATE approval SET " . implode(", ", $setClauses) . " WHERE id = ?";
    $stmt = $config->prepare($sql_approval);

    // Jalankan query
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Update berhasil.";
    } else {
        echo "Tidak ada perubahan data atau update gagal.";
    }

    if ($stmt->execute()) {
        // Update existing lov entries
        if (isset($_POST['keterangan']) && is_array($_POST['keterangan'])) {
            foreach ($_POST['keterangan'] as $index => $keterangan) {
                // Skip if 'keterangan' is empty
                if (empty($keterangan)) {
                    continue; // Lewati jika tidak ada keterangan
                }

                // Retrieve other form data
                $deskripsi = $_POST['deskripsi'][$index] ?? '';
                $jumlah = $_POST['jumlah'][$index] ?? 0.00;
                $id_lov = $_POST['id_lov_update'][$index] ?? null;

                // Initialize the variable for the file name
                $bukti = ''; // Set initial value to null
                // Check if the file has been reset
                if (isset($_POST['bukti_reset']) && $_POST['bukti_reset'] === '1') {
                    $bukti = null; // Set bukti to null if reset
                } else {
                    // Handle the file upload for 'bukti'
                    if (isset($_FILES['bukti']['name'][$index]) && !empty($_FILES['bukti']['name'][$index])) {
                        if ($_FILES['bukti']['error'][$index] === UPLOAD_ERR_OK) {
                            $bukti_filename = basename($_FILES['bukti']['name'][$index]);
                            $bukti_path = $upload_dir . $bukti_filename;

                            if (move_uploaded_file($_FILES['bukti']['tmp_name'][$index], $bukti_path)) {
                                $bukti = $bukti_filename;
                            } else {
                                echo "Error uploading file: " . $_FILES['bukti']['name'][$index];
                                exit();
                            }
                        } else {
                            echo "Error with file upload for bukti: " . $_FILES['bukti']['error'][$index];
                            exit();
                        }
                    } else {
                        // If no new file is uploaded and reset is not triggered, set bukti to null
                        $lovQuery = "SELECT bukti FROM lov WHERE id = ?";
                        $stmt_lov_get = $config->prepare($lovQuery);
                        $stmt_lov_get->bind_param("i", $id_lov);
                        $stmt_lov_get->execute();
                        $lovResult = $stmt_lov_get->get_result();
                        $lovData = $lovResult->fetch_assoc();
                        $bukti = $lovData['bukti'] ?? null; // Keep it null if no existing file is found
                    }
                }

                // Update existing record
                $stmt_lov_update = $config->prepare("UPDATE lov SET id_ket = ?, deskripsi = ?, jumlah = ?, bukti = ?, status ='pending' WHERE id = ?");
                $stmt_lov_update->bind_param("isisi", $keterangan, $deskripsi, $jumlah, $bukti, $id_lov);

                if (!$stmt_lov_update->execute()) {
                    echo "Error updating lov entry: " . $stmt_lov_update->error;
                    exit();
                }
            }
        }


        // Handle new rows
        if (isset($_POST['keterangan_new']) && is_array($_POST['keterangan_new'])) {
            foreach ($_POST['keterangan_new'] as $index => $keterangan) {
                // If 'keterangan' is empty (for 'Lainnya'), assign it as NULL
                if (empty($keterangan)) {
                    $keterangan = null;
                }

                $deskripsi = $_POST['deskripsi_new'][$index] ?? 0;
                $jumlah = $_POST['jumlah_new'][$index] ?? 0.00;

                // Handle the file upload for 'bukti'
                $bukti_filename = !empty($_FILES['bukti_new']['name'][$index]) ? basename($_FILES['bukti_new']['name'][$index]) : null;
                if (!is_null($bukti_filename)) {
                    $bukti_path = $upload_dir . $bukti_filename;
                    move_uploaded_file($_FILES['bukti_new']['tmp_name'][$index], $bukti_path);
                }

                // Prepare the SQL statement for inserting into the lov table
                $stmt_lov = $config->prepare("INSERT INTO lov (id_approval, id_user, id_ket, deskripsi, jumlah, bukti) VALUES (?, ?, ?, ?, ?, ?)");

                // Bind 'keterangan' as NULL if it's empty
                $stmt_lov->bind_param("iiisis", $id_approve, $id_user, $keterangan, $deskripsi, $jumlah, $bukti_filename);

                if (!$stmt_lov->execute()) {
                    echo "Error inserting into lov table: " . $stmt_lov->error;
                    exit();
                }
            }
        }


        // Proses Update Data Tabel Bukti
        $ids_bukti = $_POST['id_bukti'] ?? [];
        $deskripsi_bukti_arr = $_POST['deskripsi_bukti'] ?? [];
        $biaya_arr = $_POST['biayabukti'] ?? [];
        $tarif_arr = $_POST['tarif'] ?? [];
        $gerbang_tol_arr = $_POST['gerbangTol'] ?? [];
        $ids_ket = $_POST['id_ket'] ?? [];

        // Pastikan 'id_ket' adalah array
        if (!is_array($ids_ket)) {
            $ids_ket = array_fill(0, count($ids_bukti), $ids_ket);
        }

        // Proses Update Data
        foreach ($ids_bukti as $i => $id_bukti) {
            $deskripsi_bukti = htmlspecialchars(trim($deskripsi_bukti_arr[$i] ?? ''), ENT_QUOTES);
            $biaya = floatval($biaya_arr[$i] ?? 0);
            $tarif = floatval($tarif_arr[$i] ?? null);
            $id_tol = (!empty($gerbang_tol_arr[$i]) && $gerbang_tol_arr[$i] !== '#') ? htmlspecialchars($gerbang_tol_arr[$i], ENT_QUOTES) : null;
            $status_bukti = "head section"; // Status default atau sesuai logika Anda
            $id_ket = $ids_ket[$i] ?? null;

            $file_input_name = null;
            $bukti_file1 = null;

            // Tentukan input file berdasarkan kondisi
            if (
                isset($_FILES['buktitol']['name'][$i]) &&
                $_FILES['buktitol']['error'][$i] === UPLOAD_ERR_OK &&
                !empty($_FILES['buktitol']['name'][$i])
            ) {
                $file_input_name = 'buktitol';
            } elseif (
                isset($_FILES['bukti_detail']['name'][$i]) &&
                $_FILES['bukti_detail']['error'][$i] === UPLOAD_ERR_OK &&
                !empty($_FILES['bukti_detail']['name'][$i])
            ) {
                $file_input_name = 'bukti_detail';
            }

            // Proses Upload File jika ada
            if ($file_input_name) {
                try {
                    $bukti_file1 = processFileUpload($file_input_name, $i, $upload_dir);
                } catch (Exception $e) {
                    echo "Error uploading file for index $i ($file_input_name): " . $e->getMessage() . "<br>";
                    exit();
                }
            }

            // Jika tidak ada file baru, ambil file lama dari database
            if (!$bukti_file1 && $id_bukti) {
                $query_get_file = "SELECT file FROM bukti WHERE id = ?";
                $stmt_get_file = $config->prepare($query_get_file);
                $stmt_get_file->bind_param('i', $id_bukti);
                $stmt_get_file->execute();
                $result_get_file = $stmt_get_file->get_result();
                $row = $result_get_file->fetch_assoc();
                $bukti_file1 = $row['file'] ?? null;
            }

            // Update data ke database
            if ($id_bukti) {
                if (in_array($id_ket, [1, 9, 14])) {
                    $stmt = $config->prepare("UPDATE bukti SET deskripsi = ?, biaya = ?, file = ?, status = ? WHERE id = ?");
                    $stmt->bind_param("sissi", $deskripsi_bukti, $biaya, $bukti_file1, $status_bukti, $id_bukti);
                } elseif ($id_ket == 13) {
                    $stmt = $config->prepare("UPDATE bukti SET file = ?, id_tol = ?, biaya = ?, status = ? WHERE id = ?");
                    $stmt->bind_param("siisi", $bukti_file1, $id_tol, $tarif, $status_bukti, $id_bukti);
                }

                if (!$stmt->execute()) {
                    echo "Error updating bukti table (ID: $id_bukti): " . $stmt->error;
                    exit();
                }
            }
        }

        // Validasi awal untuk memastikan input berupa array
        $deskripsi_bukti_new_arr = isset($_POST['deskripsi_buktiNew']) && is_array($_POST['deskripsi_buktiNew']) ? $_POST['deskripsi_buktiNew'] : [];
        $biaya_new_arr = isset($_POST['biayabukti_New']) && is_array($_POST['biayabukti_New']) ? $_POST['biayabukti_New'] : [];
        $tarif_new_arr = isset($_POST['tarif_New']) && is_array($_POST['tarif_New']) ? $_POST['tarif_New'] : [];
        $gerbang_tol_new_arr = isset($_POST['gerbangTol_New']) && is_array($_POST['gerbangTol_New']) ? $_POST['gerbangTol_New'] : [];
        $id_lov_new = $_POST['id_lov_insert'][0] ?? null; // Mengambil satu ID LOV saja

        // Debug input
        // echo "<pre>";
        // print_r($deskripsi_bukti_new_arr);
        // print_r($biaya_new_arr);
        // print_r($tarif_new_arr);
        // print_r($gerbang_tol_new_arr);
        // echo "</pre>";
        $insert_data = [];
        // Loop untuk setiap data form
        $total_entries = max(count($deskripsi_bukti_new_arr), count($gerbang_tol_new_arr));
        for ($i = 0; $i < $total_entries; $i++) {
            // Untuk bukti biasa
            $deskripsi_new = trim($deskripsi_bukti_new_arr[$i] ?? '');

            // Untuk bukti tol, set deskripsi sebagai NULL
            if (!empty($id_tol_new)) {
                $deskripsi_new = null; // Deskripsi null untuk bukti tol
            }

            $biaya_new = floatval($biaya_new_arr[$i] ?? 0);
            $tarif_new = floatval($tarif_new_arr[$i] ?? 0);
            $id_tol_new = trim($gerbang_tol_new_arr[$i] ?? '');

            // Debugging: Tampilkan data yang sedang diproses
            // echo "Deskripsi: $deskripsi_new, Biaya: $biaya_new, Tarif: $tarif_new, Gerbang Tol: $id_tol_new\n";

            // Validasi data kosong (terutama untuk bukti biasa, id_tol_new bisa kosong)
            if (empty($deskripsi_new) && empty($id_tol_new)) {
                continue; // Lewati jika deskripsi kosong untuk bukti biasa dan id_tol kosong untuk bukti tol
            }

            // Pilih file input yang sesuai
            $file_input_name = !empty($id_tol_new) ? 'buktiTol_New' : 'bukti1';

            try {
                $uploaded_file = processFileUpload($file_input_name, $i, $upload_dir);
            } catch (Exception $e) {
                echo "Upload Error: " . $e->getMessage();
                continue; // Skip data jika upload gagal
            }

            // Hindari duplikasi dengan validasi ke database
            $check_stmt = $config->prepare(
                "SELECT COUNT(*) FROM bukti WHERE deskripsi = ? AND id_tol = ? AND id_lov = ?"
            );
            $check_stmt->bind_param("ssi", $deskripsi_new, $id_tol_new, $id_lov_new);
            $check_stmt->execute();
            $check_stmt->bind_result($exists);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($exists > 0) {
                continue; // Lewati jika data sudah ada
            }

            // Tentukan biaya yang digunakan (gunakan tarif untuk tol dan biaya untuk bukti biasa)
            $biaya_final = !empty($id_tol_new) ? $tarif_new : $biaya_new;

            // Tambahkan data ke array insert_data
            $insert_data[] = [
                'file' => $uploaded_file,
                'deskripsi' => $deskripsi_new,  // Akan null untuk bukti tol
                'id_tol' => $id_tol_new,
                'biaya' => $biaya_final,
                'id_lov' => $id_lov_new,
                'status' => 'head section',
            ];
            // echo "Data berhasil ditambahkan ke insert_data.\n";
        }


        // Debug akhir
        // echo "<pre>";
        // print_r($insert_data);
        // echo "</pre>";

        // Insert data ke tabel bukti
        if (!empty($insert_data)) {
            try {
                $config->begin_transaction();

                $stmt = $config->prepare(
                    "INSERT INTO bukti (file, deskripsi, id_tol, biaya, id_lov, status) VALUES (?, ?, ?, ?, ?, ?)"
                );

                foreach ($insert_data as $data) {
                    $stmt->bind_param(
                        "ssidis",
                        $data['file'],
                        $data['deskripsi'],
                        $data['id_tol'],
                        $data['biaya'],
                        $data['id_lov'],
                        $data['status']
                    );
                    $stmt->execute();
                }

                $stmt->close();
                $config->commit();
                echo "Data berhasil disimpan!";
            } catch (Exception $e) {
                $config->rollback();
                echo "Error saat menyimpan data: " . $e->getMessage();
            }
        } else {
            echo "Tidak ada data valid untuk disimpan.";
        }



        // Redirect to a success page after successful update
        header("Location: historyU.php");
        exit();
    } else {
        echo "Error updating approval table: " . $stmt->error;
        exit();
    }
}
