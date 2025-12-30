<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role'])) {
    $session_username = $_SESSION['username'];
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
    <?php include 'favicon.php'; ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Bill by All Report</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            a.link {
                text-decoration: none;
                color: white;
            }
            .checkbox-container {
                display: flex;
                flex-wrap: wrap;
            }
            .checkbox-container label {
                width: 33.33%;
                box-sizing: border-box;
                margin-bottom: 10px;
            }
            .checkbox-container label:nth-child(3n) {
                margin-right: 0;
            }
        </style>
    </head>

    <body>
        <?php
        if ($_SESSION['role'] == 'admin') {
            include 'admin-menu-bar.php';
            echo '<br>';
            include 'admin-menu-btn.php';
        } else {
            include 'menu-bar.php';
            echo '<br>';
            include 'sub-menu-btn.php';
        }
        ?>

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card mt-5">
                        <div class="card-header">
                            <h4>Indiv Unpaid Customer Report</h4>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Select Month</label>
                                            <input type="month" name="month_year" 
                                                   value="<?= isset($_GET['month_year']) ? $_GET['month_year'] : date('Y-m'); ?>" 
                                                   class="form-control"/>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Enter Start Limit</label>
                                        <input type="number" name="query_start_limit" class="form-control" required/>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Enter End Limit</label>
                                        <input type="number" name="query_end_limit" class="form-control" required/>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <br>
                                            <button type="submit" class="btn btn-primary">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover" border="5" style="white-space: nowrap;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Status</th>
                                            <th>UID</th>
                                            <th>MSO</th>
                                            <th>STB No</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Remarks</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['month_year'])) {
                                            $month_year = $_GET['month_year'];
                                            $query_start_limit = isset($_GET['query_start_limit'])?$_GET['query_start_limit']:0;
                                            $query_end_limit = isset($_GET['query_end_limit'])?$_GET['query_end_limit']:0;
                                            $difference = abs($query_end_limit - $query_start_limit);
                                            if ($difference <= 100) {
                                                $query_limit = $difference;
                                            }else{
                                                echo "<script>alert('The Limit should be 100 or less');</script>";
                                                $query_limit = 0;
                                            }
                                            $stmt = $con->prepare("SELECT * FROM customer WHERE rc_dc = 1 LIMIT ?");
                                            $stmt->bind_param("i", $query_limit);
                                            $stmt->execute();
                                            $result = $stmt->get_result();

                                            if ($result->num_rows > 0) {
                                                $serial_number = 1;
                                                while ($row = $result->fetch_assoc()) {
                                                    $IndivPreMonthPaidStatus=fetchIndivPreMonthPaidStatus($row['stbno'], $month_year);

                                                    if ($IndivPreMonthPaidStatus['code'] != 200) {
                                                        $stmt2 = $con->prepare("SELECT * FROM customer WHERE id = ?");
                                                        $stmt2->bind_param("s", $row['id']);
                                                        $stmt2->execute();
                                                        $result2 = $stmt2->get_result();

                                                        if ($result2->num_rows > 0) {
                                                            while ($row2 = $result2->fetch_assoc()) {
                                                                ?>
                                                                <tr>
                                                                    <td style="font-weight: bold;"><?= $serial_number++; ?></td>
                                                                    <td style="font-weight: bold;"><?= $IndivPreMonthPaidStatus['html_code']; ?></td>
                                                                    <td style="font-weight: bold;"><?= $row2['id']; ?></td>
                                                                    <td style="font-weight: bold;"><?= $row2['mso']; ?></td>
                                                                    <td style="font-weight: bold;"><?= $row2['stbno']; ?></td>
                                                                    <td style="font-weight: bold;"><?= $row2['name']; ?></td>
                                                                    <td style="font-weight: bold;"><?= $row2['phone']; ?></td>
                                                                    <td style="font-weight: bold;"><?= $row2['description']; ?></td>
                                                                    <td style="font-weight: bold; color: #F20000;">
                                                                        <?= $row2['amount']; ?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                            }
                                                        }
                                                        $stmt2->close();
                                                    }
                                                    // else{
                                                    //     echo "<tr><td colspan='9' class='text-center'>No Record Found</td></tr>";
                                                    // }
                                                }
                                            } else {
                                                echo "<tr><td colspan='9' class='text-center'>No Record Found</td></tr>";
                                            }
                                            $stmt->close();
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

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>

    <?php include 'footer.php'; ?>
    <?php
} else {
    header("Location: index.php");
    exit();
}
?>
