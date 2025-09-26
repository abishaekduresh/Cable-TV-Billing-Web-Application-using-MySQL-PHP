<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';
require "dbconfig.php";
require "component.php";

use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$g = new GoogleAuthenticator();

if (isset($_POST['first'], $_POST['second'], $_POST['third'], $_POST['fourth'], $_POST['fifth'], $_POST['sixth'])) {

    $verify_otp = $_POST['first'] . $_POST['second'] . $_POST['third'] . $_POST['fourth'] . $_POST['fifth'] . $_POST['sixth'];

    $sessionOTP = isset($_SESSION['temp_login_otp']) ? $_SESSION['temp_login_otp'] : null;
    $otpOption = isset($_POST['otpOption']) ? $_POST['otpOption'] : null;

    $password = $_POST['password'];
    $username = $_POST['username'];

    if ($otpOption == 'smsOTP' || $otpOption == 'noOTP' || $otpOption == 'passcode') {
        if ($verify_otp == $sessionOTP) {

            // Use prepared statement to prevent SQL injection
            $stmt = $con->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $row = $result->fetch_assoc();

                if ($row['password'] === $password && $row['status'] == '1') {
                    $_SESSION['name'] = $row['name'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['username'] = $row['username'];

                    // Log user activity
                    logUserActivity($row['id'], $row['username'], $row['role'], "Logged in via $otpOption");

                    header("Location: redirect-login.php");
                    exit();
                } else {
                    header("Location: logout.php?error=1Incorrect Username or Password");
                    exit();
                }
            } else {
                header("Location: logout.php?error=2Incorrect Username or Password");
                exit();
            }

        } else {
            header("Location: logout.php?error=Invalid OTP, try again");
            exit();
        }

    } elseif ($otpOption == 'googleTOTP') {

        // Step 1: Try to fetch the user's TOTP secret
        $stmt = $con->prepare("SELECT google_totp_auth_secret FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $storedSecret = $row['google_totp_auth_secret'] ?? null;

        if ($storedSecret) {
            if (!$g->checkCode($storedSecret, $verify_otp)) {
                // echo json_encode(['status' => false, 'message' => 'Invalid OTP']);
                header("Location: logout.php?error=Invalid Google TOTP");
                exit();
            }
        }

        // Step 2: Verify username and password
        // Use prepared statement to prevent SQL injection
        $stmt = $con->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $row = $result->fetch_assoc();

            if ($row['password'] === $password && $row['status'] == '1') {
                $_SESSION['name'] = $row['name'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['username'] = $row['username'];

                // Log user activity
                logUserActivity($row['id'], $row['username'], $row['role'], "Logged in via Google TOTP");

                header("Location: redirect-login.php");
                exit();
            } else {
                header("Location: logout.php?error=3Incorrect Username or Password");
                exit();
            }
        } else {
            header("Location: logout.php?error=4Incorrect Username or Password");
            exit();
        }

    } else {
        header("Location: logout.php?error=Invalid OTP, try again!");
        exit();
    }

} else {
    header("Location: logout.php?error=Invalid OTP, try again!");
    exit();
}
