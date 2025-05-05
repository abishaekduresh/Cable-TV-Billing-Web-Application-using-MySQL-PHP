<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

// Get the search query from the request
$searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

// Prepare SQL based on query presence
if (!empty($searchQuery)) {
    $sql = "SELECT * FROM customer_area 
            WHERE customer_area_name LIKE ? 
               OR customer_area_code LIKE ?
            LIMIT 10"; // Fetch max 10 matching records
    $stmt = $con->prepare($sql);
    $param = "%$searchQuery%";
    $stmt->bind_param("ss", $param, $param);
} else {
    $sql = "SELECT * FROM customer_area LIMIT 5"; // Default limit 5
    $stmt = $con->prepare($sql);
}

// Execute query
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Close statement and connection
$stmt->close();
$con->close();

// Return JSON response
if (!empty($data)) {
    echo json_encode(["status" => true, "data" => $data]);
} else {
    echo json_encode(["status" => false, "message" => "No data found"]);
}
?>
