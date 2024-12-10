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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
<style>
    /* SweetAlert2 Styling */
    .swal2-popup {
        font-family: 'Arial', sans-serif;
        font-size: 1rem;
        padding: 0;
        background: #f4f7fc;
        border-radius: 20px;
        max-width: 600px;
    }

    .swal2-title {
        font-size: 1.8rem;
        font-weight: bold;
        color: #1e293b;
        margin-top: 20px;
    }

    .swal2-html-container {
        padding: 20px;
    }

    .modern-card {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        background: #ffffff;
        border-radius: 15px;
        padding: 20px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .modern-card .card-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #f4f7fc;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e0e7ff;
    }

    .card-item h3 {
        font-size: 1rem;
        color: #1e293b;
        margin-bottom: 5px;
    }

    .card-item span {
        font-size: 1.2rem;
        font-weight: bold;
        color: #0ea5e9;
    }

    .highlight-section {
        text-align: center;
        padding: 15px;
        border-radius: 12px;
        background: linear-gradient(90deg, #10b981, #34d399);
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .swal2-confirm {
        background: #3b82f6;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-size: 1rem;
        cursor: pointer;
    }
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
        <div class="col-lg-6">
            <div class="row pt-4">
                <!-- First Column (Date Input) -->
                <div class="col-md-4 mb-3">
                    <label for="dueMonthDate" class="form-label">Select Due Date</label>
                    <input type="date" class="form-control" value="<?= $currentDate ?>" id="dueMonthDate">
                </div>
                
                <!-- Second Column (billType Select) -->
                <div class="col-md-4 mb-3">
                    <label for="billType" class="form-label">Group/Indiv Bills</label>
                    <select class="form-select" name="billType" id="billType" required>
                        <option value="1" selected>Indiv Bills</option>
                        <option value="2">Group Bills</option>
                    </select>
                </div>
                
                <!-- Third Column (Username Select) -->
                <div class="col-md-4 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <select class="form-select" name="username" id="username" required>
                        <!-- <option value="" disabled selected>Select</option> -->
                        <?php
                            // Query to fetch data from the database (assuming you're fetching usernames)
                            $sql = "SELECT username, name FROM user WHERE status = 1"; // Replace 'users' with your table name
                            $result = $con->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['username'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                }
                            } else {
                                echo '<option value="" selected>No users found</option>';
                            }

                            $con->close();
                        ?>
                    </select>
                </div>
            </div>

            <!-- Button Row (Adjusted Position) -->
            <div class="row pt-3">
                <div class="col-md-12">
                    <button type="button" class="btn btn-primary w-100" onclick="fetchUsersData()" style="padding: 12px 25px; font-size: 1rem; border-radius: 10px; background: linear-gradient(90deg, #0ea5e9, #3b82f6); color: white; border: none; cursor: pointer;">Search</button>
                </div>
            </div>
        </div>
    </div>
</div>

    
    
<!-- Bootstrap JS Bundle (including Popper) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    
<script type="text/javascript">

async function fetchUsersData() {
    const billType = document.getElementById('billType').value;
    const username = document.getElementById('username').value;
    const dueMonthDate = document.getElementById('dueMonthDate').value;

    // Check the value of billType
    if (billType == 1) {
        try {
            // Define the JSON data to send
            const requestData = {
                dueMonthDate: dueMonthDate,
                username: username
            };

            // Make the API call with POST method
            const response = await fetch('api/v1/users/getUserIndivBillingData.php', {
                method: 'POST', // Specify the method
                headers: {
                    'Content-Type': 'application/json' // Set content type to JSON
                },
                body: JSON.stringify(requestData) // Convert the JSON data to a string
            });

            // Check if the response is okay
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            // Parse the response JSON
            const resData = await response.json();

            // Extract data for rendering
            const userData = resData.data.userData.data[0];
            const cashData = resData.data.cashData;
            const gpayData = resData.data.gpayData;
            const paytmData = resData.data.paytmData;
            const creditData = resData.data.creditData;
            const posData = resData.data.pos;
            const incomeExpense = resData.data.incomeExpense;

            const sumTotal =
                cashData.amt +
                gpayData.amt +
                paytmData.amt +
                creditData.amt +
                posData.amt;

            const data = {
                username: `${userData.username} (${userData.name})`,
                dueMonthDate: resData.data.dueMonthDate,
                cash: `₹${cashData.amt} ~> ${cashData.count}`,
                paytm: `₹${paytmData.amt} ~> ${paytmData.count}`,
                gpay: `₹${gpayData.amt} ~> ${gpayData.count}`,
                credit: `₹${creditData.amt} ~> ${creditData.count}`,
                expense: `₹${incomeExpense.sumExpense}`,
                pos: `₹${posData.amt}`,
                total: `₹${sumTotal} - ₹${incomeExpense.sumExpense} = ₹${sumTotal - incomeExpense.sumExpense}`
            };

            // Create content for SweetAlert
            const content = `
                <div class="modern-card">
                    <div class="card-item">
                        <h3>User ID</h3>
                        <span>${data.username}</span>
                    </div>
                    <div class="card-item">
                        <h3>Billing Date</h3>
                        <span>${data.dueMonthDate}</span>
                    </div>
                    <div class="card-item">
                        <h3>Cash</h3>
                        <span>${data.cash}</span>
                    </div>
                    <div class="card-item">
                        <h3>Paytm</h3>
                        <span>${data.paytm}</span>
                    </div>
                    <div class="card-item">
                        <h3>GPay</h3>
                        <span>${data.gpay}</span>
                    </div>
                    <div class="card-item">
                        <h3>Credit</h3>
                        <span>${data.credit}</span>
                    </div>
                    <div class="card-item">
                        <h3>Expense</h3>
                        <span>${data.expense}</span>
                    </div>
                    <div class="card-item">
                        <h3>POS Amount</h3>
                        <span>${data.pos}</span>
                    </div>
                </div>
                <div class="highlight-section">
                    Total - Expense: ${data.total}
                </div>
            `;

            // Show SweetAlert
            Swal.fire({
                html: content,
                width: 'auto',
                showCloseButton: true,
                showConfirmButton: false,
            });
        } catch (error) {
            // Handle any errors
            console.error('Error fetching data:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to fetch user data. Please try again later.',
                showConfirmButton: true,
            });
        }
    } else {
        // Show SweetAlert for group bill
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Group Bill Under Construction',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }
}


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