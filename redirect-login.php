
<?php 
// this code is for redirecting to different pages if the credentials are correct.
   session_start();
   include "dbconfig.php";
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
         //admin
      	if ($_SESSION['role'] == 'admin'){
			header("Location: admin-dashboard.php");
      	 }
		 //employee
		 else if ($_SESSION['role'] == 'employee'){ 
			header("Location: employee-dashboard.php");
      	} 
 }
else{
	header("Location: index.php");
} ?>
