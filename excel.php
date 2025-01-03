<?php
session_start();
require 'phpspreadsheet/vendor/autoload.php'; // Pastikan path ke autoload benar
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

include('includes/config.php');

// Cek apakah user sudah login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Cek level akses user
$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'cost control') {
    header("Location: home.php");
    exit();
}

// Cek apakah ID user tersedia di session
if (!isset($_SESSION['id'])) {
    echo "User ID is not set. Debugging Information:";
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
    exit();
}

// Proses jika form disubmit
if (isset($_POST['submit'])) {
    // Ambil tanggal dari form
    $date1 = $_POST['startDate'];
    $date2 = $_POST['endDate'];

    // Panggil stored procedure
    $storedProcQuery = "CALL GetInvoiceByDate(?, ?)";
    
    if ($stmt = $config->prepare($storedProcQuery)) {
        $stmt->bind_param('ss', $date1, $date2);
        $stmt->execute();
        $storedProcResult = $stmt->get_result();

        // Periksa apakah ada hasil
        if ($storedProcResult && $storedProcResult->num_rows > 0) {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Invoices');

            // Menambahkan judul
            $sheet->mergeCells('A1:I1'); // Menggabungkan sel untuk judul
            $sheet->setCellValue('A1', 'Kas Merah Daily'); // Menambahkan judul
            $sheet->getStyle('A1')->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle('A1')->getFill()->getStartColor()->setARGB('ADD8E6'); // Mengatur warna biru muda
            $sheet->getStyle('A1')->getFont()->setBold(true);
            $sheet->getStyle('A1')->getFont()->setSize(18); // Mengatur ukuran font
            $sheet->getStyle('A1')->getFont()->getColor()->setARGB(Color::COLOR_BLACK); // Mengatur warna font hitam
            $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT); // Mengatur rata kiri

            // Menambahkan baris kosong setelah judul
            $sheet->setCellValue('A2', ' '); // Baris kosong untuk pemisah

            // Menambahkan header dengan urutan yang diminta
            $headers = ['NO','NO INVOICE', 'TANGGAL','KETERANGAN', 'DESKRIPSI', 'NOMINAL', 'JAM', 'COA', 'KETERANGAN COA'];
            $sheet->fromArray($headers, NULL, 'A3');

            // Set lebar kolom
            $sheet->getColumnDimension('A')->setWidth(5);   
            $sheet->getColumnDimension('B')->setWidth(30); 
            $sheet->getColumnDimension('C')->setWidth(15); 
            $sheet->getColumnDimension('D')->setWidth(30); 
            $sheet->getColumnDimension('E')->setWidth(30);  
            $sheet->getColumnDimension('F')->setWidth(20); 
            $sheet->getColumnDimension('G')->setWidth(10);  
            $sheet->getColumnDimension('H')->setWidth(25);  
            $sheet->getColumnDimension('I')->setWidth(60);

            // Mengubah warna latar belakang header menjadi hijau dan font bold
            $sheet->getStyle('A3:I3')->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle('A3:I3')->getFill()->getStartColor()->setARGB(Color::COLOR_GREEN);
            $sheet->getStyle('A3:I3')->getFont()->setBold(true); // Font bold untuk header

            // Menambahkan filter
            $sheet->setAutoFilter('A3:I3');

            // Mengatur rata tengah untuk header
            $sheet->getStyle('A3:I3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $rowIndex = 4; // Mulai dari baris keempat setelah header
            $no = 1; // Inisialisasi nomor urut

            while ($row = $storedProcResult->fetch_assoc()) {
                $no_invoice = $row['no_invoice'];
                $date = date('d/m/Y', strtotime($row['date']));
                $time = date('H:i:s', strtotime($row['date']));
                $keterangan = $row['keterangan'];
                $deskripsi = $row['deskripsi'];
                $COA = $row['COA'];
                $KETERANGAN_COA = $row['KETCOA'];
                $jumlahtotal = $row['jumlahtotal'];

                $sheet->setCellValue("A$rowIndex", $no);
                $sheet->setCellValue("B$rowIndex", $no_invoice);
                $sheet->setCellValue("C$rowIndex", $date);
                $sheet->setCellValue("D$rowIndex", $keterangan);
                $sheet->setCellValue("E$rowIndex", $deskripsi);
                $sheet->setCellValue("F$rowIndex", $jumlahtotal);
                $sheet->setCellValue("G$rowIndex", $time);
                $sheet->setCellValue("H$rowIndex", $COA);
                $sheet->setCellValue("I$rowIndex", $KETERANGAN_COA);

                // Mengatur rata tengah untuk setiap kolom data (kecuali D, E, dan I)
                $sheet->getStyle("A$rowIndex:C$rowIndex")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("F$rowIndex:G$rowIndex")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H$rowIndex")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                // Kolom D, E, dan I dibiarkan dengan rata kiri (default)

                // Menambahkan border untuk setiap sel data
                $sheet->getStyle("A$rowIndex:I$rowIndex")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $rowIndex++;
                $no++; // Increment nomor urut
            }

            // Menambahkan border untuk header
            $sheet->getStyle('A3:I3')->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Menyimpan file Excel
            $writer = new Xlsx($spreadsheet);
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="export.xlsx"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit();
        } else {
            echo "Data tanggal tersebut tidak tersedia.";
        }

        $stmt->close();
    } else {
        echo "SQL error: " . $config->error;
    }
}
?>
