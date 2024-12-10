<?php
session_start();
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Set header to return JSON
header('Content-Type: application/json');

try {
    // Get JSON data from request body
    $jsonData = json_decode(file_get_contents("php://input"), true);

    // Check if JSON decoding was successful
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode([
            "status" => false,
            "billType" => "Indiv",
            "message" => 'Invalid JSON input'
        ]);
        exit();
    }

    // Check if required fields are present in the JSON data
    if (!isset($jsonData['dueMonthDate']) || !isset($jsonData['username'])) {
        echo json_encode([
            "status" => false,
            "message" => 'Missing required fields: dueMonthDate or username'
        ]);
        exit();
    }

    // Extract data from JSON
    $dueMonthDate = $jsonData['dueMonthDate'];
    $username = $jsonData['username'];

    // Retrieve data based on different payment modes
    $userCashPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'cash');
    $userGpayPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'gpay');
    $userPaytmPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'paytm');
    $userCreditPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'credit');
    $userIncomeExpense = getUserIncomeExpenseSum($dueMonthDate, $username);
    $userPosAmount = getUserPosAmount($dueMonthDate, $username);
    $userData = getUserData($username);

    // Get the user information before destroying the session
    $userId = $_SESSION['id'];
    $username = $_SESSION['username'];
    $role = $_SESSION['role'];
    $action = $jsonData['username']." - Indiv bill info fetched";
    
    // Call the function to insert user activity log
    logUserActivity($userId, $username, $role, $action);
    
    // Return response as JSON
    echo json_encode([
        "status" => true,
        "message" => "Success",
        "data" => [
            "userData" => $userData,
            "dueMonthDate" => $dueMonthDate,
            "cashData" => $userCashPayModeData,
            "gpayData" => $userGpayPayModeData,
            "paytmData" => $userPaytmPayModeData,
            "creditData" => $userCreditPayModeData,
            "incomeExpense" => $userIncomeExpense,
            "pos" => $userPosAmount
        ]
    ]);
    
} catch (Exception $e) {
    // Return an error as JSON if an exception occurs
    echo json_encode([
        "status" => false,
        "message" => $e->getMessage()
    ]);
} finally {
    // Close the database connection
    if (isset($con)) {
        $con->close();
    }
}
