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
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    

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

        
      
<div class="container d-flex justify-content-center align-items-center pt-2">
    <div class="row w-100">
        <!-- Parent Column (Full Width) -->
        <div class="col-12">
            <!-- First Row (Bill Type Select and Search Button) -->
            <div class="row mb-3">
                <!-- First Column (Bill Type Select) -->
                <div class="col-md-6 col-12 mb-3">
                    <!-- Hidden Input for Username -->
                    <input type="hidden" name="username" id="username" value="<?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') ?>" required>

                    <!-- Label and Select for Bill Type -->
                    <label for="billType" class="form-label">Group/Individual Bills</label>
                    <select class="form-select" name="billType" id="billType" required>
                        <option value="1" selected>Individual Bills</option>
                        <option value="2">Group Bills</option>
                    </select>

                    <!-- Hidden Input for Current Date -->
                    <input type="hidden" name="dueMonthDate" value="<?= htmlspecialchars($currentDate, ENT_QUOTES, 'UTF-8') ?>" id="dueMonthDate">
                </div>

                <!-- Second Column (Search Button) -->
                <div class="col-md-6 col-12 mb-3 d-flex align-items-end">
                    <button type="button" class="btn btn-primary w-100" onclick="fetchUsersData()" style="padding: 12px 25px; font-size: 1rem; border-radius: 10px; background: linear-gradient(90deg, #0ea5e9, #3b82f6); color: white; border: none; cursor: pointer;">Search</button>
                </div>
            </div>
        </div>
    </div>
</div>





    
    
<!-- Bootstrap JS Bundle (including Popper) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    
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

</script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php //include 'footer.php'?>

<?php }else{
	header("Location: index.php");
} ?>

