<?php
session_start();
include('dbconfig.php');
require "component.php";

require 'vendor/autoload.php';
$session_username = $_SESSION['username'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

// Retrieve data from MySQL based on date range
if (isset($_POST['submit'])) {
    // ...
    $from_date = $_POST['start_date'];
    $to_date = $_POST['end_date'];

    // Retrieve selected filter options
    $filters = isset($_POST['filter']) ? $_POST['filter'] : array();
    $status_filter = isset($_POST['status_filter']) ? $_POST['status_filter'] : array();

    // Build the filter condition
    $filterCondition = '';
    $statusFilterCondition = '';
    if (!empty($filters)) {
        $filterCondition = "AND bill_by IN ('" . implode("','", $filters) . "')";
    }

    if (!empty($status_filter)) {
        if (is_array($status_filter)) {
            $statusFilterCondition = "AND status IN ('" . implode("','", $status_filter) . "')";
        } else {
            $statusFilterCondition = "AND status = '$status_filter'";
        }
    }

        if (isset($_SESSION['id'])) {
            // Get the user information before destroying the session
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "Bill Excel downloaded";
        
            // Call the function to insert user activity log
            logUserActivity($userId, $username, $role, $action);
        }

    // Query to fetch data between two dates and apply filter conditions
    $query = "SELECT * FROM bill WHERE date BETWEEN '$from_date' AND '$to_date' $filterCondition $statusFilterCondition";
    $result = $con->query($query);

    // Generate Excel file
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'Bill No');
    $sheet->setCellValue('B1', 'Date');
    $sheet->setCellValue('C1', 'Bill by');
    $sheet->setCellValue('D1', 'STB No');
    $sheet->setCellValue('E1', 'Name');
    $sheet->setCellValue('F1', 'Phone');
    $sheet->setCellValue('G1', 'Description');
    $sheet->setCellValue('H1', 'Payment Mode');
    $sheet->setCellValue('I1', 'Bill Amount');
    $sheet->setCellValue('J1', 'Discount');
    $sheet->setCellValue('K1', 'Rs');
    $sheet->setCellValue('L1', 'Status');

    $row = 2;
    while ($row_data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $row_data['billNo']);
        $sheet->setCellValue('B' . $row, $row_data['date']);
        $sheet->setCellValue('C' . $row, "'" . $row_data['bill_by']);
        $sheet->setCellValue('D' . $row, "'" . $row_data['stbno']);  // added at the beginning
        $sheet->setCellValue('E' . $row, $row_data['name']);
        $sheet->setCellValue('F' . $row, $row_data['phone']);
        $sheet->setCellValue('G' . $row, $row_data['description']);
        $sheet->setCellValue('H' . $row, $row_data['pMode']);
        $sheet->setCellValue('I' . $row, $row_data['paid_amount']);
        $sheet->setCellValue('J' . $row, $row_data['discount']);
        $sheet->setCellValue('K' . $row, $row_data['Rs']);
        $sheet->setCellValue('L' . $row, $row_data['status']);
        $row++;
    }

    // Auto adjust column width
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }

    $writer = new Xls($spreadsheet);
    $filename = 'Bill_Data_' . $session_username . '_' . $currentDate . '_' . $currentTime . '.xls';
    $filepath = 'bill-excel-downloaded-files/' . $filename; // Folder path + filename

    // Save the Excel file in the specified folder
    $writer->save($filepath);

    // Set the appropriate headers for downloading the file
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    header('Content-Length: ' . filesize($filepath));

    // Read and output the file contents
    readfile($filepath);

    exit();
}

// ...
?>
