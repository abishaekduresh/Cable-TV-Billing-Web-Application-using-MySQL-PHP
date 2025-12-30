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
    <title>Income Expense Report | Admin Panel</title>
    
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
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
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
            display: inline-flex;
            align-items: center;
            gap: 8px;
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
        
        .badge-soft {
            padding: 0.35em 0.8em;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.75rem;
        }

        .summary-box {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .profit-positive { color: #10b981; }
        .profit-negative { color: #ef4444; }

    </style>
</head>
<body>

<?php
    include 'admin-menu-bar.php';
    include 'admin-menu-btn.php';
?>

<div class="main-container">
    
    <!-- Filter Section -->
    <div class="custom-card">
        <div class="card-header-gradient">
            <h5 class="card-title"><i class="bi bi-funnel-fill"></i> Report Filters</h5>
        </div>
        <div class="card-body p-4">
            <form action="" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } else { echo $currentDate; } ?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } else { echo $currentDate; } ?>" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="select" selected disabled>Select Category</option>
                            <?php
                                $query = "SELECT * FROM in_ex_category";
                                $result = mysqli_query($con, $query);
                                $selectedValue = isset($_GET['category']) ? $_GET['category'] : ''; 

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $optionValueID = $row['category_id'];
                                    $optionValue = $row['category'];
                                    echo "<option value='$optionValueID'". ($optionValue === $selectedValue ? ' selected' : '') .">$optionValue</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sub Category</label>
                        <select class="form-select" name="subcategory_id" id="subcategory_id">
                            <option value="select" selected disabled>Select Category First</option>
                        </select>
                    </div>
                    
                    <div class="col-12 d-flex justify-content-between align-items-center mt-4">
                        <button type="submit" class="btn btn-primary btn-custom shadow-sm"><i class="bi bi-search"></i> Generate Report</button>
                        
                        <?php if (isset($_GET['from_date']) && isset($_GET['to_date'])) { ?>
                        <div class="d-flex gap-2">
                             <a href="prt-in-ex-3inch.php?from_date=<?= $_GET['from_date'] ?>&to_date=<?= $_GET['to_date'] ?>&category_id=<?= isset($_GET['category_id']) ? $_GET['category_id'] : '' ?>&subcategory_id=<?= isset($_GET['subcategory_id']) ? $_GET['subcategory_id'] : '' ?>" target="_blank" class="btn btn-success btn-custom text-white">
                                <i class="bi bi-printer"></i> 3 Inch Print
                            </a>
                            <a href="rpt-in-ex-pdf-download.php?from_date=<?= $_GET['from_date'] ?>&to_date=<?= $_GET['to_date'] ?>&category_id=<?= isset($_GET['category_id']) ? $_GET['category_id'] : '' ?>&subcategory_id=<?= isset($_GET['subcategory_id']) ? $_GET['subcategory_id'] : '' ?>" target="_blank" class="btn btn-danger btn-custom text-white">
                                <i class="bi bi-file-earmark-pdf"></i> Download PDF
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div class="custom-card">
        <div class="card-header-gradient">
             <h5 class="card-title"><i class="bi bi-table"></i> Report Results</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-custom table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>#</th>
                        <th>Date & Time</th>
                        <th>User</th>
                        <th>Details</th>
                        <th>Remark</th>
                        <th class="text-success text-center">Income</th>
                        <th class="text-danger text-center">Expense</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $in_sum = 0;
                    $ex_sum = 0;
                    if(isset($_GET['from_date']) && isset($_GET['to_date'])) {
                        $from_date = $_GET['from_date'];
                        $to_date = $_GET['to_date'];
                        $filters = isset($_GET['category_id']) ? $_GET['category_id'] : '';
                        $status_filter = isset($_GET['subcategory_id']) ? $_GET['subcategory_id'] : '';
                        
                        $filterCondition = '';
                        $statusFilterCondition = '';
                        
                        if (!empty($filters)) {
                            $filterIds = array_map('intval', explode(',', $filters));
                            $filterCondition = "AND category_id IN (" . implode(",", $filterIds) . ")";
                        }
                        if (!empty($status_filter)) {
                            $statusIds = array_map('intval', explode(',', $status_filter));
                            $statusFilterCondition = (count($statusIds) > 1) ? "AND subcategory_id IN (" . implode(",", $statusIds) . ")" : "AND subcategory_id = " . $statusIds[0];
                        }
                    
                        $query = "SELECT * FROM in_ex WHERE date BETWEEN '$from_date' AND '$to_date' $filterCondition $statusFilterCondition AND status = 1 ORDER BY date DESC, time DESC";
                        $query_run = mysqli_query($con, $query);

                        if(mysqli_num_rows($query_run) > 0) {   
                            $serial_number = 1;
                            foreach($query_run as $row) {
                                // Fetch Category Name
                                $catRes = mysqli_query($con, "SELECT category FROM in_ex_category WHERE category_id='".$row['category_id']."'");
                                $catName = ($c = mysqli_fetch_assoc($catRes)) ? $c['category'] : '-';

                                // Fetch SubCategory Name
                                $subRes = mysqli_query($con, "SELECT subcategory FROM in_ex_subcategory WHERE subcategory_id='".$row['subcategory_id']."'");
                                $subName = ($s = mysqli_fetch_assoc($subRes)) ? $s['subcategory'] : '-';
                                
                                $incomeAmt = ($row['type'] === 'Income') ? $row['amount'] : 0;
                                $expenseAmt = ($row['type'] === 'Expense') ? $row['amount'] : 0;
                                
                                $in_sum += $incomeAmt;
                                $ex_sum += $expenseAmt;
                                ?>
                                <tr>
                                    <td><?= $serial_number++; ?></td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= formatDate($row['date']); ?></div>
                                        <div class="small text-muted"><?= convertTo12HourFormat($row['time']); ?></div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= $row['username']; ?></span></td>
                                    <td>
                                        <div class="fw-bold text-primary show-cat"><?= $catName ?></div>
                                        <div class="small text-secondary"><?= $subName ?></div>
                                    </td>
                                    <td><small class="text-muted"><?= $row['remark']; ?></small></td>
                                    <td class="text-center fw-bold text-success"><?= $incomeAmt > 0 ? '₹'.$incomeAmt : '-' ?></td>
                                    <td class="text-center fw-bold text-danger"><?= $expenseAmt > 0 ? '₹'.$expenseAmt : '-' ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center py-5 text-muted'><i class='bi bi-inbox fs-1 d-block mb-2'></i>No records found for the selected period</td></tr>";
                        }
                    } else {
                         echo "<tr><td colspan='7' class='text-center py-5 text-muted'>Please select a date range to view the report</td></tr>";
                    }
                ?>
                </tbody>
                <?php if(isset($query_run) && mysqli_num_rows($query_run) > 0) { ?>
                <tfoot class="bg-light border-top">
                    <tr style="font-size: 1.1rem;">
                        <td colspan="5" class="text-end fw-bold text-dark">TOTALS</td>
                        <td class="text-center fw-bold text-success bg-white border-start border-end">₹<?= number_format($in_sum, 2) ?></td>
                        <td class="text-center fw-bold text-danger bg-white border-start border-end">₹<?= number_format($ex_sum, 2) ?></td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end fw-bold text-secondary">NET PROFIT/LOSS</td>
                        <td colspan="2" class="text-center fw-bold fs-5 <?= ($in_sum >= $ex_sum) ? 'text-success' : 'text-danger' ?>">
                             <?= ($in_sum >= $ex_sum) ? '+' : '' ?>₹<?= number_format($in_sum - $ex_sum, 2) ?>
                        </td>
                    </tr>
                </tfoot>
                <?php } ?>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function(){
        $('#category_id').change(function(){
            var Stdid = $(this).val(); 
            $.ajax({
                type: 'POST',
                url: 'code-in_ex_cat_sub_fetch.php',
                data: {id: Stdid},  
                success: function(data) {
                    $('#subcategory_id').html(data);
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