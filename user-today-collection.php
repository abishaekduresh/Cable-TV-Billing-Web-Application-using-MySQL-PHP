<?php
session_start();
include "dbconfig.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    $session_username = $_SESSION['username'];
    
    ?>

    <table>
      <tr>
        <td>
            <center>
                <p style="font-family:Arial; font-size:17px"><b>THOOYAVAN PDP CABLE TV</b>
                    <br>260,Udangudi Road, Thisayanvilai
                    <br>Phone : +91 9842181951</p>
            </center>
        </td>
      </tr>
    </table>

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
    
        // Use the sum values as needed
        // echo "The sum of the column is: " . $sumBillAmt;
    } else {
        // Query execution failed
        echo "Error executing the query: " . mysqli_error($con);
    }
?>


<br>
<table border="1">
    <tr>
        <th colspan="2"><center>Today Collection Report</center></th>
    </tr>
    <tr>
        <th>User</th>
        <td><?php echo $session_username; ?></td>
    </tr>
    <tr>
        <th>Indiv & Group</th>
        <td><?php echo $totalBill; ?>&nbsp;&&nbsp;<?= $totalGroupBillCount ?></td>
    </tr>
    <tr>
        <th>Date</th>
        <td><?php echo $currentDate; ?></td>
    </tr>
    <tr>
        <th>Time</th>
        <td><?php echo $currentTimeA; ?></td>
    </tr>
    <!-- <tr>
        <th>Indiv Bill Amount</th>
        <td><?php //echo $totalBillAmount; ?></td>
    </tr>
    <tr>
        <th>Indiv Discount</th>
        <td><?php //echo $totalDiscount; ?></td>
    </tr> -->
    <tr>
        <th>Indiv Rs.</th>
        <td><?php echo $totalRs; ?></td>
    </tr>
    <!-- <tr>
        <th>Group Bill Amount</th>
        <td><?php //echo $groupTotalBillAmount; ?></td>
    </tr>
    <tr>
        <th>Group Discount</th>
        <td><?php //echo $groupTotalDiscount; ?></td>
    </tr> -->
    <tr>
        <th>Group Rs.</th>
        <td><?php echo $groupTotalRs; ?></td>
    </tr>
    <tr>
        <th>Income</th>
        <td><?php echo $sumIncome ; ?></td>
    </tr>
    <tr>
        <th>Expense</th>
        <td><?php echo $sumExpense; ?></td>
    </tr>
    <tr>
        <th>Balance</th>
        <td style="font-weight: bold;"><?php echo $groupTotalRs+$totalRs+$sumIncome-$sumExpense; ?></td>
    </tr>
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
