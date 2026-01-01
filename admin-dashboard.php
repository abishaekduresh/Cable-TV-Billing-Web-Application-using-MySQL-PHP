<?php 
   session_start();
   require_once "dbconfig.php";
   require_once 'preloader.php';
   require_once 'component.php';
      
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        $session_username = $_SESSION['username']; 
        $currentDate = date('Y-m-d');
        $logged_username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- jQuery (Required for dashboard logic) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

        /* Stat Card */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            border-left: 5px solid var(--primary-color);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.75rem;
        }

        .stat-info h6 {
            margin: 0;
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            font-weight: 600;
        }

        .stat-info h3 {
            margin: 0;
            font-weight: 700;
            color: var(--text-dark);
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            color: #4b5563;
        }
        
        .form-control, .form-select {
            padding: 0.6rem 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .btn-modern {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        
        .btn-biometric {
            background: linear-gradient(135deg, #2ec4b6, #20a4f3);
            color: white;
            border: none;
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 4px 6px rgba(32, 164, 243, 0.3);
        }
        .btn-biometric:hover {
             transform: translateY(-2px);
             box-shadow: 0 6px 12px rgba(32, 164, 243, 0.4);
             color: white;
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
        
        .modal-data-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .modal-data-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2b2d42;
        }
        
        .modal-total-section {
            background: #e0f2fe;
            color: #0369a1;
        }
        .modal-total-section {
            background: #e0f2fe;
            color: #0369a1;
        }

        /* Privacy Mode */
        .privacy-blur {
            filter: blur(6px);
            user-select: none;
            transition: filter 0.3s ease;
        }
        
        .privacy-active .stat-card h3,
        .privacy-active canvas,
        .privacy-active #avblSMSbalanceAmt {
            filter: blur(8px);
            opacity: 0.6;
            pointer-events: none; /* Prevent tooltip hover when blurred */
        }
    </style>
</head>
<body>
    
<?php include 'admin-menu-bar.php'; ?>
<br>
<?php include 'admin-menu-btn.php'; ?>

<div class="container main-content">
    <div class="row g-4 mb-4">
        <?php if(str_contains($session_username, 'A')) { ?>
        <div class="col-12">
<div class="row g-4 mb-4">
        <!-- Control Bar -->
        <div class="col-12">
            <div class="d-flex flex-wrap align-items-center justify-content-between p-3 bg-white rounded-3 shadow-sm border">
                
                <div class="d-flex align-items-center gap-2">
                    <!-- Privacy Toggle -->
                    <button class="btn btn-secondary btn-lg" id="privacyBtn" onclick="togglePrivacy()" title="Toggle Privacy Mode">
                        <i class="bi bi-eye-slash-fill" id="privacyIcon"></i>
                    </button>
                    
                    <h5 class="mb-0 fw-bold ms-2 text-secondary border-start ks-3 ps-3">Analytics Control</h5>
                </div>

                <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                    <!-- Date Preset -->
                    <select class="form-select w-auto" id="datePreset" onchange="applyDatePreset()">
                        <option value="today" selected>Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_month">This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>

                    <!-- Date Inputs -->
                    <input type="date" class="form-control w-auto" id="startDate" value="<?= date('Y-m-d') ?>">
                    <input type="date" class="form-control w-auto" id="endDate" value="<?= date('Y-m-d') ?>">

                    <!-- Filter Button -->
                    <button class="btn btn-primary" onclick="refreshDashboard()" title="Apply Filter">
                        <i class="bi bi-funnel-fill"></i>
                    </button>

                    <!-- Refresh Button -->
                    <button class="btn btn-outline-primary" onclick="refreshDashboard()" title="Refresh Data">
                        <i class="bi bi-arrow-clockwise"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="fw-bold mb-3"><i class="bi bi-calendar-check me-2 text-primary"></i>Period Overview <small class="text-muted fw-normal fs-6 ms-2" id="periodLabel">(Today)</small></h5>
            <div class="row g-3">
                <!-- Total Collection -->
                <div class="col-lg">
                    <div class="stat-card p-3 h-100 border-start-0 border-top border-4 border-primary">
                        <div class="stat-info">
                            <h6>Total Collection</h6>
                            <h3 class="text-primary">₹ <span id="todayTotal">0</span></h3>
                        </div>
                        <div class="stat-icon bg-soft-primary text-primary">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
                <!-- Indiv -->
                <div class="col-lg">
                    <div class="stat-card p-3 h-100 border-start-0 border-top border-4 border-info">
                        <div class="stat-info">
                            <h6>Individual</h6>
                            <h3 class="text-info">₹ <span id="todayIndiv">0</span></h3>
                        </div>
                        <div class="stat-icon bg-soft-info text-info">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>
                <!-- Group -->
                <div class="col-lg">
                    <div class="stat-card p-3 h-100 border-start-0 border-top border-4 border-success">
                        <div class="stat-info">
                            <h6>Group</h6>
                            <h3 class="text-success">₹ <span id="todayGroup">0</span></h3>
                        </div>
                        <div class="stat-icon bg-soft-success text-success">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>
                <!-- POS -->
                <div class="col-lg">
                    <div class="stat-card p-3 h-100 border-start-0 border-top border-4 border-warning">
                        <div class="stat-info">
                            <h6>POS</h6>
                            <h3 class="text-warning">₹ <span id="todayPOS">0</span></h3>
                        </div>
                        <div class="stat-icon bg-soft-warning text-warning">
                            <i class="bi bi-shop"></i>
                        </div>
                    </div>
                </div>
                <!-- Expense -->
                <div class="col-lg">
                    <div class="stat-card p-3 h-100 border-start-0 border-top border-4 border-danger">
                        <div class="stat-info">
                            <h6>Expense</h6>
                            <h3 class="text-danger">₹ <span id="todayExpense">0</span></h3>
                        </div>
                        <div class="stat-icon bg-soft-danger text-danger">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>

    <div class="row g-4">
        
        <!-- Left Column: Quick Stats & Actions -->
        <div class="col-lg-5">
            
            <!-- SMS Balance Card -->
            <div class="stat-card">
                <div class="stat-info">
                    <h6>SMS Balance</h6>
                    <h3>₹ <span id="avblSMSbalanceAmt">0.00</span></h3>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-chat-square-text-fill"></i>
                </div>
            </div>

            <!-- Biometric Action -->
            <a href="https://biometric.pdpgroups.com/app/login?t=<?= urlencode(encrypt(($_SESSION['username'] ?? '') . '.' . getUnixTimestamp())) ?>" 
               target="_blank" class="text-decoration-none">
                <div class="btn-biometric">
                     <i class="bi bi-fingerprint fs-4"></i>
                     <span>Access Biometric System</span>
                </div>
            </a>

        </div>

        <!-- Right Column: Collection Search -->
        <div class="col-lg-7">
            <div class="custom-card">
                <div class="card-header-gradient d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-search fs-4 me-2"></i>
                        <h5 class="mb-0 fw-bold">User Collection Search</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                     <form onsubmit="event.preventDefault(); fetchUsersBillingData();">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="dueMonthDate" class="form-label">Select Due Date</label>
                                <input type="date" class="form-control" value="<?= $currentDate ?>" id="dueMonthDate">
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label">Select User</label>
                                <select class="form-select" name="username" id="username" required>
                                    <?php
                                        $sql = "SELECT username, name FROM user WHERE status = 1 ORDER BY id DESC"; 
                                        $result = $con->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo '<option value="' . $row['username'] . '">' . htmlspecialchars($row['name']) . '</option>';
                                            }
                                        } else {
                                            echo '<option value="" disabled>No users found</option>';
                                        }
                                        // $con->close(); // Keep connection open for other includes if needed
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" 
                                        style="background: var(--primary-color); border: none;">
                                    <i class="bi bi-search me-2"></i>Search Records
                                </button>
                            </div>
                        </div>
                     </form>
                </div>
            </div>
        </div>
        
        <!-- Dashboard Graphs Section -->
        <?php if(str_contains($session_username, 'A')) { ?>
        <div class="col-12 mt-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-graph-up-arrow me-2 text-primary"></i>Analytics Overview</h5>
            <div class="row g-4">
                
                <!-- Revenue Trend -->
                <div class="col-lg-6">
                    <div class="custom-card p-3">
                         <h6 class="text-uppercase text-muted fw-bold small mb-3">Revenue Trend <span id="revenueTrendLabel" class="text-primary smaller"></span></h6>
                         <div style="height: 300px;">
                             <canvas id="revenueChart"></canvas>
                         </div>
                    </div>
                </div>

                <!-- Collection Source -->
                <div class="col-lg-3">
                    <div class="custom-card p-3">
                         <h6 class="text-uppercase text-muted fw-bold small mb-3">Collection Source</h6>
                         <div style="height: 250px;">
                             <canvas id="sourceChart"></canvas>
                         </div>
                    </div>
                </div>
                
                <!-- Customer Status -->
                <div class="col-lg-3">
                     <div class="custom-card p-3">
                         <h6 class="text-uppercase text-muted fw-bold small mb-3">Customer Status</h6>
                         <div style="height: 250px;">
                             <canvas id="customerChart"></canvas>
                         </div>
                    </div>
                </div>

            </div>
</div>
            
            <div class="row g-4 mt-2">
                 <!-- Income vs Expense -->
                 <div class="col-lg-8">
                    <div class="custom-card p-3">
                         <h6 class="text-uppercase text-muted fw-bold small mb-3">Income vs Expense (This Month)</h6>
                         <div style="height: 300px;">
                             <canvas id="incomeExpenseChart"></canvas>
                         </div>
                    </div>
                 </div>

                 <!-- Payment Modes -->
                 <div class="col-lg-4">
                    <div class="custom-card p-3">
                         <h6 class="text-uppercase text-muted fw-bold small mb-3">Payment Modes (This Month)</h6>
                         <div style="height: 300px;">
                             <canvas id="paymentModeChart"></canvas>
                         </div>
                    </div>
                 </div>
            </div>

    </div>
    <?php } ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        // Create Bootstrap-styled content for SweetAlert
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
                fetchUsersBillingData(); // Logic re-runs to show previous view (simplest way approx)
                // Or simply Swal.clickConfirm() if we Want to close? No, user wants back.
                // Calling fetchUsersBillingData will overwrite current swal.
            });
        }
    });
}

function formatMoney(amount) {
    return parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

$(document).ready(function() {
    // Load SMS Balance
    var result = <?php echo json_encode(getAvblSMSbalanceAmt()); ?>;
    if (result && result.status && result.data && result.data[0]) {
        $("#avblSMSbalanceAmt").html(formatMoney(result.data[0].BalanceAmount));
    } else {
        $("#avblSMSbalanceAmt").html("0.00");
    }
});
</script>

    <!-- Chart.js (UMD version for compatibility) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script type="text/javascript">
    
    // --- Privacy & Control Logic ---
    let isPrivacyOn = false; // Start false, so the initial toggle turns it ON (True)
    let chartsInstance = {}; // Store chart instances to destroy/update

    function togglePrivacy() {
        isPrivacyOn = !isPrivacyOn;
        const icon = document.getElementById('privacyIcon');
        const btn = document.getElementById('privacyBtn');
        const body = document.body;
        
        if(isPrivacyOn) {
            body.classList.add('privacy-active');
            icon.classList.remove('bi-eye-fill');
            icon.classList.add('bi-eye-slash-fill');
            btn.classList.remove('btn-outline-secondary');
            btn.classList.add('btn-secondary'); // Solid when hiding
        } else {
            body.classList.remove('privacy-active');
            icon.classList.remove('bi-eye-slash-fill');
            icon.classList.add('bi-eye-fill');
            btn.classList.remove('btn-secondary');
            btn.classList.add('btn-outline-secondary');
        }
    }

    function applyDatePreset() {
        const preset = document.getElementById('datePreset').value;
        const startEl = document.getElementById('startDate');
        const endEl = document.getElementById('endDate');
        const today = new Date();
        
        let start, end;
        
        switch(preset) {
            case 'today':
                start = today;
                end = today;
                break;
            case 'yesterday':
                start = new Date(today);
                start.setDate(today.getDate() - 1);
                end = start;
                break;
            case 'this_month':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                break;
            case 'last_month':
                start = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                end = new Date(today.getFullYear(), today.getMonth(), 0);
                break;
            default: // Custom
                return; // Do nothing, let user pick
        }
        
        // Helper to format YYYY-MM-DD
        const fmt = (d) => d.toISOString().split('T')[0];
        
        if(start && end) {
            startEl.value = fmt(start);
            endEl.value = fmt(end);
            refreshDashboard(); // Auto-refresh on preset change
        }
    }

    function refreshDashboard() {
        const start = document.getElementById('startDate').value;
        const end = document.getElementById('endDate').value;
        const presetText = document.getElementById('datePreset').options[document.getElementById('datePreset').selectedIndex].text;
        
        document.getElementById('periodLabel').innerText = `(${presetText}: ${start} to ${end})`;
        
        loadDashboardCharts(start, end);
    }


    // --- Chart.js Integration ---
    async function loadDashboardCharts(startDate = '', endDate = '') {
        // Check if Chart is loaded
        if (typeof Chart === 'undefined') {
            return;
        }

        try {
            let url = 'api/v1/admin/getDashboardStats.php?t=' + new Date().getTime();
            if(startDate && endDate) {
                url += `&startDate=${startDate}&endDate=${endDate}`;
            }

            const response = await fetch(url);
            const text = await response.text();
            
            let res;
            try {
                res = JSON.parse(text);
            } catch (e) {
                console.error("Server Response:", text);
                return;
            }
            
            if(res.status && res.data) {
                const d = res.data;
                
                // --- Update Period Overview ---
                
                // Determine Date Range Diff for Label
                if(startDate && endDate) {
                    const startDt = new Date(startDate);
                    const endDt = new Date(endDate);
                    const diffTime = Math.abs(endDt - startDt);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
                    
                    const labelSpan = document.getElementById('revenueTrendLabel');
                    if(labelSpan) {
                         labelSpan.textContent = (diffDays <= 31) ? '(Daily)' : '(Monthly)';
                    }
                }
                if(d.periodOverview) {
                    const to = d.periodOverview;
                    const fmt = (n) => parseFloat(n).toLocaleString('en-IN');
                    document.getElementById('todayTotal').innerText = fmt(to.totalCollection);
                    document.getElementById('todayIndiv').innerText = fmt(to.indiv);
                    document.getElementById('todayGroup').innerText = fmt(to.group);
                    document.getElementById('todayPOS').innerText = fmt(to.pos);
                    document.getElementById('todayExpense').innerText = fmt(to.expense);
                }

                // Helper to destroy existing chart before re-creating
                const destroyChart = (id) => {
                    if(chartsInstance[id]) {
                        chartsInstance[id].destroy();
                    }
                };

                // Common Chart Options
                const commonOptions = {
                    responsive: true,
                    maintainAspectRatio: false
                };

                // 1. Revenue Chart (Stays same mostly, but we reload it)
                const revCtx = document.getElementById('revenueChart');
                if (revCtx) {
                    destroyChart('Revenue');
                    chartsInstance['Revenue'] = new Chart(revCtx, {
                        type: 'line',
                        data: {
                            labels: d.revenueTrend.labels,
                            datasets: [{
                                label: 'Revenue (₹)',
                                data: d.revenueTrend.data,
                                borderColor: '#4361ee',
                                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#4361ee',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            ...commonOptions,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
                
                // 2. Source Chart
                const srcCtx = document.getElementById('sourceChart');
                if (srcCtx) {
                    destroyChart('Source');
                    chartsInstance['Source'] = new Chart(srcCtx, {
                        type: 'doughnut',
                        data: {
                            labels: d.collectionSource.labels,
                            datasets: [{
                                data: d.collectionSource.data,
                                backgroundColor: ['#4361ee', '#2ec4b6', '#3f37c9'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            ...commonOptions,
                            plugins: { 
                                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } } 
                            },
                            cutout: '70%'
                        }
                    });
                }

                // 3. Customer Chart
                const custCtx = document.getElementById('customerChart');
                if (custCtx) {
                    destroyChart('Customer');
                    chartsInstance['Customer'] = new Chart(custCtx, {
                        type: 'pie',
                        data: {
                            labels: d.customerStatus.labels,
                            datasets: [{
                                data: d.customerStatus.data,
                                backgroundColor: ['#06d6a0', '#ef476f'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            ...commonOptions,
                            plugins: { 
                                legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } }
                            }
                        }
                    });
                }

                // 4. Income vs Expense Chart
                const incExpCtx = document.getElementById('incomeExpenseChart');
                if (incExpCtx) {
                    destroyChart('IncExp');
                    chartsInstance['IncExp'] = new Chart(incExpCtx, {
                        type: 'bar',
                        data: {
                            labels: d.incomeVsExpense.labels,
                            datasets: [{
                                label: 'Amount (₹)',
                                data: d.incomeVsExpense.data,
                                backgroundColor: ['#06d6a0', '#ef476f'],
                                borderRadius: 5,
                                barPercentage: 0.6
                            }]
                        },
                        options: {
                            ...commonOptions,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }

                // 5. Payment Mode Chart
                const payModeCtx = document.getElementById('paymentModeChart');
                if (payModeCtx) {
                    destroyChart('PayMode');
                    chartsInstance['PayMode'] = new Chart(payModeCtx, {
                        type: 'polarArea',
                        data: {
                            labels: d.paymentMode.labels,
                            datasets: [{
                                data: d.paymentMode.data,
                                backgroundColor: [
                                    'rgba(67, 97, 238, 0.7)',
                                    'rgba(46, 196, 182, 0.7)',
                                    'rgba(63, 55, 201, 0.7)',
                                    'rgba(247, 37, 133, 0.7)',
                                    'rgba(72, 149, 239, 0.7)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            ...commonOptions,
                            plugins: { 
                                legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true } }
                            },
                            scales: {
                                r: { ticks: { display: false }, grid: { color: '#f3f4f6' } }
                            }
                        }
                    });
                }

            } else {
                Swal.fire('Data Fetch Error', res.message || 'Unknown error occurred', 'warning');
            }
        } catch (e) {
            console.error(e);
            Swal.fire('Network Error', 'Failed to fetch dashboard data.', 'error');
        }
    }

    $(document).ready(function() {
        // Load SMS Balance
        var result = <?php echo json_encode(getAvblSMSbalanceAmt()); ?>;
        if (result && result.status && result.data && result.data[0]) {
            $("#avblSMSbalanceAmt").html(formatMoney(result.data[0].BalanceAmount));
        } else {
            $("#avblSMSbalanceAmt").html("0.00");
        }
        
        // Initialize Privacy & Dashboard (Only if controls exist)
        if(document.getElementById('startDate')) {
            togglePrivacy(); // Sets initial state (On)
            refreshDashboard(); // Initial Fetch
        }
    });
    </script>

<?php // include 'footer.php'; ?>
</body>
</html>
<?php 
   } else {
       header("Location: logout.php");
   } 
?>