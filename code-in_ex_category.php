<?php
session_start();
$session_username = $_SESSION['username']; 
include('dbconfig.php');
require "component.php";

if(isset($_POST['save_category']))
{
    $category = mysqli_real_escape_string($con, $_POST['category']);
    $in_ex = mysqli_real_escape_string($con, $_POST['in_ex']);

    $query = "INSERT INTO in_ex_category (createdBy,category,in_ex,status) 
    VALUES ('$session_username','$category','$in_ex','1')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        
        $res = [
            'status' => 200,
            'message' => 'category Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        
        $res = [
            'status' => 500,
            'message' => 'category Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_category']))
{
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);

    $category = mysqli_real_escape_string($con, $_POST['category']);
    $in_ex = mysqli_real_escape_string($con, $_POST['in_ex']);

    $query = "UPDATE in_ex_category SET category='$category', in_ex='$in_ex'
                WHERE category_id='$category_id',status='1'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'category Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'category Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['category_id']))
{
    $category_id = mysqli_real_escape_string($con, $_GET['category_id']);

    $query = "SELECT * FROM in_ex_category WHERE category_id='$category_id' AND status = '1'";
    $query_run = mysqli_query($con, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $category = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'category Fetch Successfully by id',
            'data' => $category
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'category Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['delete_category']))
{
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);

    $query = "DELETE FROM in_ex_category WHERE category_id='$category_id' AND status = '1'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'category Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'category Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}

?>
