<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
?>

<?php
$username = $_SESSION['username'];

// Query to fetch data for the specific username
$sql = "SELECT * FROM user WHERE username = '$username'";

// Execute the query
$result = mysqli_query($con, $sql);

// Check if the query was successful
if ($result) {
    $data = mysqli_fetch_assoc($result);
    $username = $data['username'];
    $name = $data['name'];
    $role = $data['role'];
    $status = $data['status'];
} else {
    echo "Error: " . mysqli_error($con);
}

$stmt = $con->prepare("SELECT google_totp_auth_secret FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$google_totp_auth_secret = $row['google_totp_auth_secret'] ?? null;

// Database connection will be closed at the end of the script
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
  <title>User Profile</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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

        .card-header-gradient {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 0;
        }

        .card-header-gradient h5 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
        }

        /* Profile Specifics */
        .profile-userpic img {
            border-radius: 50%;
            border: 4px solid #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            width: 120px;
            height: 120px;
            object-fit: cover;
        }
        
        .profile-user-card {
            text-align: center;
            padding: 2rem 1rem;
        }
        
        .profile-name {
            font-weight: 700;
            font-size: 1.25rem;
            margin-top: 1rem;
            color: var(--heading-color);
        }
        
        .profile-role {
            color: var(--secondary-color);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* Nav Tabs */
        .nav-tabs {
            border-bottom: 2px solid #eaecf4;
            margin-bottom: 1.5rem;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: var(--secondary-color);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.2s;
            border-bottom: 3px solid transparent;
            margin-bottom: -2px;
        }
        
        .nav-tabs .nav-link:hover {
            color: var(--primary-color);
            background: rgba(78, 115, 223, 0.05);
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 3px solid var(--primary-color);
            background: transparent;
        }
        
        /* Forms */
        .form-label {
            font-weight: 600;
            color: var(--heading-color);
            font-size: 0.9rem;
        }
        
        .form-control-plaintext {
            color: var(--text-color);
            font-weight: 500;
        }
        
        .form-control {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #d1d3e2;
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
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        
        .btn-primary-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(78, 115, 223, 0.3);
            color: white;
        }

    </style>
  
</head>
<body>
<?php
    if (isset($_SESSION['username']) && $_SESSION['role'] === 'admin') {
        include 'admin-menu-bar.php';
        echo '<br>';
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
    } else{
        include 'menu-bar.php';
        ?><br><?php
        include 'sub-menu-btn.php';
        $session_username = $_SESSION['username'];
    }
?>

<div class="container py-4">
    <div class="row">
        <!-- Sidebar Profile Card -->
        <div class="col-md-3">
            <div class="card profile-user-card">
                <div class="profile-userpic">
                    <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="img-fluid" alt="User Image">
                </div>
                <div class="profile-name"><?php echo $name?></div>
                <div class="profile-role"><?php echo $role?></div>
                
                <hr class="my-4" style="opacity: 0.1">
                
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary btn-sm rounded-pill"><i class="bi bi-gear-fill me-1"></i> Account Settings</button>
                    <!-- Display 2FA status button -->
                    <?php if (!empty($google_totp_auth_secret)): ?>
                        <button class="btn btn-success btn-sm rounded-pill cursor-default"><i class="bi bi-shield-check me-1"></i> 2FA Secured</button>
                    <?php else: ?>
                        <button class="btn btn-warning btn-sm rounded-pill cursor-default"><i class="bi bi-shield-exclamation me-1"></i> 2FA Not Set</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Main Content Area -->
        <div class="col-md-9">
            <div class="card h-100">
                <div class="card-header-gradient">
                    <h5><i class="bi bi-person-lines-fill me-2"></i>My Profile</h5>
                    <span class="badge bg-light text-dark"><i class="bi bi-circle-fill text-success me-1" style="font-size:8px;"></i> Online</span>
                </div>
                <div class="card-body p-4">
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><i class="bi bi-info-circle me-1"></i> Basic Info</a>
                        </li>
                        <li class="nav-item" role="totp">
                           <a class="nav-link" id="totp-tab" data-bs-toggle="tab" href="#totp" role="tab" aria-controls="totp" aria-selected="false"><i class="bi bi-qr-code me-1"></i> Google 2FA</a>
                        </li>
                        <li class="nav-item" role="passcode">
                           <a class="nav-link" id="passcode-tab" data-bs-toggle="tab" href="#passcode" role="tab" aria-controls="passcode" aria-selected="false"><i class="bi bi-key me-1"></i> Passcode</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="forgotPassword-tab" data-bs-toggle="tab" href="#forgotPassword" role="tab" aria-controls="forgotPassword" aria-selected="false"><i class="bi bi-shield-lock me-1"></i> Password</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content pt-2">
                        <!-- INFO TAB -->
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 text-secondary">Username</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $username?>">
                                    </div>
                                </div>
                                <hr class="my-2" style="opacity: 0.05">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 text-secondary">Full Name</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <input type="text" readonly class="form-control-plaintext" value="<?php echo $name?>">
                                    </div>
                                </div>
                                <hr class="my-2" style="opacity: 0.05">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 text-secondary">Role</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill"><?php echo $role?></span>
                                    </div>
                                </div>
                                <hr class="my-2" style="opacity: 0.05">
                                <div class="row mb-3 align-items-center">
                                    <div class="col-sm-3">
                                        <h6 class="mb-0 text-secondary">Status</h6>
                                    </div>
                                    <div class="col-sm-9 text-secondary">
                                        <?php if ($status == 1): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <!-- PASSCODE TAB -->
                        <div class="tab-pane fade" id="passcode" role="tabpanel" aria-labelledby="passcode-tab">                    
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-8">
                                    <div class="p-4 bg-light rounded-3 border">
                                      <h6 class="text-primary mb-3 text-uppercase fw-bold"><i class="bi bi-incognito me-2"></i>Update Security Passcode</h6>
                                      <form id="update_passcode" autocomplete="off">
                                        <div class="mb-3">
                                          <label for="update-passcode" class="form-label">New 6-Digit Passcode</label>
                                          <div class="input-group">
                                                <span class="input-group-text bg-white"><i class="bi bi-123"></i></span>
                                                <input type="text" 
                                                    class="form-control" 
                                                    id="update-passcode" 
                                                    name="update-passcode" 
                                                    pattern="^\d{6}$" 
                                                    placeholder="######"
                                                    title="Passcode must be exactly 6 digits" 
                                                    maxlength="6" 
                                                    required>
                                          </div>
                                        </div>
                                        <input type="hidden" class="form-control" id="passcode_username" value="<?= $_SESSION['username'] ?>">
                                        <div class="d-grid">
                                          <button type="submit" class="btn btn-primary-gradient">Update Passcode</button>
                                        </div>
                                      </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- PASSWORD TAB -->
                        <div class="tab-pane fade" id="forgotPassword" role="tabpanel" aria-labelledby="forgotPassword-tab">                    
                            <div class="row justify-content-center mt-3">
                                <div class="col-md-8">
                                    <div class="p-4 bg-light rounded-3 border">
                                      <h6 class="text-secondary mb-3 text-uppercase fw-bold"><i class="bi bi-shield-lock me-2"></i>Reset Password</h6>
                                      <form id="forgot_password">
                                        <div class="mb-3">
                                          <label for="old_password" class="form-label">Current Password</label>
                                          <input type="password" class="form-control" id="old_password" name="oldPassword" required>
                                        </div>
                                        <div class="row g-2 mb-3">
                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label">New Password</label>
                                                <input type="password" class="form-control" id="new_password" name="newPassword" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                                <input type="password" class="form-control" id="confirm_password" name="confirmPassword" required>
                                            </div>
                                        </div>
                                        <div id="error_message" class="text-danger small mb-3 fw-bold"></div>
                                        
                                        <input type="hidden" class="form-control" id="f_username" value="<?= $_SESSION['username'] ?>">
                                        
                                        <div class="d-grid">
                                          <button type="submit" class="btn btn-primary-gradient">Change Password</button>
                                        </div>
                                      </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- TOTP TAB -->
                        <div class="tab-pane fade" id="totp" role="tabpanel" aria-labelledby="totp-tab">
                            <div class="row mt-3">
                                <div class="col-md-6 text-center border-end">
                                    <h6 class="text-uppercase text-secondary fw-bold mb-3">1. Scan QR Code</h6>
                                    <div class="bg-white p-2 d-inline-block border rounded mb-3">
                                        <img id="qrCodeImage" src="" alt="Google Authenticator QR Code" class="img-fluid" style="max-height: 180px;" />
                                    </div>
                                    <p class="small text-muted mb-1">Backup Secret Key:</p>
                                    <code class="d-block mb-3 bg-light p-2 rounded text-primary fw-bold" id="secret"></code>

                                    <div>
                                        <?php if (!empty($google_totp_auth_secret)): ?>
                                            <div class="alert alert-success d-inline-flex align-items-center py-2 px-3">
                                                <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                                                <div><strong>Status:</strong> Verified</div>
                                            </div>
                                        <?php else: ?>
                                            <div class="alert alert-danger d-inline-flex align-items-center py-2 px-3">
                                                <i class="bi bi-x-circle-fill fs-5 me-2"></i>
                                                <div><strong>Status:</strong> Not Verified</div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center">
                                    <h6 class="text-uppercase text-secondary fw-bold mb-3">2. Verify OTP</h6>
                                    <img id="googleAuthLogo" 
                                        src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/84/Google_Authenticator_%28April_2023%29.svg/800px-Google_Authenticator_%28April_2023%29.svg.png" 
                                        alt="Google Authenticator Logo" style="width: 80px; height: 80px;" class="mb-3" />
                                    
                                    <p class="text-muted small text-center px-4 mb-4">
                                        Open your Google Authenticator app and enter the 6-digit code generated for this account.
                                    </p>

                                    <form id="verifyForm" class="w-75">
                                        <div class="input-group mb-3">
                                            <input type="text" id="pinInput" class="form-control text-center fs-5 fw-bold" placeholder="000 000" pattern="^\d{1,6}$" maxlength="6" required>
                                            <button type="submit" class="btn btn-primary">Verify</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="exampleModalLabel"><i class="bi bi-bell-fill me-2"></i>Notification</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center py-4">
        <b><span id="response" class="fs-5"></span></b>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- jQuery and Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

<script>
    var google_totp_secret = null;
    // Fetch JSON response from the PHP script
    fetch('components/init_google_totp_auth.php')
        .then(response => response.json())
        .then(data => {
            // Display the QR code image
            $('#qrCodeImage').attr('src', data.image);

            // Display the secret
            google_totp_secret = data.secret;
            $('#secret').text(data.secret);
        })
        .catch(error => console.error('Error fetching data:', error));

    // Handle form submission
    $('#verifyForm').on('submit', function(event) {
        event.preventDefault();
        const pin = $('#pinInput').val();

        // Send the PIN to verify_google_totp.php
        $.ajax({
            url: 'components/verify_google_totp.php',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({ pin: pin, google_totp_secret: google_totp_secret }),
            success: function(data) {
                if (data.status) {
                    // Show success message
                    $('#pinInput').val('');  // Clear the PIN input field
                    Swal.fire({
                        icon: 'success',
                        title: 'PIN Verified',
                        text: data.message  // Display the success message from the server
                    }).then(() => {
                        // Set timeout of 1 second before reloading the page
                        setTimeout(function() {
                            window.location.reload();  // Reload the page after 1 second
                        }, 1000);  // 1000 milliseconds = 1 second
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid PIN',
                        text: data.message
                    });
                }
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire("Error", "Something went wrong while verifying the PIN.", "error");
            }
        });
    });
</script>

<script>
    
$(document).ready(function(){
    $("#forgot_password").submit(function(event){
        event.preventDefault();
        forgot_password();
    });
    $("#update_passcode").submit(function(event){
        event.preventDefault();
        update_passcode();
    });
});

function openModal() {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
}

function update_passcode() {
    var passcode = $("#update-passcode").val().trim();

    // Validate passcode: must be exactly 6 digits
    var passcodeRegex = /^[0-9]{6}$/;
    if (!passcodeRegex.test(passcode)) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'Passcode must be exactly 6 digits',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
        return; // stop execution if invalid
    }

    var formData = {
        username: "<?php echo $_SESSION['username']; ?>",
        update_passcode: passcode
    };

    $.ajax({
        type: "POST",
        url: "api/v1/update-passcode.php",
        data: JSON.stringify(formData),
        contentType: "application/json",
        success: function(response) {
            // SweetAlert2 popup for success
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: response.message || 'Passcode updated successfully',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            // Clear only the input field
            $("#update-passcode").val("");
        },
        error: function(xhr, status, error) {
            // SweetAlert2 popup for error
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Error: ' + status,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }
    });
}

function forgot_password() {
    var formData = {
        username: $("#f_username").val().trim(),
        old_password: $("#old_password").val().trim(),
        new_password: $("#new_password").val().trim(),
        confirm_password: $("#confirm_password").val().trim()
    };

    $.ajax({
        type: "POST",
        url: "api/v1/forgot-password.php",
        data: JSON.stringify(formData),
        contentType: "application/json",
        success: function(response) {
            $("#response").html(response.message); // Display response message
            openModal();
            // alert(response.message);
            document.getElementById("forgot_password").reset();
            document.getElementById("error_message").textContent = "";
        },
        error: function(xhr, status, error) {
            console.error('Error:', status);
            // alert(status);
            $("#response").html(status); // Display response message
            openModal();
        }
    });
}

function checkPasswordMatch() {
    var new_password = document.getElementById("new_password").value;
    var confirm_password = document.getElementById("confirm_password").value;

    // Check if passwords match
    if (new_password !== confirm_password) {
        // Display error message
        document.getElementById("error_message").textContent = "Passwords do not match";
    } else {
        // Clear error message if passwords match
        document.getElementById("error_message").textContent = "";
    }
}

    // Add event listeners to password and confirm password fields
    document.getElementById("new_password").addEventListener("input", checkPasswordMatch);
    document.getElementById("confirm_password").addEventListener("input", checkPasswordMatch);
    
</script>
</body>
</html>


<?php 
   include 'footer.php';
   // Close the database connection
   if(isset($con)) mysqli_close($con);
?>

<?php }else{
	header("Location: index.php");
} ?>