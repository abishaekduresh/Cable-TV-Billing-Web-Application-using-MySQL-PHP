<?php

// Set content type header to application/json
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../../dbconfig.php");
require_once("../../../component.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract and validate data
    $updated_user_id = $created_user_id = validateInput($data['created_user_id']);
    $channel_name = validateInput($data['channel_name']);
    $prop_name = validateInput($data['prop_name']);
    $prop_phone = validateInput($data['prop_phone']);
    $prop_address = validateInput($data['prop_address']);
    $network_amount = validateInput($data['network_amount']);
    $remark = validateInput($data['remark']);
    $status = 1;

    if ($created_user_id && $channel_name) {
        $channel_uid = generateChannelUid();
    
        $stmt = $con->prepare("SELECT channel_uid FROM loc_channels WHERE channel_uid = ?");
        $stmt->bind_param("s", $channel_uid);

        if ($stmt->execute()) {
            $stmt->bind_result($fetched_product_name);
            $stmt->fetch();
            $stmt->close();

            if (!$fetched_product_name) {
                // Insert new product
                $updated_at = $currentDateTime; // Set last update time
                
                $stmt2 = $con->prepare("INSERT INTO loc_channels (created_at, created_user_id, channel_uid, channel_name, prop_name, prop_phone, prop_address, network_amount, remark, status, updated_at, updated_user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt2->bind_param("ssssssssssss", $currentDateTime, $created_user_id, $channel_uid, $channel_name, $prop_name, $prop_phone, $prop_address, $network_amount, $remark, $status, $updated_at, $updated_user_id);

                if ($stmt2->execute()) {
                    $data = array(
                        "status" => "success",
                        "message" => "New Channel Created Successfully",
						"channel uid" => $channel_uid, 
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
                    "message" => "Channel UID Already Available",
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
