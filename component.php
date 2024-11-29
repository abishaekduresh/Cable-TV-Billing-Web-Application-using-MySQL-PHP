<?php
// session_timeout.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start or resume the session
// session_start();

// Set the session timeout period to 3 minutes (180 seconds)
$timeout = 20 * 60;

// Check if the user is logged in
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    // If the user has been inactive for more than 3 minutes, destroy the session and redirect to login
    session_unset();
    session_destroy();
    header("Location: logout.php");
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

/*/	Tamil SMS
function sms_api($name, $phone, $billNo, $due_month_timestamp, $stbno, $pMode, $bill_status) {
    
        global $con;
    
        $dateTime = new DateTime($due_month_timestamp);
        $formattedDate = $dateTime->format("M-Y");

        // SMS API
        

        
        // Final URL with query parameters
        $finalUrl = $url . '?' . $data;
        
        // Triggering the API using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $finalUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
}
*/

// function sms_api($name, $phone, $billNo, $due_month_timestamp, $stbno, $pMode, $bill_status) {
    
//         global $con;
    
//         $dateTime = new DateTime($due_month_timestamp);
//         $formattedDate = $dateTime->format("M-Y");

//         // Flag 1 = Tamil SMS, 2 = English SMS
// 		$flag = '2';
//         if($flag == '2'){
// 			if ($bill_status == 'approve') {
// 				if ($pMode == 'cash' || $pMode == 'gpay' || $pMode == 'Paytm' ) {
// 					$pMode1 = 'PAID';
// 				} elseif ($pMode == 'credit') {
// 					$pMode1 = 'UNPAID - Credit Bill';
// 				} else {
// 					$pMode1 = '-';
// 				}
// 			} elseif ($bill_status == 'cancel') {
// 				$pMode1 = 'Cancelled';
// 			} else {
// 				$pMode1 = '-';
// 			}

// 					// API endpoint URL
// 			$url = 'https://sms.textspeed.in/vb/apikey.php';

// 			$apiKey = urlencode('CpyZ6bypXgAhnqSP');
// 			$sender_id = urlencode('DURTEH');
// 			$template_id = urlencode('1707171775221460663');
// 			if(isset($pMode1)){
// 				$message = rawurlencode('Dear Customer, \nYour Cable TV bill for STB No: '.$stbno.', due in '.$formattedDate.', has been '.$pMode1.'.\nSoftware by,\nDURESH TECH.');
// 			}

// 			$data = 'apikey=' . $apiKey . '&senderid=' . $sender_id . '&templateid=' . $template_id . '&number=' . $phone . '&message=' . $message;
			
// 		}elseif($flag == '1'){	// Flag 1 Tamil SMS
			
// 			if ($bill_status == 'approve') {
// 				if ($pMode == 'cash' || $pMode == 'gpay' || $pMode == 'Paytm' ) {
// 					$pMode1 = 'செலுத்தப்பட்டது';
// 				} elseif ($pMode == 'credit') {
// 					$pMode1 = 'Credit Bill - செலுத்தப்படவில்லை';
// 				} else {
// 					$pMode1 = '-';
// 				}
// 			} elseif ($bill_status == 'cancel') {
// 				$pMode1 = 'ரத்து செய்யப்பட்டது';
// 			} else {
// 				$pMode1 = '-';
// 			}

// 					// API endpoint URL
// 			$url = 'https://sms.textspeed.in/vb/apikey.php';

// 			$apiKey = urlencode('CpyZ6bypXgAhnqSP');
// 			$sender_id = urlencode('DURTEH');
// 			$template_id = urlencode('1707172603355860021');
// 			if(isset($pMode1)){
// 				$message = rawurlencode('அன்புள்ள PDP கேபிள் டிவி வாடிக்கையாளர், STB எண்: '.$stbno.'-க்கான '.$formattedDate.' ஆம் மாத சந்தா '.$pMode1.'. Software by, DURESH TECH');
// 			}

// 			$data = 'apikey=' . $apiKey . '&senderid=' . $sender_id . '&templateid=' . $template_id . '&number=' . $phone . '&message=' . $message . '&unicode=2';
// 		}else{
			
// 		}
        
//         // Final URL with query parameters
//         $finalUrl = $url . '?' . $data;
        
//         // Triggering the API using cURL
//         $ch = curl_init();
//         curl_setopt($ch, CURLOPT_URL, $finalUrl);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         $response = curl_exec($ch);
//         curl_close($ch);
        
//         return $response;
//         // return $message;
//         // echo "<script>console.log('$response');</script>";

// }


// function fetchIndivPreMonthPaidStatus($term) {
//     global $con;
//     global $currentDate;

//     // Get the previous month and year
//     $current_year = date('Y', strtotime($currentDate));
//     $current_month = date('m', strtotime($currentDate));

//     $year = NULL;
//     $month = NULL;

//     if ($current_month == 01) {
//         $year = $current_year - 01;
//         $month = 12;
//     } else {
//         $month = $current_month - 01;
//         $year = $current_year;
//     }

//     // Construct the SQL query
//     $sql = "SELECT due_month_timestamp
//             FROM bill 
//             WHERE CONCAT(stbno, name, phone) LIKE '%$term%' 
//                 AND status = 'approve'
//                 AND DATE_FORMAT(due_month_timestamp, '%Y') = '$year'
//                 AND DATE_FORMAT(due_month_timestamp, '%m') = '0$month'";

//     // Execute the query
//     $result = $con->query($sql);

//     if ($result == false) {
//         return "Error executing the query: " . $con->error;
//     } elseif ($result->num_rows > 0) {
//         // $row = $result->fetch_assoc();
//         // $due_month_timestamp = $row["due_month_timestamp"];

//         $query = "SELECT due_month_timestamp
//         FROM bill 
//         WHERE CONCAT(stbno, name, phone) LIKE '%$term%' 
//             AND status = 'approve'
//             AND DATE_FORMAT(due_month_timestamp, '%Y') = '$current_year'
//             AND DATE_FORMAT(due_month_timestamp, '%m') = '$current_month'";

//             // Execute the query
//             $reslt = $con->query($query);

//             // Check for errors
//             if (!$reslt) {
//             // If there's an error, handle it
//             $error = $con->error;
            
//                 return "Error: $error";
//             }

//             // Check if any rows are returned
//             if ($reslt->num_rows > 0) {
//                 // Rows are returned, meaning there are old dues
//                 return "<div style='color: green;'>Current Month Paid</div>";
//             } else {
//                 // No rows returned, meaning no old dues
//                 return "<div style='color: #a19e03;'>No Due</div>";
//             }


//     } else {
//         return "<div style='color: red;'>Old Due Pending</div>";
//     }
// }

function fetchIndivPreMonthPaidStatus($term, $currentDate) {
    global $con;
    global $currentDate;

    // Get the previous month and year
    $current_year = date('Y', strtotime($currentDate));
    $current_month = date('m', strtotime($currentDate));

    $year = $current_year;
    $month = $current_month;

    // Handle the previous month
    if ($current_month == 1) {
        $month = 12;   // Previous month is December
        $year = $current_year - 1; // Previous year
    } else {
        $month = $current_month - 1; // Previous month
    }

    // Ensure the month is in two-digit format
    $previous_month = str_pad($month, 2, '0', STR_PAD_LEFT);
    
    // Prepare SQL query for previous month due
    $sql = "SELECT due_month_timestamp
            FROM bill
            WHERE CONCAT(stbno, name, phone) LIKE ?
            AND status = 'approve'
            AND DATE_FORMAT(due_month_timestamp, '%Y') = ?
            AND DATE_FORMAT(due_month_timestamp, '%m') = ?";

    // Prepare and bind the statement to prevent SQL injection
    $stmt = $con->prepare($sql);
    if ($stmt === false) {
        return "Error preparing the query: " . $con->error;
    }

    // Bind the parameters
    $term_with_wildcard = "%$term%";
    $stmt->bind_param("sss", $term_with_wildcard, $year, $previous_month);

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result === false) {
        return "Error executing the query: " . $con->error;
    } elseif ($result->num_rows > 0) {
        // Previous month due exists, now check for current month
        // Prepare SQL query for current month
        $current_month = str_pad($current_month, 2, '0', STR_PAD_LEFT);
        
        $query = "SELECT due_month_timestamp
                  FROM bill
                  WHERE CONCAT(stbno, name, phone) LIKE ?
                  AND status = 'approve'
                  AND DATE_FORMAT(due_month_timestamp, '%Y') = ?
                  AND DATE_FORMAT(due_month_timestamp, '%m') = ?";
        
        $stmt = $con->prepare($query);
        if ($stmt === false) {
            return "Error preparing the query: " . $con->error;
        }
        
        $stmt->bind_param("sss", $term_with_wildcard, $current_year, $current_month);
        
        // Execute the query for the current month
        $stmt->execute();
        $reslt = $stmt->get_result();

        if ($reslt === false) {
            return "Error executing the query: " . $con->error;
        }

        // Check if any rows are returned for the current month
        if ($reslt->num_rows > 0) {
            // Paid for current month
            return array(
                'code' => 200,
                'message' => "Current Month Paid",
                'html_code' => "<div style='color: green;'>Current Month Paid</div>"
            );
        } else {
            // No dues for the current month
            return array(
                'code' => 300,
                'message' => "Current Month Paid",
                'html_code' => "<div style='color: #a19e03;'>Current Month Unpaid</div>"
            );
        }
    } else {
        // Previous month due is pending
        return array(
            'code' => 400,
            'message' => "Current Month Paid",
            'html_code' => "<div style='color: red;'>Old Due Pending</div>"
        );
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

function generateUniqueToken($tableName, $columnName, $length = 32) {
    global $con;
    $count = 0;

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
    // Check if the date is valid and not null
    if (!empty($date)) {
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
    } else {
        // Return null or default values if the date is invalid or null
        return array(
            'day' => null,
            'month' => null,
            'year' => null
        );
    }
}


function generateChannelUid() {
    global $con;
    $maxUid = 0;

    // Retry mechanism to ensure unique UID generation
    do {
        // Prepare the SQL query to find the highest channel_uid
        $sql = "SELECT MAX(channel_uid) AS max_uid FROM loc_channels";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        $stmt->bind_result($maxUid);
        $stmt->fetch();
        
        // Generate the new UID based on the maximum channel_uid
        if ($maxUid) {
            $number = intval(substr($maxUid, 3)) + 1;
            $channelUid = 'LOC' . str_pad($number, 2, '0', STR_PAD_LEFT);
        } else {
            $channelUid = 'LOC01'; // Start from LOC01 if no previous UID exists
        }
        
        // Close the statement
        $stmt->close();

        // Check if the generated UID is unique
        $stmtCheck = $con->prepare("SELECT channel_uid FROM loc_channels WHERE channel_uid = ?");
        $stmtCheck->bind_param("s", $channelUid);
        $stmtCheck->execute();
        $stmtCheck->store_result();
        $isDuplicate = $stmtCheck->num_rows > 0;
        $stmtCheck->close();
    } while ($isDuplicate); // Keep generating until a unique UID is found

    // Ensure the generated UID is not empty
    if (empty($channelUid)) {
        throw new Exception('Failed to generate a valid channel UID.');
    }

    return $channelUid;
}



// Usage
// $loc_gen_bill_log_array_data = get_loc_gen_bills_log();
// $loc_gen_bill_log_id = isset($loc_gen_bill_log_array_data['loc_gen_bill_log_id']) ? $loc_gen_bill_log_array_data['loc_gen_bill_log_id'] : 0;

// if ($loc_gen_bill_log_id > 0) {
//     // Record found, proceed with logic
// } else {
//     // No record found
// }

function get_due_month_year_by_gen_bill_id($gen_bill_id, $channel_uid){
    global $con;
    $status = 1;

    $stmt = $con->prepare("
        SELECT due_month, due_year 
        FROM loc_gen_bills_log 
        WHERE loc_gen_bill_log_id IN (
            SELECT loc_gen_bill_log_id 
            FROM loc_gen_bills 
            WHERE loc_gen_bill_id = ? 
            AND channel_uid = ?
            AND status = ?
        )
    ");
    $stmt->bind_param("isi", $gen_bill_id, $channel_uid, $status);
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch a single row as an associative array
    $row = $result->fetch_assoc();
    
    // Close the statement
    $stmt->close();
    
    // Check if any row is returned
    if ($row) {
        // Return the result as an associative array
        return array(
            'due_month' => $row['due_month'],
            'due_year' => $row['due_year']
        );
    } else {
        // If no row is found, return a 'no record found' message
        return array('message' => 'No record found');
    }
}

function create_loc_prop_login($created_at, $loc_gen_bill_id, $channel_uid) {
    global $con;
    $status = 0;
    $generateUniqueToken = generateUniqueToken('loc_prop_login', 'token', 7); // Assuming length is 10

    // Corrected SQL statement
    $stmt = $con->prepare("INSERT INTO loc_prop_login (created_at, loc_gen_bill_id, channel_uid, token, status) VALUES (?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssi", $created_at, $loc_gen_bill_id, $channel_uid, $generateUniqueToken, $status);
    
    // Execute the statement
    if ($stmt->execute()) {
        return array(
            'loc_gen_bill_id' => $loc_gen_bill_id,
            'channel_uid' => $channel_uid,
            'token' => $generateUniqueToken
        );
    } else {
        // Handle error
        return array('message' => 'Failed to insert record');
    }
    
    $stmt->close();
}


function get_loc_channel_by_uid($channel_uid) {
    global $con;

    $stmt = $con->prepare("SELECT * FROM loc_channels WHERE channel_uid = ?");
    $stmt->bind_param("s", $channel_uid);
    
    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch a single row as an associative array
    $row = $result->fetch_assoc();
    
    // Close the statement
    $stmt->close();
    
    // Check if any row is returned
    if ($row) {
        // Return the result as an associative array
        return array(
            'created_at' => $row['created_at'],
            'created_user_id' => $row['created_user_id'],
            'channel_uid' => $row['channel_uid'],
            'channel_name' => $row['channel_name'],
            'prop_name' => $row['prop_name'],
            'prop_phone' => $row['prop_phone'],
            'prop_address' => $row['prop_address'],
            'network_amount' => $row['network_amount'],
            'remark' => $row['remark'],
            'status' => $row['status'],
            'updated_at' => $row['updated_at'],
            'updated_user_id' => $row['updated_user_id']
        );
    } else {
        // If no row is found, return a 'no record found' message
        return array('message' => 'No record found');
    }
    
}

function update_loc_prop_login_limit($token) {

    global $con;

    $limit = 2;

    // Prepare the statement to fetch the record with the matching token
    $stmt = $con->prepare("SELECT * FROM loc_prop_login WHERE token = ?");
    $stmt->bind_param("s", $token);
    
    // Execute the statement
    if($stmt->execute() === true) {
        $result = $stmt->get_result();

        // Check if any row was returned
        if ($result->num_rows > 0) {
            // Fetch the single row
            $row = $result->fetch_assoc();
            $stmt->close(); // Close the statement after fetching

            $status = $row['status'];

            if ($status < $limit) {
                $status = $status + 1;
                $stmt = $con->prepare("UPDATE loc_prop_login SET status = ? WHERE token = ?");
                $stmt->bind_param("is", $status, $token);
                if ($stmt->execute()) {
                    return "true";
                } else {
                    return "false";
                }
            } else {
                $status = $status + 1;
                $stmt = $con->prepare("UPDATE loc_prop_login SET status = ? WHERE token = ?");
                $stmt->bind_param("is", $status, $token);
                $stmt->execute();
                return "false";
            }
        } else {
            // No row found for the given token
            // return "No record found for the given token.";
               return "false";
        }
    } else {
        // Query execution failed
        // return "Query failed.";
        return "false";
    }
}


function create_loc_gen_bills_log($due_month, $due_year, $user_id, $status){
    global $con;
    global $currentDateTime, $currentMonth, $currentYear;

    $stmt = $con->prepare("SELECT * FROM loc_gen_bills_log WHERE due_month = ? AND due_year = ? AND status = ?");
    $stmt->bind_param("iii", $due_month, $due_year, $status);
    $stmt->execute();   
    $result = $stmt->get_result();
    $query_data = $result->fetch_all(MYSQLI_ASSOC);
    
    // Check if query returned any rows
    if($due_month !== $currentMonth){ 
        return array(
            "status" => "false",
            "message" => "$due_month - $due_year | You can generate only for Current Month!!!"
        );
    }elseif($due_year < $currentYear || $due_year > $currentYear){
        return array(
            "status" => "false",
            "message" => "$due_month - $due_year | Due Year should not be less than or greater than Current & Year!!!"
        );
    }elseif (count($query_data) > 0) {
        // If rows are returned, process the data
        // foreach ($query_data as $row) {
            // Your code to process each row
            $stmt = $con->prepare("SELECT loc_gen_bill_log_id FROM loc_gen_bills_log 
                                    WHERE due_month = ?
                                    AND due_year = ? AND status = ?");
            $stmt->bind_param("iii", $due_month, $due_year, $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return array(
                "status" => "false",
                "message" => "$due_month - $due_year Already Exists or Not Created...!",
                "loc_gen_bill_log_id" => $row['loc_gen_bill_log_id']
            );
        // }
    } else {
        // If no rows are returned
        $stmt = $con->prepare("INSERT INTO loc_gen_bills_log (created_at, created_user_id, due_month, due_year, status, updated_at, updated_user_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiiisi", $currentDateTime, $user_id, $due_month, $due_year, $status, $currentDateTime, $user_id);
        if($stmt->execute()){

            $stmt = $con->prepare("SELECT loc_gen_bill_log_id FROM loc_gen_bills_log 
                                    WHERE due_month = ?
                                    AND due_year = ? AND status = ?");
            $stmt->bind_param("iii", $due_month, $due_year, $status);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return array(
                "status" => "true",
                "message" => "create_loc_gen_bills_log | Created...!$due_month - $currentMonth && $due_year - $currentYear",
                "loc_gen_bill_log_id" => $row['loc_gen_bill_log_id']
            );
        } else {
            return array(
                "status" => "false",
                "message" => "create_loc_gen_bills_log | Failed to Create...!"
            );
        }

    }    

}

function patch_loc_gen_bills_due_status($loc_gen_bill_id, $channel_uid, $active_status){

    global $con;

    $stmt = $con->prepare("UPDATE loc_gen_bills SET due_status = ? WHERE loc_gen_bill_id = ? AND channel_uid = ? AND status = ?");
    $stmt->bind_param("iisi", $active_status, $loc_gen_bill_id, $channel_uid, $active_status);
    if($stmt->execute()){
        return true;
    }else{
        return false;
    }

}

function generateOTP($length = 4) {
    // Generate a random 5-digit number
    $otp = random_int(1000, 9999); 
    return $otp;
}

function loc_sms_api($phone, $due_month_year, $status, $token = null) {

    global $SMS_GATEWAY_URL, $SMS_API_KEY, $SMS_LOC_SENDER_ID, $SMS_LOC_TEMP_ID, $SMS_LOC_TEMP;

    $dateTime = DateTime::createFromFormat('m-Y', $due_month_year); 
    $formattedDate = $dateTime->format('M-Y');

    // Define the dynamic message
    $due_msg = $formattedDate." is ".$status;

    // Replace placeholders with actual values
    $message = rawurlencode(str_replace(
        ["{#var#}"], // Placeholders to be replaced
        [$due_msg],           // Values to replace with
        $SMS_LOC_TEMP
    ));

    $data = 'apikey=' . $SMS_API_KEY . '&senderid=' . $SMS_LOC_SENDER_ID . '&templateid=' . $SMS_LOC_TEMP_ID . '&number=' . $phone . '&message=' . $message;
    
    // Final URL with query parameters
    $finalUrl = $SMS_GATEWAY_URL . '?' . $data;
    
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

function send_Login_SMS_OTP($phone, $otp){

    global $SMS_GATEWAY_URL, $SMS_API_KEY, $SMS_LOGIN_SENDER_ID, $SMS_LOGIN_TEMP_ID, $SMS_LOGIN_TEMP;

    // $otp = $_SESSION['temp_login_otp'] = generateOTP();
    $message = rawurlencode(str_replace(
        ["{#var1#}"],
        [$otp],
        $SMS_LOGIN_TEMP
    ));

    $data = 'apikey=' . $SMS_API_KEY . '&senderid=' . $SMS_LOGIN_SENDER_ID . '&templateid=' . $SMS_LOGIN_TEMP_ID . '&number=' . $phone . '&message=' . $message;
    
    // Final URL with query parameters
    $finalUrl = $SMS_GATEWAY_URL . '?' . $data;
    
    // Triggering the API using cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $finalUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;

}
function send_INDIV_BILL_SMS($name, $phone, $billNo, $due_month_timestamp, $stbno, $pMode, $bill_status) {
    
    global $con;
    global $SMS_GATEWAY_URL, $SMS_API_KEY, $SMS_INDIV_BILLING_SENDER_ID, $SMS_INDIV_BILLING_TEMP_ID, $SMS_INDIV_BILLING_TEMP;

    $dateTime = new DateTime($due_month_timestamp);
    $formattedDate = $dateTime->format("M-Y");

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

    // Define the dynamic message
    $due_msg = ", due in ".$formattedDate.", has been ".$pMode1;

    // Replace placeholders with actual values
    $message = rawurlencode(str_replace(
        ["{#var1#}", "{#var2#}"], // Placeholders to be replaced
        [$stbno, $due_msg],           // Values to replace with
        $SMS_INDIV_BILLING_TEMP
    ));

    $data = 'apikey=' . $SMS_API_KEY . '&senderid=' . $SMS_INDIV_BILLING_SENDER_ID . '&templateid=' . $SMS_INDIV_BILLING_TEMP_ID . '&number=' . $phone . '&message=' . $message;
    
    // Final URL with query parameters
    $finalUrl = $SMS_GATEWAY_URL . '?' . $data;
    
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


?>

