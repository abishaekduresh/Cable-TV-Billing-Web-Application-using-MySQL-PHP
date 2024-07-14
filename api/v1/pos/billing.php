<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Check if the required files are included and the database connection is established successfully
if (!$con) {
    $response = array(
        "status" => "failed",
        "message" => "Database connection failed",
        "code" => "500"
    );
    echo json_encode($response);
    exit;
}

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

// Check for JSON parse errors
if (json_last_error() !== JSON_ERROR_NONE) {
    $response = array(
        "status" => "failed",
        "message" => "JSON Error: " . json_last_error_msg(),
        "code" => "400"
    );
    echo json_encode($response);
    exit;
}

// Validate and extract data
$requiredFields = ['username', 'timestamp', 'items'];
foreach ($requiredFields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        $response = array(
            "status" => "failed",
            "message" => "The field '{$field}' is Empty",
            "code" => "405"
        );
        echo json_encode($response);
        exit;
    }
}

    $productQuantities = [];
    foreach ($data['items'] as $item) {
        $pos_product_id = $item['pos_product_id'];
        $qty = $item['qty'];
        $stock = $item['stock'];

        if (!isset($productQuantities[$pos_product_id])) {
            $productQuantities[$pos_product_id] = [
                'qty' => 0,
                'stock' => $stock
            ];
        }

        $productQuantities[$pos_product_id]['qty'] += $qty;
    }

    // Check each accumulated quantity against the stock
    foreach ($productQuantities as $pos_product_id => $productData) {
        if ($productData['qty'] > $productData['stock']) {
            $productName = getProductName($con, $pos_product_id);
            $response = array(
                "status" => "failed",
                "message" => "Quantity exceeds available stock for product ID {$productName}",
                "code" => "405"
            );
            echo json_encode($response);
            exit;
        }
    }
    
// foreach ($data['items'] as $item) {
//     $pos_product_id = $item['pos_product_id'];
//     $qty = $item['qty'];
//     $stock = $item['stock'];
//     $productName = getProductName($pos_product_id);

//     if ($qty > $stock) {
//         $response = array(
//             "status" => "failed",
//             "message" => "Quantity exceeds available stock for {$productName}",
//             "code" => "405"
//         );
//         echo json_encode($response);
//         exit;
//     }
// }

// $bill_no = '99';
$username = validateInput($data['username']);
$timestamp = validateInput($data['timestamp']);
$cus_name = isset($data['cus_name']) ? validateInput($data['cus_name']) : '-';
$cus_phone = isset($data['cus_phone']) ? validateInput($data['cus_phone']) : '0';
$discount = $data['discount'];
$pay_mode = isset($data['pay_mode']) ? validateInput($data['pay_mode']) : '0';
$r_or_hs = $data['r_or_hs'];
$items = $data['items'];
$status = 1;
$unic_token = generateUniqueToken('pos_bill', 'token');
$bill_no = generateUniquePosInvoiceId();

// Insert data into the database
$insertStmt = $con->prepare("INSERT INTO pos_bill (entry_timestamp, bill_no, username, cus_name, cus_phone, discount, token, pay_mode, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$insertStmt) {
    $response = array(
        "status" => "failed",
        "message" => "Database Error: " . $con->error,
        "code" => "500"
    );
    echo json_encode($response);
    exit;
}
$insertStmt->bind_param("sssssssii", $timestamp, $bill_no, $username, $cus_name, $cus_phone, $discount, $unic_token, $pay_mode, $status);

if ($insertStmt->execute()) {
    $pos_bill_id = $insertStmt->insert_id;
    $insertStmt->close();

    // Prepare the second statement
    $selectStmt = $con->prepare("SELECT * FROM pos_bill WHERE token = ? LIMIT 1");
    if (!$selectStmt) {
        $response = array(
            "status" => "failed",
            "message" => "Database Error: " . $con->error,
            "code" => "500"
        );
        echo json_encode($response);
        exit;
    }
    
    $selectStmt->bind_param("s", $unic_token);
    
    if($selectStmt->execute()) {
        $result = $selectStmt->get_result();
        
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            if (!empty($items) && is_array($items)) {
                foreach ($items as $item) {
                    $qty = $item['qty'];
                    $pos_product_id = $item['pos_product_id'];
                    $price = $item['price'];
        
                    $insertItemsStmt = $con->prepare("INSERT INTO pos_bill_items (entry_timestamp, username, pos_bill_id, pos_product_id, qty, r_or_hs, price, token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    if (!$insertItemsStmt) {
                        $response = array(
                            "status" => "failed",
                            "message" => "Database Error: " . $con->error,
                            "code" => "500"
                        );
                        echo json_encode($response);
                        exit;
                    }
                    $insertItemsStmt->bind_param("sssssiss", $timestamp, $username, $pos_bill_id, $pos_product_id, $qty, $r_or_hs, $price, $unic_token);
        
                    if (!$insertItemsStmt->execute()) {
                        $insertItemsStmt->close();
                        $response = array(
                            "status" => "failed",
                            "message" => "Error inserting item: " . $insertItemsStmt->error, // Add delete bill 
                            "code" => "501"
                        );
                        echo json_encode($response);
                        exit;
                    }
                    $insertItemsStmt->close();
                }
            }
        } else {
            $response = array(
                "status" => "failed",
                "message" => "No rows returned for token",
                "code" => "501"
            );
            echo json_encode($response);
            exit;
        }
    } else {
        $response = array(
            "status" => "failed",
            "message" => "Error executing token query: " . $selectStmt->error,
            "code" => "501"
        );
        echo json_encode($response);
        exit;
    }
    $selectStmt->close();

    $sqlSum = "SELECT SUM(pbi.price) AS total_price
               FROM pos_bill pb
               LEFT JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
               WHERE DATE(pb.entry_timestamp) = '$currentDate' AND pb.status = '1'";
    
    $result = $con->query($sqlSum);
    $row = $result->fetch_assoc();
    $sumPaidAmount = $row["total_price"];

    // Check if a record exists in in_ex table
    $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 16 AND subcategory_id = 57";
    $resultCheck = $con->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        // Update existing record
        $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '16', subcategory_id = '57', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 16 AND subcategory_id = 57";
        $con->query($sqlUpdate);
    } else {
        // Insert new record
        $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$currentDate', '$currentTime','Auto', '16', '57','', $sumPaidAmount)";
        $con->query($sqlInsert);
    }
    
    $response = array(
        "status" => "success",
        "message" => "Bill processed successfully",
        "data" => array("token" => $unic_token,"bill_no" => $bill_no),
        "code" => "200"
    );
    echo json_encode($response);
} else {
    $response = array(
        "status" => "failed",
        "message" => "Error inserting bill: " . $insertStmt->error,
        "code" => "502"
    );
    echo json_encode($response);
    exit;
}

// Close connection
$con->close();
exit;
?>
