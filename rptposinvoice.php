<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role'])) {
    $session_username = $_SESSION['username'];
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php include 'favicon.php'; ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>POS Invoice Report | Admin Panel</title>
        
        <!-- Dependencies -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

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
            
            .badge-soft {
                padding: 0.4em 0.8em;
                border-radius: 6px;
                font-weight: 600;
                font-size: 0.75rem;
            }

            .total-row td {
                background-color: #f8fafc;
                font-weight: 700;
                border-top: 2px solid #e2e8f0;
                color: #1f2937;
            }
        </style>
    </head>

    <body>

    <?php
        if($_SESSION['role']=='admin'){
            include 'admin-menu-bar.php';
            include 'admin-menu-btn.php';
        }else{
            include 'menu-bar.php';
            ?><div class="mt-3"></div><?php
            include 'sub-menu-btn.php';
        }
    ?>

    <div class="main-container container-fluid">
        
        <!-- Filter Card -->
        <div class="custom-card">
            <div class="card-header-gradient">
                <h5 class="card-title"><i class="bi bi-funnel-fill"></i> POS Invoice Filters</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="GET">
                    <div class="row g-3">
                        <!-- Date & Bill Range -->
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d'); ?>" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); ?>" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">From Bill #</label>
                            <input type="number" name="from_billno" value="<?php echo isset($_GET['from_billno']) ? $_GET['from_billno'] : ''; ?>" class="form-control" placeholder="Start No">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Bill #</label>
                            <input type="number" name="to_billno" value="<?php echo isset($_GET['to_billno']) ? $_GET['to_billno'] : ''; ?>" class="form-control" placeholder="End No">
                        </div>

                        <!-- Checkbox Filters -->
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
                                    $modes = ['1'=>'Cash', '2'=>'GPay', '3'=>'PhonePe', '4'=>'Paytm', '5'=>'Credit'];
                                    foreach($modes as $val => $name) {
                                         $checked = (isset($_GET['pMode_filter']) && in_array($val, $_GET['pMode_filter'])) ? 'checked' : '';
                                         echo "<div class='form-check'><input class='form-check-input' type='checkbox' name='pMode_filter[]' value='$val' id='pm_$val' $checked><label class='form-check-label small' for='pm_$val'>$name</label></div>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-center">
                            <button type="submit" class="btn btn-primary btn-custom shadow-sm"><i class="bi bi-search me-1"></i> Generate Report</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="custom-card">
            <div class="card-header-gradient">
                <h5 class="card-title"><i class="bi bi-receipt"></i> Invoice List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Billed By</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th class="text-end">Total Amount</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                require 'dbconfig.php';
                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];
                                    $from_billno = isset($_GET['from_billno']) ? $_GET['from_billno'] : '';
                                    $to_billno = isset($_GET['to_billno']) ? $_GET['to_billno'] : '';

                                    // Filter Logic
                                    $filterCondition = "";
                                    if(isset($_GET['filter']) && !empty($_GET['filter'])) {
                                        $filterValues = array_map(function($val) use ($con) { return "'" . mysqli_real_escape_string($con, $val) . "'"; }, $_GET['filter']);
                                        $filterCondition .= " AND pb.username IN (" . implode(",", $filterValues) . ")";
                                    }
                                    if(isset($_GET['status_filter']) && !empty($_GET['status_filter'])) {
                                         $statusValues = array_map('intval', $_GET['status_filter']);
                                         $filterCondition .= " AND pb.status IN (" . implode(",", $statusValues) . ")";
                                    }
                                    if(isset($_GET['pMode_filter']) && !empty($_GET['pMode_filter'])) {
                                        $pModeValues = array_map('intval', $_GET['pMode_filter']);
                                        $filterCondition .= " AND pb.pay_mode IN (" . implode(",", $pModeValues) . ")";
                                    }
                                    if (!empty($from_billno) && !empty($to_billno)) {
                                        $filterCondition .= " AND pb.bill_no BETWEEN '$from_billno' AND '$to_billno'";
                                    }

                                    $query = "SELECT pb.*, u.name as billed_by_name,
                                                COALESCE(SUM(pbi.price * pbi.qty), 0) as sub_total, 
                                                COUNT(pbi.pos_bill_id) as calculated_item_count 
                                              FROM pos_bill pb 
                                              LEFT JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id 
                                              LEFT JOIN user u ON pb.username = u.username
                                              WHERE DATE(pb.entry_timestamp) BETWEEN '$from_date' AND '$to_date' 
                                              $filterCondition 
                                              GROUP BY pb.pos_bill_id 
                                              ORDER BY pb.pos_bill_id DESC";
                                              
                                    $query_run = mysqli_query($con, $query);

                                    // Mappings
                                    // User Map removed - now dynamic
                                    $modeMap = [
                                        1 => ['Cash', 'secondary'],
                                        2 => ['GPay', 'primary'],
                                        3 => ['PhonePe', 'primary'],
                                        4 => ['Paytm', 'primary'],
                                        5 => ['Credit', 'warning']
                                    ];

                                    $grand_total = 0;
                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;
                                        foreach($query_run as $row) {
                                            $final_total = $row['sub_total'] - $row['discount'];
                                            $grand_total += $final_total;
                                            
                                            $statusBadge = $row['status'] == 1 ? '<span class="badge bg-success bg-opacity-10 text-white">Approved</span>' : '<span class="badge bg-danger bg-opacity-10 text-white">Cancelled</span>';
                                            
                                            $entryDate = date('Y-m-d', strtotime($row['entry_timestamp']));
                                            $entryTime = date('H:i:s', strtotime($row['entry_timestamp']));
                                            
                                            $billedBy = !empty($row['billed_by_name']) ? $row['billed_by_name'] : $row['username'];
                                            
                                            $modeId = $row['pay_mode'];
                                            $modeName = isset($modeMap[$modeId]) ? $modeMap[$modeId][0] : 'Unknown';
                                            $modeColor = isset($modeMap[$modeId]) ? $modeMap[$modeId][1] : 'secondary';
                                            ?>
                                            <tr>
                                                <td class="text-secondary small"><?= $serial_number++; ?></td>
                                                <td>
                                                    <div class="fw-bold text-dark"><?= formatDate($entryDate); ?></div>
                                                    <div class="small text-muted"><?= convertTo12HourFormat($entryTime); ?></div>
                                                </td>
                                                <td class="fw-bold text-primary"><?= $row['bill_no']; ?></td>
                                                <td><span class="badge bg-light text-dark border"><?= $billedBy ?></span></td>
                                                <td>
                                                    <div class="fw-bold text-dark"><?= $row['cus_name']; ?></div>
                                                    <div class="small text-muted"><?= $row['cus_phone']; ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark border view-items-btn" style="cursor: pointer;" data-id="<?= $row['pos_bill_id']; ?>" data-billno="<?= $row['bill_no']; ?>">
                                                        <i class="bi bi-eye-fill me-1 text-primary"></i> <?= $row['calculated_item_count']; ?> Items
                                                    </span>
                                                </td>
                                                 <td><span class="badge bg-<?= $modeColor ?> bg-opacity-10 text-white"><?= $modeName ?></span></td>
                                                <td><?= $statusBadge ?></td>
                                                <td class="text-end fw-bold text-dark fs-6">₹<?= number_format($final_total, 2) ?></td>
                                                <td class="text-center">
                                                    <form action="prtposinvoice.php" method="GET" target="_blank">
                                                        <input type="hidden" name="id" value="<?= $row['pos_bill_id']; ?>">
                                                        <button type="submit" class="btn btn-warning btn-sm btn-custom py-1 px-3 shadow-sm"><i class="bi bi-printer-fill"></i></button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<tr><td colspan='8' class='text-center py-5 text-muted'><i class='bi bi-inbox fs-1 d-block mb-3'></i>No invoices found for the selected criteria.</td></tr>";
                                    }
                                }
                            ?>
                        </tbody>
                        <?php if(isset($grand_total) && $grand_total > 0) { ?>
                        <tfoot class="total-row">
                            <tr>
                                <td colspan="6" class="text-end text-uppercase text-secondary small">Total Revenue</td>
                                <td class="text-end text-success fs-5">₹<?= number_format($grand_total, 2) ?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- View Items Modal -->
    <div class="modal fade" id="itemsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fw-bold"><i class="bi bi-cart4 text-primary me-2"></i>Bill Items <span id="modalBillNo" class="text-muted small ms-2"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            $('.view-items-btn').click(function() {
                var billId = $(this).data('id');
                var billNo = $(this).data('billno');
                
                $('#modalBillNo').text('(#' + billNo + ')');
                $('#itemsTableBody').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></td></tr>');
                $('#itemsModal').modal('show');

                $.ajax({
                    url: 'code-fetch-pos-items.php',
                    type: 'POST',
                    data: { bill_id: billId },
                    success: function(response) {
                        $('#itemsTableBody').html(response);
                    },
                    error: function() {
                        $('#itemsTableBody').html('<tr><td colspan="5" class="text-center text-danger">Failed to load items.</td></tr>');
                    }
                });
            });
        });
    </script>

    <?php include 'footer.php'?>

    </body>
    </html>
<?php 
} else {
    header("Location: logout.php");
} 
?>