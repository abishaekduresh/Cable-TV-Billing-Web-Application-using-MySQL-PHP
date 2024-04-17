<?php 
  //  session_start();
   include "dbconfig.php";
   if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
    // if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
        $session_username = $_SESSION['username']; 
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<center>
    <div class="container">
  <div class="row">
    <div class="col">
      <a href="billing-dashboard.php"><button type="button" class="btn btn-primary"><b>New Indiv Bill</b></button></a>
    </div>
    <div class="col">
      <a href="billing-group-dashboard.php?group_id=select"><button type="button" class="btn btn-primary"><b>New Group Bill</b>
      </button></a>
    </div>
    <div class="col">
      <a href="customer-history.php"><button type="button" class="btn btn-secondary"><b>Indiv Customer History</b></button></a>
    </div>
    <div class="col">
      <a href="income-expenses.php"><button type="button" class="btn btn-primary"><b>Add Income/Expense</b></button></a>
    </div>
    <div class="col">
      <a href="bill-filter-by-user.php"><button type="button" class="btn btn-success"><b>Your Bill</b></button></a>
    </div>
    <div class="col">
      <a href="bill-filter-by-all.php"><button type="button" class="btn btn-success"><b>Indiv Bill Report</b></button></a>
    </div>
    <div class="col">
      <a href="rptgroupbill.php"><button type="button" class="btn btn-success"><b>Group Bill Report</b></button></a>
    </div>
    <div class="col">
      <a href="IndivDuplicateBill.php" class="btn btn-info position-relative">
        <b>Indiv Duplicate Bill</b>
        <span class="badge bg-danger rounded-pill position-absolute top-0 start-100 translate-middle">
          New
        </span>
      </a>
    </div>

  </div>
</div>
</center>


    <br>
    <hr class="mt-0 mb-4">
    <!-- <br> -->
  
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php }else{
	header("Location: index.php");
} ?>