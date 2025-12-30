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
<title>Biometric Records</title>

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
          <h4><i class="bi bi-fingerprint"></i> Biometric Tabular Record</h4>
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

// Initialize table
const table = new Tabulator("#biometric-table", {
    layout: "fitDataStretch",
    placeholder: "<div class='text-center text-muted p-3'>No Data Available</div>",
    theme: "bootstrap5",
    responsiveLayout: true,
    pagination: "remote",
    paginationSize: parseInt($("#limit").val()),
    paginationSizeSelector: [10, 25, 50, 100],
    movableColumns: true,
    columns: [
        {
            title: "#",
            hozAlign: "center",
            width: 70,
            formatter: function(cell){
                let pageSize = table.getPageSize();
                let page = table.getPage();
                return (page - 1) * pageSize + cell.getRow().getPosition(true);
            }
        },
        {title: "Date", field: "timestamp_formatted.date", responsive:0},
        {title: "Day", field: "timestamp_formatted.day", responsive:1},
        {title: "Time (12hr)", field: "timestamp_formatted.time12", responsive:1},
        {title: "Time (24hr)", field: "timestamp_formatted.time24", responsive:2},
        {title: "Emp ID", field: "employeeNoString", responsive:0},
        {title: "Emp Name", field: "employeeName", responsive:0},
        {title: "Dept Code", field: "deptCode", responsive:1},
        {title: "Device", field: "deviceName", responsive:2},
        {
            title: "Check In/Out",
            field: "checkInOut",
            responsive:0,
            formatter: function(cell){
                const value = cell.getValue();
                const color = value === "check-in" ? "success" : "primary";
                return `<span class="badge bg-${color}">${value.replace("-", " ")}</span>`;
            }
        }
    ],
    rowFormatter: function(row){
        const data = row.getData();
        const el = row.getElement();

        // Alternate row colors
        if(row.getPosition(true) % 2 === 0) el.style.backgroundColor = "#f9f9f9";

        // Highlight missed schedule
        if(data.work_summary.missed_schedule) el.style.backgroundColor = "#ffe6e6";

        // Late check-in
        if(data.work_summary.first_check_in && data.workStart){
            const workStartTime = new Date(`${data.timestamp_formatted.date}T${data.workStart}`);
            const firstCheckIn = new Date(data.work_summary.first_check_in);
            if(firstCheckIn > workStartTime){
                const cellEl = el.querySelector("[tabulator-field='timestamp_formatted.time12']");
                if(cellEl){
                    cellEl.style.color = "#d9534f";
                    cellEl.style.fontWeight = "bold";
                }
            }
        }

        // Build Work Summary HTML for popover
        let summaryHTML = `<div style="font-size:0.9em;">`;
        summaryHTML += `<strong>First Check In:</strong> ${data.work_summary.first_check_in || "-"}<br>`;
        summaryHTML += `<strong>Last Check Out:</strong> ${data.work_summary.last_check_out || "-"}<br>`;
        summaryHTML += `<strong>Total Work:</strong> ${data.work_summary.total_duration.hours + 'hr' || "-"}<br>`;
        summaryHTML += `<strong>Missed Schedule:</strong> ${data.work_summary.missed_schedule ? "Yes" : "No"}<br>`;
        if(data.work_summary.intervals && data.work_summary.intervals.length > 0){
            summaryHTML += `<strong>Intervals:</strong><br>`;
            data.work_summary.intervals.forEach(i=>{
                summaryHTML += `${i.start} → ${i.end} (${i.duration})<br>`;
            });
        } else summaryHTML += `<strong>Intervals:</strong> -<br>`;
        summaryHTML += `</div>`;

        // Attach Bootstrap popover to row
        $(el).attr('data-bs-toggle', 'popover');
        $(el).attr('data-bs-html', 'true');
        $(el).attr('data-bs-trigger', 'hover');
        $(el).attr('data-bs-content', summaryHTML);
        $(el).attr('data-bs-placement', 'top');

        // Initialize popover
        const popover = new bootstrap.Popover(el);
    }
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
