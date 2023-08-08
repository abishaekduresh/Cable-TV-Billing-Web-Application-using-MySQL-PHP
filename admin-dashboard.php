<?php 
   session_start();
   include "dbconfig.php";   
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        $session_username = $_SESSION['username']; 
        ?>
<?php

// Initialize variables with default values or null
$sumBillAmt = 0;
$sumDiscount = 0;
$sumRs = 0;




$sumQuery ="SELECT
  (SELECT SUM(paid_amount) FROM bill WHERE date = '$currentDate' AND status = 'approve') AS sumBillAmt,
  (SELECT SUM(discount) FROM bill WHERE date = '$currentDate' AND status = 'approve') AS sumDiscount,
  (SELECT SUM(Rs) FROM bill WHERE date = '$currentDate' AND status = 'approve') AS sumRs";

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
?>

<?php

// Initialize variables with default values or null
$sumIncome = 0;
$sumExpense = 0;
$profit = 0;

$sumIncomeExpense ="SELECT
  (SELECT SUM(amount) FROM in_ex WHERE type = 'Income' AND MONTH(date)='$currentMonth') AS sumIncome,
  (SELECT SUM(amount) FROM in_ex WHERE type = 'Expense' AND MONTH(date)='$currentMonth') AS sumExpense";

$resultsumIncomeExpense = mysqli_query($con, $sumIncomeExpense);

// Check if the query executed successfully
if ($resultsumIncomeExpense) {
    // Fetch the result rowToday Collection (Rs)
    $row = mysqli_fetch_assoc($resultsumIncomeExpense);

    // Assign the sum value to the variables if available
    if ($row) {
        $sumIncome = $row['sumIncome'];
        $sumExpense = $row['sumExpense'];
    }

    // Calculate the profit
    // echo $sumIncome;
    // echo $sumExpense;
    // echo $profit = $sumIncome - $sumExpense;
    
    
    // Use the sum values as needed
    // echo "The sum of the column is: " . $sumBillAmt;
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}
?>

<?php

// Initialize variables with default values or null
$sumMonthBillAmt = 0;
$sumMonthDiscount = 0;
$sumMonthRs = 0;


$sumMonth ="SELECT
  (SELECT SUM(paid_amount) FROM bill WHERE MONTH(date) = '$currentMonth' AND status = 'approve') AS sumMonthBillAmt,
  (SELECT SUM(discount) FROM bill WHERE MONTH(date) = '$currentMonth' AND status = 'approve') AS sumMonthDiscount,
  (SELECT SUM(Rs) FROM bill WHERE MONTH(date) = '$currentMonth' AND status = 'approve') AS sumMonthRs";



$resultMonth = mysqli_query($con, $sumMonth);

// Check if the query executed successfully
if ($resultMonth) {
    // Fetch the result row
    $row = mysqli_fetch_assoc($resultMonth);

    // Assign the sum value to the variables if available
    if ($row) {
        $sumMonthBillAmt = $row['sumMonthBillAmt'];
        $sumMonthDiscount = $row['sumMonthDiscount'];
        $sumMonthRs = $row['sumMonthRs'];
    }

$formattedSumMonthBillAmt = number_format($sumMonthBillAmt, 0, ',', ',');
$formattedSumMonthDiscount = number_format($sumMonthDiscount, 0, ',', ',');
$formattedSumMonthRs = number_format($sumMonthRs, 0, ',', ',');

    // Use the sum values as needed
    // echo "The sum of the column is: " . $sumBillAmt;
} else {
    // Query execution failed
    echo "Error executing the query: " . mysqli_error($con);
}


// Close the database connection
// mysqli_close($con);
?>
<?php
$todayCount = 0;
$todayCancel = 0;
$totalCashCount = 0;
$totalOnlineCount = 0;

$countQuery="SELECT 
          COUNT(CASE WHEN date = '$currentDate' THEN 1 END) AS todayCount,
          COUNT(CASE WHEN status = 'cancel' AND date = '$currentDate' THEN 1 END) AS todayCancel,
          COUNT(CASE WHEN pMode = 'cash' AND status = 'approve' AND date = '$currentDate' THEN 1 END) AS totalCashCount,
          COUNT(CASE WHEN pMode = 'gpay' AND status = 'approve' AND date = '$currentDate' THEN 1 END) AS totalOnlineCount
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

?>

<?php 

$totalCreditCount = 0;
$totalCreditRsSum = 0;

$sql ="SELECT 
  COUNT(CASE WHEN pMode = 'credit' AND status = 'approve' THEN 1 END) AS totalCreditCount,
  (SELECT SUM(Rs) FROM bill WHERE pMode = 'credit') AS totalCreditRsSum
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
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{margin-top:20px;
background-color:#f2f6fc;
color:#69707a;
}
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
    
    <?php include 'admin-menu-bar.php'?>
<br>
    <?php include 'admin-menu-btn.php'?>

<div class="container-xl px-4 mt-4">
    
    <!--<hr class="mt-0 mb-4">-->
 
     <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-10 border-start-lg border-start-primary">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Current Month Bill Amount</div>
                    <div class="h3">₹
                      <span id="CurrentMonthBillAmount" data-value="<?php echo $formattedSumMonthBillAmt; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleCurrentMonthBillAmount"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 2-->
            <div class="card h-100 border-start-lg border-start-secondary">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Current Month Discount</div>
                    <div class="h3">₹
                      <span id="CurrentMonthDiscount" data-value="<?php echo $formattedSumMonthDiscount; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleCurrentMonthDiscount"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 3-->
            <div class="card h-100 border-start-lg border-start-success">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Current Month Collection (Rs)</div>
                    <div class="h3">₹
                      <span id="CurrentMonthCollection" data-value="<?php echo $formattedSumMonthRs; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleCurrentMonthCollection"></i>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    
    <hr class="mt-0 mb-4">
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-10 border-start-lg border-start-primary">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Current Month Income</div>
                    <div class="h3">₹
                      <span id="CurrentMonthIncome" data-value="<?php $income = $sumIncome+$sumMonthRs; echo $income ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleCurrentMonthIncome"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 2-->
            <div class="card h-100 border-start-lg border-start-secondary">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Current Month Expense</div>
                    <div class="h3">₹
                      <span id="CurrentMonthExpense" data-value="<?php echo $sumExpense; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleCurrentMonthExpense"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <!-- Billing card 2-->
            <div class="card h-100 border-start-lg border-start-success">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Current Month Profit</div>
                    <div class="h3">₹
                      <span id="CurrentMonthProfit" data-value="<?php $profit = $income - $sumExpense; echo $profit; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleCurrentMonthProfit"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--<hr class="mt-0 mb-4">-->
    
    <hr class="mt-0 mb-4">
    
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-10 border-start-lg border-start-primary">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Today Bill Amount</div>
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
                    <div style="font-weight: bold;" class="small text-muted">Today Discount</div>
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
                    <div style="font-weight: bold;" class="small text-muted">Today Collection (Rs)</div>
                    <!--<div class="h3 d-flex align-items-center">₹ <?php echo $sumRs?></div>-->
                    <div class="h3">₹
                      <span id="TodayCollection" data-value="<?php echo $sumRs; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayCollection"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--<hr class="mt-0 mb-4">-->
<!--</div>   -->
    
    
<!--<div class="container-xl px-4 mt-4">-->
    <!--<hr class="mt-0 mb-4">-->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <!-- Billing card 1-->
            <div class="card h-100 border-start-lg border-start-primary">
                <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Today Bill Count</div>
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
                    <div style="font-weight: bold;" class="small text-muted">Today Cancel Bill</div>
                    <!--<div class="h3">₹ <?php echo $todayCancel?></div>-->
                    <div class="h3">
                      <span id="TodayCancelBill" data-value="<?php echo $todayCancel; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayCancelBill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 border-start-lg border-start-success">
                 <div class="card-body">
                    <div style="font-weight: bold;" class="small text-muted">Overall Crdit Bill Pending</div>
                    <div class="h3 d-flex align-items-center"> <?php echo $totalCreditCount?>   -- ₹ <?php echo $totalCreditRsSum?></div>
                    <!--<div class="h3">₹-->
                    <!--  <span id="TodayCollection" data-value="<?php echo $sumRs; ?>">****</span>-->
                    <!--  <i class="bi bi-eye-slash" id="toggleTodayCollection"></i>-->
                    <!--</div>-->
                </div>
            </div>
        </div>
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
                    <div style="font-weight: bold;" class="small text-muted">Today Cash Bill</div>
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
                    <div style="font-weight: bold;" class="small text-muted">Today GPay Bill</div>
                    <!--<div class="h3">₹ <span id="password"><?php echo $sumBillAmt; ?></span>&nbsp;<i class="bi bi-eye-slash" id="togglePassword"></i></div>-->
                    <div class="h3">
                      <span id="TodayGpay" data-value="<?php echo $totalOnlineCount; ?>">****</span>
                      <i class="bi bi-eye-slash" id="toggleTodayGpay"></i>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="col-lg-4 mb-4">-->
            <!-- Billing card 3-->
        <!--    <div class="card h-100 border-start-lg border-start-success">-->
        <!--        <div class="card-body">-->
        <!--            <div style="font-weight: bold;" class="small text-muted">Today Income</div>-->
                    <!--<div class="h3" id="password">₹ <?php echo $sumBillAmt?></div>-->
        <!--            <div class="h3">₹-->
        <!--              <span id="TodayIncome" data-value="<?php echo $sumRs; ?>">****</span>-->
        <!--              <i class="bi bi-eye-slash" id="toggleTodayIncome"></i>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    </div>
</div>   
    
 

                
<!--<div class="container">-->
<!--  <div class="row">-->
<!--    <div class="col-6" id="piechart4" style="width: 900px; height: 500px;"></div>-->
    <div class="col-6" id="piechart2" style="width: 900px; height: 500px;"></div>
    
    
    <!--<div id="columnchart_material" style="width: 800px; height: 500px;"></div>-->
    
<!--    <div class="col-6" id="piechart" style="width: 900px; height: 500px;"></div>-->
<!--    <div class="col-6" id="piechart3" style="width: 900px; height: 500px;"></div>-->
    
<!--  </div>-->
<!--</div>-->

    
    
    
    
    
<!----------------------------------------Google Pie Charts 3-------------------------------------------------------->

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['bill_by', 'count'],
         <?php
        //  $sql = "SELECT bill_by, COUNT(*) AS count FROM bill GROUP BY bill_by";
        $sql = "SELECT bill_by, COUNT(*) AS count FROM bill WHERE status = 'approve' AND date = CURDATE() - INTERVAL 1 DAY GROUP BY bill_by";
         $fire = mysqli_query($con,$sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo"['".$result['bill_by']."',".$result['count']."],";
          }

         ?>
        ]);

        var options = {
          title: "Yeserday Collection ' approved bills '"
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart3'));

        chart.draw(data, options);
      }
    </script>
    
    <!----------------------------------------Google Pie Charts 2-------------------------------------------------------->
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['bill_by', 'count'],
         <?php

         $sql = "SELECT bill_by, COUNT(*) AS count FROM bill WHERE status = 'approve' AND date = '$Date' GROUP BY bill_by";

         $fire = mysqli_query($con,$sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo"['".$result['bill_by']."',".$result['count']."],";
          }

         ?>
        ]);

        var options = {
          title: "Today Collection ' approved bills '"
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart2'));

        chart.draw(data, options);
      }
    </script>
    
    
    <!--      BAR Chart         -->




    
    <!----------------------------------------Google Pie Charts -------------------------------------------------------->

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['bill_by', 'count'],
         <?php
        //  $sql = "SELECT bill_by, COUNT(*) AS count FROM bill GROUP BY bill_by";
        $sql = "SELECT bill_by, COUNT(*) AS count FROM bill WHERE status = 'cancel' AND date = CURDATE() - INTERVAL 1 DAY GROUP BY bill_by";
         $fire = mysqli_query($con,$sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo"['".$result['bill_by']."',".$result['count']."],";
          }

         ?>
        ]);

        var options = {
          title: "Yeserday Collection ' cancel bills '"
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
      }
    </script>
    
        <!----------------------------------------Google Pie Charts 4 Cancel Bills-------------------------------------------------------->
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {

        var data = google.visualization.arrayToDataTable([
          ['bill_by', 'count'],
         <?php
            
         $sql = "SELECT bill_by, COUNT(*) AS count FROM bill WHERE status = 'cancel' AND date = '$Date' GROUP BY bill_by";

         $fire = mysqli_query($con,$sql);
          while ($result = mysqli_fetch_assoc($fire)) {
            echo"['".$result['bill_by']."',".$result['count']."],";
          }

         ?>
        ]);

        var options = {
          title: "Today Collection ' cancel bills '"
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart4'));

        chart.draw(data, options);
      }
    </script>
    
    <!-------------------------------------Bar Charts--------------------->
<?php

// Retrieve data from MySQL
$sql = "SELECT bill_by, COUNT(*) AS count FROM bill GROUP BY bill_by";
$result = mysqli_query($con, $sql);

// Prepare the data array
$data = [['bill_by', 'count']];
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = [$row['bill_by'], (int)$row['count']];
}

mysqli_close($con);
?>

<script type="text/javascript">
  google.charts.load('', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawVisualization);

  function drawVisualization() {
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

    var options = {
      title: 'Count of Bills Today',
      hAxis: {title: 'Count'},
      vAxis: {title: 'Bill By'},
      chartArea: {width: '50%'},
      bars: 'horizontal'
    };

    var chart = new google.visualization.BarChart(document.getElementById('ComboChart'));
    chart.draw(data, options);
  }
</script>

<!---------------------------------Visibility toggle------------>
<script>

////////////////////// Current Month   //////////////////
    const toggleCurrentMonthBillAmount = document.querySelector("#toggleCurrentMonthBillAmount");
    const currentMonthBillAmount = document.querySelector("#CurrentMonthBillAmount");
    
    toggleCurrentMonthBillAmount.addEventListener("click", function() {
      // toggle the visibility of the password value
      const isCurrentMonthBillAmountVisible = currentMonthBillAmount.classList.toggle("CurrentMonthBillAmount-visible");
      currentMonthBillAmount.textContent = isCurrentMonthBillAmountVisible ? currentMonthBillAmount.getAttribute("data-value") : "****";
    
      // toggle the icon
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });

    const toggleCurrentMonthDiscount = document.querySelector("#toggleCurrentMonthDiscount");
    const currentMonthDiscount = document.querySelector("#CurrentMonthDiscount");
    
    toggleCurrentMonthDiscount.addEventListener("click", function() {
      // toggle the visibility of the Current Month Discount value
      const isCurrentMonthDiscountVisible = currentMonthDiscount.classList.toggle("CurrentMonthDiscount-visible");
      currentMonthDiscount.textContent = isCurrentMonthDiscountVisible ? currentMonthDiscount.getAttribute("data-value") : "****";
    
      // toggle the icon
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });
  
    const toggleCurrentMonthCollection = document.querySelector("#toggleCurrentMonthCollection");
    const currentMonthCollection = document.querySelector("#CurrentMonthCollection");
    
    toggleCurrentMonthCollection.addEventListener("click", function() {
      // toggle the visibility of the Current Month Collection value
      const isCurrentMonthCollectionVisible = currentMonthCollection.classList.toggle("CurrentMonthCollection-visible");
      currentMonthCollection.textContent = isCurrentMonthCollectionVisible ? currentMonthCollection.getAttribute("data-value") : "****";
    
      // toggle the icon
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });

////////////////  Income Expense  //////////

    const toggleCurrentMonthIncome = document.querySelector("#toggleCurrentMonthIncome");
    const currentMonthIncome = document.querySelector("#CurrentMonthIncome");
    
    toggleCurrentMonthIncome.addEventListener("click", function() {
      // toggle the visibility of the password value
      const isCurrentMonthIncomeVisible = currentMonthIncome.classList.toggle("CurrentMonthIncome-visible");
      currentMonthIncome.textContent = isCurrentMonthIncomeVisible ? currentMonthIncome.getAttribute("data-value") : "****";
    
      // toggle the icon
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });
    
    const toggleCurrentMonthExpense = document.querySelector("#toggleCurrentMonthExpense");
    const currentMonthExpense = document.querySelector("#CurrentMonthExpense");
    
    toggleCurrentMonthExpense.addEventListener("click", function() {
      // toggle the visibility of the password value
      const isCurrentMonthExpenseVisible = currentMonthExpense.classList.toggle("CurrentMonthExpense-visible");
      currentMonthExpense.textContent = isCurrentMonthExpenseVisible ? currentMonthExpense.getAttribute("data-value") : "****";
    
      // toggle the icon
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });
    
    const toggleCurrentMonthProfit = document.querySelector("#toggleCurrentMonthProfit");
    const currentMonthProfit = document.querySelector("#CurrentMonthProfit");
    
    toggleCurrentMonthProfit.addEventListener("click", function() {
      // toggle the visibility of the password value
      const isCurrentMonthProfitVisible = currentMonthProfit.classList.toggle("CurrentMonthProfit-visible");
      currentMonthProfit.textContent = isCurrentMonthProfitVisible ? currentMonthProfit.getAttribute("data-value") : "****";
    
      // toggle the icon
      this.classList.toggle("bi-eye");
      this.classList.toggle("bi-eye-slash");
    });
    
/////////////////// Today  ////////////////
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
  
  
  // Additional toggle operation for Today Discount
  const toggleTodayIncome = document.querySelector("#toggleTodayIncome");
  const TodayIncome = document.querySelector("#TodayIncome");

  toggleTodayIncome.addEventListener("click", function() {
    // toggle the visibility of the Today Discount value
    const isTodayIncomeVisible = TodayIncome.classList.toggle("TodayIncome-visible");
    TodayIncome.textContent = isTodayIncomeVisible ? TodayIncome.getAttribute("data-value") : "****";

    // toggle the icon
    this.classList.toggle("bi-eye");
    this.classList.toggle("bi-eye-slash");
  });
</script>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>