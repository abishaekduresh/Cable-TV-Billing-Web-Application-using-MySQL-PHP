<?php
// session_timeout.php

// Start or resume the session
// session_start();

// Set the session timeout period to 3 minutes (180 seconds)
$timeout = 1800;

// Check if the user is logged in
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    // If the user has been inactive for more than 3 minutes, destroy the session and redirect to login
    session_unset();
    session_destroy();
    header("Location: index.php?error=Session timed out");
    exit;
}

// Update the last activity timestamp
$_SESSION['last_activity'] = time();
?>

<?php

function logUserActivity($userId, $username, $role, $action) {

    include 'dbconfig.php';
    // Insert user Bill Excel downloaded
    $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$username', '$role', '$action')";
    mysqli_query($con, $insertSql);
}

function fetchGroupName($groupid) {
    include 'dbconfig.php';

    $query = "SELECT groupName FROM groupinfo WHERE group_id != '2' AND group_id='$groupid'";
    $result=mysqli_query($con, $query);

    while ($row = $result->fetch_assoc()) {
        echo $row['groupName'];
    }
}

function formatDate($formatdate) {
    include 'dbconfig.php';

    echo date("d-m-Y", strtotime($formatdate));
}

function convertTo12HourFormat($time24Hour) {
    // Create a DateTime object from the input time
    $timeObj = DateTime::createFromFormat('H:i:s', $time24Hour);

    // Convert to 12-hour format
    $time12Hour = $timeObj->format('h:i A');

    return $time12Hour;
}

function customDelay($seconds) {
    sleep($seconds);
}

function getCategoryName($con, $categoryId) {
    // SQL query
    $sql = "SELECT * FROM in_ex_category WHERE category_id='$categoryId'";

    // Execute query
    $result = mysqli_query($con, $sql);

    // Check if there are any rows in the result
    if (mysqli_num_rows($result) > 0) {
        // Fetch the first row
        $row = mysqli_fetch_assoc($result);
        // Return the category name
        return $row["category"];
    } else {
        return "Category not found";
    }
}

function getSubCategoryName($con, $subcategoryId) {
    // SQL query
    $sql = "SELECT * FROM in_ex_subcategory WHERE subcategory_id='$subcategoryId'";

    // Execute query
    $result = mysqli_query($con, $sql);

    // Check if there are any rows in the result
    if (mysqli_num_rows($result) > 0) {
        // Fetch the first row
        $row = mysqli_fetch_assoc($result);
        // Return the category name
        return $row["subcategory"];
    } else {
        return "SubCategory not found";
    }
}

function printClose(){
    
    include 'dbconfig.php';

            echo "<script type='text/javascript'>
                window.onload = function() {
                    //setTimeout(function() {
                        window.print(); // Open print dialog after 1 second
                    //}, 1000); // 1000 milliseconds = 1 second
                };
            </script>";

            // Tab Close function
            function closeTab() {
                echo "<script>
                setTimeout(function(){
                    window.close();
                }, 2000);
                </script>";
            }
    
    // Close the database connection
    $con->close();
        
    // Usage example
    closeTab();
}

function splitDateAndTime($timestamp) {
    // Use the date() function to format the timestamp
    $date = date('Y-m-d', $timestamp); // Format for date (e.g., 2023-10-21)
    $time = date('H:i:s', $timestamp); // Format for time (e.g., 14:30:00)

    return array('date' => $date, 'time' => $time);
}


function call_api($url) {
    // Make GET request to API endpoint
    $response = file_get_contents($url);
    
    // Return the response
    return $response;
}


function sms_api($name, $phone, $billNo, $due_month_timestamp, $stbno, $pMode, $con) {

        // SMS API
        
        if ($pMode == 'cash' || $pMode == 'gpay') {
            $pMode1 = 'Paid';
        } elseif ($pMode == 'credit') {
            $pMode1 = 'Unpaid - Credit';
        } else {
            $pMode1 = '-';
        }

                // API endpoint URL
        $url = 'https://sms.textspeed.in/vb/apikey.php';
        
        $apiKey = urlencode('EUdKjgM81BAe6eEv');
        $sender_id = urlencode('DURTEK');
        $template_id = urlencode('1707171187493463121');
        $message = rawurlencode('Dear Customer, Your THOOYAVAN PDP Cable TV bill (STB No: ' . $stbno . ') is due on ' . $due_month_timestamp . '. Status: ' . $pMode1 . '. DURTEK Thank you.');
        
        $data = 'apikey=' . $apiKey . '&senderid=' . $sender_id . '&templateid=' . $template_id . '&number=' . $phone . '&message=' . $message;
        
        // Final URL with query parameters
        $finalUrl = $url . '?' . $data;
        
        // Triggering the API using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return true;
        // echo "<script>console.log('$response');</script>";

}


?>

