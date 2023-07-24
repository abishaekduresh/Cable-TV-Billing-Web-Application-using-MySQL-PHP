<?php
session_start();
include "dbconfig.php";
require 'dbconfig.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Group Bill Report</title>
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
    ?><br/><?php
    include 'admin-menu-btn.php';

?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h4>Group Bill Report</h4>
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
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="table-responsive">


                                <div class="container">
<br>

    <table class="table table-hover" border="5">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>B.No</th>
                                            <th>G.ID</th>
                                            <th>Bill By</th>
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

                                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                            
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];

                                            // $query1 = "SELECT *
                                            // FROM billgroupdetails
                                            // JOIN billgroup ON billgroup.billNo = billgroupdetails.billgroupNo
                                            // WHERE billgroup.date BETWEEN '$from_date' AND '$to_date'
                                            //   AND billgroupdetails.date BETWEEN '$from_date' AND '$to_date'
                                            //   AND billgroup.groupName = '$groupName';
                                            // "; 
                                                  
                                            $query1 = "SELECT *
                                            FROM billgroupdetails WHERE date BETWEEN '$from_date' AND '$to_date' AND status = 'approve' ;
                                            "; 

                                            $query_run1 = mysqli_query($con, $query1);                                            

                                            if (mysqli_num_rows($query_run1) > 0) {
                                                $serial_number = 1; // Initialize the serial number

                                                foreach ($query_run1 as $row1) {
                                                    ?>
                                                 <tr>
                                                     <td style="font-size: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                     <td style="font-weight: bold; font-size: 18px; color: #007DC3;"><?= $row1['date']; ?></td>
                                                     <td style="font-size: 18px; font-weight: bold;"><?= $row1['billGroupNo']; ?></td>
                                                     <td style="font-size: 18px; font-weight: bold;"><?= $row1['groupName']; ?></td>
                                                     <td style="font-size: 18px; font-weight: bold;"><?= $row1['billBy']; ?></td>
                                                     <td style="font-size: 18px; font-weight: bold;"><?= $row1['pMode']; ?></td>
                                                     <td style="font-weight: bold; font-size: 20px; color: #0012C3;"><?= $row1['oldMonthBal']; ?></td>
                                                     <td style="font-weight: bold; font-size: 20px; color: #05A210;"><?= $row1['billAmount']; ?></td>
                                                     <td style="font-weight: bold; font-size: 20px; color: #DD0581;"><?= $row1['discount']; ?></td>
                                                     <td style="font-weight: bold; font-size: 20px; color: #F20000;"><?= $row1['Rs']; ?></td>
                                                     <td>
                                                         <a href="groupBill-3inch-RePrint.php?groupID=<?= $row1['groupID']; ?>&date=<?= $row1['date']; ?>" target="blank">
                                                             <button type="button" class="btn btn-warning">
                                                                 <i class="bi bi-printer-fill"></i>
                                                             </button>
                                                         </a>
                                                     </td>
                                                 </tr>
                                        <?php
                                                
                                                    // Display the total sum
                                                    ?>

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
        </div>
    </div>
<br/>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
