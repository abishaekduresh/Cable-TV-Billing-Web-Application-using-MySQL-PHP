<?php

require_once("../../../dbconfig.php");
require_once("../../../component.php");

// Initialize an array to hold the fetched data
$data = array();

if (isset($_POST['query'])) {
    $query = $_POST['query'];
    
    // SQL query to fetch data
    $stmt = $con->prepare("SELECT pos_product_id, product_name FROM pos_product WHERE product_name LIKE ?");
    
    if (!$stmt) {
        $data['error'] = "Prepare failed: (" . $con->errno . ") " . $con->error;
    } else {
        $likeQuery = "%" . $query . "%";
        $stmt->bind_param("s", $likeQuery);
        if (!$stmt->execute()) {
            $data['error'] = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Fetch data and store in the array
                while($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            } else {
                $data['error'] = "No results found";
            }
        }
        $stmt->close();
    }
}

// Close the connection
$con->close();

// Set content type to JSON and return the data
header('Content-Type: application/json');
echo json_encode($data);


?>