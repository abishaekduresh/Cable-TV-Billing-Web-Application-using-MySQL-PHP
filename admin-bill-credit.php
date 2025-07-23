<?php 
   session_start();
   include "dbconfig.php";
   require "component.php";
   include 'preloader.php';
   
//    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
        $session_username = $_SESSION['username']; 
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credit Bill Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
<?php 

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Credit Bill Dashboard
                            <a href="prtindivcreditbillbulk.php" target="blank"><button type="button" class="btn btn-primary float-end" >
                                <b>Print Pending Credit Bill</b>
                            </button></a> &nbsp;
                            <a href="prtindivcreditbilllist.php" target="blank"><button type="button" class="btn btn-dark float-end" >
                                <b>Print Pending Credit Bill List</b>
                            </button></a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Bill Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } else { echo '2023-06-01'; } ?>" class="form-control">
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
                                        <label>Phone</label>
                                        <input type="number" name="phone" class="form-control" autocomplete=off>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <!--<label>Click to Filter</label>--> <br>
                                      <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label style="font-weight: bold;"><u>Bill By :</u></label>
                                    <br>
                                    <label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="23A002"> Duresh</label>
                                    <label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="23A001"> Baskar Raj</label>
                                    <label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="23E005"> Divya</label>
                                    <br>
                                    <label style="font-weight: bold;"><u>Bill Status :</u></label>
                                    <br>
                                    <label style="font-weight: bold;"><input type="checkbox" name="status_filter[]" value="credit" checked> Credit</label>
                                    <!--<label><input type="checkbox" name="status_filter[]" value="approve"> Approve</label>-->
                                    <!-- Add more checkboxes for other filter options -->
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card mt-12">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <!--<th>Bill by</th>-->
                                    <th>Col Date</th>
                                    <th>Bill Date</th>
                                    <th>Bill No</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>His</th>
                                    <th>Phone</th>
                                    <th>Remarks</th>
                                    <th>oldBal</th>
                                    <th>BillAmt</th>
                                    <th>Dist</th>
                                    <th>Rs</th>
                                    <th colspan="2"><center>Credit Bill</center></th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                                // $con = mysqli_connect("localhost","root","","phptutorials");
                                require 'dbconfig.php';
$discount_sum = '';
$paid_amount_sum = '';
$Rs_sum = '';
$oldMonthBal_sum = '';
                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];

                                    // Retrieve selected filter options
                                    $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array();
                                    $phone_filter = isset($_GET['phone']) ? $_GET['phone'] : '';
                                
                                
                                    // Build the filter condition
                                    $filterCondition = '';
                                    $statusFilterCondition = '';
                                    if (!empty($filters)) {
                                        $filterCondition = "AND bill_by IN ('" . implode("','", $filters) . "')";
                                    }
                                    
                                    if (!empty($status_filter)) {
                                        if (is_array($status_filter)) {
                                            $statusFilterCondition = "AND pMode IN ('" . implode("','", $status_filter) . "')";
                                        } else {
                                            $statusFilterCondition = "AND pMode = '$status_filter'";
                                        }
                                    }
                                    
                                    $phoneFilterCondition = '';
                                    if (!empty($phone_filter)) {
                                        $phoneFilterCondition = "AND phone = '$phone_filter'";
                                    }

                                
                                    $query = "SELECT * FROM bill WHERE pMode = 'credit' AND DATE(due_month_timestamp) BETWEEN '$from_date' AND '$to_date' AND status ='approve' $filterCondition $statusFilterCondition $phoneFilterCondition";
                                    $query_run = mysqli_query($con, $query);
                                    
                                    $Rs_sum = 0; 
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;
                                    $oldMonthBal_sum = 0;

                                    if(mysqli_num_rows($query_run) > 0)
                                    {   
                                        $serial_number = 1; // Initialize the serial number

                                        foreach($query_run as $row)
                                        {
                                            ?>
                                            <tr>
                                                    <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                    <!--<td style="font-weight: bold;"><?= $row['bill_by']; ?></td>-->
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
                                                    <td style="font-weight: bold;"><?= $row['billNo']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['mso']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['stbno']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['name']; ?></td>
                                                    <td> 
                                                        <a href="customer-history.php?search=<?= $row['stbno']; ?>" target="_blank">
                                                            <img src="assets/arrow-up-right-from-square-solid.svg" width="20px" height="20px">
                                                        </a>
                                                    </td>
                                                    <td style="font-weight: bold;"><?= $row['phone']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['description']; ?></td>
                                                    <td style="font-weight: bold; color: #0012C3;">
                                                        <?= $row['oldMonthBal']; ?>
                                                    </td>
                                                    <td style="font-weight: bold; color: #05A210;"><?= $row['paid_amount']; ?></td>
                                                    <td style="font-weight: bold; color: #DD0581;"><?= $row['discount']; ?></td>
                                                    <td style="font-weight: bold; font-weight: bold; color: #F20000;"><?= $row['Rs']; ?></td>
                                                <form action="admin-code-bill-credit.php" method="POST">
                                                    <td>                                                        
                                                        <div style="width:80px" class="input-group">
                                                            <select class="form-select p-1 mb-0 bg-warning text-dark" name="selectedValue">
                                                                <option value="cash" <?php if ($row['pMode'] === 'cash') { echo 'selected'; } ?>>Cash</option>
                                                                <option value="gpay" <?php if ($row['pMode'] === 'gpay') { echo 'selected'; } ?>>G Pay</option>
                                                                <option value="paytm" <?php if ($row['pMode'] === 'paytm') { echo 'selected'; } ?>>Paytm</option>
                                                                <option value="credit" <?php if ($row['pMode'] === 'credit') { echo 'selected'; } ?>>Credit</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="bill_no" value="<?= $row['bill_id']; ?>">
                                                        <input type="hidden" name="stbno" value="<?= $row['stbno']; ?>">
                                                        <!-- Assign 'bill_id' value to the hidden input field for 'bill_no' -->
                                                        <button type="submit" class="btn btn-danger btn-sm" style="font-weight: bold;" >
                                                            Submit
                                                        </button>
                                                    </td>
                                                 </tr>

                                            </form>
                                            <?php 
                                            
                                            $Rs_sum += $row['Rs']; // Add the value to the sum variable
                                            $discount_sum += $row['discount'];
                                            $paid_amount_sum += $row['paid_amount'];
                                            $oldMonthBal_sum += $row['oldMonthBal'];
                                        
                                            // Display the total sum
                                            ?>
                                            
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "No Record Found";
                                    }
                                
                                    // Close the database connection
                                        $con->close();
                                
                                }
                                    ?>
                                            <tr>
                                                <td colspan="9"></td>
                                                <td style="font-weight: bold; font-weight: bold;"> <b>Total :</td>
                                                <td style="font-weight: bold; color: #0012C3;"> <b><?= $oldMonthBal_sum ?></b></td>
                                                <td style="font-weight: bold; color: #05A210;"> <b><?= $paid_amount_sum ?></td>
                                                <td style="font-weight: bold; color: #DD0581;"> <b><?= $discount_sum ?></td>
                                                <td style="font-weight: bold; font-weight: bold; color: #F20000;"> <b><?= $Rs_sum ?></td>                                                
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
<br/>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>