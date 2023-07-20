<?php 
   session_start();
   include "dbconfig.php";
   if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
 ?>


<?php include 'admin-menu-bar.php'; ?>



<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>