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
    die("User ID tidak ditemukan.");
}

$user_id = $_SESSION['id'];

// Direktori untuk menyimpan file upload
$upload_dir = '../assets/image/';

function processFileUpload($file_input_name, $index, $upload_dir, $db_connection = null)
{
    if (
        isset($_FILES[$file_input_name]['name'][$index]) &&
        $_FILES[$file_input_name]['error'][$index] === UPLOAD_ERR_OK &&
        !empty($_FILES[$file_input_name]['name'][$index])
    ) {
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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    // echo '<pre>';
    // var_dump($_FILES);
    // echo '</pre>';

    // Ambil data input
    $approval_id = $_POST['id_approval'];
    $jumlahtotal = (float) $_POST['jumlahtotal_new']; // Selalu terisi
    $terbilang = $_POST['terbilang_new'];            // Selalu terisi
    $jumlahBukti = isset($_POST['jumlahtotalbukti']) && $_POST['jumlahtotalbukti'] !== '' ? (float) $_POST['jumlahtotalbukti'] : null;
    $jumlahTol = isset($_POST['jumlahtotalTol']) && $_POST['jumlahtotalTol'] !== '' ? (float) $_POST['jumlahtotalTol'] : null;

    // Inisialisasi query dinamis
    $setClauses = [];
    $params = [];
    $types = "";

    // Kolom yang pasti ada
    $setClauses[] = "jumlahtotal = ?";
    $params[] = $jumlahtotal;
    $types .= "d";

    $setClauses[] = "terbilang = ?";
    $params[] = $terbilang;
    $types .= "s";

    // Tambahkan kolom jika tidak NULL
    if ($jumlahBukti !== null) {
        $setClauses[] = "jumlah_totalBukti = ?";
        $params[] = $jumlahBukti;
        $types .= "d";
    }

    if ($jumlahTol !== null) {
        $setClauses[] = "jumlah_totalTol = ?";
        $params[] = $jumlahTol;
        $types .= "d";
    }

    // Kolom wajib
    $setClauses[] = "status = ?";
    $params[] = "head section";
    $types .= "s";

    // Tambahkan kondisi WHERE
    $params[] = $approval_id;
    $types .= "i";

    // Bangun query akhir
    $sql_approval = "UPDATE approval SET " . implode(", ", $setClauses) . " WHERE id = ?";
    $stmt = $config->prepare($sql_approval);

    // Jalankan query
    $stmt->bind_param($types, ...$params);
    $stmt->execute();


    // Data LOV
    $ids_lov = $_POST['id_lov_update'] ?? [];
    $ids_ket = $_POST['id_ket'] ?? [];
    $deskripsi_arr = $_POST['deskripsi'] ?? [];
    $jumlah_lov_arr = $_POST['jumlah'] ?? [];

    // Flag untuk menentukan apakah sudah ada query insert
    $inserted = false;

    foreach ($ids_ket as $index => $id_ket) {
        $id_lov = $ids_lov[$index] ?? null;
        $deskripsi = $deskripsi_arr[$index] ?? '';
        $jumlah_lov = $jumlah_lov_arr[$index] ?? 0;

        $bukti_file = null;

        // Periksa apakah ada file baru yang diunggah
        if (isset($_FILES['bukti']['name'][$index]) && $_FILES['bukti']['error'][$index] === UPLOAD_ERR_OK) {
            $file_name = basename($_FILES['bukti']['name'][$index]);
            $file_tmp = $_FILES['bukti']['tmp_name'][$index];
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

            if (in_array(strtolower($file_ext), $allowed_ext)) {
                $upload_path = $upload_dir . $file_name;
                if (move_uploaded_file($file_tmp, $upload_path)) {
                    $bukti_file = $file_name;
                }
            }
        } else {
            // Jika tidak ada file baru, ambil nilai lama dari database
            if ($id_lov) {
                $sql_get_bukti = "SELECT bukti FROM lov WHERE id = ?";
                $stmt_get = $config->prepare($sql_get_bukti);
                $stmt_get->bind_param('i', $id_lov);
                $stmt_get->execute();
                $result_get = $stmt_get->get_result();
                if ($row = $result_get->fetch_assoc()) {
                    $bukti_file = $row['bukti'];
                }
                $stmt_get->close();
            }
        }

        // Update data ke tabel
        if ($id_lov) {
            $sql_lov_update = "UPDATE lov SET deskripsi = ?, jumlah = ?, bukti = ? WHERE id = ?";
            $stmt = $config->prepare($sql_lov_update);
            $stmt->bind_param('sdsi', $deskripsi, $jumlah_lov, $bukti_file, $id_lov);
            $stmt->execute();
            $stmt->close();
        }

        // Data dari form untuk update
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

        // Proses Update
        foreach ($ids_bukti as $i => $id_bukti) {
            // Ambil data input
            $deskripsi_bukti = $deskripsi_bukti_arr[$i] ?? '';
            $biaya = $biaya_arr[$i] ?? 0;
            $tarif = $tarif_arr[$i] ?? null;
            $id_tol = (!empty($gerbang_tol_arr[$i]) && $gerbang_tol_arr[$i] !== '#') ? $gerbang_tol_arr[$i] : null;
            $status_bukti = "head section"; // Atau sesuai logika Anda
            $id_ket = $ids_ket[$i] ?? null;

            // $bukti_file1 = null; // Inisialisasi file pertama dengan null

            $file_input_name = null;

            // Periksa apakah input `buktitol` memiliki file yang valid pada index $i
            if (
                isset($_FILES['buktitol']['name'][$i]) &&
                $_FILES['buktitol']['error'][$i] === UPLOAD_ERR_OK &&
                !empty($_FILES['buktitol']['name'][$i])
            ) {
                $file_input_name = 'buktitol';
            }

            // Jika `buktitol` tidak ada, periksa `bukti_detail`
            elseif (
                isset($_FILES['bukti_detail']['name'][$i]) &&
                $_FILES['bukti_detail']['error'][$i] === UPLOAD_ERR_OK &&
                !empty($_FILES['bukti_detail']['name'][$i])
            ) {
                $file_input_name = 'bukti_detail';
            }

            // Jika tidak ada file valid, beri pesan debug
            if ($file_input_name === null) {
                // echo "No valid file input for index $i.<br>";
            }

            $bukti_file1 = null;
            if ($file_input_name) {
                try {
                    $bukti_file1 = processFileUpload($file_input_name, $i, $upload_dir);
                    echo "Index $i - File processed: $bukti_file1<br>";
                } catch (Exception $e) {
                    echo "Error uploading file for index $i ($file_input_name): " . $e->getMessage() . "<br>";
                    exit();
                }
            } else {
                // echo "No valid file input for index $i.<br>";
            }

            // Jika tidak ada file baru yang diupload, ambil file lama
            if (!$bukti_file1 && $id_bukti) {
                $query_get_file = "SELECT file FROM bukti WHERE id = ?";
                $stmt_get_file = $config->prepare($query_get_file);
                $stmt_get_file->bind_param('i', $id_bukti);
                $stmt_get_file->execute();
                $result_get_file = $stmt_get_file->get_result();
                $row = $result_get_file->fetch_assoc();
                $bukti_file1 = $row['file'] ?? null;
            }

            // Proses update data
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

        // Data dari form
        $deskripsi_bukti_arr = $_POST['deskripsi_buktiNew'] ?? [];
        $biaya_arr = $_POST['biayabukti_New'] ?? [];
        $tarif_arr = $_POST['tarif_New'] ?? [];
        $gerbang_tol_arr = $_POST['gerbangTol_New'] ?? [];
        $id_lov = $_POST['id_lov_insert'][0] ?? null; // Mengambil satu ID LOV saja

        // Validasi ID LOV
        if (empty($id_lov)) {
            die("ID LOV tidak ditemukan di form.");
        }

        // Validasi ID LOV berdasarkan id_ket
        $stmt = $config->prepare("SELECT id_ket FROM lov WHERE id = ?");
        $stmt->bind_param("i", $id_lov);
        $stmt->execute();
        $stmt->bind_result($id_ket);
        $stmt->fetch();
        $stmt->close();

        // // Hanya id_ket 1, 9, 13, 14 yang valid
        // $valid_id_ket = [1, 9, 13, 14];
        // if (!in_array($id_ket, $valid_id_ket)) {
        //     die("ID LOV tidak valid untuk dimasukkan ke tabel bukti.");
        // }

        // Data siap insert
        $insert_data = [];

        // Loop untuk setiap data form
        $total_entries = max(count($deskripsi_bukti_arr), count($gerbang_tol_arr));
        for ($i = 0; $i < $total_entries; $i++) {
            $deskripsi = trim($deskripsi_bukti_arr[$i] ?? '');
            $biaya = floatval($biaya_arr[$i] ?? 0);
            $tarif = floatval($tarif_arr[$i] ?? 0);
            $id_tol = trim($gerbang_tol_arr[$i] ?? '');

            // Validasi data kosong
            if (empty($deskripsi) && empty($id_tol)) {
                continue;
            }

            // Upload file
            $file_input_name = !empty($id_tol) ? 'buktiTol_New' : 'bukti1';
            try {
                $uploaded_file = processFileUpload($file_input_name, $i, $upload_dir);
            } catch (Exception $e) {
                echo "Upload Error: " . $e->getMessage();
                continue; // Skip data jika upload gagal
            }

            // Hindari duplikasi dengan validasi ke database
            $check_stmt = $config->prepare(
                "SELECT COUNT(*) FROM bukti WHERE (deskripsi = ? OR id_tol = ?) AND id_lov = ?"
            );
            $check_stmt->bind_param("ssi", $deskripsi, $id_tol, $id_lov);
            $check_stmt->execute();
            $check_stmt->bind_result($exists);
            $check_stmt->fetch();
            $check_stmt->close();

            if ($exists > 0) {
                continue; // Lewati jika data sudah ada
            }

            // Tambahkan data ke array insert_data
            $insert_data[] = [
                'file' => $uploaded_file,
                'deskripsi' => $deskripsi,
                'id_tol' => $id_tol,
                'biaya' => !empty($id_tol) ? $tarif : $biaya,
                'id_lov' => $id_lov,
                'status' => 'head section',
            ];
        }

        // Debugging: Lihat data yang akan diinsert
        echo "<pre>Data Insert: ";
        print_r($insert_data);
        echo "</pre>";

        // Insert data ke tabel bukti
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
    }

    header("Location: ../historyU.php");
    exit();
}

?>