<?php
session_start();
require "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    

    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
        
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        ?><br><?php
        include 'sub-menu-btn.php';
        $session_username = $_SESSION['username'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve checkbox values
        $checkboxValues = isset($_POST["options"]) ? $_POST["options"] : [];

        // Process selected checkboxes
        foreach ($checkboxValues as $customerId) {
            // Retrieve form data
            
                $stbno = mysqli_real_escape_string($con, $_POST["stbno"][$customerId]);
                $mso = $_POST["mso"][$customerId];
                $name = $_POST["name"][$customerId];
                $phone = $_POST["phone"][$customerId];
                $description = $_POST["description"][$customerId];
                $pMode = $_POST["pMode"][$customerId];
                $oldMonthBal = $_POST["oldMonthBal"][$customerId];
                $paid_amount = $_POST["paid_amount"][$customerId];
                $discount = $_POST["discount"][$customerId];
                $due_month_date = $_POST["due_month_date"][$customerId];
                

                $due_month_timestamp = $due_month_date . " " . $currentTime;

            function insertBill(){

                require "dbconfig.php";

                global $discount;
                global $paid_amount;
                global $oldMonthBal;
                global $mso;
                global $stbno;
                global $name;
                global $phone;
                global $description;
                global $pMode;
                global $oldMonthBal;
                global $paid_amount;
                global $due_month_timestamp;
                global $session_username;

                $bill_status = 'approve';

                if ($discount > 0) {
                    $discount = $discount;
                } else {
                    $discount = 0;
                }            
                
                $Rs = $paid_amount - $discount;
                
                
                if ($oldMonthBal > 0) {
                    $oldMonthBal = $oldMonthBal;
                } else {
                    $oldMonthBal = 0;
                }
                
                $Rs = $Rs + $oldMonthBal;

                // https://chat.openai.com/share/6d0e9e8b-5d51-4f0c-a59c-3ee2ee77c382
                if ($currentDay === '01') {
                    // Check if there is any bill entry for the next day
                    $checkNextDayQuery = "SELECT billNo FROM bill WHERE DATE(date) = DATE_ADD('$currentDate', INTERVAL 1 DAY) LIMIT 1";
                    $result = $con->query($checkNextDayQuery);
                
                    if ($result->num_rows > 0) {
                        // There is already a bill entry for the next day, so set billNo to 1
                        $billNo = 1;
                    } else {
                        // Retrieve the maximum billNo for the current day
                        $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
                        $result = $con->query($getMaxBillNoQuery);
                
                        if ($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $maxBillNo = $row["maxBillNo"];
                            if ($maxBillNo < 1) {
                                $billNo = 1;
                            } else {
                                $billNo = $maxBillNo + 1;
                            }
                        } else {
                            $billNo = 1;
                        }
                    }
                } else {
                    // Retrieve the next billNo for the current day
                    $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM bill WHERE DATE(date) = DATE('$currentDate')";
                    $result = $con->query($getBillNoQuery);
                
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $billNo = $row["maxBillNo"] + 1;
                    } else {
                        $billNo = 1;
                    }
                }
                
                $printStatus = 0;

                    // Prepare the SQL statement
                    $sql = "INSERT INTO bill (billNo, date, time, bill_by, mso, stbno, name, phone, description, pMode, oldMonthBal, paid_amount, discount, Rs, adv_status, due_month_timestamp, status, printStatus) 
                    VALUES ('$billNo', '$currentDate', '$currentTime','$session_username', '$mso', '$stbno', '$name', '$phone', '$description', '$pMode', '$oldMonthBal', '$paid_amount', '$discount', '$Rs', 1, '$due_month_timestamp', '$bill_status', '$printStatus')";

                        
                    // Execute the SQL statement
                    if ($con->query($sql) === TRUE) {
                        // Data inserted successfully
                    //  $q= "SELECT date,sum(paid_amount) TotAmt FROM `bill` where date='2023-06-01' group by date";
                    
                        // Calculate sum of paid_amount for the current date
                        $sqlSum = "SELECT SUM(Rs) AS total_Rs FROM bill WHERE date = '$currentDate' AND status = 'approve'";
                        $result = $con->query($sqlSum);
                        $row = $result->fetch_assoc();
                        $sumPaidAmount = $row["total_Rs"];

                        // Check if a record exists in in_ex table
                        $sqlCheck = "SELECT * FROM in_ex WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                        $resultCheck = $con->query($sqlCheck);

                        if ($resultCheck->num_rows > 0) {
                            // Update existing record
                            $sqlUpdate = "UPDATE in_ex SET type='Income', date='$currentDate', time = '$currentTime',username='Auto',category_id = '12', subcategory_id = '35', remark='', amount = $sumPaidAmount WHERE date = '$currentDate' AND category_id = 12 AND subcategory_id = 35";
                            $con->query($sqlUpdate);
                        } else {
                            // Insert new record
                            $sqlInsert = "INSERT INTO in_ex (type, date, time,username, category_id, subcategory_id,remark, amount) VALUES ('Income', '$currentDate', '$currentTime','Auto', '12', '35','', $sumPaidAmount)";
                            $con->query($sqlInsert);
                        }

                        // continue;

                        if (isset($_SESSION['id'])) {
                            // Get the user information before destroying the session
                            $userId = $_SESSION['id'];
                            $username = $_SESSION['username'];
                            $role = $_SESSION['role'];
                            $action = "Advance Bill Successful - $pMode - $stbno";
                        
                            // Call the function to insert user activity log
                            logUserActivity($userId, $username, $role, $action);
                        }
                        
                        // echo "<script>alert('Bill Successful $name')</script>";
                        echo "<script>
                        function myFunction(stbno) {
                        let text = 'Are Want to print ?';
                        if (confirm(text)) {
                            window.open('prtindivadvbill.php?stbnumber=' + stbno);
                        } else {
                            
                        }
                        }
                        </script>";

                        // Call the JavaScript function with the PHP variable
                        echo "<script>myFunction('$stbno');</script>";


                    } else {
                        echo "Error inserting data: " . $con->error;
                        if (isset($_SESSION['id'])) {
                            // Get the user information before destroying the session
                            $userId = $_SESSION['id'];
                            $username = $_SESSION['username'];
                            $role = $_SESSION['role'];
                            $action = "Advance Bill Failed - $pMode - $stbno";
                        
                            logUserActivity($userId, $username, $role, $action);
                        }

                    }
                }

                $DD = date('d', strtotime($due_month_date));
                $MM = date('m', strtotime($due_month_date));
                $YY = date('Y', strtotime($due_month_date));

                if ($currentDate <= $due_month_date) {

                    $sql1 = "SELECT stbno FROM bill WHERE stbno = '$stbno' AND MONTH(due_month_timestamp)=$MM AND YEAR(due_month_timestamp)=$YY LIMIT 1";

                    $run1 = $con->query($sql1);

                    if ($run1) {

                        $row = $run1->fetch_assoc();
                        $run_result1 = $row['stbno'];
                    } else {
                        $run_result1 = 0;
                    }

                    // Check if the query was successful
                    if (isset($run_result1) == $stbno) {

                        echo "<script>alert('Approved Bill Already Exists on Due Month Date !!!');</script>";

                    } else {

                        $due_date_sql = "SELECT billNo FROM bill WHERE stbno = '$stbno' 
                                            AND ((MONTH(due_month_timestamp) >= $MM AND YEAR(due_month_timestamp) >= $YY)
                                                OR (MONTH(due_month_timestamp) <= $MM AND YEAR(due_month_timestamp) >= $YY))
                                            AND adv_status = 1 AND status = 'approve'";

                        $due_date_result = $con->query($due_date_sql);

                        if ($due_date_result) {

                            insertBill();


                        } else {  

                            echo "<script>alert('Due Month Approved Bill already Exists for " . $name . "');</script>";

                            function redirect($url)
                            {
                                echo "<script>
                                setTimeout(function(){
                                    window.location.href = '$url';
                                }, 200);
                            </script>";
                            }
                    
                            // Usage example
                            $url = "adv-indiv-billing-dashboard.php"; // Replace with your desired URL bill-print-bulk.php
                            redirect($url);
                    

                        }  
                    }



                } else {
                    
                    echo "<script>alert('Incorrect Due Month Date \\nCheck Due Month Date !!!');</script>";
          

                }
            }

    }

        // Redirect after processing
        ?>
        <!--<center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="100px" height="100px"></center>-->
        <?php

        // // Redirect function
        // function redirect($url)
        // {
        //     echo "<script>
        //     setTimeout(function(){
        //         window.location.href = '$url';
        //     }, 200);
        // </script>";
        // }

        // // Usage example
        // $url = "prtadvindivdash.php"; // Replace with your desired URL bill-print-bulk.php
        // redirect($url);
    // }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advance Individual Billing Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>   -->
           <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>  


<style>
    .custom-container {
        max-width: 90%;
    }
    
/* Define the styles for odd and even rows */
.creditBill {
    background-color: yellow; /* Light gray for odd rows */
}

.oldMonthPending {
    background-color: red; /* Slightly darker gray for even rows */
}


.list-group-item-action:hover {
    background-color: #023199;
    color: white; /* Add this line to change font color on hover */
}
</style>
</head>
<body>
    
    <!--<hr class="mt-0 mb-4">-->

    <div class="container custom-container">
        <div class="row" style="width: 100%;">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Advance Individual Billing Dashboard
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <form action="" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" id="search" autocomplete="off" pattern="[A-Za-z0-9\s]{3,}" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Enter Minimum 3 Character of STB No, Name, Phone" >                                      
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                                <div id="searchList"></div> 
                            </div>
                        </div>
                    </div>      

                </div><br/>
            </div>

            <div class="col-md-12">
                <div class="card mt-12">
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="table-responsive">
                                <table class="table table-hover" border="5">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th></th>
                                            <!-- <th>MSO</th> -->
                                            <th>STB No</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Remarks</th>
                                            <th>P.Mode</th>
                                            <th>OldBal</th>
                                            <th>BillAmt</th>
                                            <th>Disct</th>
                                            <th>Due Month</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['search'])) {
                                            $filtervalues = $_GET['search'];
        
                                            $query = "SELECT * FROM customer 
                                            WHERE CONCAT(stbno,name,phone) LIKE '%$filtervalues%' AND rc_dc='1' AND cusGroup = '1' LIMIT 1 ";
    
                                            $query_run = mysqli_query($con, $query);
    
                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1;
                                                foreach ($query_run as $customer) {
    
                                                    $stbno = mysqli_real_escape_string($con, $customer['stbno']);
    
                                                    $nestedQuery = "SELECT * FROM bill 
                                                    WHERE stbno = '$stbno'  AND status = 'approve'
                                                    AND MONTH(`date`) = '$currentMonth'
                                                    AND YEAR(`date`) = '$currentYear'";
    
                                                    $nestedQuery_run = mysqli_query($con, $nestedQuery);
    
                                                    $disableButton = (mysqli_num_rows($nestedQuery_run) > 0) ? true : false;
                                                    
                                                    $nestedQuery2 = "SELECT pMode FROM bill WHERE stbno = '$stbno' AND pMode = 'credit' AND status = 'approve'";
                                                    
                                                    $nestedQuery2_run = mysqli_query($con, $nestedQuery2);
                                                    
                                                    $disableButton2 = (mysqli_num_rows($nestedQuery2_run) > 0) ? true : false;
                                                    
                                                    if ($currentDay <= 05) {
                                                        $discountValue = 10; // Set discount to 10 if current day is less than 5
                                                    } else {
                                                            $discountValue = 0;
                                                    }

                                                    ?>
                                                    
                                                    <?php if ($disableButton2): ?>
                                                    <tr class="creditBill">
                                                    <?php else: ?>
                                                    <tr>
                                                    <?php endif; ?>
                                                        
                                                        <td style="font-weight: bold; font-size: 16px;"><?= $serial_number++; ?></td>
                                                        <td>
                                                            <?php //if (!$disableButton): ?>
                                                                <div class="form-check">
                                                                    <input type="checkbox" id="myCheckbox" name="options[]" value="<?= $customer['id']; ?>" class="form-check-input">
                                                                </div>
                                                            <?php //else: ?>
                                                                    <!--<a href="customer-history.php?search=<?= $customer['stbno']; ?>" target="_blank">-->
                                                                    <!--<img src="assets/arrow-up-right-from-square-solid.svg" width="20px" height="20px">-->
                                                                    <!--</a>-->
                                                            <?php //endif; ?>
                                                        </td>
                                                        <td style="width: 160px; font-weight: bold; display: none;">
                                                                <input readonly class="form-control fw-bold" type="text" name="mso[<?= $customer['id']; ?>]" value="<?= $customer['mso']; ?>" style="width: 70px;">
                                                        </td>
                                                        <td >
                                                                <input readonly class="form-control fw-bold" type="text" name="stbno[<?= $customer['id']; ?>]" value="<?= $customer['stbno']; ?>" style="width: 180px;">
                                                        </td>
                                                        
                                                        <td style="width: 350px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="name[<?= $customer['id']; ?>]" value="<?= $customer['name']; ?>"  style="width: 300px;">
                                                        </td>
                                                        <td style="width: 110px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="phone[<?= $customer['id']; ?>]" value="<?= $customer['phone']; ?>" style="width: 130px;">
                                                        </td>
                                                        <td style="width: 180px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="description[<?= $customer['id']; ?>]" value="<?= $customer['description']; ?> " style="width: 180px;">
                                                        </td>
                                                        <td>
                                                                <select name="pMode[<?= $customer['id']; ?>]" class="form-select fw-bold" style="width: 100px; height: 40px;">
                                                                    <option value="cash" selected class="fw-bold">Cash</option>
                                                                    <option value="gpay" class="fw-bold">G Pay</option>
                                                                    <!-- <option value="credit" class="fw-bold">Credit</option> -->
                                                                </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="oldMonthBal[<?= $customer['id']; ?>]" value="0" class="form-control fw-bold" style="width: 60px; color: #0012C3;">
                                                        </td>
                                                        <td>
                                                            <input readonly type="text" name="paid_amount[<?= $customer['id']; ?>]" value="<?= $customer['amount']; ?>" class="form-control fw-bold" style="width: 70px; font-weight: bold; font-size: 18px; color: #F20000;">
                                                        </td>
                                                        <td>
                                                            <input type="text" name="discount[<?= $customer['id']; ?>]" value="<?php echo $discountValue ?>" class="form-control fw-bold" style="width: 50px; color: #DD0581;">
                                                        </td>
                                                        <td>
                                                            <input type="date" class="form-control" name="due_month_date[<?= $customer['id']; ?>]" required>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
    
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button type="button" class="btn btn-primary" id="confirmButton" data-toggle="modal" data-target="#exampleModal">
                                        Confirm
                                    </button>
                                </div>
    
                                <!-- Modal -->
                                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <!--<p>Customer Name: <?php echo $customer['name']; ?></p>-->
                                                <p>Are you sure to make Bill ?
                                                    <br/>
                                                   <b>Check Due Date Properly !!!</b>
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Autosearch List -->
<script>  
 $(document).ready(function(){  
      $('#search').keyup(function(){  
           var query = $(this).val();  
           if(query != '')  
           {  
                $.ajax({  
                     url:"code-fecth-adv-billing-dashboard.php",  
                     method:"POST",  
                     data:{query:query},  
                     success:function(data)  
                     {  
                          $('#searchList').fadeIn();  
                          $('#searchList').html(data);  
                     }  
                });  
           }  
      });  
      $(document).on('click', 'li', function(){  
           $('#search').val($(this).text());  
           $('#searchList').fadeOut();  
      });  
 });
</script>
<script>        


</script><?php include 'footer.php'?>
</body>
</html>



<?php } else{
	header("Location: index.php");
} ?>
