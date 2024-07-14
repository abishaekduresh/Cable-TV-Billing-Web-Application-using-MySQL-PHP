<?php 
   session_start();
   include "dbconfig.php";
   include "component.php";
   if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
$token = "666186679e48b9.2";
$query = "SELECT * FROM pos_bill_items WHERE token = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data and append to HTML
// while ($row = $result->fetch_assoc()) {
//     // Fetch product name using getProductName function
//     $productName = getProductName($con, $row['pos_product_id']); // Assuming 'product_id' is the correct column name
//     if ($productName === false) {
//         // Handle error if product name retrieval fails
//         $productName = 'Error: Product Name Not Found';
//     }
//     echo '<tr>
//         <td>' . htmlspecialchars($productName) . '</td>
//         <td>' . $row['price'] . '</td>
//         <td>' . $row['qty'] . '</td>
//     </tr>';
// }

echo generateUniquePosInvoiceId();


// echo $sms_res = sms_api('Abi', '7708443543', '000', '2024-05-24', '00008317', 'gpay', 'approve');
 ?>



<?php include 'admin-menu-bar.php'; ?>



<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>