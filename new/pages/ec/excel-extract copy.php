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
    $csvData = implode(',', $commaSeparatedValues);

    // Return both CSV and row count
    return [$csvData, count($commaSeparatedValues)];
}

$result1 = $result2 = null; // For both files' results

// Process uploaded files
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for the first file
    if (isset($_FILES['excelFile1'])) {
        $uploadedFile1 = $_FILES['excelFile1']['tmp_name'];

        if (is_uploaded_file($uploadedFile1)) {
            try {
                // Process Excel data
                list($csvData1, $totalRows1) = processExcelData($uploadedFile1);

                $result1 = [
                    'type' => 'success',
                    'message' => "File 1 processed successfully! Total rows: $totalRows1.<br>Data: <br><textarea class='form-control' readonly rows='5'>$csvData1</textarea>"
                ];
            } catch (Exception $e) {
                $result1 = [
                    'type' => 'danger',
                    'message' => "Error processing file: " . htmlspecialchars($e->getMessage())
                ];
            }
        } else {
            $result1 = [
                'type' => 'danger',
                'message' => "File 1 upload failed. Please try again."
            ];
        }
    }

    // Check for the second file
    if (isset($_FILES['excelFile2'])) {
        $uploadedFile2 = $_FILES['excelFile2']['tmp_name'];

        if (is_uploaded_file($uploadedFile2)) {
            try {
                // Process Excel data
                list($csvData2, $totalRows2) = processExcelData($uploadedFile2);

                $result2 = [
                    'type' => 'success',
                    'message' => "File 2 processed successfully! Total rows: $totalRows2.<br>Data: <br><textarea class='form-control' readonly rows='5'>$csvData2</textarea>"
                ];
            } catch (Exception $e) {
                $result2 = [
                    'type' => 'danger',
                    'message' => "Error processing file: " . htmlspecialchars($e->getMessage())
                ];
            }
        } else {
            $result2 = [
                'type' => 'danger',
                'message' => "File 2 upload failed. Please try again."
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excel Processor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Data copied to clipboard!');
            });
        }
    </script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4>Excel File Processor</h4>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="excelFile1" class="form-label">Upload First Excel File (.xlsx):</label>
                            <input type="file" name="excelFile1" id="excelFile1" class="form-control" accept=".xlsx" required>
                        </div>
                        <div class="mb-3">
                            <label for="excelFile2" class="form-label">Upload Second Excel File (.xlsx):</label>
                            <input type="file" name="excelFile2" id="excelFile2" class="form-control" accept=".xlsx" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Process</button>
                    </form>
                </div>
                
                <!-- Display result for File 1 -->
                <?php if (!empty($result1)) : ?>
                    <div class="card mt-4 shadow-lg">
                        <div class="card-body bg-<?php echo $result1['type']; ?> bg-opacity-25 border-<?php echo $result1['type']; ?> rounded">
                            <h5 class="card-title text-center text-<?php echo $result1['type']; ?> fw-bold">
                                <?php if ($result1['type'] === 'success'): ?>
                                    <i class="bi bi-check-circle-fill"></i> File 1 Result
                                <?php else: ?>
                                    <i class="bi bi-x-circle-fill"></i> Error
                                <?php endif; ?>
                            </h5>
                            <p class="card-text text-center"><?php echo $result1['message']; ?></p>
                            <?php if ($result1['type'] === 'success'): ?>
                                <div class="text-center mt-3">
                                    <button class="btn btn-secondary" onclick="copyToClipboard(`<?php echo htmlspecialchars($csvData1); ?>`)">Copy to Clipboard</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Display result for File 2 -->
                <?php if (!empty($result2)) : ?>
                    <div class="card mt-4 shadow-lg">
                        <div class="card-body bg-<?php echo $result2['type']; ?> bg-opacity-25 border-<?php echo $result2['type']; ?> rounded">
                            <h5 class="card-title text-center text-<?php echo $result2['type']; ?> fw-bold">
                                <?php if ($result2['type'] === 'success'): ?>
                                    <i class="bi bi-check-circle-fill"></i> File 2 Result
                                <?php else: ?>
                                    <i class="bi bi-x-circle-fill"></i> Error
                                <?php endif; ?>
                            </h5>
                            <p class="card-text text-center"><?php echo $result2['message']; ?></p>
                            <?php if ($result2['type'] === 'success'): ?>
                                <div class="text-center mt-3">
                                    <button class="btn btn-secondary" onclick="copyToClipboard(`<?php echo htmlspecialchars($csvData2); ?>`)">Copy to Clipboard</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
