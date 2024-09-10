<?php
session_start();
require 'dbconfig.php';
require "component.php";


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected fruit value from the form
    $selectedValue = $_POST['selectedValue'];
    $billNo = $_POST['billNo'];
    $date = $_POST['date'];
    $group_id = $_POST['group_id'];
    $Rs = $_POST['Rs'];

    $updateQuery = "UPDATE billgroupdetails
                        JOIN billgroup ON billgroupdetails.billNo = billgroup.billNo
                        SET billgroupdetails.status = '$selectedValue',
                            billgroup.status = '$selectedValue'
                        WHERE billgroupdetails.date = '$date' AND billgroup.date = '$date' AND billgroup.billNo = '$billNo'
                            AND billgroupdetails.group_id = '$group_id' AND billgroupdetails.billNo = '$billNo' AND billgroup.group_id = '$group_id'";
    
    // $updateQuery = "UPDATE billgroupdetails SET status = '$selectedValue' WHERE date = '$date' AND group_id = '$group_id'";
                            
                            
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Database update successful, perform any additional actions or display a success message
        echo "Database update successful. Bill Status updated to: " . $selectedValue; 

        if($selectedValue == 'cancel'){ /// UPDATE DATA INTO in_ex Table
                
            $sqlSum = "SELECT amount FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 36 AND status = 1";
            $result = $con->query($sqlSum);
            $row = $result->fetch_assoc();
            $oldAmount = $row["amount"];

            // $updatedAmount = $oldAmount-$Rs;

            if($oldAmount == 0){
                $updatedAmount = $oldAmount;
            }else{
                $updatedAmount = $oldAmount - $Rs;
            }

            if ($result->num_rows > 0) {
                // Update existing record
                $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = 12, subcategory_id = 36, remark='', amount = '$updatedAmount' WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 36 AND status = 1";
                $con->query($sqlUpdate);

                if (isset($_SESSION['id'])) {
                    $userId = $_SESSION['id'];
                    $username = 'Auto';
                    $role = $_SESSION['role'];
                    $action = "Success - Income Report Updated to Cancel Group Billno:$billNo,OldAmt:$oldAmount,UpdatedAmt:$updatedAmount ";
                
                    // Call the function to insert user activity log
                    logUserActivity($userId, $username, $role, $action);
                }

            }else{
                    if (isset($_SESSION['id'])) {
                    // Get the user information before destroying the session
                    $userId = $_SESSION['id'];
                    $username = 'Auto';
                    $role = $_SESSION['role'];
                    $action = "Failed - Income Report Updated to Cancel Group Billno:$billNo,OldAmt:$oldAmount,UpdatedAmt:$updatedAmount ";
                
                    // Call the function to insert user activity log
                    logUserActivity($userId, $username, $role, $action);
                }
            }
        }

    
        ?>
        <center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    } else {
        // Database update failed, handle the error
        echo "Error updating the database.". $con->error;
    
                        //   setTimeout(function() {
                        //     location.reload();
                        //   }, 200);
        
        ?>
        <center><img src="assets/red-thumbs-down.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    }
} else {
    // Redirect the user to the form page if accessed directly without submitting the form
    header("Location: admin-groupBill-cancel.php");
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
// $url = "http://localhost/ctv/bill-last5-print.php"; 
$url = "admin-groupBill-cancel.php"; // Replace with your desired URL
redirect($url);
?>
