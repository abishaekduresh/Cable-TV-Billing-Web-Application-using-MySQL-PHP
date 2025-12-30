<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   include "component.php";

    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
            include 'admin-menu-bar.php';
            ?> <br> <?php
            include 'admin-menu-btn.php';
            $session_username = $_SESSION['username'];
            
        } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
            include 'menu-bar.php';
            ?> <br> <?php
            include 'sub-menu-btn.php';
            $session_username = $_SESSION['username'];
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indiv Duplicate Bills</title>
    
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
            margin-bottom: 2rem;
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
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }
        .table-custom tr:hover {
            background: #f8fafc;
        }
    </style>
</head>
<body>

<div class="main-container container-fluid">

    <?php include('message.php'); ?>

    <div class="custom-card">
        <div class="card-header-gradient">
            <div class="d-flex align-items-center">
                 <div class="bg-warning bg-opacity-10 p-2 rounded-circle me-3">
                    <i class="bi bi-files text-warning fs-5"></i>
                </div>
                <div>
                     <h4 class="mb-0">Duplicate Bill Report</h4>
                    <small class="text-muted">Current Month Individual Duplicate Bills</small>
                </div>
            </div>
            
             <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm">
                <i class="bi bi-calendar-event me-2"></i><?php echo date('F Y'); ?>
            </span>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Bill No</th>
                        <th>Due Date</th>
                        <th>Bill by</th>
                        <th>MSO</th>
                        <th>STB No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Remark</th>
                        <th class="text-center">History</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $query = "SELECT * 
                                  FROM bill 
                                  WHERE YEAR(due_month_timestamp) = $currentYear 
                                  AND MONTH(due_month_timestamp) = $currentMonth 
                                  AND status = 'Approve' 
                                  AND stbno IN (
                                      SELECT stbno 
                                      FROM bill 
                                      WHERE YEAR(due_month_timestamp) = $currentYear 
                                      AND MONTH(due_month_timestamp) = $currentMonth 
                                      AND status = 'Approve'
                                      GROUP BY stbno 
                                      HAVING COUNT(*) >= 2
                                  )
                                  ORDER BY stbno ASC";

                        $query_run = mysqli_query($con, $query);

                        if(mysqli_num_rows($query_run) > 0)
                        {
                            $serial_number = 1;
                            foreach($query_run as $bill)
                            {
                                ?>
                                <tr>
                                    <td class="text-muted small ps-4"><?= $serial_number++; ?></td>
                                    <td class="fw-bold text-primary font-monospace"><?= $bill['billNo']; ?></td>
                                    <td class="fw-bold" style="color: #007DC3;"><?= formatDate($bill['due_month_timestamp']); ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= $bill['bill_by']; ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?= $bill['mso']; ?></span></td>
                                    <td class="font-monospace small"><?= $bill['stbno']; ?></td>
                                    <td class="fw-bold"><?= $bill['name']; ?></td>
                                    <td><?= $bill['phone']; ?></td>
                                    <td class="small text-muted text-truncate" style="max-width: 200px;"><?= $bill['description']; ?></td>
                                    <td class="text-center">
                                        <a href="customer-history.php?search=<?= $bill['stbno']; ?>" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                                            <i class="bi bi-box-arrow-up-right me-1"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        else
                        {
                            echo '<tr><td colspan="10" class="text-center py-5 text-muted">No duplicate bills found for this month.</td></tr>';
                        }
                    ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>
