<?php
//login login
session_start();
include "dbconfig.php";

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = test_input($_POST['username']);
    $password = test_input($_POST['password']);
    $role = test_input($_POST['role']);

    if (empty($username)) {
        header("Location: index.php?error=User Name is Required");
    } else if (empty($password)) {
        header("Location: index.php?error=Password is Required");
    } else {

        // Hashing function
        $password = md5($password);

        $sql = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($con, $sql);
        

	
	/////////////////////////////////////////////////

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);
            if ($row['password'] === $password && $row['role'] == $role && $row['status'] == '1') {
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];

                // Insert user login activity
                $userId = $row['id'];
                $userName = $row['username'];
                $role = $row['role'];
                $currentDate = $currentDate;
                $currentTime = $currentTime;
                $action = 'logged in';
                $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$userName', '$role', '$action')";
                mysqli_query($con, $insertSql);

                header("Location: redirect-login.php");
                exit(); // Terminate the script to prevent further execution
            } else {
                header("Location: index.php?error=Incorrect Username or Password");
                exit();
            }
        } else {
            header("Location: index.php?error=Incorrect Username or Password");
            exit();
        }
    }
} else {
    header("Location: index.php");
    exit();
}
?>
