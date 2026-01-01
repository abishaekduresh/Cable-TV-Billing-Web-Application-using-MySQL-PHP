<?php
ob_start();
session_start();
if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    header("Location: redirect-login.php");
    exit();
}
// Place this PHP code in your script where you want to clear the storage
echo "<script>
    // Check if localStorage is available and clear it
    if (typeof localStorage !== 'undefined') {
        localStorage.clear();
        console.log('Local storage cleared');
    }
    
    // Check if sessionStorage is available and clear it
    if (typeof sessionStorage !== 'undefined') {
        sessionStorage.clear();
        console.log('Session storage cleared');
    }
</script>";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDP Cable TV | Login</title>
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
        
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card-header-brand {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 2.5rem 2rem;
            text-align: center;
            color: white;
        }
        
        .card-body {
            padding: 2.5rem;
        }
        
        .form-floating > .form-control {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding-left: 1rem;
        }
        
        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }
        
        .form-floating > label {
            padding-left: 1rem;
            color: #6b7280;
        }
        
        .btn-login {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 1rem;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            margin-top: 1rem;
            transition: all 0.2s;
        }
        
        .btn-login:hover {
            background-color: var(--secondary-color);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }
        
        .otp-select {
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            padding: 1rem;
            width: 100%;
            margin-bottom: 1rem;
            font-size: 1rem;
            color: var(--text-dark);
            background-color: #fff;
        }
        
        .otp-select:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }
        
        .brand-icon {
            font-size: 3rem;
            margin-bottom: 0.5rem;
            display: inline-block;
        }
    </style>
</head>
<body>

    <div class="container px-4">
        <div class="login-card mx-auto">
            <div class="card-header-brand">
                <img src="https://files.catbox.moe/sepcbf.png" alt="Logo" height="80" class="mb-3">
                <h4 class="fw-bold mb-0">PDP Cable TV</h4>
                <p class="mb-0 text-white-50">Billing Software</p>
            </div>
            
            <div class="card-body">
                <form id="loginForm" action="verify-otp.php" method="post">
                    
                    <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        <div><?= htmlspecialchars($_GET['error']) ?></div>
                    </div>
                    <?php } ?>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="username" id="username" placeholder="User ID" required>
                        <label for="username">User ID</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <div class="mb-4">
                        <select class="form-select otp-select py-3" name="otpOption">
                           <option value="googleTOTP" selected>Google Authenticator (TOTP)</option>
                           <option value="passcode">Use Login Passcode</option>
                           <option value="smsOTP">Send OTP via SMS</option>
                           <option value="noOTP">No OTP (Direct Login)</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-login mb-2">
                        Sign In <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </form>
            </div>
        </div>
        
        <div class="text-center mt-4 text-muted small">
            &copy; <?= date('Y'); ?> PDP Cable TV. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>