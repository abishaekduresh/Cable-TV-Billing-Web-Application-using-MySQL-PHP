<?php 
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   

    if ($_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'admin-menu-btn.php';
    } elseif ($_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'sub-menu-btn.php';
    }

    $filtervalues = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer History</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    .table-hover tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
</head>
<body>

<div class="container-fluid mt-3">

    <!-- Search Form -->
    <div class="row mb-3">
        <div class="col-md-7">
            <form action="" method="GET">
                <div class="input-group mb-3">
                    <input type="text" name="search" pattern="[A-Za-z0-9\s]{3,}" required 
                        value="<?= htmlspecialchars($filtervalues) ?>" 
                        class="form-control" placeholder="Enter Minimum 3 Character of STB No, Phone">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Customer History Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Customer History</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-hover table-bordered align-middle" style="white-space: nowrap;">
                        <thead class="table-dark">
                            <tr>
                                <th>B.No</th>
                                <th>Col Date</th>
                                <th>Bill Date</th>
                                <th>Time</th>
                                <th>Bill by</th>
                                <th>STB No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Remark</th>
                                <th>pMode</th>
                                <th>BillAmt</th>
                                <th>Disct</th>
                                <th>Rs</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($filtervalues)) {
                                $query = "SELECT * FROM bill WHERE CONCAT(stbno, phone) LIKE '%$filtervalues%' ORDER BY bill_id DESC LIMIT 50";
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($bill = mysqli_fetch_assoc($query_run)) {
                                        $bgColor = ($bill['adv_status'] == 1) ? '#dfb9fa' : (($bill['pMode'] === 'credit') ? 'yellow' : '');
                                        ?>
                                        <tr style="background-color: <?= $bgColor ?>;" 
                                            title="<?= htmlspecialchars($bill['remark2'] ?? '-') ?>">
                                            <td class="fw-bold"><?= $bill['billNo']; ?></td>
                                            <td class="fw-bold text-primary"><?= formatDate($bill['date']); ?></td>
                                            <td class="fw-bold text-primary"><?= formatDate($bill['due_month_timestamp']); ?></td>
                                            <td class="fw-bold"><?= convertTo12HourFormat($bill['time']); ?></td>
                                            <td class="fw-bold"><?= $bill['bill_by']; ?></td>
                                            <td class="fw-bold"><?= $bill['stbno']; ?></td>
                                            <td class="fw-bold"><?= $bill['name']; ?></td>
                                            <td class="fw-bold"><?= $bill['phone']; ?></td>
                                            <td class="fw-bold text-<?= ($bill['status'] == 'approve') ? 'success' : 'danger' ?>">
                                                <?= ucfirst(strtolower($bill['status'])); ?>
                                            </td>
                                            <td class="fw-bold"><?= $bill['description']; ?></td>
                                            <td class="fw-bold"><?= $bill['pMode']; ?></td>
                                            <td class="fw-bold text-success"><?= $bill['paid_amount']; ?></td>
                                            <td class="fw-bold text-danger"><?= $bill['discount']; ?></td>
                                            <td class="fw-bold text-danger"><?= $bill['Rs']; ?></td>
                                            <td>
                                                <a href="prtindivbillrpt.php?billid=<?= $bill['bill_id']; ?>" target="_blank" 
                                                   class="btn btn-warning btn-sm">
                                                    <i class="bi bi-printer-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="15" class="text-center">No Record Found</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="15" class="text-center">No Search Value Entered</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!---------------------- After bill --------------------->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stbno        = mysqli_real_escape_string($con, $_POST["stbno"]);
    $name         = $_POST["name"];
    $phone        = $_POST["phone"];
    $mso          = $_POST["mso"];
    $description  = $_POST["description"];
    $pMode        = $_POST["pMode"];
    $paid_amount  = $_POST["paid_amount"];
    $bill_status  = 'approve';
    $discount     = 0;
    $Rs           = $paid_amount;
    $oldMonthBal  = 0;
    $printStatus  = 0;

    // Bill Number Logic
    if ($currentDay === '01') {
        $checkNextDayQuery = "SELECT billNo FROM bill WHERE DATE(date) = DATE_ADD('$currentDate', INTERVAL 1 DAY) LIMIT 1";
        $result = $con->query($checkNextDayQuery);
        if ($result->num_rows > 0) {
            $billNo = 1;
        } else {
            $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
            $result = $con->query($getMaxBillNoQuery);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $maxBillNo = $row["maxBillNo"];
                $billNo = ($maxBillNo < 1) ? 1 : $maxBillNo + 1;
            } else {
                $billNo = 1;
            }
        }
    } else {
        $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
        $result = $con->query($getBillNoQuery);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $billNo = $row["maxBillNo"] + 1;
        } else {
            $billNo = 1;
        }
    }

    $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, pMode, oldMonthBal, paid_amount, discount, Rs, adv_status, due_month_timestamp, status, printStatus) 
            VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', 0, '$currentDateTime', '$bill_status', '$printStatus')";

    if ($con->query($sql) === TRUE) {
        $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$currentDate' AND status = 'approve'";
        $result = $con->query($sqlSum);
        $row = $result->fetch_assoc();
        $sumPaidAmount = $row["total_Rs"];

        $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
        $resultCheck = $con->query($sqlCheck);

        if ($resultCheck->num_rows > 0) {
            $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
            $con->query($sqlUpdate);
        } else {
            $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount)";
            $con->query($sqlInsert);
        }

        function redirect($url) {
            echo "<script>
            setTimeout(function(){
                window.location.href = '$url';
            }, 200);
            </script>";
        }

        redirect("prtindivbulkbilldash.php");

    } else {
        echo "Error inserting data: " . $con->error;
        ?>
        <center><img src="assets/red-thumbs-up.svg" alt="red-thumbs-up" width="512px" height="512px"></center>
        <?php
    }
}

$query1 = "SELECT * FROM bill WHERE CONCAT(stbno, phone) LIKE '%$filtervalues%' AND MONTH('$currentDate')='$currentMonth' AND YEAR('$currentDate')='$currentYear'";
$query_run1 = mysqli_query($con, $query1);

if (mysqli_num_rows($query_run1) > 0) {
    $row = mysqli_fetch_assoc($query_run1);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card mt-4">
                <div class="card-body">
                    <h3><center><u>Make bill after bill : <?= $session_username ?></u></center></h3>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="mso" class="form-label">MSO :</label>
                            <input readonly type="text" name="mso" value="<?= $row['mso']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="stbno" class="form-label">STB No:</label>
                            <input readonly type="text" name="stbno" value="<?= $row['stbno']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name :</label>
                            <input readonly type="text" name="name" value="<?= $row['name']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone :</label>
                            <input readonly type="number" name="phone" value="<?= $row['phone']; ?>" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="pMode" class="form-label">pMode :</label>
                            <select name="pMode" class="form-select fw-bold">
                                <option value="cash" selected class="fw-bold">Cash</option>
                                <option value="paytm" class="fw-bold">Paytm</option>
                                <option value="gpay" class="fw-bold">G Pay</option>
                                <option value="credit" class="fw-bold">Credit</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Remark :</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="paid_amount" class="form-label">Amount :</label>
                            <input type="number" name="paid_amount" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    echo '<tr><td colspan="4">No Record Found</td></tr>';
}
?>

<br/>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>

<?php 
} else {
    header("Location: logout.php");
} 
?>
