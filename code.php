
        
<?php
session_start();
include('dbconfig.php');
require "component.php";

require 'vendor/autoload.php';

$session_username = $_SESSION['username'];
$session_id = $_SESSION['id'];

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;    //Export Data
use PhpOffice\PhpSpreadsheet\Writer\Csv;

if(isset($_POST['save_excel_data']))
{
    $fileName = $_FILES['import_file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];

    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['import_file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $count = 0;
        foreach($data as $row)
        {
            if($count > 0)
            {
                // $stbno = $row['0'];
                $stbno = mysqli_real_escape_string($con, $row['0']);
                $name = $row['1'];
                $phone = $row['2'];
                $description = $row['3'];
                $amount = $row['4'];
                                                                
                $customerQuery = "INSERT INTO customer (stbno,name,phone,description,amount) VALUES ('$stbno','$name','$phone','$description','$amount')";
                $result = mysqli_query($con, $customerQuery);
                $msg = true;
            }
            else
            {
                $count = 1;
            }
        }

        if(isset($msg))
        {
            $_SESSION['message'] = "Successfully Imported";
            header('Location: import-customer.php');
            exit(0);
        }
        else
        {
            $_SESSION['message'] = "Not Imported";
            header('Location: import-customer.php');
            exit(0);
        }
    }
    else
    {
        $_SESSION['message'] = "Invalid File";
        header('Location: import-customer.php');
        exit(0);
    }
}

///Export Data  //// Export All Customer in Customer Table /////

if(isset($_POST['export_excel_btn']))
{
    $file_ext_name = $_POST['export_file_type'];
    $fileName = "customer-sheet";

    $customer = "SELECT * FROM customer";
    $query_run = mysqli_query($con, $customer);

    if(mysqli_num_rows($query_run) > 0)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'STB No');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Phone');
        $sheet->setCellValue('D1', 'Description');
        $sheet->setCellValue('E1', 'Amount');

        $rowCount = 2;
        foreach($query_run as $data)
        {
            $sheet->setCellValue('A'.$rowCount, $data['stbno']);
            $sheet->setCellValue('B'.$rowCount, $data['name']);
            $sheet->setCellValue('C'.$rowCount, $data['phone']);
            $sheet->setCellValue('D'.$rowCount, $data['description']);
            $sheet->setCellValue('E'.$rowCount, $data['amount']);
            $rowCount++;
        }

        if($file_ext_name == 'xlsx')
        {
            $writer = new Xlsx($spreadsheet);
            $final_filename = $fileName.'.xlsx';
        }
        elseif($file_ext_name == 'xls')
        {
            $writer = new Xls($spreadsheet);
            $final_filename = $fileName.'.xls';
        }
        elseif($file_ext_name == 'csv')
        {
            $writer = new Csv($spreadsheet);
            $final_filename = $fileName.'.csv';
        }
        // $writer->save($final_filename);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attactment; filename="'.urlencode($final_filename).'"');
        $writer->save('php://output');

    }
    else
    {
        $_SESSION['message'] = "No Record Found";
        header('Location: index.php');
        exit(0);
    }
}



/// Customer CRUD Operation

if(isset($_POST['delete_customer']))
{
    $customer_id = mysqli_real_escape_string($con, $_POST['delete_customer']);

    $query = "DELETE FROM customer WHERE id='$customer_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = " வாடிக்கையாளர் நீக்கப்பட்டது !!!";
        
        
        header("Location: customer-details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = " வாடிக்கையாளர் நீக்கப்படவில்லை !!! ";
        header("Location: customer-details.php");
        exit(0);
    }
}

if(isset($_POST['update_customer']))
{
    $customer_id = mysqli_real_escape_string($con, $_POST['customer_id']);

    $cusGroup = mysqli_real_escape_string($con, $_POST['cusGroup']);
    $rc_dc = mysqli_real_escape_string($con, $_POST['rc_dc']);
    $mso = mysqli_real_escape_string($con, $_POST['mso']);
    $stbno = mysqli_real_escape_string($con, $_POST['stbno']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);

    $query = "UPDATE customer SET cusGroup='$cusGroup', mso='$mso', stbno='$stbno', name='$name', phone='$phone', description='$description', amount='$amount', rc_dc='$rc_dc' WHERE id='$customer_id' ";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $_SESSION['message'] = "customer Updated Successfully";
        
                if (isset($_SESSION['id'])) {
                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $userName = $_SESSION['username'];
                $role = $_SESSION['role'];
                $currentDate = $currentDate;
                $currentTime = $currentTime;
                $action = "Updated Customer - $stbno";
            
                // Insert user logout activity
                $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$userName', '$role', '$action')";
                mysqli_query($con, $insertSql);
                }
        
        header("Location: customer-details.php");
        exit(0);
    }
    else
    {
        $_SESSION['message'] = "customer Not Updated";
        
                        if (isset($_SESSION['id'])) {
                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $userName = $_SESSION['username'];
                $role = $_SESSION['role'];
                $currentDate = $currentDate;
                $currentTime = $currentTime;
                $action = "Updated Customer Faild - $stbno";
            
                // Insert user logout activity
                $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$userName', '$role', '$action')";
                mysqli_query($con, $insertSql);
                }
                
        header("Location: customer-details.php");
        exit(0);
    }

}

///detsils
////////////////////Add Customer///// Ajax/////////////////////////
// if(isset($_POST['save_student']))
// {


//     if($stbno == NULL || $name == NULL)
//     {
//         $res = [
//             'status' => 422,
//             'message' => 'All fields are mandatory'
//         ];
//         echo json_encode($res);
//         return;
//     }
// // date, time, '$currentDate', '$currentTime', 
//     $query = "INSERT INTO customer () VALUES ('$cusGroup', '$mso', '$stbno', '$name', '$phone', '$description', '$amount')";
//     $query_run = mysqli_query($con, $query);

//     if($query_run)
//     {
//         $res = [
//             'status' => 200,
//             'message' => 'Student Created Successfully'
//         ];
//         echo json_encode($res);
//         return;
//     }
//     else
//     {
//         $res = [
//             'status' => 500,
//             'message' => 'Student Not Created'
//         ];
//         echo json_encode($res);
//         return;
//     }
// }


if(isset($_POST['save_student']))
{
    $cusGroup = mysqli_real_escape_string($con, $_POST['groupName']);
    $mso = mysqli_real_escape_string($con, $_POST['mso']);
    $stbno = mysqli_real_escape_string($con, $_POST['stbno']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);

    // if($stbno == NULL || $phone == NULL)
    // {
    //     $res = [
    //         'status' => 422,
    //         'message' => 'All fields are mandatory'
    //     ];
    //     echo json_encode($res);
    //     return;
    // }

    $query = "INSERT INTO customer (date, time, cusGroup, mso, stbno, name, phone, description, amount, rc_dc) VALUES ('$currentDate', '$currentTime', '$cusGroup', '$mso', '$stbno', '$name', '$phone', '$description', '$amount', '1')";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        
                // Activity Log
                if (isset($_SESSION['id'])) {
                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $role = $_SESSION['role'];
                $action = "Customer Created Successfully - $stbno";
            
                // Insert user logout activity
                $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$session_username', '$role', '$action')";
                mysqli_query($con, $insertSql);
                }
        
        $res = [
            'status' => 200,
            'message' => 'Customer Created Successfully'
        ];
        echo json_encode($res);
        return;

    }
    else
    {
        
                // Activity Log
                if (isset($_SESSION['id'])) {
                // Get the user information before destroying the session
                $userId = $_SESSION['id'];
                $role = $_SESSION['role'];
                $action = "Customer Creation Failed - $stbno";
            
                // Insert user logout activity
                $insertSql = "INSERT INTO user_activity (userId, date, time, userName, role, action) VALUES ('$userId', '$currentDate', '$currentTime', '$session_username', '$role', '$action')";
                mysqli_query($con, $insertSql);
                }
        
        $res = [
            'status' => 500,
            'message' => 'Customer Not Created'
        ];
        echo json_encode($res);
        return;
    }
}


////////////////////Edit Customer///// Ajax/////////////////////////

if (isset($_GET['student_id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['student_id']);

    $query = "SELECT * FROM customer WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if (mysqli_num_rows($query_run) == 1) {
        $student = mysqli_fetch_assoc($query_run);

        $res = [
            'status' => 200,
            'message' => 'Customer Fetch Successfully by id',
            'data' => $student
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 404,
            'message' => 'Customer Id Not Found'
        ];
        echo json_encode($res);
        return;
    }
}


////////////////////Update Customer///// Ajax///////////////////////// OK

if(isset($_POST['update_student']))
{
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $cusGroup = mysqli_real_escape_string($con, $_POST['cusGroup']);
    $rc_dc = mysqli_real_escape_string($con, $_POST['rc_dc']);
    $mso = mysqli_real_escape_string($con, $_POST['mso']);
    $stbno = mysqli_real_escape_string($con, $_POST['stbno']);
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $amount = mysqli_real_escape_string($con, $_POST['amount']);

    if($stbno == NULL || $name == NULL )//|| $phone == NULL || $description == NULL || $amount == NULL
    {
        $res = [
            'status' => 422,
            'message' => 'All fields are mandatory'
        ];
        echo json_encode($res);
        return;
    }
 
    $query = "UPDATE customer SET cusGroup='$cusGroup', mso='$mso', stbno='$stbno', name='$name', phone='$phone', description='$description', amount='$amount', rc_dc='$rc_dc' WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if($query_run)
    {
        $res = [
            'status' => 200,
            'message' => 'Customer Updated Successfully'
        ];
        echo json_encode($res);
        return;
    }
    else
    {
        $res = [
            'status' => 500,
            'message' => 'Customer Not last Updated'
        ];
        echo json_encode($res);
        return;
    }
}

////////////////////Delete Customer///// Ajax/////////////////////////

if (isset($_POST['delete_student'])) {
    $student_id = mysqli_real_escape_string($con, $_POST['student_id']);

    $query = "DELETE FROM customer WHERE id='$student_id'";
    $query_run = mysqli_query($con, $query);

    if ($query_run) {
        $res = [
            'status' => 200,
            'message' => 'Student Deleted Successfully'
        ];
        echo json_encode($res);
        return;
    } else {
        $res = [
            'status' => 500,
            'message' => 'Student Not Deleted'
        ];
        echo json_encode($res);
        return;
    }
}


////////////////////Add Product///// Ajax/////////////////////////

// if(isset($_POST['save_product']))
// {
    
//     $name = mysqli_real_escape_string($con, $_POST['name']);
//     $amount = mysqli_real_escape_string($con, $_POST['amount']);

//     if($stbno == NULL || $name == NULL)
//     {
//         $res = [
//             'status' => 422,
//             'message' => 'All fields are mandatory'
//         ];
//         echo json_encode($res);
//         return;
//     }

//     $query = "INSERT INTO customer (name, amount) VALUES ('$name', '$amount')";
//     $result = $con->query($query);

//     if($result)
//     {
//         $res = [
//             'status' => 200,
//             'message' => 'Product Created Successfully'
//         ];
//         echo json_encode($res);
//         return;
//     }
//     else
//     {
//         $res = [
//             'status' => 500,
//             'message' => 'Product Not Created'
//         ];
//         echo json_encode($res);
//         return;
//     }
// }


?>