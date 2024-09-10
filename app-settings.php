<?php 
   session_start();
   include "dbconfig.php";
   include "component.php";
   include 'preloader.php';
//    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
        $session_username = $_SESSION['username']; 
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
<?php

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>


<?php


// Perform a SELECT query to fetch data from the database
$sql = "SELECT * FROM settings"; // Replace 'your_table_name' with your actual table name

$result = $con->query($sql);

// Check if there are any rows returned
$appName = '';
$email = '';
$addr1 = '';
$addr2 = '';
$phone = '';
$footer1 = '';
$footer2 = '';
if ($result->num_rows > 0) {
    // Loop through each row and fetch the data
    while ($row = $result->fetch_assoc()) {
        $appName = $row['appName'];
        $email = $row['email'];
        $addr1 = $row['addr1'];
        $addr2 = $row['addr2'];
        $phone = $row['phone'];
        $footer1 = $row['prtFooter1'];
        $footer2 = $row['prtFooter2'];
    }
} else {
    echo "No data found.";
}


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and save the form data to variables
    $appName = $_POST['appName'];
    $appName2 = $_POST['appName2'];
    $email = $_POST['email'];
    $addr1 = $_POST['addr1'];
    $addr2 = $_POST['addr2'];
    $phone = $_POST['phone'];
    $footer1 = $_POST['footer1'];
    $footer2 = $_POST['footer2'];

    if($footer2==NULL){
        $footer2Value = '';
    }else{
        $footer2Value = $footer2;
    }

    // Update data in the database
    $sql = "UPDATE settings SET appName='$appName', appName2='$appName2', email='$email', addr1='$addr1', addr2='$addr2', phone='$phone', prtFooter1='$footer1', prtFooter2='$footer2Value', lastUpdateBy='$session_username', latestUpdate='$currentDateTime'"; // Replace 'your_table_name' with your actual table name and 'id=1' with the appropriate condition
    if ($con->query($sql) === TRUE) {
        // echo "Data updated successfully!";
    } else {
        echo "Error updating data: " . $con->error;
    }

    $con->close();
}
?>

    <div class="container">
        <div class="row gutters">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        
                    ...
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="post">
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mb-2 text-primary">App Settings</h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="appName">App Name *</label>
                                        <input type="text" class="form-control" id="appName" name="appName" value="<?= $appName ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="appName2">App Name 2</label>
                                        <input type="text" class="form-control" id="appName2" name="appName2" value="<?= $appName2 ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="addr1">Address 1 *</label>
                                        <input type="text" class="form-control" id="add1" name="addr1" value="<?= $addr1 ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="addr2">Address 2 *</label>
                                        <input type="text" class="form-control" id="addr2" name="addr2" value="<?= $addr2 ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="phone">Phone *</label>
                                        <input type="text" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" value="<?= $phone ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <h6 class="mt-3 mb-2 text-primary">Printer Footer <strong>₹</strong></h6>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="footer1">Footer 1 *</label>
                                        <input type="text" class="form-control" id="footer1" name="footer1" value="<?= $footer1 ?>">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="footer2">Footer 2 </label>
                                        <input type="text" class="form-control" id="footer2" name="footer2" value="<?= $footer2 ?>">
                                    </div>
                                </div>
                                <!-- <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="sTate">State</label>
                                        <input type="text" class="form-control" id="sTate" name="sTate" placeholder="Enter State">
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="zIp">Zip Code</label>
                                        <input type="text" class="form-control" id="zIp" name="zIp" placeholder="Zip Code">
                                    </div>
                                </div> -->
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
                                        <!-- <button type="button" class="btn btn-secondary">Cancel</button> -->
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


<br/>


<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>