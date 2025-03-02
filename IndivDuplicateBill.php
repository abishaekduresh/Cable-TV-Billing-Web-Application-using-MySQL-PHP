<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   include "component.php";
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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Indiv Duplicate Bills</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body >

<!---------    last 5 bill print   --------------->

<div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Current Month Indiv Duplicate Bills</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5" style="white-space: nowrap;">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>BillNo</th>
                                    <th>Due Date</th>
                                    <th>Bill by</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Remark</th>
                                    <th>History</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // $query = "SELECT *, COUNT(*) AS occurrences FROM bill WHERE YEAR(due_month_timestamp) = $currentYear AND MONTH(due_month_timestamp) = $currentMonth GROUP BY stbno HAVING occurrences >= 2 AND status = 'Approve'";
$query = "SELECT * 
          FROM bill 
          WHERE YEAR(due_month_timestamp) = $currentYear 
          AND MONTH(due_month_timestamp) = $currentMonth 
          AND status = 'Approve' 
          AND stbno IN (
              SELECT stbno 
              FROM bill 
              WHERE YEAR(due_month_timestamp) = $currentYear 
              AND MONTH(due_month_timestamp) = $currentMonth 
              AND status = 'Approve'
              GROUP BY stbno 
              HAVING COUNT(*) >= 2
          )
          ORDER BY stbno ASC";





                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $bill)
                                        {
                                            ?>
                                            <tr>
                                                <td style="width: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="width: 40px; font-weight: bold;"><?= $bill['billNo']; ?></td>
                                                <td style="width: 130px; font-weight: bold; color: #007DC3;"><?= formatDate($bill['due_month_timestamp']); ?></td>
                                                <td style="width: 40px; font-weight: bold;"><?= $bill['bill_by']; ?></td>
                                                <td style="width: 70px; font-weight: bold;"><?= $bill['mso']; ?></td>
                                                <td style="width: 160px; font-weight: bold;"><?= $bill['stbno']; ?></td>
                                                <td style="width: 350px; font-weight: bold;"><?= $bill['name']; ?></td>
                                                <td style="width: 110px; font-weight: bold;"><?= $bill['phone']; ?></td>
                                                <td style="width: 250px; font-weight: bold;"><?= $bill['description']; ?></td>
                                                <td>
                                                    <a href="customer-history.php?search=<?= $bill['stbno']; ?>" target="blank">
                                                        <img src="assets/arrow-up-right-from-square-solid.svg" width="25px" height="25px">
                                                    </a>
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




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>
