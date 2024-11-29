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
    require_once "dbconfig.php";
    
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


// Initialize the totals
$totalBillAmount = 0;
$totalDiscount = 0;
$totalRs = 0;

// Prepare the query (preferably with prepared statements to avoid SQL injection)
$sql = "SELECT paid_amount, discount, Rs FROM bill WHERE date = ? AND bill_by = ? AND status = 'approve'";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters to the prepared statement
$stmt->bind_param("ss", $currentDate, $session_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    // Fetch each row and calculate the totals
    while ($row = $result->fetch_assoc()) {
        // Sum the amounts for each column if the values are set
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

    // // Optionally, output the results for debugging
    // echo "Total Bill Amount: " . $totalBillAmount . "<br>";
    // echo "Total Discount: " . $totalDiscount . "<br>";
    // echo "Total Rs: " . $totalRs . "<br>";

} else {
    // Echo an error message if the query fails
    echo "Error executing the query: " . $stmt->error . "<br>";
}

// Close the statement
$stmt->close();


// Prepare the query (preferably with prepared statements to avoid SQL injection)
$sql = "SELECT Rs FROM bill WHERE date = ? AND bill_by = ? AND pMode = 'cash' AND status = 'approve'";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters to the prepared statement
$stmt->bind_param("ss", $currentDate, $session_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    $CashCount = 0;   // Initialize the count variable
    $cashAmt = 0;     // Initialize the sum variable

    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            $cashAmt += $row['Rs'];  // Add Rs to the total amount
            $CashCount++;            // Increment the count for each row
        }
    }

    // // Output the results (for debugging or user info)
    // echo "Total Cash Count: " . $CashCount . "<br>";
    // echo "Total Cash Amount: " . $cashAmt . "<br>";

} else {
    // Echo an error message if the query fails
    echo "Error executing the Cash query: " . $stmt->error . "<br>";
    echo "Query: " . $sql;
}

// Close the statement
$stmt->close();


// Prepare the query (preferably with prepared statements to avoid SQL injection)
$sql = "SELECT Rs FROM bill WHERE date = ? AND bill_by = ? AND pMode = 'gpay' AND status = 'approve'";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters to the prepared statement
$stmt->bind_param("ss", $currentDate, $session_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    $GpayCount = 0;
    $GpayAmt = 0;

    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            $GpayAmt += $row['Rs']; // Add Rs amount to the total
            $GpayCount++;           // Increment the count of gpay transactions
        }
    }
    
    // // You now have the total count and the total amount
    // echo "Total GPay Count: " . $GpayCount . "<br>";
    // echo "Total GPay Amount: " . $GpayAmt . "<br>";

} else {
    // Echo an error message if the query fails
    echo "Error executing the Cash query: " . $stmt->error . "<br>";
    echo "Query: " . $sql;
}

// Close the statement
$stmt->close();


$sql = "SELECT pb.*, SUM(pbi.price * pbi.qty) - pb.discount AS total_price
        FROM pos_bill pb
        JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
        WHERE DATE(pb.entry_timestamp) = '$currentDate' AND pb.token = pbi.token AND pb.status = 1 AND pb.username = '$session_username' AND pb.username = '$session_username' ";

$pos_amount = 0;
$result = $con->query($sql);

// Check if the query was successful
if ($result !== false) {
    // Fetch each row and calculate the sum
    while ($row = $result->fetch_assoc()) {
        // Use correct key to access price
        $pos_amount += $row['total_price'];
    }
} else {
    // Echo an error message if the query fails
    echo "Error executing the query: " . $con->error . "<br>";
    echo "Query: " . $sql;
}

// Initialize the variables to store totals
$PaytmAmt = 0;
$PaytmCount = 0;

// Prepare the query (preferably with prepared statements to avoid SQL injection)
$sql = "SELECT Rs FROM bill WHERE date = ? AND bill_by = ? AND pMode = 'Paytm' AND status = 'approve'";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters to the prepared statement
$stmt->bind_param("ss", $currentDate, $session_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    // Fetch each row and calculate the totals
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            // Sum the Rs amount for Paytm transactions
            $PaytmAmt += $row['Rs'];
            // Increment the count of Paytm transactions
            $PaytmCount++;
        }
    }

    // // Optionally, output the results for debugging
    // echo "Total Paytm Amount: " . $PaytmAmt . "<br>";
    // echo "Total Paytm Count: " . $PaytmCount . "<br>";

} else {
    // Echo an error message if the query fails
    echo "Error executing the query: " . $stmt->error . "<br>";
}

// Close the statement
$stmt->close();


// Initialize the variables to store totals
$CreditAmt = 0;
$CreditCount = 0;

// Prepare the query (preferably with prepared statements to avoid SQL injection)
$sql = "SELECT Rs FROM bill WHERE date = ? AND bill_by = ? AND pMode = 'Credit' AND status = 'approve'";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters to the prepared statement
$stmt->bind_param("ss", $currentDate, $session_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    // Fetch each row and calculate the totals
    while ($row = $result->fetch_assoc()) {
        if (isset($row['Rs'])) {
            // Sum the Rs amount for Credit transactions
            $CreditAmt += $row['Rs'];
            // Increment the count of Credit transactions
            $CreditCount++;
        }
    }

    // // Optionally, output the results for debugging
    // echo "Total Credit Amount: " . $CreditAmt . "<br>";
    // echo "Total Credit Count: " . $CreditCount . "<br>";

} else {
    // Echo an error message if the query fails
    echo "Error executing the query: " . $stmt->error . "<br>";
}

// Close the statement
$stmt->close();





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

// Initialize the total variables
$groupTotalBillAmount = 0;
$groupTotalDiscount = 0;
$groupTotalRs = 0;

// Prepare the query (preferably with prepared statements to avoid SQL injection)
$sql = "SELECT billAmount, discount, Rs FROM billgroupdetails WHERE date = ? AND billBy = ? AND status = 'approve'";

// Prepare the statement
$stmt = $con->prepare($sql);

// Bind parameters to the prepared statement
$stmt->bind_param("ss", $currentDate, $session_username);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the query was successful
if ($result) {
    // Fetch each row and calculate the totals
    while ($row = $result->fetch_assoc()) {
        // Sum the billAmount, discount, and Rs
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

    // // Optionally, output the results for debugging
    // echo "Total Bill Amount: " . $groupTotalBillAmount . "<br>";
    // echo "Total Discount: " . $groupTotalDiscount . "<br>";
    // echo "Total Rs: " . $groupTotalRs . "<br>";

} else {
    // Echo an error message if the query fails
    echo "Error executing the query: " . $stmt->error . "<br>";
}

// Close the statement
$stmt->close();


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
                    <br/><?php echo $currentDate; ?>&nbsp;<?php echo $currentTimeA; ?>
                    <br/>User: <?= $session_username; ?></p>
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
        <td>₹&nbsp;<?= $sumIncome+=$pos_amount ?></td>
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
        <td>₹&nbsp;<?php echo $pos_amount; ?></td>
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
