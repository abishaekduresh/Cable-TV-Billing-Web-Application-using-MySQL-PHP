<?php
$con = mysqli_connect("localhost", "root", "", "pdpctv_dt_com");

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

// Advanced Billing: Set Target Month/Year based on GET selection or default
if (isset($_GET['billing_month']) && !empty($_GET['billing_month'])) {
    $selectedBillingDate = $_GET['billing_month']; // YYYY-MM
    $targetMonth = date('m', strtotime($selectedBillingDate));
    $targetYear = date('Y', strtotime($selectedBillingDate));
    $displayDate = $selectedBillingDate; // For setting the input value
} else {
    $targetMonth = $currentMonth;
    $targetYear = $currentYear;
    $displayDate = $currentYear . '-' . $currentMonth;
}

// $session_username = $_SESSION['username'];

$isSentSMS = true; // Set to true to enable SMS sending, false to disable

$SMS_GATEWAY_URL = "https://bulksmsplans.com/api";
$SMS_API_ID = 'APIDbQz6fZT137842';
$SMS_API_KEY = 'iuiub*&*&';
$SMS_INDIV_BILLING_SENDER_ID = 'DURTEH';
$SMS_INDIV_BILLING_TEMP_ID = '1707175688387594631';
// Use only 2 variables
// Dear Customer, your PDP Cable TV bill for Service No: 00008317000ABCD, Due: MAY-2025, is paid. Thank you. - DURTEH
$SMS_INDIV_BILLING_TEMP = 'Dear Customer, your PDP Cable TV bill for Service No: {#var1#}, Due: {#var2#}, is {#var3#}. Thank you. - DURTEH';
// ------------------------------------------------------------
$SMS_LOGIN_SENDER_ID = 'DURTEH';
$SMS_LOGIN_TEMP_ID = '1707172657083442336';
// Use only 1 Variable
$SMS_LOGIN_TEMP = 'Your OTP is {#var1#} to securely access your account. Software by DURESH TECH.';

// $SMS_LOC_SENDER_ID = rawurlencode('DURTEH');
// $SMS_LOC_TEMP_ID = rawurlencode('1707173173985140997');
// // Use only 1 Variable
// $SMS_LOC_TEMP = 'Dear Customer, Your Cable TV LOC bill for {#var#}. For more details, please contact us. Software by DURESH TECH.';

$BIOMETRIC_API_URL = "http://localhost/api.pdpgroups.com/public/api";
$BIOMETRIC_API_TOKEN = "YOUR_BEARER_TOKEN_HERE"; // Replace with your API token

?>
