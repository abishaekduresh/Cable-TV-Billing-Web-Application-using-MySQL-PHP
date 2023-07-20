<?php 
   session_start();
   include "dbconfig.php";
//    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'employee') { 
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
        $session_username = $_SESSION['username']; 
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add incomeExpenceAction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
<?php

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>
<br/>

<!----------------------Ajax Add Group---Popup model------------------------->

<div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Category/Sub Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveStudent">
            <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">Category</label>
                    <input type="text" name="category" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="">Sub Category</label>
                    <input type="text" name="subcategory" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Group</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!----------------------Ajax Edit Group---Popup model------------------------->

<div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateStudent">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="student_id" id="student_id" >

                <div class="mb-3">
                    <label for="">Category</label>
                    <input type="text" name="category" id="category" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="">Sub Category</label>
                    <input type="text" name="subcategory" id="subcategory" class="form-control" required/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Category</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!----------------------Ajax Delete Customer---Popup model------------------------->

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this data?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!---------    last 5 group print   --------------->

<br>
    <hr class="mt-0 mb-4">

<div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Group List</b>
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                                Add Group
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                        <table class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $query = "SELECT * FROM incomeExpenceinfo WHERE category != 'All'";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $group)
                                        {
                                            ?>
                                            <tr>
                                                <td style="width: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="width: 110px; font-weight: bold;"><center><?= $group['category']; ?></center></td>
                                                <td style="width: 180px; font-weight: bold;"><center><?= $group['subcategory']; ?></center></td>
                                                <td><center>
                                                    <button type="button" value="<?=$group['id'];?>" class="editStudentBtn btn btn-success btn-sm">Edit</button>
                                                        <form action="codegroupaction.php" method="POST" class="d-inline">
                                                            <!--<button type="submit" name="delete_customer" value="<?=$group['id'];?>" class="deleteStudentBtn btn btn-danger btn-sm" -->
                                                            <button type="submit" name="delete_customer" value="<?=$group['id'];?>" class="deleteStudentBtn btn btn-danger btn-sm disabled" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            Delete
                                                    </button>
                                                        </form></center>
                                                    </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<script>

$(document).on('submit', '#saveStudent', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("save_student", true);

            $.ajax({
                type: "POST",
                url: "codeincomeExpenceAction.php",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    
                    var res = jQuery.parseJSON(response);
                    if(res.status == 422) {
                        $('#errorMessage').removeClass('d-none');
                        $('#errorMessage').text(res.message);

                    }else if(res.status == 200){

                        $('#errorMessage').addClass('d-none');
                        $('#studentAddModal').modal('hide');
                        $('#saveStudent')[0].reset();

                        alertify.set('notifier','position', 'top-right');
                        alertify.success(res.message);

                        // $('#myTable').load(location.href + " #myTable");
    
                        setTimeout(function() {
                location.reload();
            }, 200);

                    }else if(res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });


$(document).on('click', '.editStudentBtn', function () {

var student_id = $(this).val();

$.ajax({
    type: "GET",
    url: "codeincomeExpenceAction.php?student_id=" + student_id,
    success: function (response) {

        var res = jQuery.parseJSON(response);
        if(res.status == 404) {

            alert(res.message);
        }else if(res.status == 200){

            $('#student_id').val(res.data.id);
            $('#category').val(res.data.category);
            $('#subcategory').val(res.data.subcategory);

            $('#studentEditModal').modal('show');
        }

    }
});

});

$(document).on('submit', '#updateStudent', function (e) {
e.preventDefault();

var formData = new FormData(this);
formData.append("update_group", true);

$.ajax({
    type: "POST",
    url: "codeincomeExpenceAction.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
        
        var res = jQuery.parseJSON(response);
        if(res.status == 422) {
            $('#errorMessageUpdate').removeClass('d-none');
            $('#errorMessageUpdate').text(res.message);

        }else if(res.status == 200){

            $('#errorMessageUpdate').addClass('d-none');

            alertify.set('notifier','position', 'top-right');
            alertify.success(res.message);
            
            $('#studentEditModal').modal('hide');
            $('#updateStudent')[0].reset();

            // $('#myTable').load(location.href + " #myTable");


            setTimeout(function() {
                location.reload();
            }, 200);

        }else if(res.status == 500) {
            alert(res.message);
        }
    }
});

});


$(document).on('click', '.deleteStudentBtn', function (e) {
            e.preventDefault();
            var student_id = $(this).val();
            
            // Open the Bootstrap modal
            $('#exampleModal').modal('show');
        
            $('#confirmDeleteBtn').click(function() {
              // Send the AJAX request to delete the student
              $.ajax({
                type: "POST",
                url: "codeincomeExpenceAction.php",
                data: {
                  'delete_group': true,
                  'student_id': student_id
                },
                success: function (response) {
                  var res = jQuery.parseJSON(response);
                  if (res.status == 500) {
                    alert(res.message);
                  } else {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);
        
                    // Reload the page after successful deletion
                    // location.reload();

                    setTimeout(function() {
                            location.reload();
                        }, 200);

                  }
                }
              });
        
              // Close the Bootstrap modal
              $('#exampleModal').modal('hide');
            });
          });
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>



<?php }else{
	header("Location: ../index.php");
} ?>