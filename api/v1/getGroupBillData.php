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
$fromBillNo = isset($data['fromBillNo']) && !empty($data['fromBillNo']) ? validateInput($data['fromBillNo']) : null;
$toBillNo = isset($data['toBillNo']) && !empty($data['toBillNo']) ? validateInput($data['toBillNo']) : null;

$numFromDate = separateDate($fromDate);
$numToDate = separateDate($toDate);

$excelFileName = "Group Bill - ".$fromDate." - ".$toDate;

// Condition for filtering by BillNo range, only if both are provided
$billNoFilterCondition = '';
if ($fromBillNo !== null && $toBillNo !== null) {
    if ($fromBillNo > $toBillNo) {
        echo json_encode(['status' => '0', 'error' => "From Bill No. can't be greater than To Bill No"]);
        exit;
    }
    $billNoFilterCondition = "AND billNo BETWEEN '$fromBillNo' AND '$toBillNo'";
}

if ($numFromDate['month'] == $currentMonth && $numFromDate['year'] == $currentYear && $numToDate['month'] == $currentMonth && $numToDate['year'] == $currentYear) {
    // Process only if fromDate, toDate, and flag are valid
    if ($fromDate != null && $toDate != null && $flag == '1') {
        // Prepare query
        $query = "SELECT * FROM billgroup WHERE date BETWEEN ? AND ? AND status = 'approve' $billNoFilterCondition";

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
                $sheet->setCellValue('C1', 'Group Name');
                $sheet->setCellValue('D1', 'Name');
                $sheet->setCellValue('E1', 'MSO');
                $sheet->setCellValue('F1', 'STB No.');
                //$sheet->setCellValue('G1', 'Status');

                $rowCount = 2;
                foreach($result as $row) {
					$groupName = fetchGroupName($row['group_id']);
                    $sheet->setCellValue('A'.$rowCount, $row['date']);
                    $sheet->setCellValue('B'.$rowCount, $row['billNo']);
                    $sheet->setCellValue('C'.$rowCount, $groupName);
                    $sheet->setCellValue('D'.$rowCount, $row['name']);
                    $sheet->setCellValue('E'.$rowCount, $row['mso']);
                    $sheet->setCellValue('F'.$rowCount, $row['stbNo'].',');
                    //$sheet->setCellValue('G'.$rowCount, $row['status']);
                    $rowCount++;
                }
				
				// Manually adjust column widths
				foreach (range('A', 'F') as $columnID) {
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
