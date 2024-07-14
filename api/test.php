<?php
require_once("../dbconfig.php");
require_once("../component.php");
require_once("../component2.php");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
    // Indiv Today Bill Amt
    $stmt = $con->prepare("SELECT * FROM customer LIMIT 5");

    // $stmt->bind_param("ssssss", $currentDate, $approveStatus, $currentDate, $approveStatus, $currentDate, $approveStatus);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $indivTodayBillArray = [];

        while ($row = $result->fetch_assoc()) {
            // Check and replace null values with 0
            foreach ($row as $key => $value) {
                if ($value === null) {
                    $row[$key] = 0;
                }
            }
            $indivTodayBillArray[] = $row;
        }

    } else {
        $response = array(
            "status" => "failed",
            "message" => "Indiv Bill Error: " . $stmt->error,
            "code" => 500
        );
        echo json_encode($response);
    }
    $stmt->close();
    
    $data = array(
        "status" => "success",
        "message" => "Successfully Retived All data",
        "data" => array(
            "customer" => $indivTodayBillArray,
        ),
        "code" => 200
    );
    echo json_encode($data);
    
} else {
    // Empty date field
    $data = array(
        "status" => "failed",
        "message" => "Method Not Allowed",
        "code" => "500"
    );
    echo json_encode($data);
}

// Close connection
$con->close();
?>
