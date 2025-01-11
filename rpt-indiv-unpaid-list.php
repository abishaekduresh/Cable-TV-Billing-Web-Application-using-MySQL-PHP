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
    <title>GTPL List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>GTPL List</h4>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>RC/DC</th>
                                    <th>MSO</th>
                                    <th>Status</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Remark</th>
                                    <th>BillAmt</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                                // $con = mysqli_connect("localhost","root","","phptutorials");
                                // require 'dbconfig.php';
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;
                                    $total_sum = 0;
                                    
                                    $query = "SELECT * FROM customer WHERE mso = 'GTPL'";
        //                             $query = "SELECT * FROM customer WHERE stbno IN (
        //     SELECT stbno
        //     FROM bill 
        //     WHERE status = 'approve' AND mso = 'GTPL' AND
        //           (YEAR(due_month_timestamp) != '$currentYear' AND MONTH(due_month_timestamp) != '$currentMonth')
        //  )";

            
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $row)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= isset($row['rc_dc']) && $row['rc_dc'] == 1 ? '<div style="color: green;">RC</div>' : '
                                                  <div style="color: red;">DC</div>'; ?></td>
                                                <td style="font-weight: bold;"><?= $row['mso']; ?></td>
                                                <td style="font-weight: bold;"><?= fetchIndivPreMonthPaidStatus($row['stbno'])['message']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['stbno']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['name']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['phone']; ?></td>
                                                <td style="font-weight: bold;"><?= $row['description']; ?></td>
                                                <td style=" font-weight: bold; color: #F20000;"><?= $row['amount']; ?></td>
                                            </tr>
                                            
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "No Record Found";
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>