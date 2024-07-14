<?php
require_once("../../dbconfig.php");
require_once("../../component.php");

// Initialize an array to hold the fetched data
$data = array();

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    
    // SQL query to fetch data
    $stmt = $con->prepare("SELECT phone, name FROM customer WHERE phone LIKE ? LIMIT 10");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("s", $likeQuery);
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
