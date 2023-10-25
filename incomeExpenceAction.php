<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
   
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
    <title>Group Options</title>
  <!-- Add Bootstrap CSS link -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    
<?php

    include 'admin-menu-bar.php';
    ?><br><?php
    include 'admin-menu-btn.php';

?>
<!-- <br/> -->
<!-- CATEGORY -->
<!----------------------Ajax Add Category---Popup model------------------------->

<div class="modal fade" id="categoryAddModal" tabindex="-1" aria-labelledby="exampleCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleCategoryModalLabel">Add Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveCategory">
            <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">Category</label>
                    <input type="text" name="category" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="">Loc</label>
                    <!-- <input type="text" name="subcategory" class="form-control" /> -->
                    <select name="in_ex" id="in_ex" class="form-select">
                        <option value="select" selected disabled>Select</option>
                        <option value="Expense">Expense</option>
                        <option value="Income">Income</option>
                        <option value="Both">Both</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Category</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!----------------------Ajax Edit Group---Popup model------------------------->

<div class="modal fade" id="categoryEditModal" tabindex="-1" aria-labelledby="exampleCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleCategoryModalLabel">Edit Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updateCategory">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="category_id" id="category_id" >

                <div class="mb-3">
                    <label for="">Category</label>
                    <input type="text" name="category" id="category" class="form-control" />
                </div>
                <div class="mb-3">
                    <label for="">Loc</label>
                    <!-- <input type="text" name="subcategory" class="form-control" /> -->
                    <select name="in_ex" id="in_ex" class="form-select">
                        <option value="select" selected disabled>Select</option>
                        <option value="Expense">Expense</option>
                        <option value="Income">Income</option>
                        <option value="Both">Both</option>
                    </select>
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

<div class="modal fade" id="exampleCategoryModal" tabindex="-1" aria-labelledby="exampleCategoryModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleCategoryModalLabel">Confirmation</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this data?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmDeleteCategoryBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- // Category -         END                 ----------->

<!-- SubCategory ---------------------------------------------------------------Start -->

<!----------------------Ajax Add Sub subcategory---Popup model------------------------->

<div class="modal fade" id="subcategoryAddModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add Sub Category</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="saveSubCategory">
            <div class="modal-body">
                <div id="errorMessage" class="alert alert-warning d-none"></div>
                <div class="mb-3">
                    <label for="">Category</label>
                    <!-- <input type="text" name="subcategory" class="form-control" /> -->
                    <select name="category_id" class="form-select">
                        <option value="select" selected disabled>Select</option>
                        <?php
                            $query = "SELECT * FROM in_ex_category";
                            $result = mysqli_query($con, $query);
                            $selectedValue = isset($_GET['subcategory']) ? $_GET['subcategory'] : ''; // Get the selected value from the URL

                            while ($row = mysqli_fetch_assoc($result)) {
                                $optionValueID = $row['category_id'];
                                $optionValue = $row['category'];
                        ?>
                        <option value="<?php echo $optionValueID; ?>" <?php if ($optionValue === $selectedValue) echo 'selected'; ?>><?php echo $optionValue; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Sub Category</label>
                    <input type="text" name="subcategory" class="form-control" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Sub Category</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!----------------------Ajax Edit Group---Popup model------------------------->

<div class="modal fade" id="subcategoryEditModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit SubCategory</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="updatesubcategory">
            <div class="modal-body">

                <div id="errorMessageUpdate" class="alert alert-warning d-none"></div>

                <input type="hidden" name="subcategory_id" id="subcategory_id" >
                <div class="mb-3">
                    <label for="">Category</label>
                    <!-- <input type="text" name="subcategory" class="form-control" /> -->
                    <select name="category_id" class="form-select">
                        <option value="select" selected disabled>Select</option>
                        <?php
                            $query = "SELECT * FROM in_ex_category";
                            $result = mysqli_query($con, $query);
                            $selectedValue = isset($_GET['category_id']) ? $_GET['category_id'] : ''; // Get the selected value from the URL

                            while ($row = mysqli_fetch_assoc($result)) {
                                $optionValueID = $row['category_id'];
                                $optionValue = $row['category'];
                        ?>
                        <option value="<?php echo $optionValueID; ?>" 
                        <?php if ($optionValueID === $selectedValue) echo 'selected'; ?>><?php echo $optionValue; ?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="">Sub Category</label>
                    <input type="text" name="subcategory" id="subcategory" class="form-control" required/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update subcategory</button>
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
        <button type="button" class="btn btn-danger" id="confirmDeleteSubCategoryBtn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- // SubCategory -->
<!-- ---------------- -->
<h1 align="center">Income/Expence Category's</h1>

  <div class="container" style="margin-top: 10px;">
    <!-- Nav tabs -->
    <ul class="nav nav-pills justify-content-center">
      <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#SubCategory"><b>Add SubCategory</b></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-bs-toggle="pill" href="#Category"><b>Add Category</b></a>
      </li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content">

      <div class="tab-pane container active" id="SubCategory">
        <div class="container">
          <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Sub Category List</b>
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#subcategoryAddModal">
                                Add Sub Category
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
                                    <th>SubCategory</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $query = "SELECT *
                                    FROM in_ex_category
                                    INNER JOIN in_ex_subcategory ON in_ex_category.category_id = in_ex_subcategory.category_id
                                    ";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $in_ex1)
                                        {
                                            ?>
                                            <tr>
                                                <td style="width: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="font-weight: bold;"><?= $in_ex1['category']; ?></td>
                                                <td style="font-weight: bold;"><?= $in_ex1['subcategory']; ?></td>
                                                <td>
                                                    <button type="button" value="<?=$in_ex1['subcategory_id'];?>" class="editSubCategoryBtn btn btn-success btn-sm">Edit</button>
                                                        <form action="code-in_ex_subcategory.php" method="POST" class="d-inline">
                                                            <button type="submit" name="delete_subcustomer" value="<?=$in_ex1['subcategory_id'];?>" class="deleteSubCategoryBtn btn btn-danger btn-sm " data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                            Delete
                                                    </button>
                                                        </form>
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


        </div>
      </div>

      <!-- Add the tab content for 'Category' here if needed -->
      <div class="tab-pane container" id="Category">
        <div class="container">
          <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Category List</b>
                            <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#categoryAddModal">
                                Add Category
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
                                    <th>Loc</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                    $query = "SELECT * FROM in_ex_category";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        $serial_number = 1;

                                        foreach($query_run as $in_ex2)
                                        {
                                            ?>
                                            <tr>
                                                <td style="width: 18px; font-weight: bold;"><?= $serial_number++; ?></td>
                                                <td style="width: 350px; font-weight: bold;"><?= $in_ex2['category']; ?></td>
                                                <td style="width: 350px; font-weight: bold;"><?= $in_ex2['in_ex']; ?></td>
                                                <td>
                                                    <button type="button" value="<?=$in_ex2['category_id'];?>" class="editCategoryBtn btn btn-success btn-sm">Edit</button>
                                                        <form action="code-in_ex_category.php" method="POST" class="d-inline">
                                                            <button type="submit" name="delete_category" value="<?=$in_ex2['category_id'];?>" class="deleteCategoryBtn btn btn-danger btn-sm " data-bs-toggle="modal" data-bs-target="#exampleCategoryModal">
                                                            Delete
                                                    </button>
                                                        </form>
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

        </div>
      </div>

    </div>
  </div>


<script>
// Category
$(document).on('submit', '#saveCategory', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("save_category", true);

            $.ajax({
                type: "POST",
                url: "code-in_ex_category.php",
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
                        $('#categoryAddModal').modal('hide');
                        $('#saveCategory')[0].reset();

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


$(document).on('click', '.editCategoryBtn', function () {

var category_id = $(this).val();

$.ajax({
    type: "GET",
    url: "code-in_ex_category.php?category_id=" + category_id,
    success: function (response) {

        var res = jQuery.parseJSON(response);
        if(res.status == 404) {

            alert(res.message);
        }else if(res.status == 200){

            $('#category_id').val(res.data.category_id);
            $('#category').val(res.data.category);
            $('#in_ex').val(res.data.in_ex);

            $('#categoryEditModal').modal('show');
        }

    }
});

});

$(document).on('submit', '#updateCategory', function (e) {
e.preventDefault();

var formData = new FormData(this);
formData.append("update_category", true);

$.ajax({
    type: "POST",
    url: "code-in_ex_category.php",
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
            
            $('#categoryEditModal').modal('hide');
            $('#updateCategory')[0].reset();

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


$(document).on('click', '.deleteCategoryBtn', function (e) {
            e.preventDefault();
            var category_id = $(this).val();
            
            // Open the Bootstrap modal
            $('#exampleCategoryModal').modal('show');
        
            $('#confirmDeleteCategoryBtn').click(function() {
              // Send the AJAX request to delete the category
              $.ajax({
                type: "POST",
                url: "code-in_ex_category.php",
                data: {
                  'delete_category': true,
                  'category_id': category_id
                },
                success: function (response) {
                  var res = jQuery.parseJSON(response);
                  if (res.status == 500) {
                    alert(res.message);
                  } else {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);
        
                    // Reload the page after successful deletion

                    var newURL = location.href + '#category';
                        location.assign(newURL);
                            location.reload();

            // $('#myTable').load(location.href + " #myTable");

                    // setTimeout(function() {
                    //         location.reload();
                    //     }, 200);

                  }
                }
              });
        
              // Close the Bootstrap modal
              $('#exampleCategoryModal').modal('hide');
            });
          });

//////////////

////////// SubCategory

$(document).on('submit', '#saveSubCategory', function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("save_subcategory", true);

            $.ajax({
                type: "POST",
                url: "code-in_ex_subcategory.php",
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
                        $('#subcategoryAddModal').modal('hide');
                        $('#saveSubCategory')[0].reset();

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


$(document).on('click', '.editSubCategoryBtn', function () {

var subcategory_id = $(this).val();

$.ajax({
    type: "GET",
    url: "code-in_ex_subcategory.php?subcategory_id=" + subcategory_id,
    success: function (response) {

        var res = jQuery.parseJSON(response);
        if(res.status == 404) {

            alert(res.message);
        }else if(res.status == 200){

            $('#subcategory_id').val(res.data.subcategory_id);
            $('#category_id').val(res.data.category_id);
            $('#subcategory').val(res.data.subcategory);

            $('#subcategoryEditModal').modal('show');
        }

    }
});

});

$(document).on('submit', '#updateSubCategory', function (e) {
e.preventDefault();

var formData = new FormData(this);
formData.append("update_subcategory", true);

$.ajax({
    type: "POST",
    url: "code-in_ex_subcategory.php",
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
            
            $('#subcategoryEditModal').modal('hide');
            $('#updateSubCategory')[0].reset();

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


$(document).on('click', '.deleteSubCategoryBtn', function (e) {
            e.preventDefault();
            var subcategory_id = $(this).val();
            
            // Open the Bootstrap modal
            $('#exampleSubCategoryModal').modal('show');
        
            $('#confirmDeleteSubCategoryBtn').click(function() {
              // Send the AJAX request to delete the category
              $.ajax({
                type: "POST",
                url: "code-in_ex_subcategory.php",
                data: {
                  'delete_subcategory': true,
                  'subcategory_id': subcategory_id
                },
                success: function (response) {
                  var res = jQuery.parseJSON(response);
                  if (res.status == 500) {
                    alert(res.message);
                  } else {
                    alertify.set('notifier', 'position', 'top-right');
                    alertify.success(res.message);
        
                    // Reload the page after successful deletion

                    var newURL = location.href + '#category';
                        location.assign(newURL);
                            location.reload();

            // $('#myTable').load(location.href + " #myTable");

                    // setTimeout(function() {
                    //         location.reload();
                    //     }, 200);

                  }
                }
              });
        
              // Close the Bootstrap modal
              $('#exampleSubCategoryModal').modal('hide');
            });
          });

//////////////

</script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <!-- Add Bootstrap and Popper.js scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php include 'footer.php'?>



<?php }else{
	header("Location: index.php");
} ?>