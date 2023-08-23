<?php 
   session_start();
   include "dbconfig.php";
   require "component.php";
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    $session_username = $_SESSION['username'];
    

require 'dbconfig.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    include 'menu-bar.php';
}

$filtervalues ='';

?>


<!------------------------Search Model-------------------------------->

<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Customer History</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">

                                <form action="" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" pattern="[A-Za-z0-9\s]{3,}" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Enter Minimum 3 Character of STB No, Phone">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div><h6>பில் போடப்பட்ட வாடிக்கையாளர் history மட்டுமே பார்க்க இயலும்.</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>B.No</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Bill by</th>
                                    <!--<th>MSO</th>-->
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Des.tion</th>
                                    <th>pMode</th>
                                    <th>BillAmt</th>
                                    <th>Disct</th>
                                    <th>Rs</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    if(isset($_GET['search']))
                                    {
                                        $filtervalues = $_GET['search'];
                                        
                                        $query = "SELECT * FROM bill WHERE CONCAT(stbno, phone) LIKE '%$filtervalues%' ORDER BY bill_id DESC LIMIT 50";

                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $bill)
                                            {
                                                ?>
                                                <tr><form action="" method="POST">
                                                    <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $bill['billNo']; ?></td>
                                                    <td style="width: 200px; font-weight: bold; font-size: 18px; color: #007DC3;">
                                                        <?= formatDate($bill['date']); ?>
                                                    </td>
                                                    <td style="width: 100px; font-size: 17px; font-weight: bold;"><?= convertTo12HourFormat($bill['time']); ?></td>
                                                    <td style="width: 100px; font-size: 18px; font-weight: bold;"><?= $bill['bill_by']; ?></td>
                                                    <!--<td style="width: 180px; font-size: 18px; font-weight: bold;"><?= $bill['mso']; ?></td>-->
                                                    <td style="width: 180px; font-size: 18px; font-weight: bold;"><?= $bill['stbno']; ?></td>
                                                    <td style="width: 220px; font-size: 18px; font-weight: bold;"><?= $bill['name']; ?></td>
                                                    <td style="width: 120px; font-size: 18px; font-weight: bold;"><?= $bill['phone']; ?></td>
                                                    <td style="width: 150px; font-size: 18px; font-weight: bold;"><?= $bill['description']; ?></td>
                                                    <td style="width: 80px; font-size: 18px; font-weight: bold;"><?= $bill['pMode']; ?></td>
                                                    <td style="width: 50px; font-weight: bold; font-size: 20px; color: #05A210;"><?= $bill['paid_amount']; ?></td>
                                                    <td style="width: 50px; font-weight: bold; font-size: 20px; color: #DD0581;"><?= $bill['discount']; ?></td>
                                                    <td style="width: 70px; font-weight: bold; font-size: 20px; color: #F20000;"><?= $bill['Rs']; ?></td>
                                                    <td style="width: 70px; font-weight: bold; font-size: 20px; color: #db4104;"><?= $bill['status']; ?></td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
                                                </tr>
                                            <?php
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


<!---------------------- After bill --------------------->

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


        // // Retrieve checkbox values
        // $checkboxValues = isset($_POST["options"]) ? $_POST["options"] : [];

        // // Process selected checkboxes
        // foreach ($checkboxValues as $customerId) {
            // Retrieve form data
            

            
            $stbno = mysqli_real_escape_string($con, $_POST["stbno"]);
            // $mso = $_POST["mso"];
            $name = $_POST["name"];
            $phone = $_POST["phone"];
            $description = $_POST["description"];
            $pMode = $_POST["pMode"];
            // $oldMonthBal = $_POST["oldMonthBal"];
            $Rs = $_POST["paid_amount"];
            // $discount = $_POST["discount"];
            $bill_status = 'approve';
 
            $discount = 0;

            $paid_amount = 0;

            $oldMonthBal = 0;
            
            $mso = $_POST["mso"];
            
            // $paid_amount = 0;

            // https://chat.openai.com/share/6d0e9e8b-5d51-4f0c-a59c-3ee2ee77c382
            if ($currentDay === '01') {
                // Check if there is any bill entry for the next day
                $checkNextDayQuery = "SELECT billNo FROM bill WHERE DATE(date) = DATE_ADD('$currentDate', INTERVAL 1 DAY) LIMIT 1";
                $result = $con->query($checkNextDayQuery);
            
                if ($result->num_rows > 0) {
                    // There is already a bill entry for the next day, so set billNo to 1
                    $billNo = 1;
                } else {
                    // Retrieve the maximum billNo for the current day
                    $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
                    $result = $con->query($getMaxBillNoQuery);
            
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $maxBillNo = $row["maxBillNo"];
                        if ($maxBillNo < 1) {
                            $billNo = 1;
                        } else {
                            $billNo = $maxBillNo + 1;
                        }
                    } else {
                        $billNo = 1;
                    }
                }
            } else {
                // Retrieve the next billNo for the current day
                $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
                $result = $con->query($getBillNoQuery);
            
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $billNo = $row["maxBillNo"] + 1;
                } else {
                    $billNo = 1;
                }
            }
            
            $printStatus = 0;
            
            // Prepare the SQL statement
            $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, pMode, oldMonthBal, paid_amount, discount, Rs, status, printStatus) VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', '$bill_status', '$printStatus')";
            
            
            
                // // Activity Log
                // if (isset($_SESSION['id'])) {
                // // Get the user information before destroying the session
                // $userId = $_SESSION['id'];
                // $role = $_SESSION['role'];
                // $action = "Bill Successful - $pMode - $stbno";
            
                // // Insert user logout activity
                // $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$session_username', '$role', '$action')";
                // mysqli_query($con, $insertSql);
                // }
                
            // Execute the SQL statement
            if ($con->query($sql) === TRUE) {
                // Data inserted successfully
                
        // Redirect function
        function redirect($url)
        {
            echo "<script>
            setTimeout(function(){
                window.location.href = '$url';
            }, 200);
        </script>";
        }

        // Usage example
        $url = "bill-print-bulk.php"; // Replace with your desired URL bill-print-bulk.php
        redirect($url);

            } else {
                echo "Error inserting data: " . $con->error;
    // // Activity Log
    //             if (isset($_SESSION['id'])) {
    //             // Get the user information before destroying the session
    //             $userId = $_SESSION['id'];
    //             $role = $_SESSION['role'];
    //             $action = "Bill Faild - $pMode - $stbno";
            
    //             // Insert Bill Faild activity
    //             $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$session_username', '$role', '$action')";
    //             mysqli_query($con, $insertSql);
    //             }
                ?>
                <center><img src="assets/red-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
                <?php
                // break;
            }
        // }

        // Redirect after processing
        ?>
        <!--<center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="100px" height="100px"></center>-->
        <?php


    }
    
    
$query1 = "SELECT * FROM bill WHERE CONCAT(stbno, phone) LIKE '%$filtervalues%' AND MONTH('$currentDate')='$currentMonth' LIMIT 1";

$query_run1 = mysqli_query($con, $query1);

if(mysqli_num_rows($query_run1) > 0)
{
    $bill = mysqli_fetch_assoc($query_run1);
?>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <h3><center>
                            <u>Make bill after bill : <?= $session_username ?></u>
                        </center></h3>
                        <form method="POST" action="">
                            
                            <div class="mb-3">
                                <label for="mso" class="form-label">MSO :</label>
                                <input readonly type="text" name="mso" value="<?= $bill['mso']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="stbno" class="form-label">STB No:</label>
                                <input readonly type="text" name="stbno" value="<?= $bill['stbno']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Name :</label>
                                <input readonly type="text" name="name" value="<?= $bill['name']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone :</label>
                                <input readonly type="text" name="phone" value="<?= $bill['phone']; ?>" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="pMode" class="form-label">pMode :</label>
                                <select name="pMode" class="form-select fw-bold">
                                    <option value="cash" selected class="fw-bold">Cash</option>
                                    <option value="gpay" class="fw-bold">G Pay</option>
                                    <option value="credit" class="fw-bold">Credit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Remark :</label>
                                <!--<textarea name="message" id="message" class="form-control" required></textarea>-->
                                <input type="text" name="description" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="paid_amount" class="form-label">Amount :</label>
                                <input type="text" name="paid_amount" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php

                                        }
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
                                                </tr>
                                            <?php
                                        }
                                    // }

?>
<br/>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>