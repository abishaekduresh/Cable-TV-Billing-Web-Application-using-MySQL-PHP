<!-- <div class="container" style="width: 80%;">
    <div class="card text-center">
        <h5 class="card-header">Channel List</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-6">
                    <div class="align-items-center">
                        <div class="mb-3 " style="width: 100%;">
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
        <div class="card-footer text-muted">
            2 days ago
        </div>
    </div>
</div> -->


<div class="container mt-5">
    <h2 style="text-align: center;">Channel List</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>UID</th>
                <th>CH.Name</th>
                <th>Prop.Name</th>
                <th>Phone</th>
                <th>Net.Amt</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody id="data-table-body">
            <!-- Data will be inserted here -->
        </tbody>
    </table>
</div>



<script>
$(document).ready(function() {
    $.ajax({
        url: '../api/v1/loc/get-all-channels.php', // URL to your PHP API
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.length === 0) {
                $('#data-table-body').append('<tr><td colspan="3" class="text-center">No records found</td></tr>');
            } else {
                data.forEach(function(item) {
                    $('#data-table-body').append(`
                        <tr>
                            <td>${item.channel_uid}</td>
                            <td>${item.channel_name}</td>
                            <td>${item.prop_name}</td>
                            <td>${item.prop_phone}</td>
                            <td>${item.network_amount}</td>
                            <td>
                                ${item.status == 1 ? '<h6 class="text-success">Active</h6>' : '<h6 class="text-danger">Inactive</h6>'}
                            </td>
                        </tr>
                    `);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            $('#data-table-body').append('<tr><td colspan="3" class="text-center">Error fetching data</td></tr>');
        }
    });
});
</script>