<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   include "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        ?>

<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
    $session_username = $_SESSION['username'];
    
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        ?><br><?php
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
    <title>Latest 10 Bill</title>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
            --warning-color: #ffd166;
            --text-dark: #2b2d42;
            --bg-light: #f8f9fa;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-dark);
        }

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
            padding: 1rem 1.5rem;
            color: white;
        }
        
        .card-header-gradient.pos {
            background: linear-gradient(135deg, #11998e, #38ef7d);
        }

        .card-title { margin: 0; font-weight: 700; font-size: 1.1rem; }

        /* Table Styling */
        .table thead th {
            background-color: #f9fafb;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .table-hover tbody tr:hover { background-color: #f8faff; }

        /* Badges & Buttons */
        .btn-action {
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: none;
            transition: all 0.2s;
        }
        .btn-print { background-color: #fff3cd; color: #856404; }
        .btn-print:hover { background-color: #ffeeba; transform: scale(1.05); }

        .header-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            text-decoration: none;
            transition: all 0.2s;
        }
        .header-btn:hover {
            background: white;
            color: var(--primary-color);
        }
    </style>
</head>
<body >

<div class="container-fluid mt-4">

        <?php include('message.php'); ?>

        <!-- Last 10 Indiv Bills -->
        <div class="row">
            <div class="col-md-12">
                <div class="custom-card">
                    <div class="card-header-gradient d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                            <i class="bi bi-receipt me-2"></i>Latest 10 Bill by <b><?php echo $session_username?></b>
                        </h4>
                        <div>
                            <a href="billing-dashboard.php" class="header-btn me-2" accesskey="n"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
                            <a href="prtindivbulkbilldash.php" class="header-btn" accesskey="n"><i class="bi bi-printer me-1"></i> Bulk Print</a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Bill No</th>
                                    <th>Date</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Mode</th>
                                    <th class="text-end">Old Bal</th>
                                    <th class="text-end">Amount</th>
                                    <th class="text-end">Disc</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // $query = "SELECT * FROM bill ORDER BY bill_id DESC LIMIT 5 WHERE bill_by = '$session_usernamename'";
                                    $query = "SELECT * FROM bill WHERE bill_by = '$session_username' AND DAY(date) = '$currentDay' AND status = 'approve' ORDER BY bill_id DESC LIMIT 10";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $bill)
                                        {
                                            ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary"><?= $serial_number++; ?></td>
                                                <td class="fw-bold"><?= $bill['billNo']; ?></td>
                                                <td class="text-muted"><?= formatDate($bill['date']); ?></td>
                                                <td><?= $bill['mso']; ?></td>
                                                <td class="small text-muted"><?= $bill['stbno']; ?></td>
                                                <td class="fw-bold text-dark"><?= $bill['name']; ?></td>
                                                <td><?= $bill['phone']; ?></td>
                                                <td class="small text-muted"><?= $bill['description']; ?></td>
                                                <td><span class="badge bg-light text-dark border"><?= $bill['pMode']; ?></span></td>
                                                <td class="text-end fw-bold text-primary">
                                                    <?= $bill['oldMonthBal']; ?>
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    <?= $bill['paid_amount']; ?>
                                                </td>
                                                <td class="text-end fw-bold text-danger">
                                                    <?= $bill['discount']; ?>
                                                </td>
                                                <td class="text-end fw-bolder text-danger fs-6">
                                                    <?= $bill['Rs']; ?>
                                                </td>
                                                <td class="text-center">
                                                    <a href="prtindivbillrpt.php?billid=<?= $bill['bill_id']; ?>" target="blank" class="btn-action btn-print" title="Print Bill">
                                                        <i class="bi bi-printer-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<tr><td colspan='14' class='text-center py-4 text-muted'>No bills found for today.</td></tr>";
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


<div class="container-fluid mt-2">

        <?php include('message.php'); ?>

        <!-- POS Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="custom-card">
                     <div class="card-header-gradient pos d-flex justify-content-between align-items-center">
                        <h4 class="card-title">
                             <i class="bi bi-cart-check me-2"></i>POS Latest 10 Bill by <b><?php echo $session_username?></b>
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Bill No</th>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Mode</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // $query = "SELECT * FROM pos_bill WHERE username = '$session_username' AND DATE(entry_timestamp) = '$currentDate' AND status = '1' ORDER BY pos_bill_id DESC LIMIT 10";
                                    $query = "SELECT * FROM pos_bill WHERE username = '$session_username' AND DATE(entry_timestamp) = '$currentDate' AND status = '1' ORDER BY pos_bill_id DESC LIMIT 10";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $bill)
                                        {
                                            ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-secondary"><?= $serial_number++; ?></td>
                                                <td class="fw-bold"><?= $bill['bill_no']; ?></td>
                                                <td class="text-muted"><?= formatDate($bill['entry_timestamp']); ?></td>
                                                <td class="fw-bold text-dark"><?= $bill['cus_name']; ?></td>
                                                <td><?= $bill['cus_phone']; ?></td>
                                                <td><span class="badge bg-light text-dark border"><?= $bill['pay_mode']; ?></span></td>
                                                <td class="text-center">
                                                    <a href="prtposinvoice.php?id=<?= $bill['pos_bill_id']; ?>" target="blank" class="btn-action btn-print" title="Print Bill">
                                                        <i class="bi bi-printer-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<tr><td colspan='7' class='text-center py-4 text-muted'>No POS bills found for today.</td></tr>";
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: logout.php");
} ?>