<?php 
   session_start();
   require_once "dbconfig.php";
   require_once 'preloader.php';
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
    
    $currentDate = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --text-dark: #2b2d42;
            --bg-light: #f8f9fa;
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-dark);
        }

        .main-content {
            padding: 2rem 1rem;
        }

        /* Card Styling */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            height: 100%;
            transition: transform 0.2s;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1.25rem 1.5rem;
            color: white;
            border: none;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            color: #4b5563;
        }
        
        .form-control {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        /* SweetAlert Custom Content Styles */
        .modal-data-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
            text-align: center;
            border: 1px solid #e9ecef;
        }
    </style>
</head>
<body>

<div class="container main-content">
    <div class="row justify-content-center">
        
        <!-- Collection Summary Card -->
        <div class="col-lg-8 col-md-10">
            <div class="custom-card">
                <div class="card-header-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-wallet2 fs-4 me-2"></i>
                        <h5 class="mb-0 fw-bold">My Collection Summary</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                     <form onsubmit="event.preventDefault(); fetchUsersBillingData();">
                        <input type="hidden" name="username" id="username" value="<?= htmlspecialchars($session_username, ENT_QUOTES, 'UTF-8') ?>">
                        
                        <div class="row g-3 align-items-end justify-content-center">
                            <div class="col-md-7">
                                <label for="dueMonthDate" class="form-label">Select Date</label>
                                <input type="date" class="form-control" value="<?= $currentDate ?>" id="dueMonthDate">
                            </div>

                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" 
                                        style="background: var(--primary-color); border: none;">
                                    <i class="bi bi-search me-2"></i>View Summary
                                </button>
                            </div>
                        </div>
                     </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script type="text/javascript">
var resData = null;

async function fetchUsersBillingData() {
    const username = document.getElementById('username').value;
    const dueMonthDate = document.getElementById('dueMonthDate').value;

    try {
        const response = await fetch('api/v1/users/getUserBillingData.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ dueMonthDate: dueMonthDate, username: username })
        });

        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

        const res = await response.json();
        resData = res.data;
        
        // Helper to format values
        const formatVal = (data) => `₹${data.amt} <i class="bi bi-arrow-right-short text-muted"></i> ${data.count}`;

        // Create Bootstrap-styled content for SweetAlert (Identical to Admin Dashboard)
        const content = `
            <div class="container-fluid px-0 text-start">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                         <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase" style="font-size:0.7rem;">User</small>
                            <strong>${resData.userData.name}</strong>
                         </div>
                    </div>
                    <div class="col-6">
                         <div class="p-2 border rounded bg-light">
                            <small class="text-muted d-block text-uppercase" style="font-size:0.7rem;">Date</small>
                            <strong>${resData.dueMonthDate}</strong>
                         </div>
                    </div>
                </div>

                <div class="card mb-3 border shadow-sm">
                    <div class="card-header bg-light fw-bold py-2 small text-uppercase text-center">Collection Details</div>
                    <div class="card-body p-2">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0 text-center align-middle" style="font-size: 0.9rem;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Type</th>
                                        <th>Cash</th>
                                        <th>Online (Paytm/GPay)</th>
                                        <th>Credit</th>
                                        <th>Discount</th>
                                        <th class="text-danger">Cancelled</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold text-primary">Indiv</td>
                                        <td>${formatVal(resData.indivData.cash)}</td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span>P: ${formatVal(resData.indivData.paytm)}</span>
                                                <span>G: ${formatVal(resData.indivData.gpay)}</span>
                                            </div>
                                        </td>
                                        <td>${formatVal(resData.indivData.credit)}</td>
                                        <td class="text-warning fw-bold">₹${resData.indivData.totDis}</td>
                                        <td class="text-danger fw-bold">${resData.indivCancelCount}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-success">Group</td>
                                        <td>${formatVal(resData.groupData.cash)}</td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span>P: ${formatVal(resData.groupData.paytm)}</span>
                                                <span>G: ${formatVal(resData.groupData.gpay)}</span>
                                            </div>
                                        </td>
                                        <td>${formatVal(resData.groupData.credit)}</td>
                                        <td class="text-warning fw-bold">₹${resData.groupData.totDis}</td>
                                        <td class="text-danger fw-bold">${resData.groupCancelCount}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-info">POS</td>
                                        <td>${formatVal(resData.posData.cash)}</td>
                                        <td>
                                            <div class="d-flex flex-column small">
                                                <span>P: ${formatVal(resData.posData.paytm)}</span>
                                                <span>G: ${formatVal(resData.posData.gpay)}</span>
                                            </div>
                                        </td>
                                        <td>${formatVal(resData.posData.credit)}</td>
                                        <td class="text-warning fw-bold">₹${resData.posData.totDis}</td>
                                        <td class="text-danger fw-bold">${resData.posCancelCount}</td>
                                    </tr>

                                    <tr>
                                        <td class="fw-bold text-success">Income</td>
                                        <td>${formatVal({amt: resData.incomeExpense.sumIncome, count: resData.incomeExpense.countIncome})}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold text-danger">Expense</td>
                                        <td>${formatVal({amt: resData.incomeExpense.sumExpense, count: resData.incomeExpense.countExpense})}</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                 <div class="row g-2 text-center mb-3">
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <div class="small text-muted text-uppercase" style="font-size:0.7rem;">Indiv Total</div>
                            <div class="fw-bold text-primary">₹${resData.indivData.totAmt}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <div class="small text-muted text-uppercase" style="font-size:0.7rem;">Group Total</div>
                            <div class="fw-bold text-success">₹${resData.groupData.totAmt}</div>
                        </div>
                    </div>
                     <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <div class="small text-muted text-uppercase" style="font-size:0.7rem;">POS Total</div>
                            <div class="fw-bold text-info">₹${resData.posData.totAmt}</div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-success d-flex justify-content-between align-items-center mb-0 shadow-sm">
                    <span class="fw-bold text-uppercase small">Total Amount In Hand</span>
                    <span class="fs-4 fw-bold">₹ ${resData.amountInHand}</span>
                </div>
                
                 <div class="mt-3 text-center">
                    <button type="button" class="btn btn-warning text-white fw-bold w-100 shadow-sm" onclick="printUsersBillingData()">
                        <i class="fa-solid fa-print me-2"></i> Print Summary
                    </button>
                </div>
            </div>
        `;

        Swal.fire({
            title: '',
            html: content,
            width: '650px',
            showConfirmButton: false,
            showCloseButton: true,
            customClass: {
                popup: 'rounded-4'
            }
        });

    } catch (error) {
        console.error('Error fetching data:', error);
        Swal.fire({
            icon: 'error',
            title: 'Fetch Error',
            text: 'Failed to retrieve billing data.',
            confirmButtonColor: '#4361ee'
        });
    }
}

function printUsersBillingData() {
    Swal.fire({
        title: 'Print Preview',
        html: `
            <iframe width="100%" height="500" 
                    src="prtUserBillingData.php?d=${encodeURIComponent(JSON.stringify(resData))}" 
                    frameborder="0" style="border-radius: 8px; border: 1px solid #ddd;"></iframe>
            <div class="mt-3">
                <button id="backBtn" class="btn btn-secondary btn-sm">Back to Summary</button>
            </div>
        `,
        width: '700px',
        showConfirmButton: false,
        showCloseButton: true,
        didOpen: () => {
            document.getElementById('backBtn').addEventListener('click', () => {
                // Re-open previous summary
                fetchUsersBillingData(); 
            });
        }
    });
}
</script>

</body>
</html>

<?php }else{
    header("Location: index.php");
} ?>
