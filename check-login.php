
<?php
//login login
session_start();
require "dbconfig.php";
require "component.php";


if (isset($_POST['first'], $_POST['second'], $_POST['third'], $_POST['fourth'])) {

    $verify_otp = $_POST['first'] . $_POST['second'] . $_POST['third'] . $_POST['fourth'];

    $sessionOTP = isset($_SESSION['temp_login_otp'])?$_SESSION['temp_login_otp']:'7805';

    if($verify_otp == $sessionOTP){

        $password = $_POST['password'];
        $username = $_POST['username'];

        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);
            if ($row['password'] === $password && $row['status'] == '1') {
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];

                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $username = $_SESSION['username'];
                $role = $_SESSION['role'];
                $action = "Logged in";
                
                // Call the function to insert user activity log
                logUserActivity($userId, $username, $role, $action);

                header("Location: redirect-login.php");
                exit(); // Terminate the script to prevent further execution
            } else {
                header("Location: logout.php?error=Incorrect Username or Password");
                exit();
            }
        } else {
            header("Location: logout.php?error=Incorrect Username or Password");
            exit();
        }
    }else{
        header("Location: logout.php?error=Invalid OTP, try again");
        exit();
    }
    // function test_input($data)
    // {
    //     $data = trim($data);
    //     $data = stripslashes($data);
    //     $data = htmlspecialchars($data);
    //     return $data;
    // }

    // $username = test_input($_POST['username']);
    // $password = test_input($_POST['password']);

    // if (empty($username)) {
    //     header("Location: logout.php?error=User Name is Required");
    // } else if (empty($password)) {
    //     header("Location: logout.php?error=Password is Required");
    // } else {

    //     // Hashing function

    // }
} else {
    header("Location: logout.php?error=Invalid OTP, try again!");
    exit();
}
?>
