<?php
session_start();
include "dbconfig.php";
require 'dbconfig.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    

    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        ?><br<?php
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
        
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
    }

 ?>

<?php
if(isset($_POST['submitExpense'])) {
  
  // Retrieve form data
  $date = $_POST['date'];
  $category = $_POST['category'];
  $subCategory = $_POST['subCategory'];
  $remark = $_POST['remark'];
  $amount = $_POST['amount'];
  $type = 'Expense';
  
  // Insert data into database
  $sql = "INSERT INTO incomeExpence (type, date, time, username, category, subCategory, remark, amount) VALUES ('$type', '$date', '$currentTime', '$session_username', '$category', '$subCategory', '$remark', '$amount')";
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
  $date = $_POST['date'];
  $category = $_POST['category'];
  $subCategory = $_POST['subCategory'];
  $remark = $_POST['remark'];
  $amount = $_POST['amount'];
  $type = 'Income';
  
  // Insert data into database
  $sql = "INSERT INTO incomeExpence (type, date, time, username, category, subCategory, remark, amount) VALUES ('$type', '$date', '$currentTime', '$session_username', '$category', '$subCategory', '$remark', '$amount')";
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
              <h2>Expense</h2>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div class="mb-3">
  <label for="date" class="form-label">Date</label>
  <input type="date" id="date" name="date" value="<?php echo $currentDate ?>"class="form-control">
</div>
                <div class="mb-3">
                  <label for="category" class="form-label">Category *</label>
                      <select id="category" name="category" class="form-select" required>
                        <option value="" selected disabled>Choose a category</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Salary">Salary</option>
                        <option value="Spliter">Spliter</option>
                        <option value="Patch Card">Patch Card</option>
                        <option value="Shop Rent">Shop Rent</option>
                        <option value="Food">Food</option>
                        <option value="Wire">Wire</option>
                        <option value="LCO Recharge">LCO Recharge</option>
                        <option value="Bike Service">Bike Service</option>
                        <option value="EB Bill">EB Bill</option>
                        <option value="Server Rent">Server Rent</option>
                        <option value="Other">Other</option>
                      </select>
                        <!--<select id="category" name="category" class="form-select">-->
                            <!--<option value="select" selected>Select</option>-->
                                <?php
                                    //$query = "SELECT category FROM incomeExpenceinfo WHERE category != 'ALL'";
                                    //$result = mysqli_query($con, $query);
                                    //$selectedValue = isset($_GET['category']) ? $_GET['category'] : ''; // Get the selected value from the URL
                                        //while ($row = mysqli_fetch_assoc($result)) {
                                            //$optionValueID = $row['id'];
                                            //$optionValue = $row['category'];
                                ?>
                            <!--<option value="<?php //echo $optionValueID; ?>" <?php //if ($optionValue === $selectedValue) echo 'selected'; ?>><?php //echo $optionValue; ?></option>-->
                                <?php
                                        //}
                                ?>
                        <!--</select>-->
                </div>
                <div class="mb-3">
                  <label for="subCategory" class="form-label">Sub Category *</label>
                  <select id="subCategory" name="subCategory" class="form-select" required>
                    <option value="" selected disabled>Choose a category</option>
                    <option value="RF Wire">RF Wire</option>
                    <option value="Fiber Wire">Fiber Wire</option>
                    <option value="VKTENTH003">VKTENTH003</option>
                    <option value="VKTENTH055">VKTENTH055</option>
                    <option value="Santhanam">Santhanam</option>
                    <option value="Gladwin">Gladwin</option>
                    <option value="Kannika">Kannika</option>
                    <option value="Jeyaraj">Jeyaraj</option>
                    <option value="Kannan">Kannan</option>
                    <option value="Baskar Raj">Baskar Raj</option>
                    <option value="Abishaek">Abishaek</option>
                    <option value="Nithin">Nithin</option>
                    <option value="Aruna Kumari">Aruna Kumari</option>
                    <option value="Other">Other</option>
                  </select>
                </div>
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
                                    <th>Income</th>
                                    <th>Expense</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                    $query = "SELECT * FROM incomeExpence WHERE username = '$session_username' AND type = 'Expense' ORDER BY date DESC LIMIT 10";

                                    $query_run = mysqli_query($con, $query);

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
                                                <td style="font-weight: bold;"><?= $incomeExpense['category']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['subCategory']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['remark']; ?></td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Income') {
                                                            echo $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Expense') {
                                                            echo $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                
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
              <h2>Income</h2>
              <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<div class="mb-3">
  <label for="date" class="form-label">Date</label>
  <input type="date" id="date" name="date" value="<?php echo $currentDate ?>"class="form-control">
</div>
                <div class="mb-3">
                  <label for="category" class="form-label">Category *</label>
                      <select id="category" name="category" class="form-select" required>
                        <option value="" selected disabled>Choose a category</option>
                        <option value="Petrol">Petrol</option>
                        <option value="Salary">Salary</option>
                        <option value="Wire Coil">Wire Coil</option>
                        <option value="FiberWire">Fiber Wire</option>
                        <option value="LCO Recharge">LCO Recharge</option>
                        <option value="Bike Service">Bike Service</option>
                        <option value="EB Bill">EB Bill</option>
                        <option value="Server Rent">Server Rent</option>
                        <option value="Other">Other</option>
                      </select>
                </div>
                <div class="mb-3">
                  <label for="subCategory" class="form-label">Sub Category *</label>
                      <select id="subCategory" name="subCategory" class="form-select" required>
                        <option value="" selected disabled>Choose a category</option>
                        <option value="VKTENTH003">VKTENTH003</option>
                        <option value="VKTENTH055">VKTENTH055</option>
                        <option value="Santhanam">Santhanam</option>
                        <option value="Gladwin">Gladwin</option>
                        <option value="Kannika">Kannika</option>
                        <option value="Jeyaraj">Jeyaraj</option>
                        <option value="Baskar Raj">Baskar Raj</option>
                        <option value="Abishaek">Abishaek</option>
                        <option value="Nithin">Nithin</option>
                        <option value="Aruna Kumari">Aruna Kumari</option>
                        <option value="Other">Other</option>
                      </select>
                </div>
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
                                    <th>Expense</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                    $query = "SELECT * FROM incomeExpence WHERE username = '$session_username' AND type = 'Income' ORDER BY date DESC LIMIT 10";

                                    $query_run = mysqli_query($con, $query);

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
                                                <td style="font-weight: bold;"><?= $incomeExpense['category']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['subCategory']; ?></td>
                                                <td style="font-weight: bold;"><?= $incomeExpense['remark']; ?></td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Income') {
                                                            echo $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                                <td style="font-weight: bold;">
                                                    <?php
                                                        if ($incomeExpense['type'] === 'Expense') {
                                                            echo $incomeExpense['amount'];
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                
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

