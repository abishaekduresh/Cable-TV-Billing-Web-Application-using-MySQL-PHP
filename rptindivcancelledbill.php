<?php
session_start();
include "dbconfig.php";
include "component.php";
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
        <title>Cancelled Bill Report | Admin Panel</title>
        
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

            .form-control {
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .form-control:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
            }

            .btn-custom {
                border-radius: 10px;
                padding: 0.75rem 1.5rem;
                font-weight: 600;
                transition: all 0.2s;
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
                padding: 1rem;
                border-bottom: 1px solid #f3f4f6;
                vertical-align: middle;
                font-size: 0.9rem;
            }
            
            .table-custom tr:last-child td {
                border-bottom: none;
            }
        </style>
    </head>

    <body>

    <?php
        include 'admin-menu-bar.php';
        include 'admin-menu-btn.php';
    ?>

    <div class="main-container">

        <!-- Filter Card -->
        <div class="custom-card">
            <div class="card-header-gradient">
                <h5 class="card-title"><i class="bi bi-funnel-fill"></i> Cancelled Bill Report</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="GET">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">From Date</label>
                            <input type="date" name="from_date" value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $currentDate; ?>" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">To Date</label>
                            <input type="date" name="to_date" value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                             <button type="submit" class="btn btn-primary btn-custom w-100 shadow-sm"><i class="bi bi-search"></i> Search Cancelled Bills</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Card -->
        <div class="custom-card">
              <div class="card-header-gradient">
                <h5 class="card-title"><i class="bi bi-x-circle-fill"></i> Cancelled Transactions</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Bill By</th>
                                <th>Date & Details</th>
                                <th>Customer Info</th>
                                <th>Remarks</th>
                                <th>Mode</th>
                                <th class="text-end">Old Bal</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Disc</th>
                                <th class="text-end">Net Amt</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            $from_date = isset($_GET['from_date']) ? $_GET['from_date'] : '';
                            $to_date = isset($_GET['to_date']) ? $_GET['to_date'] : '';
                            
                            // Initialize totals to 0 to check if search ran
                            $Rs_sum = 0;
                            $discount_sum = 0;
                            $paid_amount_sum = 0;
                            $oldMonthBal_sum = 0;
                            $has_run = false;

                            if (!empty($from_date) && !empty($to_date)) {
                                $has_run = true;
                                $query = "SELECT * FROM bill WHERE status ='cancel' AND date BETWEEN '$from_date' AND '$to_date' ORDER BY bill_id DESC";
                                $query_run = mysqli_query($con, $query);

                                if (mysqli_num_rows($query_run) > 0) {
                                    $serial_number = 1;
                                    foreach ($query_run as $row) {
                                        $Rs_sum += $row['Rs'];
                                        $discount_sum += $row['discount'];
                                        $paid_amount_sum += $row['paid_amount'];
                                        $oldMonthBal_sum += $row['oldMonthBal'];
                                        ?>
                                        <tr>
                                            <td class="text-secondary"><?= $serial_number++; ?></td>
                                            <td><span class="badge bg-light text-dark border"><?= $row['bill_by']; ?></span></td>
                                            <td>
                                                <div class="fw-bold text-primary"><?= formatDate($row['date']); ?></div>
                                                <div class="small text-muted">Bill #: <?= $row['billNo']; ?></div>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark"><?= $row['name']; ?></div>
                                                <div class="small text-muted"><?= $row['phone']; ?> | <?= $row['stbno']; ?></div>
                                                <div class="small text-secondary"><?= $row['mso']; ?></div>
                                            </td>
                                            <td><small class="text-danger"><?= $row['description']; ?></small></td>
                                            <td><span class="badge bg-secondary"><?= strtoupper($row['pMode']); ?></span></td>
                                            
                                            <td class="text-end fw-bold text-primary"><?= $row['oldMonthBal']; ?></td>
                                            <td class="text-end fw-bold text-success"><?= $row['paid_amount']; ?></td>
                                            <td class="text-end fw-bold text-danger"><?= $row['discount']; ?></td>
                                            <td class="text-end fw-bold text-dark"><?= $row['Rs']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='10' class='text-center py-5 text-muted'><i class='bi bi-inbox fs-1 d-block mb-3'></i>No cancelled bills found for this period.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='10' class='text-center py-5 text-muted'>Please select a date range to view the report.</td></tr>";
                            }
                            ?>
                        </tbody>
                        <?php if($has_run && isset($query_run) && mysqli_num_rows($query_run) > 0) { ?>
                        <tfoot class="bg-light border-top">
                            <tr style="font-size: 1rem;">
                                <td colspan="6" class="text-end fw-bold text-dark">TOTALS</td>
                                <td class="text-end fw-bold text-primary">₹<?= number_format($oldMonthBal_sum, 2) ?></td>
                                <td class="text-end fw-bold text-success">₹<?= number_format($paid_amount_sum, 2) ?></td>
                                <td class="text-end fw-bold text-danger">₹<?= number_format($discount_sum, 2) ?></td>
                                <td class="text-end fw-bold text-dark">₹<?= number_format($Rs_sum, 2) ?></td>
                            </tr>
                        </tfoot>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'?>
    </body>
    </html>

<?php } else {
    header("Location: index.php");
}
?>
