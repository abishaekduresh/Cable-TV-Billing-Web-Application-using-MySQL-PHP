<?php
session_start();
require "dbconfig.php";
require "component.php";

if (isset($_GET['error'])) {
    $error = isset($_GET['error']) ? '?error=' . $_GET['error'] : '';
} else {
    $error = '?error=Logged out...';
}

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
header("Location: index.php" . $error);
exit();
?>

