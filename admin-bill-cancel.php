<?php 
   session_start();
   include "dbconfig.php";
   include 'component.php';
   include 'preloader.php';
   
//    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
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
    <title>Cancel Bill</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f9;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #edf2f7;
            padding: 1.25rem;
            border-radius: 12px 12px 0 0 !important;
        }
        .card-header h4 {
            color: #2d3748;
            font-weight: 700;
            margin: 0;
            font-size: 1.25rem;
        }
        .form-label {
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 0.625rem 1rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3182ce;
            box-shadow: 0 0 0 3px rgba(49, 130, 206, 0.1);
        }
        .btn-primary {
            background-color: #3182ce;
            border-color: #3182ce;
            border-radius: 8px;
            padding: 0.625rem 1.5rem;
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #2b6cb0;
            border-color: #2b6cb0;
        }
        .filter-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .checkbox-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: #f8fafc;
        }
        .checkbox-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #4a5568;
        }
        .checkbox-item input {
            margin-right: 8px;
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            background-color: #f7fafc;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            vertical-align: middle;
            border-bottom: 2px solid #edf2f7;
        }
        .table td {
            vertical-align: middle;
            color: #2d3748;
            border-bottom: 1px solid #edf2f7;
            font-size: 0.9rem;
        }
        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .sum-row td {
            font-weight: 700;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    
<?php
    include 'admin-menu-bar.php';
    echo '<br>';
    include 'admin-menu-btn.php';
?>
    <div class="container-fluid px-4">
        
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4><i class="fas fa-file-invoice-dollar me-2 text-primary"></i>Cancel Bill Dashboard</h4>
            </div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">From Bill Date</label>
                            <input type="date" name="from_date"
                                value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $currentDate; ?>"
                                class="form-control">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">To Bill Date</label>
                            <input type="date" name="to_date"
                                value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>"
                                class="form-control" required>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-2"></i> Search
                            </button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label class="form-label text-primary border-bottom border-primary pb-1 d-inline-block mb-3">Filter by Bill Generator</label>
                            <?php
                            $sql = "SELECT * FROM user WHERE status = 1";
                            $result = $con->query($sql);

                            if ($result->num_rows > 0) {
                                echo '<div class="checkbox-container">';
                                $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                while ($row = $result->fetch_assoc()) {
                                    $checked = in_array($row['username'], $filters) ? 'checked' : '';
                                    echo '<label class="checkbox-item">';
                                    echo '<input type="checkbox" name="filter[]" value="' . htmlspecialchars($row['username']) . '" ' . $checked . '>';
                                    echo htmlspecialchars($row['name']);
                                    echo '</label>';
                                }
                                echo '</div>';
                            } else {
                                echo '<div class="alert alert-warning">No users found.</div>';
                            }
                            ?>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label text-primary border-bottom border-primary pb-1 d-inline-block mb-2">Bill Status</label>
                            <div class="d-flex gap-3">
                                <?php 
                                $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array('approve'); // Default checked
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_filter[]" value="approve" id="checkApprove" <?php if(in_array('approve', (array)$status_filter)) echo 'checked'; ?>>
                                    <label class="form-check-label fw-bold text-success" for="checkApprove">
                                        Approved
                                    </label>
                                </div>
                                <!-- <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_filter[]" value="cancel" id="checkCancel" <?php if(in_array('cancel', (array)$status_filter)) echo 'checked'; ?>>
                                    <label class="form-check-label fw-bold text-danger" for="checkCancel">
                                        Cancelled
                                    </label>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Bill by</th>
                                <th>Col Date</th>
                                <th>Bill Date</th>
                                <th>Bill No</th>
                                <th>MSO</th>
                                <th>STB No</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Remark</th>
                                <th>P.Mode</th>
                                <th class="text-end">OldBal</th>
                                <th class="text-end">BillAmt</th>
                                <th class="text-end">Disct</th>
                                <th class="text-end">Rs</th>
                                <th class="text-center" style="width: 200px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <?php 
                            require 'dbconfig.php';
                            
                            $discount_sum = 0;
                            $paid_amount_sum = 0;
                            $Rs_sum = 0;
                            $oldMonthBal_sum = 0;
                            
                            if(isset($_GET['from_date']) && isset($_GET['to_date']))
                            {
                                $from_date = $_GET['from_date'];
                                $to_date = $_GET['to_date'];

                                $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array();
                            
                                $filterCondition = '';
                                $statusFilterCondition = '';

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

                                $query = "SELECT * FROM bill WHERE DATE(due_month_timestamp) BETWEEN '$from_date' AND '$to_date' $filterCondition $statusFilterCondition";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0)
                                {   
                                    $serial_number = 1; 

                                    foreach($query_run as $row)
                                    {
                                        ?>
                                        <tr>
                                                <td><?= $serial_number++; ?></td>
                                                <td class="fw-bold"><?= $row['bill_by']; ?></td>
                                                <td class="text-primary fw-bold"><?= formatDate($row['date']); ?></td>
                                                <td class="text-primary fw-bold">
                                                    <?PHP 
                                                        $current_result = splitDateAndTime(strtotime($row['due_month_timestamp'])); 
                                                        formatDate($current_result['date']);
                                                    ?>
                                                </td>
                                                <td><?= $row['billNo']; ?></td>
                                                <td><?= $row['mso']; ?></td>
                                                <td><?= $row['stbno']; ?></td>
                                                <td class="fw-bold"><?= $row['name']; ?></td>
                                                <td><?= $row['phone']; ?></td>
                                                <td><?= $row['description']; ?></td>
                                                <td><span class="badge bg-secondary"><?= ucfirst($row['pMode']); ?></span></td>
                                                <td class="text-end fw-bold text-primary">
                                                    <?= $row['oldMonthBal']; ?>
                                                </td>
                                                <td class="text-end fw-bold text-success"><?= $row['paid_amount']; ?></td>
                                                <td class="text-end fw-bold text-danger"><?= $row['discount']; ?></td>
                                                <td class="text-end fw-bold text-danger"><?= $row['Rs']; ?></td>
                                            
                                                <td class="text-center">
                                                    <form class="cancel-bill-form d-flex gap-2 justify-content-center" action="admin-code-bill-cancel.php" method="POST">
                                                        <select name="selectedValue" class="form-select form-select-sm" style="width: auto;">
                                                            <option value="approve" <?php if ($row['status'] === 'approve') { echo 'selected'; } ?>>Approve</option>
                                                            <option value="cancel" <?php if ($row['status'] === 'cancel') { echo 'selected'; } ?>>Cancel</option>
                                                        </select>
                                                        <input type="hidden" name="bill_id" value="<?= $row['bill_id']; ?>">
                                                        <input type="hidden" name="date" value="<?= $row['date']; ?>">
                                                        <input type="hidden" name="stbno" value="<?= $row['stbno']; ?>">
                                                        <input type="hidden" name="name" value="<?= $row['name']; ?>">
                                                        <input type="hidden" name="billNo" value="<?= $row['billNo']; ?>">
                                                        <input type="hidden" name="due_month_timestamp" value="<?= $row['due_month_timestamp']; ?>">
                                                        <input type="hidden" name="pMode" value="<?= $row['pMode']; ?>">
                                                        <input type="hidden" name="phone" value="<?= $row['phone']; ?>">
                                                        <button type="submit" class="btn btn-danger btn-sm px-3">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                        </tr>
                                        <?php 
                                        
                                        $Rs_sum += $row['Rs']; 
                                        $discount_sum += $row['discount'];
                                        $paid_amount_sum += $row['paid_amount'];
                                        $oldMonthBal_sum += $row['oldMonthBal'];
                                    }
                                }
                                else
                                {
                                    echo "<tr><td colspan='16' class='text-center py-4 text-muted fw-bold'>No Records Found</td></tr>";
                                }
                                $con->close();
                            }
                                ?>
                                        <tr class="sum-row border-top-2">
                                            <td colspan="11" class="text-end text-uppercase">Total</td>
                                            <td class="text-end text-primary"><?= $oldMonthBal_sum ?></td>
                                            <td class="text-end text-success"><?= $paid_amount_sum ?></td>
                                            <td class="text-end text-danger"><?= $discount_sum ?></td>
                                            <td class="text-end text-danger"><?= $Rs_sum ?></td>                                                
                                            <td></td>
                                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on("submit", "form.cancel-bill-form", function(e) {
    e.preventDefault();

    let form = $(this);
    let formData = form.serializeArray(); 
    let ACTION_TYPE = form.find('select[name="selectedValue"]').val();
    
    // Only ask for remark if cancelling? Or always? Original code always asked.
    // Let's improve the UX: If approving, maybe no remark needed? 
    // But original code implies this is a "Cancel Bill" dashboard, so maybe they are changing status.
    // The prompt says "Please provide a note / reference for canceling this bill." 
    // If they select "Approve", the text might be confusing. 
    // Let's make it dynamic.

    let titleText = ACTION_TYPE === 'cancel' ? "Cancel Bill?" : "Update Status?";
    let bodyText = ACTION_TYPE === 'cancel' 
        ? "Please provide a reason for cancelling this bill." 
        : "Please provide a note for this status update.";
    let confirmBtnText = ACTION_TYPE === 'cancel' ? "Yes, Cancel it!" : "Update";
    let confirmBtnColor = ACTION_TYPE === 'cancel' ? "#d33" : "#3085d6";


    Swal.fire({
        title: titleText,
        text: bodyText,
        input: "text",
        inputPlaceholder: "Type your remark here...",
        showCancelButton: true,
        confirmButtonColor: confirmBtnColor,
        cancelButtonColor: "#6c757d",
        confirmButtonText: confirmBtnText,
        cancelButtonText: "Close",
        inputValidator: (value) => {
            if (!value || value.trim().length < 4) {
                return "Remark is required and must be at least 4 characters!";
            }
            if (value.trim().length > 50) { // increased limit slightly
                 return "Remark cannot exceed 50 characters!";
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            
            formData.push({ name: "remark2", value: result.value });

            $.ajax({
                url: "admin-code-bill-cancel.php",
                type: "POST",
                data: $.param(formData),
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed",
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    console.error("Ajax Error:", xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "Something went wrong! Check console for details."
                    });
                }
            });
        }
    });
});
</script>


</body>
</html>

<?php include 'footer.php'?>

<?php }else{
	header("Location: logout.php");
} ?>