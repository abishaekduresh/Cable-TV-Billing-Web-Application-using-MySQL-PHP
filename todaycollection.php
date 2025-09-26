<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Indiv Bill Collection Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            /* CSS to remove underline and change color for <a> tags */
            a.link {
                text-decoration: none; /* Remove underline */
                color: white; /* Change color to red */
            }

/* Add this CSS to your styles.css or inside a <style> block in your HTML */
.checkbox-container {
    display: flex;
    flex-wrap: wrap; /* Allow wrapping of elements */
}

.checkbox-container label {
    width: 33.33%; /* Each checkbox will take up 33.33% of the row, resulting in 3 columns */
    box-sizing: border-box; /* Ensure padding and margins are included in the width */
    margin-bottom: 10px; /* Space between rows */
}

.checkbox-container label:nth-child(3n) {
    margin-right: 0; /* Remove the margin for every third checkbox (last in each row) */
}

        </style>
    </head>

    <body>

<?php

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h4>Indiv Bill Collection Report</h4>
                        </div>
                        <div class="card-body">

                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>From Col Date</label>
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
                                            <label>To Col Date</label>
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
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>From Bill No.</label>
                                            <input type="number" name="from_billno" value="<?php if (isset($_GET['from_billno'])) {
                                                echo $_GET['from_billno'];
                                            }?>" class="form-control" step="1">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>To Bill No.</label>
                                            <input type="number" name="to_billno" value="<?php if (isset($_GET['to_billno'])) {
                                                echo $_GET['to_billno'];
                                            }?>" class="form-control" step="1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
<b>
    
                                                <label><u>Bill By :</u></label><br>
                                            <?php
                                            // Your database connection code should be here
                                            // Assuming $con is the established database connection

                                            $sql = "SELECT * FROM user WHERE status = 1"; // Replace 'user' with your actual table name
                                            $result = $con->query($sql);

                                            // Step 3: Check if any data was fetched and loop through the results
                                            if ($result->num_rows > 0) {
                                                // Start the container for the checkbox layout
                                                echo '<div class="checkbox-container">';
                                                
                                                // Step 4: Loop through each record and generate HTML with checkboxes
                                                $counter = 0; // Counter to track columns
                                                while ($row = $result->fetch_assoc()) {
                                                    // Assuming 'username' is the value and 'name' is the label text
                                                    echo '<label><input type="checkbox" name="filter[]" value="' . htmlspecialchars($row['username']) . '">';
                                                    echo htmlspecialchars($row['name']) . '</label>';

                                                    // After every third checkbox, insert a line break (to create three columns per row)
                                                    $counter++;
                                                    if ($counter % 3 == 0) {
                                                        echo '<br>';
                                                    }
                                                }
                                                
                                                // End the container for the checkbox layout
                                                echo '</div>';
                                            } else {
                                                echo "No records found.";
                                            }
                                            ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label><u>MSO :</u></label>
                                                    <br>
                                                    <div class="col-md">
                                                        <label>
                                                            <input type="radio" name="mso_filter" value="ALL" 
                                                            <?php echo (isset($_GET['mso_filter']) && $_GET['mso_filter'] == 'ALL') ? 'checked' : 'checked'; ?>> 
                                                            ALL
                                                        </label>
                                                    </div>
                                                    <div class="col-md">
                                                        <label>
                                                            <input type="radio" name="mso_filter" value="VK" 
                                                            <?php echo (isset($_GET['mso_filter']) && $_GET['mso_filter'] == 'VK') ? 'checked' : ''; ?>> 
                                                            VK DIGITAL
                                                        </label>
                                                    </div>
                                                    <div class="col-md">
                                                        <label>
                                                            <input type="radio" name="mso_filter" value="GTPL" 
                                                            <?php echo (isset($_GET['mso_filter']) && $_GET['mso_filter'] == 'GTPL') ? 'checked' : ''; ?>> 
                                                            GTPL
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label><u>Bill Status :</u></label>
                                                    <br>
                                                    <div class="col-md">
                                                        <label>
                                                            <input type="radio" name="status_filter" value="approve" 
                                                            <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'approve') ? 'checked' : 'checked'; ?>> 
                                                            Approve
                                                        </label>
                                                    </div>
                                                    <div class="col-md">
                                                        <label>
                                                            <input type="radio" name="status_filter" value="cancel" 
                                                            <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'cancel') ? 'checked' : ''; ?>> 
                                                            Cancel
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <label><u>Bill Payment Mode :</u></label>
                                            <br>
                                            <label><input type="checkbox" name="pMode_filter[]" value="cash">
                                                Cash</label>
                                            <label><input type="checkbox" name="pMode_filter[]" value="Gpay">
                                                GPay</label>
                                            <label><input type="checkbox" name="pMode_filter[]" value="paytm">
                                                Paytm</label>
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
                                <table class="table table-hover" border="5" style="white-space: nowrap;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Bill by</th>
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
                                                $billnoFilterCondition = "AND billNo BETWEEN '$from_billno' AND '$to_billno'";
                                            }


                                            // Retrieve selected filter options
                                            $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                            $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : '';
                                            $mso_filter = isset($_GET['mso_filter']) ? $_GET['mso_filter'] : '';
                                            $pMode_filter = isset($_GET['pMode_filter']) ? $_GET['pMode_filter'] : array();


                                            // Build the filter condition
                                            $filterCondition = '';
                                            $statusFilterCondition = '';
                                            $pModeFilterCondition = '';
                                            $msoFilterCondition = '';

                                            if (!empty($mso_filter) && $mso_filter != 'ALL') {
                                                $msoFilterCondition = "AND mso = '$mso_filter'";
                                            }

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
                                                    $pModeFilterCondition = "AND pMode IN ('" . implode("','", $pMode_filter) . "')";
                                                } else {
                                                    $pModeFilterCondition = "AND pMode = '$pMode_filter'";
                                                }
                                            }


                                            $query = "SELECT * FROM bill WHERE DATE(date) BETWEEN '$from_date' AND '$to_date' $billnoFilterCondition $filterCondition $statusFilterCondition $pModeFilterCondition $msoFilterCondition";
                                            // $query .= "ORDER BY bill_id DESC";

                                            $query_run = mysqli_query($con, $query);
                                            $Rs_sum = 0;
                                            $discount_sum = 0;
                                            $paid_amount_sum = 0;
                                            $oldMonthBal_sum = 0;

                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1; // Initialize the serial number

                                                foreach ($query_run as $row) {
                                                    ?>
                                        <tr style="background-color: <?= $row['adv_status'] == 1 ? '#dfb9fa' : '' ?>">
                                            <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                            <td style="font-weight: bold;"><?= $row['bill_by']; ?></td>
                                            <td style="width: 220px; font-weight: bold; color: #007DC3;"><?= formatDate($row['date']);  ?> <?= $row['time'] ?></td>
                                            <td style="width: 220px; font-weight: bold; color: #007DC3;">
                                                        <?php 
                                                            $current_result = splitDateAndTime(strtotime($row['due_month_timestamp'])); 
                                                            formatDate($current_result['date']);
                                                            // echo '&nbsp';
                                                            // $t=convertTo12HourFormat($current_result['time']);
                                                            // echo $t;
                                                        ?> <?= $row['time'] ?>
                                            </td>
                                            <td style="font-weight: bold;"><?= $row['billNo']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['mso']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['stbno']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['name']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['phone']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['description']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['pMode']; ?></td>
                                            <td style="font-weight: bold; color: #0012C3;">
                                                <?= $row['oldMonthBal']; ?>
                                            </td>
                                            <td style="font-weight: bold; color: #05A210;">
                                                <?= $row['paid_amount']; ?>
                                            </td>
                                            <td style="font-weight: bold; color: #DD0581;">
                                                <?= $row['discount']; ?>
                                            </td>
                                            <td style="font-weight: bold; color: #F20000;">
                                                <?= $row['Rs']; ?>
                                            </td>
                                            <td>
                                                <!-- <a href="bill-print.php?id=<?= $row['bill_id']; ?>"
                                                    target="blank"><button type="button"
                                                        class="btn btn-warning"><i
                                                            class="bi bi-printer-fill"></i></button></a> -->
                                                <a href="prtindivbillrpt.php?billid=<?= $row['bill_id']; ?>"
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
                                            <td colspan="10"></td>
                                            <td style="font-weight: bold;">Total:</td>
                                            <td style="font-weight: bold; color: #0012C3;">
                                                <b><?= $oldMonthBal_sum ?></b>
                                            </td>
                                            <td style="font-weight: bold; color: #05A210;">
                                                <b><?= $paid_amount_sum ?></b>
                                            </td>
                                            <td style="font-weight: bold; color: #DD0581;">
                                                <b><?= $discount_sum ?></b>
                                            </td>
                                            <td style="font-weight: bold; color: #F20000;">
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
<br>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
