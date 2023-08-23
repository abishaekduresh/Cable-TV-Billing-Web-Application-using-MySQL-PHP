<?php
session_start();
require "../dbconfig.php";
require "../component.php";

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    
    include 'pos-menu-bar.php';

    // if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    //     include '../admin-menu-bar.php';
    //     ?><!--br--><?php
    //     include '../admin-menu-btn.php';
    //     $session_username = $_SESSION['username'];
        
    // } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    //     include '../menu-bar.php';
    //     $session_username = $_SESSION['username'];
    // }
?>

<html>
    <head>
    <title>POS System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <style>
            body {
                background-color: #D7DBDD; /* Replace this with the color you want */
            }
        </style>
    </head>
    <body>




    <div class="container mt-5">
        <h1 class="mb-4">Point of Sale</h1>
        <div class="mb-3">
            <label class="form-label">Select Price Type:</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="price_type" id="retail" value="retail" checked>
                <label class="form-check-label" for="retail">Retail</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="price_type" id="wholesale" value="wholesale">
                <label class="form-check-label" for="wholesale">Wholesale</label>
            </div>
        </div>
        <form id="pos-form">
            <table class="table table-bordered" id="product-table">
                <thead>
                    <tr>
                        <th class="col-3">Product</th>
                        <th class="col-1">Quantity</th>
                        <th class="col-1">Sales Price</th>
                        <th class="col-1">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="product-row">
                        <td>
                            <select class="form-select product-select" name="product_id[]">
                                <!-- Populate this select box dynamically using Ajax -->
                            </select>
                        </td>
                        <td><input type="number" class="form-control quantity" name="quantity[]" min="1" value="1"></td>
                        <td><input type="number" class="form-control sales-price" name="sales_price[]" min="0.01" step="0.01" value="0.00"></td>
                        <td><span class="amount">₹0.00</span></td>
                    </tr>
                </tbody>
            </table>
            <div align="center">
                <button type="button" class="btn btn-secondary mb-3" id="add-row-btn">Add Row</button>
            </div>
            <br>
            <div align="right">
            <label for="discount">Discount:</label>
            <input type="number" id="discount" class="form-control col-1" min="0" step="0.01" value="0.00">
            <br>
            <label for="subtotal">Subtotal:</label>
            <b><span id="subtotal">₹0.00</span></b>
            <br>
            <button type="button" class="btn btn-primary" id="calculate-btn">Calculate Total</button>
            </div>
        </form>
    </div>

    <br/>
    
    <script>
    $(document).ready(function() {
        // Load products into the select boxes on page load
        loadProducts();

        // Calculate totals when quantity, sales price, discount, or price type changes
        $('#product-table').on('input', '.quantity, .sales-price', calculateTotals);
        $('#discount').on('input', calculateSubtotal);
        $('input[name="price_type"]').on('change', calculateSubtotal);

        // Calculate and display subtotals and grand total
        $('#calculate-btn').on('click', calculateSubtotal);

        // Add new row on button click
        $('#add-row-btn').on('click', addNewRow);
    });

    function loadProducts() {
    var priceType = $('input[name="price_type"]:checked').val();
    console.log("Price Type:", priceType); // Check in the browser console if this is correct
    
    $.ajax({
        url: 'get_product_info.php',
        type: 'GET',
        data: {
            price_type: priceType
        },
        dataType: 'json',
        success: function(data) {
            console.log("Product Data:", data); // Check if the received data is correct
            populateProductSelect(data);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

    function populateProductSelect(products) {
        var productSelects = $('.product-select');
        productSelects.empty();

        products.forEach(function(product) {
            productSelects.append(
                '<option value="' + product.product_id + '">' + product.product_name + '</option>'
            );
        });
    }

    function calculateTotals() {
        var row = $(this).closest('.product-row');
        var quantity = parseInt(row.find('.quantity').val());
        var salesPrice = parseFloat(row.find('.sales-price').val());
        var amount = quantity * salesPrice;
        row.find('.amount').text('₹' + amount.toFixed(2));
    }

    function calculateSubtotal() {
        var subtotals = 0;
        var discount = parseFloat($('#discount').val());

        $('.product-row').each(function() {
            var amount = parseFloat($(this).find('.amount').text().substring(1));
            subtotals += amount;
        });

        // Apply the discount to the subtotal
        subtotals -= discount;

        $('#subtotal').text('₹' + subtotals.toFixed(2));
    }

    function addNewRow() {
        var newRow = $('.product-row').first().clone();
        newRow.find('.quantity').val(1);
        newRow.find('.sales-price').val(0.00);
        newRow.find('.amount').text('₹0.00');
        $('#product-table tbody').append(newRow);
    }


$('#calculate-btn').on('click', function() {
    var salesData = [];
    
    // ... (previous code to collect salesData)

    var discount = $('#discount').val();
    var subtotal = $('#subtotal').text().substring(1);

    $.ajax({
        url: 'insert_sales.php', // Update the filename if needed
        type: 'POST',
        data: {
            salesData: JSON.stringify(salesData),
            discount: discount,
            subtotal: subtotal
        },
        success: function(response) {
            showAlert("Sales transaction was successful!");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});


function showAlert(message) {
    alert(message);
    location.reload(); // Reload the page after showing the alert
}

</script>




</body>
</html>
<?php include '../footer.php'?>


<?php } else{
	header("Location: ../index.php");
} ?>

