<?php

// Set content type header to application/json
header('Content-Type: application/json');

require_once("../../../dbconfig.php");
require_once("../../../component.php");

// if($_SERVER["REQUEST_METHOD"] == "GET"){
//         // Retrieve JSON data
//     $data = json_decode(file_get_contents('php://input'), true);

//     // Sanitize input
//     $product_name = isset($data['product_name']) ? sanitizeInput($data['product_name']) : null;
    
//     // Prepare and execute SQL query
//     $stmt = $con->prepare("SELECT * FROM pos_product WHERE product_name LIKE ? LIMIT 25");
//     $product_name = "%" . $product_name . "%"; // Add wildcards for the LIKE clause
//     $stmt->bind_param("s", $product_name);
    
//     if ($stmt->execute()) {
//         $result = $stmt->get_result();
//         // $products = []; // Initialize an empty array to store products
        
//         // Fetch each row individually and add it to the products array
//         while ($row = $result->fetch_assoc()) {
//             $products[] = $row;
//         }

//         // Prepare response data
//         $response = array(
//             "status" => "success",
//             "message" => "Products retrieved successfully",
//             "code" => "200", // Changed to integer
//             "data" => $products,
//         );
//         echo json_encode($response);
//     } else {
//         // Error occurred while executing query
//         $response = array(
//             "status" => "failed",
//             "message" => "Error: " . $stmt->error,
//             "code" => 500 // Changed error code to 500
//         );
//         echo json_encode($response);
//     }
    
//     $stmt->close();

// } else
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract and validate data
    $username = isset($data['username']) ? validateInput($data['username']) : 'Auto';
    $product_name = isset($data['product_name']) ? validateInput($data['product_name']) : null;
    $r_price = isset($data['r_price']) ? validateInput($data['r_price']) : null;
    $hs_price = isset($data['hs_price']) ? validateInput($data['hs_price']) : null;
    $stock = isset($data['stock']) ? validateInput($data['stock']) : null;

    if ($username && $product_name && $r_price && $hs_price && $stock) {
    
        // Check if product already exists
        $stmt = $con->prepare("SELECT product_name FROM pos_product WHERE product_name = ?");
        $stmt->bind_param("s", $product_name);

        if ($stmt->execute()) {
            $stmt->bind_result($fetched_product_name);
            $stmt->fetch();
            $stmt->close();

            if (!$fetched_product_name) {
                // Insert new product
                $stmt2 = $con->prepare("INSERT INTO pos_product (entry_timestamp, username, product_name, r_price, hs_price, stock) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param("ssssss", $currentDateTime, $username, $product_name, $r_price, $hs_price, $stock);

                if ($stmt2->execute()) {
                    $data = array(
                        "status" => "success",
                        "message" => "Product Added Successfully",
                        "code" => "200",
                    );
                    echo json_encode($data);
                } else {
                    $data = array(
                        "status" => "failed",
                        "message" => "Error: " . $stmt2->error,
                        "code" => "02"
                    );
                    echo json_encode($data);
                }
                $stmt2->close();
            } else {
                // Product already exists
                $data = array(
                    "status" => "success",
                    "message" => "Product Already Available",
                    "code" => "201"
                );
                echo json_encode($data);
            }
        } else {
            // SQL execution error
            $data = array(
                "status" => "failed",
                "message" => "Error: " . $stmt->error,
                "code" => "02"
            );
            echo json_encode($data);
        }
    } else {
        // Missing fields
        $data = array(
            "status" => "failed",
            "message" => "Some Fields are Empty",
            "code" => "05"
        );
        echo json_encode($data);
    } 
} else {
    // Method not allowed
    $data = array(
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => "0xx"
    );
    echo json_encode($data);
}

// Close connection
$con->close();
?>
