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
    
    // Prepare and execute SQL query
    $stmt = $con->prepare("SELECT * FROM pos_product WHERE product_name LIKE ? LIMIT 25");
    $product_name = $product_name . "%"; // Add wildcards for the LIKE clause
    $stmt->bind_param("s", $product_name);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $products = []; 
        
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }

        // Prepare response data
        $response = array(
            "status" => "success",
            "message" => "Products retrieved successfully",
            "code" => "200", // Changed to integer
            "data" => $products,
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
