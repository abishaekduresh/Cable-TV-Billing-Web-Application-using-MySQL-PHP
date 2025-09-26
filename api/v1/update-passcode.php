<?php
require_once("../../dbconfig.php");
require_once("../../component.php");

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

// Extract data
$username = validateInput($data['username']);
$update_passcode = validateInput($data['update_passcode']);

if (!empty($username) && !empty($update_passcode)) {
    // Prepare and bind
    $stmt = $con->prepare("SELECT username FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    // Execute SQL
    if ($stmt->execute()) {
        // Bind result variables
        $stmt->bind_result($fetched_username);
        // Fetch the result
        $stmt->fetch();
        // Close statement
        $stmt->close();
        // Check if username exists
        if ($fetched_username) {
            $stmt = $con->prepare("UPDATE user SET passcode = ? WHERE username = ?");
            $stmt->bind_param("ss", $update_passcode, $username);
            if ($stmt->execute()) {
                $data = array(
                    "status" => "success",
                    "message" => "Passcode Updated...",
                    "code" => "200",
                );
                echo json_encode($data);
            } else {
                $data = array(
                    "status" => "failed",
                    "message" => "Error: " . $con->error,
                    "code" => "02"
                );
                echo json_encode($data);
            }
        } else {
            // User not found
            $data = array(
                "status" => "failed",
                "message" => "User not found",
                "code" => "02"
            );
            echo json_encode($data);
        }
    } else {
        // SQL execution error
        $data = array(
            "status" => "failed",
            "message" => "Error: " . $con->error,
            "code" => "02"
        );
        echo json_encode($data);
    }
} else {
    // Empty fields
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
