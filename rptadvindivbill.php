<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   include "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        ?>

<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
    $session_username = $_SESSION['username'];
    
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    include 'menu-bar.php';
    $session_username = $_SESSION['username']; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Advance Bill List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body >

    <br>

<!---------    last 5 bill print   --------------->

<div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Active Advance Bill List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <!-- <th>BillNo</th> -->
                                    <!-- <th>Date</th> -->
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <!-- <th>Discription</th> -->
                                    <!-- <th>P.Mode</th> -->
                                    <!-- <th>OldBal</th>
                                    <th>BillAmt</th>
                                    <th>Disct</th>-->
                                    <!-- <th>Rs.</th>  -->
                                    <th>Active Month</th>
                                    <th>History</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $MM = date('m', strtotime($currentDate));
                                    $YY = date('Y', strtotime($currentDate));

                                    // $query = "SELECT * FROM bill ORDER BY bill_id DESC LIMIT 5 WHERE bill_by = '$session_usernamename'";
                                    $query = "SELECT * FROM bill WHERE 
                                    DATE(due_month_timestamp) >= '$currentDate' AND
                                    (
                                        (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                        (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                    )
                                    AND adv_status = 1 AND status = 'approve' 
                                    GROUP BY stbno";
                                
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $bill)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <!-- <td style="font-weight: bold;"><?= $bill['billNo']; ?></td> -->
                                                <!-- <td style="font-weight: bold;"><?php //formatDate($bill['date']); ?></td> -->
                                                <td style="font-weight: bold;"><?= $bill['mso']; ?></td>
                                                <td style="font-weight: bold;"><?= $stbnum = $bill['stbno']; ?></td>
                                                <td style="font-weight: bold;"><?= $bill['name']; ?></td>
                                                <td style="font-weight: bold;"><?= $bill['phone']; ?></td>
                                                <!-- <td style="font-weight: bold;"><?= $bill['description']; ?></td> -->
                                                <!-- <td style="font-weight: bold;"><?= $bill['pMode']; ?></td> -->
                                                <!-- <td style="font-weight: bold; color: #0012C3;">
                                                    <?= $bill['oldMonthBal']; ?>
                                                </td>
                                                <td style="font-weight: bold; color: #05A210;">
                                                    <?= $bill['paid_amount']; ?>
                                                </td>
                                                <td style="font-weight: bold; color: #DD0581;">
                                                    <?= $bill['discount']; ?>
                                                </td> -->
                                                <!-- <td style="font-weight: bold; color: #F20000;">
                                                    <?= $bill['Rs']; ?> 
                                                </td> -->
                                                <td>
                                                    <b>
                                                        <?php
                                                            // SQL query to select data from a table
                                                            $query = "SELECT stbno FROM bill WHERE stbno = '$stbnum' AND
                                                                DATE(due_month_timestamp) >= '$currentDate' AND
                                                                (
                                                                    (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                                                    (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                                                )
                                                                AND adv_status = 1 AND status = 'approve'";

                                                            // Execute the query
                                                            $result = $con->query($query);

                                                            // Check if the query was successful
                                                            if ($result) {
                                                                echo mysqli_num_rows($result);
                                                            } else {
                                                                echo "Query execution failed: " . $con->error;
                                                            }
                                                        ?>
                                                    </b>
                                                </td>
                                                <td>
                                                    <a href="customer-history.php?search=<?= $bill['stbno']; ?>" target="_blank">
                                                        <img src="assets/arrow-up-right-from-square-solid.svg" width="25px" height="25px">
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="prtindivadvbill.php?stbnumber=<?= $bill['stbno']; ?>" target="blank">
                                                    <button type="button" class="btn btn-warning btn-lg"><i class="bi bi-printer-fill"></i></button></a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h3 align='center'> No Active Advance Bill </h3>";
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




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>
