<?php
session_start();
require "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    

    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
        
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        ?><br><?php
        include 'sub-menu-btn.php';
        $session_username = $_SESSION['username'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                $due_month_date = $_POST["due_month_date"][$customerId];
                

                $due_month_timestamp = $due_month_date . " " . $currentTime;
                

                            function redirect($url)
                            {
                                echo "<script>
                                setTimeout(function(){
                                    window.location.href = '$url';
                                }, 200);
                            </script>";
                            }
                            
                            $url = "adv-indiv-billing-dashboard.php?search=$stbno";
                            
            function insertBill(){

                require "dbconfig.php";

                global $discount;
                global $paid_amount;
                global $oldMonthBal;
                global $mso;
                global $stbno;
                global $name;
                global $phone;
                global $description;
                global $pMode;
                global $oldMonthBal;
                global $paid_amount;
                global $due_month_timestamp;
                global $session_username;

                $bill_status = 'approve';

                if ($discount > 0) {
                    $discount = $discount;
                } else {
                    $discount = 0;
                }            
                
                $Rs = $paid_amount - $discount;
                
                
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
                
                $printStatus = 1;

                    // Prepare the SQL statement
                    $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, pMode, oldMonthBal, paid_amount, discount, Rs, adv_status, due_month_timestamp, status, printStatus) 
                    VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', 1, '$due_month_timestamp', '$bill_status', '$printStatus')";

                        
                    // Execute the SQL statement
                    if ($con->query($sql) === TRUE) {
                        // Data inserted successfully
                    //  $q= "SELECT date,sum(paid_amount) TotAmt FROM `bill` where date='2023-06-01' group by date";
                    
                    // sms_api($name, $phone, $billNo, $due_month_timestamp, $stbno, $pMode, $con);
                    
                        // Calculate sum of paid_amount for the current date
                        $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$currentDate' AND status = 'approve'";
                        $result = $con->query($sqlSum);
                        $row = $result->fetch_assoc();
                        $sumPaidAmount = $row["total_Rs"];

                        // Check if a record exists in in_ex table
                        $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                        $resultCheck = $con->query($sqlCheck);

                        if ($resultCheck->num_rows > 0) {
                            // Update existing record
                            $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                            $con->query($sqlUpdate);
                        } else {
                            // Insert new record
                            $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount)";
                            $con->query($sqlInsert);
                        }

                        // continue;

                        if (isset($_SESSION['id'])) {
                            // Get the user information before destroying the session
                            $userId = $_SESSION['id'];
                            $username = $_SESSION['username'];
                            $role = $_SESSION['role'];
                            $action = "Advance Bill Successful - $pMode - $stbno";
                        
                            // Call the function to insert user activity log
                            logUserActivity($userId, $username, $role, $action);
                        }


                            echo '<script type="text/javascript">
                                let stbno = "' . htmlspecialchars($stbno, ENT_QUOTES, 'UTF-8') . '";
                                // JavaScript to open the link in a new tab
                                window.onload = function() {
                                    // var newTab = window.open("prtindivadvbill.php?stbnumber=" + stbno);
                                    var newTab2 = window.location.href = "adv-indiv-billing-dashboard.php?search=" + stbno;
                                    var newTab = window.open("prtindivadvbill.php?stbnumber=" + stbno, "_blank");
                                    // Check if the popup was blocked by the browser
                                    if (newTab) {
                                        newTab.focus();
                                        newTab2.focus();
                                    } else {
                                        Swal.fire({
                                            toast: true,
                                            icon: "warning",
                                            title: "Please allow popups for this website",
                                            position: "top-end",
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    }
                                }
                            </script>';
                            // sleep(1);
                        // echo "<script>window.open('prtindivadvbill.php?stbnumber=' + stbno);</script>";
                        // echo "<script>
                        // function myFunction(stbno) {
                        // let text = 'Are Want to print ?';
                        // if (confirm(text)) {
                        //     window.open('prtindivadvbill.php?stbnumber=' + stbno);
                        // } else {
                            
                        // }
                        // }
                        // </script>";

                        // Call the JavaScript function with the PHP variable
                        // echo "<script>myFunction('$stbno');</script>";
                        // redirect($url);


                    } else {
                        echo "Error inserting data: " . $con->error;
                        if (isset($_SESSION['id'])) {
                            // Get the user information before destroying the session
                            $userId = $_SESSION['id'];
                            $username = $_SESSION['username'];
                            $role = $_SESSION['role'];
                            $action = "Advance Bill Failed - $pMode - $stbno";
                        
                            logUserActivity($userId, $username, $role, $action);
                        }

                    }
                }

                $DD = date('d', strtotime($due_month_date));
                $MM = date('m', strtotime($due_month_date));
                $YY = date('Y', strtotime($due_month_date));

                if ($currentDate <= $due_month_date) {

                    $sql1 = "SELECT stbno FROM bill WHERE stbno = '$stbno' AND MONTH(due_month_timestamp)=$MM AND YEAR(due_month_timestamp)=$YY AND status = 'approve' LIMIT 1";

                    $run1 = $con->query($sql1);

                    if ($run1 && $run1->num_rows > 0) {

                        $row = $run1->fetch_assoc();
                        $run_result1 = $row['stbno'];
                        
                    } else {
                        $run_result1 = null;
                    }

                    // Check if the query was successful
                    if ($run_result1 && $run_result1 == $stbno) {

                        echo "<script>
                            Swal.fire({
                                toast: true,
                                icon: 'warning',
                                title: 'Approved Bill Already Exists on Due Month Date !!!',
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        </script>";

                    } else {

                        $due_date_sql = "SELECT billNo FROM bill WHERE stbno = '$stbno' 
                                            AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                                                OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                                            AND adv_status = 1 AND status = 'approve'";

                        $due_date_result = $con->query($due_date_sql);

                        if ($due_date_result) {

                            insertBill();

                        } else {  

                            echo "<script>
                                Swal.fire({
                                    toast: true,
                                    icon: 'warning',
                                    title: 'Due Month Approved Bill already Exists for " . $name . "',
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            </script>";
                            
                            redirect($url);

                        }  
                    }

                } else {
                    
                    echo "<script>
                        Swal.fire({
                            toast: true,
                            icon: 'error',
                            title: 'Incorrect Due Month Date. Check Due Month Date !!!',
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    </script>";

                }
                            // redirect($url);
            }

    }

        // Redirect after processing
        ?>
        <!--<center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="100px" height="100px"></center>-->
        <?php

        // // Redirect function
        // function redirect($url)
        // {
        //     echo "<script>
        //     setTimeout(function(){
        //         window.location.href = '$url';
        //     }, 200);
        // </script>";
        // }

        // // Usage example
        // $url = "prtadvindivdash.php"; // Replace with your desired URL bill-print-bulk.php
        // redirect($url);
    // }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advance Individual Billing Dashboard</title>
    
    <!-- Dependencies -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #1cc88a;
            --danger-color: #ef476f;
            --warning-color: #f6c23e;
            --text-dark: #2b2d42;
            --bg-light: #f8f9fc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-dark);
            font-size: 0.9rem;
        }

        .main-content {
            padding-bottom: 5rem;
        }

        /* Card Styles */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            border: 1px solid rgba(0,0,0,0.02);
        }

        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1rem 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 16px 16px 0 0;
        }

        .card-title {
            margin: 0;
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Form Controls */
        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #e5e7eb;
            font-weight: 500;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        /* Search Autocomplete */
        #searchList {
            background: white;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
            max-height: 300px;
            overflow-y: auto;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }
        
        #searchList ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        #searchList li {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 1px solid #f1f5f9;
        }
        
        #searchList li:hover {
            background-color: #f8fafc;
            color: var(--primary-color);
        }

        /* Table Styles */
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .table-custom td {
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border: none;
            color: white;
            transition: all 0.2s;
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3);
            color: white;
        }

        .creditBill {
            background-color: #fff9c4 !important; /* Light yellow */
        }

        /* Sticky Action Bar */
        .action-bar-sticky {
            position: sticky;
            bottom: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -4px 6px -1px rgba(0,0,0,0.05);
            padding: 1rem 0;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    
    <div class="container-fluid main-content px-lg-4 px-3 py-4">
        
        <!-- Search Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 position-relative">
                <div class="custom-card mb-0">
                    <div class="card-header-gradient">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-calendar-check me-2 fs-5"></i>
                            <h5 class="card-title">Advance Individual Billing</h5>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                                <i class="bi bi-calculator me-1"></i>Calculator
                            </button>
                            <a href="billing-dashboard.php" class="btn btn-sm btn-light text-primary fw-bold shadow-sm rounded-pill px-3">
                                <i class="bi bi-arrow-left me-1"></i>Back to Indiv Billing
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <label class="form-label text-secondary fw-bold small text-uppercase">Search Customer</label>
                        <form action="" method="GET" class="position-relative">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-search"></i></span>
                                <input type="text" name="search" id="search" autocomplete="off" 
                                    value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" 
                                    class="form-control border-start-0 ps-0 form-control-lg fs-6" 
                                    placeholder="Enter Name, STB No, or Phone..." 
                                    pattern="[A-Za-z0-9\s]{3,}" required>
                                <button type="submit" class="btn btn-primary-custom px-4 fw-bold">Search</button>
                            </div>
                        </form>
                        <div id="searchList"></div> 
                    </div>      
                </div>
            </div>
        </div>

        <?php if (isset($_GET['search'])): ?>
            <div class="row justify-content-center">
                <div class="col-12">
                    <form action="" method="POST" id="billingForm">
                        <div class="custom-card">
                            <div class="card-header border-bottom bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 fw-bold text-secondary"><i class="bi bi-list-check me-2"></i>Search Results</h6>
                                    <a href="adv-indiv-billing-dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center">Select</th>
                                                <th>STB No</th>
                                                <th>Name</th>
                                                <th>Phone</th>
                                                <th>Remarks</th>
                                                <th>P.Mode</th>
                                                <th>OldBal</th>
                                                <th>BillAmt</th>
                                                <th>Disct</th>
                                                <th>Payable</th>
                                                <th>Due Month</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $filtervalues = $_GET['search'];
                                            $query = "SELECT * FROM customer WHERE CONCAT(stbno,name,phone) LIKE '%$filtervalues%' AND rc_dc='1' AND cusGroup = '1' LIMIT 1 ";
                                            $query_run = mysqli_query($con, $query);

                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1;
                                                foreach ($query_run as $customer) {
                                                    $stbno = mysqli_real_escape_string($con, $customer['stbno']);
                                                    
                                                    // Check Existing Bill
                                                    $nestedQuery = "SELECT * FROM bill WHERE stbno = '$stbno' AND status = 'approve' AND MONTH(`date`) = '$currentMonth' AND YEAR(`date`) = '$currentYear'";
                                                    $nestedQuery_run = mysqli_query($con, $nestedQuery);
                                                    $disableButton = (mysqli_num_rows($nestedQuery_run) > 0);

                                                    // Check Credit
                                                    $nestedQuery2 = "SELECT pMode FROM bill WHERE stbno = '$stbno' AND pMode = 'credit' AND status = 'approve'";
                                                    $nestedQuery2_run = mysqli_query($con, $nestedQuery2);
                                                    $disableButton2 = (mysqli_num_rows($nestedQuery2_run) > 0);
                                                    
                                                    $discountValue = ($currentDay <= 05) ? 10 : 0;
                                                    $rowClass = $disableButton2 ? 'creditBill' : '';
                                                    ?>
                                                    <tr class="<?= $rowClass ?>">
                                                        <td class="text-center fw-bold text-secondary"><?= $serial_number++; ?></td>
                                                        <td class="text-center">
                                                            <div class="form-check d-flex justify-content-center">
                                                                <input type="checkbox" id="myCheckbox" name="options[]" value="<?= $customer['id']; ?>" class="form-check-input border-2 border-primary" style="transform: scale(1.2); cursor: pointer;">
                                                            </div>
                                                        </td>
                                                        <!-- Hidden Inputs -->
                                                        <input type="hidden" name="mso[<?= $customer['id']; ?>]" value="<?= $customer['mso']; ?>">
                                                        
                                                        <td>
                                                            <input readonly class="form-control-plaintext fw-bold" type="text" name="stbno[<?= $customer['id']; ?>]" value="<?= $customer['stbno']; ?>">
                                                        </td>
                                                        
                                                        <td>
                                                            <input readonly class="form-control-plaintext fw-bold text-primary" type="text" name="name[<?= $customer['id']; ?>]" value="<?= $customer['name']; ?>">
                                                        </td>
                                                        <td>
                                                            <input readonly class="form-control-plaintext fw-bold" type="text" name="phone[<?= $customer['id']; ?>]" value="<?= $customer['phone']; ?>">
                                                        </td>
                                                        <td>
                                                            <input readonly class="form-control-plaintext fw-bold text-muted" type="text" name="description[<?= $customer['id']; ?>]" value="<?= $customer['description']; ?>">
                                                        </td>
                                                        <td>
                                                            <select name="pMode[<?= $customer['id']; ?>]" class="form-select form-select-sm fw-bold border-primary text-primary" style="min-width: 100px;">
                                                                <option value="cash" selected>Cash</option>
                                                                <option value="paytm">Paytm</option>
                                                                <option value="gpay">G Pay</option>
                                                                <!-- <option value="credit">Credit</option> -->
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="oldMonthBal[<?= $customer['id']; ?>]" value="0" class="form-control form-control-sm fw-bold text-danger text-end">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" name="paid_amount[<?= $customer['id']; ?>]" value="<?= $customer['amount']; ?>" class="form-control form-control-sm fw-bold text-success text-end fs-6" style="min-width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="discount[<?= $customer['id']; ?>]" value="<?php echo $discountValue ?>" class="form-control form-control-sm fw-bold text-warning text-end">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" name="payable_amount[<?= $customer['id']; ?>]" value="<?= $customer['amount'] + 0 - $discountValue; ?>" class="form-control form-control-sm fw-bold text-primary text-end fs-6 bg-light" style="min-width: 100px;">
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control form-control-sm" name="due_month_date[<?= $customer['id']; ?>]" value="<?= $currentDate ?>" required>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="11" class="text-center py-5 text-muted fw-bold">No customers found.</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <?php if (isset($query_run) && mysqli_num_rows($query_run) > 0): ?>
                            <div class="action-bar-sticky">
                                <div class="container text-center">
                                    <button type="button" class="btn btn-primary-custom btn-lg rounded-pill px-5 fw-bold shadow-lg" id="confirmButton">
                                        <i class="bi bi-check-circle-fill me-2"></i> Confirm Advance Bill
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>

                    </form>
                </div>
            </div>
            
            <?php else: ?>
                <!-- Empty State -->
                <div class="text-center py-5 mt-4">
                    <div class="mb-3 text-muted opacity-25">
                        <i class="bi bi-receipt-cutoff" style="font-size: 5rem;"></i>
                    </div>
                    <h4 class="fw-bold text-secondary">Ready to Bill?</h4>
                    <p class="text-muted">Search for a customer above to start generating individual bills.</p>
                </div>
            <?php endif; ?>

    </div>

    <?php include 'footer.php'?>

    <!-- Advance Calculator Modal -->
    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0 bg-primary text-white rounded-top-4 py-2">
                    <h6 class="modal-title fw-bold"><i class="bi bi-calculator me-2"></i>Advance Calculator</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="small text-muted fw-bold">Monthly Amt</label>
                            <input type="number" id="calcAmount" class="form-control form-control-sm border-primary fw-bold text-primary" placeholder="0">
                        </div>
                        <div class="col-6">
                             <label class="small text-muted fw-bold">Months</label>
                            <input type="number" id="calcMonths" class="form-control form-control-sm border-primary fw-bold text-primary" placeholder="0">
                        </div>
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="small text-muted fw-bold">Old Bal (+)</label>
                            <input type="number" id="calcOldBal" class="form-control form-control-sm border-danger fw-bold text-danger" placeholder="0">
                        </div>
                        <div class="col-6">
                             <label class="small text-muted fw-bold">Discount (-)</label>
                            <input type="number" id="calcDiscount" class="form-control form-control-sm border-success fw-bold text-success" placeholder="0">
                        </div>
                    </div>

                    <div class="p-2 bg-light rounded text-center border">
                        <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Net Payable Amount</small>
                        <div class="fs-4 fw-bold text-dark" id="calcTotal">₹0.00</div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-2 justify-content-between">
                     <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="resetCalc">Reset</button>
                     <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" data-bs-dismiss="modal">Done</button>
                </div>
            </div>
        </div>
    </div>

    <script>  
     $(document).ready(function(){  
          
          // Calculator Logic
          function updateCalculator() {
              let amount = parseFloat($('#calcAmount').val()) || 0;
              let months = parseFloat($('#calcMonths').val()) || 0;
              let oldBal = parseFloat($('#calcOldBal').val()) || 0;
              let discount = parseFloat($('#calcDiscount').val()) || 0;
              
              let total = (amount * months) + oldBal - discount;
              $('#calcTotal').text('₹' + total.toFixed(2));
          }

          $('#calcAmount, #calcMonths, #calcOldBal, #calcDiscount').on('input', updateCalculator);
          
          $('#resetCalc').click(function(){
              $('#calcAmount, #calcMonths, #calcOldBal, #calcDiscount').val('');
              updateCalculator();
          });

          // Real-time Payable Calculation (Table)
            $(document).on('input', 'input[name^="oldMonthBal"], input[name^="discount"]', function() {
                let row = $(this).closest('tr');
                let amt = parseFloat(row.find('input[name^="paid_amount"]').val()) || 0;
                let oldBal = parseFloat(row.find('input[name^="oldMonthBal"]').val()) || 0;
                let disc = parseFloat(row.find('input[name^="discount"]').val()) || 0;
                
                let payable = (amt + oldBal) - disc;
                
                // Update the payable field
                row.find('input[name^="payable_amount"]').val(payable.toFixed(2));
            });

          // Autocomplete
          $('#search').keyup(function(){  
               var query = $(this).val();  
               if(query != '') {  
                    $.ajax({  
                         url:"code-fecth-adv-billing-dashboard.php",  
                         method:"POST",  
                         data:{query:query},  
                         success:function(data){  
                              $('#searchList').fadeIn();  
                              $('#searchList').html(data);  
                         }  
                    });  
               } else {
                   $('#searchList').fadeOut();
               }
          });  
          $(document).on('click', 'li', function(){  
               $('#search').val($(this).text());  
               $('#searchList').fadeOut();  
          });  

          // Confirm Button Logic with SweetAlert2
          $('#confirmButton').on('click', function (e) {
                e.preventDefault();
                
                let checkedBoxes = $('input[name="options[]"]:checked');
                if (checkedBoxes.length === 0) {
                    Swal.fire({ 
                        title: 'Selection Required', 
                        text: "Please select a customer to bill.", 
                        icon: 'warning',
                        confirmButtonColor: '#4361ee'
                    });
                    return;
                }

                Swal.fire({
                    title: 'Confirm Advance Bill',
                    html: `
                        <div class="text-start alert alert-info small">
                            <strong>Note:</strong> ensure the <b>Due Month Date</b> is set correctly before proceeding.
                        </div>
                        <p class="mb-0">Are you sure you want to generate this bill?</p>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4361ee',
                    cancelButtonColor: '#ef476f',
                    confirmButtonText: 'Yes, Generate Bill'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#billingForm').submit();
                    }
                });
          });
     });
    </script>
</body>
</html>

<?php } else{
	header("Location: logout.php");
} ?>
