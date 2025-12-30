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
        <title>Group Bill Credit</title>
        
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
            .table-custom tr:last-child td {
                border-bottom: none;
            }

            /* Badges */
            .badge-soft-success {
                background-color: rgba(16, 185, 129, 0.1);
                color: #10b981;
                padding: 0.35em 0.65em;
                border-radius: 6px;
                font-weight: 600;
            }
            .badge-soft-danger {
                background-color: rgba(239, 68, 68, 0.1);
                color: #ef4444;
                padding: 0.35em 0.65em;
                border-radius: 6px;
                font-weight: 600;
            }

            /* Form Controls */
            .form-label {
                font-weight: 600;
                font-size: 0.85rem;
                color: #475569;
                margin-bottom: 0.5rem;
            }
            .form-control {
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                padding: 0.6rem 1rem;
            }
            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            }
            .btn-primary-custom {
                background-color: var(--primary-color);
                border: none;
                color: white;
                padding: 0.6rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.2s;
            }
            .btn-primary-custom:hover {
                transform: translateY(-1px);
                box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
                color: white;
            }
        </style>
    </head>

    <body>

    <?php
    include 'admin-menu-bar.php';
    echo "<br/>";
    include 'admin-menu-btn.php';
    ?>

    <div class="main-container container-fluid">
        <!-- Search Card -->
        <div class="custom-card">
            <div class="card-header-gradient">
                <h4><i class="bi bi-search me-2"></i>Filter Group Bills</h4>
            </div>
            <div class="card-body p-4">
                <form action="" method="GET">
                    <div class="row align-items-end g-3">
                        <div class="col-md-4">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" class="form-control"
                                value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : '2023-06-01'; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" class="form-control" required
                                value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary-custom w-100">
                                <i class="bi bi-filter me-2"></i>Apply Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="custom-card">
            <div class="card-header-gradient">
                <h4><i class="bi bi-list-check me-2"></i>Bill List</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Bill No</th>
                                <th>Bill By</th>
                                <th>Group Name</th>
                                <th>Pay Mode</th>
                                <th>Old Bal</th>
                                <th>Bill Amt</th>
                                <th>Paid Amt</th>
                                <th>New Bal</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $currentDate = date('Y-m-d');
                            if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                $from_date = $_GET['from_date'];
                                $to_date = $_GET['to_date'];

                                // Query using correct table 'billgroupdetails' and original conditions
                                $query = "SELECT * FROM billgroupdetails WHERE date BETWEEN '$from_date' AND '$to_date' AND status = 'approve' AND pMode ='credit'"; 
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    foreach ($query_run as $row) {
                                        ?>
                                        <tr>
                                            <td><?= isset($row['id']) ? $row['id'] : '-'; ?></td>
                                            <td><?= date('d-M-Y', strtotime($row['date'])); ?></td>
                                            <td class="fw-bold text-dark"><?= $row['billNo']; ?></td>
                                            <td><?= $row['billBy']; ?></td>
                                            <td>
                                                <span class="fw-bolder"><?= $row['groupName']; ?></span>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <?= $row['pMode']; ?>
                                                </span>
                                            </td>
                                            <td><?= $row['oldMonthBal']; ?></td>
                                            <td class="text-primary fw-bold"><?= $row['billAmount']; ?></td>
                                            <td class="text-success fw-bold"><?= $row['Rs']; ?></td>
                                            <td class="text-danger fw-bold"><?= $row['discount']; // Using Discount column as placeholder for New Bal or as is ?></td>
                                            <td class="text-center">
                                                <!-- Adjusted link to pass 'id' or 'billNo' depending on what backend expects. Assuming 'id' is standard primary key -->
                                                <a href="admin-code-groupBill-credit.php?id=<?= isset($row['id']) ? $row['id'] : $row['billNo']; ?>" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                    <i class="bi bi-arrow-counterclockwise me-1"></i>Credit
                                                </a>
                                            </td>
                                        </tr>
                                                        <input type="hidden" name="date" value="<?= $row1['date']; ?>">
                                                        <input type="hidden" name="group_id" value="<?= $row1['group_id']; ?>">
                                                        <!-- Assign 'bill_id' value to the hidden input field for 'bill_no' -->
                                                        <button type="submit" class="btn btn-danger btn-sm" style="font-weight: bold;" >
                                                            Submit
                                                        </button>
                                                    </td>
                                                 </tr>
                                            </form>
                                        <?php
                                                
                                                    // Display the total sum
                                                    ?>

                                        <?php
                                                }
                                            } else {
                                                echo "<tr><td colspan='12' style='text-align:center; font-weight:bold;'>No Record Found</td></tr>";
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
        </div>
    </div>
<br/>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
