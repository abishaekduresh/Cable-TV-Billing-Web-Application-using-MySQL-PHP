<?php 
   session_start();
   include "dbconfig.php";
   require "component.php";
   include 'preloader.php';
   
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {  
       $session_username = $_SESSION['username'];
       $session_name = $_SESSION['name'];
?>

<?php
// if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
//     include 'admin-menu-bar.php'; 
//     ?> <br> <?php
//     include 'admin-menu-btn.php';
// } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
//     include 'menu-bar.php';
// }
// ?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Bill Data</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<br>

    <div class="container">
        <div class="d-flex justify-content-end">
            <button type="click" class="btn btn-danger justify-content-end" onclick="closeWindow()"><i class="fa-solid fa-circle-xmark"></i>Close</button>
        </div>
    </div>

            
<div class="container">
  <div class="row">
    <!--<div class="col">-->
    <!--    <form method="POST" action="code-bill-export.php">-->
    <!--        <h2><u>Export Bill as Excel</u></h2><br>-->
    <!--        <div class="form-group">-->
    <!--            <label for="start_date">Start Date</label>-->
    <!--            <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $currentDate?>" required>-->
    <!--        </div>-->
    <!--        <div class="form-group">-->
    <!--            <label for="end_date">End Date</label>-->
    <!--            <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $currentDate?>" required>-->
    <!--        </div>-->
    <!--        <div class="form-group">-->
    <!--            <label><u>Bill By :</u></label><br>-->
    <!--            <label><input type="checkbox" name="filter[]" value="23A002"> Duresh</label>-->
    <!--            <label><input type="checkbox" name="filter[]" value="23A001"> Baskar Raj</label>-->
    <!--            <label><input type="checkbox" name="filter[]" value="23E001"> Kannika</label>-->
    <!--            <label><input type="checkbox" name="filter[]" value="23A002"> Santhanam</label>-->
    <!--            <label><input type="checkbox" name="filter[]" value="23E003"> Jeyaraj Thatha</label>-->
    <!--            <br>-->
    <!--            <label><u>Bill Status :</u></label>-->
    <!--            <br>-->
    <!--            <label><input type="checkbox" name="status_filter[]" value="cancel"> Cancel</label>-->
    <!--            <label><input type="checkbox" name="status_filter[]" value="approve" checked> Approve</label>-->
                 <!--Add more checkboxes for other filter options -->
    <!--        </div>-->
    <!--        <button type="submit" class="btn btn-primary" name="submit">Download</button>-->
    <!--    </form>-->
    <!--</div>-->
    
    
    <div class="col">
            <div class="container mt-4">
                
                <?php

                // Check if the form has been submitted
                if ($_SERVER["REQUEST_METHOD"] === "POST") {
                    $fromDate = $_POST["from_date"];
                    $endDate = $_POST["end_date"];
                    
                    $from_bill_no = isset($_POST['from_bill_no']) ? $_POST['from_bill_no'] : '';
                    $to_bill_no = isset($_POST['to_bill_no']) ? $_POST['to_bill_no'] : '';
                    $bill_no_FilterCondition = '';
                    
                    if (!empty($from_bill_no) && !empty($to_bill_no)) {
                        $bill_no_FilterCondition = "AND billNo BETWEEN '$from_bill_no' AND '$to_bill_no'";
                    }

                
                    // Fetch data from the MySQL database based on the date range
                    $sql = "SELECT stbno FROM bill WHERE DATE(due_month_timestamp) BETWEEN '$fromDate' AND '$endDate' AND status = 'approve' $bill_no_FilterCondition";
                    $result = $con->query($sql);
                
                    // if ($result && $result->num_rows > 0) {
                    //     // Create a file for writing the data
                    //     $filename = "Indiv_bill_data_" . $session_username . "_" . $currentDate . ".txt";
                    //     $file = fopen($filename, "w");
                
                    //     // Loop through each row of data
                    //     while ($row = $result->fetch_assoc()) {
                    //         // Write each data entry to the file, separated by a comma
                    //         fwrite($file, implode(",", $row) . ",\n");
                    //     }
                
                    //     // Close the file
                    //     fclose($file);
                    
                    if ($result && $result->num_rows > 0) {
                        // Specify the desired path to save the file
                        $filePath = "bill-txt-downloaded-files/";
                    
                        // Create a file for writing the data
                        $filename = $filePath . "bill_data_" . $currentDateTime . ".txt";
                        $file = fopen($filename, "w");
                    
                        // Loop through each row of data
                        while ($row = $result->fetch_assoc()) {
                            // Write each data entry to the file, separated by a comma
                            fwrite($file, implode(",", $row) . ",\n");
                        }
                    
                        // Close the file
                        fclose($file);


                        
                // Activity Log
                if (isset($_SESSION['id'])) {
                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $role = $_SESSION['role'];
                $action = "Text file Exported by $session_name";
            
                // Insert user logout activity
                $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$session_username', '$role', '$action')";
                mysqli_query($con, $insertSql);
                }
                
                        
                    } else {
                        echo "No data found.";
                    }
                }
                
                // Close the database connection
                $con->close();
                ?>
<!--https://chat.openai.com/share/e40cf2a0-a130-4724-806b-1a146c825bf4-->
                <form method="POST" action="">
                    <h2><u>Export Bill as Text file</u></h2>
                    <div class="mb-3">
                        <label for="from_date" class="form-label">From Date</label><span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="from_date" value="<?php echo $currentDate?>" name="from_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label><span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="end_date" value="<?php echo $currentDate?>" name="end_date" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" id="from_bill_no" name="from_bill_no" placeholder="Enter From Bill No.">
                        </div>
                        <div class="col-md-6 mb-3">
                            <input type="number" class="form-control" id="to_bill_no" name="to_bill_no" placeholder="Enter To Bill No.">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
        
                <?php if (isset($result) && $result->num_rows > 0) { 
                ?><b>From Date :<?= formatDate($fromDate);?> <br/> To Date:<?php 
                        echo formatDate($endDate);?> <br/> <?= $bill_no_FilterCondition;?></b>
                    <br>
                    <a href="<?php echo $filename ?>" class="btn btn-primary" download>Download Data</a>
                <?php } ?>
                </div>
        </div>
    </div>
</div>


    <script>
        function closeWindow() {
            window.close();
        }
    </script>
    <script>
        setTimeout(function() {
            window.close();
        }, 30000);
    </script>

</body>
</html>

<?php //include 'footer.php'?>

<?php
} else {
    header("Location: index.php");
}
?>
