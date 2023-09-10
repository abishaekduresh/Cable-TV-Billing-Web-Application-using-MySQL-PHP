<?php 
   session_start();
   require "dbconfig.php";
   require "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_userid = $_SESSION['username']; ?>
        
<?php
include "dbconfig.php";

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$query = mysqli_query($con, "SELECT * FROM bill WHERE pMode='credit' AND status='approve'");

/// HEADER ///

// Perform a SELECT query to fetch data from the database
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

$hidePromotion = ($footer1 != NULL);

?>
<html>
    <head>
<style>
    body {
      font-family: Arial, sans-serif;
      font-size: 10px; /* You can adjust the size according to your preference */
    }

    th, td {
      border-style: dashed;
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
</style>

    </head>
<body>

    <table>
      <tr>
        <td>
            <center>
                <p style="font-family:Arial; font-size:17px"><b><?= $appName ?></b>
                    <br><?= $addr1 ?>, <?= $addr2 ?>
                    <br>Phone : +91 <?= $phone ?></p>
            </center>
        </td>
        </tr>
        <tr class="dotted-line">
                <th colspan="2"><center>Indiv Credit Bill List</center></th>
        </tr>
    </table>
    
<?php 

$sno=1;

if (mysqli_num_rows($query) > 0) {
    echo "<br><table border='1'>
        <tr>
            <!--th>Header</th-->
            <!--th>Data</th-->
        </tr>";

    while ($row = mysqli_fetch_array($query)) {
        // $billBy = $row["bill_by"];
        $stbNo = $row["stbno"];
        $billNo = $row["billNo"];
        $date = $row["date"];
        // $time = $row["time"];
        $billTo = $row["name"];
        $phone = $row["phone"];
        // $billAmount= $row["paid_amount"];
        // $discount = $row["discount"];
        //$hideDiscountRow = ($discount == 0); // Determine if the discount row should be hidden
        $Rs = $row["Rs"];
        //$hideRsRow = ($discount == 0); // Determine if the discount row should be hidden
        // $pMode = $row["pMode"];
        // $oldMonthBal = $row["oldMonthBal"];
        // $hideoldMonthBalRow = ($oldMonthBal == 0);
        
        // $hideStatusRow = ($pMode === 'cash' || $pMode === 'gpay');

    
?>
        <table border="1">
            <tr>
                <th width="4" height="4"><?=$sno++?></th>
                <td style="font-size: 12px;"><?= formatDate($date);?>&nbsp;/&nbsp;BillNo :<?= $billNo; ?><br/><?= $billTo;?><br/><?= $phone?><br>Bill Amount: <?= $Rs?></td>
                <td width="20" height="10"></td>
            </tr>
        </table>

<!-- <br/> -->

<!-- <img align="center" src="barcode128?id=00008317000ABCDd" width="290px" height="80px"> -->

<!-- <iframe align="center" src="barcode128?id=<?=$stbNo?>" width="290px" height="80px" frameborder="0"></iframe> -->


</body>
</html>
<?php  }  

        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            $username = $_SESSION['username'];
            $role = $_SESSION['role'];
            $action = "Indiv Credit Bill - List Printed";
        
            logUserActivity($userId, $username, $role, $action);
        }

?>

<!-- <div class="spacer2"></div>
<div <?php if ($hidePromotion) echo 'class="container"'; ?>>
    <div align="center"><?= $footer1 ?></div>
    <div class="spacer"></div>
    <div align="center"><?= $footer2 ?></div>
</div> -->

<?php

    // End the table
    // echo "</table>";
    
    // CSS styles for table formatting
    echo "<style>
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
    </style>";

    // Auto print using JavaScript
    echo "<script type='text/javascript'>
        window.onload = function() {
            window.print();
        }
    </script>";
} else {
    echo "No data found.";
}
        // // Redirect function
        // function redirect($url)
        // {
        //     echo "<script>
        //         setTimeout(function(){
        //             window.location.href = '$url';
        //         }, 1000);
        //     </script>";
        // }

        // // Usage example
        // $url = "bill-last5-print.php"; // Replace with your desired URL
        // redirect($url);
        
        // Tab Close function
        function closeTab() {
          echo "<script>
            setTimeout(function(){
              window.close();
            }, 200);
          </script>";
}

// Usage example
closeTab();

// Close the database connection
$con->close();
?>
<?php }else{
	header("Location: index.php");
} ?>