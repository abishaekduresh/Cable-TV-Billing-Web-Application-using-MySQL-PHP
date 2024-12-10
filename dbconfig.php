<?php
$con = mysqli_connect("localhost", "root", "", "database");

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

$SMS_GATEWAY_URL = "https://api.domain.php";
$SMS_API_KEY = urlencode('xxxxxxxxxxx');
$SMS_INDIV_BILLING_SENDER_ID = urlencode('xxxxx');
$SMS_INDIV_BILLING_TEMP_ID = urlencode('xxxxxxxxxx');
// Use only 2 variables
$SMS_INDIV_BILLING_TEMP = 'Message.';
// ------------------------------------------------------------
$SMS_LOGIN_SENDER_ID = urlencode('xxxxxx');
$SMS_LOGIN_TEMP_ID = urlencode('xxxxxxx');
// Use only 1 Variable
$SMS_LOGIN_TEMP = 'message.';

$SMS_LOC_SENDER_ID = urlencode('xxxxx');
$SMS_LOC_TEMP_ID = urlencode('xxxxxxxxxxx');
// Use only 1 Variable
$SMS_LOC_TEMP = 'Message {#var#}. ';

?>
