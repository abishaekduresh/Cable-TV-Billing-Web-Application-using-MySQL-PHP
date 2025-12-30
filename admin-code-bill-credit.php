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
$bill_no = $_POST['bill_no'] ?? null;
$stbNo = $_POST['stbno'] ?? null;
$remark2Input = trim($_POST['remark2'] ?? '');

// Validate required fields
if (!$selectedValue || !$bill_no) {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
    exit;
}

// Validate remark2 length
if (empty($remark2Input) || strlen($remark2Input) < 4) {
    echo json_encode(["status" => "error", "message" => "Remark is required and must be at least 4 characters"]);
    exit;
}
if (strlen($remark2Input) > 30) {
    echo json_encode(["status" => "error", "message" => "Remark cannot exceed 30 characters"]);
    exit;
}

// Get session username
$usernamePrefix = $_SESSION['username'] ?? "Unknown User";

// Fetch current pMode
$stmtOld = $con->prepare("SELECT pMode FROM bill WHERE bill_id = ?");
$stmtOld->bind_param("i", $bill_no);
$stmtOld->execute();
$stmtOld->bind_result($oldPmode);
$stmtOld->fetch();
$stmtOld->close();
if (!$oldPmode) $oldPmode = "N/A";

// Build final remark
$finalRemark = $usernamePrefix . " - Changed payment mode from " . $oldPmode . " to " . $selectedValue . " | CREDIT: " . $remark2Input;

// Update bill with remark2 and pMode
$stmt = $con->prepare("UPDATE bill SET pMode = ?, remark2 = ? WHERE bill_id = ?");
$stmt->bind_param("ssi", $selectedValue, $finalRemark, $bill_no);
$updateResult = $stmt->execute();

// Prepare log message
$logMessage = $usernamePrefix . " updated bill for STB: $stbNo | Bill No: $bill_no | Payment mode changed from $oldPmode to $selectedValue | CREDIT: $remark2Input";

if ($updateResult) {
    if (isset($_SESSION['id'])) {
        logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], $logMessage);
    }
    echo json_encode([
        "status" => "success",
        "message" => "Payment mode updated to: $selectedValue",
        "data" => [
            "bill_no" => $bill_no,
            "stbNo" => $stbNo,
            "old_status" => $oldPmode,
            "new_status" => $selectedValue,
            "remark" => $finalRemark
        ]
    ]);
} else {
    if (isset($_SESSION['id'])) {
        logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'], "Failed to update bill for STB: $stbNo | Bill No: $bill_no | Attempted change from $oldPmode to $selectedValue");
    }
    echo json_encode(["status" => "error", "message" => "Failed to update payment mode"]);
}
exit;
?>
