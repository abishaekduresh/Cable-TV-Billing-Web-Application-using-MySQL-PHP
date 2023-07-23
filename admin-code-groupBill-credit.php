<?php
session_start();
require "dbconfig.php";
require "componenet.php";


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected fruit value from the form
    $selectedValue = $_POST['selectedValue'];
    $date = $_POST['date'];
    $groupID = $_POST['groupID'];
// $currentDate = $currentDate;
// $currentTime = $currentTime;
    // Perform any necessary validation or sanitization of the input data

    // Connect to the database (assuming you have a database connection established)

    // Update the table with the selected fruit value
    $updateQuery = "UPDATE billgroupdetails SET pMode = '$selectedValue' WHERE groupID = '$groupID' AND date='$date'";

    // $updateQuery = "UPDATE billgroup bg
    //             JOIN billgroupdetails bgd ON bg.groupID = bgd.groupID
    //             SET bg.status = '$selectedValue',
    //                 bgd.column_name = '$new_value'
    //             WHERE bg.groupID = '$groupID'";

    $updateResult = mysqli_query($con, $updateQuery);

    if ($updateResult) {
        // Database update successful, perform any additional actions or display a success message
        // echo "Database update successful. Bill Status updated to: " . $selectedValue; 
        
        if (isset($_SESSION['id'])) {
            // Get the user information before destroying the session
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "Group Credit Update Successful - $selectedValue";
        
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
            $action = "Group Credit Update Failed - $selectedValue";
        
            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }
        
        ?>
        <center><img src="assets/red-thumbs-down.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
        <?php
    }
} else {
    // Redirect the user to the form page if accessed directly without submitting the form
    header("Location: admin-groupBill-credit.php");
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
$url = "admin-groupBill-credit.php"; // Replace with your desired URL
redirect($url);
?>
