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
$bill_no = $_POST['bill_id'] ?? null;
$stbNo = $_POST['stbno'] ?? null;
$date = $_POST['date'] ?? null;
$name = $_POST['name'] ?? null;
$billNo = $_POST['billNo'] ?? null;
$due_month_timestamp = $_POST['due_month_timestamp'] ?? null;
$pMode = $_POST['pMode'] ?? null;
$phone = $_POST['phone'] ?? null;
$remark2Input = trim($_POST['remark2'] ?? '');

// Validate required fields
if (!$selectedValue || !$bill_no) {
    echo json_encode(["status" => "error", "message" => "Missing required data"]);
    exit;
}

// Validate remark2
if (empty($remark2Input) || strlen($remark2Input) < 4) {
    echo json_encode(["status" => "error", "message" => "Remark2 is required and must be at least 4 characters"]);
    exit;
}

if (strlen($remark2Input) > 30) {
    echo json_encode(["status" => "error", "message" => "Remark2 cannot exceed 30 characters"]);
    exit;
}

// Get session username
$usernamePrefix = $_SESSION['username'] ?? "Unknown User";

// Fetch old status and existing remark2
$stmtOld = $con->prepare("SELECT status, remark2 FROM bill WHERE bill_id = ?");
$stmtOld->bind_param("i", $bill_no);
$stmtOld->execute();
$stmtOld->bind_result($oldStatus, $existingRemark2);
$stmtOld->fetch();
$stmtOld->close();

if (!$oldStatus) $oldStatus = "N/A";
if (!$existingRemark2) $existingRemark2 = "";

// Build final remark (Append to existing)
$newLog = $usernamePrefix . " - Changed bill status from " . $oldStatus . " to " . $selectedValue . " | CANCEL " . $remark2Input;

// If existing remark is not empty, append with separator
if (!empty($existingRemark2)) {
    $finalRemark = $existingRemark2 . " || " . $newLog;
} else {
    $finalRemark = $newLog;
}

// Update bill status and remark2
$stmt = $con->prepare("UPDATE bill SET status = ?, remark2 = ? WHERE bill_id = ?");
$stmt->bind_param("ssi", $selectedValue, $finalRemark, $bill_no);
$updateResult = $stmt->execute();

if (!$updateResult) {
    echo json_encode(["status" => "error", "message" => "Failed to update bill status or remark2"]);
    exit;
}

// Update in_ex table
$currentTime = date('H:i:s');
$sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$date' AND status = 'approve'";
$result = $con->query($sqlSum);
$sumPaidAmount = 0;
if ($result) {
    $row = $result->fetch_assoc();
    $sumPaidAmount = $row['total_Rs'] ?? 0;
}

$sqlCheck = "SELECT * FROM in_ex WHERE date='$date' AND category_id=12 AND subcategory_id=35 AND status=1";
$resultCheck = $con->query($sqlCheck);
if ($resultCheck->num_rows > 0) {
    $sqlUpdate = "UPDATE in_ex SET type='Income', time='$currentTime', username='Auto', remark='', amount='$sumPaidAmount' 
                  WHERE date='$date' AND category_id=12 AND subcategory_id=35 AND status=1";
    $con->query($sqlUpdate);
} else {
    $sqlInsert = "INSERT INTO in_ex (type,date,time,username,category_id,subcategory_id,remark,amount,status) 
                  VALUES ('Income','$date','$currentTime','Auto',12,35,'','$sumPaidAmount',1)";
    $con->query($sqlInsert);
}

// Log user activity
if (isset($_SESSION['id'])) {
    logUserActivity($_SESSION['id'], $_SESSION['username'], $_SESSION['role'],
        "Bill $stbNo status changed from $oldStatus to $selectedValue | $finalRemark");
}

// Send SMS
$sms_res = send_INDIV_BILL_SMS($name, $phone, $billNo, $due_month_timestamp, $stbNo, $pMode, $selectedValue);
$sms_res_array = json_decode($sms_res, true);

// Make sure JSON decoding succeeded
$sms_res_array_status = $sms_res_array['status'] ?? null;
$sms_res_array_message = $sms_res_array['message'] ?? 'SMS sent success';

if (isset($_SESSION['id']) && $sms_res) {
    // Get the user information before destroying the session
    $userId = $_SESSION['id'];
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];

    // Use parentheses to fix ternary precedence
    $action = "Indiv Bill Cancel SMS notify Status: " . 
              ($sms_res_array_status ? 'SMS: sent success' : $sms_res_array_message) . 
              "|" . $phone . "-" . $stbNo . "-" . $sms_res_array_status;

    // Call the function to insert user activity log
    logUserActivity($userId, $username, $role, $action);
}

// Return success JSON
echo json_encode([
    "status" => "success",
    "message" => "Bill status updated to $selectedValue",
    "data" => [
        "billNo" => $billNo,
        "stbNo" => $stbNo,
        "name"  => $name,
        "oldStatus" => $oldStatus,
        "newStatus" => $selectedValue,
        "remark2" => $finalRemark
    ]
]);
exit;
?>
