<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id'])) {   
    
    // Menu includes moved to body
    $session_username = $_SESSION['username']; // Ensure variable is set if needed globally, though it was set in if blocks.
    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
    <?php include 'favicon.php'; ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dynamic Group & Advanced Bill Report</title>
        
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        
        <!-- DataTables CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">

        <style>
            :root {
                --primary-color: #4361ee;
                --secondary-color: #3f37c9;
                --accent-color: #4895ef;
                --success-color: #06d6a0;
                --danger-color: #ef476f;
                --warning-color: #ffd166;
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

            .main-content {
                padding: 2rem 1rem;
            }

            /* Card Styling */
            .custom-card {
                background: white;
                border-radius: 16px;
                border: none;
                box-shadow: var(--card-shadow);
                overflow: hidden;
                margin-bottom: 1.5rem;
                transition: transform 0.2s ease;
            }

            .card-header-gradient {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                padding: 1.5rem;
                color: white;
                border: none;
            }

            .card-title {
                margin: 0;
                font-weight: 700;
                font-size: 1.25rem;
                letter-spacing: 0.5px;
            }

            /* Form Elements */
            .form-label {
                font-weight: 600;
                font-size: 0.875rem;
                color: var(--text-dark);
                margin-bottom: 0.5rem;
            }

            .form-control, .form-select {
                border-radius: 8px;
                border: 1px solid #e5e7eb;
                padding: 0.625rem 1rem;
                font-size: 0.95rem;
                transition: all 0.2s;
            }

            .form-control:focus, .form-select:focus {
                border-color: var(--primary-color);
                box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
            }

            /* Buttons */
            .btn-search {
                background-color: var(--primary-color);
                border: none;
                padding: 0.625rem 2rem;
                border-radius: 8px;
                font-weight: 600;
                color: white;
                transition: all 0.2s;
            }

            .btn-search:hover {
                background-color: var(--secondary-color);
                transform: translateY(-1px);
            }

            /* Table Styling */
            .table-container {
                border-radius: 12px;
                overflow: hidden;
            }

            table.dataTable {
                border-collapse: separate !important;
                border-spacing: 0;
                width: 100% !important;
                border: 1px solid #e5e7eb;
                border-radius: 12px;
            }

            table.dataTable thead th {
                background-color: #f9fafb;
                color: var(--text-dark);
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                padding: 1rem !important;
                border-bottom: 1px solid #e5e7eb !important;
            }

            table.dataTable tbody td {
                padding: 1rem !important;
                vertical-align: middle;
                border-bottom: 1px solid #e5e7eb;
                color: #4b5563;
                font-size: 0.9rem;
            }

            /* Badges */
            .status-badge {
                padding: 0.35em 0.8em;
                border-radius: 6px;
                font-weight: 600;
                font-size: 0.75rem;
                letter-spacing: 0.025em;
            }
            .badge-regular { background-color: rgba(67, 97, 238, 0.1); color: var(--primary-color); }
            .badge-advanced { background-color: rgba(6, 214, 160, 0.1); color: var(--success-color); }

            /* Action Buttons */
            .action-btn {
                width: 32px;
                height: 32px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border-radius: 8px;
                transition: all 0.2s;
                border: none;
                margin: 0 2px;
            }

            .btn-print { background-color: #fff3cd; color: #856404; }
            .btn-print:hover { background-color: #ffeeba; }

            .btn-list { background-color: #e2e3e5; color: #383d41; }
            .btn-list:hover { background-color: #d6d8db; }

            /* DataTables Customization */
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                background: var(--primary-color) !important;
                color: white !important;
                border: none !important;
                border-radius: 6px;
            }
            
            .dataTables_wrapper .dataTables_length select {
                padding: 0.375rem 2rem 0.375rem 0.75rem;
                border-radius: 6px;
            }

            .dt-buttons .btn {
                background: white;
                border: 1px solid #e5e7eb;
                color: var(--text-dark);
                border-radius: 6px;
                font-size: 0.875rem;
                margin-right: 0.5rem;
            }
            
            .dt-buttons .btn:hover {
                background: #f9fafb;
            }
        </style>
    </head>

    <?php
    if (isset($_SESSION['username']) && $_SESSION['role'] == 'admin') {
        include 'admin-menu-bar.php';
        $session_username = $_SESSION['username'];
        echo '<br>';
        include 'admin-menu-btn.php';
    } elseif (isset($_SESSION['username']) && $_SESSION['role'] == 'employee') {
        include 'menu-bar.php';
        $session_username = $_SESSION['username'];
        echo '<br>';
        include 'sub-menu-btn.php';
    }
    ?>

        <div class="container-fluid main-content">
            <div class="row justify-content-center">
                <div class="col-xl-11 col-lg-12">
                    
                    <!-- Search Filter Card -->
                    <div class="custom-card">
                        <div class="card-header-gradient">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">
                                    <i class="bi bi-file-earmark-text me-2"></i>Group Reports
                                </h4>
                                <span class="badge bg-white text-primary">Dynamic & Advanced</span>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <form action="" method="GET">
                                <div class="row align-items-end g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">From Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar"></i></span>
                                            <input type="date" name="from_date" 
                                                value="<?php echo isset($_GET['from_date']) ? $_GET['from_date'] : $currentDate; ?>" 
                                                class="form-control border-start-0 ps-0">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">To Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-check"></i></span>
                                            <input type="date" name="to_date" 
                                                value="<?php echo isset($_GET['to_date']) ? $_GET['to_date'] : $currentDate; ?>" 
                                                class="form-control border-start-0 ps-0" required>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="form-label">Filter Criteria</label>
                                        <select name="date_type" class="form-select">
                                            <option value="billing_date" <?php echo (isset($_GET['date_type']) && $_GET['date_type'] == 'billing_date') ? 'selected' : ''; ?>>Billing Date</option>
                                            <option value="created_date" <?php echo (isset($_GET['date_type']) && $_GET['date_type'] == 'created_date') ? 'selected' : ''; ?>>Created Date (Entry Date)</option>
                                        </select>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-search w-100">
                                            <i class="bi bi-search me-2"></i>View Report
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Results Table -->
                    <div class="custom-card">
                        <div class="card-body p-0">
                            <div class="table-responsive p-3">
                                <table id="billingTable" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <!-- <th>ID</th> -->
                                            <th>Bill No</th>
                                            <th>Group Name</th>
                                            <th>Phone</th>
                                            <th>Mode</th>
                                            <th class="text-end">Old Bal</th>
                                            <th class="text-end">Amt</th>
                                            <th class="text-end">Disc</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($_GET['from_date']) && isset($_GET['to_date'])) {
                                            $from_date = $_GET['from_date'];
                                            $to_date = $_GET['to_date'];
                                            $date_type = isset($_GET['date_type']) ? $_GET['date_type'] : 'billing_date';

                                            $filterColumn = "date"; // Default to billing date
                                            if ($date_type == 'created_date') {
                                                // Using created_at for filtering but only the date part
                                                $filterColumn = "DATE(created_at)";
                                            }

                                            // Determine if 'ad' column exists (Handling safe fallback)
                                            // Ideally schema should have it. We assume it does based on task.
                                            // Query to fetch data
                                            $query = "SELECT 
                                                        *,
                                                        group_id as idVal,
                                                        groupName as entityName,
                                                        billAmount as mainAmount,
                                                        ad as adv_status 
                                                      FROM billgroupdetails 
                                                      WHERE $filterColumn BETWEEN '$from_date' AND '$to_date' AND status = 'approve'
                                                      ORDER BY $filterColumn DESC"; // Ordered by date

                                            $result = mysqli_query($con, $query);

                                            if ($result && mysqli_num_rows($result) > 0) {
                                                $serial_number = 1;
                                                foreach ($result as $row) {
                                                    // Determine Status
                                                    $isAdvanced = ($row['adv_status'] == 1);
                                                    $badgeClass = $isAdvanced ? 'badge-advanced' : 'badge-regular';
                                                    $badgeText = $isAdvanced ? 'ADVANCE' : 'REGULAR';
                                                    
                                                    // Formatting
                                                    $displayDate = ($date_type == 'created_date' && isset($row['created_at'])) ? 
                                                                   date('d-M-Y', strtotime($row['created_at'])) : 
                                                                   date('d-M-Y', strtotime($row['date']));
                                                    ?>
                                                    <tr>
                                                        <td><?= $serial_number++; ?></td>
                                                        <td class="fw-bold text-primary"><?= $displayDate; ?></td>
                                                        <td><span class="status-badge <?= $badgeClass; ?>"><?= $badgeText; ?></span></td>
                                                        <!-- <td class="fw-bold"><?= $row['idVal']; ?></td> -->
                                                        <td><?= $row['billNo']; ?></td>
                                                        <td class="fw-bold text-dark"><?= $row['entityName']; ?></td>
                                                        <td><?= $row['phone']; ?></td>
                                                        <td><?= strtoupper($row['pMode']); ?></td>
                                                        <td class="text-end text-secondary fw-bold"><?= number_format($row['oldMonthBal']); ?></td>
                                                        <td class="text-end text-success fw-bold"><?= number_format($row['mainAmount']); ?></td>
                                                        <td class="text-end text-danger fw-bold"><?= number_format($row['discount']); ?></td>
                                                        <td class="text-end text-danger fw-bolder fs-6"><?= number_format($row['Rs']); ?></td>
                                                        <td class="text-center">
                                                            <div class="d-flex justify-content-center">
                                                                <a href="prtgroupbillrpt.php?group_id=<?= $row['idVal']; ?>&date=<?= $row['date']; ?>" 
                                                                   target="_blank" class="action-btn btn-print" title="Print Bill">
                                                                    <i class="bi bi-printer"></i>
                                                                </a>
                                                                <!-- IMPORTANT: Passing 'date' which is billing date, crucial for fetching list items -->
                                                                <button type="button" class="action-btn btn-list view-list-btn" 
                                                                        title="View Item List"
                                                                        data-billno="<?= $row['billNo']; ?>" 
                                                                        data-groupid="<?= $row['idVal']; ?>"
                                                                        data-date="<?= $row['date']; ?>">
                                                                    <i class="bi bi-list-ul"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="7" class="text-end fw-bold text-dark">Grand Total:</th>
                                            <th class="text-end fw-bold text-secondary" id="sumOldBal"></th>
                                            <th class="text-end fw-bold text-success" id="sumAmt"></th>
                                            <th class="text-end fw-bold text-warning" id="sumDisc"></th>
                                            <th class="text-end fw-bold text-danger fs-6" id="sumTotal"></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Groups List Modal -->
        <div class="modal fade" id="groupListModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title fw-bold"><i class="bi bi-receipt me-2"></i>Bill Item Details</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0" id="groupListModalBody">
                        <!-- Loaded via AJAX -->
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        
        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

        <script>
            $(document).ready(function() {
                var table = $('#billingTable').DataTable({
                    dom: '<"row mb-3"<"col-md-6"B><"col-md-6"f>>rt<"row mt-3"<"col-md-6"i><"col-md-6"p>>',
                    buttons: [
                        { extend: 'copy', className: 'btn btn-sm btn-outline-secondary' },
                        { extend: 'excel', className: 'btn btn-sm btn-outline-success' },
                        { extend: 'pdf', className: 'btn btn-sm btn-outline-danger' },
                        { extend: 'print', className: 'btn btn-sm btn-outline-info' }
                    ],
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search records..."
                    },
                    footerCallback: function ( row, data, start, end, display ) {
                        var api = this.api();
             
                        // Helper to remove formatting (comma) for integer calculation
                        var intVal = function ( i ) {
                            return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                        };
             
                        // Columns to calculate: 7(OldBal), 8(Amt), 9(Disc), 10(Total)
                        var columns = [
                            { index: 7, id: '#sumOldBal' },
                            { index: 8, id: '#sumAmt' },
                            { index: 9, id: '#sumDisc' },
                            { index: 10, id: '#sumTotal' }
                        ];

                        columns.forEach(function(col) {
                            // Grand Total over all pages
                            var total = api.column(col.index).data().reduce( function (a, b) {
                                return intVal(a) + intVal(b);
                            }, 0 );
                            
                            // Update Footer Cell by ID
                            $(col.id).html(total.toLocaleString('en-IN'));
                        });
                    }
                });

                // Handle View List Click
                $(document).on('click', '.view-list-btn', function() {
                    var billNo = $(this).data('billno');
                    var groupId = $(this).data('groupid');
                    var date = $(this).data('date');

                    var myModalEl = document.getElementById('groupListModal');
                    var modal = new bootstrap.Modal(myModalEl);
                    modal.show();

                    $('#groupListModalBody').html('<div class="d-flex justify-content-center align-items-center p-5"><div class="spinner-border text-primary" role="status"></div><span class="ms-3">Loading details...</span></div>');

                    $.ajax({
                        url: 'code-fetch-group-details.php',
                        type: 'POST',
                        data: {
                            billNo: billNo,
                            group_id: groupId,
                            date: date
                        },
                        success: function(response) {
                            $('#groupListModalBody').html(response);
                        },
                        error: function(xhr, status, error) {
                            $('#groupListModalBody').html('<div class="text-center p-4 text-danger"><i class="bi bi-exclamation-triangle fs-1"></i><p class="mt-2">Error fetching details. Please try again.</p></div>');
                        }
                    });
                });
            });
        </script>
    </body>
    </html>

<?php } else {
    header("Location: index.php");
} ?>
