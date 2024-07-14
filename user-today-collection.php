<?php
session_start();
include "dbconfig.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    $session_username = $_SESSION['username'];
    
    ?>

    <!--<table>-->
    <!--  <tr>-->
    <!--    <td>-->
    <!--        <center>-->
    <!--            <p style="font-family:Arial; font-size:17px"><b>THOOYAVAN PDP CABLE TV</b>-->
    <!--                <br>260,Udangudi Road, Thisayanvilai-->
    <!--                <br>Phone : +91 9842181951</p>-->
    <!--        </center>-->
    <!--    </td>-->
    <!--  </tr>-->
    <!--</table>-->

    <?php
    include "dbconfig.php";


    $totalBill = 0;
    $totalBillAmount = 0;
    $totalDiscount = 0;
    $totalRs = 0;
    $groupTotalBillAmount = 0;
    $groupTotalDiscount = 0;
    $groupTotalRs = 0;
    
// Query to count the number of items
    $sql = "SELECT COUNT(*) AS count FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username' AND status = 'approve'";
    $result = $con->query($sql);


// Check if the query was successful
if ($result) {
    // Fetch the count from the result
    $row = $result->fetch_assoc();
    
    // Store the count in a variable
    $totalBill = $row['count'];
} else {
    echo "Error executing the query: " . $con->error;
}


// Query to fetch all values from table
$sql = "SELECT * FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username' AND status = 'approve'";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['paid_amount'])) {
            $totalBillAmount += $row['paid_amount'];
        }
        if (isset($row['discount'])) {
            $totalDiscount += $row['discount'];
        }
        if (isset($row['Rs'])) {
            $totalRs += $row['Rs'];
        }
    }
} else {
    echo "Error executing the query: " . $con->error;
}

// Payment Mode
$cashAmt = 0;
$GpayAmt = 0;
$PaytmAmt = 0;
$CreditAmt = 0;
$PaytmCount = 0;
$CashCount = 0;
$CreditCount = 0;
$GpayCount = 0;

$sql = "SELECT Rs, COUNT(Rs) as CashCount FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username' AND pMode= 'cash' AND status = 'approve'";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result !== false) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            $cashAmt += $row['Rs'];
            $CashCount += $row['CashCount'];
        }
    }
    
} else {
    // Echo an error message if the query fails
    echo "Error executing the Cash query: " . $con->error . "<br>";
    echo "Query: " . $sql;
}


$sql = "SELECT Rs, COUNT(Rs) as GpayCount FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username' AND pMode= 'gpay' AND status = 'approve'";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result !== false) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            $GpayAmt += $row['Rs'];
            $GpayCount += $row['GpayCount'];
        }
    }
    
} else {
    // Echo an error message if the query fails
    echo "Error executing the Cash query: " . $con->error . "<br>";
    echo "Query: " . $sql;
}

$sql = "SELECT pb.*, pbi.price
        FROM pos_bill pb
        JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
        WHERE DATE(pb.entry_timestamp) = '$currentDate' AND pb.token = pbi.token AND pb.status = 1";

$pos_amount = 0;
$result = $con->query($sql);

// Check if the query was successful
if ($result !== false) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        // Use correct key to access price
        $pos_amount += $row['price'];
    }
} else {
    // Echo an error message if the query fails
    echo "Error executing the query: " . $con->error . "<br>";
    echo "Query: " . $sql;
}

$sql = "SELECT Rs, COUNT(Rs) as PaytmCount FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username' AND pMode= 'Paytm' AND status = 'approve'";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result !== false) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            $PaytmAmt += $row['Rs'];
            $PaytmCount += $row['PaytmCount'];
        }
    }
    
} else {
    // Echo an error message if the query fails
    echo "Error executing the Cash query: " . $con->error . "<br>";
    echo "Query: " . $sql;
}


$sql = "SELECT Rs, COUNT(Rs) as CreditCount FROM bill WHERE date = '$currentDate' AND bill_by = '$session_username' AND pMode= 'Credit' AND status = 'approve'";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result !== false) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            $CreditAmt += $row['Rs'];
            $CreditCount += $row['CreditCount'];
        }
    }
    
} else {
    // Echo an error message if the query fails
    echo "Error executing the Cash query: " . $con->error . "<br>";
    echo "Query: " . $sql;
}




$sql = "SELECT COUNT(*) AS groupBillCount FROM billgroupdetails WHERE date = '$currentDate' AND billBy = '$session_username' AND status = 'approve'";
$result = $con->query($sql);


// Check if the query was successful
if ($result) {
// Fetch the count from the result
$row = $result->fetch_assoc();

// Store the count in a variable
$totalGroupBillCount = $row['groupBillCount'];
} else {
echo "Error executing the query: " . $con->error;
}

// Query to fetch all values from table
$sql = "SELECT * FROM billgroupdetails WHERE date = '$currentDate' AND billBy = '$session_username' AND status = 'approve'";

// Execute the query
$result = $con->query($sql);

// Check if the query was successful
if ($result) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['billAmount'])) {
            $groupTotalBillAmount += $row['billAmount'];
        }
        if (isset($row['discount'])) {
            $groupTotalDiscount += $row['discount'];
        }
        if (isset($row['Rs'])) {
            $groupTotalRs += $row['Rs'];
        }
    }
} else {
    echo "Error executing the query: " . $con->error;
}

?>

<?php

    // Initialize variables with default values or null
    $sumIncome = 0;
    $sumExpense = 0;
    
    
    $sumIncomeExpense ="SELECT
      (SELECT SUM(amount) FROM in_ex WHERE date = '$currentDate' AND type = 'Income' AND username = '$session_username') AS sumIncome,
      (SELECT SUM(amount) FROM in_ex WHERE date = '$currentDate' AND type = 'Expense' AND username = '$session_username') AS sumExpense";
    
    $resultsumIncomeExpense = mysqli_query($con, $sumIncomeExpense);
    
    // Check if the query executed successfully
    if ($resultsumIncomeExpense) {
        // Fetch the result row
        $row = mysqli_fetch_assoc($resultsumIncomeExpense);
    
        // Assign the sum value to the variables if available
        if ($row) {
            $sumIncome = $row['sumIncome'];
            $sumExpense = $row['sumExpense'];
        }
        $sumIncome = !empty($sumIncome) ? $sumIncome : '0';
        $sumExpense = !empty($sumExpense) ? $sumExpense : '0';
    
        // Use the sum values as needed
        // echo "The sum of the column is: " . $sumBillAmt;
    } else {
        // Query execution failed
        echo "Error executing the query: " . mysqli_error($con);
    }
?>


<table border="0">
    <tr style="border-top: 1px solid #000;">
        <td colspan="3">
            <center>
                <p style="font-family:Arial; font-size:16px"><b>THOOYAVAN PDP CABLE TV</b>
                    <br>260,Udangudi Road, Thisayanvilai
                    <br>Phone : +91 9842181951
                    <br/><?php echo $currentDate; ?>&nbsp;<?php echo $currentTimeA; ?></p>
            </center>
        </td>
    </tr>
    <tr style="border-top: 1px solid #000;">
        <th style="border-right: 1px solid #000;">Indiv & Group</th>
        <td colspan="2"><?php echo $totalBill; ?>&nbsp;&&nbsp;<?= $totalGroupBillCount ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">Indiv</th>
        <td>₹&nbsp;<?php echo $totalRs; ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">Group</th>
        <td>₹&nbsp;<?php echo $groupTotalRs; ?></td>
    </tr>
    <!-- <tr>
        <th>Indiv Bill Amount</th>
        <td><?php //echo $totalBillAmount; ?></td>
    </tr>
    <tr>
        <th>Indiv Discount</th>
        <td><?php //echo $totalDiscount; ?></td>
    </tr> -->
    <!--<tr>-->
    <!--    <th>Indiv Rs.</th>-->
    <!--    <td></td>-->
    <!--</tr>-->
    <!-- <tr>
        <th>Group Bill Amount</th>
        <td><?php //echo $groupTotalBillAmount; ?></td>
    </tr>
    <tr>
        <th>Group Discount</th>
        <td><?php //echo $groupTotalDiscount; ?></td>
    </tr> -->
    <!--<tr>-->
    <!--    <th>Group Rs.</th>-->
    <!--    <td></td>-->
    <!--</tr>-->
    <tr>
        <th style="border-right: 1px solid #000;">Income</th>
        <td>₹&nbsp;<?php echo $sumIncome ; ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">Expense (-)</th>
        <td>₹&nbsp;<?php echo $sumExpense; ?></td>
    </tr>
    <tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">
        <th style="border-right: 1px solid #000;">Balance</th>
        <td style="font-weight: bold;">₹&nbsp;<?php echo $balanceAmt=$groupTotalRs+$totalRs+$sumIncome-$sumExpense; ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">POS Amount</th>
        <td>₹&nbsp;<?php echo $pos_amount ; ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">Indiv.Cash</th>
        <td>₹&nbsp;<?php echo $cashAmt; ?>&nbsp;->&nbsp;<?= $CashCount ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">Indiv.Gpay (-)</th>
        <td>₹&nbsp;<?php echo $GpayAmt; ?>&nbsp;->&nbsp;<?= $GpayCount ?></td>
    </tr>
    <tr>
        <th style="border-right: 1px solid #000;">Indiv.Paytm (-)</th>
        <td>₹&nbsp;<?php echo $PaytmAmt; ?>&nbsp;->&nbsp;<?= $PaytmCount ?></td>
    </tr>
    <tr style="border-bottom: 1px solid #000; border-bottom: 1px solid #000;">
        <th style="border-right: 1px solid #000;">Indiv.Credit (-)</th>
        <td>₹&nbsp;<?php echo $CreditAmt; ?>&nbsp;->&nbsp;<?= $CreditCount ?></td>
    </tr>
    <!--<tr style="border-top: 1px solid #000; border-bottom: 1px solid #000;">-->
    <!--    <th style="border-right: 1px solid #000;">In Hand</th>-->
    <!--    <td style="font-weight: bold;">₹&nbsp;<?php echo $inHandAmt=($groupTotalRs+$totalRs+$sumIncome)+($GpayAmt-$PaytmAmt-$CreditAmt-$sumExpense); ?></td>-->
    <!--</tr>-->
</table>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        text-align: left;
        padding: 8px;
    }
    
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
</style>

<?php

        // Auto print using JavaScript
        echo "<script type='text/javascript'>
            window.onload = function() {
                window.print();
            }
        </script>";
    
        // Tab Close function
        function closeTab() {
          echo "<script>
            setTimeout(function(){
              window.close();
            }, 1000);
          </script>";
}

// Usage example
closeTab();

// Close the connection
$con->close();

} else {
	header("Location: index.php");
}
?>
