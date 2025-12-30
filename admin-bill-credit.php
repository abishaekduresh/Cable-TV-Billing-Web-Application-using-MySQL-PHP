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
    <title>Credit Bill Dashboard</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
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
            cursor: pointer;
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
        .sum-row td {
            font-weight: 700;
            background-color: #f8fafc;
            font-size: 1rem;
        }
        /* Page specific styles */
        .text-blue-theme { color: #007DC3; }
        .text-green-theme { color: #05A210; }
        .text-pink-theme { color: #DD0581; }
        .text-red-theme { color: #F20000; }
        
        @media print {
            .no-print { display: none !important; }
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
        
        <div class="card mt-4 no-print">
            <div class="card-header">
                <h4>
                    <span><i class="fas fa-credit-card me-2 text-primary"></i>Credit Bill Dashboard</span>
                    <div>
                        <a href="prtindivcreditbillbulk.php" target="_blank" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-print me-1"></i> Print Pending Credit Bill
                        </a>
                        <a href="prtindivcreditbilllist.php" target="_blank" class="btn btn-dark btn-sm">
                            <i class="fas fa-list me-1"></i> Print List
                        </a>
                    </div>
                </h4>
            </div>
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">From Bill Date</label>
                            <input type="date" name="from_date" 
                                value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : '2023-06-01'; ?>" 
                                class="form-control">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">To Bill Date</label>
                            <input type="date" name="to_date" 
                                value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>" 
                                class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Phone</label>
                            <input type="number" name="phone" class="form-control" autocomplete="off" placeholder="Enter Phone Number">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
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
                                $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array('credit'); 
                                ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status_filter[]" value="credit" id="checkCredit" <?php if(in_array('credit', (array)$status_filter)) echo 'checked'; ?>>
                                    <label class="form-check-label fw-bold text-danger" for="checkCredit">
                                        Credit
                                    </label>
                                </div>
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
                                <th>Col Date</th>
                                <th>Bill Date</th>
                                <th>Bill No</th>
                                <th>MSO</th>
                                <th>STB No</th>
                                <th>Name</th>
                                <th class="text-center">Hist</th>
                                <th>Phone</th>
                                <th>Remarks</th>
                                <th>P.Mode</th>
                                <th class="text-end">OldBal</th>
                                <th class="text-end">Paid</th>
                                <th class="text-end">Disc</th>
                                <th class="text-end">Rs</th>
                                <th style="width: 200px;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <?php 
                            require_once 'dbconfig.php';
                            
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
                                $phone_filter = isset($_GET['phone']) ? $_GET['phone'] : '';
                            
                                $filterCondition = '';
                                $statusFilterCondition = '';
                                
                                if (!empty($filters)) {
                                    $filterCondition = "AND bill_by IN ('" . implode("','", $filters) . "')";
                                }
                                
                                if (!empty($status_filter)) {
                                    if (is_array($status_filter)) {
                                        $statusFilterCondition = "AND pMode IN ('" . implode("','", $status_filter) . "')";
                                    } else {
                                        $statusFilterCondition = "AND pMode = '$status_filter'";
                                    }
                                }
                                
                                $phoneFilterCondition = '';
                                if (!empty($phone_filter)) {
                                    $phoneFilterCondition = "AND phone = '$phone_filter'";
                                }

                                $query = "SELECT * FROM bill WHERE pMode = 'credit' AND DATE(due_month_timestamp) BETWEEN '$from_date' AND '$to_date' AND status ='approve' $filterCondition $statusFilterCondition $phoneFilterCondition";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0)
                                {   
                                    $serial_number = 1; 

                                    foreach($query_run as $row)
                                    {
                                        ?>
                                        <tr>
                                            <td><?= $serial_number++; ?></td>
                                            <td class="fw-bold text-blue-theme"><?= formatDate($row['date']); ?></td>
                                            <td class="fw-bold text-blue-theme">
                                                <?PHP 
                                                    $current_result = splitDateAndTime(strtotime($row['due_month_timestamp'])); 
                                                    formatDate($current_result['date']);
                                                ?>
                                            </td>
                                            <td class="fw-bold"><?= $row['billNo']; ?></td>
                                            <td class="fw-bold"><?= $row['mso']; ?></td>
                                            <td class="fw-bold"><?= $row['stbno']; ?></td>
                                            <td class="fw-bold"><?= $row['name']; ?></td>
                                            <td class="text-center"> 
                                                <a href="customer-history.php?search=<?= $row['stbno']; ?>" target="_blank" class="btn btn-outline-secondary btn-sm border-0">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </td>
                                            <td class="fw-bold"><?= $row['phone']; ?></td>
                                            <td class="fw-bold small"><?= $row['description']; ?></td>
                                            <td class="fw-bold"><span class="badge bg-danger">Credit</span></td>
                                            <td class="fw-bold text-end text-blue-theme"><?= $row['oldMonthBal']; ?></td>
                                            <td class="fw-bold text-end text-green-theme"><?= $row['paid_amount']; ?></td>
                                            <td class="fw-bold text-end text-pink-theme"><?= $row['discount']; ?></td>
                                            <td class="fw-bold text-end text-red-theme"><?= $row['Rs']; ?></td>
                                            <td>   
                                                <form class="update-pmode-form d-flex gap-2 justify-content-center" method="POST">                                                     
                                                        <select class="form-select form-select-sm bg-warning text-dark fw-bold border-0" name="selectedValue" style="width: 100px;">
                                                            <option value="cash" <?php if ($row['pMode'] === 'cash') { echo 'selected'; } ?>>Cash</option>
                                                            <option value="gpay" <?php if ($row['pMode'] === 'gpay') { echo 'selected'; } ?>>G Pay</option>
                                                            <option value="paytm" <?php if ($row['pMode'] === 'paytm') { echo 'selected'; } ?>>Paytm</option>
                                                            <option value="credit" <?php if ($row['pMode'] === 'credit') { echo 'selected'; } ?>>Credit</option>
                                                        </select>
                                                        
                                                        <input type="hidden" name="bill_no" value="<?= $row['bill_id']; ?>">
                                                        <input type="hidden" name="stbno" value="<?= $row['stbno']; ?>">
                                                        
                                                        <button type="submit" class="btn btn-primary btn-sm px-3 fw-bold">
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
                                    echo "<tr><td colspan='16' class='text-center py-4 text-muted fw-bold'>No Pending Credit Bills Found</td></tr>";
                                }
                            
                                $con->close();
                            }
                        ?>
                        <tr class="sum-row border-top-2">
                            <td colspan="11" class="text-end">Total :</td>
                            <td class="text-end text-blue-theme"><?= $oldMonthBal_sum ?></td>
                            <td class="text-end text-green-theme"><?= $paid_amount_sum ?></td>
                            <td class="text-end text-pink-theme"><?= $discount_sum ?></td>
                            <td class="text-end text-red-theme"><?= $Rs_sum ?></td>                                                
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<br/>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $(document).on("submit", ".update-pmode-form", function(e) {
        e.preventDefault();

        let form = $(this);
        let formData = form.serialize();

        Swal.fire({
            title: "Enter Remark",
            text: "Please provide a note / reference for updating this payment mode.",
            input: "text",
            inputPlaceholder: "Type your remark here...",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Submit",
            cancelButtonText: "Cancel",
            preConfirm: (inputValue) => {
                if (!inputValue || inputValue.trim().length < 4) {
                    Swal.showValidationMessage("Remark is required and must be at least 4 characters!");
                }
                return inputValue.trim();
            }
        }).then((result) => {
            if (result.isConfirmed) {
                formData += "&remark2=" + encodeURIComponent(result.value);
                
                // Show loading state
                Swal.fire({
                    title: 'Processing...',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });

                $.ajax({
                    url: "admin-code-bill-credit.php",
                    type: "POST",
                    data: formData,
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            Swal.fire({
                                icon: "success",
                                title: "Updated Successfully",
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Action Failed",
                                text: response.message || "Unknown error occurred"
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
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
});
</script>
    
</body>
</html>

<?php }else{
    header("Location: logout.php");
} ?>