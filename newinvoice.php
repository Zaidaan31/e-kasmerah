<?php
session_start();
include('includes/config.php');
// require 'vendor/autoload.php'; // pastikan composer autoload di-load dengan benar

// use Endroid\QrCode\QrCode;
// use Endroid\QrCode\Writer\SvgWriter;

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'cost control') {
    header("Location: home.php");
    exit();
}

if (!isset($_SESSION['id'])) {
    echo "User ID is not set.";
    exit();
}

$id_user = $_SESSION['id'];
$approval_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$storedProcQuery = "CALL GetInvoice($approval_id)";
$storedProcResult = mysqli_query($config, $storedProcQuery);

if (!$storedProcResult) {
    die('Query failed: ' . mysqli_error($config));
}

$rows = [];
while ($row = mysqli_fetch_assoc($storedProcResult)) {
    $rows[] = $row;
}

if (count($rows) > 0) {
    $departemen = $rows[0]['dept'];
    $no_invoice = $rows[0]['no_invoice'];
    $invoice = $rows[0]['invoice'];
    $periodeAwal = $rows[0]['periodeAwal'];
    $periodeAkhir = $rows[0]['periodeAkhir'];
    $tglPengajuan = $rows[0]['tanggalPengajuan'];
    $date = $rows[0]['date'];
    $dibayar = $rows[0]['vendor'];
    $novendor = $rows[0]['no_vendor'];
    $jumlahtotal = $rows[0]['jumlahtotal'];
    $terbilang = $rows[0]['terbilang'];
    $COA = $rows[0]['COA'];
    $tgl_coa = $rows[0]['tgl_coa'];
    $deskripsi = $rows[0]['deskripsi'];
    $status = $rows[0]['status'];
}
// Data untuk QR code
$qrData = $no_invoice . "\n" .
    $novendor . "\n" .
    $dibayar . "\n" .
    $date . "\n" .
    $status;

// $qrCode = QrCode::create($qrData);

// // Tentukan ukuran SVG
// $qrCode->setSize(80); // Ukuran QR code dalam pixel (100px)

// // Gunakan SvgWriter untuk menghasilkan QR code dalam format SVG
// $writer = new SvgWriter();
// $qrSvg = $writer->write($qrCode);

// // Dapatkan konten SVG dalam bentuk string
// $svgContent = $qrSvg->getString();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>

<body class="font-sans">


    <div class="max-w-6xl mx-auto my-10 p-5 border border-red-600">
        <div class="flex justify-between items-center">
            <div class="bg-white text-center w-40 mr-0">
                <h1 class="font-bold text-red-600 text-lg">P.T. TIKI JNE</h1>
                <div class="w-full h-[2px] bg-red-500 mb-1"></div>
                <div class="w-full h-[2px] bg-red-500 mb-1"></div>
                <h1 class="font-bold text-red-600 text-md">JAKARTA</h1>
            </div>
            <div>
                <!-- Ini cara menampilkan QR Code SVG dengan aman -->
                <div id="qrcode" class="ml-2 mr-[38rem] w-35 h-25"></div>
            </div>
            <div class="text-left bg-white space-y-1 w-1/4 ml-0">
                <p class="font-semibold">
                    <span class="text-red-500 text-lg">DEPT. </span><span class="text-sm"><?php echo $departemen; ?></span>
                </p>
                <p class="font-semibold">
                    <span class="text-red-500 text-lg">NO.</span>
                    <span class="text-red-500 ml-[5px]"> :</span>
                    <span class="text-sm"><?php echo $no_invoice; ?></span>
                </p>
            </div>

        </div>
        <div class="flex-grow text-center w-full flex justify-between items-center">
            <div class="flex-grow text-center">
                <p class="font-bold text-lg self-center w-1/2 text-red-600 ml-[375px]">BUKTI PENGELUARAN KAS/BANK</p>
                <div class="w-[300px] h-[2px] bg-red-500 mb-1 ml-[400px] "></div>
            </div>
            <p class="font-bold text-xs ml-[205px] text-left w-full"><?php echo date("d-m-Y H:i", strtotime($date)); ?></p>
        </div>

        <div class="">
            <div class="flex items-center ">
                <div class="text-red-500  w-[400px]">
                    <span>Dibayar Kepada :</span>
                </div>
                <div class="w-[100rem] " style="text-decoration: underline dotted red;">
                    <strong><?php echo $novendor, ' ', $dibayar; ?></strong>
                </div>
                <svg id="barcode" class="w-3/4"></svg>
                <script>
                    var invoiceNumber = "<?php echo $no_invoice; ?>"; // PHP ke JavaScript
                    JsBarcode("#barcode", invoiceNumber, {
                        format: "CODE128", // Tipe barcode
                        width: 1, // Lebar batang
                        height: 30, // Tinggi batang
                        displayValue: false // Tampilkan nilai
                    });
                </script>
            </div>
            <div class="flex custom-justify mt-2">
                <span class="text-red-500">Sejumlah </span>
                <span class="text-red-500 ml-[51px]"> :</span>
                <span class="text-red-500 mx-1">Rp.</span>
                <strong class="mx-[1px]" style="text-decoration: underline dotted red;">
                    <?php echo number_format($jumlahtotal, 0, ',', '.'); ?>
                </strong>
                <span class="text-red-500 mt-0.5">...................................</span>
                <span class="">
                    <span class="text-red-500 mr-2">Terbilang </span><i style="text-decoration: underline dotted red;"><b><?php echo $terbilang; ?></b></i>

                </span>

            </div>
            <div class="flex custom-justify mt-2">
                <span class="text-red-500 ml-[116px]"> : ...........................................................................................................................................................................................................................................................................................</span>
            </div>

        </div>

        <div class="mt-5">
            <div class="w-full h-[2px] bg-red-500 mb-1"></div>
            <table class="w-full border-collapse border border-red-600 text-sm text-left">
                <thead class=" text-red-500 text-center">
                    <tr>
                        <th class="border border-red-600 py-2">K e t e r a n g a n</th>
                        <th class="border border-red-600 py-2">D/K</th>
                        <th class="border border-red-600 py-2">Code No</th>
                        <th class="border border-red-600 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row) { ?>
                        <tr>
                            <td class="border border-red-600 py-2  w-[600px]"><?php echo $row['keterangan'], ' ', $row['deskripsi']; ?></td>
                            <td class="border border-red-600 py-2  w-[42px]"></td>
                            <td class="border border-red-600 py-2 w-[228px] text-center"><?php echo $row['COA']; ?></td>
                            <td class="border border-red-600 py-2">
                                <div class="flex justify-between">
                                    <span class="text-red-500">Rp.</span>
                                    <span class="text-right">
                                        <?php
                                        if ($row['jumlah'] < 0) {
                                            echo '-' . '(' . number_format(abs($row['jumlah']), 0, ',', '.') . ')';
                                        } else {
                                            echo number_format($row['jumlah'], 0, ',', '.');
                                        }
                                        ?>
                                    </span>


                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table class="w-full border-collapse border border-red-600 text-sm text-left">
                <thead class=" text-white hidden">
                    <tr>
                        <th class="border border-red-600 py-2">Keterangan</th>
                        <th class="border border-red-600 py-2">D/K</th>
                        <th class="border border-red-600 py-2">Code No</th>
                        <th class="border border-red-600 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-red-600 py-2 w-[600px]"></td>
                        <td class="border border-red-600 py-2 w-[42px]"></td>
                        <td class="border border-red-600 py-2 w-[228px]"></td>
                        <td class="border border-red-600 py-2"><span class="text-red-500">Rp.</span></td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full border-collapse border border-red-600 text-sm text-left">
                <thead class=" text-white hidden">
                    <tr>
                        <th class="border border-red-600 py-2">Keterangan</th>
                        <th class="border border-red-600 py-2">D/K</th>
                        <th class="border border-red-600 py-2">Code No</th>
                        <th class="border border-red-600 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-red-600 py-2 w-[600px]"><strong><?php echo 'TGL: ', date("d-m-Y", strtotime($periodeAwal)), ' s.d ', date("d-m-Y", strtotime($periodeAkhir)); ?></strong></td>
                        <td class="border border-red-600 py-2  w-[42px]"></td>
                        <td class="border border-red-600 py-2 w-[228px] text-center"><strong><i><?php echo date("d-m-Y H:i", strtotime($tgl_coa)); ?></i></strong></td>
                        <td class="border border-red-600 py-2"><span class="text-red-500">Rp.</span></td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full border-collapse border border-red-600 text-sm text-left">
                <thead class=" text-white hidden">
                    <tr>
                        <th class="border border-red-600 py-2">Keterangan</th>
                        <th class="border border-red-600 py-2">D/K</th>
                        <th class="border border-red-600 py-2">Code No</th>
                        <th class="border border-red-600 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-red-600 py-2 w-[600px]"><strong><em><?php echo $invoice; ?></em></strong>
                        </td>
                        <td class="border border-red-600 py-2 w-[42px]"></td>
                        <td class="border border-red-600 py-2 w-[228px]"></td>
                        <td class="border border-red-600 py-2"><span class="text-red-500">Rp.</span></td>
                    </tr>
                </tbody>
            </table>
            <table class="w-full border-collapse  text-sm text-left">
                <thead class=" text-white hidden">
                    <tr>

                        <th class="border border-red-600 py-2">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class=" py-2 w-[600px]"></td>
                        <td class=" py-2 w-[42px]"></td>
                        <td class=" py-2 w-[228px]"></td>
                        <td class="border border-red-600 py-2">
                            <div class="flex justify-between">
                                <span class="text-red-500">Rp. </span>
                                <span class="text-right"><strong> <?php echo number_format($jumlahtotal, 0, ',', '.'); ?></strong></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
        <div class="flex justify-between">
            <div class="">
                <table class="w-full border-collapse border border-red-600 text-center text-sm">
                    <tr>
                        <td class="border border-red-600 px-8 text-red-500">Direktur</td>
                        <td class="border border-red-600 px-8 text-red-500">Fin. Mgr</td>
                        <td class="border border-red-600 px-8 text-red-500">Gen. Mgr</td>
                        <td class="border border-red-600 px-8 text-red-500">Dept. Mgr</td>
                        <td class="border border-red-600 px-8 text-red-500">Kasir</td>
                    </tr>
                    <tr class="h-16">
                        <td class="border border-red-600 py-20"></td>
                        <td class="border border-red-600 py-20"></td>
                        <td class="border border-red-600 py-20"></td>
                        <td class="border border-red-600 py-20"></td>
                        <td class="border border-red-600 py-20"></td>
                    </tr>
                </table>
            </div>
            <div class="mt-5 mr-[120px] text-center text-red-500">
                <p>Cikarang, <?php echo date("l, F d, Y", strtotime($tglPengajuan)); ?></p>
                <p>Yang Menerima,</p>
                <div class="h-16"></div>
                <p>(........................................)</p>
                <p>Nama Terang</p>
            </div>
        </div>
    </div>


    <script type="text/javascript">
         var qrData = `<?php echo $qrData; ?>`;

        // Buat QR code
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: qrData,
            width: 100,  // ukuran QR code
            height: 100  // ukuran QR code
        });
    </script>
</body>

</html>