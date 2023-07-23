// <?php
// session_start(); // Start the session
// include "dbconfig.php";
// session_unset(); // Unset all session variables
// session_destroy(); // Destroy the session

// // Insert user login activity
// $userId = $row['id'];
// $userName = $row['username'];
// $role = $row['role'];
// $currentDate = $currentDate;
// $currentTime = $currentTime;
// $action = 'logged out';
// $insertSql = "INSERT INTO activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$userName', '$role', '$action')";
// mysqli_query($con, $insertSql);
                
// // Redirect to a different page after session termination
// header("Location: index.php");
// exit();
// ?>

<?php
session_start();
require "dbconfig.php";
require "componenet.php";

if (isset($_SESSION['id'])) {
    // Get the user information before destroying the session
    $userId = $_SESSION['id'];
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    $action = "Logged out";

    // Call the function to insert user activity log
    logUserActivity($userId, $username, $role, $action);
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to a different page after session termination
header("Location: index.php");
exit();
?>

