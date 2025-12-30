<?php
session_start();
require "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {
    if ($_SESSION['role'] === 'admin') {
        include 'admin-menu-bar.php';
        echo '<br>';
        include 'admin-menu-btn.php';
        $session_username = $_SESSION['username'];
    } elseif ($_SESSION['role'] === 'employee') {
        include 'menu-bar.php';
        echo '<br>';
        include 'sub-menu-btn.php';
        $session_username = $_SESSION['username'];
    }
} else {
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage POS Product</title>
     <!--Bootstrap CSS -->
     <!--<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">-->-->
    
<style>
    .custom-dropdown-menu {
    display: none;
    opacity: 0;
    transform: translateY(-10px);
    transition: opacity 1s ease, transform 1s ease;
    max-height: 200px; /* Adjust the height as needed to show 10 items */
    overflow-y: auto;
    width: 100%; /* Ensures the dropdown matches the input width */
    position: absolute;
    z-index: 1000; /* Ensures the dropdown is on top of other elements */
}

.custom-dropdown-menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
} 
</style>
</head>
<body>
    
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="text-center">Manage POS Product&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" class="btn btn-outline-primary justify-content-end" data-bs-toggle="modal" data-bs-target="#pos_product_entry_model">Add Product</button>
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row justify-content-center">
                        <div class="col-md-8 d-flex justify-content-center"> <!-- Utilizing flexbox classes -->
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" style="width: 350px;" id="search_product" placeholder="Enter product name">
                                    <!--<div class="input-group-append">-->
                                    <!--    <button class="btn btn-primary" type="button" id="sesarch_product_btn">Search</button>-->
                                    <!--</div>-->
                                </div>
                                    <!--<ul class="custom-dropdown-menu" id="dropdownMenu">-->
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Product Modal -->
<form id="pos_product_entry" method="POST" autocomplete="off">
    <div class="modal fade" id="pos_product_entry_model" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">New Product</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
                <div class="mb-3">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="product_name" placeholder="Enter product name" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="r_price" class="form-label">Retail Price</label>
                        <input type="number" class="form-control" id="r_price" min="1" placeholder="Enter sales price" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="hs_price" class="form-label">Whole Price</label>
                        <input type="number" class="form-control" id="hs_price" min="1" placeholder="Enter whole sales price" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" placeholder="Enter Stock" min="1" required>
                </div>
                <input type="hidden" class="form-control" id="username" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
                <!--<div class="d-flex justify-content-end">-->
                    <!--<button type="submit" class="btn btn-primary">Submit</button>-->
                <!--</div>-->
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <!--<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newProductConfirmModel">Add</button>-->
                <button type="submit" class="btn btn-primary" id="XXaddProductBtn">Add</button>
            <!-- You can add additional buttons here if needed -->
          </div>
        </div>
      </div>
    </div>
</form>

    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-12">
                <div class="card">
                    <!--<div class="card-header">-->
                    <!--</div>-->
                    <div class="card-body">
                        <!--<h1>Products Table</h1>-->
                        <table id="productsTable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Product Name</th>
                                    <th>Retail Price</th>
                                    <th>Wholesale Price</th>
                                    <th>Stock</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Rows will be inserted here by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
            <!--<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
          </div>
          <div class="modal-body">
            <span id="response"></span>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <!--  <button type="submit" class="btn btn-primary">Save Product</button>-->
          </div>
        </div>
      </div>
    </div>

    <!-- Update Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" method="POST" autocomplete="off">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editRetailPrice" class="form-label">Retail Price</label>
                            <input type="number" class="form-control" id="editRetailPrice" min="1" name="r_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="editWholesalePrice" class="form-label">Wholesale Price</label>
                            <input type="number" class="form-control" id="editWholesalePrice" min="1" name="hs_price" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="editStock" min="1" name="stock" required>
                        </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <input type="hidden" id="editProductId" name="product_id">
                            <button type="submit" class="btn btn-primary">Update</button>
                      <!--  <button type="submit" class="btn btn-primary">Save Product</button>-->
                      </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>-->

<script>
    // $(document).ready(function() {
    //     $('#search_product').on('input', function() {
    //         var query = $(this).val();
    //         // console.log(query);
    //         if (query.length > 1) { // Start searching after 2 characters
    //             $.ajax({
    //                 url: 'api/v1/pos/fetch-pos-product.php',
    //                 method: 'POST',
    //                 data: { query: query },
    //                 dataType: 'json',
    //                 success: function(data) {
    //                     var items = '';
    //                     if (data.error) {
    //                         items = '<li class="dropdown-item">' + data.error + '</li>';
    //                     } else {
    //                         $.each(data, function(index, item) {
    //                             items += '<li class="dropdown-item">' + item.product_name + '</li>';
    //                         });
    //                     }
    //                     $('#dropdownMenu').html(items);
    //                     $('#dropdownMenu').addClass('show'); // Show the dropdown menu
    //                 },
    //                 error: function(xhr, status, error) {
    //                     console.error(xhr.responseText);
    //                 }
    //             });
    //         } else {
    //             $('#dropdownMenu').removeClass('show'); // Hide the dropdown menu
    //         }
    //     });
    
    //     // Hide dropdown when clicking outside
    //     $(document).click(function(e) {
    //         if (!$(e.target).closest('.dropdown').length) {
    //             $('#dropdownMenu').removeClass('show');
    //         }
    //     });
    
    //     // Set input value and additional value from data attribute on item click
    //     $(document).on('click', '.dropdown-item', function() {
    //         var selectedText = $(this).text();
    //         $('#search_product').val(selectedText);
    //         $('#dropdownMenu').removeClass('show');
    //     });
    // });
</script>

<script>
    
    $(document).ready(function(){
            
        $("#pos_product_entry").submit(function(event){
            event.preventDefault();
            pos_product_entry();
        });
        
        // function closeEditProductModal() {
        // }
        
        function openNotificationModel() {
            var notificationModel = new bootstrap.Modal(document.getElementById('notificationModel'));
            notificationModel.show();
        }
        
        function pos_product_entry() {
            // console.log('pos_product_entry');
            var formData = {
                username: $("#username").val().trim(),
                product_name: $("#product_name").val().trim(),
                r_price: $("#r_price").val().trim(),
                hs_price: $("#hs_price").val().trim(),
                stock: $("#stock").val().trim()
            };
    
            // console.log(JSON.stringify(formData));
    
            $.ajax({
                type: "POST",
                url: "api/v1/pos/product.php",
                data: JSON.stringify(formData),
                contentType: "application/json",
                success: function(response) {
                    try {
                        if(response.code == 200){
                            $("#response").html(response.message);
                            openNotificationModel();
                            document.getElementById("pos_product_entry").reset();
                        } else {
                            $("#response").html(response.message);
                            openNotificationModel();
                        }
                    } catch (e) {
                        console.error('Error:', e);
                        $("#response").html('Invalid JSON response from server.');
                        openNotificationModel();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    $("#response").html('Errorw: ' + xhr.responseText);
                    openNotificationModel();
                }
            });
        }
    

$(document).on('input', '#search_product', function() {
    var search_product = document.getElementById('search_product');
    var tbody = document.querySelector('#productsTable tbody');

    // Clear table content if search input is empty
    if (search_product.value === '') {
        tbody.innerHTML = '';
        return;
    }

    // console.log(search_product.value);
    
    var inputData = {
        product_name: search_product.value,
    };
    
    function populateModal(productId, productName, rPrice, hsPrice, stock) {
        $('#editProductId').val(productId);
        $('#editProductName').val(productName);
        $('#editRetailPrice').val(rPrice);
        $('#editWholesalePrice').val(hsPrice);
        $('#editStock').val(stock);
    }
    
    // Edit button click event
    $(document).on('click', '.edit-button', function() {
        var productId = $(this).data('product-id');
        var productName = $(this).data('product-name');
        var rPrice = $(this).data('r-price');
        var hsPrice = $(this).data('hs-price');
        var stock = $(this).data('stock');

        populateModal(productId, productName, rPrice, hsPrice, stock);
    });
    
    // console.log(JSON.stringify(inputData));
    // console.log(inputData);
    
    $.ajax({
        type: "POST",
        url: "api/v1/pos/get-product.php",
        data: JSON.stringify(inputData),
        contentType: "application/json",
        success: function(response) {
            try {
                if (response.code == 200) {
                    if(response.data){
                        $("#response").html(response.message);
                        // openNotificationModel();
                        // console.log(response);
                        
                        var sno = 1;
                        // Clear previous table content
                        tbody.innerHTML = '';
    
                        // Iterate over the data array
                        response.data.forEach(function(product) {
                            // Create a new row
                            var row = document.createElement('tr');
                            
                            // Create and append cells to the row
                            var serialNoCell = document.createElement('td');
                            serialNoCell.textContent = sno++;
                            row.appendChild(serialNoCell);
                            
                            var productNameCell = document.createElement('td');
                            productNameCell.textContent = product.product_name;
                            row.appendChild(productNameCell);
                            
                            var rPriceCell = document.createElement('td');
                            rPriceCell.textContent = product.r_price;
                            row.appendChild(rPriceCell);
                            
                            var hsPriceCell = document.createElement('td');
                            hsPriceCell.textContent = product.hs_price;
                            row.appendChild(hsPriceCell);
                            
                            var stockCell = document.createElement('td');
                            stockCell.textContent = product.stock;
                            row.appendChild(stockCell);
    
                            var actionCell = document.createElement('td');
                            var editButton = document.createElement('button');
                            editButton.textContent = 'Edit';
                            editButton.classList.add('btn', 'btn-primary', 'edit-button');
                            editButton.setAttribute('data-bs-toggle', 'modal');
                            editButton.setAttribute('data-bs-target', '#editProductModal');
                            editButton.setAttribute('data-product-id', product.pos_product_id);
                            editButton.setAttribute('data-product-name', product.product_name);
                            editButton.setAttribute('data-r-price', product.r_price);
                            editButton.setAttribute('data-hs-price', product.hs_price);
                            editButton.setAttribute('data-stock', product.stock);
                            actionCell.appendChild(editButton);
                            row.appendChild(actionCell);
                            
                            // Append the row to the table body
                            tbody.appendChild(row);
                        });
                    }else{
                        $("#response").html('HELLO');
                        openNotificationModel();
                    }
                } else {
                    $("#response").html(response.message);
                    openNotificationModel();
                }
            } catch (e) {
                console.error('Error:', e);
                $("#response").html('Invalid JSON response from server.');
                openNotificationModel();
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            $("#response").html('Error: ' + xhr.responseText);
            openNotificationModel();
        }
    });
});

        $('#editProductForm').submit(function(event) {
            event.preventDefault(); // Prevent the default form submission
        
            // Convert form data to a JavaScript object
            var formDataArray = $(this).serializeArray();
            var formDataObject = {};
        
            $.map(formDataArray, function(n, i){
                formDataObject[n['name']] = n['value'];
            });
        
            // console.log(JSON.stringify(formDataObject));
        
            // AJAX call to update product
                    // var editProductModal = new bootstrap.Modal(document.getElementById('editProductModal'));
            $.ajax({
                type: "POST",
                url: "api/v1/pos/edit-product.php", // Adjust the URL according to your backend script
                data: JSON.stringify(formDataObject),
                contentType: "application/json",
                success: function(response) {
                    // Handle success response
                    // console.log(response);
                    $("#response").html(response.message);
                    // editProductModal.hide();
                    openNotificationModel();
                    document.getElementById("editProductForm").reset();
                    // Close modal or display success message
                },
                error: function(xhr, status, error) {
                    // Handle error response
                    console.error('Error:', error);
                    // Display error message
                }
            });
        });

    
    });
    
</script>
    
</body>
</html>
