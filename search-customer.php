<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   
   if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Customer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body>
    
<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    include 'admin-menu-bar.php';
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    include 'menu-bar.php';
}
?>

<!------------------------Search Customer-------------------------------->

<div class="container ">
        <div class="row">
            <div class="col-md-12 ">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Search Customer </h4>
                    </div>
                    <div class="card-body ">
                        <div class="row">
                            <div class="col-md-7">

                                <form action="" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="STB No, Name, Phone">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div><h6>Bill Status - என்பது நடப்புமாத சந்த தொகை செலுத்தியாரா இல்லையா என்பதை குறிப்பிடுகிறது.</h6>
                        </div>
                    </div>
                </div>
            </div>
            
            

            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Bill Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    if(isset($_GET['search']))
                                    {
                                        $currentMonth = date('m');
                                        $currentYear = date('Y');
                                        $SerialNumber = 1;
                                    
                                        $filtervalues = $_GET['search'];
                                        $query = "SELECT * FROM customer WHERE CONCAT(stbno,name,phone) LIKE '%$filtervalues%' LIMIT 300";
                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            foreach($query_run as $customer)
                                            {
                                                
                                            $stbno = mysqli_real_escape_string($con, $customer['stbno']);

                                            $nestedQuery = "SELECT * FROM bill 
                                                            WHERE stbno = '$stbno' AND status = 'approve'
                                                            AND MONTH(`date`) = '$currentMonth'
                                                            AND YEAR(`date`) = '$currentYear'";

                                            $nestedQuery_run = mysqli_query($con, $nestedQuery);

                                            $paidCustomer = (mysqli_num_rows($nestedQuery_run) > 0) ? true : false;
                                                
                                                ?>
                                                <tr><form action="" method="POST">
                                                    <td style="width: 18px; font-weight: bold;"><?php echo $SerialNumber++?></td>
                                                    <td style="font-weight: bold;"><?= $customer['mso']; ?></td>
                                                    <td style="font-weight: bold;"><?= $customer['stbno']; ?></td>
                                                    <td style="font-weight: bold;"><?= $customer['name']; ?></td>
                                                    <td style="font-weight: bold;"><?= $customer['phone']; ?></td>
                                                    <td style="font-weight: bold;"><?= $customer['description']; ?></td>
                                                    <td style="font-weight: bold;"><?= $customer['amount']; ?></td>
                                                    <td>
                                                        <?php if ($paidCustomer): ?>
                                                            <center><img src="assets/green-thumbs-up.svg" alt="Customer Paid" width="40px" height="40px"></center>
                                                        <?php else: ?>
                                                            <center><img src="assets/red-thumbs-down.svg" alt="Customer Paid" width="40px" height="40px"></center>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>