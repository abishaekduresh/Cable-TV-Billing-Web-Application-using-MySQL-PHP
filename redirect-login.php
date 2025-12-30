<?php
session_start();

// Enable full error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "dbconfig.php";

// Check if session variables are set
if (isset($_SESSION['username']) && isset($_SESSION['id'])) {

    // Redirect based on user role
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin-dashboard.php");
        exit;
    } elseif ($_SESSION['role'] === 'employee') {
        header("Location: employee-dashboard.php");
        exit;
    } else {
        echo "Unknown role: " . htmlspecialchars($_SESSION['role']);
        exit;
    }

} else {
    // Not logged in
    header("Location: index.php");
    exit;
}
