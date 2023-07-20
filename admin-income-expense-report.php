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
    <title>Income Expense Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

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
                        <h4>Income Expense Report</h4>
                    </div>
                    <div class="card-body">
                    
                        <form action="" method="GET">
                            <div class="row">
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
                                <div class="form-group">
                                    <label><u>Bill By :</u></label><br>
                                    <label><input type="checkbox" name="filter[]" value="23A002"> Duresh</label>
                                    <label><input type="checkbox" name="filter[]" value="23A001"> Baskar Raj</label>
                                    <label><input type="checkbox" name="filter[]" value="23E001"> Kannika</label>
                                    <br>
                                    <label><u>Bill Status :</u></label>
                                    <br>
                                    <label><input type="checkbox" name="status_filter[]" value="Income"> Income</label>
                                    <label><input type="checkbox" name="status_filter[]" value="Expense"> Expense</label>
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
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Username</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Remark</th>
                                    <th>Income</th>
                                    <th>Expence</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                                require 'dbconfig.php';

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
                                        $filterCondition = "AND username IN ('" . implode("','", $filters) . "')";
                                    }
                                    
                                    if (!empty($status_filter)) {
                                        if (is_array($status_filter)) {
                                            $statusFilterCondition = "AND type IN ('" . implode("','", $status_filter) . "')";
                                        } else {
                                            $statusFilterCondition = "AND type = '$status_filter'";
                                        }
                                    }

                                
                                    $query = "SELECT * FROM incomeExpence WHERE date BETWEEN '$from_date' AND '$to_date' $filterCondition $statusFilterCondition";
                                    $query_run = mysqli_query($con, $query);
                                    

                                    if(mysqli_num_rows($query_run) > 0)
                                    {   
                                        $serial_number = 1; // Initialize the serial number

                                        foreach($query_run as $incomeExpense)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <!--<td style="font-weight: bold;"><?= $incomeExpense['type']; ?></td>-->
                                                <td style="font-weight: bold;"><?= $incomeExpense['date']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['time']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['username']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['category']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['subCategory']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['remark']; ?></td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Income') {
                                                            echo $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Expense') {
                                                            echo $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <!--</form>-->
                                            
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