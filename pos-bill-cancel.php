<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username'], $_SESSION['id'], $_SESSION['role']) && $_SESSION['role'] === 'admin') {
    $session_username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Bill Cancel</title>
    <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">-->
    <style>
        /* CSS to remove underline and change color for <a> tags */
        a.link {
            text-decoration: none; /* Remove underline */
            color: white; /* Change color to red */
        }
    </style>
</head>

<body>

<?php
include 'admin-menu-bar.php';
?><br><?php
include 'admin-menu-btn.php';

?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h4>POS Bill Cancel</h4>
                </div>
                <div class="card-body">

                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>From Bill Date</label>
                                    <input type="date" name="from_date"
                                           value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : date('Y-m-d'); ?>" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>To Bill Date</label>
                                    <input type="date" name="to_date"
                                           value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : date('Y-m-d'); ?>" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <br>
                                    <button type="submit" class="btn btn-primary">Search</button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>From Bill No.</label>
                                    <input type="number" name="from_billno" value="<?php echo isset($_GET['from_billno']) ? $_GET['from_billno'] : ''; ?>" class="form-control" step="1">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>To Bill No.</label>
                                    <input type="number" name="to_billno" value="<?php echo isset($_GET['to_billno']) ? $_GET['to_billno'] : ''; ?>" class="form-control" step="1">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <b>
                                        <label><u>Bill By :</u></label><br>
                                        <label><input type="checkbox" name="filter[]" value="23A002"> Duresh</label>
                                        <label><input type="checkbox" name="filter[]" value="23A001"> Baskar Raj</label>
                                        <label><input type="checkbox" name="filter[]" value="23E005"> Divya</label>
                                        <label><input type="checkbox" name="filter[]" value="23E002"> Santhanam</label>
                                        <label><input type="checkbox" name="filter[]" value="23E003"> Thatha</label>
                                        <br>
                                        <label><u>Bill Status :</u></label>
                                        <br>
                                        <label><input type="checkbox" name="status_filter[]" value="0"> Cancel</label>
                                        <label><input type="checkbox" name="status_filter[]" value="1" checked> Approve</label>
                                        <br>
                                        <label><u>Bill Payment Mode :</u></label>
                                        <br>
                                        <label><input type="checkbox" name="pMode_filter[]" value="1"> Cash</label>
                                        <label><input type="checkbox" name="pMode_filter[]" value="2"> GPay</label>
                                        <label><input type="checkbox" name="pMode_filter[]" value="3"> Paytm</label>
                                        <label><input type="checkbox" name="pMode_filter[]" value="4"> Credit</label>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Bill by</th>
                                <th>Bill Date</th>
                                <th>Bill No</th>
                                <th>Customer Name</th>
                                <th>Phone</th>
                                <th>Pay Mode</th>
                                <!--<th>Bill Amt</th>-->
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php
                            require 'dbconfig.php';
                            $discount_sum = '';
                            $paid_amount_sum = '';
                            $Rs_sum = '';
                            $oldMonthBal_sum = '';

                            if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                $from_date = $_GET['from_date'];
                                $to_date = $_GET['to_date'];

                                $from_billno = isset($_GET['from_billno']) ? $_GET['from_billno'] : '';
                                $to_billno = isset($_GET['to_billno']) ? $_GET['to_billno'] : '';
                                $billnoFilterCondition = '';

                                if (!empty($from_billno) && !empty($to_billno)) {
                                    $billnoFilterCondition = "AND bill_no BETWEEN '$from_billno' AND '$to_billno'";
                                }

                                // Retrieve selected filter options
                                $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array();
                                $pMode_filter = isset($_GET['pMode_filter']) ? $_GET['pMode_filter'] : array();

                                // Build the filter condition
                                $filterCondition = '';
                                $statusFilterCondition = '';
                                $pModefilterCondition = '';

                                if (!empty($filters)) {
                                    $filterCondition = "AND username IN ('" . implode("','", $filters) . "')";
                                }

                               
                                if (!empty($status_filter)) {
                                    if (is_array($status_filter)) {
                                        $statusFilterCondition = "AND status IN ('" . implode("','", $status_filter) . "')";
                                    } else {
                                        $statusFilterCondition = "AND status = '$status_filter'";
                                    }
                                }

                                if (!empty($pMode_filter)) {
                                    if (is_array($pMode_filter)) {
                                        $pModefilterCondition = "AND pay_mode IN ('" . implode("','", $pMode_filter) . "')";
                                    } else {
                                        $pModefilterCondition = "AND pay_mode = '$pMode_filter'";
                                    }
                                }

                                $query = "SELECT pb.*
                                        FROM pos_bill pb
                                        WHERE DATE(pb.entry_timestamp) BETWEEN '$from_date' AND '$to_date'
                                        $billnoFilterCondition $filterCondition $statusFilterCondition $pModefilterCondition;
                                    ";

                                $query_run = mysqli_query($con, $query);
                                $Rs_sum = 0;
                                $discount_sum = 0;
                                $paid_amount_sum = 0;
                                $oldMonthBal_sum = 0;

                                if (mysqli_num_rows($query_run) > 0) {
                                    $serial_number = 1; // Initialize the serial number
                                    $bill_amt = 0;

                                    foreach ($query_run as $row) {
                                        $bill_amt -= $row['discount'];
                                        $pay_mode = getPayModeName($row['pay_mode']);
                            ?>
                                        <tr>
                                            <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                            <td style="font-weight: bold;"><?= $row['username']; ?></td>
                                            <td style="width: 220px; font-weight: bold; color: #007DC3;"><?= formatDate($row['entry_timestamp']); ?></td>
                                            <td style="font-weight: bold;"><?= $row['bill_no']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['cus_name']; ?></td>
                                            <td style="font-weight: bold;"><?= $row['cus_phone']; ?></td>
                                            <td style="font-weight: bold;"><?= $pay_mode ?></td>
                                            <td>
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal"
                                                        data-username="<?= $row['username']; ?>"
                                                        data-entry_timestamp="<?= formatDate($row['entry_timestamp']); ?>"
                                                        data-bill_no="<?= $row['bill_no']; ?>"
                                                        data-cus_name="<?= $row['cus_name']; ?>"
                                                        data-cus_phone="<?= $row['cus_phone']; ?>"
                                                        data-pay_mode="<?= $row['pay_mode']; ?>"
                                                        data-pos_bill_id="<?= $row['pos_bill_id']; ?>"
                                                        data-status="<?= $row['status']; ?>">
                                                    View
                                                </button>
                                            </td>
                                        </tr>
                            <?php
                                    }
                                } else {
                                    echo "No Record Found";
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

<form id="updatePOSBillStatusForm" method="POST" autocomplete="off">
    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="modal-username" name="username">
                    <input type="hidden" id="modal-entry_timestamp" name="entry_timestamp">
                    <input type="hidden" id="modal-bill_no" name="bill_no">
                    <input type="hidden" id="modal-cus_name" name="cus_name">
                    <input type="hidden" id="modal-cus_phone" name="cus_phone">
                    <input type="hidden" id="modal-pay_mode" name="pay_mode">
                    <input type="hidden" id="modal-pos_bill_id" name="pos_bill_id">

                    <!-- Display fields here if needed -->
                    <p><strong>Entry Timestamp:</strong> <span id="modal-entry_timestamp_display"></span></p>
                    <p><strong>Bill No:</strong> <span id="modal-bill_no_display"></span></p>
                    <p><strong>Customer Name:</strong> <span id="modal-cus_name_display"></span></p>
                    <p><strong>Customer Phone:</strong> <span id="modal-cus_phone_display"></span></p>

                    <select id="modal-status" name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Cancel</option>
                    </select>

                    <!-- Add fields here if needed -->

                    <!-- Submit button -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <!--<input type="hidden" id="updatePOSBillStatusBtn" name="product_id">-->
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Notification Modal -->
<div class="modal fade" id="notificationModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
            </div>
            <div class="modal-body">
                <span id="response"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <!--  <button type="submit" class="btn btn-primary">Save Product</button>-->
            </div>
        </div>
    </div>
</div>

<br>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function () {

        function openNotificationModel() {
            var notificationModel = new bootstrap.Modal(document.getElementById('notificationModel'));
            notificationModel.show();
        }

        // Add event listener for when the modal is shown
        $('#productModal').on('show.bs.modal', function (event) {
            // Get the button that triggered the modal
            var button = $(event.relatedTarget);

            // Get the data attributes from the button
            var username = button.data('username');
            var entry_timestamp =
 button.data('entry_timestamp');
            var bill_no = button.data('bill_no');
            var cus_name = button.data('cus_name');
            var cus_phone = button.data('cus_phone');
            var pay_mode = button.data('pay_mode');
            var pos_bill_id = button.data('pos_bill_id');
            var status = button.data('status');

            // Populate the modal form fields with the data
            $('#modal-username').val(username);
            $('#modal-entry_timestamp').val(entry_timestamp);
            $('#modal-bill_no').val(bill_no);
            $('#modal-cus_name').val(cus_name);
            $('#modal-cus_phone').val(cus_phone);
            $('#modal-pay_mode').val(pay_mode);
            $('#modal-pos_bill_id').val(pos_bill_id);
            $('#modal-status').val(status);

            // Display data in the modal
            $('#modal-username_display').text(username);
            $('#modal-entry_timestamp_display').text(entry_timestamp);
            $('#modal-bill_no_display').text(bill_no);
            $('#modal-cus_name_display').text(cus_name);
            $('#modal-cus_phone_display').text(cus_phone);
            $('#modal-pay_mode_display').text(pay_mode);
            $('#modal-status_display').text(status == 1 ? 'Active' : 'Cancel');

            // Set the selected option in the select tag
            if (status == 1) {
                $('#modal-status option[value="1"]').prop('selected', true);
            } else {
                $('#modal-status option[value="0"]').prop('selected', true);
            }
        });

        // Form submission
        $('#updatePOSBillStatusForm').submit(function (event) {
            event.preventDefault();
            console.log("Form submitted");

            var formData = $(this).serializeArray(); // Serialize form data into an array of objects

            var jsonData = {};
            formData.forEach(function (item) {
                jsonData[item.name] = item.value;
            });

            // console.log(JSON.stringify(jsonData));

            $.ajax({
                type: "POST",
                url: "api/v1/pos/update-pos-bill-status.php",
                data: JSON.stringify(jsonData), // Pass JSON data
                contentType: "application/json",
                success: function (response) {
                    console.log(response);
                    $("#response").html(response.message);
                    openNotificationModel();
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                    // Handle success response
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    // Handle error response
                }
            });
        });

    });
</script>

</body>
</html>

<?php //include 'footer.php'?>

<?php } else {
    header("Location: index.php");
}
?>
