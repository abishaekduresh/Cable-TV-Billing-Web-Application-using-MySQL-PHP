<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   
//    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
        $session_username = $_SESSION['username']; 
        include 'admin-menu-bar.php';?>

<?php
$folderPath = 'bill-excel-downloaded-files/'; // Replace with the actual path to the folder

// Retrieve all files in the folder
$files = scandir($folderPath);

// Exclude . and .. from the file list
$files = array_diff($files, array('.', '..'));

// Sort the files array by modified time in descending order
usort($files, function ($a, $b) use ($folderPath) {
    return filemtime($folderPath . $b) - filemtime($folderPath . $a);
});
?>

<!DOCTYPE html>
<html>
<head>
    <title>Downloaded Files</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Downloaded Files</h2>
        <?php if (count($files) > 0) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>File Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file) : ?>
                        <tr>
                            <td><?php echo $file; ?></td>
                            <td><a href="<?php echo $folderPath . $file; ?>" class="btn btn-primary" download>Download</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No files available.</p>
        <?php endif; ?>
    </div>
</body>
</html>


<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>
