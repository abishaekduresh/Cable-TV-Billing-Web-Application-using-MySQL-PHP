<?php

// Set content type header to application/json
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../../../dbconfig.php");
require_once("../../../../component.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Define required fields
    $required_fields = ['due_month_year', 'created_user_id'];
    $missing_fields = [];

    // Check if all required fields are present
    // foreach ($required_fields as $field) {
    //     if (!isset($data[$field]) || empty($data[$field])) {
    //         $missing_fields[] = $field;
    //     }
    // }
    foreach ($required_fields as $field) {
        // Use isset() to check if the field is set, and check for empty strings specifically
        if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
            $missing_fields[] = $field;
        }
    }

    if (empty($missing_fields)) {
        // All required fields are present, proceed with the logic
        // $response = array(
        //     "status" => "success",
        //     "message" => "Data received successfully",
        //     "data" => $data
        // );
        // echo json_encode($response);

// //////////////////////////
        $updated_user_id = $created_user_id = validateInput($data['created_user_id']);
        $due_month_year = validateInput($data['due_month_year']);
        list($due_year, $due_month) = explode('-', $due_month_year);
        $active_status = 1;
        $due_status = 0;
        $updated_at = $currentDateTime;

        $create_loc_gen_bills_log_array = create_loc_gen_bills_log($due_month, $due_year, $created_user_id, $active_status);
        if($create_loc_gen_bills_log_array['status'] === "false"){
            $response = array(
                "status" => "failed",
                "message" => $create_loc_gen_bills_log_array['message'],
                "loc_gen_bill_log_id" => isset($create_loc_gen_bills_log_array['loc_gen_bill_log_id'])?$create_loc_gen_bills_log_array['loc_gen_bill_log_id']:''
            );
            echo json_encode($response);
            exit();
        }else{

            $loc_gen_bill_log_id = $create_loc_gen_bills_log_array['loc_gen_bill_log_id'];

            $stmt = $con->prepare("SELECT loc_gen_bill_log_id FROM loc_gen_bills");
            $stmt->execute();
            $result = $stmt->get_result();
            
            $found = false; // To check if $loc_gen_bill_log_id exists in the results
            
            // Fetch all rows and compare each with $loc_gen_bill_log_id
            while ($row = $result->fetch_assoc()) {
                if ($row['loc_gen_bill_log_id'] == $loc_gen_bill_log_id) {
                    $found = true;
                    break;
                }
            }
            $stmt->close();
            $loc_gen_bill_id = isset($row['loc_gen_bill_log_id'])?$row['loc_gen_bill_log_id']:'0';

            if(!$found){

                $stmt = $con->prepare("SELECT channel_uid, network_amount, remark FROM loc_channels WHERE status = ?");
                $stmt->bind_param("i", $active_status);
                $stmt->execute();
                $result = $stmt->get_result();
    
                // Fetch all the rows into an array
                $rows = [];
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                $stmt->close();
    
                $stmt = $con->prepare("INSERT INTO loc_gen_bills 
                                        (created_at, created_user_id, loc_gen_bill_log_id, channel_uid, due_amount, 
                                            due_status, remark, status, updated_at, updated_user_id) 
                                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                foreach ($rows as $row) {
                    $stmt->bind_param("ssisdiissi", 
                        $currentDateTime, 
                        $created_user_id,
                        $loc_gen_bill_log_id, 
                        $row['channel_uid'], 
                        $row['network_amount'],
                        $due_status,
                        $row['remark'], 
                        $active_status, 
                        $updated_at, 
                        $updated_user_id
                    );
                    $stmt->execute();
                }
    
                $stmt->close();
    
                $response = array(
                    "status" => "success",
                    "message" => "loc_gen_bills Generated Successfully",
                    "code" => "200",
                    "loc_gen_bill_log_id" => $create_loc_gen_bills_log_array['loc_gen_bill_log_id']
                );
                echo json_encode($response);

                
            }else{
                $response = array(
                    "status" => "success",
                    "message" => "Loc Gen Bills | Already Generated"
                );
                echo json_encode($response);
            }

        }   // $create_loc_gen_bills_log Function End

// /////////////////////////


    } else {
        // Return an error if any of the fields are missing
        $response = array(
            "status" => "failed",
            "message" => "Missing or empty required fields: " . implode(', ', $missing_fields),
            "code" => "0xx"
        );
        echo json_encode($response);
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
