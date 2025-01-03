<?php
session_start();
include 'config.php'; // Pastikan file ini menginisialisasi koneksi $config dengan benar

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

$id_user = $_SESSION['id'];



// Kunci enkripsi (HARUS rahasia dan sama untuk enkripsi/dekripsi)
define('ENCRYPTION_KEY', 'my_secret_key_12345');

// Fungsi untuk enkripsi data
function encryptData($data) {
    $key = ENCRYPTION_KEY;
    $cipher = 'AES-128-CTR';
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher));
    $encrypted = openssl_encrypt($data, $cipher, $key, 0, $iv);
    return base64_encode($iv . $encrypted); // Gabungkan IV dan data terenkripsi
}

// Fungsi untuk dekripsi data
function decryptData($encryptedData) {
    $key = ENCRYPTION_KEY;
    $cipher = 'AES-128-CTR';
    $data = base64_decode($encryptedData);
    $iv_length = openssl_cipher_iv_length($cipher);
    $iv = substr($data, 0, $iv_length);
    $encrypted = substr($data, $iv_length);
    return openssl_decrypt($encrypted, $cipher, $key, 0, $iv);
}


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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo '<pre>';
    var_dump($_FILES);
    echo '</pre>';

    // Memeriksa apakah field utama ada
    if (isset($_POST['dbyrkpd'], $_POST['startPeriode'], $_POST['endPeriode'], $_POST['invoice'], $_POST['tanggalPengajuan'], $_POST['terbilang'], $_POST['jumlahtotal'])) {

        $dbyrkpd = $_POST['dbyrkpd'];
        $startPeriode = $_POST['startPeriode'];
        $endPeriode = $_POST['endPeriode'];
        $tanggalPengajuan = $_POST['tanggalPengajuan'];
        $invoice = $_POST['invoice'];
        $jumlahtotal = encryptData($_POST['jumlahtotal']);
        $terbilang = $_POST['terbilang'];

        // Set up upload directory
        $upload_dir = '../assets/image/';

        // Handle file uploads only if files exist, and set them to NULL if not uploaded
        $kronologi_filenamebukti = isset($_FILES['kronologi']['name']) && !empty($_FILES['kronologi']['name']) ? basename($_FILES['kronologi']['name']) : null;
        $buktipesan_filenamebukti = isset($_FILES['buktipesan']['name']) && !empty($_FILES['buktipesan']['name']) ? basename($_FILES['buktipesan']['name']) : null;
        $dispo_filenamebukti = isset($_FILES['dispo']['name']) && !empty($_FILES['dispo']['name']) ? basename($_FILES['dispo']['name']) : null;

        // Move files if they exist
        if ($kronologi_filenamebukti) {
            move_uploaded_file($_FILES['kronologi']['tmp_name'], $upload_dir . $kronologi_filenamebukti);
        }

        if ($buktipesan_filenamebukti) {
            move_uploaded_file($_FILES['buktipesan']['tmp_name'], $upload_dir . $buktipesan_filenamebukti);
        }

        if ($dispo_filenamebukti) {
            move_uploaded_file($_FILES['dispo']['tmp_name'], $upload_dir . $dispo_filenamebukti);
        }

        // Insert into approval table
        date_default_timezone_set('Asia/Jakarta');
        $timestamp = date("Y-m-d H:i:s");
        $stmt = $config->prepare("INSERT INTO approval (id_user, date, dbyrkpd, invoice, periodeAwal, periodeAkhir, tanggalPengajuan, kronologi, buktipesan, dispo, jumlahtotal, terbilang) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isisssssssss", $id_user, $timestamp, $dbyrkpd, $invoice, $startPeriode, $endPeriode, $tanggalPengajuan, $kronologi_filenamebukti, $buktipesan_filenamebukti, $dispo_filenamebukti, $jumlahtotal, $terbilang);

        if ($stmt->execute()) {
            $id_approve = $stmt->insert_id; // ID from approval table

            // Handle lov and bukti inserts only if there's data
            if (isset($_POST['keterangan']) && is_array($_POST['keterangan'])) {
                foreach ($_POST['keterangan'] as $index => $keterangan) {
                    if (empty($keterangan)) {
                        $keterangan = null;
                    }

                    $deskripsi = $_POST['deskripsi'][$index] ?? null;
                    $jumlah = isset($_POST['jumlah'][$index]) ? encryptData($_POST['jumlah'][$index]) : encryptData(0.00);

                    // Handle file upload for 'bukti' only if the file exists
                    $bukti_filenamebukti = isset($_FILES['bukti']['name'][$index]) && !empty($_FILES['bukti']['name'][$index]) ? basename($_FILES['bukti']['name'][$index]) : null;
                    if ($bukti_filenamebukti) {
                        move_uploaded_file($_FILES['bukti']['tmp_name'][$index], $upload_dir . $bukti_filenamebukti);
                    }

                    // Insert into lov table
                    $stmt_lov = $config->prepare("INSERT INTO lov (id_approval, id_user, id_ket, deskripsi, jumlah, bukti) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt_lov->bind_param("iiisis", $id_approve, $id_user, $keterangan, $deskripsi, $jumlah, $bukti_filenamebukti);

                    if ($stmt_lov->execute()) {
                        $id_lov = $stmt_lov->insert_id; // Get ID from lov insert
                        echo "ID LOV inserted: $id_lov<br>"; // Debugging
                    } else {
                        echo "Error inserting into lov table: " . $stmt_lov->error;
                        exit();
                    }

                    // Handle file upload for 'imagesbukti[]' only if the file exists
                    $bukti_filename = isset($_FILES['imagesbukti']['name'][$index]) && !empty($_FILES['imagesbukti']['name'][$index]) ? basename($_FILES['imagesbukti']['name'][$index]) : null;

                    // Handle multiple files upload for 'imagesbukti[]'
                    if (isset($_FILES['imagesbukti']) && !empty($_FILES['imagesbukti']['name'])) {
                        foreach ($_FILES['imagesbukti']['name'] as $index => $filename) {
                            // Get the file name
                            $bukti_filename = basename($filename);

                            // Check if a file is uploaded
                            if (!empty($bukti_filename)) {
                                // Move uploaded file to target directory
                                if (move_uploaded_file($_FILES['imagesbukti']['tmp_name'][$index], $upload_dir . $bukti_filename)) {
                                    echo "File uploaded: $bukti_filename<br>";
                                } else {
                                    echo "Failed to upload file: $bukti_filename<br>";
                                    continue; // Skip this file if upload fails
                                }

                                // Insert into bukti table if bukti exists
                                $stmt_bukti = $config->prepare("INSERT INTO bukti (file, id_lov) VALUES (?, ?)");
                                $stmt_bukti->bind_param("si", $bukti_filename, $id_lov);
                                if (!$stmt_bukti->execute()) {
                                    echo "Error inserting into bukti table: " . $stmt_bukti->error . "<br>";
                                    exit();
                                }
                            }
                        }
                    }


                }
            }
            // Redirect to a success page
            header("Location: ../historyU.php");
            exit();
        } else {
            echo "Error inserting into approval table: " . $stmt->error;
            exit();
        }
    } else {
        echo "Required fields or files are missing.";
        exit();
    }
}
?>