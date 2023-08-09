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
    ?><br><?php
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                    <select name="category_id" id="category_id" class="form-select">
                                        <option value="select" selected disabled>Select Category</option>
                                        <?php
                                            $query = "SELECT * FROM in_ex_category";
                                            $result = mysqli_query($con, $query);
                                            $selectedValue = isset($_GET['category']) ? $_GET['category'] : ''; // Get the selected value from the URL

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $optionValueID = $row['category_id'];
                                                $optionValue = $row['category'];
                                        ?>
                                        <option value="<?php echo $optionValueID; ?>" <?php if ($optionValue === $selectedValue) echo 'selected'; ?>><?php echo $optionValue; ?></option>
                                        <?php
                                            }
                                        ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-select" name="subcategory_id" id="subcategory_id">
                                        <option value="select" selected disabled>Select Sub Category</option>
                                    </select>
                                </div>
                                <script>
                                    $(document).ready(function(){
                                        $('#category_id').change(function(){
                                            var Stdid = $('#category_id').val(); 

                                            $.ajax({
                                                type: 'POST',
                                                url: 'code-in_ex_cat_sub_fetch.php',
                                                data: {id: Stdid},  
                                                success: function(data) {
                                                    $('#subcategory_id').html(data);
                                                }
                                            });
                                        });
                                    });
                                </script>
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


                                    $filters = isset($_GET['category_id']) ? $_GET['category_id'] : '';
                                    $status_filter = isset($_GET['subcategory_id']) ? $_GET['subcategory_id'] : '';
                                    
                                    // Build the filter condition
                                    $filterCondition = '';
                                    $statusFilterCondition = '';
                                    
                                    if (!empty($filters)) {
                                        $filterIds = explode(',', $filters);
                                        $filterIds = array_map('intval', $filterIds);
                                        $filterCondition = "AND category_id IN (" . implode(",", $filterIds) . ")";
                                    }
                                    
                                    if (!empty($status_filter)) {
                                        $statusIds = explode(',', $status_filter);
                                        $statusIds = array_map('intval', $statusIds);
                                    
                                        if (count($statusIds) > 1) {
                                            $statusFilterCondition = "AND subcategory_id IN (" . implode(",", $statusIds) . ")";
                                        } else {
                                            $statusFilterCondition = "AND subcategory_id = " . $statusIds[0];
                                        }
                                    }

                                
                                    $query = "SELECT * FROM in_ex WHERE date BETWEEN '$from_date' AND '$to_date' $filterCondition $statusFilterCondition";
                                    $query_run = mysqli_query($con, $query);
                                    
                                    $in_sum = 0;
                                    $ex_sum = 0;

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
                                                <td style="font-weight: bold;">                                                
                                                <?php

                                                    $CategoryResult = $incomeExpense['category_id'];
                                                    // SQL query
                                                    $sql = "SELECT * FROM in_ex_category WHERE category_id='$CategoryResult'";

                                                    // Execute query
                                                    $result = mysqli_query($con, $sql);

                                                    // Check if there are any rows in the result
                                                    if (mysqli_num_rows($result) > 0) {
                                                        // Output data of each row
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            // Process data from each row
                                                            echo $row["category"];
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }

                                                ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                
                                                <?php

                                                    $incomeExpenseResult = $incomeExpense['subcategory_id'];
                                                    // SQL query
                                                    $sql = "SELECT * FROM in_ex_subcategory WHERE subcategory_id='$incomeExpenseResult'";

                                                    // Execute query
                                                    $result = mysqli_query($con, $sql);

                                                    // Check if there are any rows in the result
                                                    if (mysqli_num_rows($result) > 0) {
                                                        // Output data of each row
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            // Process data from each row
                                                            echo $row["subcategory"];
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }

                                                ?>
                                                </td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['remark']; ?></td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Income') {
                                                            echo '₹' . $incomeExpense['amount'];
                                                            $in_sum += $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Expense') {
                                                            echo '₹' . $incomeExpense['amount'];
                                                            $ex_sum += $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <!--</form>-->
                                            
                                            <?php

                                             // Add the value to the sum variable
                                            
                                        }
                                    }
                                    else
                                    {
                                        ?><h4 align='center'>No Record Found</h4><?php
                                    }
                                
                                    // Close the database connection
                                        $con->close();
                                
                                }
                                    ?>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td style="font-weight: bold; font-size: 20px;">Total:</td>
                                            <td style="font-size: 20px;">
                                            <!-- color: #0012C3;  color: #05A210; -->
                                                <b><?= '₹' . $in_sum ?></b>
                                            </td>
                                            <td style="font-size: 20px;">
                                                <b><?= '₹' . $ex_sum ?></b>
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

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>