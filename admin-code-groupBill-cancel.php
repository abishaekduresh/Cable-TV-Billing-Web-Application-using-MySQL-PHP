<?php
session_start();
require 'dbconfig.php';


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected fruit value from the form
    $selectedValue = $_POST['selectedValue'];
    // $billNo = $_POST['billNo'];
    $date = $_POST['date'];
    $groupID = $_POST['groupID'];
    
    // Perform any necessary validation or sanitization of the input data

    // Connect to the database (assuming you have a database connection established)

   // Update the table with the selected fruit value
    $updateQuery = "UPDATE billgroupdetails
                        JOIN billgroup ON billgroupdetails.billGroupNo = billgroup.billNo
                        SET billgroupdetails.status = '$selectedValue',
                            billgroup.status = '$selectedValue'
                        WHERE billgroupdetails.date = '$date' AND billgroup.date = '$date'
                            AND billgroupdetails.groupID = '$groupID' AND billgroup.groupID = '$groupID'";
    
    // $updateQuery = "UPDATE billgroupdetails SET status = '$selectedValue' WHERE date = '$date' AND groupID = '$groupID'";
                            
                            
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Database update successful, perform any additional actions or display a success message
        echo "Database update successful. Bill Status updated to: " . $selectedValue; 
        
    // if (isset($_SESSION['id'])) {
    //     // Get the user information before destroying the session
    //     $userId = $_SESSION['id'];
    //     $username = $_SESSION['username'];
    //     $role = $_SESSION['role'];
    //     $action = "From Cancel page Update Successful for $stbNo to - $selectedValue";

    //     // Insert user Bill Excel downloaded
    //     $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$username', '$role', '$action')";
    //     mysqli_query($con, $insertSql);
    // }
    
        ?>
        <center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    } else {
        // Database update failed, handle the error
        echo "Error updating the database.". $con->error;

    // if (isset($_SESSION['id'])) {
    //     // Get the user information before destroying the session
    //     $userId = $_SESSION['id'];
    //     $username = $_SESSION['username'];
    //     $role = $_SESSION['role'];
    //     $action = "rom Cancel page Update Failed for $stbNo to - $selectedValue";

    //     // Insert user Bill Excel downloaded
    //     $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$username', '$role', '$action')";
    //     mysqli_query($con, $insertSql);
    // }
    
                          setTimeout(function() {
                            location.reload();
                          }, 200);
        
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
            }, 200);
        </script>";
}

// Usage example
// $url = "http://localhost/ctv/bill-last5-print.php"; 
$url = "admin-groupBill-cancel.php"; // Replace with your desired URL
redirect($url);
?>
