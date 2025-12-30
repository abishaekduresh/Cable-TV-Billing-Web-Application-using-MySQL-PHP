<?php
session_start();
include "dbconfig.php";
include 'preloader.php';
require "component.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    
    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'admin-menu-btn.php';
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'sub-menu-btn.php';
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
            $billDateInput = isset($_POST['billDate']) ? $_POST['billDate'] : $currentDate; 
            $billDateObj = new DateTime($billDateInput);
            $targetMonth = $billDateObj->format('m');
            $targetYear = $billDateObj->format('Y');

            $groupID_check = mysqli_real_escape_string($con, $_POST["group_id"]);
            $checkDuplicateQuery = "SELECT * FROM billgroupdetails WHERE group_id = '$groupID_check' AND MONTH(date) = '$targetMonth' AND YEAR(date) = '$targetYear' AND status = 'approve'";
            $duplicateResult = $con->query($checkDuplicateQuery);

            if ($duplicateResult->num_rows > 0) {
                echo "<script>alert('Bill already exists for this group in the selected month/year!'); window.location.href='billing-group-dashboard.php';</script>";
                exit();
            }

            if ($currentDay === '01' && !isset($_POST['billDate'])) {
                // Check if there is any bill entry for the next month
                $checkNextMonthQuery = "SELECT billNo FROM billgroupdetails WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(DATE_ADD('$currentDate', INTERVAL 1 MONTH), '%Y-%m') LIMIT 1";
                $result = $con->query($checkNextMonthQuery);
            
                if ($result->num_rows > 0) {
                    $billNo = 1;
                } else {
                    $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM billgroupdetails WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
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
                // Retrieve the next billNo for the target date's month and year
                $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM billgroupdetails WHERE DATE_FORMAT(date, '%Y-%m') = '$targetYear-$targetMonth'";
                $result = $con->query($getBillNoQuery);
            
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $billNo = $row["maxBillNo"] + 1;
                } else {
                    $billNo = 1;
                }
            }
    
            ///////        Insert Data into billGroupDetails    ////////////

            // Generating Unique Transaction ID
            // Format: TXN_YYYYMMDD_RandomHex
            $txnDateStr = date('Ymd');
            $uniqueHex = strtoupper(bin2hex(random_bytes(3))); // 6 Chars
            $transaction_id = "TXN_" . $txnDateStr . "_" . $uniqueHex;

            $Rs =0;
                        
            $pMode = mysqli_real_escape_string($con, $_POST["pMode"]);
            $oldMonthBal = mysqli_real_escape_string($con, $_POST["oldMonthBal"]);
            $billAmount = mysqli_real_escape_string($con, $_POST["billAmt"]);
            $discount = mysqli_real_escape_string($con, $_POST["discount"]);
            $phone = mysqli_real_escape_string($con, $_POST["phone"]);
            $groupID = mysqli_real_escape_string($con, $_POST["group_id"]);
            $groupName = mysqli_real_escape_string($con, $_POST["groupName"]);
            $Rs = $billAmount;

            $Rs = $Rs + $oldMonthBal;
            $Rs = $Rs - $discount;

            $status = 'approve';
            
            // --- ADVANCE STATUS ---
            // User request: "Adv? toggle button value not storing on db"
            // We must respect the manual toggle. The JS validation ensures it's checked if date is Future.
            // If user checks it manually for current month, that should also be respected.
            $advance_bill = isset($_POST['advance_bill']) ? 1 : 0;
            
            // Prepare the INSERT query
            $sql = "INSERT INTO `billgroupdetails` (`billNo`, `transaction_id`, `date`, `time`, `billBy`, `group_id`, `groupName`, `phone`, `pMode`, `oldMonthBal`, `billAmount`, `discount`, `Rs`, `status`, `created_at`, `ad`) 
            VALUES ('$billNo', '$transaction_id', '$billDateInput', '$currentTime', '$session_username', '$groupID', '$groupName', '$phone', '$pMode', '$oldMonthBal', '$billAmount', '$discount', '$Rs', '$status', '$currentDateTime', '$advance_bill')";

            if ($con->query($sql) === true) {
                // Success
            } else {
                echo "bill Group Details - Error: " . $con->error;
            }
            
            // Retrieve checkbox values
            $checkboxValues = isset($_POST["options"]) ? $_POST["options"] : [];

            // Process selected checkboxes
            foreach ($checkboxValues as $customerId) {
                $groupID1 = mysqli_real_escape_string($con, $_POST["groupID1"][$customerId]);
                $stbNo = mysqli_real_escape_string($con, $_POST["stbno"][$customerId]);
                $mso = mysqli_real_escape_string($con, $_POST["mso"][$customerId]);
                $cusName = mysqli_real_escape_string($con, $_POST["cusName"][$customerId]);
                $remark = mysqli_real_escape_string($con, $_POST["description"][$customerId]);
                $status = 'approve';
                
                $sql = "INSERT INTO billgroup (billNo, transaction_id, date, time, group_id, mso, stbNo, name, remark, status, created_at)
                    VALUES ('$billNo', '$transaction_id', '$billDateInput', '$currentTime', '$groupID1', '$mso', '$stbNo', '$cusName', '$remark','$status', '$currentDateTime')";

                if ($con->query($sql) === TRUE) {
                    // Logic for sum calculation
                    $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM billgroupdetails WHERE date = '$billDateInput' AND status = 'approve'";
                    $result = $con->query($sqlSum);
                    $row = $result->fetch_assoc();
                    $sumPaidAmount = $row["total_Rs"];
                    if($sumPaidAmount == '') { $sumPaidAmount = 0; }

                    // Check if a record exists in in_ex table
                    $sqlCheck = "SELECT * FROM in_ex WHERE date = '$billDateInput' AND category_id = 12 AND subcategory_id = 36 AND status = 1";
                    $resultCheck = $con->query($sqlCheck);

                    if ($resultCheck->num_rows > 0) {
                        $sqlUpdate = "UPDATE in_ex SET type='Income', date='$billDateInput', time = '$currentTime',username='Auto',category_id = 12, subcategory_id = 36, remark='', amount = $sumPaidAmount WHERE date = '$billDateInput' AND category_id = 12 AND subcategory_id = 36 AND status = 1";
                        $con->query($sqlUpdate);
                    } else {
                        $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount, status) VALUES ('Income', '$billDateInput', '$currentTime','Auto', 12, 36,'', $sumPaidAmount,'1')";
                        $con->query($sqlInsert);
                    }
                    continue;

                } else {
                    echo "Error inserting data: " . $con->error;
                    break;
                }
            }

        function redirect($url) {
            echo "<script>
            setTimeout(function(){
                window.location.href = '$url';
            }, 200);
        </script>";
        }

        $url = "prtgroupbilldash.php?group_id=$groupID&date=$billDateInput";
        redirect($url);
    }
    $group_id = ''; 
    $phone = '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Billing Dashboard</title>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
            --text-dark: #2b2d42;
            --bg-light: #f8f9fa;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-dark);
        }

        /* Card Styles */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1rem 1.5rem;
            color: white;
            border-radius: 16px 16px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        /* Sticky Footer for Action Bar */
        .action-bar-sticky {
            position: sticky;
            bottom: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-top: 1px solid #e5e7eb;
            box-shadow: 0 -4px 6px -1px rgba(0,0,0,0.05);
            padding: 1rem 0;
        }

        /* Floating Input Labels */
        .billing-input-group {
            position: relative;
        }
        .billing-input-group label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        /* Table Styles */
        .table-custom th {
            background-color: #f9fafb;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
            font-weight: 600;
        }
        
        /* Advance Toggle */
        .adv-toggle-wrapper {
            background: #fff0f3;
            border: 1px solid #ffccd5;
            padding: 0.25rem 0.5rem;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        .adv-toggle-wrapper.active {
            background: #e6ffFA;
            border-color: #63b3ed;
        }
    </style>
</head>
<body>

    <div class="container-fluid px-4 py-4">
        <!-- Search & Filter Section -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="custom-card">
                    <div class="card-header-gradient">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-collection me-2"></i>
                            <h5 class="card-title">Group Billing Dashboard</h5>
                        </div>
                        <a href="billing-dashboard.php" class="btn btn-light btn-sm fw-bold text-primary">
                            <i class="bi bi-person me-1"></i> Individual Bill
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-5">
                                    <label class="form-label fw-bold text-secondary">Select Group</label>
                                    <select name="group_id" class="form-select">
                                        <option value="" disable selected>Select Group</option>
                                        
                                        <?php
                                        $query = "SELECT * FROM groupinfo WHERE group_id != '1' AND group_id != '2' LIMIT 100";
                                        $result = mysqli_query($con, $query);
                                        $selectedValue = isset($_GET['group_id']) ? $_GET['group_id'] : '';
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $optionValueID = $row['group_id'];
                                            $optionValue = $row['groupName'];
                                            ?>
                                            <option value="<?php echo $optionValueID; ?>" <?php if ($optionValueID == $selectedValue) echo 'selected'; ?>><?php echo $optionValue; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-secondary">Billing Month</label>
                                    <input type="month" name="billing_month" class="form-control" value="<?php echo $displayDate; ?>">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">
                                        <i class="bi bi-search me-2"></i>Load
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer List & Action Section -->
        <div class="row">
            <div class="col-md-12">
                <form action="" method="POST" id="billingForm">
                    <div class="custom-card">
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                                <table class="table table-hover table-custom mb-0 align-middle">
                                    <thead class="sticky-top bg-white shadow-sm" style="z-index: 5;">
                                        <tr>
                                            <th class="ps-4">#</th>
                                            <th>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                </div>
                                            </th>
                                            <th>Group Name</th>
                                            <th>MSO</th>
                                            <th>STB Number</th>
                                            <th>Customer Name</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['group_id'])) {
                                            $group_id = $_GET['group_id'];
                                            $query = "SELECT * FROM customer WHERE cusGroup = '$group_id' AND rc_dc='1' AND cusGroup!='1' LIMIT 300";
                                            $query_run = mysqli_query($con, $query);

                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1;
                                                foreach ($query_run as $customer) {
                                                    $cusGroupID = mysqli_real_escape_string($con, $customer['cusGroup']);
                                                    
                                                    // Check if already billed
                                                    $nestedQuery = "SELECT * FROM billgroup JOIN billgroupdetails ON billgroup.group_id = billgroupdetails.group_id
                                                        WHERE billgroupdetails.group_id = '$cusGroupID' AND billgroupdetails.status = 'approve' AND billgroup.status = 'approve'
                                                        AND MONTH(billgroupdetails.`date`) = '$targetMonth' AND YEAR(billgroupdetails.`date`) = '$targetYear'
                                                        AND MONTH(billgroup.`date`) = '$targetMonth' AND YEAR(billgroup.`date`) = '$targetYear'";

                                                    $nestedQuery_run = mysqli_query($con, $nestedQuery);
                                                    $disableButton = (mysqli_num_rows($nestedQuery_run) > 0);
                                                    
                                                    // Group Name Fetch
                                                    $grpNameRow = mysqli_fetch_assoc(mysqli_query($con, "SELECT groupName FROM groupinfo WHERE group_id='$cusGroupID' LIMIT 1"));
                                                    $fetchedGroupName = $grpNameRow['groupName'];
                                                    ?>
                                                    <tr class="<?= $disableButton ? 'bg-light text-muted' : ''; ?>">
                                                        <td class="ps-4 fw-bold text-secondary"><?= $serial_number++; ?></td>
                                                        <td>
                                                            <?php if (!$disableButton): ?>
                                                                <div class="form-check">
                                                                    <input type="checkbox" name="options[]" value="<?= $customer['id']; ?>" class="form-check-input" required checked>
                                                                </div>
                                                            <?php else: ?>
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="groupID1[<?= $customer['id']; ?>]" value="<?= $customer['cusGroup']; ?>">
                                                            <span class="fw-bold"><?= $fetchedGroupName; ?></span>
                                                        </td>
                                                        <td><input type="hidden" name="mso[<?= $customer['id']; ?>]" value="<?= $customer['mso']; ?>"><?= $customer['mso']; ?></td>
                                                        <td class="font-monospace text-primary"><input type="hidden" name="stbno[<?= $customer['id']; ?>]" value="<?= $customer['stbno']; ?>"><?= $customer['stbno']; ?></td>
                                                        <td class="fw-bold"><input type="hidden" name="cusName[<?= $customer['id']; ?>]" value="<?= $customer['name']; ?>"><?= $customer['name']; ?></td>
                                                        <td><input type="hidden" name="description[<?= $customer['id']; ?>]" value="<?= $customer['description']; ?>"><?= $customer['description']; ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="7" class="text-center py-4 text-muted">No customers found in this group.</td></tr>';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php
                    // Fetch Group Details for Footer
                    if(isset($group_id) && !empty($group_id)) {
                        $groupInfoQuery = mysqli_query($con, "SELECT group_id, groupName, phone, billAmt FROM groupinfo WHERE group_id = '$group_id'");
                        $groupInfo = ($groupInfoQuery && mysqli_num_rows($groupInfoQuery) > 0) ? mysqli_fetch_assoc($groupInfoQuery) : null;
                        
                        if ($groupInfo) {
                            $groupID = $groupInfo["group_id"];
                            $groupName = $groupInfo["groupName"];
                            $phone = $groupInfo["phone"];
                            $billAmt = $groupInfo["billAmt"] ?: "0";
                        } else {
                            // Defaults if ID provided but not found
                            $groupName = ""; $phone = ""; $billAmt = "0"; $groupID = "";
                        }
                    } else {
                        $groupName = ""; $phone = ""; $billAmt = "0"; $groupID = "";
                    }
                    ?>

                    <!-- Sticky Action Bar -->
                    <div class="action-bar-sticky" id="actionBar">
                        <div class="container">
                            <div class="row g-2 align-items-center justify-content-center">
                                <!-- Hidden Inputs -->
                                <input type="hidden" name="group_id" value="<?= $group_id; ?>">
                                <input type="hidden" name="groupName" value="<?= $groupName; ?>">
                                <input type="hidden" name="phone" value="<?= $phone; ?>">
                                
                                <!-- Bill Date -->
                                <div class="col-md-2">
                                    <div class="billing-input-group">
                                        <label>Billing Date</label>
                                        <?php
                                            $inputMinDate = isset($displayDate) ? date('Y-m-01', strtotime($displayDate . '-01')) : date('Y-m-01');
                                            $inputMaxDate = isset($displayDate) ? date('Y-m-t', strtotime($displayDate . '-01')) : date('Y-m-t');
                                        ?>
                                        <input type="date" name="billDate" id="billDateInput" 
                                            min="<?php echo $inputMinDate; ?>" 
                                            max="<?php echo $inputMaxDate; ?>" 
                                            value="<?php echo isset($displayDate) ? date('Y-m-d', strtotime($displayDate . '-01')) : $currentDate; ?>" 
                                            class="form-control fw-bold border-primary text-primary">
                                    </div>
                                </div>

                                <!-- Payment Mode -->
                                <div class="col-md-2">
                                    <div class="billing-input-group">
                                        <label>Pay Mode</label>
                                        <select name="pMode" class="form-select fw-bold">
                                            <option value="cash" selected>Cash</option>
                                            <option value="paytm">Paytm</option>
                                            <option value="gpay">G Pay</option>
                                            <!-- <option value="credit">Credit</option> -->
                                        </select>
                                    </div>
                                </div>

                                <!-- Old Bal -->
                                <div class="col-md-1">
                                    <div class="billing-input-group">
                                        <label>Old Bal</label>
                                        <input type="number" name="oldMonthBal" value="0" class="form-control fw-bold text-danger">
                                    </div>
                                </div>
                                
                                <!-- Bill Amt -->
                                <div class="col-md-1">
                                    <div class="billing-input-group">
                                        <label>Bill Amt</label>
                                        <input readonly type="number" name="billAmt" value="<?= $billAmt; ?>" class="form-control fw-bold text-success bg-light">
                                    </div>
                                </div>

                                <!-- Discount -->
                                <div class="col-md-1">
                                    <div class="billing-input-group">
                                        <label>Discount</label>
                                        <input type="number" name="discount" value="0" class="form-control fw-bold text-warning">
                                    </div>
                                </div>

                                <!-- Advance Toggle (Auto Calculated) -->
                                <div class="col-md-1">
                                    <div class="adv-toggle-wrapper" id="advWrapper">
                                        <label class="small fw-bold mb-1 text-danger">Adv?</label>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input" type="checkbox" name="advance_bill" id="advanceBillCheck" value="1" style="transform: scale(1.1);">
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Button -->
                                <div class="col-md-2 text-end">
                                    <button type="button" class="btn btn-primary w-100 py-2 rounded-3 shadow-sm fw-bold" id="confirmButton">
                                        <i class="bi bi-check-lg me-1"></i> Confirm Bill
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Modal REMOVED -->

                </form>
            </div>
        </div>
    </div>

    <script>
        // --- Advance Bill Auto-Calculation Logic ---
        const billDateInput = document.getElementById('billDateInput');
        const advanceBillCheck = document.getElementById('advanceBillCheck');
        const advWrapper = document.getElementById('advWrapper');
        const billingForm = document.getElementById('billingForm');

        function checkAdvanceStatus() {
            if(!billDateInput.value) return;

            // Safe Parse
            const [sYear, sMonth] = billDateInput.value.split('-').map(Number);
            const selectedDate = new Date(sYear, sMonth - 1, 1);
            
            const today = new Date();
            const currentMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);

            if (selectedDate > currentMonthStart) {
                // Future Month -> Advance
                advanceBillCheck.checked = true;
                advWrapper.classList.add('active');
            } else {
                // Current or Past -> Regular
                advanceBillCheck.checked = false;
                advWrapper.classList.remove('active');
            }
        }

        // Attach Listener
        if(billDateInput) {
            billDateInput.addEventListener('change', checkAdvanceStatus);
            // Run on load
            checkAdvanceStatus();
        }

        // --- Checkbox Logic (Strict "All Checked" Required) ---
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('input[name="options[]"]');
        const confirmBtn = document.getElementById('confirmButton');
        
        function updateState() {
            // Check if every single checkbox is checked
            const allChecked = (checkboxes.length > 0) && Array.from(checkboxes).every(c => c.checked);
            
            // Update Master Checkbox UI
            if(checkAll) {
                checkAll.checked = allChecked;
                // If there are no checkboxes (empty table), uncheck master
                if(checkboxes.length === 0) checkAll.checked = false;
            }

            // Strict Logic: Button enabled ONLY if ALL are checked
            if(confirmBtn) confirmBtn.disabled = !allChecked;
        }

        // Master Toggle Listener
        if(checkAll) {
            checkAll.addEventListener('change', function() {
                const isChecked = this.checked;
                checkboxes.forEach(c => c.checked = isChecked);
                updateState();
            });
        }

        checkboxes.forEach(c => c.addEventListener('change', updateState));
        
        // Init
        updateState();

        // --- SweetAlert2 Validation & Confirmation ---
        if(confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                
                // Valdiation Logic
                if(!billDateInput.value) return;
                
                // Parse Input explicitly to avoid Timezone issues with new Date("YYYY-MM")
                const [sYear, sMonth] = billDateInput.value.split('-').map(Number);
                const selectedDate = new Date(sYear, sMonth - 1, 1); // Local Time Midnight
                
                const today = new Date();
                const currentMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);

                // Calculate Month Difference
                const diffMonths = (selectedDate.getFullYear() - today.getFullYear()) * 12 + (selectedDate.getMonth() - today.getMonth());

                // Rule 1: Cannot be in the past
                if (selectedDate < currentMonthStart) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid Date',
                        text: 'Billing Date cannot be in the past! Please select the Current Month or a Future Month.',
                    });
                    return; 
                }

                // Rule 2: Cannot be more than 12 months in advance
                if (diffMonths > 12) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Date Out of Range',
                        text: 'Advance billing is allowed for up to 12 months only (Current + 12 Months).',
                    });
                    return;
                }

                // Future Check strictly based on calc
                const isFuture = selectedDate > currentMonthStart;
                const isAdvanceChecked = advanceBillCheck.checked;

                // Requirement: if Future, Adv? must be true
                if (isFuture && !isAdvanceChecked) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'For future billing months, the "Adv?" (Advance Bill) option must be checked!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    });
                    return; // Stop submission
                }

                // Gather Dynamic Details
                const checkedCount = Array.from(checkboxes).filter(c => c.checked).length;
                const groupName = document.querySelector('input[name="groupName"]').value;
                const oldBal = parseFloat(document.querySelector('input[name="oldMonthBal"]').value) || 0;
                const discount = parseFloat(document.querySelector('input[name="discount"]').value) || 0;
                const billAmt = parseFloat(document.querySelector('input[name="billAmt"]').value) || 0;
                
                // Calculation: Bill Amount (Base) + Old Bal - Discount
                // Note: The logic in PHP is $Rs = $billAmount + $oldMonthBal - $discount;
                const totalRs = (billAmt + oldBal - discount).toFixed(2);
                
                const billDate = billDateInput.value;

                // If validation passed, show confirmation with Details
                Swal.fire({
                    title: '<span class="text-primary">Confirm Transaction</span>',
                    html: `
                        <div class="text-start bg-light p-3 rounded border">
                            <div class="d-flex justify-content-between mb-2 border-bottom pb-2">
                                <span class="fw-bold text-muted">Group:</span>
                                <span class="fw-bold text-dark">${groupName}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Bill Date:</span>
                                <span class="fw-bold">${billDate}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Selected Customers:</span>
                                <span class="fw-bold text-primary">${checkedCount}</span>
                            </div>
                             <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Type:</span>
                                <span class="badge ${isAdvanceChecked ? 'bg-danger' : 'bg-success'}">${isAdvanceChecked ? 'Advance Bill' : 'Regular Bill'}</span>
                            </div>
                            <hr class="my-2">
                             <div class="d-flex justify-content-between">
                                <span class="fw-bold text-secondary">Total Payable:</span>
                                <span class="fw-bold text-success fs-5">₹ ${totalRs}</span>
                            </div>
                        </div>
                        <p class="mt-3 text-muted small">Are you sure you want to generate bills for these customers?</p>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#4361ee',
                    cancelButtonColor: '#ef476f',
                    confirmButtonText: '<i class="bi bi-check-lg"></i> Yes, Submit!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        billingForm.submit();
                    }
                });
            });
        }

    </script>
</body>
</html>

<?php } else {
	header("Location: index.php");
} ?>
