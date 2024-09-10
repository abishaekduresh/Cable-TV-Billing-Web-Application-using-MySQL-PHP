<?php

// Set content type header to application/json
header('Content-Type: application/json');

require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Function to sanitize input
function sanitizeInput($input) {
    // Add your sanitization logic here
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    // Sanitize input
    $status = isset($data['status']) ? $data['status'] : null;
    $pos_bill_id = isset($data['pos_bill_id']) ? $data['pos_bill_id'] : null;
    
    // Prepare and execute SQL query
    $stmt = $con->prepare("UPDATE pos_bill SET status = ? WHERE pos_bill_id = ?");
    $stmt->bind_param("ss", $status, $pos_bill_id);
    
    if ($stmt->execute()) {
        $d=1;
$sqlSum = "SELECT SUM(pbi.price) AS total_price
           FROM pos_bill pb
           LEFT JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
           WHERE DATE(pb.entry_timestamp) = '$currentDate' AND pb.status = '1'";

        
        $result = $con->query($sqlSum);
        $row = $result->fetch_assoc();
        $sumPaidAmount = $row["total_price"];
        // $sumPaidAmount = 9;
        if($sumPaidAmount == null){
            $sumPaidAmount = 0;
        }
    
        // Check if a record exists in in_ex table
        $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 16 AND subcategory_id = 57 AND status = '1'";
        $resultCheck = $con->query($sqlCheck);
    
        if ($resultCheck->num_rows > 0) {
            // Update existing record
            // $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '16', subcategory_id = '57', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 16 AND subcategory_id = 57";
$sqlUpdate = "UPDATE in_ex 
              SET type='Income', 
                  date='" . $currentDate . "', 
                  time='" . $currentTime . "', 
                  username='Auto', 
                  category_id=16, 
                  subcategory_id=57, 
                  remark='', 
                  amount='$sumPaidAmount' 
              WHERE date='" . $currentDate . "' AND category_id=16 AND subcategory_id=57 AND status = '1'";

            $con->query($sqlUpdate);
            $d=2;
        } else {
            // Insert new record
            $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount,status) VALUES ('Income', '$currentDate', '$currentTime','Auto', '16', '57','', '$sumPaidAmount','1')";
            $con->query($sqlInsert);
            $d=3;
        }
        
        // Prepare response data
        $response = array(
            "status" => "success",
            "message" => "POS Bill status updates successfully",
            "d" => $d,
            "code" => 200, // Changed to integer
        );
        echo json_encode($response);
    } else {
        // Error occurred while executing query
        $response = array(
            "status" => "failed",
            "message" => "Error: " . $stmt->error,
            "code" => 500 // Changed error code to 500
        );
        echo json_encode($response);
    }
    
    $stmt->close();
} else {
    // HTTP method other than POST not allowed
    $response = array(
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => 405 // Changed error code to 405 (Method Not Allowed)
    );
    echo json_encode($response);
} 

$con->close();

?>
