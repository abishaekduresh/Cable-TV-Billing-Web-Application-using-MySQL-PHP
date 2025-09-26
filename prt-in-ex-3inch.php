<?php 
   session_start();
   require "dbconfig.php";
   require "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_username = $_SESSION['username']; 

// include "dbconfig.php";


// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$sql = "SELECT * FROM settings"; // Replace 'your_table_name' with your actual table name

$result = $con->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Loop through each row and fetch the data
    while ($row = $result->fetch_assoc()) {
        $appName = $row['appName'];
        $addr1 = $row['addr1'];
        $addr2 = $row['addr2'];
        $phone = $row['phone'];
        $footer1 = $row['prtFooter1'];
        $footer2 = $row['prtFooter2'];
    }
} else {
    echo "No data found.";
}

$hidePromotion = ($footer1 == NULL);

$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$categoryID = $_GET['category_id'];
$subcategoryID = $_GET['subcategory_id'];

// Initialize the WHERE clause
$whereClause = "date BETWEEN '$from_date' AND '$to_date'";

// Check if $categoryID and $subcategoryID are not empty
if (!empty($categoryID)) {
    $whereClause .= " AND category_id = $categoryID";
}

if (!empty($subcategoryID)) {
    $whereClause .= " AND subcategory_id = $subcategoryID";
}

// Build the complete query
$query = "SELECT * FROM in_ex WHERE $whereClause";

// $query = "SELECT * FROM in_ex WHERE date BETWEEN '$from_date' AND '$to_date' AND category_id = $categoryID AND subcategory_id = $subcategoryID"; // Modify 'your_table' with the actual table name
$result = mysqli_query($con, $query); // Assuming $connection is your database connection
$rowCount = mysqli_num_rows($result);
?>

<html>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <head>
        <style>
            body {
            font-family: Arial, sans-serif; 
            }
            table
                {
                    border: 1px solid  #000000;
                    padding: 0px;
                    border-spacing: 0px;
                    border-collapse: collapse;
                    width: 100%;
                    margin-left: auto;
                    margin-right: auto;
                }
            td,th
                {
                    border: 0px solid  #cccccc;
                    height: 28px;
                    vertical-align: center;
                    padding-left: 5px;
                    font-size: 16px;
                }
            .b_f
            {
                border:1px blue; 
                border-bottom-style: solid;
                border-top-style: solid;
                border-left-style: solid;
                border-right-style: solid;
            }
            .b_l
            {
                border:1px; 
                border-left-style: solid;
            }
            .b_r
            {
                border:1px; 
                border-right-style: solid;
            }		
            .b_t
            {
                border:1px; 
                border-top-style: solid;
            }			
            .b_b
            {
                border:1px; 
                border-bottom-style: solid;
            }					
    
            .spacer {
                margin-bottom: 2px; /* Use margin to create vertical spacing */
            }
            .spacer2 {
                margin-bottom: 5px; /* Use margin to create vertical spacing */
            }

            .container {
                padding: 5px; /* Use padding to create space inside an element */
                border: 1px solid #000; /* Add a 1px solid black border around the container */
                max-width: 300px; /* Optionally set a maximum width for the container */
                margin: 0 auto; /* Center the container horizontally on the page */
            }
            div.page
            {
                page-break-after: always;
                page-break-inside: avoid;
            }
        </style>	
    </head>
    
    <body>

<?php

$from_date = $_GET['from_date'];
$to_date = $_GET['to_date'];
$categoryID = $_GET['category_id'];
$subcategoryID = $_GET['subcategory_id'];

// Initialize the WHERE clause
$whereClause = "date BETWEEN '$from_date' AND '$to_date'";

// Check if $categoryID and $subcategoryID are not empty
if (!empty($categoryID)) {
    $whereClause .= " AND category_id = " . intval($categoryID);
}

if (!empty($subcategoryID)) {
    $whereClause .= " AND subcategory_id = " . intval($subcategoryID);
}
// Build the complete query
$query = "SELECT * FROM in_ex WHERE $whereClause";
$result = mysqli_query($con, $query); // Run query

// Header Section
?>
<table>
    <tr>
        <td>
            <div style="text-align:center; padding:0; margin:0;">
                <p style="font-family:Arial; font-size:17px; margin:0;">
                    <b><?= htmlspecialchars($appName) ?></b><br/>
                    <?= htmlspecialchars($addr1) ?>, <?= htmlspecialchars($addr2) ?><br/>
                    Phone : +91 <?= htmlspecialchars($phone) ?><br/>
                    <?= $session_username ?><br/>
                </p>
            </div>
        </td>
    </tr>
</table>

<?php
// Initialize totals (outside loop!)
$in_sum = 0;
$ex_sum = 0;

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $type = $row['type'];
        $amount = (float)$row['amount'];

        if ($type === 'Income') {
            $in_sum += $amount;
        } elseif ($type === 'Expense') {
            $ex_sum += $amount;
        }
?>
    <table align="center">
        <tr>
            <td style="border:1px; border-left-style:solid;"></td>
            <td colspan="2" style="border:1.5px; border-right-style:solid;">
                <b><?= formatDate($row['date']); ?> <?= convertTo12HourFormat($row['time']); ?></b>
                <?= $type === 'Expense' ? '' : '| Income' ?>
            </td>
        </tr>

        <tr>
            <td style="border:1px; border-left-style:solid;"></td>
            <td colspan="2" style="border:1.5px; border-right-style:solid;">
                <?= getCategoryName($con, $row['category_id']) ?> | 
                <?= getSubCategoryName($con, $row['subcategory_id']) ?>
            </td>
        </tr>

        <?php if (!empty(trim($row['remark']))) { ?>
        <tr>
            <td style="border:1px; border-left-style:solid;"></td>
            <td colspan="2" style="border:1.5px; border-right-style:solid;">
                <?= htmlspecialchars($row['remark']) ?>
            </td>
        </tr>
        <?php } ?>

        <tr>
            <td style="border:1px; border-left-style:solid;"></td>
            <td colspan="2" style="border:1.5px; border-right-style:solid;">
                Amount: <b><?= number_format($amount, 2) ?></b>
            </td>
        </tr>
    </table>
<?php
    } // end while
} // end if
?>

<!-- Totals + Footer -->
<table align="center">
    <tr>
        <td colspan="3" align="center" style="border:1px; border-top-style:solid; font-size: 14px;">
            <!-- Record<?= $rowCount != 1 ? 's' : '' ?> Found. <?= $rowCount ?>&nbsp; -->
            <?= $in_sum > 0 ? 'Income Total: ' . number_format($in_sum, 2) : '' ?>&nbsp;
            <?= $ex_sum > 0 ? 'Total: ' . number_format($ex_sum, 2) : '' ?>
        </td>
    </tr>
    <tr>
        <td colspan="3" align="center" style="border:1px; border-top-style:solid; font-size: 14px;">
            Printed on&nbsp;
            <?php
                $current_result = splitDateAndTime(strtotime($currentDateTime)); 
                echo formatDate($current_result['date']) . '&nbsp' . convertTo12HourFormat($current_result['time']);
            ?>
        </td>
    </tr>
</table>
    </body>
    </html>
    
    <?php 
    printClose(); 
?>



<?php }else{
	header("Location: logout.php");
} ?>