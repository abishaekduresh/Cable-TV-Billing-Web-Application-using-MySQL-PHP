<?php 
session_start();
include "dbconfig.php";
include "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
    $session_username = $_SESSION['username']; 
    $currentDateTime = date("Y-m-d H:i:s");
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

    // Fetch existing settings
    $sql = "SELECT * FROM settings LIMIT 1"; 
    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appName   = $row['appName'];
        $appName2  = $row['appName2'];
        $email     = $row['email'];
        $addr1     = $row['addr1'];
        $addr2     = $row['addr2'];
        $phone     = $row['phone'];
        $footer1   = $row['prtFooter1'];
        $footer2   = $row['prtFooter2'];
        $sentSMS   = $row['sentSMS']; // 0 or 1 stored in DB
    } else {
        echo "No data found.";
    }

    // Handle update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $appName   = $_POST['appName'];
        $appName2  = $_POST['appName2'];
        $email     = $_POST['email'];
        $addr1     = $_POST['addr1'];
        $addr2     = $_POST['addr2'];
        $phone     = $_POST['phone'];
        $footer1   = $_POST['footer1'];
        $footer2   = $_POST['footer2'] ?? '';
        $sentSMS   = isset($_POST['toggleSwitch']) ? 1 : 0; // ✅ Fixed toggle handling

        $sql = "UPDATE settings 
                SET appName='$appName', 
                    appName2='$appName2', 
                    email='$email', 
                    addr1='$addr1', 
                    addr2='$addr2', 
                    phone='$phone', 
                    prtFooter1='$footer1', 
                    prtFooter2='$footer2', 
                    sentSMS='$sentSMS',
                    lastUpdateBy='$session_username', 
                    latestUpdate='$currentDateTime'";

        if ($con->query($sql) === TRUE) {
            echo "<div class='alert alert-success text-center'>Settings updated successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error updating data: " . $con->error . "</div>";
        }
    }
?>

    <div class="container">
        <div class="row gutters">
            <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
                        <form method="post">
                        
                        <!-- ✅ Correct toggle switch -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" role="switch" id="toggleSwitch" name="toggleSwitch" <?= ($sentSMS == 1 ? 'checked' : '') ?>>
                            <label class="form-check-label" for="toggleSwitch">Send SMS</label>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                <div class="card h-100">
                    <div class="card-body">
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
                                        <input type="text" class="form-control" id="addr1" name="addr1" value="<?= $addr1 ?>">
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
                            </div>
                            <div class="row gutters">
                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="text-right">
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

<?php include 'footer.php'?>

<?php } else {
    header("Location: logout.php");
} ?>
