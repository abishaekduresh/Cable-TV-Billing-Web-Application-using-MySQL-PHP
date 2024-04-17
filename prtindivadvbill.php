<?php 
   session_start();
   require "dbconfig.php";
   require "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_username = $_SESSION['username']; 

        $MM = date('m', strtotime($currentDate));
        $YY = date('Y', strtotime($currentDate));

$sql1 = "SELECT * FROM settings"; // Replace 'your_table_name' with your actual table name

$result = $con->query($sql1);

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


?>

<html>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Print Indiv Adv bill</title>
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

    
    $stbnumber = $_GET['stbnumber'];
    
    $query = mysqli_query($con, "SELECT * FROM bill WHERE stbno = '$stbnumber' AND
                                    DATE(due_month_timestamp) >= '$currentDate' AND
                                    (
                                        (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                        (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                    )
                                    AND adv_status = 1 AND status = 'approve' GROUP BY stbno
    ");
    
    if (mysqli_num_rows($query) > 0) {
    
        while ($row = mysqli_fetch_array($query)) {

            $billId = $row["bill_id"];
            $billBy = $row["bill_by"];
            $stbno = $row["stbno"];
            $billNo = $row["billNo"];
            // $date = $row["date"];
            // $time = $row["time"];
            $billTo = $row["name"];
            $cusphone = $row["phone"];
            // $billAmount= $row["paid_amount"];
            // $hidebillAmountRow = ($billAmount == 0);
            // $discount = $row["discount"];
            // $hideDiscountRow = ($discount == 0); // Determine if the discount row should be hidden
            // $Rs = $row["Rs"];
            // $hideRsRow = ($Rs < 0); // Determine if the discount row should be hidden
            $pMode = $row["pMode"];
            $oldMonthBal = $row["oldMonthBal"];
            $due_month_timestamp = $row["due_month_timestamp"];
            $adv_result = splitDateAndTime(strtotime($due_month_timestamp));
            $hideoldMonthBalRow = ($oldMonthBal == 0);
            
            $hideStatusRow = ($pMode === 'cash' || $pMode === 'gpay');

            if (isset($_SESSION['id'])) {
                $userId = $_SESSION['id'];
                $username = $_SESSION['username'];
                $role = $_SESSION['role'];
                $action = "Indiv Advance Bill Printed - STBNo : $stbno";
            
                logUserActivity($userId, $username, $role, $action);
            }

            

?>

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
        </table>

        <table align="center">

            <tr>
                <td colspan="2" align="center" ><b><u>Advance Bill</u></b></td>
            </tr>

            <tr>
                <td style="border:1px; border-left-style:solid;">B.No</td>
                <td align="left" colspan="2" style="border:1.5px; border-right-style:solid;">
                    <?php
                        // $sql2 = "SELECT billNo FROM bill WHERE stbno = '$stbno' 
                        // AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                        //     OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                        // AND adv_status = 1 AND status = 'approve'";

                        $sql2 = "SELECT * FROM bill WHERE stbno = '$stbno' AND
                            DATE(due_month_timestamp) >= '$currentDate' AND
                                (
                                    (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                    (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                )
                            AND adv_status = 1 AND status = 'approve'";
                            
                        $run = mysqli_query($con, $sql2);

                        if ($run) { // Check if the query was successful
                            while ($row = mysqli_fetch_assoc($run)) {
                                $bno = $row["billNo"];
                                echo $bno . ", "; // Corrected this line
                            }
                        } else {
                            echo mysqli_error($con);
                        }
                    ?>
                </td>
            </tr>
            
            <tr>
                <td style="border:1px; border-left-style:solid;">Date</td>
                <td align="left" colspan="2" style="border:1.5px; border-right-style:solid;">
                    <?php
                        // $sql3 = "SELECT due_month_timestamp FROM bill WHERE stbno = '$stbno' 
                        // AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                        //     OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                        // AND adv_status = 1 AND status = 'approve'";
                        
                                                            // SQL query to select data from a table
                        $sql3 = "SELECT * FROM bill WHERE stbno = '$stbno' AND
                            DATE(due_month_timestamp) >= '$currentDate' AND
                                (
                                    (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                    (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                )
                            AND adv_status = 1 AND status = 'approve'";

                        $run = mysqli_query($con, $sql3);

                        if ($run) { // Check if the query was successful
                            while ($row = mysqli_fetch_assoc($run)) {
                                $DateTime = $row["due_month_timestamp"];
                                echo date('d-M-Y', strtotime($DateTime)) . "<br>"; // Corrected this line
                            }
                        } else {
                            echo mysqli_error($con);
                        }
                    ?>
                    <!-- Print the total count -->
                    <b>Total Month : <?= mysqli_num_rows($run) ?></b>
            </tr>
            
            <tr>
                <td style="border:1px; border-left-style:solid;">Name</td>
                <td colspan="2" style="border:1.5px; border-right-style:solid;"><?= $billTo ?></td>
            </tr>
            
            <tr>
                <td style="border:1px; border-left-style:solid;">STB</td>
                <td colspan="2" style="border:1.5px; border-right-style:solid;"><b><?= $stbno ?></b></td>
            </tr>
            <tr>
                <td style="border:1px; border-left-style:solid;">Mobile</td>
                <td colspan="2" style="border:1.5px; border-right-style:solid;"><b><?= $cusphone ?></b></td>
            </tr>		
            
        </table>
        <table align="center">
    
            <tr>
                <td colspan="2" align="right" style="padding-right:20px;border:1px;border-left-style:solid;"><b>Bill Amount</b></td>
                <td align="right" style="padding-right:20px;  border:1px; border-left-style:solid;border-right-style:solid;"><b>
                    <?php
                        // $sql4 = "SELECT paid_amount FROM bill WHERE stbno = '$stbno' 
                        // AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                        //     OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                        // AND adv_status = 1 AND status = 'approve'";

                        $sql4 = "SELECT * FROM bill WHERE stbno = '$stbno' AND
                            DATE(due_month_timestamp) >= '$currentDate' AND
                                (
                                    (MONTH(due_month_timestamp) >= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear) OR
                                    (MONTH(due_month_timestamp) <= $currentMonth AND YEAR(due_month_timestamp) >= $currentYear)
                                )
                            AND adv_status = 1 AND status = 'approve'";
                            
                        $run = mysqli_query($con, $sql4);
                        $billAmt=0;
                        if ($run) { // Check if the query was successful
                            while ($row = mysqli_fetch_assoc($run)) {
                                $billAmt += $row["paid_amount"];
                            }
                            echo $billAmt;
                        } else {
                            echo mysqli_error($con);
                        }
                    ?></b>
                </td>
            </tr>
    
            <tr <?php if ($hideoldMonthBalRow) echo 'style="display: none;"'; ?>>
                <td colspan="2" align="right" style="padding-right:20px;border:1px;border-left-style:solid;"><b>Old Balance</b></td>
                <td align="right" style="padding-right:20px;  border:1px; border-left-style:solid;border-right-style:solid;">
                    <b>
                        <?php
                        $oldMonthBal = 0; // Initialize the variable to store oldMonthBal
                        
                        // $sql5 = "SELECT oldMonthBal, discount FROM bill WHERE stbno = '$stbno' 
                        //     AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                        //         OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                        //     AND adv_status = 1 AND status = 'approve'";
                        
                        $run = mysqli_query($con, $sql4);
                        
                        if ($run) { // Check if the query was successful
                            while ($row = mysqli_fetch_assoc($run)) {
                                $oldMonthBal += $row["oldMonthBal"];
                            }
                            echo $oldMonthBal;
                        } else {
                            echo mysqli_error($con);
                        }?>
                    </b>
                </td>
            </tr>
    
            <tr>
                <td colspan="2" align="right" style="padding-right:20px;border:1px;border-left-style:solid;"><b>Discount</b></td>
                <td align="right" style="padding-right:20px;  border:1px; border-left-style:solid;border-right-style:solid;"><b>
                    <?php
                        // $sql6 = "SELECT discount FROM bill WHERE stbno = '$stbno' 
                        // AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                        //     OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                        // AND adv_status = 1 AND status = 'approve'";

                        $run = mysqli_query($con, $sql4);
                        $discount=0;
                        if ($run) { // Check if the query was successful
                            while ($row = mysqli_fetch_assoc($run)) {
                                $discount += $row["discount"];
                            }
                            echo $discount;
                        } else {
                            echo mysqli_error($con);
                        }
                    ?></b>
                </td>
            </tr>
    
            <tr>
                <td colspan="2" align="right" style="padding-right:20px;border:1px;border-left-style:solid;"><b>Payable</b></td>
                <td align="right" style="padding-right:20px;  border:1px; border-left-style:solid;border-right-style:solid; border-top-style:solid;"><b>₹ &nbsp;<?= $billAmt-$discount+$oldMonthBal ?></b></td>
            </tr>
            
            <tr <?php if ($hideStatusRow) echo 'style="display: none;"'; ?>>
                <td colspan="3" align="center" style="border:1.5px; border-top-style:solid;"><b> Credit Bill </b></td>
            </tr>

            <tr <?php if (!$hideStatusRow) echo 'style="display: none;"'; ?>>
            <td colspan="3" align="center" style="border:1.5px; border-top-style:solid;"><b>Paid</b></td>
            </tr>

            <tr <?php if ($hidePromotion) echo 'style="display: none;"'; ?>>
                <td colspan="3" align="center" style="border:1.5px; border-top-style:solid;"><?= $footer1 ?><br/><b><?= $footer2 ?></b></td>
            </tr>

            <tr>
                <td colspan="3" align="center" style="border:1px; border-top-style:solid; font-size: 14px;">Bill Printed on&nbsp;
                    <?PHP 
                        $current_result = splitDateAndTime(strtotime($currentDateTime)); 
                        formatDate($current_result['date']);
                        echo '&nbsp';
                        $t=convertTo12HourFormat($current_result['time']);
                        echo $t;
                    ?>
                </td>
            </tr>

        </table>

        <!-- <div class="spacer2"></div>
        <div <?php if ($hidePromotion) echo 'class="container"'; ?>>
            <div align="center"><?= $footer1 ?></div>
            <div class="spacer"></div>
            <div align="center"><?= $footer2 ?></div>
        </div> -->

    </body>
    </html>
    
    <?php 
    printClose();
}
    

?>



<?php }}else{
	header("Location: index.php");
} ?>