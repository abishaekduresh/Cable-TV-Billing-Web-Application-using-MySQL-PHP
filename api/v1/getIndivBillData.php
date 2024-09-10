<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../../dbconfig.php");
require_once("../../component.php");

require '../../excel_lib/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);

// Set content type header to application/json
header('Content-Type: application/json');

// Extracting and validating input
$fromDate = isset($data['fromDate']) ? validateInput($data['fromDate']) : null;
$toDate = isset($data['toDate']) ? validateInput($data['toDate']) : null;
$flag = isset($data['flag']) ? validateInput($data['flag']) : null;
$indivMSO = isset($data['indivMSO']) ? validateInput($data['indivMSO']) : 0;
$fromBillNo = isset($data['fromBillNo']) && !empty($data['fromBillNo']) ? validateInput($data['fromBillNo']) : null;
$toBillNo = isset($data['toBillNo']) && !empty($data['toBillNo']) ? validateInput($data['toBillNo']) : null;

$numFromDate = separateDate($fromDate);
$numToDate = separateDate($toDate);

$excelFileName = "Indiv Bill - ".$fromDate." - ".$toDate;

// Condition for filtering by BillNo range, only if both are provided
$billNoFilterCondition = '';
if ($fromBillNo !== null && $toBillNo !== null) {
    if ($fromBillNo > $toBillNo) {
        echo json_encode(['status' => '0', 'error' => "From Bill No. can't be greater than To Bill No"]);
        exit();
    }
    $billNoFilterCondition = "AND billNo BETWEEN '$fromBillNo' AND '$toBillNo'";
}else{
	$billNoFilterCondition = '';
}

$indivMSOFilterCondition = '';
$indivMSOFilterCondition = "AND mso = '$indivMSO'";


if ($numFromDate['month'] == $currentMonth && $numFromDate['year'] == $currentYear && $numToDate['month'] == $currentMonth && $numToDate['year'] == $currentYear) {
    if ($fromDate != null && $toDate != null && $flag == '1') {
        // Prepare query
        $query = "SELECT billNo, bill_by, mso, stbno, name, phone, Rs, due_month_timestamp, description, status 
                  FROM bill 
                  WHERE DATE(due_month_timestamp) BETWEEN ? AND ? 
                  AND status = 'approve' 
                  $billNoFilterCondition $indivMSOFilterCondition";

        if ($stmt = $con->prepare($query)) {
            // Bind parameters and execute
            $stmt->bind_param("ss", $fromDate, $toDate);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

            if ($result) {
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                $sheet->setCellValue('A1', 'Due Date');
                $sheet->setCellValue('B1', 'Bill No.');
                $sheet->setCellValue('C1', 'User');
                $sheet->setCellValue('D1', 'Name');
                $sheet->setCellValue('E1', 'Phone');
                $sheet->setCellValue('F1', 'MSO');
                $sheet->setCellValue('G1', 'STB No.');
                $sheet->setCellValue('H1', 'Remark');
                $sheet->setCellValue('I1', 'Paid Amount');
                $sheet->setCellValue('J1', 'Status');

                $rowCount = 2;
                foreach($result as $row) {
                    $sheet->setCellValue('A'.$rowCount, $row['due_month_timestamp']);
                    $sheet->setCellValue('B'.$rowCount, $row['billNo']);
                    $sheet->setCellValue('C'.$rowCount, $row['bill_by']);
                    $sheet->setCellValue('D'.$rowCount, $row['name']);
                    $sheet->setCellValue('E'.$rowCount, $row['phone']);
                    $sheet->setCellValue('F'.$rowCount, $row['mso']);
                    $sheet->setCellValue('G'.$rowCount, $row['stbno'].',');
                    $sheet->setCellValue('H'.$rowCount, $row['description']);
                    $sheet->setCellValue('I'.$rowCount, $row['Rs']);
                    $sheet->setCellValue('J'.$rowCount, $row['status']);
                    $rowCount++;
                }

				// Manually adjust column widths
				foreach (range('A', 'I') as $columnID) {
					$sheet->getColumnDimension($columnID)->setAutoSize(true);
				}
				
                // Save Excel file to a temporary location
                $writer = new Xlsx($spreadsheet);
                $tempFile = tempnam(sys_get_temp_dir(), 'phpspreadsheet');
                $writer->save($tempFile);

                // Encode file to base64
                $fileData = file_get_contents($tempFile);
                $base64EncodedExcel = base64_encode($fileData);

                // Delete the temporary file
                unlink($tempFile);

                $response = array(
                    'status' => '1',
					'result' => $result,
                    'filename' => $excelFileName . '.xlsx',
                    'file' => $base64EncodedExcel
                );
                echo json_encode($response);
            } else {
                echo json_encode(['status' => '0', 'error' => 'No data found for the selected date range.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => '0', 'error' => 'Query preparation failed: ' . $con->error]);
        }
    } else {
        echo json_encode(['status' => '0', 'error' => 'Invalid Input. Please check your data.']);
    }
} else {
    echo json_encode(['status' => '0', 'error' => 'Please select a date range within the current Month and Year']);
}

// Close database connection
$con->close();
?>
