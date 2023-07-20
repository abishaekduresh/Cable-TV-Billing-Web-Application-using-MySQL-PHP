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
session_start(); // Start the session
include "dbconfig.php";

if (isset($_SESSION['id'])) {
    // Get the user information before destroying the session
    $userId = $_SESSION['id'];
    $session_username = $_SESSION['username'];
    $role = $_SESSION['role'];
    $currentDate = $currentDate;
    $currentTime = $currentTime;
    $action = 'logged out';

    // Insert user logout activity
    $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$session_username', '$role', '$action')";
    mysqli_query($con, $insertSql);
}

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to a different page after session termination
header("Location: index.php");
exit();
?>

