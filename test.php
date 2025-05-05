<?php

require_once 'dbconfig.php';
require_once 'component.php';

// echo json_encode(getUserGroupBillPayModeData('2024-12-05', '23A001', 'cash'));
$res = send_Login_SMS_OTP('7708443543', '123456'); 
$res_json = json_decode($res);

// Debug print
echo '<pre>';
print_r($res_json);
echo '</pre>';

?>
