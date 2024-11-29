<div class="container" style="width: 80%;">
    <div class="card">
        <h5 class="card-header">New Channel</h5>
        <div class="card-body">
            <div class="row">
                <!-- First Column -->
                <div class="col-md-6">
                    <!-- <div class="p-3 border bg-light"> -->
                    <div class="mb-3">
                        <label class="form-label">Channel Name</label>
                        <input type="text" class="form-control" id="channel_name" placeholder="Enter channel name">
                    </div>
                    <!-- </div> -->
                </div>
                <!-- Second Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Prop Name</label>
                        <input type="text" class="form-control" id="prop_name" placeholder="Enter prop name">
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- First Column -->
                <div class="col-md-6">
                    <!-- <div class="p-3 border bg-light"> -->
                    <div class="mb-3">
                        <label class="form-label">Prop Phone</label>
                        <input type="number" class="form-control" id="prop_phone" placeholder="Enter prop phone">
                    </div>
                    <!-- </div> -->
                </div>
                <!-- Second Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Network Amount</label>
                        <input type="number" class="form-control" id="network_amount" placeholder="Enter network amount">
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- First Column -->
                <div class="col-md-6">
                    <!-- <div class="p-3 border bg-light"> -->
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" rows="5" id="prop_address"></textarea>
                    </div>
                    <!-- </div> -->
                </div>
                <!-- Second Column -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Remark</label>
                        <textarea class="form-control" rows="5" id="remark"></textarea>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end" style="width: 100%;">
                <input type="hidden" class="form-control" id="created_user_id" value="<?= $_SESSION['id'];?>">
                <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Event listener for the submit button
        $('#submit-btn').click(function() {
            // Collect form data
            var channelName = $('#channel_name').val().trim();
            var propName = $('#prop_name').val().trim();
            var propPhone = $('#prop_phone').val().trim();
            var networkAmount = $('#network_amount').val().trim();
            var prop_address = $('#prop_address').val().trim();
            var remark = $('#remark').val().trim();
            var created_user_id = $('#created_user_id').val().trim();

            // Validate form data (optional)
            if (!channelName || !propName || !propPhone || !networkAmount || !prop_address) {
                alertify.set('notifier','position', 'top-right');
                alertify.set('notifier','delay', 5); // Time in seconds
                alertify.error('Please fill in all required fields.');
                return;
            }

            // Prepare data to send
            var formData = {
                channel_name: channelName,
                prop_name: propName,
                prop_phone: propPhone,
                network_amount: networkAmount,
                prop_address: prop_address,
                remark: remark,
                created_user_id: created_user_id
            };

            // console.log(JSON.stringify(formData));

            // Ajax POST request
            $.ajax({
                url: '../api/v1/loc/create-new-channel.php',  // Replace with your API endpoint
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),  // Send the data as JSON
                success: function(response) {
                    // Handle success
                    // console.log('Response:', response);
                    // alert(response.message);
                    // Clear the input fields after successful submission
                    $('#channel_name').val('');
                    $('#prop_name').val('');
                    $('#prop_phone').val('');
                    $('#network_amount').val('');
                    $('#prop_address').val('');
                    $('#remark').val('');
                    alertify.set('notifier','position', 'top-right');
                    alertify.set('notifier','delay', 5); // Time in seconds
                    alertify.success(response.message);
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error:', error);
                    alertify.set('notifier','position', 'top-right');
                    alertify.set('notifier','delay', 5); // Time in seconds
                    alertify.error('There was an error submitting the form.');
                }
            });
        });
    });
</script>