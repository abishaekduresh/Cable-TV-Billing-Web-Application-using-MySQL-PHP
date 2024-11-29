<?php
require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Allow only GET method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Only GET requests are allowed']);
    exit;
}

// Initialize an array to hold the fetched data
$data = array();

// if (isset($_GET['query'])) {
//     $query = $_GET['query'];
    
    // SQL query to fetch data
    $stmt = $con->prepare("SELECT * FROM loc_channels");
    // $likeQuery = "%" . $query . "%";
    // $stmt->bind_param("s", $likeQuery);
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
// }

// Close the connection
$con->close();

// Set content type to JSON and return the data
header('Content-Type: application/json');
echo json_encode($data);
?>
