<?php
session_start();
require "dbconfig.php";
require "component.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
    exit;
}

// Get POST values
$selectedValue = $_POST['selectedValue'] ?? null;
// Use unique identifier if possible, fallback to group_id + date combo if that's how it's keyed
$group_id = $_POST['group_id'] ?? null;
$date = $_POST['date'] ?? null;
$id = $_POST['id'] ?? null; // ID if available
$remark2Input = trim($_POST['remark2'] ?? '');

// Validate required fields
if (!$selectedValue || (!$group_id && !$id)) {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
    exit;
}

// Validate remark length (consistent with admin-code-bill-credit.php)
if (empty($remark2Input) || strlen($remark2Input) < 4) {
    echo json_encode(["status" => "error", "message" => "Remark is required and must be at least 4 characters"]);
    exit;
}

// Get session username
$usernamePrefix = $_SESSION['username'] ?? "Unknown User";

// Fetch current info for logging
if ($id) {
    $stmtOld = $con->prepare("SELECT pMode, groupName, date FROM billgroupdetails WHERE id = ?");
    $stmtOld->bind_param("i", $id);
} else {
    $stmtOld = $con->prepare("SELECT pMode, groupName, date FROM billgroupdetails WHERE group_id = ? AND date = ?");
    $stmtOld->bind_param("ss", $group_id, $date);
}
$stmtOld->execute();
$stmtOld->bind_result($oldPmode, $groupName, $billDate);
$stmtOld->fetch();
$stmtOld->close();

if (!$oldPmode) $oldPmode = "N/A";

// Build final log remark (since we don't have a remark column in DB table)
$finalRemark = "Changed payment mode from " . $oldPmode . " to " . $selectedValue . " | CREDIT: " . $remark2Input;

// Update billgroupdetails
// Note: We are updating pMode. 'status' usually implies 'approve' vs 'pending', but check if we need to set status='approve' too.
// admin-groupBill-credit.php filters by status='approve' AND pMode='credit'. So we keep status='approve'.
if ($id) {
    $stmt = $con->prepare("UPDATE billgroupdetails SET pMode = ? WHERE id = ?");
    $stmt->bind_param("si", $selectedValue, $id);
} else {
    $stmt = $con->prepare("UPDATE billgroupdetails SET pMode = ? WHERE group_id = ? AND date = ?");
    $stmt->bind_param("sss", $selectedValue, $group_id, $date);
}

$updateResult = $stmt->execute();

// Prepare log message
$logMessage = $usernamePrefix . " updated Group Bill ($groupName - $billDate) | $finalRemark";

if ($updateResult) {
    if (isset($_SESSION['id'])) {
        logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], $logMessage);
    }
    echo json_encode([
        "status" => "success",
        "message" => "Payment mode updated to: $selectedValue",
        "data" => [
            "group_id" => $group_id,
            "old_status" => $oldPmode,
            "new_status" => $selectedValue
        ]
    ]);
} else {
    if (isset($_SESSION['id'])) {
        logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], "Failed to update Group Bill ($groupName) | " . $finalRemark);
    }
    echo json_encode(["status" => "error", "message" => "Failed to update payment mode"]);
}
exit;
?>
