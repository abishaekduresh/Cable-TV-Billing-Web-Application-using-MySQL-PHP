<?php
session_start();
include "dbconfig.php"; // Assuming dbconfig.php contains database connection settings
require "component.php"; // Assuming component.php contains helper functions
require 'domPDF_lib/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_SESSION['username'], $_SESSION['id'], $_SESSION['role']) && $_SESSION['role'] == 'admin') {
    $session_userid = $_SESSION['username'];
} else {
    header("Location: index.php");
    exit(); // Always exit after redirection
} 

$token = isset($_GET['token']) ? $_GET['token'] : '-';

$sql_settings = "SELECT * FROM settings";
$result_settings = $con->query($sql_settings);

if ($result_settings && $result_settings->num_rows > 0) {
    $row_settings = $result_settings->fetch_assoc();
    $appName1 = $row_settings['appName'];
    $appName2 = $row_settings['appName2'];
    $addr1 = $row_settings['addr1'];
    $addr2 = $row_settings['addr2'];
    $phone = $row_settings['phone'];
    $prtFooter1 = $row_settings['prtFooter1'];
    $prtFooter2 = $row_settings['prtFooter2'];
} else {
    echo 'No data found.';
        function closeTab() {
          echo "<script>
            setTimeout(function(){
              window.close();
            }, 20);
          </script>";
        }
          closeTab();
    exit();
    
}

$sql_bill = "SELECT * FROM pos_bill WHERE token = ?";
$stmt_bill = $con->prepare($sql_bill);
$stmt_bill->bind_param("s", $token);
$stmt_bill->execute();
$result_bill = $stmt_bill->get_result();

if ($result_bill && $result_bill->num_rows > 0) {
    $row_bill = $result_bill->fetch_assoc();
    $bill_no = $row_bill['bill_no'];
    $cus_name = $row_bill['cus_name'];
    $cus_phone = $row_bill['cus_phone'];
    $discount = $row_bill['discount'];
    $pay_mode = getPayModeName($row_bill['pay_mode']);
    if($row_bill['pay_mode'] != 4){
        $pay_mode = 'Paid';
    }
} else {
    echo "No data found.";
    exit();
}

$options = new Options();
$options->set('defaultFont', 'Courier');
$dompdf = new Dompdf($options);

$html = '
<!DOCTYPE html>
<html>
<head>
    <title>POS Invoice PDF Print</title>
    <style>
        @page {
            size: 3in 11in;
            margin: 0;
        }
        body {
            font-family: Arial Bold, DejaVu Sans, sans-serif;
            margin: 0.1in;
            padding: 0;
            width: 2.8in;
        }
        h1 {
            color: black;
            font-size: 12px;
            margin: 0;
        }
        p {
            font-size: 12px;
            margin: 0;
        }
        
        .center{
            text-align: center;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 10px;
            padding-top: 2px;
            padding-right: 10px;
            padding-bottom: 4px;
            padding-left: 2px;
        }
        table, th, td {
            border: 0.8px solid black;
        }
        th, td {
            padding: 2px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div style="padding-bottom: 4px;">
        <h1 class="center">' . $appName1 . '</h1>
        <h1 class="center">' . $addr1 . ' - ' . $addr2 . '</h1>
        <h1 class="center">Cell: ' . $phone . '</h1>
    </div>
    <div style="border-top: 1px dashed;">
    <!--p class="center">Invoice</p-->
    <p style="text-align: right; padding-right: 10px;">INVOICE - '. $bill_no ;
if ($cus_phone > 0) {
    $html .= '<p><b>Name: </b> ' . $cus_name . ' </br><b>Phone:</b> ' . $cus_phone . '</p>';
}

$html .= '<table>
      <tr>
        <th>Item</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Amt</th>
      </tr>';

$query_items = "SELECT * FROM pos_bill_items WHERE token = ?";
$stmt_items = $con->prepare($query_items);
$stmt_items->bind_param("s", $token);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

$total_amount = 0;

// Fetch data and append to HTML
while ($row_items = $result_items->fetch_assoc()) {
    // Fetch product name using getProductName function
    $productName = getProductName($con, $row_items['pos_product_id']); // Assuming 'product_id' is the correct column name
    if ($productName === false) {
        // Handle error if product name retrieval fails
        $productName = 'Error: Product Name Not Found';
    }
    $amount = $row_items['price'] * $row_items['qty'];
    $total_amount += $amount;
    $html .= '<tr>
        <td>' . htmlspecialchars($productName) . '</td>
        <td>' . $row_items['price'] . '</td>
        <td>' . $row_items['qty'] . '</td>
        <td>₹&nbsp;' . $amount . '</td>
    </tr>';
}

if($discount > 0){
    $html .= '<tr>
        <td rowspan="2" style="text-align: center"><b>'.$pay_mode.'</b></td>
        <th colspan="2" style="text-align: left">Discount</th>
        <th>₹&nbsp;' . $discount . '</th>
    </tr>
    <tr>
        <th colspan="2" style="text-align: left">Total</th>
        <th>₹&nbsp;' . $total_amount - $discount . '</th>
    </tr>';
}else{
    $html .= '<tr>
        <td></td>
        <th colspan="2" style="text-align: left">Round Total</th>
        <th>₹&nbsp;' . round($total_amount - $discount) . '</th>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center"><b>'.$pay_mode.'</b></td>
    </tr>';
}

if($prtFooter1 != ''){
    $html .= '
    <tr>
        <td colspan="4" style="text-align: center"><b>'.$prtFooter1.'</b></td>
    </tr>';
    if($prtFooter2 != ''){
        $html .= '
        <tr>
            <td colspan="4" style="text-align: center"><b>'.$prtFooter2.'</b></td>
        </tr>';
    }
}
$html .= '</table>
</body>
</html>';

$dompdf->loadHtml($html);

// Set the paper size and orientation for a 3-inch wide receipt
// $paperWidth = 3.0 * 25.4; // Convert inches to mm (3 inches to mm)
$paperWidth = 72; // 72 mm width
$paperHeight = 297; // Custom height in mm to ensure it fits content in one page
$dompdf->setPaper(array(0, 0, $paperWidth, $paperHeight));

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("sample.pdf", ["Attachment" => false]);
?>
