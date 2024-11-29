<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array('error' => 'Only POST method is allowed'));
    exit();
}

// Initialize an array to hold the fetched data
$data = array();
$active_status = 1;

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    
    // SQL query to fetch data
    $stmt = $con->prepare("SELECT channel_uid, channel_name FROM loc_channels WHERE channel_name LIKE ? AND status = ? LIMIT 10");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("si", $likeQuery, $active_status);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Fetch data and store in the array
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    } else {
        $data['error'] = "No results found";
    }
    
    $stmt->close();
}

// Close the connection
$con->close();

// Set content type to JSON and return the data
header('Content-Type: application/json');
echo json_encode($data);
?>
