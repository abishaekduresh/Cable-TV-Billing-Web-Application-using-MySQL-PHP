<div class="container" style="width: 80%;">
    <div class="card text-center">
        <h5 class="card-header">Paid LOC Channels List</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6">
                    <div class="align-items-center">
                        <div class="mb-3" style="width: 100%;">
                            <input type="month" class="form-control" id="due_month_year" value="<?= htmlspecialchars($currentYear . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT)) ?>">
                        </div>
                        <div class="mb-3 position-relative" style="width: 100%;">
                            <input type="text" class="form-control" id="search_channel" placeholder="Enter Channel Name" 
                                value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>">
                            <!-- Dropdown Menu -->
                            <ul class="dropdown-menu" id="dropdownMenu" style="position: absolute; top: 100%; left: 0; width: 100%; z-index: 1000;"></ul>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="created_user_id" value="<?= $_SESSION['id'];?>">
                        </div>
                    </div>
                </div>
            </div>
            <!-- Table to display response data -->
            <div id="response-table-container" style="margin-top: 20px;">
                <table class="table table-bordered" id="response-table">
                    <thead>
                        <tr>
                            <th>SNo.</th>
                            <th>Date</th>
                            <th>Gen Bill ID</th>
                            <th>Ch.UID</th>
                            <!-- <th>Ch.Name</th> -->
                            <th>Paid Amount</th>
                            <th>Discount</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
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
        var due_month_year = $('#due_month_year').val().trim();
        var created_user_id = $('#created_user_id').val().trim();
        var selectedText = $(this).text();
        var selectedUid = $(this).data('channel-uid');
        
        $('#search_channel').val(selectedText);
        $('#dropdownMenu').removeClass('show');

        var formData = {
            due_month_year: due_month_year,
            created_user_id: created_user_id,
            channel_uid: selectedUid
        };

        // console.log('Sending formData: ', JSON.stringify(formData)); // Debugging formData

        // Fetch data for the selected channel
        $.ajax({
            url: '../api/v1/loc/rpt/loc-bills.php',
            method: 'POST',
            contentType: 'application/json', // Ensure the server knows you're sending JSON
            dataType: 'json',
            data: JSON.stringify(formData),
            success: function(response) {
                console.log('Response from server: ', response); // Debugging response

                // Target the correct table (#response-table instead of #loc_table)
                $('#response-table tbody').empty();

                if (response.status === 'success' && Array.isArray(response.data) && response.data.length > 0) {
                    let sno = 1;
                    var sum_paid_amount = 0;
                    var sum_paid_discount = 0;
                    // Populate the table dynamically with response data
                    response.data.forEach(function(item) {
                        $('#response-table tbody').append(`
                            ${sum_paid_amount += item.paid_amount}
                            ${sum_paid_discount += item.paid_discount}
                            <tr>
                                <td>${sno++}</td>
                                <td>${item.bill_created_at}</td>
                                <td>${item.loc_gen_bill_id}</td>
                                <td>${item.channel_uid}</td>
                                <!--td>${item.channel_name}</td-->
                                <td>₹ ${item.paid_amount}</td>
                                <td>₹ ${item.paid_discount}</td>
                                <td>${item.remark || 'N/A'}</td>
                                <!--td>
                                    <button class="btn btn-primary btn-view btn-sm" data-id="${item.loc_gen_bill_log_id}" data-item='${JSON.stringify(item)}'>View</button>
                                </td-->
                            </tr>
                        `);
                    });
                    $('#response-table tbody').append(`
                            <tr>
                                <td colspan="4"><b>Total</b></td>
                                <td>₹ ${sum_paid_amount}</td>
                                <td>₹ ${sum_paid_discount}</td>
                                <td><b>₹ ${sum_paid_amount-sum_paid_discount}</b></td>
                            </tr>
                        `);

                } else {
                    // Handle case when no data is returned
                    $('#response-table tbody').append(`
                        <tr>
                            <td colspan="7" style="text-align: center">No data available</td>
                        </tr>
                    `);
                    console.log('No data available.');
                }
            },
            error: function(xhr, status, error) {
                console.log('Error occurred during the request: ', error);
            }
        });
    });
});

</script>