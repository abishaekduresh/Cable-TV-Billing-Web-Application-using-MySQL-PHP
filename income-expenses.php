<?php
session_start();
include "dbconfig.php";
include 'preloader.php';
require 'component.php';

$swal_script = ""; // Initialize variable for SweetAlert script

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    
    // Determine Menu
    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
         echo '<br>';
        include 'admin-menu-btn.php';
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
         echo '<br>';
        include 'sub-menu-btn.php';
    }
 ?>

<?php
// --- PHP LOGIC FOR FORM SUBMISSION (Preserved) ---
if(isset($_POST['submitExpense'])) {
  $category = $_POST['category'];
  $subCategory = $_POST['subCategory'];
  $remark = $_POST['remark'];
  $amount = $_POST['amount'];
  $type = 'Expense';
  
  $sql = "INSERT INTO in_ex (type, date, time, username, category_id, subcategory_id, remark, amount, status) VALUES ('$type', '$currentDate', '$currentTime', '$session_username', '$category', '$subCategory', '$remark', '$amount','1')";
  
  if ($con->query($sql) === TRUE) {
        $swal_script = "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Expense Added',
                    text: 'Expense saved successfully!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'income-expenses.php';
                });
            });
        </script>";
  } else {
    $error_msg = $con->error;
    $swal_script = "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save expense: $error_msg',
                });
            });
        </script>";
  }
}

if(isset($_POST['submitIncome'])) {
  $category = $_POST['category'];
  $subCategory = $_POST['subCategory'];
  $remark = $_POST['remark'];
  $amount = $_POST['amount'];
  $type = 'Income';
  
  $sql = "INSERT INTO in_ex (type, date, time, username, category_id, subcategory_id, remark, amount, status) VALUES ('$type', '$currentDate', '$currentTime', '$session_username', '$category', '$subCategory', '$remark', '$amount','1')";
  
  if ($con->query($sql) === TRUE) {
       $swal_script = "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Income Added',
                    text: 'Income saved successfully!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'income-expenses.php';
                });
            });
        </script>";
  } else {
      $error_msg = $con->error;
      $swal_script = "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to save income: $error_msg',
                });
            });
        </script>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income & Expenses Manager</title>

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
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Nav Pills Styling */
        .nav-pills .nav-link {
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: 600;
            color: var(--text-light);
            background: white;
            border: 1px solid #e5e7eb;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .nav-pills .nav-link:hover:not(.active) {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        /* Card Styles */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
            height: 100%;
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

        /* Form Controls */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

        /* Buttons */
        .btn-submit {
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            width: 100%;
            transition: transform 0.2s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
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

        .amount-positive { color: var(--success-color); font-weight: 700; }
        .amount-negative { color: var(--danger-color); font-weight: 700; }
        
        .badge-pill {
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            background: #e2e8f0;
            color: #475569;
        }
        
    </style>
</head>
<body>

<div class="main-container container-xl">

    <!-- TABS -->
    <ul class="nav nav-pills justify-content-center mb-4">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="pill" href="#Expense"><i class="bi bi-graph-down-arrow me-2"></i>Add Expense</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="pill" href="#Income"><i class="bi bi-graph-up-arrow me-2"></i>Add Income</a>
        </li>    
    </ul>

    <!-- TAB CONTENT -->
    <div class="tab-content">
        
        <!-- ======================= EXPENSE TAB ======================= -->
        <div class="tab-pane container active" id="Expense">
            <div class="row g-4">
                <!-- EXPENSE FORM -->
                <div class="col-lg-4">
                    <div class="custom-card">
                        <div class="card-header-gradient" style="background: linear-gradient(135deg, #fff0f3 0%, #ffe3e3 100%);">
                            <h4 class="text-danger"><i class="bi bi-dash-circle-fill me-2"></i>New Expense</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="mb-3">
                                  <label class="form-label">Category</label>
                                    <select class="form-select" name="category" id="categoryID" required>
                                        <option selected disabled>Select Category</option>
                                        <?php
                                            $sql = "SELECT * FROM in_ex_category WHERE (in_ex='Expense' OR in_ex='Both') AND status = '1'";
                                            $result = mysqli_query($con, $sql);
                                            while($row = mysqli_fetch_assoc($result)) {
                                                echo '<option value="' . $row['category_id'] . '">' . $row['category'] . '</option>';
                                            }
                                        ?>
                                    </select>
                                </div>            
                                <div class="mb-3">
                                    <label class="form-label">Sub Category</label>
                                    <select class="form-select" name="subCategory" id="show_category">
                                        <option selected disabled>Select Category First</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Amount</label>
                                  <div class="input-group">
                                    <span class="input-group-text bg-white">₹</span>
                                    <input type="number" name="amount" class="form-control" required placeholder="0.00">
                                  </div>
                                </div>
                                <div class="mb-4">
                                  <label class="form-label">Remark</label>
                                  <textarea name="remark" class="form-control" rows="3" placeholder="Description (Optional)"></textarea>
                                </div>
                                <button type="submit" name="submitExpense" class="btn btn-danger btn-submit text-white shadow-sm">
                                    <i class="bi bi-plus-lg me-2"></i>Save Expense
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- EXPENSE TABLE -->
                <div class="col-lg-8">
                    <div class="custom-card">
                         <div class="card-header-gradient">
                            <h4><i class="bi bi-list-columns me-2"></i>Today's Expenses</h4>
                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                User: <?= $session_username ?>
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-custom">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time</th>
                                            <th>Category / Sub</th>
                                            <th>Remark</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            // $query = "SELECT * FROM in_ex WHERE username = '$session_username' AND date = '$currentDate' AND status = 1 AND type = 'Expense' ORDER BY date DESC";
                                            // Reverting to order by date as in_ex_id does not exist
                                            $query = "SELECT * FROM in_ex WHERE username = '$session_username' AND date = '$currentDate' AND status = 1 AND type = 'Expense' ORDER BY date DESC";
                                            $query_run = mysqli_query($con, $query);
                                            $ex_sum = 0;
                                            $sl = 1;

                                            if(mysqli_num_rows($query_run) > 0) {
                                                foreach($query_run as $row) {
                                                    $ex_sum += $row['amount'];
                                                    
                                                    // Fetch Category Name
                                                    $catName = "Unknown";
                                                    $catSql = "SELECT category FROM in_ex_category WHERE category_id='".$row['category_id']."'";
                                                    $catRes = mysqli_query($con, $catSql);
                                                    if($cRow = mysqli_fetch_assoc($catRes)) $catName = $cRow['category'];

                                                    // Fetch SubCategory Name
                                                    $subName = "-";
                                                    if($row['subcategory_id'] > 0){
                                                        $subSql = "SELECT subcategory FROM in_ex_subcategory WHERE subcategory_id='".$row['subcategory_id']."'";
                                                        $subRes = mysqli_query($con, $subSql);
                                                        if($sRow = mysqli_fetch_assoc($subRes)) $subName = $sRow['subcategory'];
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td class="text-muted small"><?= $sl++; ?></td>
                                                        <td class="text-muted small"><?= date("h:i A", strtotime($row['time'])); ?></td>
                                                        <td>
                                                            <div class="fw-bold text-dark"><?= $catName ?></div>
                                                            <div class="small text-muted"><?= $subName ?></div>
                                                        </td>
                                                        <td class="text-secondary small"><?= empty($row['remark']) ? '-' : $row['remark']; ?></td>
                                                        <td class="text-end amount-negative">- ₹<?= number_format($row['amount'], 2) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center py-5 text-muted">No expenses recorded today.</td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td colspan="4" class="text-end fw-bold text-secondary text-uppercase small">Total Expenses</td>
                                            <td class="text-end fw-bold fs-5 text-danger">₹<?= number_format($ex_sum, 2) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ======================= INCOME TAB ======================= -->
        <div class="tab-pane container fade" id="Income">
            <div class="row g-4">
                <!-- INCOME FORM -->
                <div class="col-lg-4">
                    <div class="custom-card">
                       <div class="card-header-gradient" style="background: linear-gradient(135deg, #e6fffa 0%, #d1fae5 100%);">
                            <h4 class="text-success"><i class="bi bi-plus-circle-fill me-2"></i>New Income</h4>
                        </div>
                        <div class="card-body p-4">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="mb-3">
                                  <label class="form-label">Category</label>
                                    <select class="form-select" name="category" id="category_ID" required>
                                        <option selected disabled>Select Category</option>
                                        <?php
                                        $sql = "SELECT * FROM in_ex_category WHERE (in_ex='Income' OR in_ex='Both') AND status = 1";
                                        $result = mysqli_query($con, $sql);
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo '<option value="' . $row['category_id'] . '">' . $row['category'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>            
                                <div class="mb-3">
                                    <label class="form-label">Sub Category</label>
                                    <select class="form-select" name="subCategory" id="show_subcategory" required>
                                        <option selected disabled>Select Category First</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                  <label class="form-label">Amount</label>
                                  <div class="input-group">
                                    <span class="input-group-text bg-white">₹</span>
                                    <input type="number" name="amount" class="form-control" required placeholder="0.00">
                                  </div>
                                </div>
                                <div class="mb-4">
                                  <label class="form-label">Remark</label>
                                  <textarea name="remark" class="form-control" rows="3" placeholder="Description (Optional)"></textarea>
                                </div>
                                <button type="submit" name="submitIncome" class="btn btn-success btn-submit text-white shadow-sm">
                                    <i class="bi bi-plus-lg me-2"></i>Save Income
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- INCOME TABLE -->
                <div class="col-lg-8">
                     <div class="custom-card">
                         <div class="card-header-gradient">
                            <h4><i class="bi bi-list-columns me-2"></i>Today's Income</h4>
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                User: <?= $session_username ?>
                            </span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table-custom">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Time</th>
                                            <th>Category / Sub</th>
                                            <th>Remark</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            // $query = "SELECT * FROM in_ex WHERE username = '$session_username' AND date = '$currentDate' AND status = 1 AND type = 'Income' ORDER BY date DESC";
                                             $query = "SELECT * FROM in_ex WHERE username = '$session_username' AND date = '$currentDate' AND status = 1 AND type = 'Income' ORDER BY date DESC";
                                            
                                            $query_run = mysqli_query($con, $query);
                                            $in_sum = 0;
                                            $sl = 1;

                                            if(mysqli_num_rows($query_run) > 0) {
                                                foreach($query_run as $row) {
                                                    $in_sum += $row['amount'];
                                                    
                                                     // Fetch Category Name
                                                    $catName = "Unknown";
                                                    $catSql = "SELECT category FROM in_ex_category WHERE category_id='".$row['category_id']."'";
                                                    $catRes = mysqli_query($con, $catSql);
                                                    if($cRow = mysqli_fetch_assoc($catRes)) $catName = $cRow['category'];

                                                    // Fetch SubCategory Name
                                                    $subName = "-";
                                                    if($row['subcategory_id'] > 0){
                                                        $subSql = "SELECT subcategory FROM in_ex_subcategory WHERE subcategory_id='".$row['subcategory_id']."'";
                                                        $subRes = mysqli_query($con, $subSql);
                                                        if($sRow = mysqli_fetch_assoc($subRes)) $subName = $sRow['subcategory'];
                                                    }

                                                    ?>
                                                    <tr>
                                                        <td class="text-muted small"><?= $sl++; ?></td>
                                                        <td class="text-muted small"><?= date("h:i A", strtotime($row['time'])); ?></td>
                                                         <td>
                                                            <div class="fw-bold text-dark"><?= $catName ?></div>
                                                            <div class="small text-muted"><?= $subName ?></div>
                                                        </td>
                                                        <td class="text-secondary small"><?= empty($row['remark']) ? '-' : $row['remark']; ?></td>
                                                        <td class="text-end amount-positive">+ ₹<?= number_format($row['amount'], 2) ?></td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center py-5 text-muted">No income recorded today.</td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <td colspan="4" class="text-end fw-bold text-secondary text-uppercase small">Total Income</td>
                                            <td class="text-end fw-bold fs-5 text-success">₹<?= number_format($in_sum, 2) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Scripts for AJAX Fetching (Category/Subcategory) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // Expense Subcategory Fetch
        $('#categoryID').change(function(){
            var Stdid = $('#categoryID').val(); 
            $.ajax({
                type: 'POST',
                url: 'code-in_ex_cat_sub_fetch.php',
                data: {id: Stdid},  
                success: function(data) {
                    $('#show_category').html(data);
                }
            });
        });

        // Income Subcategory Fetch
        $('#category_ID').change(function(){
            var Stdid = $('#category_ID').val(); 
            $.ajax({
                type: 'POST',
                url: 'code-in_ex_cat_sub_fetch.php',
                data: {id: Stdid},  
                success: function(data) {
                    $('#show_subcategory').html(data);
                }
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert Script Logic -->
<?php echo $swal_script; ?>

<?php include 'footer.php'?>
</body>
</html>

<?php } else {
	header("Location: index.php");
} ?>

