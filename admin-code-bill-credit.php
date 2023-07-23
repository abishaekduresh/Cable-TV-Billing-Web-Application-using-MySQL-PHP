<?php
session_start();
require "dbconfig.php";
require "componenet.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected fruit value from the form
    $selectedValue = $_POST['selectedValue'];
    $bill_no = $_POST['bill_no'];
    $stbNo = $_POST['stbno'];
    
    // Perform any necessary validation or sanitization of the input data

    // Connect to the database (assuming you have a database connection established)

    // Update the table with the selected fruit value
    $updateQuery = "UPDATE bill SET pMode = '$selectedValue' WHERE bill_id = '$bill_no'";
    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Database update successful, perform any additional actions or display a success message
        echo "Database update successful. Bill Status updated to: " . $selectedValue; 

        if (isset($_SESSION['id'])) {
            // Get the user information before destroying the session
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "From Credit page Update Successful for $stbNo to - $selectedValue";
        
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
            $action = "From Credit page Failed Successful for $stbNo to - $selectedValue";
        
            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
        ?>
        <center><img src="assets/red-thumbs-down.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    }
} else {
    // Redirect the user to the form page if accessed directly without submitting the form
    header("Location: admin-bill-credit.php");
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
$url = "admin-bill-credit.php"; // Replace with your desired URL
redirect($url);
?>
