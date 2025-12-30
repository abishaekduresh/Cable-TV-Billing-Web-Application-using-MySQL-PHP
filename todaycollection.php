<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
    <?php include 'favicon.php'; ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Indiv Bill Collection Report</title>
        
        <!-- Premium UI Dependencies -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            :root {
                --primary-color: #4361ee;
                --secondary-color: #3f37c9;
                --success-color: #06d6a0;
                --danger-color: #ef476f;
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

            .main-container {
                padding: 1rem;
                max-width: 100%;
                margin: 0 auto;
            }

            /* Card Styles */
            .custom-card {
                background: white;
                border-radius: 16px;
                border: none;
                box-shadow: var(--card-shadow);
                overflow: hidden;
                border: 1px solid rgba(0,0,0,0.02);
                margin-bottom: 1.5rem;
            }

            .card-header-gradient {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                padding: 1.25rem;
                border-bottom: 1px solid #dee2e6;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            
            .card-header-gradient h4 {
                font-size: 1.1rem;
                font-weight: 700;
                margin: 0;
                color: var(--text-dark);
            }

            /* Filters */
            .filter-section {
                background-color: #f8fafc;
                border-radius: 12px;
                padding: 1.5rem;
                border: 1px solid #e2e8f0;
            }
            
            .filter-label {
                font-weight: 600;
                font-size: 0.85rem;
                color: #475569;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                margin-bottom: 0.75rem;
                display: block;
                border-bottom: 1px solid #e2e8f0;
                padding-bottom: 0.35rem;
            }

            .form-label {
                font-weight: 500;
                font-size: 0.9rem;
                color: #64748b;
            }

            .form-control, .form-select {
                border-radius: 8px;
                border: 1px solid #d1d5db;
                padding: 0.6rem 1rem;
                font-size: 0.95rem;
            }
            
            .form-check-label {
                font-size: 0.9rem;
                color: #334155;
            }

            /* Table Styling */
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
                font-size: 0.85rem;
                color: #334155;
            }
            .table-custom tr:hover {
                background: #f1f5f9;
            }

            .badge-soft-primary { background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
            .badge-soft-success { background-color: rgba(6, 214, 160, 0.1); color: var(--success-color); }
            .badge-soft-danger { background-color: rgba(239, 71, 111, 0.1); color: var(--danger-color); }
            .badge-soft-secondary { background-color: rgba(141, 153, 174, 0.1); color: var(--text-light); }
            
            .checkbox-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 0.5rem;
            }
        </style>
    </head>

    <body>

<?php
    include 'admin-menu-bar.php';
    echo '<br>';
    include 'admin-menu-btn.php';
?>

        <div class="main-container">
            <!-- FILTER SECTION -->
            <div class="custom-card">
                <div class="card-header-gradient">
                    <h4><i class="bi bi-funnel-fill me-2"></i>Report Filters</h4>
                    <button class="btn btn-primary btn-sm rounded-pill px-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterBody" aria-expanded="true">
                        Toggle Filters
                    </button>
                </div>
                <div class="collapse show" id="filterBody">
                    <div class="card-body p-4">
                        <form action="" method="GET">
                            <!-- Top Row: Dates and Search -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-3">
                                    <label class="form-label">From Date</label>
                                    <input type="date" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $currentDate; ?>" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">To Date</label>
                                    <input type="date" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>" class="form-control" required>
                                </div>
                                <div class="col-md-2">
                                     <label class="form-label">From Bill No.</label>
                                    <input type="number" name="from_billno" value="<?php echo isset($_GET['from_billno']) ? $_GET['from_billno'] : ''; ?>" class="form-control" placeholder="Start No." step="1">
                                </div>
                                <div class="col-md-2">
                                     <label class="form-label">To Bill No.</label>
                                    <input type="number" name="to_billno" value="<?php echo isset($_GET['to_billno']) ? $_GET['to_billno'] : ''; ?>" class="form-control" placeholder="End No." step="1">
                                </div>
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-search me-2"></i>Search</button>
                                </div>
                            </div>
                            
                            <hr class="text-muted opacity-25">

                            <!-- Bottom Row: Advanced Filters -->
                            <div class="row g-4">
                                <!-- BILL BY -->
                                <div class="col-md-6">
                                    <div class="filter-section h-100">
                                        <span class="filter-label"><i class="bi bi-person-check me-2"></i>Bill To (User)</span>
                                        <?php
                                            $sql = "SELECT * FROM user WHERE status = 1";
                                            $result = $con->query($sql);
                                            if ($result->num_rows > 0) {
                                                echo '<div class="checkbox-grid">';
                                                while ($row = $result->fetch_assoc()) {
                                                    $checked = (isset($_GET['filter']) && in_array($row['username'], $_GET['filter'])) ? 'checked' : '';
                                                    echo '<div class="form-check">';
                                                    echo '<input class="form-check-input" type="checkbox" name="filter[]" value="' . htmlspecialchars($row['username']) . '" id="user_'.$row['username'].'" '.$checked.'>';
                                                    echo '<label class="form-check-label" for="user_'.$row['username'].'">' . htmlspecialchars($row['name']) . '</label>';
                                                    echo '</div>';
                                                }
                                                echo '</div>';
                                            } else {
                                                echo '<span class="text-muted small">No active users found.</span>';
                                            }
                                        ?>
                                    </div>
                                </div>

                                <!-- OTHER FILTERS -->
                                <div class="col-md-6">
                                    <div class="row g-3 h-100">
                                        <!-- MSO -->
                                        <div class="col-md-6">
                                            <div class="filter-section h-100">
                                                <span class="filter-label"><i class="bi bi-hdd-network me-2"></i>MSO</span>
                                                <div class="d-flex flex-column gap-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mso_filter" value="ALL" id="msoAll" <?php echo (!isset($_GET['mso_filter']) || $_GET['mso_filter'] == 'ALL') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="msoAll">All MSOs</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mso_filter" value="VK" id="msoVk" <?php echo (isset($_GET['mso_filter']) && $_GET['mso_filter'] == 'VK') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="msoVk">VK Digital</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="mso_filter" value="GTPL" id="msoGtpl" <?php echo (isset($_GET['mso_filter']) && $_GET['mso_filter'] == 'GTPL') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="msoGtpl">GTPL</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- STATUS & MODE -->
                                        <div class="col-md-6">
                                            <div class="filter-section h-100">
                                                <span class="filter-label"><i class="bi bi-sliders me-2"></i>Status & Mode</span>
                                                
                                                <div class="mb-3">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_filter" value="approve" id="statApprove" <?php echo (!isset($_GET['status_filter']) || $_GET['status_filter'] == 'approve') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label text-success fw-bold" for="statApprove">Approved</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status_filter" value="cancel" id="statCancel" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'cancel') ? 'checked' : ''; ?>>
                                                        <label class="form-check-label text-danger fw-bold" for="statCancel">Cancelled</label>
                                                    </div>
                                                </div>

                                                <div class="d-flex flex-wrap gap-3">
                                                    <?php 
                                                        $modes = ['cash'=>'Cash', 'Gpay'=>'GPay', 'paytm'=>'Paytm', 'credit'=>'Credit'];
                                                        foreach($modes as $val => $label) {
                                                            $pChecked = (isset($_GET['pMode_filter']) && in_array($val, $_GET['pMode_filter'])) ? 'checked' : '';
                                                            echo '<div class="form-check">';
                                                            echo '<input class="form-check-input" type="checkbox" name="pMode_filter[]" value="'.$val.'" id="pm_'.$val.'" '.$pChecked.'>';
                                                            echo '<label class="form-check-label" for="pm_'.$val.'">'.$label.'</label>';
                                                            echo '</div>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- RESULTS TABLE -->
            <div class="custom-card">
                 <div class="card-header-gradient">
                    <h4><i class="bi bi-table me-2"></i>Collection Report</h4>
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                       <?php echo isset($_GET['from_date']) ? date('d M', strtotime($_GET['from_date'])) . ' - ' . date('d M', strtotime($_GET['to_date'])) : 'Today'; ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bill By</th>
                                    <th>Col Date</th>
                                    <th>Due Month</th>
                                    <th>Bill No</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Remarks</th>
                                    <th>Mode</th>
                                    <th class="text-end">Old Bal</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Disc</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $discount_sum = 0;
                                $paid_amount_sum = 0;
                                $Rs_sum = 0;
                                $oldMonthBal_sum = 0;

                                if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];


                                    $from_billno = isset($_GET['from_billno']) ? $_GET['from_billno'] : '';
                                    $to_billno = isset($_GET['to_billno']) ? $_GET['to_billno'] : '';
                                    $billnoFilterCondition = '';

                                    if (!empty($from_billno) && !empty($to_billno)) {
                                        $billnoFilterCondition = "AND billNo BETWEEN '$from_billno' AND '$to_billno'";
                                    }


                                    // Retrieve selected filter options
                                    $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : 'approve'; // Default to approve if not set (based on UI check) - actually original code default was based on radio check
                                    // Original logic: if empty, handled later. The radio has 'checked' for approve by default in HTML.
                                    
                                    $mso_filter = isset($_GET['mso_filter']) ? $_GET['mso_filter'] : '';
                                    $pMode_filter = isset($_GET['pMode_filter']) ? $_GET['pMode_filter'] : array();


                                    // Build the filter condition
                                    $filterCondition = '';
                                    $statusFilterCondition = '';
                                    $pModeFilterCondition = '';
                                    $msoFilterCondition = '';

                                    if (!empty($mso_filter) && $mso_filter != 'ALL') {
                                        $msoFilterCondition = "AND mso = '$mso_filter'";
                                    }

                                    if (!empty($filters)) {
                                        $filterCondition = "AND bill_by IN ('" . implode("','", $filters) . "')";
                                    }

                                    if (!empty($status_filter)) {
                                        if (is_array($status_filter)) {
                                            $statusFilterCondition = "AND status IN ('" . implode("','", $status_filter) . "')";
                                        } else {
                                            $statusFilterCondition = "AND status = '$status_filter'";
                                        }
                                    }

                                    if (!empty($pMode_filter)) {
                                        if (is_array($pMode_filter)) {
                                            $pModeFilterCondition = "AND pMode IN ('" . implode("','", $pMode_filter) . "')";
                                        } else {
                                            $pModeFilterCondition = "AND pMode = '$pMode_filter'";
                                        }
                                    }


                                    $query = "SELECT * FROM bill WHERE DATE(date) BETWEEN '$from_date' AND '$to_date' $billnoFilterCondition $filterCondition $statusFilterCondition $pModeFilterCondition $msoFilterCondition";
                                    // $query .= "ORDER BY bill_id DESC";

                                    $query_run = mysqli_query($con, $query);

                                    if (mysqli_num_rows($query_run) > 0) {
                                        $serial_number = 1;

                                        foreach ($query_run as $row) {
                                            $Rs_sum += $row['Rs'];
                                            $discount_sum += $row['discount'];
                                            $paid_amount_sum += $row['paid_amount'];
                                            $oldMonthBal_sum += $row['oldMonthBal'];
                                            
                                            // Advance Status Coloring
                                            $rowClass = ($row['adv_status'] == 1) ? 'table-warning' : ''; 
                                            // Note: Original used #dfb9fa (purpleish). table-warning is yellow. 
                                            // Let's stick to a custom style if we want that exact purple, or use bootstrap class.
                                            // Original: style="background-color: <?= $row['adv_status'] == 1 ? '#dfb9fa' : '' 
                                            $rowStyle = ($row['adv_status'] == 1) ? 'background-color: #dfb9fa;' : '';
                                            ?>
                                <tr style="<?php echo $rowStyle; ?>">
                                    <td class="text-muted small"><?= $serial_number++; ?></td>
                                    <td class="fw-bold"><?= $row['bill_by']; ?></td>
                                    <td class="small">
                                        <div class="fw-bold text-primary"><?= formatDate($row['date']); ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;"><?= $row['time'] ?></div>
                                    </td>
                                    <td class="small fw-bold text-info">
                                        <?php 
                                            $current_result = splitDateAndTime(strtotime($row['due_month_timestamp'])); 
                                            echo formatDate($current_result['date']);
                                        ?>
                                    </td>
                                    <td class="fw-bold"><?= $row['billNo']; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $row['mso']; ?></span></td>
                                    <td class="small font-monospace"><?= $row['stbno']; ?></td>
                                    <td class="fw-bold"><?= $row['name']; ?></td>
                                    <td><?= $row['phone']; ?></td>
                                    <td class="small text-muted text-wrap" style="max-width: 150px;"><?= $row['description']; ?></td>
                                    <td><span class="badge badge-soft-secondary text-dark"><?= $row['pMode']; ?></span></td>
                                    <td class="text-end text-primary fw-bold"><?= $row['oldMonthBal']; ?></td>
                                    <td class="text-end text-success fw-bold"><?= $row['paid_amount']; ?></td>
                                    <td class="text-end text-danger fw-bold"><?= $row['discount']; ?></td>
                                    <td class="text-end text-danger fw-bold fs-6"><?= $row['Rs']; ?></td>
                                    <td class="text-center">
                                        <a href="prtindivbillrpt.php?billid=<?= $row['bill_id']; ?>" target="_blank" class="btn btn-warning btn-sm shadow-sm">
                                            <i class="bi bi-printer-fill"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="16" class="text-center py-5 text-muted">No records found for the selected criteria.</td></tr>';
                                    }
                                }
                                ?>
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <td colspan="11" class="text-end fw-bold text-uppercase text-secondary">Totals</td>
                                    <td class="text-end fw-bold text-primary fs-6"><?= number_format($oldMonthBal_sum, 2) ?></td>
                                    <td class="text-end fw-bold text-success fs-6"><?= number_format($paid_amount_sum, 2) ?></td>
                                    <td class="text-end fw-bold text-danger fs-6"><?= number_format($discount_sum, 2) ?></td>
                                    <td class="text-end fw-bold text-danger fs-5"><?= number_format($Rs_sum, 2) ?></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>

    <?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
