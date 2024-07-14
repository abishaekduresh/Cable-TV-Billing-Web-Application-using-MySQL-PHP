<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");
require_once("../../../component2.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

$inputDate = isset($data['date']) ? validateInput($data['date']) : '';
$inputDate = date("Y-m-d", strtotime($inputDate));
$inputYearDate = date("Y-m", strtotime($inputDate));
$currentYearMonth = date("Y-m", strtotime($currentDate));
$approveStatus = 'approve';
$creditPMode = 'credit';
$income = 'Income';
$expense = 'Expense';

if (!empty($inputDate)) {
    
    // Indiv Today Bill Amt
    $stmt = $con->prepare("SELECT
        (SELECT SUM(paid_amount) FROM bill WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND status = ?) AS indivTodayBillColAmt,
        (SELECT SUM(discount) FROM bill WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND status = ?) AS indivTodayBillDisAmt,
        (SELECT COUNT(Rs) FROM bill WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND status = ?) AS groupTodayBillCount");

    $stmt->bind_param("ssssss", $currentDate, $approveStatus, $currentDate, $approveStatus, $currentDate, $approveStatus);

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
    
    // Group Today Bill Array
    $stmt = $con->prepare("SELECT
        (SELECT SUM(Rs) FROM billgroupdetails WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND status = ?) AS groupTodayBillColAmt,
        (SELECT SUM(discount) FROM billgroupdetails WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND status = ?) AS groupTodayBillDisAmt,
        (SELECT COUNT(Rs) FROM billgroupdetails WHERE DATE_FORMAT(date, '%Y-%m-%d') = ? AND status = ?) AS groupTodayBillCount");
    
    if ($stmt === false) {
        // Handle prepare error
        $response = array(
            "status" => "failed",
            "message" => "Prepare failed: " . $con->error,
            "code" => 500
        );
        echo json_encode($response);
        exit; // Exit script
    }
    
    // Bind parameters
    $stmt->bind_param("ssssss", $currentDate, $approveStatus, $currentDate, $approveStatus, $currentDate, $approveStatus);
    
    // Execute the statement
    if ($stmt->execute()) {
        // If execution is successful, fetch results
        $result = $stmt->get_result();
        $groupTodayBillArray = [];
    
        while ($row = $result->fetch_assoc()) {
            // Check and replace null values with 0
            foreach ($row as $key => $value) {
                if ($value === null) {
                    $row[$key] = 0;
                }
            }
            $groupTodayBillArray[] = $row;
        }
    
    } else {
        // If execution fails, handle the error
        $response = array(
            "status" => "failed",
            "message" => "Group Bill Error: " . $stmt->error,
            "code" => 500
        );
        echo json_encode($response);
    }
    
    // Close statement
    $stmt->close();
    
    // Indiv Credt Bill Amt
    $stmt = $con->prepare("SELECT
        (SELECT SUM(Rs) FROM bill WHERE pMode = ? AND status = ?) AS indivCreditBillAmt,
        (SELECT COUNT(Rs) FROM bill WHERE pMode = ? AND status = ?) AS indivCreditBillCount");

    $stmt->bind_param("ssss", $creditPMode, $approveStatus, $creditPMode, $approveStatus);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $indivCreditBillArray = [];

        while ($row = $result->fetch_assoc()) {
            // Check and replace null values with 0
            foreach ($row as $key => $value) {
                if ($value === null) {
                    $row[$key] = 0;
                }
            }
            $indivCreditBillArray[] = $row;
        }

    } else {
        $response = array(
            "status" => "failed",
            "message" => "Indiv Credit Bill Error: " . $stmt->error,
            "code" => 500
        );
        echo json_encode($response);
    }
    $stmt->close();
    
    // Group Today Bill Array
    $stmt = $con->prepare("SELECT
        (SELECT SUM(Rs) FROM billgroupdetails WHERE pMode = ? AND status = ?) AS groupCreditBillAmt,
        (SELECT COUNT(Rs) FROM billgroupdetails WHERE pMode = ? AND status = ?) AS groupCreditBillCount");
    
    if ($stmt === false) {
        // Handle prepare error
        $response = array(
            "status" => "failed",
            "message" => "Prepare failed: " . $con->error,
            "code" => 500
        );
        echo json_encode($response);
        exit; // Exit script
    }
    
    // Bind parameters
    $stmt->bind_param("ssss", $creditPMode, $approveStatus, $creditPMode, $approveStatus);
    
    // Execute the statement
    if ($stmt->execute()) {
        // If execution is successful, fetch results
        $result = $stmt->get_result();
        $groupCreditBillArray = [];
    
        while ($row = $result->fetch_assoc()) {
            // Check and replace null values with 0
            foreach ($row as $key => $value) {
                if ($value === null) {
                    $row[$key] = 0;
                }
            }
            $groupCreditBillArray[] = $row;
        }
    
    } else {
        // If execution fails, handle the error
        $response = array(
            "status" => "failed",
            "message" => "Group Bill Error: " . $stmt->error,
            "code" => 500
        );
        echo json_encode($response);
    }
    
    $stmt->close();
    
    
    // Income / Expense Array
    $stmt = $con->prepare("SELECT
      (SELECT SUM(amount) FROM in_ex WHERE type = ? AND DATE_FORMAT(date, '%Y-%m') = ?) AS totIncomeAmt,
      (SELECT SUM(amount) FROM in_ex WHERE type = ? AND DATE_FORMAT(date, '%Y-%m') = ?) AS totExpenseAmt");
    
    if ($stmt === false) {
        $response = array(
            "status" => "failed",
            "message" => "Prepare failed: " . $con->error,
            "code" => 500
        );
        echo json_encode($response);
        exit;
    }
    
    $stmt->bind_param("ssss", $income, $currentYearMonth, $expense, $currentYearMonth);
    
    if ($stmt->execute()) {
        // If execution is successful, fetch results
        $result = $stmt->get_result();
        $incomeExpenseArray = [];
    
        while ($row = $result->fetch_assoc()) {
            // Check and replace null values with 0
            foreach ($row as $key => $value) {
                if ($value === null) {
                    $row[$key] = 0;
                }
            }
            $incomeExpenseArray[] = $row;
        }
    
    } else {
        // If execution fails, handle the error
        $response = array(
            "status" => "failed",
            "message" => "Income Expense Error: " . $stmt->error,
            "code" => 500
        );
        echo json_encode($response);
    }
    
    // Close statement
    $stmt->close();
    
//     // Income / Expense Array
// $stmt = $con->prepare("SELECT due_month_timestamp, SUM(Rs) AS indivSum, COUNT(Rs) AS indivCount 
//                       FROM bill 
//                       WHERE status = ? AND due_month_timestamp >= DATE_SUB(?, INTERVAL -5 MONTH) 
//                       GROUP BY due_month_timestamp 
//                       ORDER BY due_month_timestamp DESC 
//                       LIMIT 10");

// if ($stmt === false) {
//     $response = array(
//         "status" => "failed",
//         "message" => "Prepare failed: " . $con->error,
//         "code" => 500
//     );
//     echo json_encode($response);
//     exit;
// }

// // Bind parameters
// $stmt->bind_param("ss", $approveStatus, $currentDate);

// if ($stmt->execute()) {
//     // If execution is successful, fetch results
//     $result = $stmt->get_result();
//     $indivCountArray = [];

//     while ($row = $result->fetch_assoc()) {
//         // Check and replace null values with 0
//         foreach ($row as $key => $value) {
//             if ($value === null) {
//                 $row[$key] = 0;
//             }
//         }
//         $indivCountArray[] = $row;
//     }

//     // Output the result array
//     $response = array(
//         "status" => "success",
//         "data" => $indivCountArray,
//         "code" => 200
//     );
//     echo json_encode($response);

// } else {
//     // If execution fails, handle the error
//     $response = array(
//         "status" => "failed",
//         "message" => "Income Expense Error: " . $stmt->error,
//         "code" => 500
//     );
//     echo json_encode($response);
// }

// // Close statement
// $stmt->close();
    
    // SMS Credit Check
    $avlSmsCredit = sms_credit();
    
    
    $data = array(
        "status" => "success",
        "message" => "Successfully Retived All data",
        "data" => array(
            "indivTodayBillArray" => $indivTodayBillArray,
            "groupTodayBillArray" => $groupTodayBillArray,
            "indivCreditBillArray" => $indivCreditBillArray,
            "groupCreditBillArray" => $groupCreditBillArray,
            "incomeExpenseArray" => $incomeExpenseArray,
            // "indivCount" => $indivCountArray,
            "avlSmsCredit" => $avlSmsCredit,
        ),
        "code" => 200
    );
    echo json_encode($data);

} else {
    // Empty date field
    $data = array(
        "status" => "failed",
        "message" => "Date field is empty",
        "code" => "05"
    );
    echo json_encode($data);
}
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
