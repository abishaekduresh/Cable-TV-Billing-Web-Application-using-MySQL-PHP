<?php
// Get the JSON string from the URL parameter
if (isset($_GET['d'])) {
    $jsonData = urldecode($_GET['d']);  // Decode the URL-encoded JSON string
    $data = json_decode($jsonData, true);  // Convert JSON string to PHP associative array
} else {
    echo "No data received.";
}
?>


<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Initialize DOMPDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'Arial');
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

// HTML content for the receipt
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Collection Summary</title>
    <style>
        @page {
            margin-top: 0;
            margin-left: 5;
            margin-right: 5;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            font-size: 12px;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0; /* Removes left and right margins */
            padding: 0; /* Removes any padding */
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            font-size: 10px;
        }

        th {
            background-color: #e0e0e0;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .header-cell {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding: 8px;
            border-bottom: 1px solid black;
        }

        .footer-cell {
            text-align: center;
            padding-top: 5px;
            font-style: italic;
            border-top: 1px solid black;
        }

        .category-total {
            background-color: #d9ead3;
            font-weight: bold;
        }

        .grand-total {
            background-color: #ffe599;
            font-weight: bold;
            font-size: 10px;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

<h2>Collection Summary</h2>

<table>
    <tr>
        <td colspan="5" class="header-cell">
            THOOYAVAN PDP CABLE TV <br>
            260, Udangudi Road, Thisayanvilai <br>
            Cell: +91 9842181951
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-left"><strong></strong> ' . $data['userData']['username'] . ' (' . $data['userData']['name'] . ')</td>
        <td colspan="3" class="text-right"><strong>Collection Date:</strong> ' . $data['dueMonthDate'] . '</td>
    </tr>
    <tr>
        <th>Type</th>
        <th>Payment Mode</th>
        <th>Bill Count</th>
        <th>Amount</th>
        <th>Discount</th>
    </tr>

    <!-- Individual Payments -->
    <tr>
        <td rowspan="6">Individual</td>
        <td>Cash</td>
        <td>' . $data['indivData']['cash']['count'] . '</td>
        <td>' . $data['indivData']['cash']['amt'] . '</td>
        <td>' . $data['indivData']['cash']['discount'] . '</td>
    </tr>
    <tr>
        <td>Paytm</td>
        <td>' . $data['indivData']['paytm']['count'] . '</td>
        <td>' . $data['indivData']['paytm']['amt'] . '</td>
        <td>' . $data['indivData']['paytm']['discount'] . '</td>
    </tr>
    <tr>
        <td>Gpay</td>
        <td>' . $data['indivData']['gpay']['count'] . '</td>
        <td>' . $data['indivData']['gpay']['amt'] . '</td>
        <td>' . $data['indivData']['gpay']['discount'] . '</td>
    </tr>
    <tr>
        <td>Credit</td>
        <td>' . $data['indivData']['credit']['count'] . '</td>
        <td>' . $data['indivData']['credit']['amt'] . '</td>
        <td>' . $data['indivData']['credit']['discount'] . '</td>
    </tr>
    <tr>
        <td style="color:red;">Cancelled</td>
        <td style="color:red; font-weight:bold;">' . $data['indivCancelCount'] . '</td>
        <td>-</td>
        <td>-</td>
    </tr>
    <tr class="category-total">
        <td>Total</td>
        <td>' . $data['indivData']['totCount'] . '</td>
        <td>' . $data['indivData']['totAmt'] . '</td>
        <td>' . $data['indivData']['totDis'] . '</td>
    </tr>

    <!-- Group Payments -->
    <tr>
        <td rowspan="6">Group</td>
        <td>Cash</td>
        <td>' . $data['groupData']['cash']['count'] . '</td>
        <td>' . $data['groupData']['cash']['amt'] . '</td>
        <td>' . $data['groupData']['cash']['discount'] . '</td>
    </tr>
    <tr>
        <td>Paytm</td>
        <td>' . $data['groupData']['paytm']['count'] . '</td>
        <td>' . $data['groupData']['paytm']['amt'] . '</td>
        <td>' . $data['groupData']['paytm']['discount'] . '</td>
    </tr>
    <tr>
        <td>Gpay</td>
        <td>' . $data['groupData']['gpay']['count'] . '</td>
        <td>' . $data['groupData']['gpay']['amt'] . '</td>
        <td>' . $data['groupData']['gpay']['discount'] . '</td>
    </tr>
    <tr>
        <td>Credit</td>
        <td>' . $data['groupData']['credit']['count'] . '</td>
        <td>' . $data['groupData']['credit']['amt'] . '</td>
        <td>' . $data['groupData']['credit']['discount'] . '</td>
    </tr>
    <tr>
        <td style="color:red;">Cancelled</td>
        <td style="color:red; font-weight:bold;">' . $data['groupCancelCount'] . '</td>
        <td>-</td>
        <td>-</td>
    </tr>
    <tr class="category-total">
        <td>Total</td>
        <td>' . $data['groupData']['totCount'] . '</td>
        <td>' . $data['groupData']['totAmt'] . '</td>
        <td>' . $data['groupData']['totDis'] . '</td>
    </tr>

    <!-- POS Payments -->
    <tr>
        <td rowspan="6">POS</td>
        <td>Cash</td>
        <td>' . $data['posData']['cash']['count'] . '</td>
        <td>' . $data['posData']['cash']['amt'] . '</td>
        <td>' . $data['posData']['cash']['discount'] . '</td>
    </tr>
    <tr>
        <td>Paytm</td>
        <td>' . $data['posData']['paytm']['count'] . '</td>
        <td>' . $data['posData']['paytm']['amt'] . '</td>
        <td>' . $data['posData']['paytm']['discount'] . '</td>
    </tr>
    <tr>
        <td>Gpay</td>
        <td>' . $data['posData']['gpay']['count'] . '</td>
        <td>' . $data['posData']['gpay']['amt'] . '</td>
        <td>' . $data['posData']['gpay']['discount'] . '</td>
    </tr>
    <tr>
        <td>Credit</td>
        <td>' . $data['posData']['credit']['count'] . '</td>
        <td>' . $data['posData']['credit']['amt'] . '</td>
        <td>' . $data['posData']['credit']['discount'] . '</td>
    </tr>
    <tr>
        <td style="color:red;">Cancelled</td>
        <td style="color:red; font-weight:bold;">' . $data['posCancelCount'] . '</td>
        <td>-</td>
        <td>-</td>
    </tr>
    <tr class="category-total">
        <td>Total</td>
        <td>' . $data['posData']['totCount'] . '</td>
        <td>' . $data['posData']['totAmt'] . '</td>
        <td>' . $data['posData']['totDis'] . '</td>
    </tr>

    <!-- Grand Total -->
    <tr class="grand-total">
        <td colspan="2" rowspan="4">Grand Total</td>
        <td colspan="2">Total Amount</td>
        <td>' . $data['totAmt'] . '</td>
    </tr>

    <tr class="grand-total">
        <td colspan="2">Income</td>
        <td>' . $data['incomeExpense']['sumIncome'] . '</td>
    </tr>

    <tr class="grand-total">
        <td colspan="2">Expense (-)</td>
        <td>' . $data['incomeExpense']['sumExpense'] . '</td>
    </tr>

    <tr class="grand-total">
        <td colspan="2">Total Discount(-)</td>
        <td>' . $data['totDis'] . '</td>
    </tr>

    <tr class="grand-total">
        <td colspan="4">Balance</td>
        <td> ' . $data['bal'] . '</td>
    </tr>

    <tr class="grand-total">
        <td colspan="4">Amount in Hand</td>
        <td> ' . $data['amountInHand'] . '</td>
    </tr>

    <tr>
        <td colspan="5" class="footer-cell">
            Printed on ' . $data['date'] . ' at ' . $data['time'] . '
        </td>
    </tr>
</table>

</body>
</html>
';

// Load HTML into DOMPDF
$dompdf->loadHtml($html);

// Set custom paper size for 80mm thermal printer (227pt width ≈ 80mm)
$customPaper = [0, 0, 204.0944, 999];  // Increased width to utilize full space
$dompdf->setPaper($customPaper, 'portrait');

// Render PDF
$dompdf->render();

// Stream the PDF to the browser without saving (inline preview)
$dompdf->stream("collection_summary.pdf", [
    "Attachment" => false  // false for preview, true for download
]);
?>