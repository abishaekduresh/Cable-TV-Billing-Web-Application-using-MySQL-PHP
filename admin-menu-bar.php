<?php
if (session_status() === PHP_SESSION_NONE) {
    // Session should normally be started by parent
}

include_once "dbconfig.php"; 

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $session_user = $_SESSION['username']; // Changed to 'username' as per common session usage in this project, assuming 'name' might fail if not set.

    // Fetch App Name
    $appName2 = "Admin Panel"; // Default
    $sql = "SELECT appName2 FROM settings LIMIT 1";
    $result = mysqli_query($con, $sql); // Use mysqli consistent with dbconfig
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $appName2 = $row['appName2'];
    }
?>

<!-- Dependencies (Safe to include here for pages that rely on menu for styles) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- SweetAlert2 (Commonly used by valid pages) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<!-- Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --text-dark: #2b2d42;
        --bg-light: #f8f9fa;
        --white: #ffffff;
    }

    /* Navbar Styles */
    .admin-navbar {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Soft premium shadow */
        padding: 0.8rem 1rem;
    }
    
    .navbar-brand {
        font-weight: 700;
        font-size: 1.25rem;
        color: var(--white) !important;
        letter-spacing: -0.5px;
    }

    .nav-link {
        color: rgba(255, 255, 255, 0.9) !important;
        font-weight: 500;
        font-size: 0.95rem;
        padding: 0.5rem 1rem !important;
        transition: all 0.2s ease;
        border-radius: 6px;
    }

    .nav-link:hover, .nav-link:focus, .nav-link.active {
        color: var(--white) !important;
        background-color: rgba(255, 255, 255, 0.15);
    }

    .nav-link i {
        margin-right: 6px;
        color: rgba(255, 255, 255, 0.8);
    }
    .nav-link:hover i {
        color: var(--white);
    }

    /* Dropdowns */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        border-radius: 8px;
        padding: 0.5rem;
        margin-top: 10px;
    }
    
    .dropdown-item {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        color: var(--text-dark);
        font-weight: 500;
    }
    
    .dropdown-item:hover {
        background-color: #f7fafc;
        color: var(--primary-color);
    }
    
    .dropdown-divider {
        border-color: #edf2f7;
        margin: 0.4rem 0;
    }

    /* Buttons in Form */
    .nav-btn-group .btn {
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    /* Override buttons for dark header */
    .btn-outline-info-custom {
        border: 1px solid rgba(255,255,255,0.4);
        color: white;
    }
    .btn-outline-info-custom:hover {
        background: white;
        color: var(--primary-color);
    }

    .btn-outline-danger-custom {
        border: 1px solid rgba(239, 71, 111, 0.8);
        background: rgba(239, 71, 111, 0.15);
        color: #ffcccc;
    }
    .btn-outline-danger-custom:hover {
        background: var(--danger-color);
        color: white;
    }
    .btn-outline-danger-custom:hover {
        background: var(--danger-color);
        color: white;
    }
    
    .btn-outline-warning-custom {
        border: 1px solid rgba(246, 194, 62, 0.8);
        color: #fff3cd;
    }
    .btn-outline-warning-custom:hover {
        background: var(--warning-color);
        color: #000;
    }

    /* Calculator Styles - Casio Theme */
    .casio-modal {
        background-color: #2c3e50; /* Dark casing */
        border: 4px solid #1a252f;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.6);
    }
    .casio-display-container {
        background-color: #9ea7a6; /* LCD Background */
        padding: 15px;
        border-radius: 10px;
        border: 3px solid #576574;
        margin-bottom: 20px;
        box-shadow: inset 0 3px 8px rgba(0,0,0,0.3);
    }
    .calc-display {
        font-family: 'Courier New', Courier, monospace; /* LCD-ish font */
        font-size: 3rem;
        height: 80px;
        background: transparent;
        color: #1a252f;
        border: none;
        text-align: right;
        padding: 0;
        font-weight: 700;
        letter-spacing: 3px;
    }
    .calc-brand {
        color: #bfc7c6;
        font-size: 0.9rem;
        font-weight: bold;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .calc-btn {
        height: 65px;
        font-size: 1.5rem;
        border-radius: 10px;
        font-weight: bold;
        box-shadow: 0 4px 0 #1a252f;
        border: none;
        transition: all 0.1s;
    }
    .calc-btn:active {
        transform: translateY(4px);
        box-shadow: none;
    }
    
    /* Mobile Responsive Tweaks */
    @media (max-width: 576px) {
        .calc-btn { height: 55px; font-size: 1.2rem; }
        .calc-display { font-size: 2.2rem; height: 60px; }
        .casio-modal { border-width: 3px; }
    }
    .btn-num {
        background-color: #34495e;
        color: #ecf0f1;
    }
    .btn-num:hover { background-color: #2c3e50; color: white; }
    
    .btn-op {
        background-color: #95a5a6;
        color: #2c3e50;
    }
    .btn-op:hover { background-color: #7f8c8d; }

    .btn-clear {
        background-color: #e74c3c;
        color: white;
    }
    .btn-clear:hover { background-color: #c0392b; color: white; }

    .btn-equal {
        background-color: #e67e22; /* Casio Orange/Yellowish */
        color: white;
    }
    .btn-equal:hover { background-color: #d35400; color: white; }
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* User Info */
    .user-info-text {
        color: white;
    }
    .user-date-text {
        color: rgba(255,255,255,0.7);
    }
</style>

<nav class="navbar navbar-expand-lg admin-navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="admin-dashboard.php">
            <img src="https://files.catbox.moe/sepcbf.png" alt="Logo" height="40" class="me-2"><?= htmlspecialchars($appName2) ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbarContent" aria-controls="adminNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                
                <!-- Latest Bill -->
                <li class="nav-item">
                    <a class="nav-link" href="bill-last5-print.php">
                        <i class="fas fa-receipt"></i>Latest Bill
                    </a>
                </li>
                
                <!-- Billing Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="billingDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-file-invoice-dollar"></i>Billing
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="billingDrop">
                        <li><a class="dropdown-item" href="billing-dashboard.php">Indiv Billing Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="billing-group-dashboard.php">Group Billing Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="adv-indiv-billing-dashboard.php">Indiv Advance Billing</a></li>
                    </ul>
                </li>

                <!-- Customer Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="custDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-users"></i>Customer
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="custDrop">
                        <li><a class="dropdown-item" href="search-customer.php">Search Customer</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="customer-history.php">Customer History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="billing-dashboard.php">Billing Dashboard</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="customer-details.php">Customer Details/Action</a></li>
                    </ul>
                </li>

                <!-- Report Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="reportDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-chart-line"></i>Report
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportDrop">
                        <li><a class="dropdown-item" href="admin-bill-filter-by-all.php"><i class="bi bi-person-fill me-2"></i>Indiv Bill by All</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rptadvindivbill.php"><i class="bi bi-cash-coin me-2"></i>Advance Indiv Bill</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rptgroupbill.php"><i class="bi bi-collection-fill me-2"></i>Group & Adv Report</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="admin-in-ex-report.php"><i class="bi bi-graph-up me-2"></i>Income Expense</a></li>
                        <li><hr class="dropdown-divider"></li>

                        <li><a class="dropdown-item" href="rptindivcancelledbill.php" target="_blank"><i class="bi bi-person-x me-2"></i>Cancelled Indiv Bill</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rptposinvoice.php">POS Report</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rpt-indiv-unpaid-list.php" target="_blank"><i class="bi bi-person-fill me-2"></i>Indiv Unpaid List</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rpt-customer-list.php"><i class="bi bi-people me-2"></i>Customer List Report</a></li>
                    </ul>
                </li>

                <!-- EC -->
                <li class="nav-item">
                    <a class="nav-link" href="export-stbno.php">
                        <i class="fas fa-file-export"></i>EC
                    </a>
                </li>

                <!-- Action Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="actionDrop" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bolt"></i>Action
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="actionDrop">
                        <li><a class="dropdown-item" href="admin-bill-credit.php">Credit Bill</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="admin-bill-cancel.php">Cancel Bill</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="pos-bill-cancel.php">POS Bill Cancel</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="incomeExpenceAction.php">Add Income/Expense</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="pos-product.php">Manage POS Product</a></li>
                    </ul>
                </li>

                <!-- Action Links -->
                <li class="nav-item">
                    <a class="nav-link" href="admin-activity.php"><i class="fas fa-history"></i>Activity</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.php"><i class="fas fa-user-circle"></i>Profile</a>
                </li>
            </ul>

            <!-- Right Side Controls -->
            <div class="d-flex align-items-center gap-3">
                <div class="d-none d-lg-block text-end me-2">
                    <div class="small fw-bold user-info-text"><?= htmlspecialchars($session_user) ?></div>
                    <div class="small user-date-text" style="font-size: 0.75rem;">
                        <span id="navbarDate"><?= date("d M Y") ?></span> 
                        <span class="mx-1 opacity-50">|</span> 
                        <span id="navbarClock" class="fw-bold text-warning"><?= date("h:i:s A") ?></span>
                    </div>
                </div>

                <div class="nav-btn-group d-flex gap-2">
                    <button type="button" class="btn btn-outline-warning-custom" data-bs-toggle="modal" data-bs-target="#globalCalcModal">
                        <i class="fas fa-calculator"></i>
                    </button>
                    <a href="app-settings.php" class="btn btn-outline-info-custom">
                        <i class="fas fa-cog"></i> <span class="d-none d-md-inline">Settings</span>
                    </a>
                    <a href="logout.php" class="btn btn-outline-danger-custom">
                        <i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Global Calculator Modal -->
<div class="modal fade" id="globalCalcModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="false">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content casio-modal">
            <div class="modal-header border-0 pb-0 pt-3 px-4">
                <div class="ms-1 calc-brand">CASIO <span style="font-size: 0.7rem; opacity: 0.7;">FX-GLOBAL</span></div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-1 px-4 pb-4">
                <div class="casio-display-container">
                    <input type="text" class="form-control calc-display" id="gCalcDisplay" readonly value="0">
                </div>
                <div class="row g-2">
                     <!-- Row 1 -->
                    <div class="col-3"><button class="btn w-100 calc-btn btn-clear" onclick="gCalcClear()">AC</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-op" onclick="gCalcBackspace()">DEL</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-op" onclick="gCalcAppend('%')">%</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-op" onclick="gCalcAppend('/')">÷</button></div>
                    
                    <!-- Row 2 -->
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('7')">7</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('8')">8</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('9')">9</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-op" onclick="gCalcAppend('*')">×</button></div>
                    
                    <!-- Row 3 -->
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('4')">4</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('5')">5</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('6')">6</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-op" onclick="gCalcAppend('-')">-</button></div>
                    
                    <!-- Row 4 -->
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('1')">1</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('2')">2</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('3')">3</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-op" onclick="gCalcAppend('+')">+</button></div>
                    
                    <!-- Row 5 -->
                    <div class="col-6"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('0')">0</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-num" onclick="gCalcAppend('.')">.</button></div>
                    <div class="col-3"><button class="btn w-100 calc-btn btn-equal" onclick="gCalcCalculate()">=</button></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global Calculator Functions
    var calcModalEl = document.getElementById('globalCalcModal');
    
    // Auto-Reset on Close
    if(calcModalEl) {
        calcModalEl.addEventListener('hidden.bs.modal', function () {
            gCalcClear();
        });
        
        // Keyboard Support
        document.addEventListener('keydown', function(event) {
            // Only if modal is open
            if(calcModalEl.classList.contains('show')) {
                const key = event.key;
                
                // Numbers
                if(/[0-9]/.test(key) && key.length === 1) {
                    gCalcAppend(key);
                } 
                // Operators
                else if(key === '+' || key === '-' || key === '*' || key === '/') {
                    gCalcAppend(key);
                } 
                else if(key === '.') {
                    gCalcAppend('.');
                }
                // Enter / Equal
                else if(key === 'Enter' || key === '=') {
                    event.preventDefault(); // Stop form submit etc
                    gCalcCalculate();
                }
                // Backspace
                else if(key === 'Backspace') {
                    gCalcBackspace();
                }
                // Esc
                else if(key === 'Escape') {
                    // Modal handles close automatically, listener handles clear
                }
            }
        });
    }

    function gCalcAppend(val) {
        const display = document.getElementById('gCalcDisplay');
        if(!display) return;
        if(display.value === '0' && val !== '.') display.value = val;
        else display.value += val;
    }
    function gCalcClear() {
        const display = document.getElementById('gCalcDisplay');
        if(display) display.value = '0';
    }
    function gCalcBackspace() {
        const display = document.getElementById('gCalcDisplay');
        if(!display) return;
        display.value = display.value.slice(0, -1);
        if(display.value === '') display.value = '0';
    }
    function gCalcCalculate() {
        const display = document.getElementById('gCalcDisplay');
        if(!display) return;
        try {
            // Using Function constructor for safer eval alternative
            // replace x with * just in case, though we used *
            display.value = new Function('return ' + display.value)();
        } catch {
            display.value = 'Error';
            setTimeout(() => display.value = '0', 1000);
        }
    }

    function goBack() {
        window.history.back();
    }

    function updateNavbarClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
        // Format date manually or use logic, but existing date("d M Y") form PHP is fine for initial. 
        // We will just update time dynamically
        const clockEl = document.getElementById('navbarClock');
        if(clockEl) clockEl.textContent = timeString;
    }
    
    // Update every second
    setInterval(updateNavbarClock, 1000);
    // Initial call
    updateNavbarClock();
</script>

<?php
} else {
    // If not included in a valid session page, this might be viewed directly or session expired
    // The parent page often handles redirect, but we can do a js redirect if needed or just show nothing.
    // Preserving logic to just not show content.
    // header("Location: index.php"); // Avoiding header calls in partials if output already sent
}
?>
