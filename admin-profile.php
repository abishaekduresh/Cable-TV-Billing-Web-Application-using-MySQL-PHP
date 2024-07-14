<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   
   if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
 ?>



<?php include 'admin-menu-bar.php'; ?>

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
                                    <i class="fa-regular fa-user"></i><center><b><?php echo $role?></b></center>
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
                    <h5 class="card-title">Your info</h5>
                </div>
                <div class="card-body"><h1>Working On....</h1>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="pill" href="#home" role="tab" aria-controls="home" aria-selected="true">Info</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Profile</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="messages-tab" data-bs-toggle="pill" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Messages</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="settings-tab" data-bs-toggle="pill" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Settings</a>
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
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputFile" class="form-label">File input</label>
                                    <input type="file" class="form-control" id="exampleInputFile">
                                    <div class="form-text">Example block-level help text here.</div>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label" for="exampleCheck1">Check me out</label>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            Profile
                        </div>
                        <div class="tab-pane fade" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                            Messages
                        </div>
                        <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            Settings
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</body>
</html>


<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>
