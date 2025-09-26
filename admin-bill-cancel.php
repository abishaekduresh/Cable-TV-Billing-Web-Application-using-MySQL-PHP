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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
<?php

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card mt-2">
                    <div class="card-header">
                        <h4>Cancel Bill Dashboard</h4>
                    </div>
                    <div class="card-body">
                    
                        <form action="" method="GET">
                            <div class="row">
                                <!-- <div class="col-md-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <label>From Date</label>-->
                                <!--        <input type="date" name="from_date" value="<?php if(isset($_GET['from_date'])){ echo $_GET['from_date']; } ?>" class="form-control">-->
                                <!--    </div>-->
                                <!--</div>-->
                                <!--<div class="col-md-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <label>To Date</label>-->
                                <!--        <input type="date" name="to_date" value="<?php if(isset($_GET['to_date'])){ echo $_GET['to_date']; } ?>" class="form-control">-->
                                <!--    </div>-->
                                <!--</div> -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>From Bill Date</label>
                                        <input type="date" name="from_date"
                                            value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $currentDate; ?>"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>To Bill Date</label>
                                        <input type="date" name="to_date"
                                            value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="form-group w-100">
                                        <button type="submit" class="btn btn-primary w-100">Search</button>
                                    </div>
                                </div>
                                <!--<div class="col-md-4">-->
                                <!--    <div class="form-group">-->
                                <!--        <label><input type="checkbox" name="filter[]" value="kannika" <?php if(isset($_GET['filter']) && in_array('23E001', $_GET['filter'])) echo 'checked'; ?>> Kannika</label>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="duresh" <?php if(isset($_GET['filter']) && in_array('23A002', $_GET['filter'])) echo 'checked'; ?>> Duresh</label>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="baskarraj" <?php if(isset($_GET['filter']) && in_array('23A001', $_GET['filter'])) echo 'checked'; ?>> Baskar Raj</label><br/>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="cancel" <?php if(isset($_GET['filter']) && in_array('cancel', $_GET['filter'])) echo 'checked'; ?>> Cancell Bill</label>-->
                                <!--        <label><input type="checkbox" name="filter[]" value="approve" <?php if(isset($_GET['filter']) && in_array('approve', $_GET['filter'])) echo 'checked'; ?>> Approve</label>-->
                                        <!-- Add more checkboxes for other filter options -->
                                <!--    </div>-->
                                <!--</div>-->
                                <div class="form-group">
                                    <label style="font-weight: bold;"><u>Bill By :</u></label><br>
                                    <?php
                                    $sql = "SELECT * FROM user WHERE status = 1";
                                    $result = $con->query($sql);

                                    // Step 3: Check if any data was fetched and loop through the results
                                    if ($result->num_rows > 0) {
                                        // Start the container for the checkbox layout
                                        echo '<div class="checkbox-container">';
                                        
                                        // Step 4: Loop through each record and generate HTML with checkboxes
                                        $counter = 0; // Counter to track columns
                                        while ($row = $result->fetch_assoc()) {
                                            // Assuming 'username' is the value and 'name' is the label text
                                            echo '<label style="font-weight: bold;"><input type="checkbox" name="filter[]" value="' . htmlspecialchars($row['username']) . '">';
                                            echo htmlspecialchars($row['name']) . '</label>&nbsp;&nbsp;&nbsp;';

                                            // After every third checkbox, insert a line break (to create three columns per row)
                                            $counter++;
                                            if ($counter % 3 == 0) {
                                                echo '<br>';
                                            }
                                        }
                                        
                                        // End the container for the checkbox layout
                                        echo '</div>';
                                    } else {
                                        echo "No records found.";
                                    }
                                        ?>
                                    <br>
                                    <label style="font-weight: bold;"><u>Bill Status :</u></label>
                                    <br>
                                    <!-- <label style="font-weight: bold;"><input type="checkbox" name="status_filter[]" value="cancel"> Cancel</label> -->
                                    <label style="font-weight: bold;"><input type="checkbox" name="status_filter[]" value="approve" checked> Approve</label>
                                    <!-- Add more checkboxes for other filter options -->
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
                                    <th>Col Date</th>
                                    <th>Bill Date</th>
                                    <th>Bill No</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Remark</th>
                                    <th>P.Mode</th>
                                    <th>OldBal</th>
                                    <th>BillAmt</th>
                                    <th>Disct</th>
                                    <th>Rs</th>
                                    <th colspan="2"><center>Cancel Bill</center></th>
                                </tr>
                            </thead>
                            <tbody>
                            
                            <?php 
                                // $con = mysqli_connect("localhost","root","","phptutorials");
                                require 'dbconfig.php';
                                
                                $discount_sum = '';
                                $paid_amount_sum = '';
                                $Rs_sum = '';
                                $oldMonthBal_sum = '';
                                
                                if(isset($_GET['from_date']) && isset($_GET['to_date']))
                                {
                                    $from_date = $_GET['from_date'];
                                    $to_date = $_GET['to_date'];

                                    // Retrieve selected filter options
                                    $filters = isset($_GET['filter']) ? $_GET['filter'] : array();
                                    $status_filter = isset($_GET['status_filter']) ? $_GET['status_filter'] : array();
                                
                                
                                    // Build the filter condition
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
                                    
                                    $Rs_sum = 0; 
                                    $discount_sum = 0;
                                    $paid_amount_sum = 0;
                                    $oldMonthBal_sum = 0;

                                    if(mysqli_num_rows($query_run) > 0)
                                    {   
                                        $serial_number = 1; // Initialize the serial number

                                        foreach($query_run as $row)
                                        {
                                            ?>
                                            <!-- <form action="admin-code-bill-cancel.php" method="POST"> -->
                                            <tr>
                                                    <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['bill_by']; ?></td>
                                                    <td style="font-weight: bold; color: #007DC3;"><?= formatDate($row['date']); ?></td>
                                                    <td style="font-weight: bold; color: #007DC3;">
                                                        <?PHP 
                                                            $current_result = splitDateAndTime(strtotime($row['due_month_timestamp'])); 
                                                            formatDate($current_result['date']);
                                                            // echo '&nbsp';
                                                            // $t=convertTo12HourFormat($current_result['time']);
                                                            // echo $t;
                                                        ?>
                                                    </td>
                                                    <td style="font-weight: bold;"><?= $row['billNo']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['mso']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['stbno']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['name']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['phone']; ?></td>
                                                    <td style="font-weight: bold;"><?= $row['description']; ?></td>
                                                    <td style="font-weight: bold;"><?= ucfirst($row['pMode']); ?></td>
                                                    <td style="font-weight: bold; color: #0012C3;">
                                                        <?= $row['oldMonthBal']; ?>
                                                    </td>
                                                    <td style="font-weight: bold; color: #05A210;"><?= $row['paid_amount']; ?></td>
                                                    <td style="font-weight: bold; color: #DD0581;"><?= $row['discount']; ?></td>
                                                    <td style="font-weight: bold; color: #F20000;"><?= $row['Rs']; ?></td>
                                                <form class="cancel-bill-form" action="admin-code-bill-cancel.php" method="POST">
                                                    <td>
                                                        <select name="selectedValue" class="form-select bg-warning text-dark">
                                                            <option value="approve" <?php if ($row['status'] === 'approve') { echo 'selected'; } ?>>Approve</option>
                                                            <option value="cancel" <?php if ($row['status'] === 'cancel') { echo 'selected'; } ?>>Cancel</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="bill_id" value="<?= $row['bill_id']; ?>">
                                                        <input type="hidden" name="date" value="<?= $row['date']; ?>">
                                                        <input type="hidden" name="stbno" value="<?= $row['stbno']; ?>">
                                                        <input type="hidden" name="name" value="<?= $row['name']; ?>">
                                                        <input type="hidden" name="billNo" value="<?= $row['billNo']; ?>">
                                                        <input type="hidden" name="due_month_timestamp" value="<?= $row['due_month_timestamp']; ?>">
                                                        <input type="hidden" name="pMode" value="<?= $row['pMode']; ?>">
                                                        <input type="hidden" name="phone" value="<?= $row['phone']; ?>">
                                                        <!-- Assign 'bill_id' value to the hidden input field for 'bill_no' -->
                                                        <button type="submit" class="btn btn-danger btn-sm" style="font-weight: bold;">Submit</button>
                                                    </td>
                                                </form>
                                            </tr>
                                            <?php 
                                            
                                            $Rs_sum += $row['Rs']; // Add the value to the sum variable
                                            $discount_sum += $row['discount'];
                                            $paid_amount_sum += $row['paid_amount'];
                                            $oldMonthBal_sum += $row['oldMonthBal'];
                                        
                                            // Display the total sum
                                            ?>
                                            
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<tr><td colspan='12' style='text-align:center; font-weight:bold;'>No Record Found</td></tr>";
                                    }
                                
                                    // Close the database connection
                                        $con->close();
                                
                                }
                                    ?>
                                            <tr>
                                                <td colspan="10"></td>
                                                <td style="font-weight: bold;">Total :</td>
                                                <td style="font-weight: bold; color: #0012C3;"><?= $oldMonthBal_sum ?></td>
                                                <td style="font-weight: bold; color: #05A210;"><?= $paid_amount_sum ?></td>
                                                <td style="font-weight: bold; color: #DD0581;"><?= $discount_sum ?></td>
                                                <td style="font-weight: bold; color: #F20000;"><?= $Rs_sum ?></td>                                                
                                                <td></td>
                                            </tr>
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).on("submit", "form.cancel-bill-form", function(e) {
    e.preventDefault();

    let form = $(this);
    let formData = form.serializeArray(); // serialize as array

    Swal.fire({
        title: "Enter Remark",
        text: "Please provide a note / reference for canceling this bill.",
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
            } else if (inputValue.trim().length > 30) {
                Swal.showValidationMessage("Remark cannot exceed 30 characters!");
            }
            return inputValue.trim();
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Add remark2 to the serialized array
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
                            title: "Bill " + response.data.name,
                            text: response.message,
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 1500
                        });
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Failed!",
                            text: response.message,
                            toast: true,
                            position: "top-end",
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 1500
                        });
                    }
                },
                error: function(xhr) {
                    console.error("Ajax Error:", xhr.responseText);
                    Swal.fire({
                        icon: "error",
                        title: "Server Error",
                        text: "Something went wrong!",
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timerProgressBar: true,
                        timer: 1500
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