<?php

function activityLog($userId, $username, $role, $action) {
    
    $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$username', '$role', '$action')";
    mysqli_query($con, $insertSql);
}

?>