<?php
session_start();
require "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    

    if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
        include 'admin-menu-bar.php';
        echo '<br>';
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
    } else{
        include 'menu-bar.php';
        ?><br><?php
        include 'sub-menu-btn.php';
        $session_username = $_SESSION['username'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $indiv_csrf_token = $_POST["indiv_csrf_token"];

        if($indiv_csrf_token === $_SESSION['indiv_bill_csrf_token']){
            
            // Retrieve checkbox values
            $checkboxValues = isset($_POST["options"]) ? $_POST["options"] : [];

            // Process selected checkboxes
            foreach ($checkboxValues as $customerId) {
                // Retrieve form data
                
                $stbno = mysqli_real_escape_string($con, $_POST["stbno"][$customerId]);
                $mso = $_POST["mso"][$customerId];
                $name = $_POST["name"][$customerId];
                $phone = $_POST["phone"][$customerId];
                $description = $_POST["description"][$customerId];
                $pMode = $_POST["pMode"][$customerId];
                $oldMonthBal = $_POST["oldMonthBal"][$customerId];
                $paid_amount = $_POST["paid_amount"][$customerId];
                $discount = $_POST["discount"][$customerId];
                $bill_status = 'approve';
                

                if ($discount > 0) {
                    $discount = $discount;
                } else {
                    $discount = 0;
                }
                
                // if ($currentDay <= 05) {
                //     $discount = 10; // Set discount to 10 if current day is less than 5
                // } else {
                //     if ($discount > 0) {
                //         $discount = $discount;
                //     } else {
                //         $discount = 10;
                //     }
                // }
                
                
                
                $Rs = $paid_amount - $discount;

    // OLD
                // $currentDay = $datetime->format('d');
                // // Reset billNo to 1 on the first day of each month
                // if ($currentDay === '01') {
                //     $resetQuery = "UPDATE bill SET billNo = 1 WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
                //     $con->query($resetQuery);
                // }

                // // Retrieve the next billNo for the current month and year
                // $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
                // $result = $con->query($getBillNoQuery);

                // if ($result->num_rows > 0) {
                //     $row = $result->fetch_assoc();
                //     $billNo = $row["maxBillNo"] + 1;
                // } else {
                //     $billNo = 1;
                // }
                
                
                
                // /// NOT THIS CODE https://chat.openai.com/share/80e316ef-c537-447b-9b20-a1db341e3e94
                // /// THIS CODE https://chat.openai.com/share/59162802-39b4-4187-8f8c-b6ceb6bc0258
                // if ($currentDay === '01') {
                //     // Check if there is any bill entry for the next month
                //     $checkNextMonthQuery = "SELECT billNo FROM bill WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(DATE_ADD('$currentDate', INTERVAL 1 MONTH), '%Y-%m') LIMIT 1";
                //     $result = $con->query($checkNextMonthQuery);
                
                //     if ($result->num_rows > 0) {
                //         // There is already a bill entry for the next month, so set billNo to 1
                //         $billNo = 1;
                //     } else {
                //         // Retrieve the maximum billNo for the current month and year
                //         $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
                //         $result = $con->query($getMaxBillNoQuery);
                
                //         if ($result->num_rows > 0) {
                //             $row = $result->fetch_assoc();
                //             $maxBillNo = $row["maxBillNo"];
                //             if ($maxBillNo < 1) {
                //                 $billNo = 1;
                //             } else {
                //                 $billNo = $maxBillNo + 1;
                //             }
                //         } else {
                //             $billNo = 1;
                //         }
                //     }
                // } else {
                //     // Retrieve the next billNo for the current month and year
                //     $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
                //     $result = $con->query($getBillNoQuery);
                
                //     if ($result->num_rows > 0) {
                //         $row = $result->fetch_assoc();
                //         $billNo = $row["maxBillNo"] + 1;
                //     } else {
                //         $billNo = 1;
                //     }
                // }
                
                
                if ($oldMonthBal > 0) {
                    $oldMonthBal = $oldMonthBal;
                } else {
                    $oldMonthBal = 0;
                }
                
                $Rs = $Rs + $oldMonthBal;

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
            $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, pMode, oldMonthBal, paid_amount, discount, Rs, adv_status, due_month_timestamp, status, printStatus) 
                VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', 0, '$currentDateTime', '$bill_status', '$printStatus')";

                    if (isset($_SESSION['id'])) {
                        // Get the user information before destroying the session
                        $userId = $_SESSION['id'];
                        $username = $_SESSION['username'];
                        $role = $_SESSION['role'];
                        $action = "Bill Successful - $pMode - $stbno";
                    
                        // Call the function to insert user activity log
                        logUserActivity($userId, $username, $role, $action);
                    }
                    
                    
                // Execute the SQL statement
                if ($con->query($sql) === TRUE) {
                    // Data inserted successfully
                
                    // Calculate sum of paid_amount for the current date
                    $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$currentDate' AND status = 'approve'";
                    $result = $con->query($sqlSum);
                    $row = $result->fetch_assoc();
                    $sumPaidAmount = $row["total_Rs"];

                    // Check if a record exists in in_ex table
                    $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35 AND status = 1";
                    $resultCheck = $con->query($sqlCheck);

                    if ($resultCheck->num_rows > 0) {
                        // Update existing record
                        $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35 AND status = 1";
                        $con->query($sqlUpdate);
                    } else {
                        // Insert new record
                        $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount, status) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount,'1')";
                        $con->query($sqlInsert);
                    }
                    
                    $bill_status = "approve";
                    $sms_res = send_INDIV_BILL_SMS($name, $phone, $billNo, $currentDateTime, $stbno, $pMode, $bill_status);
                    $sms_res_array = json_decode($sms_res, true);
					$sms_res_array_status = $sms_res_array['status'];
                    if (isset($_SESSION['id']) && $sms_res == true) {
                        // Get the user information before destroying the session
                        $userId = $_SESSION['id'];
                        $username = $_SESSION['username'];
                        $role = $_SESSION['role'];
                        $action = "Indiv Bill SMS Status: $sms_res_array_status | $phone - $stbno - $sms_res_array_status";
                    
                        // Call the function to insert user activity log
                        logUserActivity($userId, $username, $role, $action);
                    }
                    
                    // echo "<script>console.log('$response');</script>";

                    continue;

                } else {
                    echo "Error inserting data: " . $con->error;
                    if (isset($_SESSION['id'])) {
                        // Get the user information before destroying the session
                        $userId = $_SESSION['id'];
                        $username = $_SESSION['username'];
                        $role = $_SESSION['role'];
                        $action = "Indiv Bill Failed - $pMode - $stbno";
                    
                        // Call the function to insert user activity log
                        logUserActivity($userId, $username, $role, $action);
                    }
                    ?>
                    <center><img src="assets/red-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
                    <?php
                    break;
                }
            }

            // Redirect after processing
            ?>
            <!--<center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="100px" height="100px"></center>-->
            <?php

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
            $url = "prtindivbulkbilldash.php"; // Replace with your desired URL bill-print-bulk.php
            redirect($url);

            unset($_SESSION['indiv_bill_csrf_token']);

        }else{
            
            echo "<script>alert('Invalid CSRF Token - Double Entry Avoided');</script>";
            unset($_SESSION['indiv_bill_csrf_token']);
        
        }

    }

?>

<!----------------------Ajax Edit Customer---Popup model------------------------->

<div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateStudent" autocomplete="off">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="student_id" id="student_id" >

                <!-- <label for="selectBox" class="form-label">Select RC/DC Status: *</label>
                <select style="font-weight: bold;" name="rc_dc" id="rc_dc" class="form-select" required>
                  <option style="font-weight: bold;" value="1" selected>RC</option>
                   <option style="font-weight: bold;" value="0">DC</option> 
                </select>
                
                <label for="selectBox" class="form-label">Select an Group: *</label>
                <select style="font-weight: bold;" name="cusGroup" id="cusGroup" class="form-select" required>
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                
                                                $query = "SELECT group_id,groupName FROM groupinfo WHERE group_id != '2'";
                                                $result = mysqli_query($con, $query);
                                                
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $optionValueID = $row['group_id'];
                                                    $optionValue = $row['groupName'];
                                                    ?>
                                                    <option value="<?php echo $optionValueID; ?>"><b><?php echo $optionValue; ?></b></option>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </select> -->

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="selectBox" class="form-label">Select RC/DC Status: *</label>
                        <select style="font-weight: bold;" name="rc_dc" id="rc_dc" class="form-select" required>
                        <!--<option style="font-weight: bold;" selected disabled>Select ...</option>-->
                        <option style="font-weight: bold;" value="1" selected>RC</option>
                        <option style="font-weight: bold;" value="0">DC</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="selectBox" class="form-label">Select Group: *</label>
                        <select style="font-weight: bold;" name="cusGroup" id="cusGroup" class="form-select" required>
                            <option value="" selected disabled>Select</option>
                            <?php
                            
                            $query = "SELECT group_id,groupName FROM groupinfo WHERE group_id != '2'";
                            $result = mysqli_query($con, $query);
                            
                            while ($row = mysqli_fetch_assoc($result)) {
                                $optionValueID = $row['group_id'];
                                $optionValue = $row['groupName'];
                                ?>
                                <option value="<?php echo $optionValueID; ?>"><b><?php echo $optionValue; ?></b></option>
                                <?php
                            }
                            
                            ?>
                        </select>
                    </div>
                </div>
                
                <label for="selectBox" class="form-label">Select an MSO: *</label>
                <select style="font-weight: bold;" name="mso" id="mso" class="form-select" required>
                  <!--<option style="font-weight: bold;" selected disabled>Select ...</option>-->
                  <option style="font-weight: bold;" value="VK" selected>VK DIGITAL</option>
                  <option style="font-weight: bold;" value="GTPL">GTPL</option>
                </select>
                <label for="editCustomerAreaCode" class="form-label">Customer Area <span class="text-danger">*</span></label>
                <select style="font-weight: bold;" name="editCustomerAreaCode" id="editCustomerAreaCode" class="form-select" required>
                    <option value="" selected disabled>Select</option>
                    <?php
                    
                    $query = "SELECT * FROM customer_area WHERE customer_area_status = 'Active'";
                    $result = mysqli_query($con, $query);
                    
                    while ($row = mysqli_fetch_assoc($result)) {
                        $optionValueID = $row['customer_area_code'];
                        $optionValue = $row['customer_area_code'] . ' - ' .$row['customer_area_name'];
                        ?>
                        <option value="<?php echo $optionValueID; ?>"><b><?php echo $optionValue; ?></b></option>
                        <?php
                    }
                    
                    ?>
                </select>

                <div class="mb-3">
                        <label for="stbno">STB No</label>
                        <input style="font-weight: bold;" type="text" name="stbno" id="stbno" class="form-control" />
                </div>
                <div class="mb-3">
                        <label for="name">Name</label>
                        <input style="font-weight: bold;" type="text" name="name" id="name" class="form-control" />
                </div>
                <div class="mb-3">
                        <label for="phone">Phone</label>
                        <input style="font-weight: bold;" type="text" name="phone" id="phone" class="form-control" />
                </div>

                <div class="mb-3">
                        <label for="description">Remark</label>
                        <input style="font-weight: bold;" type="text" name="description" id="description" class="form-control" />
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="selectAccessories" class="form-label">Select an Accessories: *</label>
                        <select style="font-weight: bold;" name="accessories" id="accessories" class="form-select" required>
                            <option style="font-weight: bold;" selected value="-">-</option>
                            <option style="font-weight: bold;" value="Node">Node</option>
                            <option style="font-weight: bold;" value="POC">POC</option>
                            <option style="font-weight: bold;" value="FTTH">FTTH</option>
                            <option style="font-weight: bold;" value="RF">RF</option>
                            <option style="font-weight: bold;" value="Node + POC">Node + POC</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="amount">Amount *</label>
                        <input style="font-weight: bold;" type="text" name="amount" id="amount" class="form-control" required />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
        </div>
    </div>
</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Billing Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">


    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>   -->
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  



<style>
    .custom-container {
        max-width: 90%;
    }
    
/* Define the styles for odd and even rows */
.creditBill {
    background-color: yellow; /* Light gray for odd rows */
}

.oldMonthPending {
    background-color: red; /* Slightly darker gray for even rows */
}


.list-group-item-action:hover {
    background-color: #023199;
    color: white; /* Add this line to change font color on hover */
}
</style>
</head>
<body>
    

    <!--<hr class="mt-0 mb-4">-->

    <div class="container custom-container">
        <div class="row" style="width: 100%;">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Individual Billing Dashboard
                            <a href="billing-group-dashboard.php?search=select">
                                <button type="button" class="btn btn-primary float-end">
                                    Group Bill
                                </button>
                            </a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                
                                <form autocomplete="off" action="" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" id="search" pattern="[A-Za-z0-9\s]{3,}" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Enter Minimum 3 Character of STB No, Name, Phone" autofocus>                                      
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                                <div id="searchList"></div> 
                            </div>
                        </div>
                    </div>      

                </div><br/>
            </div>

            <div class="col-md-12">
                <div class="card mt-12">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="table-responsive">
                                <table class="table table-hover" border="5" style="white-space: nowrap;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>#$#</th>
                                            <th>MSO</th>
                                            <th>STB No</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <!--th>Area</th-->
                                            <th>Accessories ℹ️</th>
                                            <th>Remarks ℹ️</th>
                                            <th>P.Mode</th>
                                            <th>OldBal</th>
                                            <th>BillAmt</th>
                                            <th>Disct</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        <input type="hidden" style="width: 750px;" name="indiv_csrf_token" value="<?= generate_indiv_bill_csrf_token(); ?>">
                                    
                                        <?php
                                        if (isset($_GET['search'])) {
                                            $filtervalues = $_GET['search'];
        
                                            $query = "SELECT * FROM customer 
                                            WHERE CONCAT(stbno, name, phone) LIKE '%$filtervalues%' AND rc_dc='1' AND cusGroup = '1' LIMIT 300";
    
                                            $query_run = mysqli_query($con, $query);
    
                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1;
                                                foreach ($query_run as $customer) {
    
                                                    $stbno = mysqli_real_escape_string($con, $customer['stbno']);
    
                                                    $nestedQuery = "SELECT * FROM bill 
                                                    WHERE stbno = '$stbno'  AND status = 'approve'
                                                    AND MONTH(`due_month_timestamp`) = '$currentMonth'
                                                    AND YEAR(`due_month_timestamp`) = '$currentYear'";
    
                                                    $nestedQuery_run = mysqli_query($con, $nestedQuery);
    
                                                    $disableButton = (mysqli_num_rows($nestedQuery_run) > 0) ? true : false;
                                                    
                                                    $nestedQuery2 = "SELECT pMode FROM bill WHERE stbno = '$stbno' AND pMode = 'credit' AND status = 'approve'";
                                                    
                                                    $nestedQuery2_run = mysqli_query($con, $nestedQuery2);
                                                    
                                                    $disableButton2 = (mysqli_num_rows($nestedQuery2_run) > 0) ? true : false;
                                                    
                                                    // if ($currentDay <= 10) {
                                                    //     $discountValue = 10; // Set discount to 10 if current day is less than 5
                                                    // } else {
                                                            $discountValue = 0;
                                                    // }

                                                    ?>
                                                    
                                                    <?php if ($disableButton2): ?>
                                                    <tr class="creditBill">
                                                    <?php else: ?>
                                                    <tr>
                                                    <?php endif; ?>
                                                        
                                                        <td style="font-weight: bold; font-size: 16px;"><?= $serial_number++; ?></td>
                                                    
                                                        <td><b><?php
                                                        $IndivPreMonthPaidStatus=fetchIndivPreMonthPaidStatus($customer['stbno'], $currentDate);
                                                        echo $IndivPreMonthPaidStatus['html_code'] ?></b></td>
                                                        
                                                        <td>
                                                            <?php if (!$disableButton): ?>
                                                                <div class="form-check">
                                                                    <input type="checkbox" id="myCheckbox" name="options[]" value="<?= $customer['id']; ?>" class="form-check-input">
                                                                </div>
                                                            <?php else: ?>
                                                                    <a href="customer-history.php?search=<?= $customer['stbno']; ?>" target="_blank">
                                                                    <img src="assets/arrow-up-right-from-square-solid.svg" width="20px" height="20px">
                                                                    </a>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 160px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="mso[<?= $customer['id']; ?>]" value="<?= $customer['mso']; ?>" style="width: 70px;">
                                                        </td>
                                                        <td >
                                                                <input readonly class="form-control fw-bold" type="text" name="stbno[<?= $customer['id']; ?>]" value="<?= $customer['stbno']; ?>" style="width: 180px;">
                                                        </td>
                                                        
                                                        <td style="width: 350px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="name[<?= $customer['id']; ?>]" value="<?= $customer['customer_area_code'] . ' - ' . $customer['name']; ?>"  style="width: 300px;">
                                                        </td>
                                                        <td style="width: 110px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="phone[<?= $customer['id']; ?>]" value="<?= $customer['phone']; ?>" style="width: 130px;">
                                                        </td>
                                                        <!--td style="width: 110px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="customerAreaCode[<?= $customer['id']; ?>]" value="<?= $customer['customer_area_code']; ?>" style="width: 70px;">
                                                        </td-->
                                                        <!-- <td>
                                                            <input type="text" name="accessories[<?= $customer['id']; ?>]" value="<?= $customer['accessories']; ?>" class="form-control fw-bold" style="width: 50px; color: #DD0581;">
                                                        </td> -->
                                                        <td style="width: 100px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="accessories[<?= $customer['id']; ?>]" value="<?= $customer['accessories']; ?>" onclick="showDescription('Accessories', '<?= $customer['accessories']; ?>',null, 'Accessories')" style="background-color:rgb(255, 251, 5); width: 100px;">
                                                        </td>
                                                        <td style="width: 180px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="description[<?= $customer['id']; ?>]" value="<?= $customer['description']; ?>" onclick="showDescription('<?= $customer['description']; ?>', '<?= $customer['name']; ?>', '<?= $customer['stbno']; ?>', 'Packages')" style="background-color: #5cc5fa; width: 180px;">
                                                        </td>
                                                        <td>
                                                            <select name="pMode[<?= $customer['id']; ?>]" class="form-select fw-bold" style="width: 100px; height: 40px;">
                                                                <option value="cash" selected class="fw-bold">Cash</option>
                                                                <option value="gpay" class="fw-bold">G Pay</option>
                                                                <option value="Paytm" class="fw-bold">Paytm</option>
                                                                <option value="credit" class="fw-bold">Credit</option>
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="oldMonthBal[<?= $customer['id']; ?>]" value="0" class="form-control fw-bold" style="width: 60px; color: #0012C3;">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" name="paid_amount[<?= $customer['id']; ?>]" value="<?= $customer['amount']; ?>" class="form-control fw-bold" style="width: 70px; font-weight: bold; font-size: 18px; color: #F20000;">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="discount[<?= $customer['id']; ?>]" value="<?php echo $discountValue ?>" class="form-control fw-bold" style="width: 50px; color: #DD0581;">
                                                        </td>
                                                        <td>
                                                            <button type="button" value="<?=$customer['id'];?>" class="editStudentBtn btn btn-success btn-sm">Edit</button>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
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
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="confirmButton" data-toggle="modal" data-target="#exampleModal">
                                        Confirm
                                    </button>
                                </div>
    
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
                                                <!--<p>Customer Name: <?php echo $customer['name']; ?></p>-->
                                                <p>Are you sure to make Bill ?</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<br/>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Autosearch List -->
<script>  

function showDescription(description, name, stbno, whatData) {
    Swal.fire({
        title: `${description}`, // Concatenate description and name
        text: `${name} - ${stbno} - ${whatData}`,
        icon: 'info',
        confirmButtonText: 'OK',
    });
}

 $(document).ready(function(){  
      $('#search').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"code-fecth-billing-dashboard.php",  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                          $('#searchList').fadeIn();  
                          $('#searchList').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#search').val($(this).text());  
           $('#searchList').fadeOut();  
      });  
 });
</script>
<script>

$(document).on('click', '.editStudentBtn', function () {

    var student_id = $(this).val();

    $.ajax({
        type: "GET",
        url: "code.php?student_id=" + student_id,
        success: function (response) {

            var res = jQuery.parseJSON(response);
            if(res.status == 404) {

                alert(res.message);
            }else if(res.status == 200){

                $('#student_id').val(res.data.id);
                $('#cusGroup').val(res.data.cusGroup);
                $('#rc_dc').val(res.data.rc_dc);
                $('#mso').val(res.data.mso);
                $('#name').val(res.data.name);
                $('#stbno').val(res.data.stbno);
                $('#phone').val(res.data.phone);
                $('#editCustomerAreaCode').val(res.data.customer_area_code);
                $('#description').val(res.data.description);
                $('#accessories').val(res.data.accessories);
                $('#amount').val(res.data.amount);

                $('#studentEditModal').modal('show');
            }

        }
    });

});
        
        
        $(document).on('submit', '#updateStudent', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("update_student", true);

            $.ajax({
                type: "POST",
                url: "code.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    
                    var res = jQuery.parseJSON(response);
                    if(res.status == 422) {
                        $('#errorMessageUpdate').removeClass('d-none');
                        $('#errorMessageUpdate').text(res.message);

                    }else if(res.status == 200){

                        $('#errorMessageUpdate').addClass('d-none');

                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);
                        
                        $('#studentEditModal').modal('hide');
                        $('#updateStudent')[0].reset();

                        // $('#myTable').load(location.href + " #myTable");
                          setTimeout(function() {
                            location.reload();
                          }, 200);

                    }else if(res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });
        


</script><?php include 'footer.php'?>
</body>
</html>



<?php } else{
	header("Location: index.php");
} ?>

