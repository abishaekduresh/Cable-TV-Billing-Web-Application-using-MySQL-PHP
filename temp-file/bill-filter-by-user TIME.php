<?php 
   session_start();
   include "dbconfig.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_user = $_SESSION['username']; ?>
        
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Session User Data Fetching -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing Filter by User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<?php include 'menu-bar.php'?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-5">
                    <div class="card-header">
                        <h4>Billing Filter by User</h4>
                    </div>
                    <div class="card-body">
                    
                        <form action="" method="GET">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>From time</label>
                                        <input type="time" name="from_time" value="<?php if(isset($_GET['from_time'])){ echo $_GET['from_time']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>To time</label>
                                        <input type="time" name="to_time" value="<?php if(isset($_GET['to_time'])){ echo $_GET['to_time']; } ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <!-- <label>Click to Filter</label> --> <br> 
                                      <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <a href="/ctv/bill-filter-by-all.php"><button type="submit" class="btn btn-primary">Refresh</button></a>
            

                <div class="card mt-4">
                    <div class="card-body">
                        <table class="table table-borderd">
                            <thead>
                                <tr>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Discount</th>
                                    <th>Paid Amount</th>
                                    <th>Total</th>
                                    <th>Bill</th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                                // $con = mysqli_connect("localhost","root","","phptutorials");
                                require 'dbconfig.php';

                                if(isset($_GET['from_date']) && isset($_GET['to_date']) && isset($_GET['from_time']) && isset($_GET['to_time']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];
                                    $from_time = $_GET['from_time'];
                                    $to_time = $_GET['to_time'];

                                    // $query = "SELECT * FROM bill WHERE date BETWEEN '$from_date' AND '$to_date' 
                                    //             AND time BETWEEN '$from_time' AND '$to_time' AND bill_by = '$session_user' ";

                                    $query = "SELECT * FROM bill WHERE bill_by = '$session_user'";

                                        // Check if date filter is provided
                                        if (!empty($from_date) && !empty($to_date)) {
                                            // Add the date filter to the query
                                            $query .= " AND date BETWEEN '$from_date' AND '$to_date'";
                                        }

                                        // Check if time filter is provided
                                        if (!empty($from_time) && !empty($to_time)) {
                                            // Add the time filter to the query
                                            $query .= " AND time BETWEEN '$from_time' AND '$to_time'";
                                        }

                                    $query_run = mysqli_query($con, $query);
                                    $total_sum = 0; //////////
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $row) 
                                        // $total_sum += $row['total'];
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $row['stbno']; ?></td>
                                                <td><?= $row['name']; ?></td>
                                                <td><?= $row['phone']; ?></td>
                                                <td><?= $row['description']; ?></td>
                                                <td><?= $row['discount']; ?></td>
                                                <td><?= $row['paid_amount']; ?></td>
                                                <td><?= $row['total']; ?></td>
                                                <td>
                                                    <a href="/ctv/bill-pdf.php?id=<?= $row['bill_id']; ?>" target="blank"><button type="button" class="btn btn-warning"><i class="bi bi-printer-fill"></i></button></a>
                                                </td>
                                            </tr>
                                            <?php 
                                            
                                            $total_sum += $row['total']; // Add the value to the sum variable
                                            $discount_sum += $row['discount'];
                                            $paid_amount_sum += $row['paid_amount'];
                                        }
                                            // Display the total sum
                                            ?>
                                            <tr>
                                                <td colspan="3"></td>
                                                <td> <b>Grand Total :</td>
                                                <td> <b><?= $discount_sum ?></td>
                                                <td> <b><?= $paid_amount_sum ?></td>
                                                <td> <b><?= $total_sum ?></td>                                                
                                                <td></td>
                                            </tr>
                                            <?php
                                        
                                    }
                                    else
                                    {
                                        echo "No Record Found";
                                    }
                                }

                                /// Comma Separated file ///

                                // SQL query to fetch data from the database
                                $sql = "SELECT stbno FROM bill";
                                $result = $con->query($sql);

                                // Check if query execution was successful
                                if ($result) {
                                    if ($result->num_rows > 0) {
                                        // File path to save the data
                                        $filepath = "data.txt";

                                        // Open the file in write mode
                                        $file = fopen($filepath, "w");


                                        // Write the column headers to the file
                                        // $headers = array();
                                        // fwrite($file, implode(",", $headers) . "\n");

                                        // Fetch each row and write it to the file
                                        while ($row = $result->fetch_assoc()) {
                                            // Convert the row values to a comma-separated string
                                            $data = implode(",", $row);

                                            // Write the data to the file
                                            fwrite($file, $data . ",\n");
                                        }


                                        // Close the file
                                        fclose($file);

                                        echo "Data has been successfully written to data.txt file.";
                                    } else {
                                        echo "No records found in the database.";
                                    }
                                } else {
                                    echo "Error executing the query: " . $con->error;
                                }
                                
                            ?>

                                <h1>Download Data</h1>
                                    <p>Click the button below to download the data file:</p>

                                    <a href="data.txt" download>Download File</a>

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php }else{
	header("Location: index.php");
} ?>