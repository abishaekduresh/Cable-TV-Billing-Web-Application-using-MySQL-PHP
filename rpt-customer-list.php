<?php
session_start();
include "dbconfig.php";
require "component.php";

// ----------------------------------------------------------------
// SERVER-SIDE DATA PROCESSING (AJAX)
// ----------------------------------------------------------------
if (isset($_POST['action']) && $_POST['action'] == 'fetch_customers') {
    
    // 1. Base Query
    $columns = array(
        0 => 'customer_id', // Serial placeholder
        1 => 'cusGroup',
        2 => 'mso',
        3 => 'rc_dc',
        4 => 'stbno',
        5 => 'name',
        6 => 'phone',
        7 => 'customer_area_code',
        8 => 'oldMonthBal'
    );

    $sql = "SELECT * FROM customer";
    $count_query = "SELECT COUNT(*) as total FROM customer";
    
    // 2. Filter Logic
    $conditions = array();

    // Custom Filters
    if (!empty($_POST['group_id'])) {
        $conditions[] = "cusGroup = '" . mysqli_real_escape_string($con, $_POST['group_id']) . "'";
    }
    if (isset($_POST['rc_dc']) && $_POST['rc_dc'] !== '') {
        $conditions[] = "rc_dc = '" . mysqli_real_escape_string($con, $_POST['rc_dc']) . "'";
    }
    if (!empty($_POST['mso'])) {
        $mso = mysqli_real_escape_string($con, $_POST['mso']);
        $conditions[] = "mso = '$mso'";
    }
    if (!empty($_POST['area'])) {
        $area = mysqli_real_escape_string($con, $_POST['area']);
        $conditions[] = "customer_area_code = '$area'";
    }
    // Note: The 'search' input in the top card is now handled by DataTable's global search 
    // OR we can map it manually if we want a separate custom search field. 
    // For now, let's let the DataTable 'search[value]' handle the global search AND 
    // if the user typed in the top box, we can include that too.
    if (!empty($_POST['custom_search'])) {
        $search = mysqli_real_escape_string($con, $_POST['custom_search']);
        $conditions[] = "(name LIKE '%$search%' OR phone LIKE '%$search%' OR stbno LIKE '%$search%')";
    }

    // DataTable Global Search
    if (!empty($_POST['search']['value'])) {
        $search_val = mysqli_real_escape_string($con, $_POST['search']['value']);
        $conditions[] = "(name LIKE '%$search_val%' OR phone LIKE '%$search_val%' OR stbno LIKE '%$search_val%')";
    }

    // Combine Conditions
    if (count($conditions) > 0) {
        $sql .= " WHERE " . implode(' AND ', $conditions);
        $count_query .= " WHERE " . implode(' AND ', $conditions);
    }

    // 3. Ordering
    if (isset($_POST['order'])) {
        $column_name = $columns[$_POST['order'][0]['column']];
        $order = $_POST['order'][0]['dir'];
        $sql .= " ORDER BY " . $column_name . " " . $order;
    } else {
        $sql .= " ORDER BY cusGroup ASC, name ASC";
    }

    // 4. Pagination
    if ($_POST['length'] != -1) {
        $start = $_POST['start'];
        $length = $_POST['length'];
        $sql .= " LIMIT " . $start . ", " . $length;
    }

    // Execute Data Query
    $query = mysqli_query($con, $sql);
    $data = array();
    
    // Execute Count Query
    $count_run = mysqli_query($con, $count_query);
    $count_row = mysqli_fetch_assoc($count_run);
    $total_filtered = $count_row['total'];

    // Total Records (Without Filter) - Strictly typically this is total DB count, 
    // but for performance we often just use the filtered count or a separate cached count. 
    // For simplicity, let's query total table count.
    $total_sql = "SELECT COUNT(*) as total FROM customer";
    $total_run = mysqli_query($con, $total_sql);
    $total_row = mysqli_fetch_assoc($total_run);
    $total_records = $total_row['total'];

    $serial = $_POST['start'] + 1;

    while ($row = mysqli_fetch_assoc($query)) {
        $sub_array = array();
        
        $sub_array[] = '<span class="text-muted small">' . $serial++ . '</span>';
        
        // Group
        $sub_array[] = '<span class="badge bg-primary">' . fetchGroupName($row['cusGroup']) . '</span>';
        
        // MSO
        $sub_array[] = '<span class="small font-monospace">' . $row['mso'] . '</span>';
        
        // Status
        if($row['rc_dc'] == 1) {
            $sub_array[] = '<span class="badge bg-success">RC</span>';
        } else {
            $sub_array[] = '<span class="badge bg-danger">DC</span>';
        }

        // STB
        $sub_array[] = '<span class="fw-bold text-primary small">' . $row['stbno'] . '</span>';
        
        // Name
        $sub_array[] = '<span class="fw-bold">' . $row['name'] . '</span>';
        
        // Phone
        $sub_array[] = $row['phone'];
        
        // Area
        $sub_array[] = '<span class="text-muted small">' . $row['customer_area_code'] . '</span>';
        
        // Balance
        $amount = isset($row['amount']) ? $row['amount'] : (isset($row['oldMonthBal']) ? $row['oldMonthBal'] : 0);
        $sub_array[] = '<span class="fw-bold text-dark">' . number_format($amount, 2) . '</span>';

        $data[] = $sub_array;
    }

    // Output JSON
    $json_data = array(
        "draw"            => intval($_POST['draw']),
        "recordsTotal"    => intval($total_records),
        "recordsFiltered" => intval($total_filtered),
        "data"            => $data
    );

    echo json_encode($json_data);
    exit; // Stop further execution
}

// ----------------------------------------------------------------
// PAGE UI
// ----------------------------------------------------------------
include 'preloader.php'; // Only include preloader for HTML page

if (isset($_SESSION['username']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List Report</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --text-dark: #2b2d42;
            --bg-light: #f8f9fa;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            color: var(--text-dark);
        }
        .main-content { padding: 2rem 1rem; }
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1.5rem;
            color: white;
        }
        .btn-search {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.625rem 2rem;
            font-weight: 600;
        }
        .btn-search:hover { background-color: var(--secondary-color); color: white; }
        
        .table thead th {
            background-color: #f9fafb;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 1rem;
        }
        .badge { font-weight: 500; letter-spacing: 0.5px; }
    </style>
</head>

<?php
include 'admin-menu-bar.php';
echo '<br>';
include 'admin-menu-btn.php';
?>

<body>
    <div class="container-fluid main-content">
        <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12">
                
                <!-- Filter Card -->
                <div class="custom-card">
                    <div class="card-header-gradient">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="mb-0 fw-bold"><i class="bi bi-people-fill me-2"></i>Customer List Report</h4>
                            <span class="badge bg-white text-primary">Advanced Filter</span>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <form id="filterForm">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Customer Group</label>
                                    <select id="group_id" class="form-select">
                                        <option value="">All Groups</option>
                                        <?php
                                        $group_query = "SELECT * FROM groupinfo WHERE group_id != '2'";
                                        $group_result = mysqli_query($con, $group_query);
                                        if(mysqli_num_rows($group_result) > 0){
                                            while($grp = mysqli_fetch_assoc($group_result)){
                                                echo "<option value='".$grp['group_id']."'>".$grp['groupName']."</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select id="rc_dc" class="form-select">
                                        <option value="">All</option>
                                        <option value="1">RC (Connected)</option>
                                        <option value="0">DC (Disconnected)</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">MSO</label>
                                    <select id="mso" class="form-select">
                                        <option value="">All MSOs</option>
                                        <?php
                                        $mso_query = "SELECT DISTINCT mso FROM customer ORDER BY mso ASC";
                                        $mso_result = mysqli_query($con, $mso_query);
                                        if(mysqli_num_rows($mso_result) > 0){
                                            while($row = mysqli_fetch_assoc($mso_result)){
                                                if(!empty($row['mso'])) {
                                                    echo "<option value='".$row['mso']."'>".$row['mso']."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Area Code</label>
                                    <select id="area" class="form-select">
                                        <option value="">All Areas</option>
                                        <?php
                                        $area_query = "SELECT DISTINCT customer_area_code FROM customer ORDER BY customer_area_code ASC";
                                        $area_result = mysqli_query($con, $area_query);
                                        if(mysqli_num_rows($area_result) > 0){
                                            while($row = mysqli_fetch_assoc($area_result)){
                                                if(!empty($row['customer_area_code'])) {
                                                    echo "<option value='".$row['customer_area_code']."'>".$row['customer_area_code']."</option>";
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="row g-2">
                                        <div class="col-md-10">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                                                <input type="text" id="custom_search" class="form-control" placeholder="Optional: Filter by Name, Phone or STB...">
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-search w-100" id="applyFilterBtn">
                                                <i class="bi bi-filter me-2"></i>Load Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Results Table -->
                <div class="custom-card">
                    <div class="card-body p-0">
                        <div class="table-responsive p-3">
                            <table class="table table-hover w-100" id="customerTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Group</th>
                                        <th>MSO</th>
                                        <th>Status</th>
                                        <th>STB No</th>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Area</th>
                                        <th class="text-end">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data Loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            var dataTable = $('#customerTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order": [], // Initial no order
                "pageLength": 10, // 10 per page as requested
                "deferLoading": 0, // IMPORTANT: Don't load initial data!
                "ajax": {
                    url: "rpt-customer-list.php",
                    type: "POST",
                    data: function(d) {
                        d.action = "fetch_customers";
                        d.group_id = $('#group_id').val();
                        d.rc_dc = $('#rc_dc').val();
                        d.mso = $('#mso').val();
                        d.area = $('#area').val();
                        d.custom_search = $('#custom_search').val();
                    }
                },
                "columnDefs": [
                    { "targets": [0, 8], "orderable": false }, // Disable sorting on serial and balance
                    { "className": "text-end", "targets": [8] }
                ],
                "language": {
                    "search": "Global Search:",
                    "searchPlaceholder": "Search all records...",
                    "processing": "<div class='spinner-border text-primary' role='status'><span class='visually-hidden'>Loading...</span></div>"
                }
            });

            // Load Data Button
            $('#applyFilterBtn').click(function() {
                dataTable.draw(); // Trigger the AJAX
            });
            
            // Allow Enter key in custom search
            $('#custom_search').keypress(function(e) {
                if(e.which == 13) {
                    e.preventDefault();
                    dataTable.draw();
                }
            });
        });
    </script>
</body>
</html>
<?php 
} else {
    header("Location: logout.php");
}
?>
