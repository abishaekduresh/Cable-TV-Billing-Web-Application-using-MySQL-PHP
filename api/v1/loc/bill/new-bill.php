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
    $required_fields = ['created_user_id', 'loc_gen_bill_id', 'channel_uid', 'due_amount',
        'paid_amount', 'paid_discount', 'balance_amount', 'pay_amount', 'pay_discount', 'pay_mode'];

    $missing_fields = [];
    $active_status = 1;

    // Check if all required fields are present
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || (is_string($data[$field]) && trim($data[$field]) === '')) {
            $missing_fields[] = $field;
        }
    }

    if (empty($missing_fields)) {
        $finalAmountCheck = $data['paid_discount'] + $data['paid_amount'] + $data['pay_amount'] + $data['pay_discount'];
        $loc_gen_bill_id = $data['loc_gen_bill_id'];
        $channel_uid = $data['channel_uid'];

        if ($data['pay_amount'] >= 0) {
            if ($data['pay_discount'] >= 0 && $finalAmountCheck >= 0) {
                if ($data['due_amount'] >= $finalAmountCheck) {
                    // Check loc_gen_bills - due_status to continue
                    $stmt = $con->prepare("SELECT due_status, status FROM loc_gen_bills WHERE loc_gen_bill_id = ? AND channel_uid = ?");
                    $stmt->bind_param("is", $loc_gen_bill_id, $channel_uid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row['due_status'] == "0") {
                        $status = isset($row['status']) ? $row['status'] : null;

                        // If status is null or "0"
                        if (is_null($status) || $status == "0") {
                            echo json_encode([
                                "status" => "failed",
                                "message" => "loc_gen_bill_id status is Zero or not found!"
                            ]);
                            exit();
                        } else {
                            // Insert into loc_bills table
                            $stmt = $con->prepare("INSERT INTO loc_bills 
                                (created_at, created_user_id, loc_gen_bill_id, channel_uid, paid_amount, 
                                 discount, remark, status, updated_at, updated_user_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                            $currentDateTime = date('Y-m-d H:i:s');
                            $stmt->bind_param("siisiisisi",
                                $currentDateTime, 
                                $data['created_user_id'], 
                                $data['loc_gen_bill_id'], 
                                $data['channel_uid'], 
                                $data['pay_amount'], 
                                $data['pay_discount'], 
                                $data['remark'], 
                                $active_status, 
                                $currentDateTime, 
                                $data['created_user_id']
                            );

                            if ($stmt->execute()) {
                                $prop_phone = get_loc_channel_by_uid($data['channel_uid'])['prop_phone'];
                                $create_loc_prop_login_array = create_loc_prop_login($currentDateTime, $data['loc_gen_bill_id'], $data['channel_uid']);
                                $get_due_month_year_by_gen_bill_id_array = get_due_month_year_by_gen_bill_id($data['loc_gen_bill_id'], $data['channel_uid']);
                                $concat_due_month_year = $get_due_month_year_by_gen_bill_id_array['due_month'] . "-" . $get_due_month_year_by_gen_bill_id_array['due_year'];

                                if ($finalAmountCheck == $data['due_amount']) {
                                    $loc_sms_status = "paid";
                                    $patch_loc_gen_bills_due_status = patch_loc_gen_bills_due_status($data['loc_gen_bill_id'], $data['channel_uid'], $active_status);
                                } else {
                                    $loc_sms_status = "still pending";
                                    // If there is still an outstanding amount
                                    $patch_loc_gen_bills_due_status = false;
                                }
                                $loc_sms = loc_sms_api($prop_phone, $concat_due_month_year, $loc_sms_status, $token = null);

                                $response = [
                                    "status" => "success",
                                    "message" => "Bill Created! | " . $data['due_amount'] . ' - ' . $finalAmountCheck,
                                    "code" => "200",
                                    "due_status" => $patch_loc_gen_bills_due_status,
                                    "sms_status" => $loc_sms
                                ];
                                echo json_encode($response);
                            } else {
                                echo json_encode([
                                    "status" => "failed",
                                    "message" => "Failed to insert into loc_bills"
                                ]);
                            }
                        }
                    } else {
                        echo json_encode([
                            "status" => "success",
                            "message" => "Due Already Cleared...",
                            "code" => "200"
                        ]);
                    }
                } else {
                    echo json_encode([
                        "status" => "failed",
                        "message" => "Due Amount is less than the FinalAmount Check!"
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "failed",
                    "message" => "finalAmountCheck can't be Negative!"
                ]);
            }
        } else {
            echo json_encode([
                "status" => "failed",
                "message" => "Pay Amount can't be less than Zero!"
            ]);
        }
    } else {
        echo json_encode([
            "status" => "failed",
            "message" => "Missing or empty required fields: " . implode(', ', $missing_fields),
            "code" => "0xx"
        ]);
    }
} else {
    echo json_encode([
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => "0xx"
    ]);
}

// Close connection
$con->close();
?>
