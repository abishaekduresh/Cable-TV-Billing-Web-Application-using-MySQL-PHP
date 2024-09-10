<!-- //fetch.php -->
<?php
  include('dbconfig.php');
 
  $category_id = $_POST['id'];
  $sql = "SELECT * FROM in_ex_subcategory WHERE category_id= '$category_id' AND status = '1'";
  $result = mysqli_query($con,$sql);
 
  $out='';
  while($row = mysqli_fetch_assoc($result)) 
  {   
     $out .=  '<option value="' . $row['subcategory_id'] . '">' . $row['subcategory'] . '</option>'; 
  }
   echo $out;
?>