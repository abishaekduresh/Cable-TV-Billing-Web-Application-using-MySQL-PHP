<?php

// Set content type header to application/json
header('Content-Type: application/json');

require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Function to sanitize input
function sanitizeInput($input) {
    // Add your sanitization logic here
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize input
    $product_name = isset($data['product_name']) ? sanitizeInput($data['product_name']) : null;
    $r_price = isset($data['r_price']) ? sanitizeInput($data['r_price']) : null;
    $hs_price = isset($data['hs_price']) ? sanitizeInput($data['hs_price']) : null;
    $stock = isset($data['stock']) ? sanitizeInput($data['stock']) : null;
    $pos_product_id = isset($data['product_id']) ? sanitizeInput($data['product_id']) : null;
    
    // Prepare and execute SQL query
    $stmt = $con->prepare("UPDATE pos_product SET product_name = ?, r_price = ?, hs_price = ?, stock = ? WHERE pos_product_id = ?");
    $stmt->bind_param("sssss", $product_name, $r_price, $hs_price, $stock, $pos_product_id);
    
    if ($stmt->execute()) {
        // Prepare response data
        $response = array(
            "status" => "success",
            "message" => "Product updated successfully",
            "code" => 200 // Changed to integer
        );
        echo json_encode($response);
    } else {
        // Error occurred while executing query
        $response = array(
            "status" => "failed",
            "message" => "Error: " . $stmt->error,
            "code" => 500 // Changed error code to 500
        );
        echo json_encode($response);
    }
    
    $stmt->close();
} else {
    // HTTP method other than POST not allowed
    $response = array(
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => 405 // Changed error code to 405 (Method Not Allowed)
    );
    echo json_encode($response);
} 

$con->close();

?>
