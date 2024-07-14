<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// header('Access-Control-Allow-Origin: *');
$allowed_origins = [
    'https://cabletv.pdpgroups.in',
    // 'https://another-allowed-domain.com'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowed_origins)) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
}
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Check for JSON parse errors
if (json_last_error() !== JSON_ERROR_NONE) {
    $response = array(
        "status" => "failed",
        "message" => "JSON Error: " . json_last_error_msg(),
        "code" => "400"
    );
    echo json_encode($response);
    exit;
}

$subject = validateInput($data['subject']);
$feedback = validateInput($data['feedback']);
$rating = validateInput($data['rating']);

$requiredFields = ['subject', 'feedback', 'rating'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        $response = array(
            "status" => "failed",
            "message" => "The field '{$field}' is Empty",
            "code" => "405"
        );
        echo json_encode($response);
        exit;
    }
}

// Check connection
if ($con->connect_error) {
    $response = array(
        "status" => "failed",
        "message" => "Connection failed: " . $con->connect_error,
        "code" => "405"
    );
    echo json_encode($response);
    exit;
}

// Prepare the SQL statement
$stmt = $con->prepare("SELECT name, feedback, rc_dc FROM customer WHERE subject = ? AND feedback = ?");
if (!$stmt) {
    $response = array(
        "status" => "failed",
        "message" => "Prepare failed: " . $con->error,
        "code" => "405"
    );
    echo json_encode($response);
    exit;
}

// Bind parameters
if (!$stmt->bind_param("si", $subject, $feedback)) {
    $response = array(
        "status" => "failed",
        "message" => "Binding parameters failed: " . $stmt->error,
        "code" => "405"
    );
    echo json_encode($response);
    exit;
}

// Execute the statement
if (!$stmt->execute()) {
    $response = array(
        "status" => "failed",
        "message" => "Execute failed: " . $stmt->error,
        "code" => "405"
    );
    echo json_encode($response);
    exit;
}

// Bind the result variables
$stmt->bind_result($cus_name, $feedback, $rc_dc);

if($rc_dc === 0){
    $response = array(
        "status" => "success",
        "message" => "DC Customer",
        "code" => "201"
    );
    echo json_encode($response);
    exit;
}elseif($stmt->fetch()){
    $response = array(
        "status" => "success",
        "message" => "Login Scusses redirects in 2sec",
        "data" => array(
            "cus_name" => $cus_name,
            "feedback" => $feedback
        ),
        "code" => "200"
    );
    echo json_encode($response);
    exit;
}else{
    $response = array(
        "status" => "success",
        "message" => "Invalid STB Number or feedback Number",
        "code" => "201"
    );
    echo json_encode($response);
    exit;
}

// Close connection
$con->close();
?>
