<?php

function sms_credit(){
    global $SMS_API_KEY;
    // URL to retrieve JSON data
    $url = 'https://sms.textspeed.in/vb/http-credit.php?apikey=' . urlencode($SMS_API_KEY) . '&route_id=1&format=json';
    
    // Fetch JSON data from the URL
    $json_data = file_get_contents($url);
    
    // Decode JSON data into a PHP associative array
    $data_array = json_decode($json_data, true);
    
    // Check if JSON data was successfully decoded
    if ($data_array !== null) {
        // Extract data from the associative array
        $status = $data_array['status'];
        $code = $data_array['code'];
        $balance_sms_credit = $data_array['balance'];
    
        // Now you can use $status, $code, and $balance variables as needed
        // echo "Status: $status <br>";
        // echo "Code: $code <br>";
        // echo "Balance: $balance <br>";
        return $balance_sms_credit;
    } else {
        // Handle JSON decoding error
        return "Failed to decode JSON data.";
    }
}


?>