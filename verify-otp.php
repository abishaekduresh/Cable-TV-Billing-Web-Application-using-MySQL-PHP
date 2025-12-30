<?php 
session_start();
require_once "dbconfig.php";
require_once "component.php";

// Input cleaning
function test_input($data) {
    if ($data === null) return '';
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'], $_POST['password'])) {

    $username = test_input($_POST['username']);
    $password_input = $_POST['password']; // Raw password
    $otpOption = isset($_POST['otpOption']) ? test_input($_POST['otpOption']) : null;

    if (empty($username)) {
        header("Location: logout.php?error=User Name is Required");
        exit();
    } else if (empty($password_input)) {
        header("Location: logout.php?error=Password is Required");
        exit();
    }

    // 1. Authenticate User Credentials (First Pass)
    $password_hash = md5($password_input); // Legacy MD5 usage
    
    // Use Prepared Statement
    $stmt = $con->prepare("SELECT * FROM user WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password_hash);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check Account Status
        if ($row['status'] != '1') {
             header("Location: logout.php?error=Account is inactive");
             exit();
        }

        // 2. Handle OTP Selection Logic
        $user_phone = $row['phone'];
        
        // A. SMS OTP
        if ($otpOption == 'smsOTP') {
            if (empty($user_phone) || strlen($user_phone) != 10 || !is_numeric($user_phone)) {
                 echo "<script>window.location = 'logout.php?error=Invalid Phone Number';</script>";
                 exit();
            }
            // Generate & Send
            $otp_code = generateOTP(6);
            $_SESSION['temp_login_otp'] = $otp_code;
            
            // Send SMS (assuming function exists in component.php)
            $res = send_Login_SMS_OTP($user_phone, $otp_code);
            $res_json = json_decode($res);
            
            if (isset($res_json->code) && $res_json->code != 200) {
                 $err = urlencode($res_json->message);
                 echo "<script>window.location = 'logout.php?error=SMS Error: $err';</script>";
                 exit();
            }
            
        // B. Google TOTP
        } elseif ($otpOption == 'googleTOTP') {
            // Check if secret exists
            if (empty($row['google_totp_auth_secret'])) {
                echo "<script>window.location = 'logout.php?error=Google Authenticator not set up for this user';</script>";
                exit();
            }
            // No session OTP needed for TOTP, check-login.php handles verification
            
        // C. Passcode
        } elseif ($otpOption == 'passcode') {
            if (empty($row['passcode']) || strlen($row['passcode']) != 6) {
                 echo "<script>window.location = 'logout.php?error=Passcode not configured. Contact Admin.';</script>";
                 exit();
            }
            $_SESSION['temp_login_otp'] = $row['passcode'];
            
        // D. No OTP (Direct Login - Hardcoded)    
        } elseif ($otpOption == 'noOTP') {
            $_SESSION['temp_login_otp'] = "773577"; // Hardcoded OTP
            // Proceed to show the OTP entry form so user can enter this code manually.
            
        } else {
            // Default Fallback
             $_SESSION['temp_login_otp'] = "773577";
        }

    } else {
        header("Location: logout.php?error=Incorrect Username or Password");
        exit();
    }
} else {
    header("Location: logout.php");
    exit();
}
// End Logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --text-dark: #2b2d42;
            --bg-light: #f3f4f6;
            --success-color: #06d6a0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .otp-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            text-align: center;
        }
        
        .card-body {
            padding: 3rem 2rem;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            background-color: rgba(67, 97, 238, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: var(--primary-color);
            font-size: 2rem;
        }

        h4 {
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        p {
            color: #6b7280;
            margin-bottom: 2rem;
            font-size: 0.95rem;
        }

        .otp-inputs {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .otp-inputs input {
            width: 100%;
            height: 60px;
            text-align: center;
            font-size: 1.5rem;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            background-color: #f9fafb;
            color: var(--text-dark);
            transition: all 0.2s;
        }

        .otp-inputs input:focus {
            border-color: var(--primary-color);
            background-color: white;
            outline: none;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        .btn-verify {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            width: 100%;
            font-size: 1rem;
            transition: all 0.2s;
        }

        .btn-verify:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
             box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .resend-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .resend-link:hover {
            text-decoration: underline;
        }
        
        .back-link {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            margin-top: 1.5rem;
            display: inline-block;
        }
        
        .back-link:hover { color: var(--text-dark); }

    </style>
</head>
<body>

    <div class="container px-4">
        <div class="otp-card mx-auto">
            <div class="card-body">
                <div class="icon-circle">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <h4>Verify it's you</h4>
                <p>
                    Please enter the 6-digit code sent via <strong><?= htmlspecialchars($otpOption) ?></strong> to your registered number.
                </p>

                <form action="check-login.php" method="POST" autocomplete="off" id="verificationForm">
                    <div id="otp" class="otp-inputs">
                        <input type="password" name="first" maxlength="1" required />
                        <input type="password" name="second" maxlength="1" required />
                        <input type="password" name="third" maxlength="1" required />
                        <input type="password" name="fourth" maxlength="1" required />
                        <input type="password" name="fifth" maxlength="1" required />
                        <input type="password" name="sixth" maxlength="1" required />
                        
                        <!-- Hidden Fields for Login Check -->
                        <input type="hidden" value="<?= htmlspecialchars($_POST['username']) ?>" name="username"/>
                        <!-- Note: check-login.php expects md5 hashed password as per original index.php submission logic structure? 
                             Wait, verify-otp.php POSTs user/pass(md5) to check-login.php.
                             Original code: value="<?=md5($_POST['password'])?>" name="password"
                        -->
                        <input type="hidden" value="<?= md5($_POST['password']) ?>" name="password"/>
                        <input type="hidden" value="<?= htmlspecialchars($otpOption) ?>" name="otpOption"/>
                    </div>

                    <button type="submit" class="btn btn-verify mb-3">
                        Visualize & Verify <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                    
                    <div>
                        <span class="text-muted small">Didn't receive code?</span>
                        <a onclick="resendOTP()" class="resend-link ms-1">Resend OTP</a>
                    </div>
                    
                    <a href="index.php" class="back-link"><i class="bi bi-arrow-left me-1"></i> Back to Login</a>
                </form>
                
                <!-- Hidden Form for Resend Logic -->
                <!-- We post back to this same page (verify-otp.php) with the RAW password 
                     so it can run the logic at the top again (hash, check DB, send OTP). -->
                <form id="resendForm" action="verify-otp.php" method="POST" style="display: none;">
                    <input type="hidden" name="username" value="<?= htmlspecialchars($_POST['username']) ?>">
                    <input type="hidden" name="password" value="<?= htmlspecialchars($_POST['password']) ?>"> <!-- RAW Password -->
                    <input type="hidden" name="otpOption" value="<?= htmlspecialchars($otpOption) ?>">
                </form>
                
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Auto-focus logic
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll('#otp > input');
            
            // Focus first input on load
            inputs[0].focus();

            for (let i = 0; i < inputs.length; i++) {
                inputs[i].addEventListener('input', function(event) {
                    if (inputs[i].value.length > 1) {
                        inputs[i].value = inputs[i].value.slice(0, 1);
                    }
                    if (i !== inputs.length - 1 && inputs[i].value !== '') {
                        inputs[i + 1].focus();
                    }
                });

                inputs[i].addEventListener('keydown', function(event) {
                    if (event.key === "Backspace" && inputs[i].value === '' && i !== 0) {
                        inputs[i - 1].focus();
                    }
                });
                
                // Allow paste
                inputs[i].addEventListener('paste', function(event) {
                     let pasteData = (event.clipboardData || window.clipboardData).getData('text');
                     // If paste data is 6 digits
                     if(pasteData.length === 6 && /^\d+$/.test(pasteData)) {
                         event.preventDefault();
                         for(let j=0; j<6; j++){
                             inputs[j].value = pasteData[j];
                         }
                         inputs[5].focus();
                     }
                });
            }
        });

        // Resend OTP Function
        function resendOTP() {
            // Optional: Add basic UI feedback
            const link = document.querySelector('.resend-link');
            link.innerHTML = 'Sending... <span class="spinner-border spinner-border-sm ms-1"></span>';
            link.style.pointerEvents = 'none';
            link.style.color = '#9ca3af';
            
            // Submit the hidden form
            setTimeout(() => {
                document.getElementById('resendForm').submit();
            }, 500);
        }
    </script>
</body>
</html>
