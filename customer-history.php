<?php 
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   

    // Determine Menu Bar
    if ($_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
        echo '<br>';
        include 'admin-menu-btn.php';
    } elseif ($_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
         echo '<br>';
        include 'sub-menu-btn.php';
    }

    $filtervalues = $_GET['search'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <link href="https://files.catbox.moe/sepcbf.png">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Customer History</title>

<!-- Custom SCSS-like Styles -->
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --success-color: #06d6a0;
        --danger-color: #ef476f;
        --warning-color: #ffd166;
        --text-dark: #2b2d42;
        --text-light: #8d99ae;
        --bg-light: #f8f9fa;
        --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f3f4f6;
        color: var(--text-dark);
    }

    .main-content {
        padding: 1.5rem 1rem;
    }

    /* CARD STYLES */
    .custom-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
        transition: transform 0.2s ease;
    }

    .card-header-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        padding: 1.5rem;
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-title {
        margin: 0;
        font-weight: 700;
        font-size: 1.25rem;
    }

    /* FORM ELEMENTS */
    .form-control-custom {
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: all 0.2s;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
    }

    .btn-search {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        background-color: var(--primary-color);
        border: none;
        color: white;
    }
    .btn-search:hover {
        background-color: var(--secondary-color);
        color: white;
    }

    /* TABLE STYLES */
    .table-container {
        padding: 0.5rem;
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-custom thead th {
        background-color: #f8fafb;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-custom tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
        color: #334155;
    }

    .table-custom tbody tr:hover {
        background-color: #f8fafc;
    }

    /* BADGES */
    .status-badge {
        padding: 0.35em 0.8em;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .badge-success { background-color: rgba(6, 214, 160, 0.1); color: var(--success-color); }
    .badge-danger { background-color: rgba(239, 71, 111, 0.1); color: var(--danger-color); }
    .badge-warning { background-color: rgba(255, 209, 102, 0.15); color: #d97706; }
    
    .pmode-badge {
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        padding: 0.25em 0.6em;
        border-radius: 4px;
        background: #e2e8f0;
        color: #475569;
    }

    /* Action Button */
    .btn-icon {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .btn-print { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
    .btn-print:hover { background-color: #ffeeba; }

    /* Make Bill Section */
    .make-bill-section {
        background: #fff;
        border-top: 5px solid var(--primary-color);
    }
</style>
</head>
<body>

<div class="container-fluid main-content">
    
    <!-- Search Section -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="custom-card mb-0">
                <div class="card-body p-4">
                    <form autocomplete="off" action="" method="GET">
                        <label class="form-label fw-bold text-muted text-uppercase small mb-2">Search Customer</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="search" pattern="[A-Za-z0-9\s]{3,}" autofocus required 
                                value="<?= htmlspecialchars($filtervalues) ?>" 
                                class="form-control form-control-custom border-start-0 ps-0" 
                                placeholder="Enter STB No, Phone (Min 3 chars)...">
                            <button type="submit" class="btn btn-search">Search Records</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- History Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="custom-card">
                <div class="card-header-gradient">
                    <div>
                        <h4 class="card-title"><i class="bi bi-clock-history me-2"></i>History & Transactions</h4>
                        <small class="opacity-75">View past bills and transactions</small>
                    </div>
                </div>
                <div class="card-body p-0 table-responsive">
                    <table class="table-custom text-nowrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bill No</th>
                                <th>Col Date</th>
                                <th>Bill Date</th>
                                <th>Time</th>
                                <th>Bill By</th>
                                <th>STB No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Remark</th>
                                <th>Remark 2</th>
                                <th>Mode</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Disc</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($filtervalues)) {
                                $query = "SELECT * FROM bill WHERE CONCAT(stbno, phone) LIKE '%$filtervalues%' ORDER BY bill_id DESC LIMIT 50";
                                $query_run = mysqli_query($con, $query);
                                $sno= 1;

                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($bill = mysqli_fetch_assoc($query_run)) {
                                        // Row Highlighting Logic
                                        $rowStyle = "";
                                        $rowClass = "";
                                        if($bill['adv_status'] == 1) {
                                            $rowStyle = "background-color: #f3e8ff;"; // Light purple for adv
                                        } elseif ($bill['pMode'] === 'credit') {
                                            $rowStyle = "background-color: #fffbeb;"; // Light yellow for credit
                                        }

                                        $statusClass = ($bill['status'] == 'approve') ? 'badge-success' : 'badge-danger';
                                        ?>
                                        <tr style="<?= $rowStyle ?>">
                                            <td class="fw-bold text-dark"><?= $sno++; ?></td>
                                            <td class="fw-bold text-dark">#<?= $bill['billNo']; ?></td>
                                            <td class="text-primary fw-medium"><?= formatDate($bill['date']); ?></td>
                                            <td class="text-secondary"><?= formatDate($bill['due_month_timestamp']); ?></td>
                                            <td class="small text-muted"><?= convertTo12HourFormat($bill['time']); ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center me-2" style="width:24px;height:24px;font-size:10px;">
                                                        <i class="bi bi-person-fill text-muted"></i>
                                                    </div>
                                                    <?= $bill['bill_by']; ?>
                                                </div>
                                            </td>
                                            <td class="fw-bold"><?= $bill['stbno']; ?></td>
                                            <td><?= $bill['name']; ?></td>
                                            <td><?= $bill['phone']; ?></td>
                                            <td><span class="status-badge <?= $statusClass; ?>"><?= ucfirst(strtolower($bill['status'])); ?></span></td>
                                            <td class="small text-muted text-wrap" style="max-width: 150px;"><?= $bill['description']; ?></td>
                                            <td class="small text-muted text-wrap" style="max-width: 150px;"><?= isset($bill['remark2']) ? $bill['remark2'] : ''; ?></td>
                                            <td><span class="pmode-badge"><?= $bill['pMode']; ?></span></td>
                                            <td class="text-end fw-bold text-success"><?= $bill['paid_amount']; ?></td>
                                            <td class="text-end text-danger"><?= $bill['discount']; ?></td>
                                            <td class="text-end fw-bolder text-dark"><?= $bill['Rs']; ?></td>
                                            <td class="text-center">
                                                <a href="prtindivbillrpt.php?billid=<?= $bill['bill_id']; ?>" target="_blank" 
                                                   class="btn-icon btn-print text-decoration-none" title="Print Bill">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="15" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-1 d-block mb-2"></i>No records found matching your search.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="15" class="text-center py-5 text-muted"><i class="bi bi-search fs-1 d-block mb-2"></i>Enter an STB Number or Phone to search.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Bill Section (Only visible if search has 1 exact match logic usually, or reusing previous record) -->
    <?php
    // Logic: If search returns result, allow making another bill for that customer easily
    if (!empty($filtervalues)) {
        // Re-run similar query to check if we can populate the form
        // Using strict matching for auto-fill context might be better, but staying consistent with original logic:
        // Original logic: select * ... AND currentMonth/Year check? 
        // Wait, original logic lines 220 checks for BILLS in current month. 
        // If they paid this month, show this form?
        
        $query1 = "SELECT * FROM bill WHERE CONCAT(stbno, phone) LIKE '%$filtervalues%' AND MONTH('$currentDate')='$currentMonth' AND YEAR('$currentDate')='$currentYear' ORDER BY bill_id DESC LIMIT 1";
        $query_run1 = mysqli_query($con, $query1);
    
        if (mysqli_num_rows($query_run1) > 0) {
            $row = mysqli_fetch_assoc($query_run1);
    ?>
    <div class="row justify-content-center pt-3">
        <div class="col-md-10 col-lg-8">
            <div class="custom-card make-bill-section">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="card-title text-primary"><i class="bi bi-lightning-charge-fill me-2"></i>Quick Bill Action</h5>
                    <p class="text-muted mb-0 small">Create a new bill for: <strong><?= $row['name'] ?></strong> (<?= $row['stbno'] ?>)</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">MSO</label>
                                <input readonly type="text" name="mso" value="<?= $row['mso']; ?>" class="form-control form-control-custom bg-light">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">STB Number</label>
                                <input readonly type="text" name="stbno" value="<?= $row['stbno']; ?>" class="form-control form-control-custom bg-light">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Customer Name</label>
                                <input readonly type="text" name="name" value="<?= $row['name']; ?>" class="form-control form-control-custom bg-light">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Phone</label>
                                <input readonly type="number" name="phone" value="<?= $row['phone']; ?>" class="form-control form-control-custom bg-light">
                            </div>
                            
                            <div class="col-12"><hr class="my-2 text-muted opacity-25"></div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Payment Mode</label>
                                <select name="pMode" class="form-select form-control-custom">
                                    <option value="cash" selected>Cash</option>
                                    <option value="paytm">Paytm</option>
                                    <option value="gpay">GPay</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Amount</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">₹</span>
                                    <input type="number" name="paid_amount" class="form-control form-control-custom border-start-0" required placeholder="0.00">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Remark</label>
                                <input type="text" name="description" class="form-control form-control-custom" placeholder="Optional remark" required>
                            </div>
                            
                            <div class="col-12 mt-4 text-end">
                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow-sm">
                                    <i class="bi bi-check-circle-fill me-2"></i>Submit Bill
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php 
        } 
    }
    ?>

</div>

<!-- PHP Logic for Bill Submission -->
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stbno        = mysqli_real_escape_string($con, $_POST["stbno"]);
    $name         = $_POST["name"];
    $phone        = $_POST["phone"];
    $mso          = $_POST["mso"];
    $description  = $_POST["description"];
    $pMode        = $_POST["pMode"];
    $paid_amount  = $_POST["paid_amount"];
    $bill_status  = 'approve';
    $discount     = 0;
    $Rs           = $paid_amount;
    $oldMonthBal  = 0;
    $printStatus  = 0;

    // Bill Number Logic
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

    $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, pMode, oldMonthBal, paid_amount, discount, Rs, adv_status, due_month_timestamp, status, printStatus) 
            VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', 0, '$currentDateTime', '$bill_status', '$printStatus')";

    if ($con->query($sql) === TRUE) {
        // Update Income/Expense logic
        $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$currentDate' AND status = 'approve'";
        $result = $con->query($sqlSum);
        $row = $result->fetch_assoc();
        $sumPaidAmount = $row["total_Rs"];

        $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
        $resultCheck = $con->query($sqlCheck);

        if ($resultCheck->num_rows > 0) {
            $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
            $con->query($sqlUpdate);
        } else {
            $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount)";
            $con->query($sqlInsert);
        }

        echo "<script>
        setTimeout(function(){
            window.location.href = 'prtindivbulkbilldash.php';
        }, 200);
        </script>";

    } else {
        echo "Error inserting data: " . $con->error;
        ?>
        <div class="d-flex justify-content-center mt-4">
             <div class="alert alert-danger shadow-sm">
                <h4 class="alert-heading"><i class="bi bi-exclamation-triangle-fill me-2"></i>Error!</h4>
                <p>Could not save the bill. Please try again.</p>
                <hr>
                <p class="mb-0"><?php echo $con->error; ?></p>
             </div>
        </div>
        <?php
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'; ?>

<?php 
} else {
    header("Location: logout.php");
} 
?>
