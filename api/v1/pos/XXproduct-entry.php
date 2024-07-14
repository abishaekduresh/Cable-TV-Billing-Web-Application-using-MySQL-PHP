<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

// Extract and validate data
$username = isset($data['username']) ? validateInput($data['username']) : 'Auto';
$product_name = isset($data['product_name']) ? validateInput($data['product_name']) : 'NULL';
$r_price = isset($data['r_price']) ? validateInput($data['r_price']) : '99';
$hs_price = isset($data['hs_price']) ? validateInput($data['hs_price']) : '33';
$qty = isset($data['qty']) ? validateInput($data['qty']) : '999';

if ($username && $product_name && $r_price && $hs_price && $qty) {

    // Check if product already exists
    $stmt = $con->prepare("SELECT product_name FROM pos_product WHERE product_name = ?");
    $stmt->bind_param("s", $product_name);

    if ($stmt->execute()) {
        $stmt->bind_result($fetched_product_name);
        $stmt->fetch();
        $stmt->close();

        if (!$fetched_product_name) {
            // Insert new product
            $stmt = $con->prepare("INSERT INTO pos_product (username, product_name, r_price, hs_price, qty) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $username, $product_name, $r_price, $hs_price, $qty);

            if ($stmt->execute()) {
                $data = array(
                    "status" => "success",
                    "message" => "Product Added Successfully",
                    "code" => "200",
                );
                echo json_encode($data);
            } else {
                $data = array(
                    "status" => "failed",
                    "message" => "Error: " . $stmt->error,
                    "code" => "02"
                );
                echo json_encode($data);
            }
            $stmt->close();
        } else {
            // Product already exists
            $data = array(
                "status" => "failed",
                "message" => "Product Already Available",
                "code" => "02"
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

// Close connection
$con->close();
?>