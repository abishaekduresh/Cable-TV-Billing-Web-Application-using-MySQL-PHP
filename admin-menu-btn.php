<?php 
  //  session_start();
   include "dbconfig.php";
//    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
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


<center><h6>
    <div class="container">
        <!--<img src="https://research.mgu.ac.in/wp-content/uploads/2021/07/new-icon-gif-9.gif" width="150" height="100">-->
  <div class="row">
    <div class="col">
      <a href="billing-group-dashboard.php?group_id=select"><button type="button" class="btn btn-primary"><b>New Group Bill</b>
      </button></a>
    </div>
    <div class="col">
      <a href="pos-billing.php"><button type="button" class="btn btn-primary position-relative">
		  <b>POS Billing</b>
          <!--span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            New
            <span class="visually-hidden">unread messages</span>
          </span-->
        </button>
      </a>
    </div>
    <!--<div class="col">-->
    <!--  <a href="customer-history.php"><button type="button" class="btn btn-secondary"><b>Group History</b></button></a>-->
    <!--</div>-->
    <!-- <div class="col">
      <a href="income-expenses.php"><button type="button" class="btn btn-primary"><b>Add Income/Expense</b></button></a>
    </div> -->
    <div class="col">
      <a href="rptgroupbill.php"><button type="button" class="btn btn-success"><b>Group Bill Report</b></button></a>
    </div>
    <div class="col">
      <a href="admin-groupBill-cancel.php"><button type="button" class="btn btn-danger"><b>Bill Group Cancel</b></button></a>
    </div>
    <div class="col">
      <a href="admin-groupBill-credit.php"><button type="button" class="btn btn-warning"><b>Credit Bill Group</b></button></a>
    </div>
    <div class="col">
      <a href="groupaction.php"><button type="button" class="btn btn-info"><b>Add | Edit Group</b></button></a>
    </div>
    <div class="col">
      <a href="loc/dashboard.php?page=new-bill"><button type="button" class="btn btn-warning"><b>LOC</b></button></a>
    </div>
    <div class="col">
      <a href="IndivDuplicateBill.php" class="btn btn-info position-relative">
        <b>Indiv Duplicate Bill</b>
      </a>
    </div>

    <!-- <div class="col">
      <a href="options-add.php"><button type="button" class="btn btn-secondary"><b>Add Options</b></button></a>
    </div> -->
  </div>
</div>
</center>

<br>

<center>
    <div class="container">
  <div class="row">
    <div class="col">
      <a href="billing-dashboard.php"><button type="button" class="btn btn-primary"><b>New Bill</b></button></a>
    </div>
    <div class="col">
      <a href="customer-history.php"><button type="button" class="btn btn-secondary"><b>Customer History</b></button></a>
    </div>
    <div class="col">
      <a href="income-expenses.php"><button type="button" class="btn btn-primary"><b>Add Income/Expense</b></button></a>
    </div>
    <div class="col">
      <a href="admin-bill-filter-by-all.php"><button type="button" class="btn btn-success"><b>Bill Report</b></button></a>
    </div>
    <div class="col">
      <a href="todaycollection.php"><button type="button" class="btn btn-success"><b>Today Bill Collection</b></button></a>
    </div>
    <div class="col">
      <a href="admin-bill-cancel.php"><button type="button" class="btn btn-danger"><b>Cancel Bill</b></button></a>
    </div>
    <div class="col">
      <a href="admin-bill-credit.php"><button type="button" class="btn btn-warning"><b>Credit Bill</b></button></a>
    </div>
    <div class="col">
      <a href="customer-details.php"><button type="button" class="btn btn-info"><b>Add | Edit Customer</b></button></a>
    </div>
    <!--<div class="col">-->
    <!--  <a href="options-add.php"><button type="button" class="btn btn-secondary"><b>Add Options</b></button></a>-->
    <!--</div>-->
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