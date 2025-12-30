<?php 
   // Expecting session to be started by parent page
   if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
?>

<style>
    .minimal-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        background: #fff;
        border: 1px solid #e3e6f0;
        border-radius: 50px; /* Pill shape for modern minimal look */
        padding: 0.4rem 1rem;
        color: #5a5c69;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.2s;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        height: 100%;
    }

    .minimal-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        color: #4e73df;
        border-color: #4e73df;
    }

    .minimal-action-btn i {
        font-size: 1rem;
    }

    /* Color accents on hover or always */
    .btn-accent-primary:hover { color: #4e73df; border-color: #4e73df; background: #f8f9fc; }
    .btn-accent-success:hover { color: #1cc88a; border-color: #1cc88a; background: #f0fdf4; }
    .btn-accent-warning:hover { color: #f6c23e; border-color: #f6c23e; background: #fffcf0; }
    .btn-accent-danger:hover { color: #e74a3b; border-color: #e74a3b; background: #fef2f2; }
    .btn-accent-info:hover { color: #36b9cc; border-color: #36b9cc; background: #f0faff; }

    .section-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #aaa;
        margin-right: 1rem;
        white-space: nowrap;
        align-self: center;
        font-weight: 700;
    }

    .action-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        padding: 0.5rem 0;
        align-items: center;
    }
</style>

<div class="container-fluid mb-2">
    
    <!-- Row 1: Group Billing Actions -->
    <div class="action-row mb-2 border-bottom pb-2">
        <div class="section-label"><i class="bi bi-collection-fill me-1"></i> Group</div>
        
        <a href="billing-group-dashboard.php?group_id=select" class="minimal-action-btn btn-accent-primary">
            <i class="bi bi-plus-circle-fill text-primary"></i> New
        </a>
        
        <a href="pos-billing.php" class="minimal-action-btn btn-accent-primary">
            <i class="bi bi-cart-fill text-primary"></i> POS
        </a>

        <a href="rptgroupbill.php" class="minimal-action-btn btn-accent-success">
            <i class="bi bi-bar-chart-fill text-success"></i> Report
        </a>

        <a href="groupaction.php" class="minimal-action-btn btn-accent-info">
            <i class="bi bi-pencil-square text-info"></i> Manage
        </a>

        <a href="admin-groupBill-credit.php" class="minimal-action-btn btn-accent-warning">
            <i class="bi bi-credit-card-2-front-fill text-warning"></i> Credit
        </a>

        <a href="admin-groupBill-cancel.php" class="minimal-action-btn btn-accent-danger">
            <i class="bi bi-x-circle-fill text-danger"></i> Cancel
        </a>
        
        <a href="loc/dashboard.php?page=new-bill" class="minimal-action-btn btn-accent-warning">
            <i class="bi bi-geo-alt-fill text-warning"></i> LOC
        </a>

        <a href="IndivDuplicateBill.php" class="minimal-action-btn btn-accent-info">
            <i class="bi bi-files text-info"></i> Duplicate
        </a>
    </div>

    <!-- Row 2: Individual Billing Actions -->
    <div class="action-row">
        <div class="section-label"><i class="bi bi-person-fill me-1"></i> Indiv</div>

        <a href="billing-dashboard.php" class="minimal-action-btn btn-accent-primary">
            <i class="bi bi-person-plus-fill text-primary"></i> New
        </a>

        <a href="customer-history.php" class="minimal-action-btn btn-accent-primary">
            <i class="bi bi-clock-history text-primary"></i> History
        </a>

        <a href="income-expenses.php" class="minimal-action-btn btn-accent-primary">
            <i class="bi bi-wallet2 text-primary"></i> In/Ex
        </a>

        <a href="admin-bill-filter-by-all.php" class="minimal-action-btn btn-accent-success">
            <i class="bi bi-receipt text-success"></i> Report
        </a>

        <a href="todaycollection.php" class="minimal-action-btn btn-accent-success">
            <i class="bi bi-cash-stack text-success"></i> Collection
        </a>
        
        <a href="customer-details.php" class="minimal-action-btn btn-accent-info">
            <i class="bi bi-person-lines-fill text-info"></i> Cust. Action
        </a>

        <a href="admin-bill-credit.php" class="minimal-action-btn btn-accent-warning">
            <i class="bi bi-credit-card-2-back-fill text-warning"></i> Credit
        </a>

        <a href="admin-bill-cancel.php" class="minimal-action-btn btn-accent-danger">
            <i class="bi bi-x-circle-fill text-danger"></i> Cancel
        </a>
    </div>

</div>

<?php 
   } // End Session Check
   else {
       if (!isset($_SESSION['username'])) {
           header("Location: index.php");
       }
   }
?>