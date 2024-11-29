<?php
require_once("dbconfig.php");
require_once("component.php");
require_once("component2.php");
// echo $s = generateChannelUID();
// $channel_uid = "LOC03";
// $active_status = 1;
// echo $res = send_INDIV_BILL_SMS("Duresh", 7708443543, "555", "OCT-2024", "00008317", "Cash", "approve");
// echo $res = sms_api("Duresh", 7708443543, "555", "OCT-2024", "00008317", "cash", "approve");
// echo 'Hello';

echo $res = fetchIndivPreMonthPaidStatus('00008317000A415C');

// SQL query to fetch data from both tables with placeholders for the parameters
// $sql = "SELECT b.loc_gen_bill_id, b.created_at AS bill_created_at, b.due_amount, b.due_status, b.remark, 
//                b.status AS bill_status, b.updated_at AS bill_updated_at, 
//                l.loc_gen_bill_log_id, l.created_at AS log_created_at, l.due_month, l.due_year, 
//                l.status AS log_status, l.updated_at AS log_updated_at
//         FROM loc_gen_bills b
//         INNER JOIN loc_gen_bills_log l ON b.loc_gen_bill_log_id = l.loc_gen_bill_log_id
//         WHERE b.channel_uid = ? AND b.status = ? AND l.status = ?";

// // Prepare the query
// $stmt = $con->prepare($sql);

// // Bind the parameters: channel_uid (string), bill status (int), log status (int)
// $stmt->bind_param("sii", $channel_uid, $active_status, $active_status);

// // Execute the query
// $stmt->execute();

// // Get the result set
// $result = $stmt->get_result();  

// // Display the data
// while ($row = $result->fetch_assoc()) {
//     echo "Bill ID: " . $row['loc_gen_bill_id'] . "<br>";
//     echo "Bill Created At: " . $row['bill_created_at'] . "<br>";
//     echo "Due Amount: " . $row['due_amount'] . "<br>";
//     echo "Due Status: " . $row['due_status'] . "<br>";
//     echo "Remark: " . $row['remark'] . "<br>";
//     echo "Bill Status: " . $row['bill_status'] . "<br>";
//     echo "Bill Updated At: " . $row['bill_updated_at'] . "<br><br>";

//     echo "Log ID: " . $row['loc_gen_bill_log_id'] . "<br>";
//     echo "Log Created At: " . $row['log_created_at'] . "<br>";
//     echo "Due Month: " . $row['due_month'] . "<br>";
//     echo "Due Year: " . $row['due_year'] . "<br>";
//     echo "Log Status: " . $row['log_status'] . "<br>";
//     echo "Log Updated At: " . $row['log_updated_at'] . "<br>";
//     echo "-----------------------------------------<br>";
// }

// $stmt = $con->prepare("SELECT * FROM loc_gen_bills WHERE loc_gen_bill_id = ? AND channel_uid = ? AND status = ?");
// $stmt->bind_param("isi", $data['loc_gen_bill_id'], $data['channel_uid'], $active_status);
// $loc_gen_bill_id=1;
// try {
//     // Prepare the SQL statement
//     $stmt = $con->prepare("SELECT * FROM loc_gen_bills WHERE loc_gen_bill_id = ? AND channel_uid = ?");
    
//     if (!$stmt) {
//         throw new Exception("Prepare statement failed: " . $con->error);
//     }

//     // Bind parameters
//     $stmt->bind_param("ss", $loc_gen_bill_id, $channel_uid);

//     // Execute the statement
//     if (!$stmt->execute()) {
//         throw new Exception("Execution failed: " . $stmt->error);
//     }

//     // Fetch the result
//     $result = $stmt->get_result();
//     if (!$result) {
//         throw new Exception("Fetching result failed: " . $stmt->error);
//     }

//     $row = $result->fetch_assoc();

//     // Prepare the response if no status or status is "0"
//     $response = array(
//         "status" => "failed",
//         "message" => "loc_gen_bill_id status is Zero or not found!!!",
//         "data" => $row
//     );
//     echo json_encode($response);
//     exit();
// } catch (Exception $e) {
//     // Handle any errors here
//     $errorResponse = array(
//         "status" => "error",
//         "message" => "An error occurred: " . $e->getMessage()
//     );
//     echo json_encode($errorResponse);
//     exit();
// }

// echo $patch_loc_gen_bills = patch_loc_gen_bills("1","LOC01","1");
// echo $ff = loc_sms_api("7708443543", "2024-09", "2");
// // Close the statement and the connection
// $stmt->close();
// $con->close();
// echo loc_sms_api("7708443543", "kjbkjbk");;
// echo $currentDate;

// // SQL query to sum prices from pos_bill_items
// $sqlSum = "SELECT SUM(pbi.price * pbi.qty) - pb.discount AS total_price
//            FROM pos_bill pb
//            JOIN pos_bill_items pbi ON pb.pos_bill_id = pbi.pos_bill_id
//            WHERE DATE(pb.entry_timestamp) = ? 
//            AND pb.status = ?";

// // Prepare the statement
// $stmt = $con->prepare($sqlSum);

// // Assuming $currentDate is in 'Y-m-d' format and status is active (1)
// $active_status = "1";
// $stmt->bind_param("ss", $currentDate, $active_status); 
// $stmt->execute();
// $result = $stmt->get_result();

// // Fetch the total price from the result
// $row = $result->fetch_assoc();
// $total_price = $row['total_price'];
// //$total_price -= $row['discount'];

// echo "Total Price: " . $total_price;

    
// 	//Check if a record exists in in_ex table
// 	$sqlCheck = "SELECT id FROM in_ex WHERE date = '$currentDate' AND category_id = 16 AND subcategory_id = 57 AND status = '1'";
// 	$resultCheck = $con->query($sqlCheck);

// 	if ($resultCheck->num_rows > 0) {
// 		// Update existing record
// 		$sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '16', subcategory_id = '57', remark='', amount = $total_price WHERE date = '$currentDate' AND category_id = 16 AND subcategory_id = 57 AND status ='1'";
// 		$con->query($sqlUpdate);
// 	} else {
// 		// Insert new record
// 		$sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount,status) VALUES ('Income', '$currentDate', '$currentTime','Auto', '16', '57','', '$total_price','1')";
// 		$con->query($sqlInsert);
// 	}

?>
