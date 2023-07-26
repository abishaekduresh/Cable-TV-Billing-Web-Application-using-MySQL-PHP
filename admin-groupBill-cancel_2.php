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
        <title>Group Bill Cancel</title>
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
                            <h4>Group Bill Cancel</h4>
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

                                    <div class="col-md-4">
                                        <div class="form-group">

                                    <label for="selectBox" class="form-label">Select an Group: *</label>
                                    <select style="font-weight: bold;" name="groupID" class="form-select" required>
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                
                                                $query = "SELECT id,groupName FROM groupinfo WHERE groupName != 'Indiv' AND groupName != 'ALL'";
                                                $result = mysqli_query($con, $query);
                                                
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $optionValueID = $row['id'];
                                                    $optionValue = $row['groupName'];
                                                    ?>
                                                    <option value="<?php echo $optionValueID; ?>"><?php echo $optionValue; ?></option>
                                                    <?php
                                                }
                                                
                                                ?>
                                    </select>
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
  <div class="row">
    <div class="col">

    <table class="table table-hover" border="5">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <!-- <th>Bill by</th> -->
                                            <th>Date</th>
                                            <th>B.No</th>
                                            <!-- <th>Group</th> -->
                                            <th>MSO</th>
                                            <th>STB No</th>
                                            <th>Name</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                    
                                        // $discount_sum = '';
                                        // $billAmount_sum = '';
                                        // $Rs_sum = '';
                                        // $oldMonthBal = '';

                                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                            
                                            $groupID = $_GET['groupID'];
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];

                                            // $query = "SELECT *
                                            // FROM billgroupdetails
                                            // JOIN billgroup ON billgroup.billNo = billgroupdetails.billgroupNo
                                            // WHERE billgroup.date BETWEEN '$from_date' AND '$to_date'
                                            //   AND billgroupdetails.date BETWEEN '$from_date' AND '$to_date'
                                            //   AND billgroup.groupName = '$groupName' AND billgroupdetails.groupName = '$groupName'
                                            //   AND billgroupdetails.status = 'approve' AND billgroup.status = 'approve'";


                                            $query = "SELECT *
                                            FROM billgroup WHERE groupID = '$groupID' AND date BETWEEN '$from_date' AND '$to_date'
                                                AND status = 'approve'
                                            "; 
                                  


                                            $query_run = mysqli_query($con, $query);

                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1; // Initialize the serial number

                                                foreach ($query_run as $row) {
                                                    ?>
                                        <tr>
                                            <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                            <!-- <td style="width: 100px; font-size: 18px; font-weight: bold;"><?= $row['billBy']; ?></td> -->
                                            <td style="width: 40px; font-weight: bold; font-size: 18px; color: #007DC3;"><?= $row['date']; ?></td>
                                            <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row['billNo']; ?></td>
                                            <!-- <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row['groupName']; ?></td> -->
                                            <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row['mso']; ?></td>
                                            <td style="width: 140px; font-size: 18px; font-weight: bold;"><?= $row['stbNo']; ?></td>
                                            <td style="width: 120px; font-size: 18px; font-weight: bold;"><?= $row['name']; ?></td>
                                            <td style="width: 100px; font-size: 18px; font-weight: bold;"><?= $row['remark']; ?></td>
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
    <div class="col">

    <table class="table table-hover" border="5">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>B.No</th>
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
                                            
                                            $groupID = $_GET['groupID'];
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
                                            FROM billgroupdetails WHERE groupID = '$groupID' AND date BETWEEN '$from_date' AND '$to_date' AND status = 'approve' ;
                                            "; 

                                            $query_run1 = mysqli_query($con, $query1);

                                            // if (mysqli_num_rows($query_run1) > 0) {
                                            //     $row1 = mysqli_fetch_assoc($query_run1); // Fetch the first row
                                                
                                            //     $serial_number = 1; // Initialize the serial number
                                            //     ?>
                                            <!-- //     <tr>
                                            //         <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                            //         <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row1['pMode']; ?></td>
                                            //         <td style="width: 50px; font-weight: bold; font-size: 20px; color: #0012C3;"><?= $row1['oldMonthBal']; ?></td>
                                            //         <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;"><?= $row1['billAmount']; ?></td>
                                            //         <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;"><?= $row1['discount']; ?></td>
                                            //         <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;"><?= $row1['Rs']; ?></td>
                                            //         <td>
                                            //             <a href="../print/groupBill-3inch-print.php?groupName=<?= $row1['groupName']; ?>&from_date=<?= $from_date ?>&to_date=<?= $to_date ?>" target="blank">
                                            //                 <button type="button" class="btn btn-warning">
                                            //                     <i class="bi bi-printer-fill"></i>
                                            //                 </button>
                                            //             </a>
                                            //         </td>
                                            //     </tr> -->

                                                 <?php
                                            // } else {
                                            //     echo "No Record Found";
                                            // }
                                            

                                            if (mysqli_num_rows($query_run1) > 0) {
                                                $serial_number = 1; // Initialize the serial number

                                                foreach ($query_run1 as $row1) {
                                                    ?>
                                                <form action="admin-code-groupBill-cancel.php" method="POST">
                                                 <tr>
                                                     <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                     <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row1['billBy']; ?></td>
                                                     <td style="width: 120px; font-weight: bold; font-size: 18px; color: #007DC3;"><?= $row1['date']; ?></td>
                                                     <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $row1['billGroupNo']; ?></td>
                                                     <td style="width: 40px; font-size: 18px; font-weight: bold;"><?= $row1['pMode']; ?></td>
                                                     <td style="width: 50px; font-weight: bold; font-size: 20px; color: #0012C3;"><?= $row1['oldMonthBal']; ?></td>
                                                     <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;"><?= $row1['billAmount']; ?></td>
                                                     <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;"><?= $row1['discount']; ?></td>
                                                     <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;"><?= $row1['Rs']; ?></td>
                                                     <td>
<select style="font-weight: bold;" name="selectedValue" class="form-select bg-warning text-dark">
  <option style="font-weight: bold;" value="approve" <?php if ($row1['status'] === 'approve') { echo 'selected'; } ?>>Approve</option>
  <option style="font-weight: bold;" value="cancel" <?php if ($row1['status'] === 'cancel') { echo 'selected'; } ?>>Cancel</option>
</select>

                                                    </td>
                                                    <td>
                                                        <!--<input type="hidden" name="billNo" value="<?= $row1['billNo']; ?>">-->
                                                        <input type="hidden" name="date" value="<?= $row1['date']; ?>">
                                                        <input type="hidden" name="groupID" value="<?= $row1['groupID']; ?>">
                                                        <!-- Assign 'bill_id' value to the hidden input field for 'bill_no' -->
                                                        <button type="submit" class="btn btn-danger btn-sm" style="font-weight: bold;">
                                                            Submit
                                                        </button>
                                                    </td>
                                                 </tr>
                                            </form>
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
