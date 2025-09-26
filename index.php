<?php
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
<html>
<head>
	<title>PDP Cable TV | Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<style>
body {
  background: #007bff;
  background: linear-gradient(to bottom, #0062E6, #33AEFF);
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh; /* This ensures the background covers the entire viewport height */
}

@keyframes zoomInOut {
  0% {
    transform: scale(0);
    opacity: 0;
  }
  50% {
    transform: scale(1.2);
    opacity: 1;
  }
  100% {
    transform: scale(1);
  }
}

.container {
  animation: zoomInOut 0.5s ease-in-out;
}



.container {
  /* background-color: white; /* If you want a white background for your container */
  padding: 20px; /* Adjust the padding as needed */
  border-radius: 30px; /* Add rounded corners if desired */
  /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow if desired */
}

.btn-login {
  font-size: 0.9rem;
  letter-spacing: 0.05rem;
  padding: 0.75rem 1rem;
}


.btn-google {
  color: white !important;
  background-color: #ea4335;
}

.btn-facebook {
  color: white !important;
  background-color: #3b5998;
}
</style>
</head>
<!-- This snippet uses Font Awesome 5 Free as a dependency. You can download it at fontawesome.io! -->

<body>
<div class="container">
  <div class="row">
    <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
      <div class="card border-0 shadow rounded-3 my-5">
        <div class="card-body p-4 p-sm-5">
          <h4 class="text-center">CABLE TV Billing Software</h4><br />
          
          <form id="loginForm" action="verify-otp.php" method="post">
            <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger" role="alert">
              <?= $_GET['error'] ?>
            </div>
            <?php } ?>

            <div class="form-floating mb-3">
              <input type="text" class="form-control" name="username" id="username" required>
              <label for="username">User ID</label>
            </div>

            <div class="form-floating mb-3">
              <input type="password" name="password" class="form-control" id="password" required>
              <label for="password">Password</label>
            </div>

            <div class="mb-3">
              <label class="form-label">OTP Option</label>
              <div class="d-flex justify-content-start">
                <div class="form-check me-4">
                  <input class="form-check-input" type="radio" name="otpOption" id="googleTOTP" value="googleTOTP" checked>
                  <label class="form-check-label" for="googleTOTP">
                    Google TOTP
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="radio" name="otpOption" id="passcode" value="passcode">
                  <label class="form-check-label" for="Passcode">
                    Passcode
                  </label>
                </div>

                <div class="form-check me-4">
                  <input class="form-check-input" type="radio" name="otpOption" id="smsOTP" value="smsOTP">
                  <label class="form-check-label" for="smsOTP">
                    SMS
                  </label>
                </div>

                <div class="form-check">
                  <input class="form-check-input" type="radio" name="otpOption" id="noOTP" value="noOTP">
                  <label class="form-check-label" for="noOTP">
                    No OTP
                  </label>
                </div>
              </div>
            </div>

            <div class="d-grid">
              <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit" id="submitBtn">
                Sign in
              </button>
            </div>

          </form>

          <center>
            <h6>Powered by <a href="https://www.dureshtech.com/" target="_blank" style="text-decoration: none;">Duresh Tech</a></h6>
          </center>
          <p align="center">28th-Commit</p>

        </div>
      </div>
    </div>
  </div>
</div>
<script>		
        const submitBtn = document.getElementById('submitBtn');
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            submitBtn.textContent = 'Processing...';
            submitBtn.disabled = true;
        });
        
</script>
</body>
</html>