<?php
$con = mysqli_connect("localhost", "ujl8kvvqjhauz", "pd+*1@b15k[#", "dbj8ikofivgxgi");

// Check if connection is null
if (!$con) {
    header("Location: db-connection-error.php");
    exit(); // Terminate script after redirect
}

// Check connection
// if (mysqli_connect_errno()) {
//     die("Failed to connect to MySQL: " . mysqli_connect_error());
// }



date_default_timezone_set('Asia/Kolkata');

$timezone = new DateTimeZone('Asia/Kolkata');
$datetime = new DateTime('now', $timezone);
$currentTimeA = $datetime->format('h:i:s A');
$currentTime = $datetime->format('H:i:s');
$currentDate = $datetime->format('Y-m-d');

$currentDateTime = $datetime->format('Y-m-d H:i:s');

// $timestamp = $datetime->format('Y-m-d H:i:s');

$currentDay = $datetime->format('d');
$currentMonth = $datetime->format('m');
$currentYear = $datetime->format('Y');

// $session_username = $_SESSION['username'];

$SMS_GATEWAY_URL = "https://sms.textspeed.in/vb/apikey.php";
$SMS_API_KEY = urlencode('xxxx');
$SMS_INDIV_BILLING_SENDER_ID = urlencode('xxx');
$SMS_INDIV_BILLING_TEMP_ID = urlencode('xxxx');
// Use only 2 variables
$SMS_INDIV_BILLING_TEMP = 'Dear Customer, Your Cable TV bill for STB No: {#var1#}, has been {#var2#}.';
// ------------------------------------------------------------
$SMS_LOGIN_SENDER_ID = urlencode('xxx');
$SMS_LOGIN_TEMP_ID = urlencode('xxx');
// Use only 1 Variable
$SMS_LOGIN_TEMP = 'Your OTP is {#var1#} to securely access your account. Software by DURESH TECH.';

$SMS_LOC_SENDER_ID = urlencode('xxx');
$SMS_LOC_TEMP_ID = urlencode('xxxx');
// Use only 1 Variable
$SMS_LOC_TEMP = 'Dear Customer, Your Cable TV LOC bill for {#var#}. For more details, please contact us.';

?>
