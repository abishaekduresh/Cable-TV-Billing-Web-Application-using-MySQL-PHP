<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   include "component.php";
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        ?>

<?php
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
    <title>Latest 10 Bill</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body >

    <br>

<!---------    last 5 bill print   --------------->

<div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Latest 10 Bill by <b><?php echo $session_username?></b>
                            <a href="billing-dashboard.php"><button class="btn btn-primary float-end" accesskey="n">Billing Dashboard</button></a>
                            <br/>
                            <a href="prtindivbulkbilldash.php"><button class="btn btn-primary float-end" accesskey="n">Bulk Print</button></a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>BillNo</th>
                                    <th>Date</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Discription</th>
                                    <th>P.Mode</th>
                                    <th>OldBal</th>
                                    <th>BillAmt</th>
                                    <th>Disct</th>
                                    <th>Rs.</th>
                                    <th>Print</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // $query = "SELECT * FROM bill ORDER BY bill_id DESC LIMIT 5 WHERE bill_by = '$session_usernamename'";
                                    $query = "SELECT * FROM bill WHERE bill_by = '$session_username' AND DAY(date) = '$currentDay' AND status = 'approve' ORDER BY bill_id DESC LIMIT 10";
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
                                                <td style="width: 130px; font-weight: bold;"><?= formatDate($bill['date']); ?></td>
                                                <td style="width: 70px; font-weight: bold;"><?= $bill['mso']; ?></td>
                                                <td style="width: 160px; font-weight: bold;"><?= $bill['stbno']; ?></td>
                                                <td style="width: 350px; font-weight: bold;"><?= $bill['name']; ?></td>
                                                <td style="width: 110px; font-weight: bold;"><?= $bill['phone']; ?></td>
                                                <td style="width: 180px; font-weight: bold;"><?= $bill['description']; ?></td>
                                                <td style="width: 40px; font-weight: bold;"><?= $bill['pMode']; ?></td>
                                                <td style="width: 50px; font-weight: bold; color: #0012C3;">
                                                    <?= $bill['oldMonthBal']; ?>
                                                </td>
                                                <td style="width: 50px; font-weight: bold; color: #05A210;">
                                                    <?= $bill['paid_amount']; ?>
                                                </td>
                                                <td style="width: 50px; font-weight: bold; color: #DD0581;">
                                                    <?= $bill['discount']; ?>
                                                </td>
                                                <td style="width: 70px; font-weight: bold; font-size: 18px; color: #F20000;">
                                                    <?= $bill['Rs']; ?>
                                                </td>
                                                <td>
                                                    <a href="prtindivbillrpt.php?billid=<?= $bill['bill_id']; ?>" target="blank">
                                                    <button type="button" class="btn btn-warning btn-lg"><i class="bi bi-printer-fill"></i></button></a>
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
