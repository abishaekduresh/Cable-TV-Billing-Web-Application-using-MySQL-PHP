<?php
header('Content-Type: application/json');

// Error Handling: Hide errors from output, log them instead (or catch and JSON encode)
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Path Fix: Ensure we are pointing to root v2
// api/v1/admin/ -> ../../../ -> root
$dbPath = __DIR__ . '/../../../dbconfig.php';
if (!file_exists($dbPath)) {
    echo json_encode(['status' => false, 'message' => "dbconfig not found at $dbPath"]);
    exit;
}

require_once $dbPath;

session_start();
// Allow detailed errors for debugging if needed, but return as JSON
try {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        throw new Exception('Unauthorized');
    }

    $response = [
        'status' => true,
        'data' => []
    ];

    // --- 0. Date Filters ---
    // Accept startDate and endDate from GET request. Default to Today if not set.
    // However, for "Overview Cards", default is Today. 
    // For other charts, typically "This Month" was default.
    // To harmonize: The UI will send specific dates. 
    // If NO dates sent, we default to TODAY for specific stats, but arguably the UI should always drive this.
    // Let's implement: If params exist, use them. If not, default to Today for "Overview" and This Month for "Charts".
    // ACTUALLY, to make it consistent with the "Date Filter" concept, if no params, we'll default to TODAY for everything except trends/status.
    
    $startDate = isset($_GET['startDate']) ? $_GET['startDate'] : date('Y-m-d');
    $endDate = isset($_GET['endDate']) ? $_GET['endDate'] : date('Y-m-d');

    // --- 1. Period Overview (Replaces "Today's Overview") ---
    
    // --- 1. Period Overview (Replaces "Today's Overview") ---
    
    // 1a. Individual Collection (Strict 'approve' status matches todaycollection.php)
    $sqlIndiv = "SELECT COALESCE(SUM(paid_amount),0) as total FROM bill WHERE date BETWEEN '$startDate' AND '$endDate' AND status = 'approve'";
    $resIndiv = $con->query($sqlIndiv);
    if(!$resIndiv) throw new Exception("Indiv Query Failed: " . $con->error);
    $periodIndiv = (float)$resIndiv->fetch_assoc()['total'];

    // 1b. Group Collection (Strict 'approve' status matches rptgroupbill.php)
    $sqlGroup = "SELECT COALESCE(SUM(Rs),0) as total FROM billgroupdetails WHERE date BETWEEN '$startDate' AND '$endDate' AND status = 'approve'";
    $resGroup = $con->query($sqlGroup);
    if(!$resGroup) throw new Exception("Group Query Failed: " . $con->error);
    $periodGroup = (float)$resGroup->fetch_assoc()['total'];

    // 1c. POS Collection (Calculate from items matches rptposinvoice.php)
    // rptposinvoice.php sums (price*qty) from items then subtracts discount.
    // We strictly filter for status=1 (Approved) as per report default.
    $sqlPOS = "SELECT COALESCE(SUM((pbi.price * pbi.qty) - pb.discount), 0) as total 
               FROM pos_bill pb 
               JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id 
               WHERE DATE(pb.entry_timestamp) BETWEEN '$startDate' AND '$endDate' 
               AND pb.status = 1";
    $resPOS = $con->query($sqlPOS);
    if(!$resPOS) throw new Exception("POS Query Failed: " . $con->error);
    $periodPOS = (float)$resPOS->fetch_assoc()['total'];

    // 1d. Expenses
    $sqlExp = "SELECT COALESCE(SUM(amount),0) as total FROM in_ex WHERE type='Expense' AND status='1' AND date BETWEEN '$startDate' AND '$endDate'";
    $resExp = $con->query($sqlExp);
    if(!$resExp) throw new Exception("Expense Query Failed: " . $con->error);
    $periodExpense = (float)$resExp->fetch_assoc()['total'];

    $response['data']['periodOverview'] = [
        'indiv' => $periodIndiv,
        'group' => $periodGroup,
        'pos' => $periodPOS,
        'expense' => $periodExpense,
        'totalCollection' => $periodIndiv + $periodGroup + $periodPOS
    ];


    // --- 2. Revenue Trend (Dynamic: Daily or Monthly) ---
    // If range <= 31 days -> Daily Trend
    // If range > 31 days -> Monthly Trend
    
    $startDiff = new DateTime($startDate);
    $endDiff = new DateTime($endDate);
    $diffDays = $startDiff->diff($endDiff)->days;
    
    $revenueTrend = ['labels' => [], 'data' => []];

    if($diffDays <= 31) {
        // DAILY VIEW
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            (new DateTime($endDate))->modify('+1 day')
        );

        foreach ($period as $dt) {
            $currentDate = $dt->format('Y-m-d');
            $label = $dt->format('d M'); 
            
            $sql = "SELECT COALESCE(SUM(amount),0) as total FROM in_ex WHERE type='Income' AND status='1' AND date = '$currentDate'";
            $result = $con->query($sql);
            if(!$result) throw new Exception("Revenue Query Failed: " . $con->error);
            
            $row = $result->fetch_assoc();
            $revenueTrend['labels'][] = $label;
            $revenueTrend['data'][] = (float)$row['total'];
        }

    } else {
        // MONTHLY VIEW (Iterate by Month)
        // Adjust start to first day of month, end to last day of month for cleaner bins if desired,
        // or just iterate month by month covering the range.
        
        $current = new DateTime($startDate);
        $end = new DateTime($endDate);
        // Ensure we include the end month
        $end->modify('first day of next month'); 
        
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($current, $interval, $end);

        foreach ($period as $dt) {
            $monthStart = $dt->format('Y-m-01');
            $monthEnd = $dt->format('Y-m-t');
            
            // If it's the first month, constrain by startDate
            if($monthStart < $startDate) $monthStart = $startDate;
            // If it's the last month, constrain by endDate (logic tricky with loop, simplified: use full month or strict range?)
            // Let's use intersection with requested range for accuracy.
            
            // Actually, simpler loop approach for monthly report:
            // Just use full months for the buckets falling within range is standard, 
            // but let's stick to the exact range logic per bucket.
            
            // Let's go with Standard Monthly Buckets (Full Month Data) that overlap the range, 
            // OR strictly within range. User usually wants "Trend" so full month context is often better, 
            // but let's stick to rigorous filtering:
            
            $queryStart = $monthStart;
            $queryEnd = $monthEnd;
            
            // Clamp to selection
            if ($queryStart < $startDate) $queryStart = $startDate;
            if ($queryEnd > $endDate) $queryEnd = $endDate;

            if ($queryStart > $queryEnd) continue;

            $label = $dt->format('M Y');
            
            $sql = "SELECT COALESCE(SUM(amount),0) as total FROM in_ex WHERE type='Income' AND status='1' AND date BETWEEN '$queryStart' AND '$queryEnd'";
            $result = $con->query($sql);
            if(!$result) throw new Exception("Revenue Query Failed: " . $con->error);
            
            $row = $result->fetch_assoc();
            $revenueTrend['labels'][] = $label;
            $revenueTrend['data'][] = (float)$row['total'];
        }
    }
    
    $response['data']['revenueTrend'] = $revenueTrend;


    // --- 3. Collection Source (Selected Period) ---
    // Reuse period calculated values directly since logic is identical
    $response['data']['collectionSource'] = [
        'labels' => ['Individual', 'Group', 'POS'],
        'data' => [$periodIndiv, $periodGroup, $periodPOS]
    ];


    // --- 4. Payment Mode Analysis (Selected Period) ---
    $pModeStats = [];

    // 4a. Indiv
    $sqlPMIndiv = "SELECT pMode, 
                          SUM(CASE WHEN pMode = 'Credit' OR pMode = 'credit' THEN Rs ELSE paid_amount END) as total 
                   FROM bill 
                   WHERE date BETWEEN '$startDate' AND '$endDate' 
                   AND status = 'approve' 
                   GROUP BY pMode";
    $queryPMIndiv = $con->query($sqlPMIndiv);
    while($row = $queryPMIndiv->fetch_assoc()){
        $mode = ucfirst(strtolower($row['pMode']));
        if(!isset($pModeStats[$mode])) $pModeStats[$mode] = 0;
        $pModeStats[$mode] += (float)$row['total'];
    }

    // 4b. Group
    $sqlPMGroup = "SELECT pMode, SUM(Rs) as total FROM billgroupdetails WHERE date BETWEEN '$startDate' AND '$endDate' AND status = 'approve' GROUP BY pMode";
    $queryPMGroup = $con->query($sqlPMGroup);
    while($row = $queryPMGroup->fetch_assoc()){
        $mode = ucfirst(strtolower($row['pMode']));
        if(!isset($pModeStats[$mode])) $pModeStats[$mode] = 0;
        $pModeStats[$mode] += (float)$row['total'];
    }

    // 4c. POS
    // Note: Must use join logic for 'amount' here too? 
    // The previous POS query used 'amount - discount'.
    // To be perfectly accurate, we should join items here too, but GROUP BY complicates it.
    // However, the SUM(pbi.price * pbi.qty) logic is per-bill.
    // If we group by pay_mode, we can join:
    $sqlPMPOS = "SELECT pm.name as pMode, 
                        SUM((pbi.price * pbi.qty) - pb.discount) as total 
                 FROM pos_bill pb 
                 JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
                 JOIN pay_mode pm ON pb.pay_mode = pm.pay_mode_id 
                 WHERE DATE(pb.entry_timestamp) BETWEEN '$startDate' AND '$endDate' 
                 AND pb.status = 1 
                 GROUP BY pm.name";
    $queryPMPOS = $con->query($sqlPMPOS);
    if($queryPMPOS){
        while($row = $queryPMPOS->fetch_assoc()){
             $mode = ucfirst(strtolower($row['pMode']));
             if(!isset($pModeStats[$mode])) $pModeStats[$mode] = 0;
             $pModeStats[$mode] += (float)$row['total'];
        }
    }

    $response['data']['paymentMode'] = [
        'labels' => array_keys($pModeStats),
        'data' => array_values($pModeStats)
    ];


    // --- 5. Income vs Expense (Selected Period) ---
    $totalIncomePeriod = $periodIndiv + $periodGroup + $periodPOS;
    $totalExpensePeriod = $periodExpense;

    $response['data']['incomeVsExpense'] = [
        'labels' => ['Income', 'Expense'],
        'data' => [$totalIncomePeriod, $totalExpensePeriod]
    ];


    // --- 6. Customer Status (Existing) ---
    $sqlActive = "SELECT COUNT(*) as count FROM customer WHERE rc_dc='1'";
    $sqlInactive = "SELECT COUNT(*) as count FROM customer WHERE rc_dc='0'";
    $resActive = $con->query($sqlActive);
    $resInactive = $con->query($sqlInactive);
    $activeCount = (int)$resActive->fetch_assoc()['count'];
    $inactiveCount = (int)$resInactive->fetch_assoc()['count'];
    
    $response['data']['customerStatus'] = [
        'labels' => ['Active', 'Inactive'],
        'data' => [$activeCount, $inactiveCount]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode(['status' => false, 'message' => $e->getMessage()]);
}
