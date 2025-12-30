<?php
include "dbconfig.php";

if(isset($_POST['billNo']) && isset($_POST['group_id'])) {
    $billNo = mysqli_real_escape_string($con, $_POST['billNo']);
    $group_id = mysqli_real_escape_string($con, $_POST['group_id']);
    
    // Optional: Use date as well if billNo is not unique globally (though usually it resets or increments)
    // Based on previous code, billNo might restart, so allow filtering by date too if passed, but for now lets trust billNo + group_id helps.
    // Actually, looking at billing-group-dashboard.php: $billNo is calculated per month/year.
    // So we definitely need the Date (Year/Month) or distinct ID.
    // The previous output shows I have access to 'date' in the main report. passing it is safer.
    
    // If transaction_id is available, use it as the primary key for fetching
    if(isset($_POST['transaction_id']) && !empty($_POST['transaction_id']) && $_POST['transaction_id'] != 'undefined') {
        $transaction_id = mysqli_real_escape_string($con, $_POST['transaction_id']);
        $query = "SELECT * FROM billgroup WHERE transaction_id = '$transaction_id'";
    } else {
        // Fallback for old records without transaction_id
        $dateCondition = "";
        if(isset($_POST['date'])) {
            $date = mysqli_real_escape_string($con, $_POST['date']);
            $dateCondition = "AND date = '$date'";
        }
        $query = "SELECT * FROM billgroup WHERE billNo = '$billNo' AND group_id = '$group_id' $dateCondition";
    }
    
    $result = mysqli_query($con, $query);

    if(mysqli_num_rows($result) > 0) {
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead><tr><th>#</th><th>STB No</th><th>Name</th><th>MSO</th><th>Remark</th><th>Status</th></tr></thead>';
        echo '<tbody>';
        $sl = 1;
        while($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . $sl++ . '</td>';
            echo '<td>' . $row['stbNo'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['mso'] . '</td>';
            echo '<td>' . $row['remark'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '</tr>';
        }
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
    } else {
        echo '<p class="text-center text-muted">No items found for this group bill.</p>';
    }
}
?>
