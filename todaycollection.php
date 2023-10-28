<?php 
   session_start();
   include "dbconfig.php";
   require "component.php";
   include 'preloader.php';

   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    
    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'admin-menu-btn.php';
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'sub-menu-btn.php';
    }
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Today Collection</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Today Collection</h4>
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
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Remark</th>
                                    <th>OldBal</th>
                                    <th>BillAmt</th>
                                    <th>Disct</th>
                                    <th>Total</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                                // $con = mysqli_connect("localhost","root","","phptutorials");
                                require 'dbconfig.php';
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;
                                    $total_sum = 0;
                                    
                                    $query = "SELECT * FROM bill WHERE date = '$currentDate' AND status = 'approve'";
                                    $query_run = mysqli_query($con, $query);
                                    $total_sum = 0; //////////
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $row)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= $row['bill_by']; ?></td>
                                                <td style="font-weight: bold; color: #007DC3;">
                                                    <?PHP 
                                                        $current_result = splitDateAndTime(strtotime($row['due_month_timestamp'])); 
                                                        formatDate($current_result['date']);
                                                        // echo '&nbsp';
                                                        // $t=convertTo12HourFormat($current_result['time']);
                                                        // echo $t;
                                                    ?>
                                                </td>
                                                <td style="font-weight: bold;"><?= $row['stbno']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['name']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['phone']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['description']; ?></td>
                                                <td style="font-weight: bold; color: #0012C3;"><?= $row['oldMonthBal']; ?></td>
                                                <td style="font-weight: bold; color: #05A210;"><?= $row['paid_amount']; ?></td>
                                                <td style="font-weight: bold; color: #DD0581;"><?= $row['discount']; ?></td>
                                                <td style=" font-weight: bold; color: #F20000;"><?= $row['Rs']; ?></td>
                                                <td>
                                                    <a href="prtindivbillrpt.php?billid=<?= $row['bill_id']; ?>" target="blank"><button type="button" class="btn btn-warning"><i class="bi bi-printer-fill"></i></button></a>
                                                </td>
                                            </tr>
                                            <?php 
                                            
                                            $total_sum += $row['Rs'];
                                            $discount_sum += $row['discount'];
                                            $paid_amount_sum += $row['paid_amount'];

                                            ?>
                                            
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "No Record Found";
                                    }
                            ?>
                                            <tr>
                                               <td colspan="7"></td>
                                               <td> <b>Grand Total :</td>
                                               <td style="font-weight: bold; color: #05A210;"> <b><?= $paid_amount_sum ?></td>
                                               <td style="font-weight: bold; color: #DD0581;"> <b><?= $discount_sum ?></td>
                                               <td style=" font-weight: bold; color: #F20000;"> <b><?= $total_sum ?></td>                                               
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>