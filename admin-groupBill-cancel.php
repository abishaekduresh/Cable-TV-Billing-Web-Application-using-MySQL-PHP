<?php
session_start();
include "dbconfig.php";
require 'component.php';
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
        <title>Group Bill Cancel</title>
        
        <!-- Premium UI Dependencies -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <!-- SweetAlert2 -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <style>
            :root {
                --primary-color: #4361ee;
                --text-dark: #2b2d42;
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
                font-size: 0.9rem;
                color: #334155;
            }
            .table-custom tr:hover {
                background: #f8fafc;
            }

             /* Form Control Overrides */
            .form-control, .form-select {
                border-radius: 8px;
                border: 1px solid #d1d5db;
                padding: 0.6rem 1rem;
            }
            .form-control:focus, .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            }
        </style>
    </head>

    <body>

<?php
    include 'admin-menu-bar.php';
    echo '<br>';
    include 'admin-menu-btn.php';
?>

        <div class="main-container container-fluid">
            
            <!-- FILTER SECTION -->
            <div class="custom-card">
                 <div class="card-header-gradient">
                     <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="bi bi-funnel-fill text-primary fs-5"></i>
                        </div>
                        <div>
                            <h4 class="mb-0">Filter Group Bills</h4>
                            <small class="text-muted">Select date range to view approved bills</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form action="" method="GET">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-muted text-uppercase">From Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" name="from_date" class="form-control border-start-0 ps-0"
                                        value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $currentDate; ?>">
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fw-bold small text-muted text-uppercase">To Date</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar3"></i></span>
                                    <input type="date" name="to_date" class="form-control border-start-0 ps-0" required
                                        value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                                    <i class="bi bi-search me-2"></i>Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- RESULTS SECTION -->
            <div class="custom-card">
                 <div class="card-header-gradient">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-2 rounded-circle me-3">
                            <i class="bi bi-x-octagon-fill text-danger fs-5"></i>
                        </div>
                        <h4 class="mb-0">Bill Cancel Management</h4>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table-custom">
                            <thead>
                                <tr>
                                    <th class="ps-4">No</th>
                                    <th>Date</th>
                                    <th>Bill No</th>
                                    <th>Bill By</th>
                                    <th>Group Name</th>
                                    <th>Mode</th>
                                    <th class="text-end">Old Bal</th>
                                    <th class="text-end">Bill Amt</th>
                                    <th class="text-end">Discount</th>
                                    <th class="text-end">Paid (Rs)</th>
                                    <th class="text-center" style="width: 150px;">Status Action</th>
                                    <th class="text-center">Submit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];

                                    $query1 = "SELECT * FROM billgroupdetails WHERE date BETWEEN '$from_date' AND '$to_date' AND status = 'approve'"; 
                                    $query_run1 = mysqli_query($con, $query1);                                            

                                    if (mysqli_num_rows($query_run1) > 0) {
                                        $serial_number = 1;
                                        foreach ($query_run1 as $row1) {
                                            ?>
                                            <tr>
                                                <form class="update-bill-form" action="admin-code-groupBill-cancel.php" method="POST">
                                                    <td class="text-muted small ps-4"><?= $serial_number++; ?></td>
                                                    <td class="fw-bold text-primary"><?= formatDate($row1['date']); ?></td>
                                                    <td class="font-monospace small"><?= $row1['billNo']; ?></td>
                                                    <td><span class="badge bg-light text-dark border"><?= $row1['billBy']; ?></span></td>
                                                    <td class="fw-bold"><?= $row1['groupName']; ?></td>
                                                    <td><span class="badge bg-light text-dark border"><?= $row1['pMode']; ?></span></td>
                                                    <td class="text-end fw-bold text-secondary"><?= $row1['oldMonthBal']; ?></td>
                                                    <td class="text-end fw-bold text-success"><?= $row1['billAmount']; ?></td>
                                                    <td class="text-end fw-bold text-danger"><?= $row1['discount']; ?></td>
                                                    <td class="text-end fw-bold text-primary"><?= $row1['Rs']; ?></td>
                                                    <td>
                                                        <select name="selectedValue" class="form-select form-select-sm fw-bold border-warning bg-warning bg-opacity-10 text-dark action-select">
                                                            <option value="approve" <?php if ($row1['status'] === 'approve') { echo 'selected'; } ?>>Approve</option>
                                                            <option value="cancel" <?php if ($row1['status'] === 'cancel') { echo 'selected'; } ?>>Cancel</option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="hidden" name="billNo" value="<?= $row1['billNo']; ?>">
                                                        <input type="hidden" name="date" value="<?= $row1['date']; ?>">
                                                        <input type="hidden" name="group_id" value="<?= $row1['group_id']; ?>">
                                                        <input type="hidden" name="Rs" value="<?= $row1['Rs']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm fw-bold">
                                                            Update
                                                        </button>
                                                    </td>
                                                </form>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<tr><td colspan='12' class='text-center py-5 text-muted'>No approved group bills found for this period.</td></tr>";
                                    }
                                } else {
                                     echo "<tr><td colspan='12' class='text-center py-5 text-muted'>Please select a date range to search.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).on('submit', '.update-bill-form', function(e) {
            e.preventDefault();
            var form = this;
            var actionType = $(this).find('.action-select').val();
            var actionText = actionType === 'cancel' ? 'Cancel Bill' : 'Approve Bill';
            var confirmColor = actionType === 'cancel' ? '#ef476f' : '#06d6a0';
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to " + actionType + " this bill.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#8d99ae',
                confirmButtonText: 'Yes, ' + actionType + ' it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>

<?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
