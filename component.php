<?php

function logUserActivity($userId, $username, $role, $action) {

    include 'dbconfig.php';
    // Insert user Bill Excel downloaded
    $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$username', '$role', '$action')";
    mysqli_query($con, $insertSql);
}

function fetchGroupName($groupid) {
    include 'dbconfig.php';

    $query = "SELECT groupName FROM groupinfo WHERE group_id != '2' AND group_id='$groupid'";
    $result=mysqli_query($con, $query);

    while ($row = $result->fetch_assoc()) {
        echo $row['groupName'];
    }
}

function formatDate($formatdate) {
    include 'dbconfig.php';

    echo date("d-m-Y", strtotime($formatdate));
}


?>