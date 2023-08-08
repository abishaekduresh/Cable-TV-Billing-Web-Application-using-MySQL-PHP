<?php
session_start();
$session_username = $_SESSION['username']; 
include('dbconfig.php');
require "component.php";

/// SubCategory

if(isset($_POST['save_subcategory']))
{
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);
    $subcategory = mysqli_real_escape_string($con, $_POST['subcategory']);

    $query = "INSERT INTO in_ex_subcategory (category_id,subcategory) 
    VALUES ('$category_id','$subcategory')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        
        $res = [
            'status' => 200,
            'message' => 'subcategory Created Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        
        $res = [
            'status' => 500,
            'message' => 'subcategory Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['update_subcategory']))
{
    $subcategory_id = mysqli_real_escape_string($con, $_POST['subcategory_id']);

    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);
    $subcategory = mysqli_real_escape_string($con, $_POST['subcategory']);


    $query = "UPDATE in_ex_subcategory SET category_id ='$category_id', subcategory='$subcategory' WHERE subcategory_id='$subcategory_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'subcategory Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'subcategory Not Updated'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_GET['subcategory_id']))
{
    $subcategory_id = mysqli_real_escape_string($con, $_GET['subcategory_id']);

    $query = "SELECT * FROM in_ex_subcategory WHERE subcategory_id='$subcategory_id'";
    $query_run = mysqli_query($con, $query);

    if(mysqli_num_rows($query_run) == 1)
    {
        $subcategory = mysqli_fetch_array($query_run);

        $res = [
            'status' => 200,
            'message' => 'subcategory Fetch Successfully by id',
            'data' => $subcategory
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 404,
            'message' => 'subcategory Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}


if(isset($_POST['delete_subcategory']))
{
    $subcategory_id = mysqli_real_escape_string($con, $_POST['subcategory_id']);

    $query = "DELETE FROM in_ex_subcategory WHERE subcategory_id='$subcategory_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'subcategory Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'subcategory Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}
?>
