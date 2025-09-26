<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
    ?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bill by All Report</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
.table-hover tbody tr:hover {
    background-color: #f1f1f1;
}
.table thead th {
    vertical-align: middle;
    text-align: center;
}
.table tbody td {
    vertical-align: middle;
    text-align: center;
}
</style>
</head>

<body>

<?php
include 'admin-menu-bar.php';
?><br><?php
include 'admin-menu-btn.php';
?>

<div class="container-fluid mt-4">

    <!-- Filter Form -->
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Filter Bills</h4>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label>From Bill Date</label>
                            <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label>To Bill Date</label>
                            <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-2">
                            <label>From Bill No</label>
                            <input type="number" name="from_billno" class="form-control" value="<?= $_GET['from_billno'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <label>To Bill No</label>
                            <input type="number" name="to_billno" class="form-control" value="<?= $_GET['to_billno'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Bill by All Report</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="white-space: nowrap;">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Bill By</th>
                                <th>Col Date</th>
                                <th>Bill Date</th>
                                <th>Bill No</th>
                                <th>MSO</th>
                                <th>STB No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Remarks</th>
                                <th>P.Mode</th>
                                <th>OldBal</th>
                                <th>BillAmt</th>
                                <th>Disct</th>
                                <th>Rs</th>
                                <th>Print</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $Rs_sum = $discount_sum = $paid_amount_sum = $oldMonthBal_sum = 0;
                        if (isset($_GET['from_date'], $_GET['to_date'])) {
                            $from_date = $_GET['from_date'];
                            $to_date = $_GET['to_date'];
                            $from_billno = $_GET['from_billno'] ?? '';
                            $to_billno = $_GET['to_billno'] ?? '';
                            $billFilter = '';
                            if($from_billno && $to_billno){
                                $billFilter = " AND billNo BETWEEN '$from_billno' AND '$to_billno'";
                            }

                            $query = "SELECT * FROM bill WHERE DATE(due_month_timestamp) BETWEEN '$from_date' AND '$to_date' $billFilter ORDER BY DATE(due_month_timestamp) DESC";
                            $result = mysqli_query($con, $query);

                            if(mysqli_num_rows($result) > 0){
                                $sn = 1;
                                while($row = mysqli_fetch_assoc($result)){
                                    $Rs_sum += $row['Rs'];
                                    $discount_sum += $row['discount'];
                                    $paid_amount_sum += $row['paid_amount'];
                                    $oldMonthBal_sum += $row['oldMonthBal'];
                                    ?>
                                    <tr><b>
                                        <td class="fw-bold"><?= $sn++; ?></td>
                                        <td class="fw-bold"><?= $row['bill_by']; ?></td>
                                        <td class="text-primary fw-bold"><?= formatDate($row['date']); ?></td>
                                        <td class="text-primary fw-bold"><?= formatDate($row['due_month_timestamp']); ?></td>
                                        <td class="fw-bold"><?= $row['billNo']; ?></td>
                                        <td class="fw-bold"><?= $row['mso']; ?></td>
                                        <td class="fw-bold"><?= $row['stbno']; ?></td>
                                        <td class="fw-bold"><?= $row['name']; ?></td>
                                        <td class="fw-bold"><?= $row['phone']; ?></td>
                                        <td class="fw-bold"><?= $row['description']; ?></td>
                                        <td class="fw-bold"><?= $row['pMode']; ?></td>
                                        <td class="text-primary fw-bold"><?= $row['oldMonthBal']; ?></td>
                                        <td class="text-success fw-bold"><?= $row['paid_amount']; ?></td>
                                        <td class="text-danger fw-bold"><?= $row['discount']; ?></td>
                                        <td class="text-danger fw-bold"><?= $row['Rs']; ?></td>
                                        <td>
                                            <a href="prtindivbillrpt.php?billid=<?= $row['bill_id']; ?>" target="_blank" class="btn btn-warning btn-sm">
                                                <i class="bi bi-printer-fill"></i>
                                            </a>
                                        </td>
                                </b></tr>
                                    <?php
                                }
                                // Totals row
                                ?>
                                <tr class="table-secondary fw-bold">
                                    <td colspan="11" class="text-end">Total:</td>
                                    <td><?= $oldMonthBal_sum ?></td>
                                    <td><?= $paid_amount_sum ?></td>
                                    <td><?= $discount_sum ?></td>
                                    <td><?= $Rs_sum ?></td>
                                    <td></td>
                                </tr>
                                <?php
                            } else {
                                echo '<tr><td colspan="16" class="text-center fw-bold">No Record Found</td></tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>
<?php 
} else {
    header("Location: logout.php");
}
?>
