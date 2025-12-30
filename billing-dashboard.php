<?php
session_start();
require "dbconfig.php";
require "component.php";
include 'preloader.php';

date_default_timezone_set('Asia/Kolkata');

$timezone = new DateTimeZone('Asia/Kolkata');
$datetime = new DateTime('now', $timezone);
$currentTimeA = $datetime->format('h:i:s A');
$currentTime = $datetime->format('H:i:s');
$currentDate = $datetime->format('Y-m-d');
$currentDateTime = $datetime->format('Y-m-d H:i:s');
$currentDay = $datetime->format('d');
$currentMonth = $datetime->format('m');
$currentYear = $datetime->format('Y');

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    
    // Header Include
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
            
            $checkboxValues = isset($_POST["options"]) ? $_POST["options"] : [];

            foreach ($checkboxValues as $customerId) {
                // Determine source: POST arrays are keyed by customer ID
                // Sanitize and Retrieve
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
                $remark2 = $_POST["remark2"][$customerId] ?? null;

                $discount = ($discount > 0) ? $discount : 0;
                $Rs = $paid_amount - $discount;
                $oldMonthBal = ($oldMonthBal > 0) ? $oldMonthBal : 0;
                $Rs = $Rs + $oldMonthBal;

                // Bill No Generation Logic
                if ($currentDay === '01') {
                    $checkNextDayQuery = "SELECT billNo FROM bill WHERE DATE(date) = DATE_ADD('$currentDate', INTERVAL 1 DAY) LIMIT 1";
                    $result = $con->query($checkNextDayQuery);
                    if ($result->num_rows > 0) {
                        $billNo = 1;
                    } else {
                        $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
                        $result = $con->query($getMaxBillNoQuery);
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $maxBillNo = $row["maxBillNo"];
                            $billNo = ($maxBillNo < 1) ? 1 : $maxBillNo + 1;
                        } else {
                            $billNo = 1;
                        }
                    }
                } else {
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
                    
                $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, remark2, pMode, oldMonthBal, paid_amount, discount, Rs, adv_status, due_month_timestamp, status, printStatus) 
                VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$remark2', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', 0, '$currentDateTime', '$bill_status', '$printStatus')";

                if ($con->query($sql) === TRUE) {
                    // Update Activity Log
                    if (isset($_SESSION['id'])) {
                        logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], "Bill Successful - $pMode - $stbno");
                    }
                
                    // Update Income/Expense Ledger
                    $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$currentDate' AND status = 'approve'";
                    $result = $con->query($sqlSum);
                    $row = $result->fetch_assoc();
                    $sumPaidAmount = $row["total_Rs"];

                    $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35 AND status = 1";
                    $resultCheck = $con->query($sqlCheck);

                    if ($resultCheck->num_rows > 0) {
                        $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35 AND status = 1";
                        $con->query($sqlUpdate);
                    } else {
                        $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount, status) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount,'1')";
                        $con->query($sqlInsert);
                    }

                    // Send SMS
                    $bill_status = "approve";
                    $sms_res = send_INDIV_BILL_SMS($name, $phone, $billNo, $currentDateTime, $stbno, $pMode, $bill_status);
                    
                    if (isset($_SESSION['id']) && $sms_res) {
                         $sms_res_array = json_decode($sms_res, true);
                         $sms_status = $sms_res_array['status'] ?? null;
                         $sms_msg = $sms_res_array['message'] ?? 'SMS sent success';
                         $action = "Indiv Bill SMS notify Status: " . ($sms_status ? 'SMS: sent success' : $sms_msg) . "|" . $phone . "-" . $stbno . "-" . $sms_status;
                         logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], $action);
                    }
                    continue;

                } else {
                    if (isset($_SESSION['id'])) {
                        logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], "Indiv Bill Failed - $pMode - $stbno");
                    }
                    // Visual error in loop not handy, but kept logic
                }
            }

            // Client-side redirect
            echo "<script> setTimeout(function(){ window.location.href = 'prtindivbulkbilldash.php'; }, 200); </script>";
            unset($_SESSION['indiv_bill_csrf_token']);

        } else {
            echo "<script>alert('Invalid CSRF Token - Double Entry Avoided');</script>";
            unset($_SESSION['indiv_bill_csrf_token']);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Billing Dashboard</title>
    
    <!-- Dependencies -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            /* overflow: hidden; Removed to allow dropdown to show */
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

        .img-placeholder {
            max-width: 250px;
            opacity: 0.7;
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
                        <i class="bi bi-person-badge me-2 fs-5"></i>
                        <h5 class="card-title">Individual Billing</h5>
                    </div>
                    <!-- Redirect Button -->
                    <a href="adv-indiv-billing-dashboard.php" class="btn btn-light btn-sm fw-bold text-primary rounded-pill px-3 shadow-sm">
                        <i class="bi bi-calendar-event me-1"></i>Advance Indiv Bill
                    </a>
                </div>
                <div class="card-body p-4">
                     <label class="form-label text-secondary fw-bold small text-uppercase">Search Customer</label>
                     <form autocomplete="off" action="" method="GET" class="position-relative">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-primary"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" id="search" 
                                value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" 
                                class="form-control border-start-0 ps-0 form-control-lg fs-6" 
                                placeholder="Start typing Name, STB No, or Phone..." 
                                required>
                            <button type="submit" class="btn btn-primary-custom px-4 fw-bold">Search</button>
                        </div>
                        <div id="searchList"></div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>

    <?php if (isset($_GET['search'])): ?>
    
    <!-- Results Section -->
    <form action="" method="POST" id="billingForm">
        <input type="hidden" name="indiv_csrf_token" value="<?= generate_indiv_bill_csrf_token(); ?>">
        
        <div class="custom-card">
            <div class="card-header border-bottom bg-white py-3">
                 <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-secondary"><i class="bi bi-list-check me-2"></i>Search Results</h6>
                    <a href="billing-dashboard.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Status</th>
                                <th class="text-center">Select</th>
                                <th>Customer Details</th>
                                <th>Pay Mode</th>
                                <th class="text-end" width="10%">Old Bal</th>
                                <th class="text-end" width="10%">Bill Amt</th>
                                <th class="text-end" width="10%">Discount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $filtervalues = $_GET['search'];
                            // Limit results to avoid overload
                            $query = "SELECT * FROM customer WHERE CONCAT(stbno, name, phone) LIKE '%$filtervalues%' AND rc_dc='1' AND cusGroup = '1' LIMIT 50";
                            $query_run = mysqli_query($con, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                $serial_number = 1;
                                foreach ($query_run as $row) {
                                    $stbno = $row['stbno'];
                                    
                                    // Check if already billed
                                    $billCheck = mysqli_query($con, "SELECT billNo FROM bill WHERE stbno = '$stbno' AND status = 'approve' AND MONTH(due_month_timestamp) = '$currentMonth' AND YEAR(due_month_timestamp) = '$currentYear'");
                                    $isBilled = (mysqli_num_rows($billCheck) > 0);
                                    
                                    // Check credit
                                    $creditCheck = mysqli_query($con, "SELECT billNo FROM bill WHERE stbno = '$stbno' AND pMode = 'credit' AND status = 'approve'");
                                    $isCredit = (mysqli_num_rows($creditCheck) > 0);
                                    
                                    $rowClass = $isCredit ? 'bg-light-warning' : '';
                                    
                                    // Hidden inputs for readonly data
                                    $hidInfo = "
                                        <input type='hidden' name='name[{$row['id']}]' value='{$row['name']}'>
                                        <input type='hidden' name='stbno[{$row['id']}]' value='{$stbno}'>
                                        <input type='hidden' name='phone[{$row['id']}]' value='{$row['phone']}'>
                                        <input type='hidden' name='mso[{$row['id']}]' value='{$row['mso']}'>
                                        <input type='hidden' name='description[{$row['id']}]' value='{$row['description']}'>
                                    ";
                            ?>
                            <tr class="<?= $rowClass ?>">
                                <td class="text-center fw-bold text-secondary"><?= $serial_number++; ?></td>
                                
                                <td>
                                    <?php 
                                        $status = fetchIndivPreMonthPaidStatus($stbno, $currentDate); 
                                        echo $status['html_code'];
                                        if($isCredit) echo '<span class="badge bg-warning text-dark ms-1">Credit Due</span>';
                                    ?>
                                </td>

                                <td class="text-center">
                                    <?php if (!$isBilled): ?>
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox" name="options[]" value="<?= $row['id']; ?>" class="form-check-input border-2 border-primary" style="transform: scale(1.2); cursor: pointer;">
                                        </div>
                                    <?php else: ?>
                                        <a href="customer-history.php?search=<?= $stbno; ?>" class="badge bg-success text-decoration-none rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i>Paid</a>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-primary"><?= $row['name'] ?></span>
                                        <small class="text-muted"><i class="bi bi-box-seam me-1"></i><?= $stbno ?></small>
                                        <small class="text-muted"><i class="bi bi-phone me-1"></i><?= $row['phone'] ?></small>
                                        <?= $hidInfo; ?>
                                    </div>
                                </td>

                                <td>
                                    <select name="pMode[<?= $row['id']; ?>]" class="form-select form-select-sm fw-bold border-primary text-primary" style="min-width: 100px;">
                                        <option value="cash" selected>Cash</option>
                                        <option value="gpay">GPay</option>
                                        <option value="Paytm">Paytm</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="number" name="oldMonthBal[<?= $row['id']; ?>]" value="0" class="form-control form-control-sm text-end text-danger fw-bold">
                                </td>

                                <td>
                                    <input readonly type="number" name="paid_amount[<?= $row['id']; ?>]" value="<?= $row['amount']; ?>" class="form-control form-control-sm text-end text-success fw-bold bg-white">
                                </td>

                                <td>
                                    <input type="number" name="discount[<?= $row['id']; ?>]" value="0" class="form-control form-control-sm text-end text-warning fw-bold">
                                </td>

                                <td class="text-center">
                                    <button type="button" value="<?=$row['id'];?>" class="editStudentBtn btn btn-light btn-sm text-primary shadow-sm rounded-circle" title="Edit Customer">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo '<tr><td colspan="9" class="text-center py-5 text-muted fw-bold">No customers found matching "' . htmlspecialchars($filtervalues) . '"</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (isset($query_run) && mysqli_num_rows($query_run) > 0): ?>
            <!-- Sticky Action Bar -->
            <div class="action-bar-sticky">
                <div class="container text-center">
                    <button type="button" class="btn btn-primary-custom btn-lg rounded-pill px-5 fw-bold shadow-lg" id="confirmButton">
                        <i class="bi bi-check-circle-fill me-2"></i> Confirm Billing
                    </button>
                </div>
            </div>
        <?php endif; ?>

    </form>
    
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

<!-- Edit Customer Modal -->
<div class="modal fade" id="studentEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="updateStudent">
                    <input type="hidden" name="student_id" id="student_id">
                    <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                    <div class="row g-3">
                         <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light text-success fw-bold">₹</span>
                                <input type="number" name="amount" id="amount" class="form-control fw-bold text-success" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control fw-bold">
                        </div>
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Package / Remark</label>
                            <input type="text" name="description" id="description" class="form-control fw-bold">
                        </div>
                        <!-- Hidden fields to maintain data integrity -->
                         <input type="hidden" name="rc_dc" id="rc_dc">
                         <input type="hidden" name="cusGroup" id="cusGroup">
                         <input type="hidden" name="mso" id="mso">
                         <input type="hidden" name="editCustomerAreaCode" id="editCustomerAreaCode">
                         <input type="hidden" name="stbno" id="stbno">
                         <input type="hidden" name="name" id="name">
                         <input type="hidden" name="accessories" id="accessories">
                    </div>
                    
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm py-2">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Search Autocomplete
    $('#search').keyup(function(){  
        var query = $(this).val();  
        if(query != '') {  
            $.ajax({  
                url:"code-fecth-billing-dashboard.php",  
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

    // Confirm Bill Logic
    $('#confirmButton').on('click', function (e) {
        e.preventDefault();
        
        let checkedBoxes = $('input[name="options[]"]:checked');
        if (checkedBoxes.length === 0) {
            Swal.fire({ title: 'Select Customer', text: "Please select at least one customer.", icon: 'warning'});
            return;
        }

        // Calculate Summary & Aggregates
        let totalAmount = 0;
        let totalDiscount = 0;
        let totalCreditAmount = 0;
        let pModeCounts = { 'cash': 0, 'gpay': 0, 'Paytm': 0, 'credit': 0 };
        let creditStbs = []; 
        let statusCounts = {}; 

        checkedBoxes.each(function() {
            let cid = $(this).val();
            let row = $(this).closest('tr');
            
            // Financials
            let amt = parseFloat(row.find(`input[name="paid_amount[${cid}]"]`).val()) || 0;
            let disc = parseFloat(row.find(`input[name="discount[${cid}]"]`).val()) || 0;
            totalAmount += amt;
            totalDiscount += disc;
            
            let rowNet = amt - disc;

            // Payment Mode
            let pMode = row.find(`select[name="pMode[${cid}]"]`).val();
            if(pModeCounts[pMode] !== undefined) pModeCounts[pMode]++;
            else pModeCounts[pMode] = 1;

            // Credit Check
            if (pMode === 'credit') {
                let stb = row.find(`input[name="stbno[${cid}]"]`).val();
                creditStbs.push(stb);
                totalCreditAmount += rowNet;
            }
        });

        let netAmount = totalAmount - totalDiscount;
        let expectedReceived = netAmount - totalCreditAmount;

        // generated HTML for PMode Counts
        let pModeHtml = '';
        for (const [mode, count] of Object.entries(pModeCounts)) {
            if (count > 0) {
                let badgeClass = 'bg-secondary';
                if(mode === 'cash') badgeClass = 'bg-success';
                else if(mode === 'credit') badgeClass = 'bg-warning text-dark';
                else if(mode === 'gpay' || mode === 'Paytm') badgeClass = 'bg-info text-dark';
                
                pModeHtml += `<span class="badge ${badgeClass} me-1 mb-1" style="font-size:0.9rem;">${mode.toUpperCase()}: ${count}</span>`;
            }
        }

        // Credit Alert HTML
        let creditAlertHtml = '';
        if (creditStbs.length > 0) {
            creditAlertHtml = `
                <div class="alert alert-warning mt-2 mb-0 p-2 text-start small">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> <strong>Credit Bills (${creditStbs.length}):</strong><br>
                    ${creditStbs.join(', ')}
                </div>
            `;
        }

        let summaryHtml = `
            <div class="container bg-light p-3 rounded">
                
                <!-- Financials -->
                <div class="row g-2 mb-3">
                    <div class="col-6 text-start">
                        <span class="text-secondary small fw-bold">CUSTOMERS</span><br>
                        <span class="fs-4 fw-bold text-dark">${checkedBoxes.length}</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="text-secondary small fw-bold">TOTAL PAYABLE</span><br>
                        <span class="fs-4 fw-bold text-success">₹${netAmount.toFixed(2)}</span>
                    </div>
                </div>

                <!-- Credit Deduction Section -->
                <div class="row g-2 mb-3 border-top pt-2">
                     <div class="col-6 text-start">
                        <span class="text-muted small fw-bold">LESS: CREDIT</span>
                    </div>
                    <div class="col-6 text-end">
                        <span class="fw-bold text-danger">- ₹${totalCreditAmount.toFixed(2)}</span>
                    </div>
                </div>

                <!-- Expected Received -->
                <div class="d-flex justify-content-between align-items-center bg-white p-2 border rounded mb-3">
                    <span class="text-primary fw-bold text-uppercase small">Expected Cash/Digital</span>
                    <span class="fs-4 fw-bold text-primary">₹${expectedReceived.toFixed(2)}</span>
                </div>

                <!-- Payment Modes -->
                <div class="mb-3 text-start">
                    <label class="small text-muted fw-bold d-block mb-1">PAYMENT MODES</label>
                    <div class="d-flex flex-wrap">${pModeHtml}</div>
                </div>

                ${creditAlertHtml}
                
                <hr>
                <div class="text-start">
                    <label class="form-label fw-bold text-dark mb-1">Verify Received Amount</label>
                    <input type="number" id="swal-received-amount" class="form-control form-control-lg border-primary text-center fw-bold" placeholder="Enter Amount Received">
                    <div class="form-text text-muted small">Enter the exact amount collected (Cash + Online) to proceed.</div>
                </div>

            </div>
        `;

        Swal.fire({
            title: 'Confirm Transactions',
            html: summaryHtml,
            icon: 'info',
            width: 500,
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            confirmButtonText: 'Verify & Process',
            cancelButtonText: 'Review',
            preConfirm: () => {
                const enteredAmount = parseFloat(document.getElementById('swal-received-amount').value);
                const expected = parseFloat(expectedReceived.toFixed(2));
                
                if (isNaN(enteredAmount)) {
                    Swal.showValidationMessage('Please enter the received amount');
                    return false;
                }
                if (enteredAmount !== expected) {
                    Swal.showValidationMessage(`Amount mismatch! Expected: ₹${expected} but entered: ₹${enteredAmount}`);
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Check credits rationale
                let creditRows = [];
                 checkedBoxes.each(function() {
                     let cid = $(this).val();
                     let pMode = $(`select[name="pMode[${cid}]"]`).val();
                     if(pMode === 'credit') creditRows.push(cid);
                });

                let processForm = () => $('form#billingForm').submit();

                if(creditRows.length > 0) {
                    // Recursive function for credit reasons
                    let creditIndex = 0;
                    let askCreditReason = () => {
                        if(creditIndex >= creditRows.length) {
                             processForm();
                             return;
                        }
                        let cid = creditRows[creditIndex];
                        let stbRaw = $(`input[name="stbno[${cid}]"]`).val();
                        let nameRaw = $(`input[name="name[${cid}]"]`).val();

                         Swal.fire({
                            title: 'Credit Reason Required',
                            html: `<div class="mb-2">Customer: <strong>${nameRaw}</strong></div>
                                   <div class="mb-3 text-muted small">STB: ${stbRaw}</div>
                                   <div>Please enter the reason for credit:</div>`,
                            input: 'text',
                            inputPlaceholder: 'e.g. Promised payment on Monday',
                            inputAttributes: { required: 'true' },
                            showCancelButton: true,
                            confirmButtonText: 'Save Reason'
                        }).then((res) => {
                            if(res.isConfirmed && res.value) {
                                let input = $('<input>').attr('type', 'hidden').attr('name', `remark2[${cid}]`).val(res.value);
                                $('form#billingForm').append(input);
                                creditIndex++;
                                askCreditReason();
                            }
                        });
                    };
                    askCreditReason();
                } else {
                    processForm();
                }
            }
        });
    });

    // Edit Modal Logic
    $(document).on('click', '.editStudentBtn', function () {
        var student_id = $(this).val();
        $.ajax({
            type: "GET", url: "code.php?student_id=" + student_id,
            success: function (response) {
                var res = JSON.parse(response);
                if(res.status == 200){
                    $('#student_id').val(res.data.id);
                    $('#amount').val(res.data.amount);
                    $('#phone').val(res.data.phone);
                    $('#description').val(res.data.description);
                    
                    $('#rc_dc').val(res.data.rc_dc);
                    $('#cusGroup').val(res.data.cusGroup);
                    $('#mso').val(res.data.mso);
                    $('#editCustomerAreaCode').val(res.data.customer_area_code);
                    $('#stbno').val(res.data.stbno);
                    $('#name').val(res.data.name);
                    $('#accessories').val(res.data.accessories);

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
            type: "POST", url: "code.php", data: formData, processData: false, contentType: false,
            success: function (response) {
                var res = JSON.parse(response);
                if(res.status == 200){
                    $('#studentEditModal').modal('hide');
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: res.message, showConfirmButton: false, timer: 1500
                    }).then(() => location.reload()); // Reload to reflect changes if needed
                } else {
                     $('#errorMessageUpdate').removeClass('d-none').text(res.message);
                }
            }
        });
    });

</script>
</body>
</html>
<?php 
} else{
    header("Location: logout.php");
} 
?>
