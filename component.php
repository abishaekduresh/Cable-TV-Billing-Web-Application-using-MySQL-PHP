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

/*function fetchGroupName($groupid) {
    include 'dbconfig.php';

    $query = "SELECT groupName FROM groupinfo WHERE group_id != '2' AND group_id='$groupid'";
    $result=mysqli_query($con, $query);

    while ($row = $result->fetch_assoc()) {
        echo $row['groupName'];
    }
}*/
function fetchGroupName($groupId) {
    include 'dbconfig.php';

    // Sanitize the input to prevent SQL injection
    $groupId = mysqli_real_escape_string($con, $groupId);

    // Query to fetch the group name
    $query = "SELECT groupName FROM groupinfo WHERE group_id != '2' AND group_id='$groupId'";
    $result = mysqli_query($con, $query);

    // Check if the query returned any rows
    if ($row = $result->fetch_assoc()) {
        return $row['groupName'];
    } else {
        return 'Unknown Group'; // Default value if no match found
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


function sms_api($name, $phone, $billNo, $due_month_timestamp, $stbno, $pMode, $bill_status) {
    
        global $con;
    
        $dateTime = new DateTime($due_month_timestamp);
        $formattedDate = $dateTime->format("M-Y");

        // SMS API
        
        if ($bill_status == 'approve') {
            if ($pMode == 'cash' || $pMode == 'gpay' || $pMode == 'Paytm' ) {
                $pMode1 = 'PAID';
            } elseif ($pMode == 'credit') {
                $pMode1 = 'UNPAID - Credit Bill';
            } else {
                $pMode1 = '-';
            }
        } elseif ($bill_status == 'cancel') {
            $pMode1 = 'Cancelled';
        } else {
            $pMode1 = '-';
        }

                // API endpoint URL
        $url = 'https://sms.textspeed.in/vb/apikey.php';
        
        $apiKey = urlencode('i1JdnQyj9tFYW6S7');
        $sender_id = urlencode('DURTEH');
        // $template_id = urlencode('1707171187493463121');
        $template_id = urlencode('1707171774363119788');
        // $message = rawurlencode('Dear Customer, Your THOOYAVAN PDP Cable TV bill (STB No: ' . $stbno . ') is due on ' . $due_month_timestamp . '. Status: ' . $pMode1 . '. DURTEK Thank you.');
        if(isset($pMode1)){
            $message = rawurlencode('Dear Customer,\nYour Cable TV bill for STB No: '.$stbno.', due in '.$formattedDate.', has been '.$pMode1.'.\nSoftware by,\nDURESH TECH.');
        }
        // elseif($bill_status == 'cancel'){
        //     $message = rawurlencode('Dear Customer,\nYour Cable TV bill for STB No: '.$stbno.', due in '.$formattedDate.', has been '.$pMode2.'.\nSoftware by,\nDURESH TECH.');
        // }
        else{
            $apiKey = urlencode('XX');
            $message = rawurlencode('Best regards, DURTEH');
        }
        
        $data = 'apikey=' . $apiKey . '&senderid=' . $sender_id . '&templateid=' . $template_id . '&number=' . $phone . '&message=' . $message;
        
        // Final URL with query parameters
        $finalUrl = $url . '?' . $data;
        
        // Triggering the API using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
        // return $message;
        // echo "<script>console.log('$response');</script>";

}


function fetchIndivPreMonthPaidStatus($term) {
    global $con;
    global $currentDate;

    // Get the previous month and year
    $current_year = date('Y', strtotime($currentDate));
    $current_month = date('m', strtotime($currentDate));

    $year = NULL;
    $month = NULL;

    if ($current_month == 01) {
        $year = $current_year - 01;
        $month = 12;
    } else {
        $month = $current_month - 01;
        $year = $current_year;
    }

    // Construct the SQL query
    $sql = "SELECT due_month_timestamp
            FROM bill 
            WHERE CONCAT(stbno, name, phone) LIKE '%$term%' 
                AND status = 'approve'
                AND DATE_FORMAT(due_month_timestamp, '%Y') = '$year'
                AND DATE_FORMAT(due_month_timestamp, '%m') = '0$month'";

    // Execute the query
    $result = $con->query($sql);

    if ($result === false) {
        return "Error executing the query: " . $con->error;
    } elseif ($result->num_rows > 0) {
        // $row = $result->fetch_assoc();
        // $due_month_timestamp = $row["due_month_timestamp"];

        $query = "SELECT due_month_timestamp
        FROM bill 
        WHERE CONCAT(stbno, name, phone) LIKE '%$term%' 
            AND status = 'approve'
            AND DATE_FORMAT(due_month_timestamp, '%Y') = '$current_year'
            AND DATE_FORMAT(due_month_timestamp, '%m') = '$current_month'";

            // Execute the query
            $reslt = $con->query($query);

            // Check for errors
            if (!$reslt) {
            // If there's an error, handle it
            $error = $con->error;
            
                return "Error: $error";
            }

            // Check if any rows are returned
            if ($reslt->num_rows > 0) {
                // Rows are returned, meaning there are old dues
                return "<div style='color: green;'>Current Month Paid</div>";
            } else {
                // No rows returned, meaning no old dues
                return "<div style='color: #a19e03;'>No Due</div>";
            }


    } else {
        return "<div style='color: red;'>Old Due Pending</div>";
    }
}

function generate_indiv_bill_csrf_token() {

    unset($_SESSION['indiv_bill_csrf_token']);
    
    // Check if the session variable is set
    if (!isset($_SESSION['indiv_bill_csrf_token'])) {
        // Generate a random token
        $token = bin2hex(random_bytes(32));
        
        // Set the session variable
        $_SESSION['indiv_bill_csrf_token'] = $token;
    } else {
        // Token already exists, unset it
        unset($_SESSION['indiv_bill_csrf_token']);
    }
    
    // Return the token
    return $_SESSION['indiv_bill_csrf_token'];
}

function validateInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// function generate_pos_billing_token() {

//     unset($_SESSION['generate_pos_billing_token']);
    
//     // Check if the session variable is set
//     if (!isset($_SESSION['generate_pos_billing_token'])) {
//         // Generate a random token
//         $token = bin2hex(random_bytes(16));
        
//         // Set the session variable
//         $_SESSION['generate_pos_billing_token'] = 'DUR43' . $token . '3A5';
//     } else {
//         // Token already exists, unset it
//         unset($_SESSION['generate_pos_billing_token']);
//     }
    
//     // Return the token
//     return $_SESSION['generate_pos_billing_token'];
// }

function generateUniqueToken($tableName, $columnName, $length = 64) {
    global $con;

    do {
        // Generate a random unique ID
        $uniqueId = uniqid('', true) . mt_rand();
        
        // Ensure the length of the unique ID if specified
        if ($length) {
            $uniqueId = substr($uniqueId, 0, $length);
        }

        // Prepare a statement to check if the unique ID exists in the table
        $stmt = $con->prepare("SELECT COUNT(*) FROM $tableName WHERE $columnName = ?");
        $stmt->bind_param("s", $uniqueId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
    } while ($count > 0);

    return $uniqueId;
}

function getProductName($con, $productId) {
    // SQL query
    $sql = "SELECT * FROM pos_product WHERE pos_product_id = '$productId'";

    // Execute query
    $result = mysqli_query($con, $sql);

    // Check if there are any rows in the result
    if (mysqli_num_rows($result) > 0) {
        // Fetch the first row
        $row = mysqli_fetch_assoc($result);
        // Return the product name
        return $row["product_name"];
    } else {
        return "Product not found";
    }
}

// function generateUniquePosInvoiceId() {
    
//     global $con;
//     global $currentDate;

//     $stmt = $con->prepare("SELECT MAX(bill_no) FROM pos_bill WHERE DATE(entry_timestamp) = ?");
//     $stmt->bind_param("s", $currentDate);
//     $stmt->execute();
//     $stmt->bind_result($lastId);
//     $stmt->fetch();
//     $stmt->close();

//     if ($lastId) {
//         $lastIdParts = explode('-', $lastId);
//         $lastSequence = intval(end($lastIdParts));
//         $sequence = $lastSequence + 1;
//     } else {
//         $sequence = 1; 
//     }

//     $uniqueId = 'INVOICE-' . $sequence;

//     return $uniqueId;
// }

function extractIntegers($str) {
    preg_match_all('/\d+/', $str, $matches);
    return implode('', $matches[0]);
}

function generateUniquePosInvoiceId() {
    global $con;
    global $currentDate;
    $bill_no = 0;
    
    $sql = "SELECT MAX(bill_no) AS bill_no FROM pos_bill WHERE DATE(entry_timestamp) = '$currentDate'";
    
    // Execute the query
    $result = $con->query($sql);
    
    // Check if there are any results
    if ($result->num_rows > 0) {
        // Fetch data and assign it to a variable
        while ($row = $result->fetch_assoc()) {
            // Access the 'bill_no' key
            $bill_no = $row['bill_no'];
        }
    } else {
        $bill_no = 0;
    }
    // Increment the retrieved bill number and return
    return $bill_no + 1;
}

function getPayModeName($pay_mode_id) {
    
    global $con;
    // SQL query
    $sql = "SELECT * FROM pay_mode WHERE pay_mode_id = '$pay_mode_id' AND status = '1'";

    // Execute query
    $result = mysqli_query($con, $sql);

    // Check if there are any rows in the result
    if (mysqli_num_rows($result) > 0) {
        // Fetch the first row
        $row = mysqli_fetch_assoc($result);
        // Return the category name
        return $row["name"];
    } else {
        return "Not found or Deactive";
    }
}

function separateDate($date) {
    // Create a DateTime object from the date string
    $dateTime = new DateTime($date);

    // Extract day, month, and year
    $day = $dateTime->format('d');
    $month = $dateTime->format('m');
    $year = $dateTime->format('Y');

    // Return the result as an associative array
    return array(
        'day' => $day,
        'month' => $month,
        'year' => $year
    );
}


?>

