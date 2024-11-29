<div class="container" style="width: 100%;">

    <div class="card">

        <h5 class="card-header">New Bill</h5>

        <div class="card-body">

            <div class="d-flex justify-content-center">

                <div class="input-group mb-3" style="width: 50%; position: relative;"> <!-- Make this relative for dropdown positioning -->

                    <input type="text" class="form-control" id="search_channel" placeholder="Enter Channel Name" 

                           value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">

                    <ul class="dropdown-menu" id="dropdownMenu" style="position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;"></ul> <!-- Ensure positioning -->

                </div>

            </div>

        </div>

    </div>

</div>



<div class="container mt-5">

    <div class="card">

        <!-- <h5 class="card-header">New Bill</h5> -->

        <div class="card-body table-responsive">

            <table class="table table-hover" id="loc_table">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Due Date</th>

                        <th>CUID</th>

                        <th>Channel Name</th>

                        <th>Balance</th>

                        <th>Remark</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    <!-- <tr>

                        <td><?= $sno++ ?></td>

                        <td>A</td>

                        <td>B</td>

                        <td>C</td>

                        <td>D</td>

                        <td>E</td>

                        <td>

                            <a href="customer-history.php?search=<?= $customer['stbno']; ?>" target="_blank">

                                <img src="../assets/arrow-up-right-from-square-solid.svg" width="20px" height="20px">

                            </a>

                        </td>

                    </tr> -->

                </tbody>

            </table>

        </div>

    </div>

</div>



<!-- <div class="modal fade" id="billingModel" tabindex="-1" aria-labelledby="billingModelLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="billingModelLabel">Modal Title</h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body" id="modalBody">

                

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

            </div>

        </div>

    </div>

</div> -->



<div class="modal fade" id="billingModel" tabindex="-1" aria-labelledby="billingModelLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="billingModelLabel">LOC Billing | Due Date: <span id="due_month_year"></span></h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>

            <div class="modal-body" id="modalBody">

                <form id="locBillingForm" autocomplete="none">

                    <div class="container">

                        <div class="row align-items-center">

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="loc_gen_bill_id" class="form-label">loc_gen_bill_id</label>

                                    <input type="number" class="form-control" id="loc_gen_bill_id" readonly>

                                </div>

                            </div>

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="channel_uid" class="form-label">Channel UID</label>

                                    <input type="text" class="form-control" id="channel_uid" readonly>

                                </div>

                            </div>

                            <div class="col-4">

                                <div class="mb-3">

                                    <label for="channel_name" class="form-label">Channel Name</label>

                                    <input type="text" class="form-control" id="channel_name" readonly>

                                </div>

                            </div>

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="due_amount" class="form-label">Due Amount</label>

                                    <input type="number" class="form-control" id="due_amount" readonly>

                                </div>

                            </div>

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="paid_amount" class="form-label">Paid Amount</label>

                                    <input type="number" class="form-control" id="paid_amount" readonly>

                                </div>

                            </div>

                        </div>



                        <div class="row align-items-center">

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="paid_discount" class="form-label">Paid Discount</label>

                                    <input type="number" class="form-control" id="paid_discount" readonly>

                                </div>

                            </div>

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="balance_amount" class="form-label">Balance Amount</label>

                                    <input type="number" class="form-control" id="balance_amount" readonly>

                                </div>

                            </div>

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="pay_amount" class="form-label">Pay Amount</label>

                                    <input type="number" max="6000" class="form-control" id="pay_amount">

                                </div>

                            </div>

                            <div class="col-2">

                                <div class="mb-3">

                                    <label for="pay_discount" class="form-label">Pay Discount</label>

                                    <input type="number" max="6000" class="form-control" id="pay_discount" value="0">

                                </div>

                            </div>

                            <div class="col-4">

                                <div class="mb-3">

                                    <label for="pay_mode_dropdown" class="form-label">Pay Mode</label>

                                    <select id="pay_mode_dropdown" class="form-select">

                                        <option value="">Select Pay Mode</option>

                                    </select>

                                </div>

                            </div>

                        </div>



                        <div class="row align-items-center">

                            <div class="col-8">

                                <div class="mb-3">

                                    <p id="output"></p>

                                </div>

                            </div>

                            <div class="col-4">

                                <div class="mb-3">

                                    <label for="remark" class="form-label">remark</label>

                                    <textarea class="form-control" id="remark" rows="2" placeholder="Enter your remark"></textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

            <div class="modal-footer">

                <!-- <input type="hidden" class="form-control" id="due_month_year" value="<?= $_SESSION['id'];?>"> -->

                <input type="hidden" class="form-control" id="created_user_id" value="<?= $_SESSION['id'];?>">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

                <button type="button" class="btn btn-primary" id="loc_billing_submit_btn">Submit</button>

            </div>

        </div>

    </div>

</div>



<div class="container d-flex justify-content-center align-items-center">

	<h4>Total Balance: </h4>&nbsp;<h4 id="balance">0</h4>

</div>



<script>

    $(document).ready(function() {



        var billingModel = new bootstrap.Modal(document.getElementById('billingModel'));

        // billingModel.show();



        const loc_billing_submit_btn = document.getElementById('loc_billing_submit_btn');

        const payAmount = document.getElementById('pay_amount');

        const payDiscount = document.getElementById('pay_discount');

        const balanceAmount = document.getElementById('balance_amount');

        const dueAmount = document.getElementById('due_amount');

        const due_month_year = document.getElementById('due_month_year');



        const output = document.getElementById('output');

        // document.getElementById('output').innerText = 'Input Value: ' + inputValue;

        loc_billing_submit_btn.style.display = 'none';



        // Add event listeners for both input fields

        payAmount.addEventListener('input', updateOutput);

        payDiscount.addEventListener('input', updateOutput);



        function updateOutput() {

            const payAmountValue = parseFloat(payAmount.value) || 0;  // Parse as float or default to 0

            const payDiscountValue = parseFloat(payDiscount.value) || 0; // Parse as float or default to 0

            const balanceAmountValue = parseFloat(balanceAmount.value) || 0; // Parse as float or default to 0

            const dueAmountValue = parseFloat(dueAmount.value) || 0; // Parse as float or default to 0

            const finalAmountCheck = (balanceAmountValue - payAmountValue) - payDiscountValue;

            // Show the button if either value is greater than 0

            if (payAmountValue >= 0) {

                if(payDiscountValue >= 0 && finalAmountCheck >= 0){

                    if(dueAmountValue >= finalAmountCheck){

                        loc_billing_submit_btn.style.display = 'block'; // Show the button

                    }else{

                        loc_billing_submit_btn.style.display = 'none'; // Hide the button

                    }

                } else {

                    loc_billing_submit_btn.style.display = 'none'; // Hide the button

                }

            } else {

                loc_billing_submit_btn.style.display = 'none'; // Hide the button

            }

            output.innerText = `finalAmountCheck: ${finalAmountCheck}`;

        }



        var sno = 1;



        // Event listener for input field changes

        $('#search_channel').on('input', function() {

            var query = $(this).val().trim();



            // Check if query length is more than 1 character

            if (query.length > 1) {

                $.ajax({

                    url: '../api/v1/loc/fetch-loc-channels.php',

                    method: 'POST',

                    dataType: 'json',

                    data: { query: query },

                    success: function(data) {

                        var items = '';

                        if (data.error) {

                            items = '<li class="dropdown-item">' + data.error + '</li>';

                        } else {

                            $.each(data, function(index, item) {

                                items += '<li class="dropdown-item" data-channel-uid="' + item.channel_uid + '">' + item.channel_name + '</li>';

                            });

                        }

                        $('#dropdownMenu').html(items).addClass('show');

                    },

                    error: function(xhr, status, error) {

                        console.log("Error occurred: " + error);

                    }

                });

            } else {

                $('#dropdownMenu').removeClass('show');

            }

        });



        // Hide dropdown when clicking outside

        $(document).click(function(e) {

            if (!$(e.target).closest('.input-group').length) {

                $('#dropdownMenu').removeClass('show');

            }

        });



        // Set input value and fetch data when dropdown item is clicked

        $(document).on('click', '.dropdown-item', function() {

            var selectedText = $(this).text();

            var selectedUid = $(this).data('channel-uid');

            

            $('#search_channel').val(selectedText);

            $('#dropdownMenu').removeClass('show');

            // console.log(JSON.stringify({ channel_uid: selectedUid }));

            // Fetch data for the selected channel

            $.ajax({

                url: '../api/v1/loc/bill/get-pending-due.php',

                method: 'POST',

                dataType: 'json',

                data: JSON.stringify({ channel_uid: selectedUid }),

                success: function(response) {

                    $('#loc_table tbody').empty();



                    if (response.status === 'success' && Array.isArray(response.data) && response.data.length > 0) {

                        let sno = 1;



                        // Populate the table dynamically with response data

                        var sumBalance = 0;

                        response.data.forEach(function(item) {

                            let balance = item.due_amount - item.paid_amount - item.paid_discount;

                            $('#loc_table tbody').append(`
                                <tr>
                                    <td>${sno++}</td>
                                    <td>${item.due_month}-${item.due_year}</td>
                                    <td>${item.channel_uid}</td>
                                    <td>${item.channel_name}</td>
                                    <td>${item.due_amount - item.paid_amount - item.paid_discount || 0}</td>
                                    <td>${item.remark || 'N/A'}</td>
                                    ${item.due_amount - item.paid_amount - item.paid_discount !== 0 ? `
                                        <td>
                                            <button class="btn btn-primary btn-view btn-sm" data-id="${item.loc_gen_bill_log_id}" data-item='${JSON.stringify(item)}'>View</button>
                                        </td>
                                    ` : '<td><h4>Paid</h4></td>'}
                                </tr>
                            `);

                            sumBalance += balance;

							$('#balance').text(sumBalance);

                            // console.log(sumBalance);

                        });



                        // Attach click event listener to view buttons

                        attachViewButtonClickListener();

                    } else {

                        // Handle case when no data is returned

                        $('#loc_table tbody').append(`

                            <tr>

                                <td colspan="7" style="text-align: center">No data available</td>

                            </tr>

                        `);

                        // alert('No record found');

                    }

                },

                error: function(xhr, status, error) {

                    console.log("Error occurred: " + error);

                }

            });

        });





        // AJAX request to fetch pay modes

        $.ajax({

            url: '../api/v1/get-pay-mode.php', // Ensure this path is correct

            method: 'GET',

            dataType: 'json',

            success: function(response) {

                // Check if there is an error in the response

                if (response.error) {

                    alert(response.error);

                } else {

                    // Populate the dropdown with pay mode names and ids

                    response.forEach(function(payMode) {

                        $('#pay_mode_dropdown').append(

                            '<option value="' + payMode.pay_mode_id + '">' + payMode.name + '</option>'

                        );

                    });

                }

            },

            error: function(xhr, status, error) {

                // Show a general error message if AJAX fails

                alert('Error: ' + error);

            }

        });

    

        // document.getElementById('pay_amount, pay_discount').addEventListener('input', function() {

        //     const pay_amount = pay_amount;            

        //     const pay_discount = this.pay_discount;   

        //     document.getElementById('output').innerText = 'Input Value: ' + inputValue;

        // });



        // Function to attach click event listener to view buttons

        function attachViewButtonClickListener() {

            $('.btn-view').off('click').on('click', function() {

                // Get the row data from the button's data-item attribute

                const rowData = $(this).data('item');

                // console.log(rowData);

                if (rowData) {

                    // Set values in modal input fields

                    let balance_amount = (rowData.due_amount - rowData.paid_amount - rowData.paid_discount);

                    $('#loc_gen_bill_id').val(rowData.loc_gen_bill_id);

                    $('#channel_uid').val(rowData.channel_uid);

                    $('#channel_name').val(rowData.channel_name);

                    $('#due_amount').val(rowData.due_amount);

                    $('#paid_amount').val(rowData.paid_amount);

                    $('#balance_amount').val(balance_amount);

                    $('#paid_discount').val(rowData.paid_discount);

                    due_month_year.innerText = `${rowData.due_month}-${rowData.due_year}`;

                    // $('#remark').val(rowData.remark);



                    // Show the billing modal

                    billingModel.show();

                } else {

                    console.error('No rowData available.');

                }

            });

        }



        $('#loc_billing_submit_btn').click(function(event) {

            // Prevent default form submission

            event.preventDefault();

            

            // Gather form data

            const formData = {

                created_user_id: $('#created_user_id').val().trim(),

                loc_gen_bill_id: $('#loc_gen_bill_id').val().trim(),

                channel_uid: $('#channel_uid').val().trim(),

                due_amount: $('#due_amount').val().trim(),

                paid_amount: $('#paid_amount').val().trim(),

                paid_discount: $('#paid_discount').val().trim(),

                balance_amount: $('#balance_amount').val().trim(),

                pay_amount: $('#pay_amount').val().trim(),

                pay_discount: $('#pay_discount').val().trim(),

                pay_mode: $('#pay_mode_dropdown').val().trim(),

                remark: $('#remark').val().trim(),

                due_month_year: $('#due_month_year').text().trim(),

            };



            // Stringify the data

            const jsonData = JSON.stringify(formData);

            console.log(jsonData);



            // Send data to the API using AJAX

            $.ajax({

                url: '../api/v1/loc/bill/new-bill.php', // Replace with your API endpoint

                type: 'POST',

                contentType: 'application/json',

                data: jsonData,

                success: function(response) {

                    // Handle success

                    console.log('Data sent successfully:', response);

                    if(response.code === "200"){

                        billingModel.hide();

                        alertify.set('notifier','position', 'top-right');

                        alertify.set('notifier','delay', 5); // Time in seconds

                        alertify.success(response.message);

                        setTimeout(function() {

                            window.location.reload();

                        }, 3000); // 5000 milliseconds = 5 seconds



                    }else{

                        // billingModel.hide();

                        alertify.set('notifier','position', 'top-right');

                        alertify.set('notifier','delay', 5); // Time in seconds

                        alertify.error(response.message);

                        // setTimeout(function() {

                        //     window.location.reload();

                        // }, 2000); // 5000 milliseconds = 5 seconds

                    }

                },

                error: function(xhr, status, error) {

                    // Handle error

                    console.error('Error sending data:', error);

                    // setTimeout(function() {

                    //     window.location.reload();

                    // }, 1000); // 5000 milliseconds = 5 seconds

                }

            });

        });

    });

</script>



