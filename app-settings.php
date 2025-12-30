<?php 
session_start();
include "dbconfig.php";
include "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
    $session_username = $_SESSION['username']; 
    $currentDateTime = date("Y-m-d H:i:s");
    
    // Fetch existing settings
    $sql = "SELECT * FROM settings LIMIT 1"; 
    $result = $con->query($sql);

    // Initialize variables
    $appName = $appName2 = $email = $addr1 = $addr2 = $phone = $footer1 = $footer2 = "";
    $sentSMS = 0;

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
        $sentSMS   = $row['sentSMS']; 
    }

    // Handle update
    $msg = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $appName   = $_POST['appName'];
        $appName2  = $_POST['appName2'];
        $email     = $_POST['email'];
        $addr1     = $_POST['addr1'];
        $addr2     = $_POST['addr2'];
        $phone     = $_POST['phone'];
        $footer1   = $_POST['footer1'];
        $footer2   = $_POST['footer2'] ?? '';
        $sentSMS   = isset($_POST['toggleSwitch']) ? 1 : 0; 

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
            $msg = "<div class='alert alert-success text-center mb-0 shadow-sm'><i class='bi bi-check-circle-fill me-2'></i>Settings updated successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger mb-0 shadow-sm'><i class='bi bi-exclamation-triangle-fill me-2'></i>Error updating data: " . $con->error . "</div>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>App Settings</title>
    
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --card-bg: #ffffff;
            --body-bg: #f8f9fc;
            --text-color: #5a5c69;
            --heading-color: #2e384d;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--body-bg);
            color: var(--text-color);
        }

        /* Custom Premium Card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            overflow: hidden;
            background-color: var(--card-bg);
            margin-bottom: 2rem;
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 0;
        }

        .card-header-gradient h6 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1rem;
        }
        
        .section-header {
             font-size: 0.85rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            text-transform: uppercase;
            border-bottom: 2px solid #eaecf4;
            padding-bottom: 0.25rem;
            margin-top: 1rem;
        }

        /* Form Styles */
        .form-label {
            font-weight: 500;
            color: var(--heading-color);
            font-size: 0.9rem;
        }

        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #d1d3e2;
            font-size: 0.95rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #bac8f3;
            box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
        }

        .btn-primary-gradient {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            border: none;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
            color: white;
        }
        
        /* Switch Styling */
        .form-switch .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-left: -2.5em;
            background-color: #e3e6f0;
            border-color: #d1d3e2;
        }
        .form-switch .form-check-input:checked {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        .toggle-label {
            font-weight: 600;
            margin-left: 10px;
            vertical-align: middle;
            font-size: 1rem;
        }

    </style>
</head>
<body>
    
<?php
    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';
?>

    <div class="container-fluid px-4">
        
        <?php if($msg != "") echo "<div class='mb-4'>$msg</div>"; ?>
    
        <form method="post">
            <div class="row">
                
                <!-- Quick Actions / Sidebar -->
                <div class="col-lg-3 col-md-12">
                    <div class="card h-100">
                        <div class="card-header-gradient bg-warning">
                            <h6><i class="bi bi-lightning-charge me-2"></i>Quick Actions</h6>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-between p-4">
                            <div>
                                <h6 class="text-muted text-uppercase small fw-bold mb-3">System Features</h6>
                                <div class="p-3 rounded bg-light border">
                                    <div class="form-check form-switch d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" role="switch" id="toggleSwitch" name="toggleSwitch" <?= ($sentSMS == 1 ? 'checked' : '') ?>>
                                        <label class="form-check-label toggle-label text-dark" for="toggleSwitch">SMS Notifications</label>
                                    </div>
                                    <small class="text-muted d-block mt-2">Enable to send automatic SMS alerts to customers for billing.</small>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <img src="https://cdni.iconscout.com/illustration/premium/thumb/settings-4103008-3402773.png" alt="Settings" class="img-fluid" style="opacity: 0.7; max-width: 150px;">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Settings Form -->
                <div class="col-lg-9 col-md-12">
                    <div class="card">
                        <div class="card-header-gradient">
                            <h6><i class="bi bi-sliders me-2"></i>General Configuration</h6>
                        </div>
                        <div class="card-body p-4">
                            
                            <div class="section-header">Organization Details</div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="appName" class="form-label">Application Name *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-app-indicator"></i></span>
                                        <input type="text" class="form-control" id="appName" name="appName" value="<?= $appName ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="appName2" class="form-label">Secondary Name</label>
                                    <input type="text" class="form-control" id="appName2" name="appName2" value="<?= $appName2 ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="addr1" class="form-label">Address Line 1 *</label>
                                    <input type="text" class="form-control" id="addr1" name="addr1" value="<?= $addr1 ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="addr2" class="form-label">Address Line 2</label>
                                    <input type="text" class="form-control" id="addr2" name="addr2" value="<?= $addr2 ?>">
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Contact Phone *</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" value="<?= $phone ?>" required>
                                    </div>
                                    <div class="form-text">10-digit mobile number</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Contact Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bi bi-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="section-header mt-5">Receipt & Print Settings</div>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="footer1" class="form-label">Footer Line 1 (Promotion) *</label>
                                    <input type="text" class="form-control" id="footer1" name="footer1" value="<?= $footer1 ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="footer2" class="form-label">Footer Line 2 (Terms/Thanks)</label>
                                    <input type="text" class="form-control" id="footer2" name="footer2" value="<?= $footer2 ?>">
                                </div>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-12 text-end">
                                    <button type="submit" class="btn btn-primary-gradient">
                                        <i class="bi bi-check-circle me-2"></i>Save Changes
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php include 'footer.php'?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php } else {
    header("Location: logout.php");
} ?>