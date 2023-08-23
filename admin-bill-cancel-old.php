<?php 
   session_start();
   include "dbconfig.php";
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
    <title>Cancel Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
<?php

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Cancel Bill Dashboard</h4>
                    </div>
                    <div class="card-body">
                    
                        <form action="" method="GET">
                            <div class="row">
                                <!--<div class="col-md-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <label>From Date</label>-->
                                <!--        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="col-md-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <label>To Date</label>-->
                                <!--        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">-->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } else { echo $currentDate; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } else { echo $currentDate; } ?>" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <!--<label>Click to Filter</label>--> <br>
                                      <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </div>
                                <!--<div class="col-md-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <label><input type="checkbox" name="filter[]" value="kannika" <?php if(isset($_GET['filter']) && in_array('23E001', $_GET['filter'])) echo 'checked'; ?>> Kannika</label>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="duresh" <?php if(isset($_GET['filter']) && in_array('23A002', $_GET['filter'])) echo 'checked'; ?>> Duresh</label>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="baskarraj" <?php if(isset($_GET['filter']) && in_array('23A001', $_GET['filter'])) echo 'checked'; ?>> Baskar Raj</label><br/>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="cancel" <?php if(isset($_GET['filter']) && in_array('cancel', $_GET['filter'])) echo 'checked'; ?>> Cancell Bill</label>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="approve" <?php if(isset($_GET['filter']) && in_array('approve', $_GET['filter'])) echo 'checked'; ?>> Approve</label>-->
                                        <!-- Add more checkboxes for other filter options -->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="form-group">
                                    <label style="font-weight: bold;"><u>Bill By :</u></label><br>
                                    <label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="23A002"> Duresh</label>
                                    <label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="23A001"> Baskar Raj</label>
                                    <label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="23E001"> Kannika</label>
                                    <br>
                                    <label style="font-weight: bold;"><u>Bill Status :</u></label>
                                    <br>
                                    <label style="font-weight: bold;"><input type="checkbox" name="status_filter[]" value="cancel"> Cancel</label>
                                    <label style="font-weight: bold;"><input type="checkbox" name="status_filter[]" value="approve" checked> Approve</label>
                                    <!-- Add more checkboxes for other filter options -->
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
                                    <th>Bill No</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Remark</th>
                                    <th>OldBal</th>
                                    <th>BillAmt</th>
                                    <th>Disct</th>
                                    <th>Rs</th>
                                    <th colspan="2"><center>Cancel Bill</center></th>
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
                                
                                
                                    // Build the filter condition
                                    $filterCondition = '';
                                    $statusFilterCondition = '';
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

                                
                                    $query = "SELECT * FROM bill WHERE date BETWEEN '$from_date' AND '$to_date' $filterCondition $statusFilterCondition";
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
                                            <form action="admin-code-bill-cancel.php" method="POST">
                                            <tr>
                                                    <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['bill_by']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['billNo']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['mso']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['stbno']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['name']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['phone']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['description']; ?></td>
                                                    <td style="width: 40px; font-weight: bold; font-size: 20px; color: #0012C3;">
                                                        <?= $row['oldMonthBal']; ?>
                                                    </td>
                                                    <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;"><?= $row['paid_amount']; ?></td>
                                                    <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;"><?= $row['discount']; ?></td>
                                                    <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;"><?= $row['Rs']; ?></td>
                                                    <td>
                                                        <select style="font-weight: bold;" name="selectedValue" class="form-select bg-warning text-dark">
                                                            <option style="font-weight: bold;" value="approve" <?php if ($row['status'] === 'approve') { echo 'selected'; } ?>>Approve</option>
                                                            <option style="font-weight: bold;" value="cancel" <?php if ($row['status'] === 'cancel') { echo 'selected'; } ?>>Cancel</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <!-- Code POST Method Data -->
                                                        <input type="hidden" name="billNo" value="<?= $row['billNo']; ?>">
                                                        <input type="hidden" name="date" value="<?= $row['date']; ?>">
                                                        <input type="hidden" name="stbno" value="<?= $row['stbno']; ?>">
                                                        <input type="hidden" name="Rs" value="<?= $row['Rs']; ?>">
                                                        <input type="hidden" name="bill_id" value="<?= $row['bill_id']; ?>">
                                                        <!-- <button type="submit" class="btn btn-danger btn-sm" style="font-weight: bold;"> -->
                                                        <button type="button" class="btn btn-primary btn-sm" style="font-weight: bold;" data-toggle="modal" data-target="#exampleModal">
                                                            Submit
                                                        </button>
                                                    </td>
                                                 </tr>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <p>Are you sure to cancel the Bill ?</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-danger">Submit</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                <td colspan="7"></td>
                                                <td style="font-weight: bold; font-size: 19px;">Total :</td>
                                                <td style="width: 50px; font-weight: bold; font-size: 20px; color: #0012C3;"><?= $oldMonthBal_sum ?></td>
                                                <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;"><?= $paid_amount_sum ?></td>
                                                <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;"><?= $discount_sum ?></td>
                                                <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;"><?= $Rs_sum ?></td>                                                
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