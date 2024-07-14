<!-- <style>
    .list-group-item-action:hover {
        background-color: #3771f0;
    }
</style> -->

<?php

require "dbconfig.php";
require "component.php";

if (isset($_POST["query"])) {
    $output = '';
    $term = mysqli_real_escape_string($con, $_POST["query"]);  // Sanitize the input
    $query = "SELECT * FROM customer WHERE rc_dc='1' AND cusGroup = '1' AND CONCAT(stbno, name, phone) LIKE '%$term%' LIMIT 10";
    
    $result = mysqli_query($con, $query);
    if ($result) {  // Check if query execution was successful
        if(mysqli_num_rows($result) > 0) {
            $output .= '<ul class="list-unstyled list-group">';
            while ($row = mysqli_fetch_array($result)) {
                // $msg=fetchIndivPreMonthPaidStatus($row["stbno"]);
                $output .= '<a href="billing-dashboard.php?search='.$row["stbno"].'" class="text-decoration-none text-dark font-weight-bold"><li class="list-group-item list-group-item-action">'.$row["name"].' | '.$row["stbno"].' | '.$row["phone"].' -> '.$row["description"].'</li></a>';
            }
            $output .= '</ul>';
        } else {
            $output .= '<p class="my-2">No Results Found</p>';
        }
        echo $output;
    } else {
        echo "Query execution failed: " . mysqli_error($con);
    }
}
?>
