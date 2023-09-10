<?php 
   session_start();
   include "dbconfig.php";
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    

    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
        
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        ?><br><?php
        include 'sub-menu-btn.php';
        $session_username = $_SESSION['username'];
    }

?>
            

<?php

// Initialize variables with default values or null
$sumBillAmt = 0;
$sumDiscount = 0;
$sumRs = 0;




$sumQuery ="SELECT
  (SELECT SUM(paid_amount) FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username') AS sumBillAmt,
  (SELECT SUM(discount) FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username') AS sumDiscount,
  (SELECT SUM(Rs) FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username') AS sumRs";

$result = mysqli_query($con, $sumQuery);

// Check if the query executed successfully
if ($result) {
    // Fetch the result row
    $row = mysqli_fetch_assoc($result);

    // Assign the sum value to the variables if available
    if ($row) {
        $sumBillAmt = $row['sumBillAmt'];
        $sumDiscount = $row['sumDiscount'];
        $sumRs = $row['sumRs'];
    }

    // Use the sum values as needed
    // echo "The sum of the column is: " . $sumBillAmt;
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

$todayCount = 0;
$todayCancel = 0;
$totalCashCount = 0;
$totalOnlineCount = 0;

$countQuery="SELECT 
          COUNT(CASE WHEN status = 'approve' AND date = '$currentDate' AND bill_by = '$session_username' THEN 1 END) AS todayCount,
          COUNT(CASE WHEN status = 'cancel' AND date = '$currentDate' AND bill_by = '$session_username' THEN 1 END) AS todayCancel,
          COUNT(CASE WHEN pMode = 'cash' AND date = '$currentDate' AND status = 'approve' AND bill_by = '$session_username' THEN 1 END) AS totalCashCount,
          COUNT(CASE WHEN pMode = 'gpay' AND  date = '$currentDate' AND status = 'approve' AND bill_by = '$session_username' THEN 1 END) AS totalOnlineCount
            FROM bill";

$countresult = mysqli_query($con, $countQuery);

// Check if the query executed successfully
if ($countresult) {
    // Fetch the result row
    $row = mysqli_fetch_assoc($countresult);

    // Assign the sum value to the variables if available
    if ($row) {
        $todayCount = $row['todayCount'];
        $todayCancel = $row['todayCancel'];
        $totalCashCount = $row['totalCashCount'];
        $totalOnlineCount = $row['totalOnlineCount'];
    }

    // Use the sum values as needed
    // echo "The sum of the column is: " . $sumBillAmt;
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

$totalCreditCount = 0;
$totalCreditRsSum = 0;

$sql ="SELECT 
  COUNT(CASE WHEN pMode = 'credit' AND status = 'approve' THEN 1 END) AS totalCreditCount,
  (SELECT SUM(Rs) FROM bill WHERE pMode = 'credit' AND  date = '$currentDate') AS totalCreditRsSum
FROM bill";

$result2 = mysqli_query($con, $sql);

// Check if the query executed successfully
if ($result2) {
    // Fetch the result row
    $row = mysqli_fetch_assoc($result2);

    // Assign the sum value to the variables if available
    if ($row) {
        $totalCreditCount = $row['totalCreditCount'];
        $totalCreditRsSum = $row['totalCreditRsSum'];
    }

    // Use the sum values as needed
    // echo "The sum of the column is: " . $sumBillAmt;
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
 <style>
.img-account-profile {
    height: 10rem;
}
.rounded-circle {
    border-radius: 50% !important;
}
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgb(33 40 50 / 15%);
}
.card .card-header {
    font-weight: 500;
}
.card-header:first-child {
    border-radius: 0.35rem 0.35rem 0 0;
}
.card-header {
    padding: 1rem 1.35rem;
    margin-bottom: 0;
    background-color: rgba(33, 40, 50, 0.03);
    border-bottom: 1px solid rgba(33, 40, 50, 0.125);
}
.form-control, .dataTable-input {
    display: block;
    width: 100%;
    padding: 0.875rem 1.125rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1;
    color: #69707a;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #c5ccd6;
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-radius: 0.35rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.nav-borders .nav-link.active {
    color: #0061f2;
    border-bottom-color: #0061f2;
}
.nav-borders .nav-link {
    color: #69707a;
    border-bottom-width: 0.125rem;
    border-bottom-style: solid;
    border-bottom-color: transparent;
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
    padding-left: 0;
    padding-right: 0;
    margin-left: 1rem;
    margin-right: 1rem;
}
.fa-2x {
    font-size: 2em;
}

.table-billing-history th, .table-billing-history td {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
    padding-left: 1.375rem;
    padding-right: 1.375rem;
}
.table > :not(caption) > * > *, .dataTable-table > :not(caption) > * > * {
    padding: 0.75rem 0.75rem;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
}

.border-start-primary {
    border-left-color: #0061f2 !important;
}
.border-start-secondary {
    border-left-color: #6900c7 !important;
}
.border-start-success {
    border-left-color: #00ac69 !important;
}
.border-start-lg {
    border-left-width: 0.25rem !important;
}
.h-100 {
    height: 100% !important;
}

</style>

</head>
<body >

        
      
<div class="container-xl px-4 mt-4">
    <!--<hr class="mt-0 mb-4">-->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-10 border-start-lg border-start-primary">
                <div class="card-body">
                    <div class="small text-muted">Today Bill Amount</div>
                    <!--<div class="h3">₹ <span id="password"><?php echo $sumBillAmt; ?></span>&nbsp;<i class="bi bi-eye-slash" id="togglePassword"></i></div>-->
                    <div class="h3">₹
                      <span id="TodayBillAmount" data-value="<?php echo $sumBillAmt; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayBillAmount"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 2-->
            <div class="card h-100 border-start-lg border-start-secondary">
                <div class="card-body">
                    <div class="small text-muted">Today Discount</div>
                    <!--<div class="h3" id="password">₹ <?php echo $sumDiscount?></div>-->
                    <div class="h3">₹
                      <span id="TodayDiscount" data-value="<?php echo $sumDiscount; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayDiscount"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 3-->
            <div class="card h-100 border-start-lg border-start-success">
                <div class="card-body">
                    <div class="small text-muted">Today Collection (Rs)</div>
                    <!--<div class="h3 d-flex align-items-center">₹ <?php echo $sumRs?></div>-->
                    <div class="h3">₹
                      <span id="TodayCollection" data-value="<?php echo $sumRs; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayCollection"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--</div>   -->
    
    
<!--<div class="container-xl px-4 mt-4">-->
    <!--<hr class="mt-0 mb-4">-->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-100 border-start-lg border-start-primary">
                <div class="card-body">
                    <div class="small text-muted">Today Bill Count</div>
                    <!--<div class="h3">₹ <?php echo $todayCount?></div>-->
                    <div class="h3">
                      <span id="TodayBillCount" data-value="<?php echo $todayCount; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayBillCount"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 2-->
            <div class="card h-100 border-start-lg border-start-secondary">
                <div class="card-body">
                    <div class="small text-muted">Today Cancel Bill</div>
                    <!--<div class="h3">₹ <?php echo $todayCancel?></div>-->
                    <div class="h3">
                      <span id="TodayCancelBill" data-value="<?php echo $todayCancel; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayCancelBill"></i>
                    </div>
                </div>
            </div>
        </div>
            <!--<div class="col-lg-4 mb-4">-->
            <!--    <div class="card h-100 border-start-lg border-start-success">-->
            <!--         <div class="card-body">-->
            <!--            <div class="small text-muted">Overall Crdit Bill Pending</div>-->
            <!--            <div class="h3 d-flex align-items-center"> <?php echo $totalCreditCount?>   -- ₹ <?php echo $totalCreditRsSum?></div>-->
                        <!--<div class="h3">₹-->
                        <!--  <span id="TodayCollection" data-value="<?php echo $sumRs; ?>">****</span>-->
                        <!--  <i class="bi bi-eye-slash" id="toggleTodayCollection"></i>-->
                        <!--</div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->
    <!--</div>-->
    <!--<hr class="my-4">-->
</div>  

<!--<div class="container-xl px-4 mt-4">-->
    <!--<hr class="mt-0 mb-4">-->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-10 border-start-lg border-start-primary">
                <div class="card-body">
                    <div class="small text-muted">Today Cash Bill</div>
                    <div class="h3">
                      <span id="TodayCashCount" data-value="<?php echo $totalCashCount; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayCashCount"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 2-->
            <div class="card h-100 border-start-lg border-start-secondary">
                <div class="card-body">
                    <div class="small text-muted">Today GPay Bill</div>
                    <!--<div class="h3">₹ <span id="password"><?php echo $sumBillAmt; ?></span>&nbsp;<i class="bi bi-eye-slash" id="togglePassword"></i></div>-->
                    <div class="h3">
                      <span id="TodayGpay" data-value="<?php echo $totalOnlineCount; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayGpay"></i>
                    </div>
                </div>
            </div>
        </div>
    


<!---------------------------------Visibility toggle------------>
<script>
  const toggleTodayBillAmount = document.querySelector("#toggleTodayBillAmount");
  const todayBillAmount = document.querySelector("#TodayBillAmount");

  toggleTodayBillAmount.addEventListener("click", function() {
    // toggle the visibility of the password value
    const isTodayBillAmountVisible = todayBillAmount.classList.toggle("TodayBillAmount-visible");
    todayBillAmount.textContent = isTodayBillAmountVisible ? todayBillAmount.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });

  // Additional toggle operation for Today Discount
  const toggleTodayDiscount = document.querySelector("#toggleTodayDiscount");
  const todayDiscount = document.querySelector("#TodayDiscount");

  toggleTodayDiscount.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayDiscountVisible = todayDiscount.classList.toggle("TodayDiscount-visible");
    todayDiscount.textContent = isTodayDiscountVisible ? todayDiscount.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
 
  // Additional toggle operation for Today Discount
  const toggleTodayCollection = document.querySelector("#toggleTodayCollection");
  const TodayCollection = document.querySelector("#TodayCollection");

  toggleTodayCollection.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayCollectionVisible = TodayCollection.classList.toggle("TodayCollection-visible");
    TodayCollection.textContent = isTodayCollectionVisible ? TodayCollection.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
  
   // Additional toggle operation for Today Discount
  const toggleTodayBillCount = document.querySelector("#toggleTodayBillCount");
  const TodayBillCount = document.querySelector("#TodayBillCount");

  toggleTodayBillCount.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayBillCountVisible = TodayBillCount.classList.toggle("TodayBillCount-visible");
    TodayBillCount.textContent = isTodayBillCountVisible ? TodayBillCount.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
  
  // Additional toggle operation for Today Discount
  const toggleTodayCancelBill = document.querySelector("#toggleTodayCancelBill");
  const TodayCancelBill = document.querySelector("#TodayCancelBill");

  toggleTodayCancelBill.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayCancelBillVisible = TodayCancelBill.classList.toggle("TodayCancelBill-visible");
    TodayCancelBill.textContent = isTodayCancelBillVisible ? TodayCancelBill.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
  
  // Additional toggle operation for Today Discount
  const toggleTodayCashCount = document.querySelector("#toggleTodayCashCount");
  const TodayCashCount = document.querySelector("#TodayCashCount");

  toggleTodayCashCount.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayCashCountVisible = TodayCashCount.classList.toggle("TodayCashCount-visible");
    TodayCashCount.textContent = isTodayCashCountVisible ? TodayCashCount.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
  

   // Additional toggle operation for Today Discount
  const toggleTodayGpay = document.querySelector("#toggleTodayGpay");
  const TodayGpay = document.querySelector("#TodayGpay");

  toggleTodayGpay.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayGpayVisible = TodayGpay.classList.toggle("TodayGpay-visible");
    TodayGpay.textContent = isTodayGpayVisible ? TodayGpay.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
  
  
 </script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php //include 'footer.php'?>

<?php }else{
	header("Location: index.php");
} ?>

