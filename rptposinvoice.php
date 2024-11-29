<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role'])) {
    $session_username = $_SESSION['username'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>POS Invoice Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* CSS to remove underline and change color for <a> tags */
            a.link {
                text-decoration: none; /* Remove underline */
                color: white; /* Change color to red */
            }
        </style>
    </head>

    <body>

<?php

    if($_SESSION['role']=='admin'){
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
    }else{
        include 'menu-bar.php';
        ?><br><?php
        include 'sub-menu-btn.php';
    }

?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h4>POS Invoice Report</h4>
                        </div>
                        <div class="card-body">

                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>From Bill Date</label>
                                            <input type="date" name="from_date"
                                                value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d'); ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>To Bill Date</label>
                                            <input type="date" name="to_date"
                                                value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>From Bill No.</label>
                                            <input type="number" name="from_billno" value="<?php echo isset($_GET['from_billno']) ? $_GET['from_billno'] : ''; ?>" class="form-control" step="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>To Bill No.</label>
                                            <input type="number" name="to_billno" value="<?php echo isset($_GET['to_billno']) ? $_GET['to_billno'] : ''; ?>" class="form-control" step="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <b>
                                                <label><u>Bill By :</u></label><br>
                                                <label><input type="checkbox" name="filter[]" value="23A002"> Duresh</label>
                                                <label><input type="checkbox" name="filter[]" value="23A001"> Baskar Raj</label>
                                                <label><input type="checkbox" name="filter[]" value="23E005"> Divya</label>
                                                <label><input type="checkbox" name="filter[]" value="23E002"> Santhanam</label>
                                                <label><input type="checkbox" name="filter[]" value="23E003"> Thatha</label>
                                                <br>
                                                <label><u>Bill Status :</u></label>
                                                <br>
                                                <label><input type="checkbox" name="status_filter[]" value="0"> Cancel</label>
                                                <label><input type="checkbox" name="status_filter[]" value="1" checked> Approve</label>
                                                <br>
                                                <label><u>Bill Payment Mode :</u></label>
                                                <br>
                                                <label><input type="checkbox" name="pMode_filter[]" value="1"> Cash</label>
                                                <label><input type="checkbox" name="pMode_filter[]" value="2"> GPay</label>
                                                <label><input type="checkbox" name="pMode_filter[]" value="3"> Paytm</label>
                                                <label><input type="checkbox" name="pMode_filter[]" value="4"> Credit</label>
                                            </b>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" border="5">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bill by</th>
                                            <th>Bill Date</th>
                                            <th>Bill No</th>
                                            <th>Customer Name</th>
                                            <th>Phone</th>
                                            <th>Pay Mode</th>
                                            <!--<th>Bill Amt</th>-->
                                            <th>Print</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        require 'dbconfig.php';
                                        $discount_sum = '';
                                        $paid_amount_sum = '';
                                        $Rs_sum = '';
                                        $oldMonthBal_sum = '';

                                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];

                                            $from_billno = isset($_GET['from_billno']) ? $_GET['from_billno'] : '';
                                            $to_billno = isset($_GET['to_billno']) ? $_GET['to_billno'] : '';
                                            $billnoFilterCondition = '';

                                            if (!empty($from_billno) && !empty($to_billno)) {
                                                $billnoFilterCondition = "AND bill_no BETWEEN '$from_billno' AND '$to_billno'";
                                            }

                                            // Retrieve selected filter options
                                            $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                            $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array();
                                            $pMode_filter = isset($_GET['pMode_filter']) ? $_GET['pMode_filter'] : array();

                                            // Build the filter condition
                                            $filterCondition = '';
                                            $statusFilterCondition = '';
                                            $pModefilterCondition = '';

                                            if (!empty($filters)) {
                                                $filterCondition = "AND username IN ('" . implode("','", $filters) . "')";
                                            }

                                            if (!empty($status_filter)) {
                                                if (is_array($status_filter)) {
                                                    $statusFilterCondition = "AND status IN ('" . implode("','", $status_filter) . "')";
                                                } else {
                                                    $statusFilterCondition = "AND status = '$status_filter'";
                                                }
                                            }

                                            if (!empty($pMode_filter)) {
                                                if (is_array($pMode_filter)) {
                                                    $pModefilterCondition = "AND pay_mode IN ('" . implode("','", $pMode_filter) . "')";
                                                } else {
                                                    $pModefilterCondition = "AND pay_mode = '$pMode_filter'";
                                                }
                                            }

                                            // $query = "SELECT pb.*, pbi.*
                                            //             FROM pos_bill pb
                                            //             FULL JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
                                            //             WHERE DATE(pb.entry_timestamp) BETWEEN '$from_date' AND '$to_date'
                                            //             $billnoFilterCondition $filterCondition $statusFilterCondition $pModefilterCondition;
                                            //         ";

                                            $query = "SELECT pb.*
                                                        FROM pos_bill pb
                                                        WHERE DATE(pb.entry_timestamp) BETWEEN '$from_date' AND '$to_date'
                                                        $billnoFilterCondition $filterCondition $statusFilterCondition $pModefilterCondition;
                                                    ";

                                            $query_run = mysqli_query($con, $query);
                                            $Rs_sum = 0;
                                            $discount_sum = 0;
                                            $paid_amount_sum = 0;
                                            $oldMonthBal_sum = 0;

                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1; // Initialize the serial number
                                                $bill_amt = 0;

                                                foreach ($query_run as $row) {
                                                    // $bill_amt += $row['price'];
                                                    $bill_amt -= $row['discount'];
                                                    $pay_mode = getPayModeName($row['pay_mode']);
                                                    ?>
                                        <tr>
                                            <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                            <td style="font-weight: bold;"><?= $row['username']; ?></td>
                                            <td style="width: 220px; font-weight: bold; color: #007DC3;"><?= formatDate($row['entry_timestamp']); ?></td>
                                            <td style="font-weight: bold;"><?= $row['bill_no']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['cus_name']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['cus_phone']; ?></td>
                                            <td style="font-weight: bold;"><?= $pay_mode ?></td>
                                            <!--<td style="font-weight: bold;"><?= $bill_amt ?></td>-->
                                            <td>
                                                <a href="prtposinvoice.php?token=<?= $row['token']; ?>"
                                                    target="_blank"><button type="button"
                                                        class="btn btn-warning"><i
                                                            class="bi bi-printer-fill"></i></button></a>
                                            </td>
                                        </tr>
                                        <?php
                                                }
                                            } else {
                                                echo "No Record Found";
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
        </div>
    <br>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'footer.php'?>

<?php } else {
    header("Location: logout.php");
}
?>
