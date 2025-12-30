<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   require_once 'component.php';
   
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
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/592a9320b6.js" crossorigin="anonymous"></script>
    

</head>
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
<body >

        
      
<div class="container d-flex justify-content-center align-items-center">
    <div class="row w-100 text-center">
        <!-- Parent Column (Full Width) -->
        <div class="col-12">
            <!-- First Row (Bill Type Select and Search Button) -->
            <div class="row mb-3 justify-content-center">
                <div class="col-md-6 col-12 mb-3">
                    <!-- Hidden Input for Current Date -->
                    <input type="hidden" name="dueMonthDate" value="<?= htmlspecialchars($currentDate, ENT_QUOTES, 'UTF-8') ?>" id="dueMonthDate">
                    <!-- Hidden Input for Username -->
                    <input type="hidden" name="username" id="username" value="<?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>" required>
                    <label for="billType" class="form-label fw-bold">Today Collection Summary</label>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" onclick="fetchUsersBillingData()" style="padding: 8px 18px; font-size: 1rem; border-radius: 10px; background: linear-gradient(90deg, #0ea5e9, #3b82f6); color: white; border: none; cursor: pointer;">Search</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






    
    
<!-- Bootstrap JS Bundle (including Popper) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    
<script type="text/javascript">

async function fetchUsersBillingData() {
    const username = document.getElementById('username').value;
    const dueMonthDate = document.getElementById('dueMonthDate').value;

    try {
        // Define the JSON data to send
        const requestData = {
            dueMonthDate: dueMonthDate,
            username: username
        };

        // Make the API call with POST method
        const response = await fetch('api/v1/users/getUserBillingData.php', {
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
        const res = await response.json();
        resData = res.data;
        console.log(resData);

        const data = {
            username: `${resData.userData.username} (${resData.userData.name})`,
            dueMonthDate: resData.dueMonthDate,
            indivCash: `₹${resData.indivData.cash.amt} ~> ${resData.indivData.cash.count}`,
            indivPaytm: `₹${resData.indivData.paytm.amt} ~> ${resData.indivData.paytm.count}`,
            indivGpay: `₹${resData.indivData.gpay.amt} ~> ${resData.indivData.gpay.count}`,
            indivCredit: `₹${resData.indivData.credit.amt} ~> ${resData.indivData.credit.count}`,

            groupCash: `₹${resData.groupData.cash.amt} ~> ${resData.groupData.cash.count}`,
            groupPaytm: `₹${resData.groupData.paytm.amt} ~> ${resData.groupData.paytm.count}`,
            groupGpay: `₹${resData.groupData.gpay.amt} ~> ${resData.groupData.gpay.count}`,
            groupCredit: `₹${resData.groupData.credit.amt} ~> ${resData.groupData.credit.count}`,

            posCash: `₹${resData.posData.cash.amt}-${resData.posData.cash.discount}=${resData.posData.cash.amt - resData.posData.cash.discount} ~> ${resData.posData.cash.count}`,
            posGpay: `₹${resData.posData.gpay.amt}-${resData.posData.gpay.discount}=${resData.posData.gpay.amt - resData.posData.gpay.discount} ~> ${resData.posData.gpay.count}`,
            posPaytm: `₹${resData.posData.paytm.amt}-${resData.posData.paytm.discount}=${resData.posData.paytm.amt - resData.posData.paytm.discount} ~> ${resData.posData.paytm.count}`,
            posCredit: `₹${resData.posData.credit.amt}-${resData.posData.credit.discount}=${resData.posData.credit.amt - resData.posData.credit.discount} ~> ${resData.posData.credit.count}`,
            
            incomeExpense: `₹${resData.incomeExpense.sumIncome} / ₹${resData.incomeExpense.sumExpense}`,
            overall: `₹${resData.totAmt} - ₹${resData.totDis} = ₹${resData.totAmt - resData.totDis}`
        };

        // console.table(JSON.stringify(data, null, 2));

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
                    <h3>Indiv Cash</h3>
                    <span>${data.indivCash}</span>
                    <h3>Group Cash</h3>
                    <span>${data.groupCash}</span>
                    <h3>POS Cash</h3>
                    <span>${data.posCash}</span>
                </div>
                <div class="card-item">
                    <h3>Indiv Paytm</h3>
                    <span>${data.indivPaytm}</span>
                    <h3>Group Paytm</h3>
                    <span>${data.groupPaytm}</span>
                    <h3>POS Paytm</h3>
                    <span>${data.posPaytm}</span>
                </div>
                <div class="card-item">
                    <h3>Indiv GPay</h3>
                    <span>${data.indivGpay}</span>
                    <h3>Group GPay</h3>
                    <span>${data.groupGpay}</span>
                    <h3>POS GPay</h3>
                    <span>${data.posGpay}</span>
                </div>
                <div class="card-item">
                    <h3>Indiv Credit</h3>
                    <span>${data.indivCredit}</span>
                    <h3>Group Credit</h3>
                    <span>${data.groupCredit}</span>
                    <h3>POS Credit</h3>
                    <span>${data.posCredit}</span>
                </div>
                <div class="card-item">
                    <h3>Indiv Total / Discount</h3>
                    <span>₹${resData.indivData.totAmt} / ₹${resData.indivData.totDis}</span>
                </div>
                <div class="card-item">
                    <h3>Group Total / Discount</h3>
                    <span>₹${resData.groupData.totAmt} / ₹${resData.groupData.totDis}</span>
                </div>
                <div class="card-item">
                    <h3>POS Total / Discount</h3>
                    <span>₹${resData.posData.totAmt} / ₹${resData.posData.totDis}</span>
                </div>
                <div class="card-item">
                    <h3>Income / Expense</h3>
                    <span>${data.incomeExpense}</span>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="highlight-section m-2" style="font-size: 18px;">
                            Indiv/Group/POS/Income (Cash - Discount) Rs: <span style="font-size: 20px;">${resData.amountInHand}</span>
                        </div>
                        <!--div class="highlight-section m-2">
                            Total - Expense: ${data.overall}
                        </div-->
                        <button type="button" class="btn btn-primary w-100" 
                                onclick="printUsersBillingData()" 
                                style="padding: 8px 18px; font-size: 1rem; border-radius: 10px; background: linear-gradient(90deg, #fbbf24, #f59e0b); color: white; border: none; cursor: pointer; margin-left: 10px;">
                            <i class="fa-solid fa-print"></i> Print
                        </button>
                    </div>
                </div>
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
}

async function printUsersBillingData() {
    Swal.fire({
        title: `Collection Summary`,
        html: `
            <iframe 
            width="100%" 
            height="515" 
            src="prtUserBillingData.php?d=${encodeURIComponent(JSON.stringify(resData))}" 
            frameborder="0" 
            allowfullscreen
            style="max-width: 600px; width: 100%; height: 515px;">
            </iframe>
            <button id="myButton" class="swal2-confirm swal2-styled mt-2">Back</button>`,
        showConfirmButton: false,
        showCloseButton: true,
        position: 'top',
        didOpen: () => {
            // Add event listener for the button
            document.getElementById('myButton').addEventListener('click', () => {
                fetchUsersBillingData();
            });
        }
    });
}

</script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php //include 'footer.php'?>

<?php }else{
	header("Location: index.php");
} ?>

