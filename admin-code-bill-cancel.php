<?php
session_start();
require "dbconfig.php";
require "component.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected fruit value from the form
    $selectedValue = $_POST['selectedValue'];
    $bill_no = $_POST['bill_id'];
    $stbNo = $_POST['stbno'];
    $date = $_POST['date'];
    $name = $_POST['name'];
    $billNo = $_POST['billNo'];
    $due_month_timestamp = $_POST['due_month_timestamp'];
    $pMode = $_POST['pMode'];
    $phone = $_POST['phone'];

    // Initialize $currentTime
    $currentTime = date('H:i:s'); // Set to current time in HH:mm:ss format

    // Sanitize variables (prevent SQL errors)
    $bill_no = mysqli_real_escape_string($con, $bill_no);
    $selectedValue = mysqli_real_escape_string($con, $selectedValue);
    $date = mysqli_real_escape_string($con, $date);

    // Update the table with the selected fruit value
    $updateQuery = "UPDATE bill SET status = '$selectedValue' WHERE bill_id = '$bill_no'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Calculate sum of paid_amount for the current date
        $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$date' AND status = 'approve'";
        $result = $con->query($sqlSum);

        if ($result) {
            $row = $result->fetch_assoc();
            $sumPaidAmount = $row["total_Rs"] ?? 0; // Default to 0 if NULL

            // Check if a record exists in in_ex table
            $sqlCheck = "SELECT * FROM in_ex WHERE date = '$date' AND category_id = 12 AND subcategory_id = 35 AND status = 1";
            $resultCheck = $con->query($sqlCheck);

            if ($resultCheck->num_rows > 0) {
                // Update existing record
                $sqlUpdate = "UPDATE in_ex 
                              SET 
                                  type = 'Income', 
                                  time = '$currentTime', 
                                  username = 'Auto', 
                                  remark = '', 
                                  amount = '$sumPaidAmount' 
                              WHERE 
                                  date = '$date' AND 
                                  category_id = 12 AND 
                                  subcategory_id = 35 AND 
                                  status = 1";
                $con->query($sqlUpdate);
            } else {
                // Insert new record
                $sqlInsert = "INSERT INTO in_ex (type, date, time, username, category_id, subcategory_id, remark, amount, status) 
                              VALUES ('Income', '$date', '$currentTime', 'Auto', 12, 35, '', '$sumPaidAmount', 1)";
                $con->query($sqlInsert);
            }
        }

        // Log user activity if session is active
        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "From Cancel page Update Successful for $stbNo to - $selectedValue";

            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }

        $bill_status = "cancel";
        $sms_res = send_INDIV_BILL_SMS($name, $phone, $billNo, $due_month_timestamp, $stbNo, $pMode, $bill_status);
        $sms_res_array = json_decode($sms_res, true);
        $sms_res_array_status = $sms_res_array['status'] ?? 'unknown';
        if (isset($_SESSION['id']) && $sms_res == true) {
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "Indiv Bill SMS Status: $sms_res_array_status | $phone - $stbNo";

            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
        ?>
        <center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    } else {
        // Database update failed, handle the error
        echo "Error updating the database: " . $con->error;

        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "From Cancel page Update Failed for $stbNo to - $selectedValue";

            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
        ?>
        <center><img src="assets/red-thumbs-down.svg" alt="red-thumbs-down" width="512px" height="512px"></center>
        <?php
    }
} else {
    // Redirect the user to the form page if accessed directly without submitting the form
    header("Location: admin-bill-cancel.php");
    exit();
}

// Redirect function
function redirect($url)
{
    echo "<script>
            setTimeout(function(){
                window.location.href = '$url';
            }, 300);
        </script>";
}

// Usage example
$url = "admin-bill-cancel.php"; // Replace with your desired URL
redirect($url);
?>
