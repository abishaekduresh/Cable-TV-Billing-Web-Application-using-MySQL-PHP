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
    <title>bill-filter-by-date</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    

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
                                        <label>From Bill Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } else { echo $currentDate; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Bill Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } else { echo $currentDate; } ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Click to Search</label> <br>
                                      <button type="submit" class="btn btn-primary">Search</button>
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
                                    
                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];

                                    $query = "SELECT * FROM bill WHERE DATE(due_month_timestamp) BETWEEN '$from_date' AND '$to_date' AND status = 'approve'";
                                    $query_run = mysqli_query($con, $query);
                                    $total_sum = 0; //////////
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 0;

                                        foreach($query_run as $row)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= $row['bill_by']; ?></td>
                                                <td style="font-weight: bold; color: #007DC3;"><?= formatDate($row['date']); ?></td>
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
                                            
                                            // $total_sum += $row['Rs'];
                                            // $discount_sum += $row['discount'];
                                            // $paid_amount_sum += $row['paid_amount'];

                                            ?>
                                            
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "No Record Found";
                                    }
                                }
                            ?>
                                            <!-- <tr>
                                               <td colspan="7"></td>
                                               <td> <b>Grand Total :</td>
                                               <td> <b><?= $discount_sum ?></td>
                                               <td> <b><?= $paid_amount_sum ?></td>
                                               <td> <b><?= $total_sum ?></td>                                               
                                               <td></td>
                                            </tr> -->
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