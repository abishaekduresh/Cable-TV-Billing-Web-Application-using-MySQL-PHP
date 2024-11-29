<?php

session_start();
if (!isset($_SESSION['username']) && !isset($_SESSION['id'])) {
  header("Location: ../index.php");
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Local Channel Portal | Dashboard</title>
    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      /* body { */
        /* padding: 5px 5px 5px 5px; */
      /* } */

      .bottom-right {     /* Return to CTV Dashboard */
          position: fixed;
          bottom: 0;
          right: 0;
          width: 100px; /* Set your desired width */
          height: auto; /* Auto height to maintain aspect ratio */
          margin: 10px; /* Optional: adds spacing from the edges */
      }

      .ajax-dropdown-menu {
          display: none;
          opacity: 0;
          transform: translateY(-10px);
          transition: opacity 1s ease, transform 1s ease;
          max-height: 200px;
          overflow-y: auto;
          width: 100%;
          position: absolute;
          z-index: 1000; 
      }

      .ajax-dropdown-menu.show {
          display: block;
          opacity: 1;
          transform: translateY(0);
      }

      #loc_billing_btn {
          display: none; /* Initially hides the button */
      }
    </style>
</head>
<body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- <script src="../lib/alertifyjs/alertify.min.js"></script>
<script src="../lib/alertifyjs/alertify.js"></script> -->
<!-- CSS for styling -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css"/>
<!-- Default theme -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css"/>

<!-- JS for functionality -->
<script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>


<?php include 'header.php';
      require '../dbconfig.php';
      require '../component.php';

if(isset($_GET['page']) && $_GET['page'] == 'new-bill'){
  include 'bill/new-bill.php'; 
}elseif(isset($_GET['page']) && $_GET['page'] == 'new-channel'){
  include 'new-channel.php'; 
}elseif(isset($_GET['page']) && $_GET['page'] == 'gen-bill'){
  include 'bill/gen-bill.php'; 
}elseif(isset($_GET['page']) && $_GET['page'] == 'rpt-channels'){
  include 'rpt/channels.php'; 
}elseif(isset($_GET['page']) && $_GET['page'] == 'rpt-loc-bills'){
  include 'rpt/loc-bills.php'; 
}else{
  include 'dash.php';
}

?>

<a href="../admin-dashboard.php"><img src="../assets/paid.png" alt="Image" class="bottom-right"></a>


</body>
</html>
