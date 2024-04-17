<?php 
   include "dbconfig.php";
   include 'preloader.php';
   include "component.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Log</title>
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
                        <h4>Customer Log</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    // $query = "SELECT * FROM bill ORDER BY bill_id DESC LIMIT 5 WHERE bill_by = '$session_usernamename'";
                                    $query = "SELECT * FROM customer_log ORDER BY customer_log_id DESC LIMIT 10";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $bill)
                                        {
                                            ?>
                                            <tr>
                                                <td style="width: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= formatDate($bill['timestamp']);?>&nbsp;<?= date('h:i A', strtotime($bill['timestamp'])); ?></td>
                                                <td style="font-weight: bold;"><?= $bill['stbno']; ?></td>
                                                <td style="font-weight: bold;"><?= $bill['name']; ?></td>
                                                <td style="font-weight: bold;"><?= $bill['phone']; ?></td>
                                                <td style="font-weight: bold;"><?= $bill['activity']; ?></td>
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

