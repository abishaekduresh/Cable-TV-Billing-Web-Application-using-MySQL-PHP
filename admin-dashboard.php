<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
//   include 'component.php';
//   include 'component2.php';
      
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        $session_username = $_SESSION['username']; 
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
body
{
	background:#00bcd4;
}

h1
{
	color:#fff;
	margin:40px 0 60px 0;
	font-weight:300;
}

.our-team-main
{
	width:100%;
	height:auto;
	border-bottom:5px #323233 solid;
	background:#fff;
	text-align:center;
	border-radius:10px;
	overflow:hidden;
	position:relative;
	transition:0.5s;
	margin-bottom:28px;
}


.our-team-main img
{
	border-radius:50%;
	margin-bottom:20px;
	width: 90px;
}

.our-team-main h3
{
	font-size:20px;
	font-weight:700;
}

.our-team-main p
{
	margin-bottom:0;
}

.team-back
{
	width:100%;
	height:auto;
	position:absolute;
	top:0;
	left:0;
	padding:5px 15px 0 15px;
	text-align:left;
	background:#fff;
	
}

.team-front
{
	width:100%;
	height:auto;
	position:relative;
	z-index:10;
	background:#fff;
	padding:15px;
	bottom:0px;
	transition: all 0.5s ease;
}

.our-team-main:hover .team-front
{
	bottom:-200px;
	transition: all 0.5s ease;
}

.our-team-main:hover
{
	border-color:#777;
	transition:0.5s;
}

.dt-img-fluid {
    /*width: 10px;*/
    height: 60px;
}

/*our-team-main*/


</style>
</head>
<body >
    
    <?php include 'admin-menu-bar.php'?>
<br>
    <?php include 'admin-menu-btn.php'?>
    
<!--<div class="container mt-2" style="text-align: center;">-->
    <input type="hidden" id="date" class="form-control" style="display: inline-block; width: 230px;" value="<?= $currentDate ?>">
<!--</div>-->


<div class="container">
    <!-- Two-column layout -->
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-6">
            <div  id="dashboard-data" style="display: none;">
                <!--Today-->
                <h4 style="text-align: center;">Today Bill</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/bill.png" alt="Billing" class="dt-img-fluid" style="width: 60px; border: 0px;" />
                                <h3>Collection</h3>
                            </div>
                            <div class="team-back">
                                <div style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 20px;">
                                    ₹ <span id="todayColAmt">
                                    </span>
                                </div>
                                <p>Indiv &nbsp;&nbsp;: ₹ <span id="indivTodayColAmt"></span></p>
                                <p>Group : ₹ <span id="groupTodayColAmt"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/discount.png" alt="Discount" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Discount</h3>
                            </div>
                            <div class="team-back">
                                <div style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 20px;">
                                    ₹ <span id="todayDisAmt">
                                    </span>
                                </div>
                                <p>Indiv &nbsp;&nbsp;: ₹ <span id="indivTodayDisAmt"></span></p>
                                <p>Group : ₹ <span id="groupTodayDisAmt"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/profits.png" alt="Profits" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Profit</h3>
                            </div>
                            <div class="team-back" style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 60px;">
                                ₹ <span id="todayProfAmt">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 style="text-align: center;">Credit Due Bill Amt</h4>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/credit.png" alt="Credit" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Individual</h3>
                            </div>
                            <div class="team-back">
                                <div style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 20px;">
                                    ₹ <span id="indivTotCreditAmt">
                                    </span>
                                </div>
                                <p>Count &nbsp;&nbsp;: <span id="indivCreditBillCount"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/credit.png" alt="Credit" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Group</h3>
                            </div>
                            <div class="team-back">
                                <div style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 20px;">
                                    ₹ <span id="groupTotCreditAmt">
                                    </span>
                                </div>
                                <p>Count &nbsp;&nbsp;: <span id="groupCreditBillCount"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/smartphone.png" alt="SMS" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>SMS Credits</h3>
                            </div>
                            <div class="team-back" style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 40px;">
                                <span id="avlSmsCredit">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <h4 style="text-align: center;"> Current Month </h4>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/month.png" alt="Month" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Income</h3>
                            </div>
                            <div class="team-back" style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 40px;">
                                ₹ <span id="totIncomeAmt">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/expenses.png" alt="Expenses" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Expense</h3>
                            </div>
                            <div class="team-back" style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 40px;">
                                ₹ <span id="totExpenseAmt">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="our-team-main">
                            <div class="team-front">
                                <img src="icons/profits.png" alt="Profits" class="dt-img-fluid" style="width: 60px; padding: 0px;" />
                                <h3>Profit</h3>
                            </div>
                            <div class="team-back" style="font-size: 25px; font-weight: bold; text-align: center; padding-top: 40px;">
                                ₹ <span id="incomeExpenseProfit">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  <!--  dashboard-data div  -->

            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <button type="button" id="passcode_btn" class="btn btn-primary" onclick="checkPasscode()">
                      Enter Passcode
                    </button>
                </div>
            </div>
        

        </div>


        <!-- Right Column (empty) -->
        <!--<div class="col-lg-6">-->
            <!--<p>Testing</p>-->
            
    <!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
    <!--<script type="text/javascript">-->
    <!--  // Sample JSON data for Red Sox Attendance and Amount-->
    <!--  var jsonData = [-->
    <!--    {"date": "2022-01", "attendance": 35000, "amount": 4000},-->
    <!--    {"date": "2022-04", "attendance": 35000, "amount": 4000},-->
    <!--    {"date": "2023-04", "attendance": 36000, "amount": 2000},-->
    <!--    {"date": "2023-04", "attendance": 17000, "amount": 4050},-->
    <!--    {"date": "2023-05", "attendance": 38000, "amount": 1000},-->
    <!--    {"date": "2024-05", "attendance": 2500, "amount": 4000}-->
    <!--    // Add more data points as needed-->
    <!--  ];-->

    <!--  // Load the Google Charts library-->
    <!--  google.charts.load('current', {'packages':['corechart']});-->

    <!--  // Set a callback function to run when the Google Charts library is loaded-->
    <!--  google.charts.setOnLoadCallback(drawChart);-->

    <!--  function drawChart() {-->
    <!--    // Convert JSON data to DataTable format-->
    <!--    var data = new google.visualization.DataTable();-->
    <!--    data.addColumn('string', 'Date');-->
    <!--    data.addColumn('number', 'Attendance');-->
    <!--    data.addColumn('number', 'Amount');-->

    <!--    jsonData.forEach(function(row) {-->
    <!--      data.addRow([row.date, row.attendance, row.amount]);-->
    <!--    });-->

    <!--    // Define chart options-->
    <!--    var options = {-->
    <!--      title: 'Red Sox Attendance and Amount',-->
    <!--      curveType: 'function',-->
    <!--      legend: { position: 'bottom' },-->
    <!--      series: {-->
    <!--        0: { targetAxisIndex: 0 },-->
    <!--        1: { targetAxisIndex: 1 }-->
    <!--      },-->
    <!--      vAxes: {-->
    <!--        0: { title: 'Attendance' },-->
    <!--        1: { title: 'Amount' }-->
    <!--      }-->
    <!--    };-->

    <!--    // Create and draw the combination chart-->
    <!--    var chart = new google.visualization.LineChart(document.getElementById('attendance_chart'));-->
    <!--    chart.draw(data, options);-->
    <!--  }-->
    <!--</script>-->
    <!--<div id="attendance_chart" style="width: 900px; height: 500px;"></div>-->
    
    
<!--<!DOCTYPE html>-->
<!--<html>-->
<!--  <head>-->
<!--    <title>Red Sox Attendance and Amount Chart</title>-->
<!--    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
<!--    <script type="text/javascript">-->
<!--      // Sample JSON data for Red Sox Attendance and Amount-->
<!--      var jsonData = [-->
<!--        {"date": "2022-01", "attendance": 35000, "amount": 4000},-->
<!--        {"date": "2022-04", "attendance": 35000, "amount": 4000},-->
<!--        {"date": "2023-04", "attendance": 36000, "amount": 2000},-->
<!--        {"date": "2023-04", "attendance": 17000, "amount": 4050},-->
<!--        {"date": "2023-05", "attendance": 38000, "amount": 1000},-->
<!--        {"date": "2024-05", "attendance": 2500, "amount": 4000}-->
<!--        // Add more data points as needed-->
<!--      ];-->

<!--      // Load the Google Charts library-->
<!--      google.charts.load('current', {'packages':['corechart']});-->

<!--      // Set a callback function to run when the Google Charts library is loaded-->
<!--      google.charts.setOnLoadCallback(drawChart);-->

<!--      function drawChart() {-->
        <!--// Convert JSON data to DataTable format-->
<!--        var data = new google.visualization.DataTable();-->
<!--        data.addColumn('string', 'Date');-->
<!--        data.addColumn('number', 'Attendance');-->
<!--        data.addColumn('number', 'Amount');-->

<!--        jsonData.forEach(function(row) {-->
<!--          data.addRow([row.date, row.attendance, row.amount]);-->
<!--        });-->

        <!--// Define chart options-->
<!--        var options = {-->
<!--          title: 'Red Sox Attendance and Amount',-->
<!--          legend: { position: 'bottom' },-->
<!--          seriesType: 'bars',-->
<!--          series: {-->
<!--            0: { targetAxisIndex: 0 },-->
<!--            1: { targetAxisIndex: 1 }-->
<!--          },-->
<!--          vAxes: {-->
<!--            0: { title: 'Attendance' },-->
<!--            1: { title: 'Amount' }-->
<!--          },-->
<!--          bar: { groupWidth: '75%' }-->
<!--        };-->

        <!--// Create and draw the bar chart-->
<!--        var chart = new google.visualization.ComboChart(document.getElementById('attendance_chart'));-->
<!--        chart.draw(data, options);-->
<!--      }-->
<!--    </script>-->
<!--  </head>-->
<!--  <body>-->
    <!-- Div where the chart will be rendered -->
<!--    <div id="attendance_chart" style="width: 900px; height: 500px;"></div>-->
<!--  </body>-->
<!--</html>-->



            
        <!--</div>-->
    </div>
</div>


    
    
<!-- Bootstrap JS Bundle (including Popper) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    
<script type="text/javascript">

function checkPasscode() {
    var passcode = prompt("Please enter the passcode:");
    
    // Replace with your actual passcode validation logic
    if (passcode === "sarojaammal") {
        // Show the item
        document.getElementById("dashboard-data").style.display = "block";
        document.getElementById("passcode_btn").style.display = "none";
        
    } else {
        // Hide the item
        document.getElementById("dashboard-data").style.display = "none";
        alert("Incorrect passcode. Please try again.");
    }
}


$(document).ready(function(){
    
    function formatMoney(amount) {
        // Convert amount to number and round to two decimal places
        amount = parseFloat(amount).toFixed(2);
    
        // Separate the whole number part from the decimal part
        let parts = amount.toString().split('.');
        let wholeNumber = parts[0];
        let decimalPart = parts.length > 1 ? '.' + parts[1] : '';
    
        // Add commas for thousands separator
        wholeNumber = wholeNumber.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    
        // Combine whole number part with decimal part
        return wholeNumber + decimalPart;
    }
    
    var inputData = {
        date: $("#date").val().trim()
    };
    
    console.log(inputData);

    $.ajax({
        type: "POST",
        url: "api/v1/admin/dashboard.php",
        data: JSON.stringify(inputData),
        contentType: "application/json",
        success: function(response) {
            if (response.status === "success") {
                if (!response.data.indivTodayBillArray.length > 0) {
                    console.warn('indivTodayArray is empty.');
                }
                
                if (!response.data.groupTodayBillArray.length > 0) {
                    console.warn('groupTodayArray is empty.');
                }
                
                if (response.data.avlSmsCredit == 0) {
                    console.warn('avlSmsCredit is zero.');
                }

                if (response.data && !isNaN(response.data.avlSmsCredit)) {
                    if (response.data.avlSmsCredit < 1000) {
                        alert('Warning: SMS Credit is low (' + response.data.avlSmsCredit + ' available). Please refill soon.');
                    }
                } else {
                    console.error("Invalid SMS Credit value:", response.data.avlSmsCredit);
                }
                
                var indivTodayBillData = response.data.indivTodayBillArray[0];
                var groupTodayBillData = response.data.groupTodayBillArray[0];
                var indivCreditBillData = response.data.indivCreditBillArray[0];
                var groupCreditBillData = response.data.groupCreditBillArray[0];
                var incomeExpenseArrayData = response.data.incomeExpenseArray[0];
                
                var todayTotColAmt = Number(indivTodayBillData.indivTodayBillColAmt) + Number(groupTodayBillData.groupTodayBillColAmt);
                var todayTotDisAmt = Number(indivTodayBillData.indivTodayBillDisAmt) + Number(groupTodayBillData.groupTodayBillDisAmt);
                var incomeExpenseProfit = Number(incomeExpenseArrayData.totIncomeAmt) - Number(incomeExpenseArrayData.totExpenseAmt);
                
                var todayTotProfAmt = todayTotColAmt - todayTotDisAmt;
                
                $("#todayColAmt").html(formatMoney(todayTotColAmt));
                $("#todayDisAmt").html(formatMoney(todayTotDisAmt));
                $("#todayProfAmt").html(formatMoney(todayTotProfAmt));
                $("#indivTodayColAmt").html(formatMoney(indivTodayBillData.indivTodayBillColAmt));
                $("#groupTodayColAmt").html(formatMoney(groupTodayBillData.groupTodayBillColAmt));
                $("#indivTodayDisAmt").html(formatMoney(indivTodayBillData.indivTodayBillDisAmt));
                $("#groupTodayDisAmt").html(formatMoney(groupTodayBillData.groupTodayBillDisAmt));
                
                $("#indivTotCreditAmt").html(formatMoney(indivCreditBillData.indivCreditBillAmt));
                $("#indivCreditBillCount").html(indivCreditBillData.indivCreditBillCount);
                
                $("#groupTotCreditAmt").html(formatMoney(groupCreditBillData.groupCreditBillAmt));
                $("#groupCreditBillCount").html(groupCreditBillData.groupCreditBillCount);
                
                $("#totIncomeAmt").html(formatMoney(incomeExpenseArrayData.totIncomeAmt));
                $("#totExpenseAmt").html(formatMoney(incomeExpenseArrayData.totExpenseAmt));
                $("#incomeExpenseProfit").html(formatMoney(incomeExpenseProfit));
                
                $("#avlSmsCredit").html(response.data.avlSmsCredit);
                
            } else {
                console.warn('Response status is not success:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', status, error);
            alert('An error occurred: ' + error);
            $("#response").html(status);
            openModal();
        }
    });

    
});

</script>






    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php //include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>