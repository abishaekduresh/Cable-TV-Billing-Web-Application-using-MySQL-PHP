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
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Biometric Calendar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f5f7fa;
    padding: 20px;
}

/* Employee List Styling */
#employees {
    max-height: 720px;
    overflow-y: auto;        /* allow scrolling */
    scrollbar-width: none;  /* Firefox */
    -ms-overflow-style: none; /* IE 10+ */
}

/* Chrome, Edge, Safari */
#employees::-webkit-scrollbar {
    display: none;
}

#employees .employee-item {
    padding: 12px 15px;
    margin-bottom: 10px;
    border-radius: 12px;
    background: #ffffff;
    border-left: 5px solid #0d6efd;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    cursor: pointer;
    transition: all 0.2s ease-in-out;
}
#employees .employee-item:hover {
    background-color: #e7f1ff;
    transform: translateX(5px);
}
#employees .employee-item.active {
    border-left-color: #dc3545;
    background-color: #ffe5e5;
    color: #b71c1c; /* Dark red text color */
}

/* Calendar Styling */
.fc {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding: 10px;
}

/* Modal Styling */
.modal-header {
    background: linear-gradient(90deg, #0d6efd, #6610f2);
    color: #fff;
}
.modal-body {
    font-size: 0.95rem;
}
.modal-body li {
    margin-bottom: 8px;
}

/* --- Mobile Responsiveness --- */
@media (max-width: 768px) {
    /* Stack components on small screens */
    .row {
        flex-direction: column;
    }

    .col-lg-3, .col-md-4, .col-lg-9, .col-md-8 {
        width: 100%;
    }

    /* Make employee list full-width and remove fixed height */
    #employees {
        max-height: 180px;
        /* max-height: none;
        overflow-y: visible; */
        margin-bottom: 20px;
    }

    /* Adjust padding for a cleaner look on mobile */
    body {
        padding: 10px;
    }
    
    /* FullCalendar header adjustments */
    .fc-header-toolbar {
        flex-direction: column;
        align-items: center;
    }
    .fc-toolbar-chunk {
        margin-bottom: 10px;
    }
}
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
          <h4><i class="bi bi-fingerprint"></i> Biometric Attendance Calendar</h4>
        </div>
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-12 mb-3">
                        <input type="text" id="searchEmp" class="form-control mb-3 rounded-pill" placeholder="Search Employee">
                        <div id="employees" class="list-group"></div>
                    </div>

                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow">
                        <div class="modal-header">
                            <h5 class="modal-title" id="attendanceModalLabel">Attendance Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="attendanceDetails"></div>
                    </div>
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
const bearerToken = "YOUR_BEARER_TOKEN_HERE"; // Replace with your API token
const apiAttendance = "https://api.pdpgroups.com/biometric/public/api/report";
let selectedEmployee = null;

// Initialize FullCalendar
const calendarEl = document.getElementById('calendar');
// Make sure SweetAlert2 is included

const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
    events: [],

    eventContent: function(arg) {
        const data = arg.event.extendedProps.details;
        const time = data.timestamp_formatted.time12;
        const check = data.checkInOut;
        const empName = data.employeeName || '';
        return { html: `<div>
                            <small><strong>${time}</strong> (${check})</small><br>
                        </div>` };
    },

    eventClick: function(info) {
        const data = info.event.extendedProps.details || {};

        // Build intervals HTML
        let intervalsHTML = "-";
        if(data.work_summary.intervals && data.work_summary.intervals.length > 0){
            intervalsHTML = `<ul style="padding-left:1rem; margin:0;">`;
            data.work_summary.intervals.forEach(i => {
                intervalsHTML += `<li><strong>${i.start} → ${i.end}</strong> (${i.duration})</li>`;
            });
            intervalsHTML += `</ul>`;
        }

        // SweetAlert HTML
        let html = `
        <table class="table table-bordered table-sm mb-2">
            <tbody>
                <tr>
                    <th>Emp Name</th>
                    <td>${data.employeeName || info.event.title}</td>
                    <th>Emp Code</th>
                    <td>${data.employeeNoString || '-'}</td>
                </tr>
                <tr>
                    <th>Timestamp</th>
                    <td colspan="3">${data.timestamp_formatted.date} ${data.timestamp_formatted.time12} (${data.checkInOut})</td>
                </tr>
                <tr>
                    <th>Device</th>
                    <td colspan="3">${data.deviceName || '-'} (${data.serialNo || 'N/A'})</td>
                </tr>
                <tr>
                    <th colspan="5" class="text-center">Work Summary</th>
                </tr>
                <tr>
                    <th>First Check In</th>
                    <th>Last Check Out</th>
                    <th>Total Duration</th>
                    <th>Missed Schedule</th>
                    <th>Intervals</th>
                </tr>
                <tr>
                    <td>${data.work_summary.first_check_in || '-'}</td>
                    <td>${data.work_summary.last_check_out || '-'}</td>
                    <td>${data.work_summary.total_duration ? data.work_summary.total_duration.hhmm : '-'}</td>
                    <td>${data.work_summary.missed_schedule ? 'Yes' : 'No'}</td>
                    <td>${intervalsHTML}</td>
                </tr>
            </tbody>
        </table>`;

        Swal.fire({
            title: `Attendance Details`,
            html: html,
            width: '80%',            // wider modal
            showCloseButton: true,
            showConfirmButton: false,
            focusConfirm: false,
            customClass: {
                popup: 'swal2-overflow' // allow scroll if content is long
            }
        });
    },

    eventColor: '#0d6efd',
    eventTextColor: '#fff',
});

calendar.render();


// Fetch employees (async)
async function fetchEmployees() {
    try {
        const res = await fetch(apiAttendance + '?page=1&limit=1000', {
            headers: { "Authorization": `Bearer ${bearerToken}` }
        });
        const data = await res.json();
        if(data.status && Array.isArray(data.data)){
            const unique = {};
            data.data.forEach(d => {
                if(d.employeeNoString && d.employeeName && !unique[d.employeeNoString]){
                    unique[d.employeeNoString] = { name: d.employeeName, eno: d.employeeNoString };
                }
            });
            const employees = Object.values(unique);
            renderEmployees(employees);
            return employees;
        } else {
            Swal.fire('Error', 'No employees found', 'error');
            return [];
        }
    } catch(err){
        console.error(err);
        Swal.fire('Error', 'Failed to fetch employees', 'error');
        return [];
    }
}

// Render employee list
function renderEmployees(employees){
    const container = $("#employees");
    container.empty();
    if(employees.length === 0){
        container.html('<div class="text-muted">No employees available</div>');
        return;
    }
    employees.forEach(emp => {
        const div = $(`<div class="employee-item list-group-item" data-eno="${emp.eno}">
                        <strong>${emp.name}</strong><br><small>${emp.eno}</small>
                    </div>`);
        div.on('click', async () => {
            $(".employee-item").removeClass("active");
            div.addClass("active");
            selectedEmployee = emp.eno;
            await fetchAttendance();
        });
        container.append(div);
    });
}

// Fetch attendance (async)
async function fetchAttendance() {
    if(!selectedEmployee) return;
    try {
        const params = new URLSearchParams({ eno: selectedEmployee, page:1, limit:100 });
        const res = await fetch(apiAttendance + '?' + params.toString(), {
            headers: { "Authorization": `Bearer ${bearerToken}` }
        });
        const data = await res.json();
        if(data.status && Array.isArray(data.data)){
            const events = data.data.map(d => ({
                title: d.employeeName + ' (' + d.currentVerifyMode + ')',
                start: d.timestamp,
                allDay: false,
                extendedProps: { details: d }
            }));
            calendar.removeAllEvents();
            calendar.addEventSource(events);
            Swal.fire({
                toast:true,
                position:'top-end',
                icon:'success',
                title:data.message || 'Attendance loaded',
                showConfirmButton:false,
                timer:1500,
                timerProgressBar:true
            });
        } else {
            Swal.fire('Info', 'No attendance records found', 'info');
        }
    } catch(err){
        console.error(err);
        Swal.fire('Error', 'Failed to fetch attendance', 'error');
    }
}

// Employee search filter
$("#searchEmp").on("input", function(){
    const val = $(this).val().toLowerCase();
    $(".employee-item").each(function(){
        const name = $(this).text().toLowerCase();
        $(this).toggle(name.includes(val));
    });
});

// Initialize on page load
$(document).ready(async function(){
    await fetchEmployees();
});
</script>
</body>
</html>
<?php include 'footer.php'; ?>
<?php } else {
  header("Location: logout.php");
} ?>
