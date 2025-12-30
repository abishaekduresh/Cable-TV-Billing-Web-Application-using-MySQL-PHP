<?php
require 'dbconfig.php';

if(isset($_POST['bill_id'])) {
    $bill_id = mysqli_real_escape_string($con, $_POST['bill_id']);
    
    $query = "SELECT pbi.*, p.product_name 
              FROM pos_bill_items pbi 
              LEFT JOIN pos_product p ON pbi.pos_product_id = p.pos_product_id 
              WHERE pbi.pos_bill_id = '$bill_id'";
              
    $query_run = mysqli_query($con, $query);
    
    if(mysqli_num_rows($query_run) > 0) {
        $count = 1;
        $total_sum = 0;
        foreach($query_run as $row) {
            $total = $row['price'] * $row['qty'];
            $total_sum += $total;
            ?>
            <tr>
                <td><?= $count++ ?></td>
                <td class="fw-bold"><?= $row['product_name'] ?></td>
                <td class="text-center"><?= $row['qty'] ?></td>
                <td class="text-end">₹<?= number_format($row['price'], 2) ?></td>
                <td class="text-end fw-bold">₹<?= number_format($total, 2) ?></td>
            </tr>
            <?php
        }
        ?>
        <tr class="bg-light border-top">
            <td colspan="4" class="text-end fw-bold">Sub Total</td>
            <td class="text-end fw-bold text-primary">₹<?= number_format($total_sum, 2) ?></td>
        </tr>
        <?php
    } else {
        echo '<tr><td colspan="5" class="text-center text-muted">No items found for this bill.</td></tr>';
    }
}
?>
