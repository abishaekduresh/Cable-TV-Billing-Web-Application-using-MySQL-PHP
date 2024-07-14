<?php
require_once("../../dbconfig.php");
require_once("../../component.php");

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

// Extract data
$username = validateInput($data['username']);
$old_password = validateInput($data['old_password']);
$new_password = validateInput($data['new_password']);
$confirm_password = validateInput($data['confirm_password']);

if (!empty($username) && !empty($old_password) && !empty($new_password)) {

    // Check if passwords match
    if ($new_password === $confirm_password) {

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

                // Verify old password
                $hashed_old_password = md5($old_password);
                $stmt = $con->prepare("SELECT username FROM user WHERE username = ? AND password = ?");
                $stmt->bind_param("ss", $username, $hashed_old_password);
                if ($stmt->execute()) {

                    // Check if the old password is correct
                    if ($stmt->fetch()) {

                        // Close statement
                        $stmt->close();

                        // Update password
                        $hashed_password = md5($new_password);
                        $stmt = $con->prepare("UPDATE user SET password = ? WHERE username = ?");
                        $stmt->bind_param("ss", $hashed_password, $username);
                        if ($stmt->execute()) {
                            $data = array(
                                "status" => "success",
                                "message" => "Password Updated...",
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
                        // Incorrect old password
                        $data = array(
                            "status" => "failed",
                            "message" => "Incorrect Old Password",
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
        // Password mismatch
        $data = array(
            "status" => "failed",
            "message" => "Password Mismatch",
            "code" => "03"
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
