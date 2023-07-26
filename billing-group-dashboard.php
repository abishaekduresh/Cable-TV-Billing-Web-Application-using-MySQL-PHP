<?php
session_start();
include "dbconfig.php";
require 'dbconfig.php';
require "component.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    

    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br><?php
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
        
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
            /// NOT THIS CODE https://chat.openai.com/share/80e316ef-c537-447b-9b20-a1db341e3e94
            /// THIS CODE https://chat.openai.com/share/59162802-39b4-4187-8f8c-b6ceb6bc0258
            if ($currentDay === '01') {
                // Check if there is any bill entry for the next month
                $checkNextMonthQuery = "SELECT billNo FROM billgroup WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(DATE_ADD('$currentDate', INTERVAL 1 MONTH), '%Y-%m') LIMIT 1";
                $result = $con->query($checkNextMonthQuery);
            
                if ($result->num_rows > 0) {
                    // There is already a bill entry for the next month, so set billNo to 1
                    $billNo = 1;
                } else {
                    // Retrieve the maximum billNo for the current month and year
                    $getMaxBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM billgroup WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
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
                // Retrieve the next billNo for the current month and year
                $getBillNoQuery = "SELECT MAX(billNo) AS maxBillNo FROM billgroup WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT('$currentDate', '%Y-%m')";
                $result = $con->query($getBillNoQuery);
            
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $billNo = $row["maxBillNo"] + 1;
                } else {
                    $billNo = 1;
                }
            }
            
            // $billGroupNo = 1;
            
///////        Insert Data into billGroupDetails    ////////////

            $Rs =0;
            
            $pMode = mysqli_real_escape_string($con, $_POST["pMode"]);
            $oldMonthBal = mysqli_real_escape_string($con, $_POST["oldMonthBal"]);
            $billAmount = mysqli_real_escape_string($con, $_POST["billAmt"]);
            $discount = mysqli_real_escape_string($con, $_POST["discount"]);
            $phone = mysqli_real_escape_string($con, $_POST["phone"]);
            $groupID = mysqli_real_escape_string($con, $_POST["groupID"]);
            $groupName = mysqli_real_escape_string($con, $_POST["groupName"]);
            $Rs = $billAmount;
            
            $Rs = $Rs + $oldMonthBal;
            // bill Group Details - Error: Cannot add or update a child row: a foreign key constraint fails (`ctv.pdpgroups`.`billGroupDetails`, CONSTRAINT `billGroupDetails_ibfk_1` FOREIGN KEY (`billGroupNo`) REFERENCES `billGroup` (`id`))
            $Rs = $Rs - $discount;
            
            $status = 'approve';
            
            
            // Prepare the INSERT query
            $sql = "INSERT INTO `billgroupdetails` (`billGroupNo`, `date`, `time`, `billBy`, `groupID`, `groupName`, `phone`, `pMode`, `oldMonthBal`, `billAmount`, `discount`, `Rs`, `status`) 
            VALUES ('$billNo', '$currentDate', '$currentTime', '$session_username', '$groupID', '$groupName', '$phone', '$pMode', '$oldMonthBal', '$billAmount', '$discount', '$Rs', '$status')";
            
            // Execute the query
            if ($con->query($sql) === true) {
                // echo "Record inserted in bill Group Details successfully.";
            } else {
                echo "bill Group Details - Error: " . $con->error;
            }

    
    
        // Retrieve checkbox values
$checkboxValues = isset($_POST["options"]) ? $_POST["options"] : [];

// Process selected checkboxes
foreach ($checkboxValues as $customerId) {
    // Retrieve form data
    $groupID1 = mysqli_real_escape_string($con, $_POST["groupID1"][$customerId]);
    $stbNo = mysqli_real_escape_string($con, $_POST["stbno"][$customerId]);
    $mso = mysqli_real_escape_string($con, $_POST["mso"][$customerId]);
    $cusName = mysqli_real_escape_string($con, $_POST["cusName"][$customerId]);
    $remark = mysqli_real_escape_string($con, $_POST["description"][$customerId]);

$status = 'approve';
    // Prepare the SQL statement
    $sql = "INSERT INTO billgroup (billNo, date, time, groupID, mso, stbNo, name, remark,status)
        VALUES ('$billNo', '$currentDate', '$currentTime', '$groupID1', '$mso', '$stbNo', '$cusName', '$remark','$status')";

           
                
            // Execute the SQL statement
            if ($con->query($sql) === TRUE) {
                // Data inserted successfully
                

                continue;
            } else {
                echo "Error inserting data: " . $con->error;


                ?>
                <center><img src="assets/red-thumbs-up.svg" alt="green-thumbs-up" width="512px" height="512px"></center>
                <?php
                break;
            }
        }

        // Redirect after processing
        ?>
        <!--<center><img src="assets/green-thumbs-up.svg" alt="green-thumbs-up" width="100px" height="100px"></center>-->
        <?php

        // Redirect function
        function redirect($url)
        {
            echo "<script>
            setTimeout(function(){
                window.location.href = '$url';
            }, 200);
        </script>";
        }

        // Usage example
        $url = "groupBill-3inch-Print.php?groupID=$groupID&date=$currentDate";
        redirect($url);
    }
    $groupID = '';  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Billing Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
    
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

</style>
</head>
<body>
    
    <br>
    <!--<hr class="mt-0 mb-4">-->

    <div class="container custom-container">
        <div class="row" style="width: 100%;">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Group Billing Dashboard
                            <a href="billing-dashboard.php">
                                <button type="button" class="btn btn-primary float-end">
                                    Indiv Bill
                                </button>
                            </a>
                        </h4>
                    </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-7">
                                    
                                    <form action="" method="GET">
                                        <div class="input-group mb-3">
                                            <select name="groupID" class="form-select">
                                                <option value="select" selected disabled>Select</option>
                                                <?php
                                                $query = "SELECT * FROM groupinfo WHERE id != '1' AND id != '2' LIMIT 100";
                                                $result = mysqli_query($con, $query);
                                                $selectedValue = isset($_GET['groupID']) ? $_GET['groupID'] : ''; // Get the selected value from the URL

                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $optionValueID = $row['id'];
                                                    $optionValue = $row['groupName'];
                                                    ?>
                                                    <option value="<?php echo $optionValueID; ?>" <?php if ($optionValue === $selectedValue) echo 'selected'; ?>><?php echo $optionValue; ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </form>

                                    
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
                                            <th>
                                                <?php //if (!$disableButton): ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                                </div>
                                                <?php //endif; ?>
                                            </th>
                                            <th>Group</th>
                                            <th>MSO</th>
                                            <th>STB No</th>
                                            <th>Name</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['groupID'])) {
                                            // $filtervalues = '';
                                            // $filtervalues = 0;
                                            $filtervalues = $_GET['groupID'];
    
                                            $name = '';
                                            $phone = '';
                                            
                                            $query = "SELECT * FROM customer WHERE cusGroup = '$filtervalues' LIMIT 300";
    
                                            $query_run = mysqli_query($con, $query);
    
                                            if (mysqli_num_rows($query_run) > 0) {
                                                $serial_number = 1;
                                                foreach ($query_run as $customer) {
    
                                                    $cusGroupID = mysqli_real_escape_string($con, $customer['cusGroup']);
    
                                                    // $nestedQuery = "SELECT * FROM billGroup 
                                                    // WHERE stbNo = '$stbNo'
                                                    // AND MONTH(`date`) = '$currentMonth'
                                                    // AND YEAR(`date`) = '$currentYear'";

                                                    $nestedQuery = "SELECT *
                FROM billgroup
                JOIN billgroupdetails ON billgroup.groupID = billgroupdetails.groupID
                WHERE billgroupdetails.groupID = '$cusGroupID'
                AND billgroupdetails.status = 'approve'
                AND billgroup.status = 'approve'
                AND MONTH(billgroupdetails.`date`) = '$currentMonth'
                AND YEAR(billgroupdetails.`date`) = '$currentYear'
                AND MONTH(billgroup.`date`) = '$currentMonth'
                AND YEAR(billgroup.`date`) = '$currentYear'";

                                                    
    
                                                    $nestedQuery_run = mysqli_query($con, $nestedQuery);
    
                                                    $disableButton = (mysqli_num_rows($nestedQuery_run) > 0) ? true : false;
                                                    
                                                    ?>
                                                    

                                                    <tr>
                                                        
                                                        <td style="font-weight: bold; font-size: 16px;"><?= $serial_number++; ?></td>
                                                        <td>
                                                            <?php if (!$disableButton): ?>
                                                                <div class="form-check">
                                                                    <input type="checkbox" name="options[]" value="<?= $customer['id']; ?>" class="form-check-input" required>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="width: 160px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="groupID1[<?= $customer['id']; ?>]" value="<?= $customer['cusGroup']; ?>" style="width: 200px;">
                                                        </td>
                                                        <td style="width: 160px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="mso[<?= $customer['id']; ?>]" value="<?= $customer['mso']; ?>" style="width: 70px;">
                                                        </td>
                                                        <td >
                                                                <input readonly class="form-control fw-bold" type="text" name="stbno[<?= $customer['id']; ?>]" value="<?= $customer['stbno']; ?>" style="width: 200px;">
                                                        </td>
                                                        
                                                        <td style="width: 350px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="cusName[<?= $customer['id']; ?>]" value="<?= $customer['name']; ?>"  style="width: 300px;">
                                                        </td>
                                                        <td style="width: 180px; font-weight: bold;">
                                                                <input readonly class="form-control fw-bold" type="text" name="description[<?= $customer['id']; ?>]" value="<?= $customer['description']; ?> " style="width: 180px;">
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


                                
                                <br/>
                                    
                                    
<div class="d-flex justify-content-center">
    <table>
        <tr>
            <th>Group</th>
            <th>Phone</th>
            <th>Pay Mode</th>
            <th>Old Bal</th>
            <th>Bill Amt</th>
            <th>Discount</th>
        </tr>
        <tr>
            <td>
                <div class="col">
                <?php
                 
                    $sql = "SELECT id,groupName,phone FROM groupinfo WHERE id = '$filtervalues'";
                    $result = $con->query($sql);

                    if ($result->num_rows > 0) {
                        
                        $row = $result->fetch_assoc();
                        $groupID = $row["id"];
                        $groupName = $row["groupName"];
                        $phone = $row["phone"];
                    } else {
                        $groupName = NULL;
                    }



                    $nestedQuery1 = "SELECT *
                    FROM billgroup
                    JOIN billgroupdetails ON billgroup.groupID = billgroupdetails.groupID
                    WHERE billgroupdetails.groupID = '$groupID'
                    AND billgroupdetails.status = 'approve'
                    AND billgroup.status = 'approve'
                    AND MONTH(billgroupdetails.`date`) = '$currentMonth'
                    AND YEAR(billgroupdetails.`date`) = '$currentYear'
                    AND MONTH(billgroup.`date`) = '$currentMonth'
                    AND YEAR(billgroup.`date`) = '$currentYear'";
                    
                                                        
                    
                                                        $nestedQuery_run1 = mysqli_query($con, $nestedQuery1);
                    
                                                        $disableButton1 = (mysqli_num_rows($nestedQuery_run1) > 0) ? true : false;

                                                             

                ?>

                    <label>
                        <input readonly type="hidden" name="groupID" value="<?php echo $groupID; ?>" class="form-control fw-bold" style="width: 220px; font-weight: bold; font-size: 18px; color: #F20000;">
                        <input readonly type="text" name="groupName" value="<?php echo $groupName; ?>" class="form-control fw-bold" style="width: 220px; font-weight: bold; font-size: 18px; color: #F20000;">
                    </label>
                </div>
            </td>
            <td>
                <div class="col">
                    <label>
                        <input readonly type="text" name="phone" value="<?php echo $phone; ?>" class="form-control fw-bold" style="width: 180px; font-weight: bold; font-size: 18px; color: #F20000;">
                    </label>
                </div>
            </td>
            <td>
                <div class="col">
                    <label>
                        <select name="pMode" class="form-select fw-bold" style="width: 100px; height: 40px;">
                            <option value="cash" selected class="fw-bold">Cash</option>
                            <option value="gpay" class="fw-bold">G Pay</option>
                            <option value="credit" class="fw-bold">Credit</option>
                        </select>
                    </label>
                </div>
            </td>
            <td>
                <label>
                    <input type="text" name="oldMonthBal" value="0" class="form-control fw-bold" style="width: 90px; color: #0012C3;">
                </label>
            </td>
            <td>
                <div class="col">
                    <?php
                    $sql = "SELECT billAmt FROM groupinfo WHERE id = '$filtervalues'";
                    $result = $con->query($sql);
                    // Check if any data is returned
                    if ($result->num_rows > 0) {
                        // Retrieve the first row from the result set
                        $row = $result->fetch_assoc();
                        $billAmt = $row["billAmt"];
                    } else {
                        $billAmt = "0"; // Default value if no data is found
                    }
                    
                    ?>
                    <label>
                        <input readonly type="text" name="billAmt" value="<?php echo $billAmt; ?>" class="form-control fw-bold" style="width: 90px; font-weight: bold; font-size: 18px; color: #F20000;">
                    </label>
                </div>
            </td>
            <td>
                <div class="col">
                    <label>
                        <input type="text" name="discount" value="0" class="form-control fw-bold" style="width: 90px; color: #DD0581;">
                    </label>
                </div>
            </td>
        </tr>
    </table>
</div>

                                    
                                <br/>
                                <div class="text-center">
                                <?php //if (!$disableButton1): ?>
                                    <button type="button" class="btn btn-primary" id="confirmButton" data-toggle="modal" data-target="#exampleModal">
                                        Confirm
                                    </button>
                                <?php //endif ?>
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
                                                <p>Are you sure to make Bill ?</p>
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

<br/>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>

    // // JavaScript code to handle checkbox functionality
    // const checkAllCheckbox = document.getElementById('checkAll');
    // const checkboxes = document.querySelectorAll('input[name="options[]"]');
    
    // function updateCheckAllCheckbox() {
    //     let allChecked = true;
    //     checkboxes.forEach(function(checkbox) {
    //         if (!checkbox.checked) {
    //             allChecked = false;
    //             return;
    //         }
    //     });
    
    //     checkAllCheckbox.checked = allChecked;
    // }
    
    // checkAllCheckbox.addEventListener('change', function() {
    //     checkboxes.forEach(function(checkbox) {
    //         checkbox.checked = checkAllCheckbox.checked;
    //     });
    // });
    
    // checkboxes.forEach(function(checkbox) {
    //     checkbox.addEventListener('change', function() {
    //         updateCheckAllCheckbox();
    //     });
    // });
    
const checkAllCheckbox = document.getElementById('checkAll');
const checkboxes = document.querySelectorAll('input[name="options[]"]');
const confirmButton = document.getElementById('confirmButton');

function updateCheckAllCheckbox() {
  let allChecked = true;
  checkboxes.forEach(function(checkbox) {
    if (!checkbox.checked) {
      allChecked = false;
      return;
    }
  });

  checkAllCheckbox.checked = allChecked;
  confirmButton.disabled = !allChecked; // Disable/enable the confirm button based on allChecked value
}

checkAllCheckbox.addEventListener('change', function() {
  checkboxes.forEach(function(checkbox) {
    checkbox.checked = checkAllCheckbox.checked;
  });
  updateCheckAllCheckbox(); // Update the confirm button status when 'Check All' checkbox is changed
});

checkboxes.forEach(function(checkbox) {
  checkbox.addEventListener('change', function() {
    updateCheckAllCheckbox();
  });
});

// Call the updateCheckAllCheckbox function initially to set the initial state of the confirm button
updateCheckAllCheckbox();


</script><?php include 'footer.php'?>
</body>
</html>



<?php } else{
	header("Location: index.php");
} ?>

