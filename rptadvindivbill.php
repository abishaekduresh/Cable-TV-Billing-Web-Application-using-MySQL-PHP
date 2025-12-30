<?php 
    session_start();
    include "dbconfig.php";
    include 'preloader.php';
    include "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Advance Bill List | Admin Panel</title>
    
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

        .btn-custom {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .active-month-badge {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 0.35em 0.8em;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    include 'admin-menu-bar.php';
    include 'admin-menu-btn.php';
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    include 'menu-bar.php';
}
?>

<div class="main-container">
    <?php include('message.php'); ?>

    <div class="custom-card">
        <div class="card-header-gradient">
            <h5 class="card-title"><i class="bi bi-calendar-check-fill"></i> Active Advance Bill List</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-custom table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>MSO</th>
                            <th>STB No</th>
                            <th>Customer Name</th>
                            <th>Phone</th>
                            <th class="text-center">Active Months</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $MM = date('m', strtotime($currentDate));
                            $YY = date('Y', strtotime($currentDate));

                            // Logic to find active advance bills
                            $query = "SELECT * FROM bill WHERE 
                            DATE(due_month_timestamp) >= '$currentDate' AND
                            (
                                (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                            )
                            AND adv_status = 1 AND status = 'approve' 
                            GROUP BY stbno";
                        
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $serial_number = 1;

                                foreach($query_run as $bill)
                                {
                                    $stbnum = $bill['stbno'];
                                    ?>
                                    <tr>
                                        <td class="fw-bold text-secondary"><?= $serial_number++; ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= $bill['mso']; ?></span></td>
                                        <td class="fw-bold text-primary"><?= $stbnum ?></td>
                                        <td class="fw-bold text-dark"><?= $bill['name']; ?></td>
                                        <td class="text-muted"><?= $bill['phone']; ?></td>
                                        
                                        <td class="text-center">
                                            <?php
                                                // Calculate count of active advance months for this STB
                                                $countQuery = "SELECT stbno FROM bill WHERE stbno = '$stbnum' AND
                                                    DATE(due_month_timestamp) >= '$currentDate' AND
                                                    (
                                                        (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                                        (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                                    )
                                                    AND adv_status = 1 AND status = 'approve'";

                                                $result = $con->query($countQuery);
                                                $countIdx = ($result) ? mysqli_num_rows($result) : 0;
                                            ?>
                                            <span class="active-month-badge"><?= $countIdx ?> Months</span>
                                        </td>
                                        
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="customer-history.php?search=<?= $bill['stbno']; ?>" target="_blank" class="btn btn-outline-secondary btn-custom btn-sm" title="View History">
                                                    <i class="fas fa-history"></i>
                                                </a>
                                                <a href="prtindivadvbill.php?stbnumber=<?= $bill['stbno']; ?>" target="_blank" class="btn btn-warning btn-custom btn-sm text-dark shadow-sm" title="Print Bill">
                                                    <i class="bi bi-printer-fill me-1"></i> Print
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                                echo "<tr><td colspan='7' class='text-center py-5 text-muted'><i class='bi bi-inbox fs-1 d-block mb-3'></i><br>No Active Advance Bills Found</td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'?>

</body>
</html>

<?php } else {
	header("Location: index.php");
} ?>
