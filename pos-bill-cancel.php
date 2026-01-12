<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username'], $_SESSION['id'], $_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $session_username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Bill Manager | Admin Panel</title>
    
    <!-- Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
            :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --background-color: #f3f4f6;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            --text-main: #1f2937;
            --text-light: #6b7280;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
        }

        .main-container {
            padding: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1.5rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-control, .form-select {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .btn-custom {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.2s;
        }
        
        .filter-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            height: 100%;
        }

        .filter-header {
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--secondary-color);
            margin-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.5rem;
            display: block;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #6b7280;
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }

        .table-custom td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }
        
        .status-badge-active {
            background-color: #d1fae5;
            color: #065f46;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .status-badge-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        /* Fix for SweetAlert appearing behind Bootstrap Modal */
        .swal2-container {
            z-index: 20000 !important;
        }
    </style>
</head>

<body>

<?php
include 'admin-menu-bar.php';
include 'admin-menu-btn.php';
?>

<div class="main-container container-fluid">
    
    <!-- Filter Card -->
    <div class="custom-card">
        <div class="card-header-gradient">
            <h5 class="card-title"><i class="bi bi-sliders"></i> Filter POS Bills</h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d'); ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">From Bill No</label>
                        <input type="number" name="from_billno" value="<?php echo isset($_GET['from_billno']) ? $_GET['from_billno'] : ''; ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Bill No</label>
                        <input type="number" name="to_billno" value="<?php echo isset($_GET['to_billno']) ? $_GET['to_billno'] : ''; ?>" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <div class="filter-section">
                            <span class="filter-header"><i class="bi bi-person me-1"></i> Billed By</span>
                            <div class="d-flex flex-wrap gap-2">
                                <?php 
                                $userQuery = "SELECT username, name FROM user WHERE status = 1";
                                $userResult = mysqli_query($con, $userQuery);
                                
                                if(mysqli_num_rows($userResult) > 0) {
                                    foreach($userResult as $userRow) {
                                        $val = $userRow['username'];
                                        $name = $userRow['name'];
                                        $checked = (isset($_GET['filter']) && in_array($val, $_GET['filter'])) ? 'checked' : '';
                                        echo "<div class='form-check'><input class='form-check-input' type='checkbox' name='filter[]' value='$val' id='u_$val' $checked><label class='form-check-label small' for='u_$val'>$name</label></div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="filter-section">
                            <span class="filter-header"><i class="bi bi-check-circle me-1"></i> Status</span>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_filter[]" value="1" id="st_approve" <?php echo (isset($_GET['status_filter']) && in_array('1', $_GET['status_filter'])) || !isset($_GET['status_filter']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label small" for="st_approve">Approved</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_filter[]" value="0" id="st_cancel" <?php echo (isset($_GET['status_filter']) && in_array('0', $_GET['status_filter'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label small" for="st_cancel">Cancelled</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="filter-section">
                            <span class="filter-header"><i class="bi bi-credit-card me-1"></i> Payment Mode</span>
                            <div class="d-flex flex-wrap gap-3">
                                <?php
                                $modes = ['1'=>'Cash', '2'=>'GPay', '3'=>'PhonePe', '4'=>'Paytm', '5'=>'Credit']; // Check logic for IDs, standard is usually 1,2,3,4. Using array from logic.
                                // Previous file usage: 1=Cash, 2=Gpay, 3=Paytm, 4=Credit.
                                // Updated modes based on pos-billing.php:
                                $payModes = [
                                    '1' => 'Cash',
                                    '2' => 'Gpay',
                                    '3' => 'Paytm',
                                    '4' => 'Credit'
                                ];

                                foreach($payModes as $val => $name) {
                                     $checked = (isset($_GET['pMode_filter']) && in_array($val, $_GET['pMode_filter'])) ? 'checked' : '';
                                     echo "<div class='form-check'><input class='form-check-input' type='checkbox' name='pMode_filter[]' value='$val' id='pm_$val' $checked><label class='form-check-label small' for='pm_$val'>$name</label></div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4 text-center">
                        <button type="submit" class="btn btn-primary btn-custom shadow-sm"><i class="bi bi-search me-1"></i> Search Bills</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Card -->
    <div class="custom-card">
        <div class="card-header-gradient">
            <h5 class="card-title"><i class="bi bi-list-check"></i> Manage Bills</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Billed By</th>
                            <th>Date</th>
                            <th>Bill No</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Mode</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                            $from_date = $_GET['from_date'];
                            $to_date = $_GET['to_date'];
                            $from_billno = isset($_GET['from_billno']) ? $_GET['from_billno'] : '';
                            $to_billno = isset($_GET['to_billno']) ? $_GET['to_billno'] : '';

                            $filterCondition = "";
                            
                            // Username filter
                            if(isset($_GET['filter']) && !empty($_GET['filter'])) {
                                $filterValues = array_map(function($val) use ($con) { return "'" . mysqli_real_escape_string($con, $val) . "'"; }, $_GET['filter']);
                                $filterCondition .= " AND username IN (" . implode(",", $filterValues) . ")";
                            }

                            // Status filter
                            if(isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
                                $statusValues = array_map('intval', $_GET['status_filter']);
                                $filterCondition .= " AND status IN (" . implode(",", $statusValues) . ")";
                            }

                            // Pay Mode filter
                            if(isset($_GET['pMode_filter']) && !empty($_GET['pMode_filter'])) {
                                $pModeValues = array_map('intval', $_GET['pMode_filter']);
                                $filterCondition .= " AND pay_mode IN (" . implode(",", $pModeValues) . ")";
                            }

                            if (!empty($from_billno) && !empty($to_billno)) {
                                $filterCondition .= " AND bill_no BETWEEN '$from_billno' AND '$to_billno'";
                            }

                            $query = "SELECT pb.* FROM pos_bill pb 
                                      WHERE DATE(pb.entry_timestamp) BETWEEN '$from_date' AND '$to_date' 
                                      $filterCondition 
                                      ORDER BY pb.pos_bill_id DESC";
                            
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                $serial_number = 1;
                                $modeMap = [
                                    1 => 'Cash',
                                    2 => 'Gpay',
                                    3 => 'Paytm',
                                    4 => 'Credit'
                                ];

                                foreach($query_run as $row) {
                                    $statusBadge = $row['status'] == 1 
                                        ? '<span class="status-badge-active"><i class="bi bi-check-circle me-1"></i>Active</span>' 
                                        : '<span class="status-badge-cancelled"><i class="bi bi-x-circle me-1"></i>Cancelled</span>';
                                    
                                    $modeName = isset($modeMap[$row['pay_mode']]) ? $modeMap[$row['pay_mode']] : 'Unknown';
                                    $remarkShort = strlen($row['remark']) > 30 ? substr($row['remark'], 0, 30) . '...' : $row['remark'];
                        ?>
                        <tr>
                            <td class="text-secondary small"><?= $serial_number++; ?></td>
                            <td class="fw-bold"><?= $row['username']; ?></td>
                            <td class="text-primary fw-bold small">
                                <?= date('d-M-Y', strtotime($row['entry_timestamp'])); ?><br>
                                <span class="text-muted fw-normal"><?= date('h:i A', strtotime($row['entry_timestamp'])); ?></span>
                            </td>
                            <td class="fw-bold"><?= $row['bill_no']; ?></td>
                            <td><?= $row['cus_name']; ?></td>
                            <td><?= $row['cus_phone']; ?></td>
                            <td><span class="badge bg-secondary bg-opacity-10 text-dark border"><?= $modeName ?></span></td>
                            <td><?= $statusBadge; ?></td>
                            <td class="small text-muted" title="<?= htmlspecialchars($row['remark']) ?>"><?= htmlspecialchars($remarkShort) ?></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-primary btn-sm btn-custom py-1 px-3 shadow-sm edit-btn" 
                                        data-bs-toggle="modal" data-bs-target="#updateModal"
                                        data-pos_bill_id="<?= $row['pos_bill_id']; ?>"
                                        data-bill_no="<?= $row['bill_no']; ?>"
                                        data-cus_name="<?= $row['cus_name']; ?>"
                                        data-status="<?= $row['status']; ?>"
                                        data-pay_mode="<?= $row['pay_mode']; ?>"
                                        data-remark="<?= htmlspecialchars($row['remark']); ?>">
                                    <i class="bi bi-pencil-square me-1"></i> Manage
                                </button>
                            </td>
                        </tr>
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center py-5 text-muted'>No records found.</td></tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" data-bs-focus="false" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold"><i class="bi bi-gear-fill text-primary me-2"></i>Manage Bill <span id="modalBillNo" class="text-muted small ms-2"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="updateForm">
                    <input type="hidden" id="modal-pos_bill_id" name="pos_bill_id">
                    <input type="hidden" id="modal-old-remark" name="old_remark">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small text-uppercase">Customer</label>
                        <div class="fw-bold fs-5" id="modal-cus_name"></div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Payment Mode</label>
                            <select class="form-select" id="modal-pay_mode" name="pay_mode">
                                <option value="1">Cash</option>
                                <option value="2">Gpay</option>
                                <option value="3">Paytm</option>
                                <option value="4">Credit</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="modal-status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Cancelled</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary btn-custom" id="saveChangesBtn">Update Bill</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    const sessionUsername = "<?php echo $session_username ?? 'Admin'; ?>";
    let initialStatus, initialMode;

    $('.edit-btn').click(function() {
        const btn = $(this);
        const billId = btn.data('pos_bill_id');
        const billNo = btn.data('bill_no');
        const cusName = btn.data('cus_name');
        const status = btn.data('status');
        const payMode = btn.data('pay_mode');
        const remark = btn.data('remark');

        $('#modal-pos_bill_id').val(billId);
        $('#modalBillNo').text('(#' + billNo + ')');
        $('#modal-cus_name').text(cusName);
        $('#modal-status').val(status);
        $('#modal-pay_mode').val(payMode);
        $('#modal-old-remark').val(remark);

        // Store initial values to detect changes
        initialStatus = status;
        initialMode = payMode;
        
    });

    $('#saveChangesBtn').click(function() {
        // DEBUG: Alert to confirm click
        // alert('Button Clicked');

        const newStatus = String($('#modal-status').val());
        const newMode = String($('#modal-pay_mode').val());
        
        // Ensure initial values are strings
        const oldStatus = String(initialStatus);
        const oldMode = String(initialMode);

        console.log('Comparison:', 
            'NewS:', newStatus, 'OldS:', oldStatus, 
            'NewM:', newMode, 'OldM:', oldMode
        );

        // Check if anything changed
        if (newStatus === oldStatus && newMode === oldMode) {
             console.log('No changes detected');
             Swal.fire('No Changes', 'You haven\'t changed any settings. (Debug: ' + newStatus + '==' + oldStatus + ')', 'info');
             return;
        }

        Swal.fire({
            title: 'Enter Reason for Update',
            input: 'text',
            inputLabel: 'Please explain why you are making this change.',
            inputPlaceholder: 'Reason...',
            showCancelButton: true,
            confirmButtonText: 'Submit Update',
            inputValidator: (value) => {
                if (!value) {
                    return 'You must provide a reason!'
                }
                if (value.length < 5) {
                    return 'Reason is too short!'
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                const oldRemark = $('#modal-old-remark').val();
                
                // Construct Audit String
                const currentDate = new Date().toLocaleString();
                const modeNames = { '1': 'Cash', '2': 'Gpay', '3': 'Paytm', '4': 'Credit' };
                const statusNames = { '1': 'Active', '0': 'Cancelled' };
                
                let changeLog = [];
                if (newStatus != initialStatus) changeLog.push(`Status: ${statusNames[initialStatus]} -> ${statusNames[newStatus]}`);
                if (newMode != initialMode) changeLog.push(`Mode: ${modeNames[initialMode]} -> ${modeNames[newMode]}`);
                
                // Use dynamic username
                const auditTrail = `Updated by ${sessionUsername} on ${currentDate}: ${changeLog.join(', ')} - Reason: ${reason}`;
                const finalRemark = oldRemark ? oldRemark + ' | ' + auditTrail : auditTrail;

                // Prepare Data
                const payload = {
                    pos_bill_id: $('#modal-pos_bill_id').val(),
                    status: newStatus,
                    pay_mode: newMode,
                    remark: finalRemark
                };

                // Send AJAX
                Swal.fire({ title: 'Processing...', didOpen: () => Swal.showLoading() });

                $.ajax({
                    url: 'api/v1/pos/update-pos-bill-status.php',
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(payload),
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire('Success', 'Bill updated successfully.', 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', response.message || 'Update failed', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire('Error', 'Server error occurred.', 'error');
                        console.error(error);
                    }
                });
            }
        });
    });
});
</script>

</body>
</html>

<?php } else {
    header("Location: logout.php");
} ?>
