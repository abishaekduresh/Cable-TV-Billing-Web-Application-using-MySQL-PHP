<?php 
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

// ✅ Check admin session properly
if (isset($_SESSION['username'], $_SESSION['id'], $_SESSION['role']) && $_SESSION['role'] === 'admin') {    
    $session_username = $_SESSION['username']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'favicon.php'; ?>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Biometric</title>
<title>Biometric Record Attendance</title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Tabulator Bootstrap Theme CSS -->
<link href="https://unpkg.com/tabulator-tables@5.5.1/dist/css/tabulator_bootstrap5.min.css" rel="stylesheet">

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- Tabulator JS -->
<script src="https://unpkg.com/tabulator-tables@5.5.1/dist/js/tabulator.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

<style>
body { padding: 20px; background-color: #f8f9fa; }
h2 { margin-bottom: 20px; text-align: center; }
.filter-container .form-control, 
.filter-container .form-select { margin-bottom: 10px; }
#biometric-table { margin-top: 20px; }
</style>
</head>
<body>
    
<?php 
include 'admin-menu-bar.php';
?><br><?php
include 'admin-menu-btn.php';
?>

<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col">
      <div class="card mt-1">
        <div class="card-header">
          <h4><i class="bi bi-fingerprint"></i> Biometric Attendance Record</h4>
        </div>
        <div class="card-body">
              <div class="container">
                  <div class="filter-container mb-3">
                      <div class="row g-2 align-items-center">
                          <div class="col-6 col-sm-3 col-md-2 col-lg-2">
                              <select id="dc" class="form-select form-select-sm">
                                  <option value="">All Dept.</option>
                              </select>
                          </div>
                          <div class="col-6 col-sm-3 col-md-2 col-lg-1">
                              <select id="limit" class="form-select form-select-sm">
                                  <!-- <option value="0" selected>All</option> -->
                                  <option value="25">25</option>
                                  <option value="50">50</option>
                                  <option value="100">100</option>
                              </select>
                          </div>
                          <div class="col-6 col-sm-6 col-md-2 col-lg-2">
                              <input type="text" id="q" class="form-control form-control-sm" placeholder="Search">
                          </div>
                          <div class="col-6 col-sm-6 col-md-2 col-lg-2">
                              <input type="text" id="eno" class="form-control form-control-sm" placeholder="Emp. No.">
                          </div>
                          <div class="col-6 col-md-2">
                              <input type="date" id="fmd" class="form-control" value="<?= date('Y-m-d') ?>">
                          </div>
                          <div class="col-6 col-md-2">
                              <input type="date" id="td" class="form-control" value="<?= date('Y-m-d') ?>">
                          </div>
                          <div class="col-12 col-md-2 col-lg-1">
                              <div class="row g-1">
                                  <div class="col-6">
                                      <button id="filterBtn" class="btn btn-primary btn-sm w-100"><i class="bi bi-search"></i></button>
                                  </div>
                                  <div class="col-6">
                                      <button id="resetBtn" class="btn btn-secondary btn-sm w-100"><i class="bi bi-x-circle-fill"></i></button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div id="biometric-table"></div>
              </div>
        </div>
      </div>
    </div>
  </div>
</div>
<br/>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tabulator JS -->
<script src="https://unpkg.com/tabulator-tables@5.6.2/dist/js/tabulator.min.js"></script>

<!-- Attendance Report -->
<script>
const host = "https://api.pdpgroups.com/biometric/public"; // your API host
const path = "/api/report";
const deptApi = host + "/api/department?dc=true&page=1&limit=0";
const bearerToken = "YOUR_BEARER_TOKEN_HERE"; // replace with your token

let currentPage = 1;

const table = new Tabulator("#biometric-table", {
    layout: "fitDataStretch",
    placeholder: "No Data Available",
    theme: "bootstrap5",
    responsiveLayout:true,
    pagination: "remote",
    paginationSize: parseInt($("#limit").val()),
    paginationSizeSelector: [10, 25, 50, 100],
    columns: [
        {title: "#", hozAlign:"center", width: 70, formatter: function(cell){
            let pageSize = table.getPageSize();
            let page = table.getPage();
            return (page - 1) * pageSize + cell.getRow().getPosition(true);
        }},
        {title: "Employee Info", columns: [   // <-- header group
            {title: "Emp ID", field: "employeeNoString", responsive:0},
            {title: "Emp Name", field: "employeeName", responsive:0},
        ]},
        {title: "Date", columns: [   // <-- header group
            {title: "Date", field: "timestamp_formatted.date", responsive:0},
        ]},
        {title: "Duration", columns: [
            {
                title: "Check In",
                field: "timestamp_formatted.time12",
                responsive: 0,
                formatter: function(cell, formatterParams, onRendered){
                    return cell.getRow().getData().checkInOut === 'check-in' ? cell.getValue() : '00:00';
                }
            },
            {
                title: "Check Out",
                field: "timestamp_formatted.time12",
                responsive: 0,
                formatter: function(cell, formatterParams, onRendered){
                    return cell.getRow().getData().checkInOut === 'check-out' ? cell.getValue() : '00:00';
                }
            }
        ]}
    ]
});


function loadDepartments(){
    $.ajax({
        url: deptApi,
        method: "GET",
        headers: { "Authorization": `Bearer ${bearerToken}` },
        dataType: "json"
    }).done(function(data){
        if(data.status && data.data && Array.isArray(data.data.deptCode)){
            const select = $("#dc");
            data.data.deptCode.forEach(code => select.append(`<option value="${code}">${code}</option>`));
        }
    }).fail(function(xhr){
        Swal.fire({ toast:true, position:'top-end', icon:'error', title:`Failed to load departments: ${xhr.statusText}`, showConfirmButton:false, timer:3000 });
    });
}

// 🔹 Custom function to fetch data using jQuery
function fetchTableData(page = 1){
    const params = {
        q: $("#q").val() || "",
        eno: $("#eno").val() || "",
        fmd: $("#fmd").val() || "",
        td: $("#td").val() || "",
        dc: $("#dc").val() || "",
        page: page,
        limit: parseInt($("#limit").val()) || 25
    };

    $.ajax({
        url: host + path,
        method: "GET",
        headers: { "Authorization": `Bearer ${bearerToken}` },
        data: params,
        dataType: "json",
        success: function(response, textStatus, xhr){
            if(xhr.status === 200 && response.status && response.data){
                // ✅ Load data into Tabulator
                table.setData(response.data);

                // Update pagination
                table.setMaxPage(Math.ceil(response.total / params.limit));
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: response.message || `API Error (HTTP ${xhr.status})`,
                    showConfirmButton: false,
                    timer: 3000
                });
                table.clearData();
            }
        },
        error: function(xhr){
            let message = `Error ${xhr.status}: ${xhr.statusText}`;
            try {
                const res = JSON.parse(xhr.responseText);
                if(res.message) message = res.message;
            } catch(e) {}
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: message,
                showConfirmButton: false,
                timer: 3000
            });
            table.clearData();
        }
    });
}

loadDepartments();
fetchTableData();

$("#filterBtn").click(()=>fetchTableData(1));
$("#resetBtn").click(function(){
    $("#q,#eno,#fmd,#td,#dc").val("");
    fetchTableData(1);
});

let typingTimer;
$("#q").on("input", function(){
    clearTimeout(typingTimer);
    if($(this).val().length >= 3 || $(this).val().length === 0) typingTimer = setTimeout(()=>fetchTableData(1), 500);
});

$("#limit").on("change", function(){
    table.setPageSize(parseInt($(this).val()));
    fetchTableData(1);
});
</script>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php include 'footer.php'; ?>
<?php } else {
  header("Location: logout.php");
} ?>
