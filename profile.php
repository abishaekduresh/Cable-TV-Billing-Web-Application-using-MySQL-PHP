<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
 ?>



<?php if($_SESSION['role'] == 'admin'){include 'admin-menu-bar.php';}else{include 'menu-bar.php';} ?>

<?php
$username = $_SESSION['username'];

// Query to fetch data for the specific username
$sql = "SELECT * FROM user WHERE username = '$username'";

// Execute the query
$result = mysqli_query($con, $sql);

// Check if the query was successful
if ($result) {
    // Fetch the data and store it in a variable
    $data = mysqli_fetch_assoc($result);
    
    // Do something with the fetched data
    // For example, access specific column values
    $username = $data['username'];
    $name = $data['name'];
    $role = $data['role'];
    $status = $data['status'];
    
    // Display the values
    // echo "Username: " . $username . "<br>";
    // echo "Name: " . $name . "<br>";
    // echo "Role: " . $role . "<br>";
} else {
    // Query execution failed
    echo "Error: " . mysqli_error($con);
}

// Close the database connection
mysqli_close($con);
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <title>Profile</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
  
  
</head>
<body>
<br>
<!------------------------------------------------------------------------->
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="profile-userpic">
                        <img src="https://bootdey.com/img/Content/avatar/avatar6.png" class="img-fluid" alt="">
                    </div>
                    <!--<div class="profile-usertitle">-->
                    <!--    <div class="profile-usertitle-name">Marcus Doe</div>-->
                    <!--    <div class="profile-usertitle-job">Developer</div>-->
                    <!--</div>-->
                    <!--<div class="profile-userbuttons">-->
                    <!--    <button type="button" class="btn btn-info btn-sm">Follow</button>-->
                    <!--    <button type="button" class="btn btn-info btn-sm">Message</button>-->
                    <!--</div>--><br>
                    <div class="profile-usermenu">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">
                                    <i class="fa-regular fa-user"></i><center><b><?php echo $name?></b></center>
                                </a>
                            </li>
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link" href="#">-->
                            <!--        <i class="bi bi-gear"></i> Support Staff-->
                            <!--    </a>-->
                            <!--</li>-->
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link" href="#">-->
                            <!--        <i class="bi bi-info"></i> Configurations-->
                            <!--    </a>-->
                            <!--</li>-->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Profile</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="pill" href="#home" role="tab" aria-controls="home" aria-selected="true">Info</a>
                        </li>
                        <!--<li class="nav-item" role="presentation">-->
                        <!--    <a class="nav-link" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>-->
                        <!--</li>-->
                        <!--<li class="nav-item" role="presentation">-->
                        <!--    <a class="nav-link" id="messages-tab" data-bs-toggle="pill" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Messages</a>-->
                        <!--</li>-->
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="settings-tab" data-bs-toggle="pill" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Forgot Password</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <form><br>
                                <div class="mb-3 row">
                                <label for="username" class="col-sm-2 col-form-label"><b>Username :</b></label>
                                <div class="col-sm-10">
                                      <input type="text" readonly class="form-control-plaintext" id="username" value="<?php echo $username?>">
                                </div>
                                </div>
                                <div class="mb-3 row">
                                <label for="name" class="col-sm-2 col-form-label"><b>Name :</b></label>
                                <div class="col-sm-10">
                                      <input type="text" readonly class="form-control-plaintext" id="name" value="<?php echo $name?>">
                                </div>
                                </div>
                                <div class="mb-3 row">
                                <label for="name" class="col-sm-2 col-form-label"><b>Role :</b></label>
                                <div class="col-sm-10">
                                      <input type="text" readonly class="form-control-plaintext" value="<?php echo $role?>">
                                </div>
                                </div>
                                <div class="mb-3 row">
                                <label for="name" class="col-sm-2 col-form-label"><b>Status :</b></label>
                                <div class="col-sm-10">
                                 <?php
                                    $status_badge = ($status == 1) ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Deactive</span>';
                                ?>
                                <div class="form-control-plaintext">
                                    <?php echo $status_badge; ?>
                                </div>
                                </div>
                                </div>
                            </form>
                        </div>
                        <!--<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">-->
                        <!--    Profile-->
                        <!--</div>-->
                        <!--<div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">-->
                        <!--    Messages-->
                        <!--</div>-->
                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            
                            
                            <div class="container mt-5">
                              <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <div class="card-body">
                                      <form id="forgot_password">
                                        <div class="mb-3">
                                          <label for="oldPassword" class="form-label">Old Password</label>
                                          <input type="password" class="form-control" id="old_password" name="oldPassword" required>
                                        </div>
                                        <div class="mb-3">
                                          <label for="newPassword" class="form-label">New Password</label>
                                          <input type="password" class="form-control" id="new_password" name="newPassword" required>
                                        </div>
                                        <div class="mb-3">
                                          <label for="confirmPassword" class="form-label">Confirm Password</label>
                                          <input type="password" class="form-control" id="confirm_password" name="confirmPassword" required>
                                          <p id="error_message" style="color: red;"></p>
                                        </div>
                                          <input type="hidden" class="form-control" id="f_username" value="<?= $_SESSION['username'] ?>">
                                        <div class="text-center">
                                          <button type="submit" class="btn btn-primary">Reset Password</button>
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
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <b><span id="response"></span></b>
      </div>
    </div>
  </div>
</div>


<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    
$(document).ready(function(){
    $("#forgot_password").submit(function(event){
        event.preventDefault();
        forgot_password();
    });
});

function openModal() {
        var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
        myModal.show();
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


<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>
