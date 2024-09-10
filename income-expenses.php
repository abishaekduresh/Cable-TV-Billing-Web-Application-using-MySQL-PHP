<?php
session_start();
include "dbconfig.php";
include 'preloader.php';
require 'component.php';


if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    
    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'admin-menu-btn.php';
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
        ?><br><?php
        include 'sub-menu-btn.php';
    }

 ?>

<?php
if(isset($_POST['submitExpense'])) {
  
  // Retrieve form data
//   $date = $_POST['date'];
  $category = $_POST['category'];
  $subCategory = $_POST['subCategory'];
  $remark = $_POST['remark'];
  $amount = $_POST['amount'];
  $type = 'Expense';
  
  // Insert data into database
  $sql = "INSERT INTO in_ex (type, date, time, username, category_id, subcategory_id, remark, amount,status) VALUES ('$type', '$currentDate', '$currentTime', '$session_username', '$category', '$subCategory', '$remark', '$amount','1')";
  if ($con->query($sql) === TRUE) {
    // echo "<div class='container mt-3'>Data stored successfully!</div>";
    
            // Redirect function
            function redirect($url)
            {
                echo "<script>
                setTimeout(function(){
                    window.location.href = '$url';
                },500);
            </script>";
            }
    
            // Usage example
            $url = "income-expenses.php"; // Replace with your desired URL
            redirect($url);
        
  } else {
      
    echo "Error: " . $sql . "<br>" . $con->error;
    
  }
  
  // Close the database connection
//   $con->close();
}
?>

<?php
if(isset($_POST['submitIncome'])) {
  
  // Retrieve form data
//   $date = $_POST['date'];
  $category = $_POST['category'];
  $subCategory = $_POST['subCategory'];
  $remark = $_POST['remark'];
  $amount = $_POST['amount'];
  $type = 'Income';
  
  // Insert data into database
  $sql = "INSERT INTO in_ex (type, date, time, username, category_id, subcategory_id, remark, amount,status) VALUES ('$type', '$currentDate', '$currentTime', '$session_username', '$category', '$subCategory', '$remark', '$amount','1')";
  if ($con->query($sql) === TRUE) {
    // echo "<div class='container mt-3'>Data stored successfully!</div>";
    
            // Redirect function
            function redirect($url)
            {
                echo "<script>
                setTimeout(function(){
                    window.location.href = '$url';
                },500);
            </script>";
            }
    
            // Usage example
            $url = "income-expenses.php"; // Replace with your desired URL
            redirect($url);
        
  } else {
      
    echo "Error: " . $sql . "<br>" . $con->error;
    
  }
  
  // Close the database connection
//   $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income - Expenses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>

    <br>
    <!--<hr class="mt-0 mb-4">-->

<div class="container" style="margin-top: 10px;">
<!-- Nav tabs -->
    <ul class="nav nav-pills justify-content-center">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="pill" href="#Expense"><b>Add Expense</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="pill" href="#Income"><b>Add Income</b></a>
        </li>    
    </ul>

<!-- Tab panes -->
    <div class="tab-content">
        
        <div class="tab-pane container active" id="Expense">
<!--Expense-->
            <div class="container">
              <!-- <h2>Expense</h2> -->
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3">
                  <label for="category" class="form-label">Category</label>
                    <select class="form-select" name="category" id="categoryID" required>
                        <option selected disabled>Select Category</option>
                        <?php
                        $sql = "SELECT * FROM in_ex_category WHERE in_ex='Expense' AND status = '1'";
                        $result = mysqli_query($con, $sql);
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['category_id'] . '">' . $row['category'] . '</option>';
                        }
                        ?>
                    </select>
                </div>            
                <div class="mb-3">
                    <label for="sub" class="form-label">Sub Category</label>
                    <select class="form-select" name="subCategory" id="show_category">
                        <option selected disabled>Select Sub Category</option>
                    </select>
                </div>
                <script>
                    $(document).ready(function(){
                        $('#categoryID').change(function(){
                            var Stdid = $('#categoryID').val(); 

                            $.ajax({
                                type: 'POST',
                                url: 'code-in_ex_cat_sub_fetch.php',
                                data: {id: Stdid},  
                                success: function(data) {
                                    $('#show_category').html(data);
                                }
                            });
                        });
                    });
                </script>
                <div class="mb-3">
                  <label for="remark" class="form-label">Remark</label>
                  <textarea id="remark" name="remark" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount *</label>
                  <input type="number" id="amount" name="amount" class="form-control" required>
                </div>
                <button type="submit" name="submitExpense" class="btn btn-primary">Add Expense</button>
              </form>
            </div>
      
      <br>
      <hr class="mt-0 mb-4">
      
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Expense by <b><?php echo $session_username?></b>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Username</th>
                                    <th>Category</th>
                                    <th>subCategory</th>
                                    <th>Remark</th>
                                    <th>Expense</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                    $query = "SELECT * FROM in_ex WHERE username = '$session_username' AND date = '$currentDate' AND status = 1 AND type = 'Expense' ORDER BY date DESC";

                                    $query_run = mysqli_query($con, $query);

                                    $ex_sum = 0;

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $incomeExpense)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['date']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['time']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['username']; ?></td>
                                                <td style="font-weight: bold;">                                                
                                                <?php

                                                    $CategoryResult = $incomeExpense['category_id'];
                                                    // SQL query
                                                    $sql = "SELECT * FROM in_ex_category WHERE category_id='$CategoryResult' AND status = '1'";

                                                    // Execute query
                                                    $result = mysqli_query($con, $sql);

                                                    // Check if there are any rows in the result
                                                    if (mysqli_num_rows($result) > 0) {
                                                        // Output data of each row
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            // Process data from each row
                                                            echo $row["category"];
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }

                                                ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                
                                                <?php

                                                    $incomeExpenseResult = $incomeExpense['subcategory_id'];
                                                    // SQL query
                                                    $sql = "SELECT * FROM in_ex_subcategory WHERE subcategory_id='$incomeExpenseResult' AND status = 1";

                                                    // Execute query
                                                    $result = mysqli_query($con, $sql);

                                                    // Check if there are any rows in the result
                                                    if (mysqli_num_rows($result) > 0) {
                                                        // Output data of each row
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            // Process data from each row
                                                            echo $row["subcategory"];
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }

                                                ?>
                                                </td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['remark']; ?></td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Expense') {
                                                            echo '₹' . $incomeExpense['amount'];
                                                            $ex_sum += $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> Today NoRecord Found </h5>";
                                    }
                                ?>
                                <tr>
                                    <td colspan="6"></td>
                                    <td style="font-weight: bold; font-size: 20px;">Total:</td>
                                    <td style="font-size: 20px;">
                                        <b><?= '₹' . $ex_sum ?></b>
                                    </td>
                                    <td></td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="tab-pane container fade" id="Income">
<!--Income-->
            <div class="container">
              <!-- <h2>Income</h2> -->
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <div class="mb-3">
                  <label for="category" class="form-label">Category</label>
                    <select class="form-select" name="category" id="category_ID" required>
                        <option selected disabled>Select Category</option>
                        <?php
                        $sql = "SELECT * FROM in_ex_category WHERE in_ex='Income' OR in_ex='Both' AND status = 1";
                        $result = mysqli_query($con, $sql);
                        while($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row['category_id'] . '">' . $row['category'] . '</option>';
                        }
                        ?>
                    </select>
                </div>            
                <div class="mb-3">
                    <label for="sub" class="form-label">Sub Category</label>
                    <select class="form-select" name="subCategory" id="show_subcategory" required>
                        <option selected disabled>Select Sub Category</option></select>
                </div>
                <script>
                    $(document).ready(function(){
                        $('#category_ID').change(function(){
                            var Stdid = $('#category_ID').val(); 

                            $.ajax({
                                type: 'POST',
                                url: 'code-in_ex_cat_sub_fetch.php',
                                data: {id: Stdid},  
                                success: function(data) {
                                    $('#show_subcategory').html(data);
                                }
                            });
                        });
                    });
                </script>
                <div class="mb-3">
                  <label for="remark" class="form-label">Remark</label>
                  <textarea id="remark" name="remark" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount *</label>
                  <input type="number" id="amount" name="amount" class="form-control" required>
                </div>
                <button type="submit" name="submitIncome" class="btn btn-primary">Add Income</button>
              </form>
            </div>
      
      <br>
      <hr class="mt-0 mb-4">
      
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Income by <b><?php echo $session_username?></b>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Username</th>
                                    <th>Category</th>
                                    <th>subCategory</th>
                                    <th>Remark</th>
                                    <th>Income</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                    $query = "SELECT * FROM in_ex WHERE username = '$session_username' AND date = '$currentDate' AND status = '1' AND type = 'Income' ORDER BY date DESC";

                                    $query_run = mysqli_query($con, $query);

                                    $in_sum = 0;
                                    
                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $incomeExpense)
                                        {
                                            ?>
                                            <tr>
                                                <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['date']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['time']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['username']; ?></td>
                                                <td style="font-weight: bold;">                                                
                                                <?php

                                                    $CategoryResult = $incomeExpense['category_id'];
                                                    // SQL query
                                                    $sql = "SELECT * FROM in_ex_category WHERE category_id='$CategoryResult' AND status = '1'";

                                                    // Execute query
                                                    $result = mysqli_query($con, $sql);

                                                    // Check if there are any rows in the result
                                                    if (mysqli_num_rows($result) > 0) {
                                                        // Output data of each row
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            // Process data from each row
                                                            echo $row["category"];
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }

                                                ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                
                                                <?php

                                                    $incomeExpenseResult = $incomeExpense['subcategory_id'];
                                                    // SQL query
                                                    $sql = "SELECT * FROM in_ex_subcategory WHERE subcategory_id='$incomeExpenseResult' AND status = '1'";

                                                    // Execute query
                                                    $result = mysqli_query($con, $sql);

                                                    // Check if there are any rows in the result
                                                    if (mysqli_num_rows($result) > 0) {
                                                        // Output data of each row
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            // Process data from each row
                                                            echo $row["subcategory"];
                                                        }
                                                    } else {
                                                        echo "0 results";
                                                    }

                                                ?>
                                                </td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['remark']; ?></td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Income') {
                                                            echo $incomeExpense['amount'];
                                                            $in_sum += $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> Today NoRecord Found </h5>";
                                    }
                                ?>
                                <tr>
                                    <td colspan="6"></td>
                                    <td style="font-weight: bold; font-size: 20px;">Total:</td>
                                    <td style="font-size: 20px;">
                                    <!-- color: #0012C3;  color: #05A210; -->
                                        <b><?= '₹' . $in_sum ?></b>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>


</script><?php include 'footer.php'?>
</body>
</html>



<?php } else{
	header("Location: index.php");
} ?>

