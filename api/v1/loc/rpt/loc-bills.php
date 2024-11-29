<?php

// Set content type header to application/json
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../../../../dbconfig.php");
require_once("../../../../component.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Define required fields
    $required_fields = ['channel_uid', 'due_month_year'];
    $missing_fields = [];
    $active_status = 1;

    // Check if all required fields are present
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (empty($missing_fields)) {
        // All required fields are present, proceed with the logic
        $channel_uid = $data['channel_uid'];
        $due_month_year = $data['due_month_year'];

        // Validate and extract due_month and due_year
        list($due_year, $due_month) = explode('-', $due_month_year);
        $active_status = 1;

        // Prepare SQL query to fetch loc bills based on channel_uid and status
        $stmt = $con->prepare("
            SELECT 
                loc_gen_bills_log.*,
                loc_gen_bills.*,
                loc_bills.*
            FROM 
                loc_gen_bills_log
            INNER JOIN 
                loc_gen_bills ON loc_gen_bills_log.loc_gen_bill_log_id = loc_gen_bills.loc_gen_bill_log_id
            INNER JOIN 
                loc_bills ON loc_gen_bills.loc_gen_bill_id = loc_bills.loc_gen_bill_id
            WHERE 
                loc_gen_bills_log.status = ?
                AND loc_gen_bills.status = ?
                AND loc_bills.status = ?
                AND loc_gen_bills_log.due_month = ?
                AND loc_gen_bills_log.due_year = ?
                AND loc_gen_bills.channel_uid = ?
                AND loc_bills.channel_uid = ?
        ");
        
        // Bind parameters (two channel_uid used twice, so binding twice)
        $stmt->bind_param("iiiiiss", $active_status, $active_status, $active_status, $due_month, $due_year, $channel_uid, $channel_uid);
        
        // Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize an array to hold all the fetched rows
        $final_data = [];
        $paid_amount = 0;  // Initialize to 0
        $discount = 0;     // Initialize to 0

        // Fetch all rows as an associative array
        while ($row = $result->fetch_assoc()) {
            // Calculate paid_amount and discount if needed
            $paid_amount = isset($row['paid_amount']) ? $row['paid_amount'] : 0;
            $discount = isset($row['discount']) ? $row['discount'] : 0;

            // Prepare the processed row
            $processed_row = [
                'loc_gen_bill_id' => isset($row['loc_gen_bill_id']) ? $row['loc_gen_bill_id'] : null,
                'bill_created_at' => isset($row['created_at']) ? $row['created_at'] : null,
                'channel_uid' => isset($row['channel_uid']) ? $row['channel_uid'] : null,
                'channel_name' => get_loc_channel_by_uid($row['channel_uid'])['channel_name'],
                'due_amount' => isset($row['due_amount']) ? $row['due_amount'] : null,
                'due_status' => isset($row['due_status']) ? $row['due_status'] : null,
                'remark' => isset($row['remark']) ? $row['remark'] : '',
                'bill_status' => isset($row['bill_status']) ? $row['bill_status'] : null,
                'bill_updated_at' => isset($row['bill_updated_at']) ? $row['bill_updated_at'] : null,
                'loc_gen_bill_log_id' => isset($row['loc_gen_bill_log_id']) ? $row['loc_gen_bill_log_id'] : null,
                'log_created_at' => isset($row['log_created_at']) ? $row['log_created_at'] : null,
                'log_status' => isset($row['log_status']) ? $row['log_status'] : null,
                'log_updated_at' => isset($row['log_updated_at']) ? $row['log_updated_at'] : null,
                'due_month' => isset($row['due_month']) ? $row['due_month'] : null,
                'due_year' => isset($row['due_year']) ? $row['due_year'] : null,
                'paid_amount' => $paid_amount,
                'paid_discount' => $discount
            ];

            // Append the processed row to $final_data
            $final_data[] = $processed_row;
        }

        // Check if query returned any data
        if (!empty($final_data)) {
            // If data found, send a successful response
            $response = [
                "status" => "success",
                "message" => "Data fetched successfully",
                "data" => $final_data,
                "code" => "200"
            ];
        } else {
            // If no data found, send a response with no records
            $response = [
                "status" => "success",
                "message" => "No record found",
                "data" => null,
                "code" => "05"
            ];
        }

        // Return JSON response
        echo json_encode($response);

    } else {
        // Return an error if any of the fields are missing
        $response = [
            "status" => "failed",
            "message" => "Missing or empty required fields: " . implode(', ', $missing_fields),
            "code" => "0xx"
        ];
        echo json_encode($response);
    }

} else {
    // Method not allowed
    $response = [
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => "0xx"
    ];
    echo json_encode($response);
}

// Close connection
$con->close();
?>
