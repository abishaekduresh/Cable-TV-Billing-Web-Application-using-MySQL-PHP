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
    $channel_uid = validateInput($data['channel_uid']);
    $prop_phone = validateInput($data['phone']);
    $status = 1;

    if ($channel_uid) {
        // Step 1: Select data from Table A (loc_channels)
        $stmtA = $con->prepare("SELECT * FROM loc_channels WHERE channel_uid = ? AND prop_phone = ? AND status = ?");
        $stmtA->bind_param("ssi", $channel_uid, $prop_phone, $status);
        $stmtA->execute();
        $resultA = $stmtA->get_result();

        // Fetch all the rows into an array at once
        $rows = $resultA->fetch_all(MYSQLI_ASSOC);

        // Check if rows are returned
        if (count($rows) > 0) {
            // Accessing the data
            /*foreach ($rows as $row) {
                // Access individual columns for each row
                $channel_uid = $row['channel_uid'];
                $prop_phone = $row['prop_phone'];
            }*/

            // Success message
            $data = array(
                "status" => "true",
                "message" => "Login successfully",
                /*"data" => array(
                    "channel_uid" => $channel_uid,
                    "prop_phone" => $prop_phone
                ),*/
				"data" => $rows,
                "code" => "200"
            );
            echo json_encode($data);

        } else {
            // No rows returned
            $data = array(
                "status" => "false",
                "message" => "No matching records found",
                "code" => "404"
            );
            echo json_encode($data);
        }
        $stmtA->close();
    } else {
        // Missing fields
        $data = array(
            "status" => "false",
            "message" => "Some Fields are Empty",
            "code" => "404"
        );
        echo json_encode($data);
    }

} else {
    // Method not allowed
    $data = array(
        "status" => "false",
        "message" => "Method Not Allowed",
        "code" => "0xx"
    );
    echo json_encode($data);
}

// Close connection
$con->close();
?>
