<?php
session_start();
require "../dbconfig.php";
require "../component.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    
    include 'pos-menu.php';

    // if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    //     include '../admin-menu-bar.php';
    //     ?><!--br--><?php
    //     include '../admin-menu-btn.php';
    //     $session_username = $_SESSION['username'];
        
    // } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    //     include '../menu-bar.php';
    //     $session_username = $_SESSION['username'];
    // }
?>

<html>
    <head>
        <style>
            body {
                background-color: #5e5e5c; /* Replace this with the color you want */
            }
        </style>
    </head>
    <body>

    </body>
</html>











<?php include '../footer.php'?>


<?php } else{
	header("Location: ../index.php");
} ?>

