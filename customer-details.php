<?php 
   session_start();
   include "dbconfig.php";
   require 'dbconfig.php';
   require "component.php";

    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        $session_username = $_SESSION['username']; 
?>

<?php
if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';
    $session_role = 'admin';
} elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
    include 'menu-bar.php';
    $session_role = 'employee';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details/Action</title>
</head>
<body >

<!----------------------Ajax Add Customer---Popup model------------------------->

<div class="modal fade" id="studentAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveStudent">
                <div class="modal-body">
                    <div id="errorMessage" class="alert alert-warning d-none"></div>
                    
                    <label for="selectBox" class="form-label">Select an Group: *</label>
                    <select style="font-weight: bold;" name="groupName" class="form-select" required>
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                
                                                $query = "SELECT group_id,groupName FROM groupinfo WHERE group_id != '2'";
                                                $result = mysqli_query($con, $query);
                                                
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $optionValueID = $row['group_id'];
                                                    $optionValue = $row['groupName'];
                                                    ?>
                                                    <option value="<?php echo $optionValueID; ?>"><?php echo $optionValue;?></option>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </select>
    
                    <label for="selectBox" class="form-label">Select an MSO: *</label>
                    <select style="font-weight: bold;" name="mso" class="form-select" required>
                      <option style="font-weight: bold;" selected disabled>Select ...</option>
                      <option style="font-weight: bold;" value="VK">VK DIGITAL</option>
                      <option style="font-weight: bold;" value="GTPL">GTPL</option>
                    </select>

    
                    <div class="mb-3">
                        <label for="stbno">STB No *</label>
                        <input style="font-weight: bold;" type="text" name="stbno" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="name">Name *</label>
                        <input style="font-weight: bold;" type="text" name="name" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="phone">Phone *</label>
                        <input style="font-weight: bold;" type="text" name="phone" class="form-control" pattern="[0-9]{10}" required />
                    </div>
                    <div class="mb-3">
                        <label for="description">Remark</label>
                        <input style="font-weight: bold;" type="text" name="description" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label for="amount">Amount *</label>
                        <input style="font-weight: bold;" type="text" name="amount" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!----------------------Ajax Edit Customer---Popup model------------------------->

<div class="modal fade" id="studentEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Customer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateStudent">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="student_id" id="student_id" >

                <label for="selectBox" class="form-label">Select RC/DC Status: *</label>
                <select style="font-weight: bold;" name="rc_dc" id="rc_dc" class="form-select" required>
                  <!--<option style="font-weight: bold;" selected disabled>Select ...</option>-->
                  <option style="font-weight: bold;" value="1" selected>RC</option>
                  <option style="font-weight: bold;" value="0">DC</option>
                </select>

                <label for="selectBox" class="form-label">Select Group: *</label>
                <select style="font-weight: bold;" name="cusGroup" id="cusGroup" class="form-select" required>
                                                <option value="" selected disabled>Select</option>
                                                <?php
                                                
                                                $query = "SELECT group_id,groupName FROM groupinfo WHERE group_id != '2'";
                                                $result = mysqli_query($con, $query);
                                                
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    $optionValueID = $row['group_id'];
                                                    $optionValue = $row['groupName'];
                                                    ?>
                                                    <option value="<?php echo $optionValueID; ?>"><b><?php echo $optionValue; ?></b></option>
                                                    <?php
                                                }
                                                
                                                ?>
                                            </select>

                <label for="selectBox" class="form-label">Select MSO: *</label>
                <select style="font-weight: bold;" name="mso" id="mso" class="form-select" required>
                  <!--<option style="font-weight: bold;" selected disabled>Select ...</option>-->
                  <option style="font-weight: bold;" value="VK" selected>VK DIGITAL</option>
                  <option style="font-weight: bold;" value="GTPL">GTPL</option>
                </select>


                <div class="mb-3">
                        <label for="stbno">STB No *</label>
                        <input style="font-weight: bold;" type="text" name="stbno" id="stbno" class="form-control" required/>
                </div>
                <div class="mb-3">
                        <label for="name">Name *</label>
                        <input style="font-weight: bold;" type="text" name="name" id="name" class="form-control" required />
                </div>
                <div class="mb-3">
                        <label for="phone">Phone *</label>
                        <input style="font-weight: bold;" type="text" name="phone" id="phone" class="form-control" pattern="[0-9]{10}" required />
                </div>
                <div class="mb-3">
                        <label for="description">Remark</label>
                        <input style="font-weight: bold;" type="text" name="description" id="description" class="form-control" />
                </div>
                <div class="mb-3">
                        <label for="amount">Amount *</label>
                        <input style="font-weight: bold;" type="text" name="amount" id="amount" class="form-control" required />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Customer</button>
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


<!------------------------Search Customer Model-------------------------------->

<div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <h4>Customer Details/Action
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                                Add Customer
                            </button>
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">

                                <form action="" method="GET">
                                    <div class="input-group mb-3">
                                        <input type="text" name="search" required value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="STB No, Name, Phone">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="table-responsive">
                        <table id="myTable" class="table table-hover" border="5">
                            <thead>
                                <tr>
                                    <th>S.No.</th>
                                    <th>Group</th>
                                    <th>RC/DC</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    //$con = mysqli_connect("localhost","root","","phptutorials");

                                    if(isset($_GET['search']))
                                    {
                                        $filtervalues = $_GET['search'];
                                        $query = "SELECT * FROM customer WHERE CONCAT(stbno,name,phone) LIKE '%$filtervalues%'";
                                        $query_run = mysqli_query($con, $query);

                                        if(mysqli_num_rows($query_run) > 0)
                                        {
                                            $serial_number = 1;

                                            foreach($query_run as $customer)
                                            {
                                                
                                                ?>
                                                <tr>
                                                    <td style="width: 18px; font-size: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                    <td style="font-size: 18px; font-weight: bold;">
                                                        <?= fetchGroupName($customer['cusGroup']); ?>
                                                    </td>
                                                    <td style="font-size: 18px; font-weight: bold;">
                                                    <?php
                                                        $rc_dc_status = $customer['rc_dc'];
                                                        if($rc_dc_status == 1){
                                                            echo 'RC';
                                                        }else{
                                                            echo 'DC';
                                                        }
                                                    ?>
                                                    </td>
                                                    <td style="font-size: 18px; font-weight: bold;"><?= $customer['mso']; ?></td>
                                                    <td style="font-size: 18px; font-weight: bold;"><?= $customer['stbno']; ?></td>
                                                    <td style="font-size: 18px; font-weight: bold;"><?= $customer['name']; ?></td>
                                                    <td style="font-size: 18px; font-weight: bold;"><?= $customer['phone']; ?></td>
                                                    <td style="font-size: 18px; font-weight: bold;"><?= $customer['description']; ?></td>
                                                    <td style="font-size: 18px; font-weight: bold;"><?= $customer['amount']; ?></td>
                                                    <td>
                                                        <button type="button" value="<?=$customer['id'];?>" class="editStudentBtn btn btn-success btn-sm">Edit</button>
                                                        <form action="code.php" method="POST" class="d-inline">
                                                            <!--<button type="submit" name="delete_customer" value="<?=$customer['id'];?>" class="deleteStudentBtn btn btn-danger btn-sm" -->
                                                            <button type="submit" name="delete_customer" value="<?=$customer['id'];?>" class="deleteStudentBtn btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" 
                                                                <?php if ($session_role !== 'admin') {
                                                                    echo 'disabled'; 
                                                                    } elseif ($session_role == 'employee'){
                                                                        echo 'disabled'; 
                                                                    }?>
                                                            >Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                                <tr>
                                                    <td colspan="4">No Record Found</td>
                                                </tr>
                                            <?php
                                        }
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
                url: "code.php",
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

          setTimeout(function() {
            location.reload();
          }, 600);

                    }else if(res.status == 500) {
                        alert(res.message);
                    }
                }
            });

        });

        // $(document).on('click', '.editStudentBtn', function () {

        //     var student_id = $(this).val();
        //     // alert(student_id);
            
        //     $.ajax({
        //         type: "GET",
        //         url: "code.php?student_id=" + student_id,
        //         success: function (response) {

        //             var res = jQuery.parseJSON(response);
        //             if(res.status == 404) {

        //                 alert(res.message);
        //             }else if(res.status == 200){

                        // $('#student_id').val(res.data.id);
                        // $('#stbno').val(res.data.stbno);
                        // $('#name').val(res.data.name);
                        // $('#phone').val(res.data.phone);
                        // $('#description').val(res.data.description);
                        // $('#amount').val(res.data.amount);

        //                 $('#studentEditModal').modal('show');
        //             }

        //         }
        //     });

        // });
        
$(document).on('click', '.editStudentBtn', function () {
    var student_id = $(this).val();
    
    $.ajax({
        type: "GET",
        url: "code.php?student_id=" + student_id,
        success: function (response) {
            var res = jQuery.parseJSON(response);
            if (res.status == 404) {
                alert(res.message);
            } else if (res.status == 200) {
                $('#student_id').val(res.data.id);
                $('#cusGroup').val(res.data.cusGroup);
                $('#rc_dc').val(res.data.rc_dc);
                $('#mso').val(res.data.mso);
                $('#stbno').val(res.data.stbno);
                $('#name').val(res.data.name);
                $('#phone').val(res.data.phone);
                $('#description').val(res.data.description);
                $('#amount').val(res.data.amount);

                $('#studentEditModal').modal('show');
            }
        }
    });
});

        
        
        $(document).on('submit', '#updateStudent', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("update_student", true);

            $.ajax({
                type: "POST",
                url: "code.php",
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
                url: "code.php",
                data: {
                  'delete_student': true,
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
                    location.reload();
                  }
                }
              });
        
              // Close the Bootstrap modal
              $('#exampleModal').modal('hide');
            });
          });

    </script>

</body>
</html>

<?php include 'footer.php'?>


<?php }else{
	header("Location: index.php");
} ?>