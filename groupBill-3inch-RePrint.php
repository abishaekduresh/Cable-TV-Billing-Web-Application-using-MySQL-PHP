<?php 
   session_start();
   include "dbconfig.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_username = $_SESSION['username']; ?>
        
<?php
include "dbconfig.php";

// Check the connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$groupID = $_GET['groupID'];
$date = $_GET['date'];


$query = mysqli_query($con, "SELECT *
FROM billgroupdetails WHERE groupID = '$groupID' AND date = '$date';
");


/////////////////////////////////////////////////////////

?>
<html>
    <head>
<style>
    body {
      font-family: Arial, sans-serif;
    }

    th, td {
      border-style: dashed;
    }
</style>

</head>
<body>

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

    
// Check if any rows are returned
if (mysqli_num_rows($query) > 0) {
    echo "<br><table border='1'>
        <tr>
            <!--th>Header</th-->
            <!--th>Data</th-->
        </tr>";

    $row = mysqli_fetch_array($query);
        $billBy = $row["billBy"];
        $billNo = $row["billGroupNo"];
        $date = $row["date"];
        $time = $row["time"];
        $billTo = $row["groupID"];
        $phone = $row["phone"];
        $billAmount= $row["billAmount"];
        $oldMonthBal = $row["oldMonthBal"];
        $hideoldMonthBalRow = ($oldMonthBal == 0);
        $discount = $row["discount"];
        $hideDiscountRow = ($discount == 0); // Determine if the discount row should be hidden
        $Rs = $row["Rs"];
        $hideRsRow = ($discount == 0 && $oldMonthBal == 0); // Determine if the discount row should be hidden
        $pMode = $row["pMode"];
        
        $hideStatusRow = ($pMode === 'cash' || $pMode === 'gpay');
    
    if (isset($_SESSION['id'])) {
        
        $userId = $_SESSION['id'];
        $username = $_SESSION['username'];
        $role = $_SESSION['role'];
        $action = "Group Bill Re Printed - $groupID";

        
        $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$username', '$role', '$action')";
        mysqli_query($con, $insertSql);
    }
    
?>
        <table border="1">
            <tr class="dotted-line">
                <th colspan="2"><center>Customer Bill</center></th>
            </tr>
            <tr>
            <tr>
                <th>Bill No</th>
                <td><?php echo $billNo; ?> &nbsp;/&nbsp;<?php echo $billBy; ?></td>
            </tr>
                <th>Bill To</th>
                <td><?php echo $billTo; ?></td>
            </tr>
            <tr>
                <th>STB</th>
                <td>
                <?php
                                                
                    $query1 = "SELECT stbNo FROM billgroup WHERE groupID = '$groupID' 
                                AND status = 'approve' AND date = '$date'";
                    $result1 = mysqli_query($con, $query1);
                                                
                        while ($row1 = mysqli_fetch_assoc($result1)) {
                            $stbNoValue = $row1["stbNo"];
                ?>
                    <?= $stbNoValue; ?><br>
                <?php
                        }
                                                
                ?>                
                </td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo $phone; ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo $date; ?>/<?php echo $time; ?></td>
            </tr>
            <tr>
                <th>BillAmt</th>
                <td><b><?php echo $billAmount; ?></b></td>
            </tr>
            <tr <?php if ($hideoldMonthBalRow) echo 'style="display: none;"'; ?>>
                <th>OldBal</th>
                <td><?php echo $oldMonthBal; ?></td>
            </tr>
            <tr <?php if ($hideDiscountRow) echo 'style="display: none;"'; ?>>
                <th>Disct</th>
                <td><?php echo $discount; ?></td>
            </tr>
            <tr <?php if ($hideRsRow) echo 'style="display: none;"'; ?>>
                <th>Rs.</th>
                <td><b><?php echo $Rs; ?></b></td>
            </tr>
            <tr <?php if ($hideStatusRow) echo 'style="display: none;"'; ?>>
                <th>A.Sign</th>
                <td></td>
            </tr>
            <tr <?php if ($hideStatusRow) echo 'style="display: none;"'; ?>>
                <td colspan="2"><center>Credit</center></td>
            </tr>
        </table>
        <br/>
</body>
</html>
<?php
     

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

    echo "<script type='text/javascript'>
        window.onload = function() {
            window.print();
        }
    </script>";
} else {
    echo "No data found.";
}

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

$con->close();
?>
<?php }else{
	header("Location: index.php");
} ?>
