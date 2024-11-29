<div class="container" style="width: 80%;">
    <div class="card">
        <h5 class="card-header">Generate Due Bill</h5>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="mb-3">
                            <ul>
                                <li>Current Date: <?= $currentDate ?></li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <ul>
                                <li>Only one time you can generate bills in a month</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column align-items-center">
                        <div class="mb-3" style="width: 100%;">
                            <input type="month" class="form-control" id="due_month_year" value="<?= htmlspecialchars($currentYear . '-' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT)) ?>">
                        </div>
                        <div class="mb-3">
                            <input type="hidden" class="form-control" id="created_user_id" value="<?= $_SESSION['id'];?>">
                            <button type="button" class="btn btn-primary" id="submit-btn">Submit</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Event listener for the submit button
        $('#submit-btn').click(function() {
            // Collect form data
            var due_month_year = $('#due_month_year').val().trim();
            var created_user_id = $('#created_user_id').val().trim();

            // Validate form data (optional)
            if (!due_month_year) {
                alert('Please fill in all required fields.');
                return;
            }

            // Prepare data to send
            var formData = {
                due_month_year: due_month_year,
                created_user_id: created_user_id
            };

            // console.log(JSON.stringify(formData));

            // Ajax POST request
            $.ajax({
                url: '../api/v1/loc/bill/gen-bills.php',  // Replace with your API endpoint
                method: 'POST',
                contentType: 'application/json',
                dataType: 'json',
                data: JSON.stringify(formData),  // Send the data as JSON
                success: function(response) {
                    if(response.code == "200"){
                        alertify.set('notifier','position', 'top-right');
                        alertify.set('notifier','delay', 5); // Time in seconds
                        alertify.success(response.message);
                    }else{
                        alertify.set('notifier','position', 'top-right');
                        alertify.set('notifier','delay', 5); // Time in seconds
                        alertify.error(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error('Error:', error);
                    alertify.set('notifier','position', 'top-right');
                    alertify.set('notifier','delay', 5); // Time in seconds
                    alertify.success('There was an error submitting the form.');
                }
            });
        });
    });
</script>
