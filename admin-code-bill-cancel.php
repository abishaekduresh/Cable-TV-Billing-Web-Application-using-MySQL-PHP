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
    // $ = $_POST[''];

    // Update the table with the selected fruit value
    $updateQuery = "UPDATE bill SET status = '$selectedValue' WHERE bill_id = '$bill_no'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Database update successful, perform any additional actions or display a success message
        // echo "Database update successful. Bill Status updated to: " . $selectedValue; 

                // Calculate sum of paid_amount for the current date
                $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$date' AND status = 'approve'";
                $result = $con->query($sqlSum);
                $row = $result->fetch_assoc();
                $sumPaidAmount = $row["total_Rs"];

                // Check if a record exists in in_ex table
                $sqlCheck = "SELECT * FROM in_ex WHERE date = '$date' AND category_id = 12 AND subcategory_id = 35";
                $resultCheck = $con->query($sqlCheck);

                if ($resultCheck->num_rows > 0) {
                    // Update existing record
                    $sqlUpdate = "UPDATE in_ex SET type='Income', date='$date', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$date' AND category_id = 12 AND subcategory_id = 35";
                    $con->query($sqlUpdate);
                } else {
                    // Insert new record
                    $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$date', '$currentTime','Auto', '12', '35','', $sumPaidAmount)";
                    $con->query($sqlInsert);
                }

        if (isset($_SESSION['id'])) {
            // Get the user information before destroying the session
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "From Cancel page Update Successful for $stbNo to - $selectedValue";
        
            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
            $bill_status = "cancel";
            $sms_res = sms_api($name, $phone, $billNo, $due_month_timestamp, $stbNo, $pMode, $bill_status);
            
            if (isset($_SESSION['id']) && $sms_res == true) {
                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $username = $_SESSION['username'];
                $role = $_SESSION['role'];
                $action = "Cancel SMS Send to $phone - $stbNo";
            
                // Call the function to insert user activity log
                logUserActivity($userId, $username, $role, $action);
            }
        ?>
        <center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    } else {
        // Database update failed, handle the error
        echo "Error updating the database.";

        if (isset($_SESSION['id'])) {
            // Get the user information before destroying the session
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "rom Cancel page Update Failed for $stbNo to - $selectedValue";
        
            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
        
        ?>
        <center><img src="assets/red-thumbs-down.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
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
            }, 200);
        </script>";
}

// Usage example
// $url = "http://localhost/ctv/bill-last5-print.php"; 
$url = "admin-bill-cancel.php"; // Replace with your desired URL
redirect($url);
?>