<?php
session_start();
require "dbconfig.php";
require "component.php";


$username = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected fruit value from the form
    echo $selectedData = $_POST['selectedValue'];
    echo $billNo = $_POST['billNo'];
    echo $stbNo = $_POST['stbno'];
    echo $Rs = $_POST['Rs'];
    echo $date = $_POST['date'];
    
    $updateQuery = "UPDATE bill SET status = '$selectedData' WHERE billNo = '$billNo' AND date='$date'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {

        
            // if($selectedData == 'cancel'){ /// UPDATE DATA INTO in_ex Table
                
                // $sqlSum = "SELECT amount FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                // $result = $con->query($sqlSum);
                // $row = $result->fetch_assoc();
                // $oldAmount = $row["amount"];

                // // $updatedAmount = $oldAmount-$Rs;

                // if($oldAmount == 0){
                //     $updatedAmount = $oldAmount;
                // }else{
                //     $updatedAmount = $oldAmount - $Rs;
                // }

                // if ($result->num_rows > 0) {
                //     // Update existing record
                //     $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = '$updatedAmount' WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                //     $con->query($sqlUpdate);

                //     if (isset($_SESSION['id'])) {
                //         $userId = $_SESSION['id'];
                //         // $username = $_SESSION['username'];
                //         $role = $_SESSION['role'];
                //         $action = "Success - Income Report Updated to Cancel Indiv Billno:billNo,OldAmt:$oldAmount,UpdatedAmt:$updatedAmount ";
                    
                //         // Call the function to insert user activity log
                //         logUserActivity($userId, $username, $role, $action);
                //     }

                // }else{
                //         if (isset($_SESSION['id'])) {
                //         // Get the user information before destroying the session
                //         $userId = $_SESSION['id'];
                //         // $username = $_SESSION['username'];
                //         $role = $_SESSION['role'];
                //         $action = "Failed - Income Report Updated to Cancel Indiv Billno:$billNo,OldAmt:$oldAmount,UpdatedAmt:$updatedAmount ";
                    
                //         // Call the function to insert user activity log
                //         logUserActivity($userId, $username, $role, $action);
                //     }
                // }

                // Calculate sum of paid_amount for the current date
                $sqlSum = "SELECT SUM(paid_amount) AS total_paid FROM bill WHERE date = '$currentDate' AND status = 'approve'";
                $result = $con->query($sqlSum);
                $row = $result->fetch_assoc();
                $sumPaidAmount = $row["total_paid"];

                // Check if a record exists in in_ex table
                $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                $resultCheck = $con->query($sqlCheck);

                if ($resultCheck->num_rows > 0) {
                    // Update existing record
                    $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                    $con->query($sqlUpdate);
                } else {
                    // Insert new record
                    $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount)";
                    $con->query($sqlInsert);
                }

            // }
        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            // $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "From Cancel page Update Successful for Indiv BillNo: $billNo - $stbNo to - $selectedData   ";
        
            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
    
        ?>
        <!-- <center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center> -->
        <?php
    } else {
        // Database update failed, handle the error
        echo "Error updating the database: " . mysqli_error($con);

        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            $role = $_SESSION['role'];
            $action = "From Cancel page Update Failed for Indiv BillNo: $billNo - $stbNo to - $selectedValue";
        
            logUserActivity($userId, $username, $role, $action);
        }
        
        ?>
        <!-- <center><img src="assets/red-thumbs-down.svg" alt="green-thumbs-up" width="512px" height="512px"></center> -->
        <?php
    }
} else {

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


$url = "admin-bill-cancel.php"; // Replace with your desired URL
redirect($url);
?>
