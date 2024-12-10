<?php
require '../../main.php'; // Include PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;

function processExcelData($filePath) {
    $spreadsheet = IOFactory::load($filePath);
    $sheet = $spreadsheet->getActiveSheet();

    $highestRow = $sheet->getHighestRow(); // Get total rows
    $commaSeparatedValues = []; // Hold cleaned data

    for ($row = 1; $row <= $highestRow; $row++) {
        // Read cell from column 'B'
        $cellValue = $sheet->getCell('B' . $row)->getValue();

        // Sanitize the cell value
        if (!is_null($cellValue) && trim($cellValue) !== '') {
            $cleanedValue = ltrim(trim($cellValue), "'"); // Remove leading apostrophe and whitespace
            $commaSeparatedValues[] = htmlspecialchars($cleanedValue); // Ensure safe data
        }
    }

    // Convert to CSV
    $csvData = implode(',', array_unique($commaSeparatedValues));

    // Return both CSV and row count
    return [$csvData, count($commaSeparatedValues)];
}

$result1 = $result2 = $compareResult = null;
$userInput = ''; // Variable for user's comma-separated input

// Process uploaded files
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input (comma-separated data)
    if (isset($_POST['userInput'])) {
        $userInput = $_POST['userInput'];
    }

    // Check for the first file
    if (isset($_FILES['excelFile1'])) {
        $uploadedFile1 = $_FILES['excelFile1']['tmp_name'];

        if (is_uploaded_file($uploadedFile1)) {
            try {
                list($csvData1, $totalRows1) = processExcelData($uploadedFile1);
                $result1 = [
                    'type' => 'success',
                    'data' => $csvData1,
                    'message' => "Processed successfully! Total rows: $totalRows1.",
                ];
            } catch (Exception $e) {
                $result1 = [
                    'type' => 'danger',
                    'message' => "Error processing file: " . htmlspecialchars($e->getMessage()),
                ];
            }
        }
    }

    // Check for the second file
    if (isset($_FILES['excelFile2'])) {
        $uploadedFile2 = $_FILES['excelFile2']['tmp_name'];

        if (is_uploaded_file($uploadedFile2)) {
            try {
                list($csvData2, $totalRows2) = processExcelData($uploadedFile2);
                $result2 = [
                    'type' => 'success',
                    'data' => $csvData2,
                    'message' => "Processed successfully! Total rows: $totalRows2.",
                ];
            } catch (Exception $e) {
                $result2 = [
                    'type' => 'danger',
                    'message' => "Error processing file: " . htmlspecialchars($e->getMessage()),
                ];
            }
        }
    }

    // Perform comparison with user's input
    if (isset($result1['data']) && isset($result2['data']) && !empty($userInput)) {
        $input1Array = array_map('trim', explode(',', $result1['data']));
        $input2Array = array_map('trim', explode(',', $result2['data']));
        $userInputArray = array_map('trim', explode(',', $userInput));

        // Find unique values in user's input that are not in input1 or input2
        $notInInput1And2 = array_diff($userInputArray, array_merge($input1Array, $input2Array));
        $notInCount = count($notInInput1And2);

        $compareResult = [
            'type' => 'info',
            'message' => !empty($notInInput1And2) ? 
                "Unique data not found in both files:<br><strong style='font-size: 20px;'>" . implode(', &nbsp;', array_map(function($item) {
                    return htmlspecialchars($item) . ' <a href="../../../customer-history.php?search=' . urlencode($item) . '" target="_blank" title="Open in new tab"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
                }, $notInInput1And2)) . "</strong><br>Total: $notInCount" :
                "All data from your input is present in both files.",
        ];

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel File Processor & Compare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Excel File Processor & Compare</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="excelFile1" class="form-label">Upload First Excel File (.xlsx):</label>
                            <input type="file" name="excelFile1" id="excelFile1" class="form-control" accept=".xlsx" required>
                        </div>
                        <div class="mb-3">
                            <label for="excelFile2" class="form-label">Upload Second Excel File (.xlsx):</label>
                            <input type="file" name="excelFile2" id="excelFile2" class="form-control" accept=".xlsx" >
                        </div>
                        <div class="mb-3">
                            <label for="userInput" class="form-label">Enter Comma-Separated Data:</label>
                            <textarea class="form-control" name="userInput" rows="6" placeholder="E.g., 00008317000ABCD, 00008317000ABCD, ..."><?php echo htmlspecialchars($userInput); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Upload and Process</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    <div class="row mt-4">
        <?php if ($result1): ?>
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body bg-<?php echo $result1['type']; ?> bg-opacity-25 border-<?php echo $result1['type']; ?> rounded">
                        <h5 class="card-title text-<?php echo $result1['type']; ?> fw-bold">File 1</h5>
                        <p class="card-text"><?php echo $result1['message']; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($result2): ?>
            <div class="col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body bg-<?php echo $result2['type']; ?> bg-opacity-25 border-<?php echo $result2['type']; ?> rounded">
                        <h5 class="card-title text-<?php echo $result2['type']; ?> fw-bold">File 2</h5>
                        <p class="card-text"><?php echo $result2['message']; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($compareResult): ?>
        <div class="row mt-4">
            <div class="col">
                <div class="card shadow-lg">
                    <div class="card-body bg-<?php echo $compareResult['type']; ?> bg-opacity-25 border-<?php echo $compareResult['type']; ?> rounded">
                        <h5 class="card-title text-<?php echo $compareResult['type']; ?> fw-bold">Comparison Result</h5>
                        <p class="card-text"><?php echo $compareResult['message']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Fontawesome -->
<script src="https://kit.fontawesome.com/592a9320b6.js" crossorigin="anonymous"></script>
</body>
</html>
