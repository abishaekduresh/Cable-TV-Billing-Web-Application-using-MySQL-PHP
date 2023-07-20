<?php 
   session_start();
   include "dbconfig.php";
   require 'dbconfig.php';
    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_username = $_SESSION['username']; ?>

<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    include 'admin-menu-bar.php';
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    include 'menu-bar.php';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How to Import Excel Data into database in PHP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body >
    
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">

                <?php
                if(isset($_SESSION['message']))
                {
                    echo "<h4>".$_SESSION['message']."</h4>";
                    unset($_SESSION['message']);
                }
                ?>

                <div class="card">
                    <div class="card-header">
                        <h4>Import Customer to Databse</h4>
                    </div>
                    <div class="card-body">

                        <form action="code.php" method="POST" enctype="multipart/form-data">

                            <input type="file" name="import_file" class="form-control" required />
                            <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>
                            <a href="template/Import-Customer-to-DB.xlsx" download><button type="button" class="btn btn-secondary mt-3"><i class="bi bi-cloud-download"></i>Template</button></a>

                        </form>

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