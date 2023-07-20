<?php
session_start();
include "dbconfig.php";

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>bill-filter-by-date</title>
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

    include 'admin-menu-bar.php';
    ?><br<?php
    include 'admin-menu-btn.php';

?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h4>Bill by All</h4>
                        </div>
                        <div class="card-body">

                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input type="date" name="from_date"
                                                value="<?php if (isset($_GET['from_date'])) {
                                                echo $_GET['from_date'];
                                            } else {
                                                echo $currentDate;
                                            } ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>To Date</label>
                                            <input type="date" name="to_date"
                                                value="<?php if (isset($_GET['to_date'])) {
                                                echo $_GET['to_date'];
                                            } else {
                                                echo $currentDate;
                                            } ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <!--<label>Click to Filter</label>--> <br>
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div><p>Under Testing...</p>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>From Time</label>
                                            <input type="time" name="from_time" value="<?php if (isset($_GET['from_time'])) {
                                                echo $_GET['from_time'];
                                            }?>" class="form-control" step="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>To Time</label>
                                            <input type="time" name="to_time" value="<?php if (isset($_GET['to_time'])) {
                                                echo $_GET['to_time'];
                                            }?>" class="form-control" step="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
<b>
    
                                                <label><u>Bill By :</u></label><br>
                                            <label><input type="checkbox" name="filter[]" value="23A002">
                                                Duresh</label>
                                            <label><input type="checkbox" name="filter[]" value="23A001">
                                                Baskar Raj</label>
                                            <label><input type="checkbox" name="filter[]" value="23E001">
                                                Kannika</label>
                                            <label><input type="checkbox" name="filter[]" value="23E002">
                                                Santhanam</label>
                                            <label><input type="checkbox" name="filter[]" value="23E003">
                                                Thatha</label>
                                            <br>
                                            <label><u>Bill Status :</u></label>
                                            <br>
                                            <label><input type="checkbox" name="status_filter[]" value="cancel">
                                                Cancel</label>
                                            <label><input type="checkbox" name="status_filter[]" value="approve" checked>
                                                Approve</label>
                                            <br>
                                            <label><u>Bill Payment Mode :</u></label>
                                            <br>
                                            <label><input type="checkbox" name="pMode_filter[]" value="cash">
                                                Cash</label>
                                            <label><input type="checkbox" name="pMode_filter[]" value="gpay">
                                                GPay</label>
                                            <label><input type="checkbox" name="pMode_filter[]" value="credit">
                                                Credit</label>
</b>
                                            <!-- Add more checkboxes for other filter options -->
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
                                            <th>Date</th>
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
                                        require 'dbconfig.php';
                                        $discount_sum = '';
                                        $paid_amount_sum = '';
                                        $Rs_sum = '';
                                        $oldMonthBal = '';

                                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];


$from_time = isset($_GET['from_time']) ? $_GET['from_time'] : '';
$to_time = isset($_GET['to_time']) ? $_GET['to_time'] : '';
$timeFilterCondition = '';

if (!empty($from_time) && !empty($to_time)) {
    $timeFilterCondition = "AND time BETWEEN '$from_time' AND '$to_time'";
}


                                            // Retrieve selected filter options
                                            $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                            $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array();
                                            $pMode_filter = isset($_GET['pMode_filter']) ? $_GET['pMode_filter'] : array();


                                            // Build the filter condition
                                            $filterCondition = '';
                                            $statusFilterCondition = '';
                                            $pModefilterCondition = '';
                                            $oldMonthBal_sum = '';

                                            if (!empty($filters)) {
                                                $filterCondition = "AND bill_by IN ('" . implode("','", $filters) . "')";
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
                                                    $pModefilterCondition = "AND pMode IN ('" . implode("','", $pMode_filter) . "')";
                                                } else {
                                                    $pModefilterCondition = "AND pMode = '$pMode_filter'";
                                                }
                                            }


                                            $query = "SELECT * FROM bill WHERE date BETWEEN '$from_date' AND '$to_date' $timeFilterCondition $filterCondition $statusFilterCondition $pModefilterCondition";

                                            $query_run = mysqli_query($con, $query);
                                            $Rs_sum = 0;
                                            $discount_sum = 0;
                                            $paid_amount_sum = 0;
                                            $oldMonthBal_sum = 0;

                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1; // Initialize the serial number

                                                foreach ($query_run as $row) {
                                                    ?>
                                        <tr>
                                            <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                            <td style="width: 100px; font-size: 18px; font-weight: bold;"><?= $row['bill_by']; ?></td>
                                            <td style="width: 240px; font-weight: bold; font-size: 18px; color: #007DC3;"><?= $row['date']; ?></td>
                                            <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row['billNo']; ?></td>
                                            <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row['mso']; ?></td>
                                            <td style="width: 160px; font-size: 18px; font-weight: bold;"><?= $row['stbno']; ?></td>
                                            <td style="width: 350px; font-size: 18px; font-weight: bold;"><?= $row['name']; ?></td>
                                            <td style="width: 110px; font-size: 18px; font-weight: bold;"><?= $row['phone']; ?></td>
                                            <td style="width: 180px; font-size: 18px; font-weight: bold;"><?= $row['description']; ?></td>
                                            <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row['pMode']; ?></td>
                                            <td style="width: 50px; font-weight: bold; font-size: 20px; color: #0012C3;">
                                                <?= $row['oldMonthBal']; ?>
                                            </td>
                                            <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;">
                                                <?= $row['paid_amount']; ?>
                                            </td>
                                            <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;">
                                                <?= $row['discount']; ?>
                                            </td>
                                            <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;">
                                                <?= $row['Rs']; ?>
                                            </td>
                                            <td>
                                                <a href="bill-print.php?id=<?= $row['bill_id']; ?>"
                                                    target="blank"><button type="button"
                                                        class="btn btn-warning"><i
                                                            class="bi bi-printer-fill"></i></button></a>
                                            </td>
                                        </tr>
                                        <?php

                                            $Rs_sum += $row['Rs']; // Add the value to the sum variable
                                            $discount_sum += $row['discount'];
                                            $paid_amount_sum += $row['paid_amount'];
                                            $oldMonthBal_sum += $row['oldMonthBal'];

                                                    // Display the total sum
                                                    ?>

                                        <?php
                                                }
                                            } else {
                                                echo "No Record Found";
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="9"></td>
                                            <td style="width: 70px; font-weight: bold; font-size: 20px;">Total:</td>
                                            <td style="width: 50px; font-weight: bold; font-size: 20px; color: #0012C3;">
                                                <b><?= $oldMonthBal_sum ?></b>
                                            </td>
                                            <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;">
                                                <b><?= $paid_amount_sum ?></b>
                                            </td>
                                            <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;">
                                                <b><?= $discount_sum ?></b>
                                            </td>
                                            <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;">
                                                <b><?= $Rs_sum ?></b>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
