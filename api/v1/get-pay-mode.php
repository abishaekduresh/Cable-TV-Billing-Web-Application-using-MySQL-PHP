<?php
require_once("../../dbconfig.php"); // Ensure this path is correct
require_once("../../component.php");

// Set content type to JSON
header('Content-Type: application/json');

try {
    // Prepare the SQL query to fetch pay modes where status = 1
    $sql = "SELECT pay_mode_id, name FROM pay_mode WHERE status = 1";
    $result = $con->query($sql);

    // Check if results exist
    if ($result->num_rows > 0) {
        $pay_modes = array();

        // Fetch the data and store it in an array
        while ($row = $result->fetch_assoc()) {
            $pay_modes[] = $row;
        }

        // Output the data in JSON format
        echo json_encode($pay_modes);
    } else {
        // Return a message if no data is found
        echo json_encode(array('error' => 'No active payment modes found'));
    }
} catch (Exception $e) {
    // Catch and return any error that occurs
    echo json_encode(array('error' => 'Error fetching data: ' . $e->getMessage()));
}

// Close the database connection
$con->close();
?>
