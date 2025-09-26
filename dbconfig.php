<?php
$con = mysqli_connect("localhost", "uxxhv0w9uvg6t", "DFff48^*&*K6Jj", "dbnqnobrek5ptr");

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

$stmt = $con->prepare("SELECT * FROM settings LIMIT 1");
$stmt->execute();
$settingsResult = $stmt->get_result();

// Fetch single row as associative array
$settings = $settingsResult->fetch_assoc();

$isSentSMS = false; // default

if ($settings && isset($settings['sentSMS']) && $settings['sentSMS']) {
    $isSentSMS = true;
}

$SMS_GATEWAY_URL = "https://smsplans.com/api";
$SMS_API_ID = 'APIDbQz6fZ13';
$SMS_API_KEY = 'iui&*&';
$SMS_INDIV_BILLING_SENDER_ID = 'XXXX';
$SMS_INDIV_BILLING_TEMP_ID = '1707175688389463';
// Use only 2 variables
// Dear Customer, your PDP Cable TV bill for Service No: 00008317000ABCD, Due: MAY-2025, is paid. Thank you. - XXXX
$SMS_INDIV_BILLING_TEMP = 'Dear Customer, your PDP Cable TV bill for Service No: {#var1#}, Due: {#var2#}, is {#var3#}. Thank you. - XXXX';
// ------------------------------------------------------------
$SMS_LOGIN_SENDER_ID = 'XXXX';
$SMS_LOGIN_TEMP_ID = '1707172657082336';
// Use only 1 Variable
$SMS_LOGIN_TEMP = 'Your OTP is {#var1#} to securely access your account. Software by DURESH TECH.';

// $SMS_LOC_SENDER_ID = rawurlencode('XXXX');
// $SMS_LOC_TEMP_ID = rawurlencode('170717317398510997');
// // Use only 1 Variable
// $SMS_LOC_TEMP = 'Dear Customer, Your Cable TV LOC bill for {#var#}. For more details, please contact us. Software by DURESH TECH.';

$BIOMETRIC_API_URL = "https://api.xyz.com/biometric/public/api";
$BIOMETRIC_API_TOKEN = "YOUR_BEARER_TOKEN_HERE"; // Replace with your API token

?>
