<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

$product_id = isset($data['product_id']) ? validateInput($data['product_id']) : null;
// $billing_type = isset($data['billing_type']) ? validateInput($data['billing_type']) : null;
$zero = 0;

if ($product_id != null) { // Check if $product_id is not null
    $query = "SELECT * FROM pos_product WHERE pos_product_id = ? AND stock > ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $product_id, $zero);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No data found for product ID: ' . $product_id]);
    }
} else {
    echo json_encode(['error' => 'Product ID is null']);
}
?>
