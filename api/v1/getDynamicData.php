<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once("../../dbconfig.php");
require_once("../../component.php");

// Retrieve JSON data
$data = json_decode(file_get_contents('php://input'), true);
header('Content-Type: application/json');

// Validate key inputs
$source = isset($data['source']) ? $data['source'] : '';
$columns = isset($data['columns']) ? $data['columns'] : [];
$separator = isset($data['separator']) ? $data['separator'] : "\n";

// Filters
$fromDate = isset($data['fromDate']) ? $data['fromDate'] : null;
$toDate = isset($data['toDate']) ? $data['toDate'] : null;
$mso = isset($data['mso']) ? $data['mso'] : '';
$status = isset($data['status']) ? $data['status'] : '';

if (empty($source) || empty($columns)) {
    echo json_encode(['status' => '0', 'error' => 'Invalid Request: Source and Columns are required.']);
    exit;
}

// Convert separator token to actual character
$sepChar = "\n"; // Default new line
if ($separator === 'comma') $sepChar = ", ";
elseif ($separator === 'tab') $sepChar = "\t";
elseif ($separator === 'pipe') $sepChar = " | ";
elseif ($separator === 'newline') $sepChar = "\n";

$resultData = [];
$query = "";

try {
    if ($source === 'customer') {
        // Build Column String
        // Validation: Ensure columns exist in table (basic safelist)
        $allowedCols = ['stbno', 'name', 'phone', 'mso', 'customer_area_code', 'amount'];
        $selectCols = [];
        foreach ($columns as $col) {
            if (in_array($col, $allowedCols)) {
                $selectCols[] = $col;
            }
        }
        
        if (empty($selectCols)) {
            echo json_encode(['status' => '0', 'error' => 'No valid columns selected.']);
            exit;
        }
        
        $colString = implode(", ", $selectCols);
        $query = "SELECT $colString FROM customer WHERE 1=1";

        if (!empty($mso) && $mso !== 'all') {
            $query .= " AND mso = '$mso'";
        }
        // Customer status logic if needed (e.g. rc_dc)
        
    } elseif ($source === 'indiv_bill') {
         $allowedCols = ['billNo', 'due_month_timestamp', 'name', 'phone', 'stbno', 'mso', 'Rs', 'status'];
         $selectCols = [];
         foreach ($columns as $col) {
             if (in_array($col, $allowedCols)) {
                 $selectCols[] = $col;
             }
         }
         
         if (empty($selectCols)) {
             echo json_encode(['status' => '0', 'error' => 'No valid columns selected.']);
             exit;
         }

         $colString = implode(", ", $selectCols);
         $query = "SELECT $colString FROM bill WHERE 1=1";
         
         if ($fromDate && $toDate) {
             $query .= " AND DATE(due_month_timestamp) BETWEEN '$fromDate' AND '$toDate'";
         }
         if (!empty($mso) && $mso !== 'all') {
             $query .= " AND mso = '$mso'";
         }
         
    } elseif ($source === 'group_bill') {
         $allowedCols = ['billNo', 'date', 'name', 'stbNo', 'mso', 'group_id'];
         $selectCols = [];
         foreach ($columns as $col) {
             if ($col === 'group_id') { // Special handling for group name if needed, usually we join
                  $selectCols[] = 'group_id'; 
             } else if (in_array($col, $allowedCols)) {
                 $selectCols[] = $col;
             }
         }
         
         if (empty($selectCols)) {
             echo json_encode(['status' => '0', 'error' => 'No valid columns selected.']);
             exit;
         }

         $colString = implode(", ", $selectCols);
         $query = "SELECT $colString FROM billgroup WHERE 1=1";
         
         if ($fromDate && $toDate) {
             $query .= " AND date BETWEEN '$fromDate' AND '$toDate'";
         }
    } else {
        echo json_encode(['status' => '0', 'error' => 'Invalid Source.']);
        exit;
    }

    $sqlResult = mysqli_query($con, $query);
    
    if (!$sqlResult) {
        throw new Exception(mysqli_error($con));
    }

    $formattedLines = [];
    
    while ($row = mysqli_fetch_assoc($sqlResult)) {
        $lineParts = [];
        foreach ($columns as $col) {
            // Handle special formatting if needed
            if($source === 'group_bill' && $col === 'group_id') {
                 $lineParts[] = fetchGroupName($row[$col]); 
            } else {
                 $lineParts[] = isset($row[$col]) ? $row[$col] : '';
            }
        }
        $formattedLines[] = implode(" - ", $lineParts); // Separate columns within a line with ' - ' or space?
                                                        // USER REQUEST: "comma separated dynamic"
                                                        // Actually, user wants "in line or comma separated".
                                                        // Let's assume columns are joined by a delimiter (e.g., custom or ' - ') 
                                                        // and RS are joined by $separator.
                                                        // Let's make internal column separator configurable or fixed.
                                                        // Fixed " " or " - " is good for WhatsApp usually.
    }
    
    // Allow user to define Column Separator? For now let's default to " " or user might select just one column.
    // If multiple columns, usually "Name - Phone - Amount" looks good.
    $colDelim = "  "; // Double space
    
    $finalLines = [];
    mysqli_data_seek($sqlResult, 0); // Reset pointer
    while ($row = mysqli_fetch_assoc($sqlResult)) {
         $lineParts = [];
         foreach ($columns as $col) {
            if($source === 'group_bill' && $col === 'group_id') {
                 $lineParts[] = fetchGroupName($row[$col]); 
            } else {
                 $lineParts[] = isset($row[$col]) ? $row[$col] : '';
            }
         }
         $finalLines[] = implode($colDelim, $lineParts);
    }
    
    $formattedText = implode($sepChar, $finalLines);
    
    echo json_encode([
        'status' => '1',
        'result_count' => count($finalLines),
        'formatted_text' => $formattedText
    ]);

} catch (Exception $e) {
    echo json_encode(['status' => '0', 'error' => $e->getMessage()]);
}

$con->close();
?>
