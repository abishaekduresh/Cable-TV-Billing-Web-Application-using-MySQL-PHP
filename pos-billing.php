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

// $query = "SELECT product_name, pos_product_id FROM pos_product ORDER BY product_name ASC ";
$query = "SELECT product_name, pos_product_id FROM pos_product WHERE stock > 0 ORDER BY product_name ASC";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Billing System</title>
      <style>
        /* Custom styles for the toggle slider */
        .toggle-slider {
          width: 60px;
          height: 34px;
          position: relative;
        }
    
        .toggle-slider input {
          opacity: 0;
          width: 0;
          height: 0;
        }
    
        .toggle-slider .slider {
          position: absolute;
          cursor: pointer;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
          background-color: #ccc;
          transition: .4s;
          border-radius: 34px;
        }
    
        .toggle-slider .slider:before {
          position: absolute;
          content: "";
          height: 26px;
          width: 26px;
          left: 4px;
          bottom: 4px;
          background-color: white;
          transition: .4s;
          border-radius: 50%;
        }
    
        .toggle-slider input:checked + .slider {
          background-color: #2196F3;
        }
    
        .toggle-slider input:focus + .slider {
          box-shadow: 0 0 1px #2196F3;
        }
    
        .toggle-slider input:checked + .slider:before {
          transform: translateX(26px);
        }
    
        /* Rounded sliders */
        .toggle-slider .slider.round {
          border-radius: 34px;
        }
    
        .toggle-slider .slider.round:before {
          border-radius: 50%;
        }
        
    .text-center {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
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

<!--<h1 style="text-align:center">Testing</h1>-->

<div class="container-lg mt-5">

    <!-- Form for POS product entry -->
    <form id="pos_billing" method="POST" autocomplete="off">
        <!-- Customer details -->
        <div class="row mb-3">
            <div class="col-md-2">
                <b><div id="statusText">Retail</div></b>
                <label class="toggle-slider">
                  <input type="checkbox" id="toggleBtn">
                  <span class="slider"></span>
                </label>
            </div>
            <div class="col-md-5">
                <label for="cus_phone" class="form-label">Customer Phone</label>
                <input type="number" class="form-control cus_phone" id="cus_phone" placeholder="Enter phone number" >
                <ul class="custom-dropdown-menu" id="dropdownMenu">
            </div>
            <div class="col-md-5">
                <label for="cus_name" class="form-label">Customer Name</label>
                <input type="text" class="form-control" id="cus_name" placeholder="Enter customer name" >
            </div>
        </div>
        <!-- Product table -->
        <table id="productTable" class="table table-bordered">
            <thead>
                <tr>
                    <th style="width:20px">S.No.</th>
                    <th style="width:350px">Product Name <span style="color: red;">*</span></th>
                    <th style="width:30px"></th>
                    <th style="width:100px">Price</th>
                    <th style="width:80px">Stock</th>
                    <th style="width:100px">Qty</th>
                    <th style="width:100px">Total</th>
                    <th style="width:100px">Remove</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?= $sno=1; ?></td>
                    <td>
                        <select class="form-select product_id_box" name="product_id[]" >
                            <option>Select Product</option>
                            <?php 
                            foreach ($result as $row) {
                                echo '<option value="' . $row["pos_product_id"] . '">' . $row["product_name"] . '</option>';
                            }
                            ?>
                        </select>
                    </td>
                    <td><input type="hidden" class="form-control pos_product_id" name="pos_product_id[]" required></td>
                    <td><input type="number" readonly class="form-control price" name="price[]" required></td>
                    <td><input type="number" readonly class="form-control stock" name="stock[]" required></td>
                    <td><input type="number" class="form-control qty" name="qty[]" min="1" max="99" required></td>
                    <td><input type="number" readonly class="form-control total" name="total[]" required></td>
                    <td><button class="btn btn-danger deleteRow">Delete</button></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-end"><b>Total</b></td>
                    <td colspan="3"><input type="text" readonly class="form-control" id="grand_total"></td>
                </tr>
                <!--<tr>-->
                <!--    <td colspan="6" class="text-end"><b>Discount</b></td>-->
                <!--    <td colspan="3"><input type="text" class="form-control" id="discount" value="10"></td>-->
                <!--</tr>-->
                <tr>
                    <td><button id="addRow" class="btn btn-primary">Add Row</button></td>
                    <td colspan="5"></td>
                    <input type="hidden" readonly class="form-control" id="username" value="<?= $_SESSION['username']; ?>">
                    <input type="hidden" readonly class="form-control" id="timestamp" value="<?= $currentDateTime ?>">
                    <td class="text-end">
                        <!--<button type="button" class="btn btn-primary" onlick="invoicePDFModel.show();">Reprint</button>-->
                    </td>
                    <td><button type="button" class="btn btn-primary" id="confirmSubmitBtn">Submit</button></td>
                </tr>
            </tfoot>
        </table>
        <!-- Scrollable modal -->
        <div class="modal fade" id="paymentModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <!--<div class="modal fade" id="paymentModel" data-bs-backdrop="static" data-bs-keyboa/rd="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true"-->
          <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <label>Total</label>
                                <input type="text" readonly class="form-control grand_total_2" id="grand_total_2">
                            </div>
                            <div class="col">
                                <label>Discount</label>
                                <input type="text" class="form-control discount" id="discount" value="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label>Payable</label>
                                <input type="text" readonly class="form-control payable_amount" id="payable_amount">
                            </div>
                            <div class="col">
                                <label>Received Amount</label>
                                <input type="text" class="form-control received_amount" id="received_amount">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="exampleSelect" class="form-label">Select Payment Mode</label>
                                <select class="form-select pay_mode" id="pay_mode" required>
                                    <!--<option selected>Select a payment mode</option>-->
                                    <option value="1">Cash</option>
                                    <option value="2">Gpay</option>
                                    <option value="3">Paytm</option>
                                    <!-- <option value="4">Credit</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label>Balance</label>
                                <input type="text" readonly class="form-control payable_amount" id="balance_amount">
                            </div>
                        </div>
                        <div id="payErrorMsg" style="color: red; display: none;">Balance cannot be negative.</div>
                    </div>
                </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" id="payBtn" class="btn btn-primary" >Pay</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Vertically centered modal -->
        <div class="modal fade" id="confirmModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <span>Are you sure?</span>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Make Bill</button>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Invoice PDF Modal -->
        <div class="modal fade" id="invoicePDFModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg ">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Invoice PDF Print</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!--<iframe src="prtposinvoice.php?token=<?php echo htmlspecialchars('<span id="bill_token"></span>'); ?>" width="100%" height="300" style="border:none;"></iframe>-->
                    <iframe id="invoiceIframe" width="100%" height="300" style="border:none;"></iframe>
                    <span id="bill_token"></span>
              </div>
            </div>
          </div>
        </div>
    </form>
</div>
<!--<button type="submit" class="btn btn-primary">Submit</button>-->
<!-- Modal -->
<div class="modal fade" id="notificationModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span id="response"></span>
            </div>
        </div>
    </div>
</div>


<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
<!--<script src="lib/js/deselect.js"></script>-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>-->

<script>
    $(document).ready(function() {
        var sno = 1;
        $('#cus_phone').on('input', function() {
            var query = $(this).val();
            if (query.length > 1) { // Start searching after 2 characters
                $.ajax({
                    url: 'api/v1/fetch-customer.php',
                    method: 'POST',
                    dataType: 'json',
                    data: { query: query },
                    success: function(data) {
                        var items = '';
                        if (data.error) {
                            items = '<li class="dropdown-item">' + data.error + '</li>';
                        } else {
                            $.each(data, function(index, item) {
                                // Include both phone numbers and names in the dropdown items
                                items += '<li class="dropdown-item" data-name="' + item.name + '">'+ item.phone + '</li>';
                            });
                            sno = 1;
                        }
                        $('#dropdownMenu').html(items);
                        $('#dropdownMenu').addClass('show'); // Show the dropdown menu
                    }
                });
            } else {
                $('#dropdownMenu').removeClass('show'); // Hide the dropdown menu
            }
        });
    
        // Hide dropdown when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.dropdown').length) {
                $('#dropdownMenu').removeClass('show');
            }
        });
    
        // Set input value and additional value from data attribute on item click
        $(document).on('click', '.dropdown-item', function() {
            var selectedText = $(this).text();
            var selectedName = $(this).data('name'); // Retrieve additional value (name) from data attribute
            // console.log(selectedText); // Log selected phone number
            // console.log(selectedName); // Log selected name
            $('#cus_phone').val(selectedText);
            $('#cus_name').val(selectedName); // Optionally, set the name in another input field
            $('#dropdownMenu').removeClass('show');
        });
    
        // Animation handling for hiding the dropdown
        $('#dropdownMenu').on('transitionend', function(e) {
            if (!$(this).hasClass('show')) {
                $(this).css('display', 'none');
            }
        });
    
        // Ensure dropdown is displayed on adding the show class
        $('#dropdownMenu').on('addClass', function(e) {
            if ($(this).hasClass('show')) {
                $(this).css('display', 'block');
            }
        });
    });
</script>
    
<script>
    $(document).ready(function(){ 
        
        const toggleBtn = document.getElementById('toggleBtn');
        const statusText = document.getElementById('statusText');
        const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
        const payBtn = document.getElementById('payBtn');
        // const payMode = document.getElementById('payMode');
        payBtn.style.display = 'none';
        const payErrorMsg = document.getElementById('payErrorMsg');
        const notificationModel = new bootstrap.Modal(document.getElementById('notificationModel'));
        const confirmModel = new bootstrap.Modal(document.getElementById('confirmModel'));
        const invoicePDFModel = new bootstrap.Modal(document.getElementById('invoicePDFModel'));
        const paymentModel = new bootstrap.Modal(document.getElementById('paymentModel'));
        const bill_token = document.getElementById("bill_token");
        

    
        confirmSubmitBtn.addEventListener('click', function() {
            paymentModel.show();
            // console.log('Submit Btn');
        });
    
        payBtn.addEventListener('click', function() {
            paymentModel.hide();
            confirmModel.show();
            // console.log('Pay Btn');
        });
    
        $('#discount').on('change', function() {
            var grand_total_2 = parseFloat($("#grand_total_2").val());
            var discount = parseFloat($("#discount").val());
            var payable_amount = grand_total_2 - discount;
            $("#payable_amount").val(payable_amount.toFixed(2));
        });
        
        $('#received_amount, #pay_mode').on('change', function() {
            var payable_amount = parseFloat($("#payable_amount").val());
            var received_amount = parseFloat($("#received_amount").val());
            var balance_amount = received_amount - payable_amount;
            $("#balance_amount").val(balance_amount.toFixed(2));
            var pay_mode_val = $("#pay_mode").val();
            // console.log('#paymode:  ',pay_mode_val);
            if (balance_amount < 0) {
                if(pay_mode_val != 4){
                    payBtn.style.display = 'none';
                    payErrorMsg.style.display = 'block';
                } else {
                    payBtn.style.display = 'block';
                    payErrorMsg.style.display = 'none';
                }
            } else {
                payBtn.style.display = 'block';
                payErrorMsg.style.display = 'none';
            }
        });
        
        $("#pos_billing").submit(function(event){
            event.preventDefault();
            pos_billing();
        });
        
        
        function calPriceQty(event) {
            var $row = $(event.target).closest("tr");
            var price = Number($row.find(".price").val());
            var qty = Number($row.find(".qty").val());
            var total = price * qty;
            $row.find(".total").val(total);
            grand_total();
        }
        
        var r_or_hs = 1; // Retail
            // console.log(r_or_hs);
        
        toggleBtn.addEventListener('change', function() {
            
            $("body .price, .qty").each(function() {
                calPriceQty({target: this});
            });
    
            if (!this.checked) {
                statusText.textContent = 'Hole Sale';
                r_or_hs = 0;
            } else {
                statusText.textContent = 'Retail';
                r_or_hs = 1;
            }
        
            // Loop through each row in the table
            $('#productTable tbody tr').each(function() {
                var $row = $(this); // Get the current row
                var productId = $row.find('select[name="product_id[]"]').val();
        
                // Perform AJAX request to fetch product details
                $.ajax({
                    type: 'POST',
                    url: 'api/v1/pos/fetch-product.php',
                    data: JSON.stringify({ product_id: productId }),
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            alert(response.error);
                        } else {
                            // Update price and stock inputs in the same row
                            if (statusText.textContent != 'Hole Sale') {
                                $row.find('.price').val(response.r_price);
                                r_or_hs = 1;
                                // console.log('Retail Value');
                            } else {
                                $row.find('.price').val(response.hs_price);
                                r_or_hs = 0;
                                // console.log('Hole Value');
                            }
                            $row.find('.stock').val(response.stock);
                            $row.find('.pos_product_id').val(response.pos_product_id);
                            var qty = parseFloat($row.find('.qty').val());
                            var stock = parseFloat(response.stock);
            
                            // Check if quantity exceeds stock
                            if (qty > stock) {
                                // Hide the button and display a message
                                $('#confirmSubmitBtn').hide();
                                alert('Quantity exceeds available stock!');
                            } else {
                                // Show the button if the quantity is within the stock limit
                                $('#confirmSubmitBtn').show();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                        console.error('Response:', xhr.responseText);
                    }
                });
            });
        });
        
        // $('#productTable').on('change', 'select[name="product_id[]"]', function() {
        //     var $row = $(this).closest('tr'); // Get the closest row
        //     var productId = $(this).val(); // Get selected product ID
        
        //     const statusText1 = document.getElementById('statusText');
        
        //     // Perform AJAX request to fetch product details
        //     $.ajax({
        //         type: 'POST',
        //         url: 'api/v1/pos/fetch-product.php',
        //         data: JSON.stringify({ product_id: productId }),
        //         dataType: 'json',
        //         success: function(response) {
        //             if (response.error) {
        //                 alert(response.error);
        //             } else {
        //                 // Update price and stock inputs in the same row
        //                 if (statusText.textContent != 'Hole Sale') {
        //                     $row.find('.price').val(response.r_price);
        //                     // console.log('Retail Value');
        //                 } else {
        //                     $row.find('.price').val(response.hs_price);
        //                     // console.log('Hole Value');
        //                 }
        //                 $row.find('.stock').val(response.stock);
        //                 $row.find('.pos_product_id').val(response.pos_product_id);
        //                 calPriceQty({target: $row.find(".price")[0]});
                
        //                 // Fetch the quantity value
        //                 var qty = parseFloat($row.find(".qty").val());
        //                 var stock = parseFloat(response.stock);
        
        //                 // Check if quantity exceeds stock
        //                 if (qty > stock) {
        //                     // Hide the button and display a message
        //                     $('#confirmSubmitBtn').hide();
        //                     alert('Quantity exceeds available stock!');
        //                 } else {
        //                     // Show the button if the quantity is within the stock limit
        //                     $('#confirmSubmitBtn').show();
        //                 }
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error:', status, error);
        //             console.error('Response:', xhr.responseText); // Log the response text for debugging
        //         }
        //     });
        // });
        
        $('#productTable').on('change', 'select[name="product_id[]"], input.qty', function() {
            $('#productTable tbody tr').each(function() {
                var $row = $(this); // Get the current row
                var productId = $row.find('select[name="product_id[]"]').val(); // Get selected product ID
        
                const statusText1 = document.getElementById('statusText');
        
                // Perform AJAX request to fetch product details
                $.ajax({
                    type: 'POST',
                    url: 'api/v1/pos/fetch-product.php',
                    data: JSON.stringify({ product_id: productId }),
                    dataType: 'json',
                    success: function(response) {
                        if (response.error) {
                            alert(response.error);
                        } else {
                            // Update price and stock inputs in the same row
                            if (statusText1.textContent !== 'Hole Sale') {
                                $row.find('.price').val(response.r_price);
                                r_or_hs = 1;
                            } else {
                                $row.find('.price').val(response.hs_price);
                                r_or_hs = 0;
                            }
                            $row.find('.stock').val(response.stock);
                            $row.find('.pos_product_id').val(response.pos_product_id);
                            calPriceQty({target: $row.find(".price")[0]});
        
                            var qty = parseFloat($row.find(".qty").val());
                            var stock = parseFloat(response.stock);
        
                            // Check if quantity exceeds stock
                            if (qty > stock) {
                                // Hide the button and display a message
                                $('#confirmSubmitBtn').hide();
                                alert('Quantity exceeds available stock!');
                                return false; // Exit the .each() loop
                            } else {
                                // Show the button if the quantity is within the stock limit
                                $('#confirmSubmitBtn').show();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                        console.error('Response:', xhr.responseText); // Log the response text for debugging
                    }
                });
            });
        });

        
        var maxRows = 12;
        var currentRows = $('#productTable tbody tr').length;
        var sno = currentRows + 1;
        
        $('#addRow').click(function(event) {
            event.preventDefault();
        
            if (currentRows < maxRows) {
                $('#productTable tbody').append(`
                    <tr>
                        <td>${sno++}</td>
                        <td>
                            <select class="form-select product_id_box" name="product_id[]">
                                <option value="">Select Product</option>
                                <?php 
                                foreach ($result as $row) {
                                    echo '<option value="' . $row["pos_product_id"] . '">' . $row["product_name"] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                        <td><input type="hidden" class="form-control pos_product_id" name="pos_product_id[]" required></td>
                        <td><input type="number" readonly class="form-control price" name="price[]" required></td>
                        <td><input type="number" readonly class="form-control stock" name="stock[]" required></td>
                        <td><input type="number" class="form-control qty" name="qty[]" min="1" max="99" required></td>
                        <td><input type="number" readonly class="form-control total" name="total[]" required></td>
                        <td><button class="btn btn-danger deleteRow">Delete</button></td>
                    </tr>
                `);
        
                currentRows++;
            } else {
                $('#response').text('Maximum limit reached (12 rows).');
                $('#notificationModel').modal('show');
            }
        });
        
        // Delete Row button click event
        $(document).on('click', '.deleteRow', function() {
            if (confirm("Are You Sure?")) {
                $(this).closest('tr').remove();
                currentRows--;
                updateSerialNumbers();
            }
        });
        
        // Update total and grand total on input changes
        $("body").on("keyup change", ".price, .qty", function(event) {
            calPriceQty(event);
        });
    
        // Attach change event to the slider checkbox
        // $("#toggleBtn").change(function(){
        //     // Call the calculateTotals function to update totals when slider changes
        //     $("body .price, .qty").each(function(){
        //         calPriceQty({target: this});
        //     });
        // });

        function updateSerialNumbers() {
            $('#productTable tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        function grand_total(){
            var tot = 0;
            $(".total").each(function(){
                tot += Number($(this).val());
            });
            $("#grand_total").val(tot.toFixed(2));
            $("#grand_total_2").val(tot.toFixed(2));
            $("#payable_amount").val(tot.toFixed(2));
        }
        
        $('.cus_name, .cus_phone').on('change', function() {
            var cusName = $("#cus_name").val();
            var cusPhone = $("#cus_phone").val();
            // var cusName: ($("#cus_phone").val() !== '') ? $("#cus_name").val() : '-',
            // var cusPhone: ($("#cus_phone").val() !== '') ? $("#cus_phone").val() : '0',
            document.getElementById("cusName").textContent = cusName;
            document.getElementById("cusPhone").textContent = cusPhone;
        });
        
        function pos_billing() {
            
            var formData = {
                username: $("#username").val(),
                timestamp: $("#timestamp").val(),
                cus_name: ($("#cus_name").val().trim() !== '') ? $("#cus_name").val().trim() : '-',
                cus_phone: ($("#cus_phone").val() !== '') ? $("#cus_phone").val() : '0',
                discount: $("#discount").val(),
                pay_mode: $("#pay_mode").val(),
                // pay_mode: pay_mode_val,
                r_or_hs: (r_or_hs == '1') ? '1' : '0',
                // cus_phone: cusPhone,
                discount: $("#discount").val().trim(),
                items: []
            };
                                // console.log('posBilling()', r_or_hs);
            
            $("#pos_billing").find('tr').each(function() {
              var pos_product_id = $(this).find('input[name="pos_product_id[]"]').val();
              var price = $(this).find('input[name="price[]"]').val();
              var stock = $(this).find('input[name="stock[]"]').val();
              var qty = $(this).find('input[name="qty[]"]').val();
              var total = $(this).find('input[name="total[]"]').val();
            
              // Check if any of the required fields (e.g., pos_product_id, qty) are empty
              if (pos_product_id && qty) {
                var item = {
                  pos_product_id: pos_product_id,
                  price: price,
                  stock: stock,
                  qty: qty,
                  total: total
                };
                formData.items.push(item);
              }
            });
            
            // console.log(JSON.stringify(formData.items));
             console.log(JSON.stringify(formData));

            $.ajax({
                type: "POST",
                url: "api/v1/pos/billing.php",
                data: JSON.stringify(formData),
                contentType: "application/json",
                success: function(response) {
                    $("#response").html(response.message);
                    if(response.code == 200) {
                        confirmModel.hide();
                        
                        // Ensure the element with ID "bill_token" exists
                        var bill_token = document.getElementById("bill_token");
                        if (bill_token) {
                            bill_token.innerText = response.data.token;
            
                            // Set the iframe src with the token
                            var iframe = document.getElementById("invoiceIframe");
                            if (iframe) {
                                iframe.src = "prtposinvoice.php?id=" + encodeURIComponent(response.data.pos_bill_id);
                            } else {
                                console.error("Iframe with ID 'invoiceIframe' not found.");
                            }
                        } else {
                            console.error("Element with ID 'bill_token' not found.");
                        }
                        
                        // Show invoice PDF modal
                        invoicePDFModel.show();
                        document.getElementById("pos_billing").reset();
                    } else {
                    	//$("#response").html(response);
                        //notificationModel.show();
						console.log(response);
                    }
                },
                error: function(xhr, status, error) {
                        confirmModel.hide();
                    console.error('Error:', status);
                    console.error('Error:', error);
                    $("#response").html(error);
                    notificationModel.show();
                }
            });


            // $.ajax({
            //     type: "POST",
            //     url: "api/v1/pos/billing.php",
            //     data: JSON.stringify(formData),
            //     contentType: "application/json",
            //     success: function(response) {
            //         $("#response").html(response.message);
            //         if(response.code == 200){
            //             confirmModel.hide();
            //             console.log(response.data.token);
            //             bill_token.innerText = response.data.token;
            //             // notificationModel.show();
            //             invoicePDFModel.show();
            //             document.getElementById("pos_billing").reset();
            //         }else{
            //             notificationModel.show();
            //         }
            //     },
            //     error: function(xhr, status, error) {
            //         console.error('Error:', status);
            //         console.error('Error:', error);
            //         $("#response").html(error);
            //         notificationModel.show();
            //     }
            // });
        }

    });
</script>
</body>
</html>
