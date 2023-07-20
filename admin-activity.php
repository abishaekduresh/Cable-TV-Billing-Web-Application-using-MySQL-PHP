<?php 
session_start();
include "dbconfig.php";

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
    $session_username = $_SESSION['username'];
    include 'admin-menu-bar.php';
?>


<?php
// Include the database configuration file
include 'dbconfig.php';

// Query to fetch the last 50 data from the database
$query = "SELECT * FROM user_activity ORDER BY id DESC LIMIT 50";
$result = mysqli_query($con, $query);
?>
<br/>
<!DOCTYPE html>
<html>
<head>
    <title>User Activity</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h3>Last 50 Activity</h3>
        <div class="table-responsive">
        <table class="table table-hover" border="5">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Name</th>
                    <th>Action</th>
                    <!-- Add more table headers as needed -->
                </tr>
            </thead>
            <tbody>
                <?php $serialNumber = 1;?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td style="width: 18px; font-weight: bold;"><?php echo $serialNumber++?></td>
                        <td style="width: 150px; font-weight: bold;"><?php echo $row['date']; ?></td>
                        <td style="width: 100px; font-weight: bold;"><?php echo $row['time']; ?></td>
                        <td style="width: 100px; font-weight: bold;"><?php echo $row['userName']; ?></td>
                        <td style="width: 400px; font-weight: bold;"><?php echo $row['action']; ?></td>
                        <!-- Add more table data columns as needed -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



<?php include 'footer.php'?>

<?php } else {
    header("Location: index.php");
} ?>
