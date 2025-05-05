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
    $userIndivCashPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'cash');
    $userIndivGpayPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'gpay');
    $userIndivPaytmPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'paytm');
    $userIndivCreditPayModeData = getUserIndivBillPayModeData($dueMonthDate, $username, 'credit');

    $userGroupCashPayModeData = getUserGroupBillPayModeData($dueMonthDate, $username, 'cash');
    $userGroupGpayPayModeData = getUserGroupBillPayModeData($dueMonthDate, $username, 'gpay');
    $userGroupPaytmPayModeData = getUserGroupBillPayModeData($dueMonthDate, $username, 'paytm');
    $userGroupCreditPayModeData = getUserGroupBillPayModeData($dueMonthDate, $username, 'credit');

    $userPOSCashData = getUserPOSBillPayModeData($dueMonthDate, $username, '1');
    $userPOSGpayData = getUserPOSBillPayModeData($dueMonthDate, $username, '2');
    $userPOSPaytmData = getUserPOSBillPayModeData($dueMonthDate, $username, '3');
    $userPOSCreditData = getUserPOSBillPayModeData($dueMonthDate, $username, '4');

    $userIncomeExpenseData = getUserIncomeExpenseSum($dueMonthDate, $username);
    $userData = getUserData($username);

    // Get the user information before destroying the session
    $userId = isset($_SESSION['id'])?$_SESSION['id']:'API Call';
    $username = isset($_SESSION['username'])?$_SESSION['username']:'API Call';
    $role = isset($_SESSION['role'])?$_SESSION['role']:'API Call';
    $action = $jsonData['username']." - Indiv bill info fetched";
    
    // Call the function to insert user activity log
    logUserActivity($userId, $username, $role, $action);
    
    $indivTotalAmt = $userIndivCashPayModeData['amt'] +
                    $userIndivGpayPayModeData['amt'] +
                    $userIndivPaytmPayModeData['amt'] +
                    $userIndivCreditPayModeData['amt'] ?? 0;

    $indivTotalDis = $userIndivCashPayModeData['discount'] +
                    $userIndivGpayPayModeData['discount'] +
                    $userIndivPaytmPayModeData['discount'] +
                    $userIndivCreditPayModeData['discount'] ?? 0;

    $indivTotalCount = $userIndivCashPayModeData['count'] +
                    $userIndivGpayPayModeData['count'] +
                    $userIndivPaytmPayModeData['count'] +
                    $userIndivCreditPayModeData['count'] ?? 0;

    $groupTotalAmt = $userGroupCashPayModeData['amt'] +
                    $userGroupGpayPayModeData['amt'] +
                    $userGroupPaytmPayModeData['amt'] +
                    $userGroupCreditPayModeData['amt'] ?? 0;

    $groupTotalDis = $userGroupCashPayModeData['discount'] +
                    $userGroupGpayPayModeData['discount'] +
                    $userGroupPaytmPayModeData['discount'] +
                    $userGroupCreditPayModeData['discount'] ?? 0;

    $groupTotalCount = $userGroupCashPayModeData['count'] +
                    $userGroupGpayPayModeData['count'] +
                    $userGroupPaytmPayModeData['count'] +
                    $userGroupCreditPayModeData['count'] ?? 0;

    $posTotalAmt = $userPOSCashData['amt'] +
                    $userPOSGpayData['amt'] +
                    $userPOSPaytmData['amt'] +
                    $userPOSCreditData['amt'] ?? 0;

    $posTotalDis = $userPOSCashData['discount'] +
                    $userPOSGpayData['discount'] +
                    $userPOSPaytmData['discount'] +
                    $userPOSCreditData['discount'] ?? 0;

    $posTotalCount = $userPOSCashData['count'] +
                    $userPOSGpayData['count'] +
                    $userPOSPaytmData['count'] +
                    $userPOSCreditData['count'] ?? 0;

    $userSumIncome = $userIncomeExpenseData['sumIncome'] ?? 0;
    $userSumExpense = $userIncomeExpenseData['sumExpense'] ?? 0;
                    
    $totalAmt = $indivTotalAmt + $groupTotalAmt + $posTotalAmt + $userSumIncome ?? 0;
    // $totalDis = $indivTotalDis + $groupTotalDis + $posTotalDis + $userSumExpense ?? 0;
    $totalDis = $indivTotalDis + $groupTotalDis + $posTotalDis ?? 0;
    $totalCount = $indivTotalCount + $groupTotalCount + $posTotalCount ?? 0;
    $balance = ($totalAmt - $totalDis) ?? 0;
    // $amountInHand = (
    //     ($userIndivCashPayModeData['amt'] - $userIndivCashPayModeData['discount']) 
    //     - ($userIndivCreditPayModeData['amt'] - $userIndivCreditPayModeData['discount'])
    //     + ($userGroupCashPayModeData['amt'] - $userGroupCashPayModeData['discount'])
    //     - ($userGroupCreditPayModeData['amt'] - $userGroupCreditPayModeData['discount'])
    //     + ($userPOSCashData['amt'] - $userPOSCashData['discount'])
    //     - ($userPOSCreditData['amt'] - $userPOSCreditData['discount'])
    //     + $userSumIncome - $userSumExpense
    // );  
    $amountInHand = (
        ($userIndivCashPayModeData['amt'] - $userIndivCashPayModeData['discount']) 
        // - ($userIndivCreditPayModeData['amt'] - $userIndivCreditPayModeData['discount'])
        + ($userGroupCashPayModeData['amt'] - $userGroupCashPayModeData['discount'])
        // - ($userGroupCreditPayModeData['amt'] - $userGroupCreditPayModeData['discount'])
        + ($userPOSCashData['amt'] - $userPOSCashData['discount'])
        // - ($userPOSCreditData['amt'] - $userPOSCreditData['discount'])
        + $userSumIncome - $userSumExpense
    );    

    // Return response as JSON
    echo json_encode([
        "status" => true,
        "message" => "Success",
        "data" => [
            "userData" => $userData['data'][0],
            "dueMonthDate" => $dueMonthDate,
            "indivData" => [
                "cash" => $userIndivCashPayModeData,
                "gpay" => $userIndivGpayPayModeData,
                "paytm" => $userIndivPaytmPayModeData,
                "credit" => $userIndivCreditPayModeData,
                "totAmt" => $indivTotalAmt,
                "totDis" => $indivTotalDis,
                "totCount" => $indivTotalCount,
            ],
            "groupData" => [
                "cash" => $userGroupCashPayModeData,
                "gpay" => $userGroupGpayPayModeData,
                "paytm" => $userGroupPaytmPayModeData,
                "credit" => $userGroupCreditPayModeData,
                "totAmt" => $groupTotalAmt,
                "totDis" => $groupTotalDis,
                "totCount" => $groupTotalCount,
            ],
            "posData" => [
                "cash" => $userPOSCashData,
                "gpay" => $userPOSGpayData,
                "paytm" => $userPOSPaytmData,
                "credit" => $userPOSCreditData,
                "totAmt" => $posTotalAmt,
                "totDis" => $posTotalDis,
                "totCount" => $posTotalCount,
            ],
            "incomeExpense" => $userIncomeExpenseData,
            "totAmt" => $totalAmt,
            "totDis" => $totalDis,
            "totCount" => $totalCount,
            "bal" => $balance,
            "date" => $currentDate,
            "time" => $currentTime,
            "amountInHand" => $amountInHand
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