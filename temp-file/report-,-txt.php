<html>
</body>
<?php

/// Comma Separated file ///

// SQL query to fetch data from the database
$sql =  "SELECT stbno FROM bill WHERE 1=1";

// Check if date filter is provided
if (!empty($from_date) && !empty($to_date)) {
    // Add the date filter to the query
    $query .= " AND date BETWEEN '$from_date' AND '$to_date'";
}

// Check if time filter is provided
if (!empty($from_time) && !empty($to_time)) {
    // Add the time filter to the query
    $query .= " AND time BETWEEN '$from_time' AND '$to_time'";
}
$result = $con->query($sql);

// Check if query execution was successful
if ($result) {
    if ($result->num_rows > 0) {
        // File path to save the data
        $filepath = "data.txt";

        // Open the file in write mode
        $file = fopen($filepath, "w");


        // Write the column headers to the file
        // $headers = array();
        // fwrite($file, implode(",", $headers) . "\n");

        // Fetch each row and write it to the file
        while ($row = $result->fetch_assoc()) {
            // Convert the row values to a comma-separated string
            $data = implode(",", $row);

            // Write the data to the file
            fwrite($file, $data . ",\n");
        }
    }
}

?>

<h1>Download Data</h1>
                                    <p>Click the button below to download the data file:</p>

                                    <a href="data.txt" download>Download File</a>

    </body>
    </html>