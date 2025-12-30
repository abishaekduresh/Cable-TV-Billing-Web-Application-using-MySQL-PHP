<?php 
   session_start();
   include "dbconfig.php";
   include "component.php";
   include 'preloader.php';
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
        $session_username = $_SESSION['username']; 
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Group Management</title>
    
    <!-- Premium UI Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
            --text-dark: #2b2d42;
            --text-light: #8d99ae;
            --bg-light: #f8f9fa;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-dark);
        }

        .main-container {
            padding: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Card Styles */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.25rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header-gradient h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
        }

        /* Table Styling */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }
        .table-custom tr:hover {
            background: #f8fafc;
        }
        
        /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        .modal-header {
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            border-radius: 16px 16px 0 0;
            padding: 1.25rem;
        }
        .modal-title {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1.1rem;
        }
    </style>

</head>
<body>
    
<?php
    include 'admin-menu-bar.php';
    echo '<br>';
    include 'admin-menu-btn.php';
?>

<div class="main-container container-fluid">

    <div class="custom-card">
        <div class="card-header-gradient">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                    <i class="bi bi-people-fill text-primary fs-5"></i>
                </div>
                <div>
                    <h4 class="mb-0">Group Management</h4>
                    <small class="text-muted">Manage customer groups</small>
                </div>
            </div>
            <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                <i class="bi bi-plus-lg me-2"></i>Add Group
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Group Name</th>
                            <th class="text-center">STB Count</th>
                            <th class="text-center">Phone</th>
                            <th class="text-end">Group Amt</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $query = "SELECT * FROM groupinfo WHERE group_id != '1' AND group_id != '2'";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $serial_number = 1;
                                foreach($query_run as $group)
                                {
                                    ?>
                                    <tr>
                                        <td class="text-muted small ps-4"><?= $serial_number++; ?></td>
                                        <td class="fw-bold"><?= $group['groupName']; ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-light text-dark border">
                                                <?php
                                                    $gid = $group['group_id'];
                                                    $query2 = "SELECT cusGroup FROM customer WHERE cusGroup = $gid AND rc_dc = 1";
                                                    $result2 = $con->query($query2);
                                                    echo ($result2) ? mysqli_num_rows($result2) : 0;
                                                ?>
                                            </span>
                                        </td>
                                        <td class="text-center small font-monospace"><?= $group['phone']; ?></td>
                                        <td class="text-end fw-bold text-success">₹<?= $group['billAmt']; ?></td>
                                        <td class="text-center">
                                            <button type="button" value="<?=$group['group_id'];?>" class="editStudentBtn btn btn-outline-primary btn-sm rounded-circle shadow-sm p-2 mx-1" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </button>
                                            <button type="button" value="<?=$group['group_id'];?>" class="deleteStudentBtn btn btn-outline-danger btn-sm rounded-circle shadow-sm p-2 mx-1" title="Delete">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            else
                            {
                                echo '<tr><td colspan="6" class="text-center py-5 text-muted">No groups found.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add Group Modal -->
<div class="modal fade" id="studentAddModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveStudent">
                <div class="modal-body p-4">
                    <div id="errorMessage" class="d-none alert alert-warning"></div>
                    
                    <div class="mb-3">
                        <label class="form-label">Group Name</label>
                        <input type="text" name="groupName" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bill Amount</label>
                        <input type="number" name="billAmt" class="form-control" step="0.01" />
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save Group</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Group Modal -->
<div class="modal fade" id="studentEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Group</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStudent">
                <div class="modal-body p-4">
                    <div id="errorMessageUpdate" class="d-none alert alert-warning"></div>
                    <input type="hidden" name="student_id" id="student_id" >
                    
                    <div class="mb-3">
                        <label class="form-label">Group Name</label>
                        <input type="text" name="groupName" id="groupName" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control" required/>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bill Amount</label>
                        <input type="text" name="billAmt" id="billAmt" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary fw-bold">Update Group</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ADD GROUP
    $(document).on('submit', '#saveStudent', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("save_student", true);

        $.ajax({
            type: "POST",
            url: "code-group-action.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: res.message });
                } else if(res.status == 200){
                    $('#studentAddModal').modal('hide');
                    $('#saveStudent')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { location.reload(); });
                } else if(res.status == 500) {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            }
        });
    });

    // FETCH DATA FOR EDIT
    $(document).on('click', '.editStudentBtn', function () {
        var student_id = $(this).val();
        $.ajax({
            type: "GET",
            url: "code-group-action.php?student_id=" + student_id,
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 404) {
                    Swal.fire({ icon: 'error', title: 'Not Found', text: res.message });
                } else if(res.status == 200){
                    $('#student_id').val(res.data.group_id);
                    $('#groupName').val(res.data.groupName);
                    $('#phone').val(res.data.phone);
                    $('#billAmt').val(res.data.billAmt);
                    $('#studentEditModal').modal('show');
                }
            }
        });
    });

    // UPDATE GROUP
    $(document).on('submit', '#updateStudent', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("update_group", true);

        $.ajax({
            type: "POST",
            url: "code-group-action.php",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if(res.status == 422) {
                    Swal.fire({ icon: 'warning', title: 'Validation Error', text: res.message });
                } else if(res.status == 200){
                    $('#studentEditModal').modal('hide');
                    $('#updateStudent')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { location.reload(); });
                } else if(res.status == 500) {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            }
        });
    });

    // DELETE GROUP
    $(document).on('click', '.deleteStudentBtn', function (e) {
        e.preventDefault();
        var student_id = $(this).val();
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef476f',
            cancelButtonColor: '#8d99ae',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "code-group-action.php",
                    data: {
                        'delete_group': true,
                        'student_id': student_id
                    },
                    success: function (response) {
                        var res = jQuery.parseJSON(response);
                        if (res.status == 500) {
                            Swal.fire('Error!', res.message, 'error');
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => { location.reload(); });
                        }
                    }
                });
            }
        })
    });
</script>

</body>
</html>

<?php include 'footer.php'?>

<?php }else{
	header("Location: ../index.php");
} ?>