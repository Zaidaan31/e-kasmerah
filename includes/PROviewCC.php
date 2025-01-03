<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'config.php';


if (!isset($_SESSION['email'])) {
    header("Location: ../login.php");
    exit();
}

$level = $_SESSION['level'];
if ($level !== 'admin' && $level !== 'cost control') {
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
$approval_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek jenis form berdasarkan action
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Proses LOV
        if ($action === 'lov' && isset($_POST['id_lov']) && isset($_POST['status'])) {
            $id_lov = intval($_POST['id_lov']);
            $status = $_POST['status'];

            if ($status === 'success' || $status === 'revisi') {
                $updateQuery = "UPDATE lov SET status = '$status' WHERE id = '$id_lov'";
                if (mysqli_query($config, $updateQuery)) {
                    // echo "<div class='alert alert-success'>Status LOV berhasil diperbarui.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Terjadi kesalahan saat mengupdate status LOV.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Status LOV tidak valid.</div>";
            }
        }
        elseif ($action === 'bukti' && isset($_POST['id_bukti']) && isset($_POST['status'])) {
            $id_bukti = intval($_POST['id_bukti']);
            $status = $_POST['status'];

            if ($status === 'success' || $status === 'revisi') {
                $updateQuery = "UPDATE bukti SET status = '$status' WHERE id = '$id_bukti'";
                if (mysqli_query($config, $updateQuery)) {
                    // echo "<div class='alert alert-success'>Status bukti berhasil diperbarui.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Terjadi kesalahan saat mengupdate status bukti.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Status bukti tidak valid.</div>";
            }
        }
        elseif ($action === 'tol' && isset($_POST['id_tol']) && isset($_POST['status'])) {
            $id_tol = intval($_POST['id_tol']);
            $status = $_POST['status'];

            if ($status === 'success' || $status === 'revisi') {
                $updateQuery = "UPDATE bukti SET status = '$status' WHERE id = '$id_tol'";
                if (mysqli_query($config, $updateQuery)) {
                    // echo "<div class='alert alert-success'>Status tol berhasil diperbarui.</div>";
                } else {
                    echo "<div class='alert alert-danger'>Terjadi kesalahan saat mengupdate status tol.</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Status tol tidak valid.</div>";
            }
        }
        // Proses Approval
        elseif ($action === 'approval' && isset($_POST['approval_action']) && isset($_POST['approval_id'])) {
            $approval_action = $_POST['approval_action'];
            $approval_id = intval($_POST['approval_id']);
            $status = '';
            $revisi_by = '';
            $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : '';

            if ($approval_action === 'approve') {
                $status = 'success';
            } elseif ($approval_action === 'revisi') {
                $status = 'revisi';
                $revisi_by = 'cost control';
            } else {
                echo "<div class='alert alert-danger'>Invalid action untuk approval.</div>";
                exit();
            }

            $updateApprovalQuery = "UPDATE approval SET status = '$status', catatan = '$catatan' WHERE id = '$approval_id'";
            if (mysqli_query($config, $updateApprovalQuery)) {
                if ($approval_action === 'revisi') {
                    $insertRevisiByQuery = "UPDATE approval SET revisi_by = '$revisi_by' WHERE id = '$approval_id'";
                    if (!mysqli_query($config, $insertRevisiByQuery)) {
                        echo "<div class='alert alert-danger'>Terjadi kesalahan saat menambahkan revisi_by pada approval.</div>";
                        exit();
                    }
                }
                $_SESSION['success_message'] = $approval_action === 'approve' ? "Data berhasil di approve." : "Data berhasil di revisi.";
                header("Location: approvalCC.php");
                exit();
            } else {
                echo "<div class='alert alert-danger'>Terjadi kesalahan saat mengupdate status approval.</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Aksi tidak valid.</div>";
        }
    }
}





$approvalQuery = "SELECT approval.*, vendor.no_vendor, vendor.nama as vendor, users.dept as dept,users.username as user FROM approval
                             LEFT JOIN vendor ON approval.dbyrkpd = vendor.id 
                             LEFT JOIN users ON approval.id_user = users.id WHERE approval.id = '$approval_id'";
$approvalResult = mysqli_query($config, $approvalQuery);

if (mysqli_num_rows($approvalResult) > 0) {
    $data = mysqli_fetch_assoc($approvalResult);
    $id_approval = $data['id'];

    $lovQuery = "SELECT lov.*, keterangan.nama as keterangan FROM lov LEFT JOIN keterangan on lov.id_ket = keterangan.id
     WHERE id_approval = '$id_approval'";
    $lovResult = mysqli_query($config, $lovQuery);
} else {
    echo "Data not found.";
    // exit();
}
?>