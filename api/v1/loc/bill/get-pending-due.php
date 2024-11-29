<?php

// Set content type header to application/json
header('Content-Type: application/json');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../../../dbconfig.php");
require_once("../../../../component.php");

// Initialize response array
$final_data = [];

// Check if request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Define required fields
    $required_fields = ['channel_uid'];
    $missing_fields = [];
    $active_status = 1;

    // Check if all required fields are present
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (empty($missing_fields)) {

        // Sanitize input
        $channel_uid = $data['channel_uid'];

        // Prepare SQL query with JOIN to reduce the number of queries
        $stmt = $con->prepare("
            SELECT b.loc_gen_bill_id, b.created_at AS bill_created_at, b.channel_uid, b.due_amount, b.due_status, 
                   b.remark, b.status AS bill_status, b.updated_at AS bill_updated_at, 
                   l.loc_gen_bill_log_id, l.created_at AS log_created_at, l.due_month, l.due_year, 
                   l.status AS log_status, l.updated_at AS log_updated_at, 
                   SUM(bi.paid_amount) AS paid_amount, SUM(bi.discount) AS discount
            FROM loc_gen_bills b
            INNER JOIN loc_gen_bills_log l ON b.loc_gen_bill_log_id = l.loc_gen_bill_log_id
            LEFT JOIN loc_bills bi ON b.loc_gen_bill_id = bi.loc_gen_bill_id
            WHERE b.channel_uid = ? AND b.status = ? AND l.status = ?
            GROUP BY b.loc_gen_bill_id
        ");
        $stmt->bind_param("sii", $channel_uid, $active_status, $active_status);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the query returned any rows
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Prepare the processed row
                $processed_row = [
                    'loc_gen_bill_id' => $row['loc_gen_bill_id'],
                    'bill_created_at' => $row['bill_created_at'],
                    'channel_uid' => $row['channel_uid'],
                    'channel_name' => get_loc_channel_by_uid($row['channel_uid'])['channel_name'],
                    'due_amount' => $row['due_amount'],
                    'due_status' => $row['due_status'],
                    'remark' => $row['remark'],
                    'bill_status' => $row['bill_status'],
                    'bill_updated_at' => $row['bill_updated_at'],
                    'loc_gen_bill_log_id' => $row['loc_gen_bill_log_id'],
                    'log_created_at' => $row['log_created_at'],
                    'log_status' => $row['log_status'],
                    'log_updated_at' => $row['log_updated_at'],
                    'due_month' => $row['due_month'],
                    'due_year' => $row['due_year'],
                    'paid_amount' => $row['paid_amount'] ?? 0,
                    'paid_discount' => $row['discount'] ?? 0
                ];

                // Append the processed row to final data array
                $final_data[] = $processed_row;
            }

            // Return success response with data
            $data = [
                "status" => "success",
                "message" => "Data fetched successfully",
                "data" => $final_data,
                "code" => "200"
            ];
        } else {
            // If no data found, return a message with no records
            $data = [
                "status" => "success",
                "message" => "No record found",
                "data" => null,
                "code" => "05"
            ];
        }

    } else {
        // Return an error if any required fields are missing
        $data = [
            "status" => "failed",
            "message" => "Missing or empty required fields: " . implode(', ', $missing_fields),
            "code" => "1001"
        ];
    }

    // Output JSON response
    echo json_encode($data);

} else {
    // Method not allowed response
    $data = [
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => "0xx"
    ];
    echo json_encode($data);
}

// Close database connection
$con->close();

?>
