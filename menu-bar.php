<?php
if (session_status() === PHP_SESSION_NONE) {
    // Session should normally be started by parent page
}

include_once "dbconfig.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    $session_user = $_SESSION['name']; // Using 'name' as in original menu-bar.php checking

    // Fetch App Name
    $appName2 = "Billing App"; // Default
    $sql = "SELECT appName2 FROM settings LIMIT 1";
    $result = mysqli_query($con, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $appName2 = $row['appName2'];
    }
?>

<!-- Dependencies -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

    /* User Info & Buttons */
    .user-info-text {
        color: white;
    }
    .user-date-text {
        color: rgba(255,255,255,0.7);
    }

    .btn-outline-danger-custom {
        border: 1px solid rgba(239, 71, 111, 0.8);
        background: rgba(239, 71, 111, 0.15);
        color: #ffcccc;
    }
    .btn-outline-danger-custom:hover {
        background: var(--danger-color, #ef476f);
        color: white;
    }
    
    .navbar-toggler {
        border: 1px solid rgba(255,255,255,0.3);
        padding: 0.5rem;
    }
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }
</style>

<nav class="navbar navbar-expand-lg admin-navbar sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="employee-dashboard.php">
            <img src="https://files.catbox.moe/sepcbf.png" alt="Logo" height="40" class="me-2"><?= htmlspecialchars($appName2) ?>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#empNavbarContent" aria-controls="empNavbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="empNavbarContent">
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
                        <li><a class="dropdown-item" href="bill-filter-by-all.php">Indiv Bill by All</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rptgroupbill.php">Group Bill</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rptadvindivbill.php">Advance Indiv Bill</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="bill-filter-by-user.php">Bill by You</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="rptposinvoice.php">POS Report</a></li>
                    </ul>
                </li>

                <!-- EC -->
                <li class="nav-item">
                    <a class="nav-link" href="export-stbno.php">
                        <i class="fas fa-file-export"></i>EC
                    </a>
                </li>

                 <!-- Profile -->
                 <li class="nav-item">
                    <a class="nav-link" href="profile.php">
                        <i class="fas fa-user-circle"></i>Profile
                    </a>
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
                    <a href="logout.php" class="btn btn-outline-danger-custom">
                        <i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateNavbarClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', second: '2-digit', hour12: true });
        const clockEl = document.getElementById('navbarClock');
        if(clockEl) clockEl.textContent = timeString;
    }
    
    // Update every second
    setInterval(updateNavbarClock, 1000);
    // Initial call
    updateNavbarClock();

    function openBill_Export() {
        window.open('bill-export.php', '_blank', 'width=2000, height=750');
    }
</script>

<?php 
} else {
    // Redirect if session invalid
    header("Location: logout.php");
    exit();
} 
?>