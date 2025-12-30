<?php 
   session_start();
   include "dbconfig.php";
   // require 'dbconfig.php'; // Redundant
   require "component.php";
   include 'preloader.php';

    if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
        if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
            include 'admin-menu-bar.php';
            $session_username = $_SESSION['username'];
            echo '<br>';
            include 'admin-menu-btn.php';
            $session_role = 'admin';
        } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
            include 'menu-bar.php';
            $session_username = $_SESSION['username'];
             echo '<br>';
            include 'sub-menu-btn.php';
            $session_role = 'employee';
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Details</title>

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
            max-width: 100%; /* Full width as requested */
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
            padding: 0.85rem 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.85rem;
            color: #334155;
        }
        .table-custom tr:hover {
            background: #f1f5f9;
        }

        /* Form Styling */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #475569;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.6rem 1rem;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }

         /* Modal Styles */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem;
            background: #f8fafc;
            border-radius: 16px 16px 0 0;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
            background: #f8fafc;
             border-radius: 0 0 16px 16px;
        }
    </style>
</head>
<body>

<div class="main-container container-fluid">
    
    <!-- SEARCH SECTION -->
    <div class="custom-card">
        <div class="card-header-gradient">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                    <i class="bi bi-search text-primary fs-5"></i>
                </div>
                <div>
                     <h4 class="mb-0">Find Customer</h4>
                    <small class="text-muted">Search by STB No, Name, Phone, or MSO</small>
                </div>
            </div>
             <button type="button" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#studentAddModal">
                <i class="bi bi-person-plus-fill me-2"></i>Add Customer
            </button>
        </div>
        <div class="card-body p-4">
             <form action="" method="GET">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" 
                           pattern="[A-Za-z0-9\s]{3,}" required 
                           value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" 
                           placeholder="Enter at least 3 characters...">
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Search</button>
                </div>
            </form>
        </div>
    </div>

    <!-- RESULTS SECTION -->
    <div class="custom-card">
         <div class="card-header-gradient">
             <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                    <i class="bi bi-people-fill text-success fs-5"></i>
                </div>
                <h4 class="mb-0">Customer List</h4>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="myTable" class="table-custom">
                    <thead>
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Group</th>
                            <th>Status</th>
                            <th>MSO</th>
                            <th>STB No</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Area</th>
                            <th>Accessories</th>
                            <th>Description</th>
                            <th class="text-end">Amount</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if(isset($_GET['search']))
                            {
                                $filtervalues = $_GET['search'];
                                $query = "SELECT * FROM customer WHERE CONCAT(stbno,name,phone,mso) LIKE '%$filtervalues%'";
                                $query_run = mysqli_query($con, $query);

                                if(mysqli_num_rows($query_run) > 0)
                                {
                                    $serial_number = 1;
                                    foreach($query_run as $customer)
                                    {
                                        ?>
                                        <tr>
                                            <td class="text-muted small ps-4"><?= $serial_number++; ?></td>
                                            <td class="fw-bold text-primary"><?= fetchGroupName($customer['cusGroup']); ?></td>
                                            <td>
                                                <?php if($customer['rc_dc'] == 1): ?>
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2">RC</span>
                                                <?php else: ?>
                                                     <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2">DC</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><span class="badge bg-light text-dark border"><?= $customer['mso']; ?></span></td>
                                            <td class="font-monospace small"><?= $customer['stbno']; ?></td>
                                            <td class="fw-bold"><?= $customer['name']; ?></td>
                                            <td><?= $customer['phone']; ?></td>
                                            <td class="small text-muted"><?= $customer['customer_area_code']; ?></td>
                                            <td class="small"><?= $customer['accessories']; ?></td>
                                            <td class="small text-muted text-truncate" style="max-width: 150px;"><?= $customer['description']; ?></td>
                                            <td class="text-end fw-bold">₹<?= $customer['amount']; ?></td>
                                            <td class="text-center">
                                                <button type="button" value="<?=$customer['id'];?>" class="editStudentBtn btn btn-outline-primary btn-sm rounded-circle shadow-sm p-2 mx-1" title="Edit">
                                                    <i class="bi bi-pencil-fill"></i>
                                                </button>
                                                
                                                <?php if ($session_role === 'admin'): ?>
                                                <button type="button" value="<?=$customer['id'];?>" class="deleteStudentBtn btn btn-outline-danger btn-sm rounded-circle shadow-sm p-2 mx-1" title="Delete">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                else
                                {
                                    echo '<tr><td colspan="12" class="text-center py-5 text-muted">No records found matching your search.</td></tr>';
                                }
                            } else {
                                echo '<tr><td colspan="12" class="text-center py-5 text-muted">Use the search box to find customers.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- ADD CUSTOMER MODAL -->
<div class="modal fade" id="studentAddModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveStudent">
                <div class="modal-body">
                    <div id="errorMessage" class="d-none alert alert-warning"></div>
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Customer Group <span class="text-danger">*</span></label>
                            <select name="groupName" class="form-select" required>
                                <option value="" selected disabled>Select Group</option>
                                <?php
                                $query = "SELECT group_id,groupName FROM groupinfo WHERE group_id != '2'";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="'.$row['group_id'].'">'.$row['groupName'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">MSO <span class="text-danger">*</span></label>
                             <select name="mso" class="form-select" required>
                                <option selected disabled>Select MSO</option>
                                <option value="VK">VK DIGITAL</option>
                                <option value="GTPL">GTPL</option>
                                <option value="C32">C32</option>
                                <option value="VK-IPTV-C32">VK IPTV - C32</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                             <label class="form-label">Phone <span class="text-danger">*</span></label>
                             <input type="text" name="phone" class="form-control" pattern="[0-9]{10}" required placeholder="10-digit mobile number"/>
                        </div>
                         <div class="col-md-6">
                            <label class="form-label">Customer Area <span class="text-danger">*</span></label>
                            <select name="newCustomerAreaCode" id="newCustomerAreaCode" class="form-select" required>
                                <option value="" selected disabled>Select Area</option>
                                <?php
                                $query = "SELECT * FROM customer_area WHERE customer_area_status = 'Active'";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                     echo '<option value="'.$row['customer_area_code'].'">'.$row['customer_area_code'] . ' - ' .$row['customer_area_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="Full Name"/>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label">STB No <span class="text-danger">*</span></label>
                             <input type="text" name="stbno" class="form-control" required placeholder="Set Top Box Number"/>
                        </div>

                         <div class="col-md-12">
                            <label class="form-label">Remark</label>
                             <input type="text" name="description" class="form-control" placeholder="Optional notes" />
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Accessories <span class="text-danger">*</span></label>
                             <select name="add-accessories" id="add-accessories" class="form-select" required>
                                <option selected value="-">- None -</option>
                                <option value="Node">Node</option>
                                <option value="POC">POC</option>
                                <option value="FTTH">FTTH</option>
                                <option value="RF">RF</option>
                                <option value="Node + POC">Node + POC</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label">Amount <span class="text-danger">*</span></label>
                             <input type="number" name="add-amount" id="add-amount" class="form-control" required placeholder="0.00"/>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary fw-bold">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- EDIT CUSTOMER MODAL -->
<div class="modal fade" id="studentEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateStudent">
                <div class="modal-body">
                    <div id="errorMessageUpdate" class="d-none alert alert-warning"></div>
                    <input type="hidden" name="student_id" id="student_id" >
                    
                     <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">RC/DC Status <span class="text-danger">*</span></label>
                            <select name="rc_dc" id="rc_dc" class="form-select" required>
                                <option value="1">RC (Active)</option>
                                <option value="0">DC (Deactive)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                             <label class="form-label">Customer Group <span class="text-danger">*</span></label>
                            <select name="cusGroup" id="cusGroup" class="form-select" required>
                                <option value="" selected disabled>Select Group</option>
                                <?php
                                $query = "SELECT group_id,groupName FROM groupinfo WHERE group_id != '2'";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="'.$row['group_id'].'">'.$row['groupName'].'</option>';
                                }
                                ?>
                            </select>
                        </div>

                         <div class="col-md-6">
                            <label class="form-label">Customer Area <span class="text-danger">*</span></label>
                            <select name="editCustomerAreaCode" id="editCustomerAreaCode" class="form-select" required>
                                <option value="" selected disabled>Select Area</option>
                                <?php
                                $query = "SELECT * FROM customer_area WHERE customer_area_status = 'Active'";
                                $result = mysqli_query($con, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                     echo '<option value="'.$row['customer_area_code'].'">'.$row['customer_area_code'] . ' - ' .$row['customer_area_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                         <div class="col-md-6">
                            <label class="form-label">MSO <span class="text-danger">*</span></label>
                            <select name="mso" id="mso" class="form-select" required>
                                <option value="VK">VK DIGITAL</option>
                                <option value="GTPL">GTPL</option>
                            </select>
                        </div>
                        
                         <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required />
                        </div>
                         <div class="col-md-6">
                             <label class="form-label">STB No <span class="text-danger">*</span></label>
                             <input type="text" name="stbno" id="stbno" class="form-control" required/>
                        </div>

                         <div class="col-md-6">
                             <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" id="phone" class="form-control" pattern="[0-9]{10}" required />
                        </div>
                         <div class="col-md-6">
                              <label class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" required />
                        </div>

                         <div class="col-md-6">
                            <label class="form-label">Accessories <span class="text-danger">*</span></label>
                            <select name="accessories" id="accessories" class="form-select" required>
                                <option selected value="-">- None -</option>
                                <option value="Node">Node</option>
                                <option value="POC">POC</option>
                                <option value="FTTH">FTTH</option>
                                <option value="RF">RF</option>
                                <option value="Node + POC">Node + POC</option>
                            </select>
                        </div>
                         <div class="col-md-6">
                             <label class="form-label">Remark</label>
                            <input type="text" name="description" id="description" class="form-control" />
                        </div>
                     </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary fw-bold">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // ADD CUSTOMER
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
            url: "code.php?student_id=" + student_id,
            success: function (response) {
                var res = jQuery.parseJSON(response);
                if (res.status == 404) {
                    Swal.fire({ icon: 'error', title: 'Not Found', text: res.message });
                } else if (res.status == 200) {
                    var accessories = res.data.accessories.trim();
                    $('#student_id').val(res.data.id);
                    $('#cusGroup').val(res.data.cusGroup);
                    $('#editCustomerAreaCode').val(res.data.customer_area_code);
                    $('#rc_dc').val(res.data.rc_dc);
                    $('#mso').val(res.data.mso);
                    $('#stbno').val(res.data.stbno);
                    $('#name').val(res.data.name);
                    $('#phone').val(res.data.phone);
                    $('#accessories').val(accessories); 
                    $('#description').val(res.data.description);
                    $('#amount').val(res.data.amount);
                    $('#studentEditModal').modal('show');
                }
            }
        });
    });

    // UPDATE CUSTOMER
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
                if(res.status == 200) {
                    $('#studentEditModal').modal('hide');
                    $('#updateStudent')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function (xhr, status, error) {
                 Swal.fire({ icon: 'error', title: 'System Error', text: 'An error occurred: ' + error });
            }
        });
    });

    // DELETE CUSTOMER
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
                    url: "code.php",
                    data: {
                        'delete_student': true,
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
        });
    });
</script>

</body>
</html>

<?php include 'footer.php'?>

<?php }else{
	header("Location: index.php");
} ?>