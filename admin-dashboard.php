<?php 
   session_start();
   require_once "dbconfig.php";
   require_once 'preloader.php';
   require_once 'component.php';
      
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        $session_username = $_SESSION['username']; 
        $currentDate = date('Y-m-d');
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
    </style>
</head>
<body>
    
<?php include 'admin-menu-bar.php'; ?>
<br>
<?php include 'admin-menu-btn.php'; ?>

<div class="container main-content">
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
                                        $sql = "SELECT username, name FROM user WHERE status = 1"; 
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

    </div>
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
                                        <th>Type</th>
                                        <th>Cash</th>
                                        <th>Online (Paytm/GPay)</th>
                                        <th>Credit</th>
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

<?php // include 'footer.php'; ?>
</body>
</html>
<?php 
   } else {
       header("Location: logout.php");
   } 
?>