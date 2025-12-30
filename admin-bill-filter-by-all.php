<?php
session_start();
include "dbconfig.php";
require "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && $_SESSION['role'] == 'admin') {
    $session_username = $_SESSION['username'];
    $sumPaid = $sumOldBal = $sumDisc = $sumTotal = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill by All Report</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
            --secondary-gradient: linear-gradient(135deg, #4cc9f0 0%, #4361ee 100%);
            --card-shadow: 0 10px 20px rgba(0,0,0,0.05); /* Softer shadow */
            --border-radius: 16px;
            --font-family: 'Inter', sans-serif;
        }

        body {
            font-family: var(--font-family);
            background-color: #f0f2f5;
            color: #1a1a1a;
        }

        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .main-content {
            padding: 2rem 1rem;
        }

        /* Card Styling */
        .custom-card {
            background: white;
            border-radius: var(--border-radius);
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }

        .card-header-gradient {
            background: var(--primary-gradient);
            padding: 1.5rem 2rem;
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            margin: 0;
            font-weight: 800;
            font-size: 1.5rem;
        }

        /* Form Controls */
        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 1px solid #dee2e6;
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.1);
        }

        /* Buttons */
        .btn-search {
            background: var(--primary-gradient);
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            color: white;
            letter-spacing: 0.02em;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(67, 97, 238, 0.4);
            color: white;
        }

        /* Table Styling */
        table.dataTable {
            border-collapse: separate;
            border-spacing: 0;
            width: 100% !important;
        }

        table.dataTable thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem !important;
            border-bottom: 2px solid #e9ecef !important;
        }

        table.dataTable tbody td {
            padding: 1rem !important;
            vertical-align: middle;
            font-size: 0.95rem;
            border-bottom: 1px solid #f1f3f5;
            color: #343a40;
        }
        
        table.dataTable tbody tr:hover {
            background-color: #f8f9fa !important;
        }

        /* Footer Totals */
        table.dataTable tfoot th {
            background-color: #f1f3f5;
            padding: 1rem !important;
            font-size: 1rem;
            border-top: 2px solid #dee2e6 !important;
        }

        /* DataTables Buttons Customization */
        .dt-buttons .btn {
            border-radius: 8px;
            padding: 0.4rem 1rem;
            font-weight: 600;
            font-size: 0.85rem;
            margin-right: 0.5rem;
            border: 1px solid #e9ecef;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        
        .btn-group > .btn:not(:first-child), .btn-group > .btn-group:not(:first-child) {
             margin-left: 5px; /* Spacing between export buttons */
        }

        /* Pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 8px !important;
            padding: 0.4rem 0.8rem !important;
            border: 1px solid transparent !important;
            margin: 0 0.1rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #4361ee !important;
            color: white !important;
            border: 1px solid #4361ee !important;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.2);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #e9ecef !important;
            border: 1px solid #dee2e6 !important;
            color: black !important;
        }

        .badge-pill-custom {
            padding: 0.5em 1em;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>

<?php
include 'admin-menu-bar.php';
echo '<br>';
include 'admin-menu-btn.php';
?>

<div class="container-fluid main-content">
    <div class="row justify-content-center">
        <div class="col-xl-11 col-lg-12">
            
            <!-- Filters Card -->
            <div class="custom-card">
                <div class="card-header-gradient">
                    <div>
                        <h4 class="card-title"><i class="bi bi-funnel-fill me-2"></i>History & Reports</h4>
                        <p class="mb-0 opacity-75 small">Filter billing records by date range and bill numbers.</p>
                    </div>
                </div>
                <div class="card-body p-4 pt-5">
                    <form method="GET" class="row g-4 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-calendar-event me-1"></i>From Date</label>
                            <input type="date" name="from_date" class="form-control" value="<?= $_GET['from_date'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-calendar-event me-1"></i>To Date</label>
                            <input type="date" name="to_date" class="form-control" value="<?= $_GET['to_date'] ?? date('Y-m-d'); ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-hash me-1"></i>Start Bill No</label>
                            <input type="number" name="from_billno" class="form-control" placeholder="Original No" value="<?= $_GET['from_billno'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label"><i class="bi bi-hash me-1"></i>End Bill No</label>
                            <input type="number" name="to_billno" class="form-control" placeholder="Original No" value="<?= $_GET['to_billno'] ?? ''; ?>">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-search w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-search me-2"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Card -->
            <div class="custom-card">
                 <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3 px-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 text-primary">
                            <i class="bi bi-file-earmark-spreadsheet-fill fs-5"></i>
                        </div>
                        <h5 class="m-0 fw-bold text-dark">Revenue Report</h5>
                    </div>
                    <span class="badge bg-light text-dark border p-2 rounded-3 shadow-sm">
                        <i class="bi bi-clock-history me-1 text-secondary"></i>
                        <?php 
                         if (isset($_GET['from_date'], $_GET['to_date'])) {
                             echo date('d M Y', strtotime($_GET['from_date'])) . ' - ' . date('d M Y', strtotime($_GET['to_date']));
                         } else {
                             echo 'Today';
                         }
                        ?>
                    </span>
                 </div>
                 <div class="card-body p-0">
                     <div class="table-responsive p-3">
                        <table id="billTable" class="table table-hover align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Biller</th>
                                    <th>Col. Date</th>
                                    <th>Bill Date</th>
                                    <th>Bill #</th>
                                    <th>MSO</th>
                                    <th>STB No</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Remarks</th>
                                    <th>Mode</th>
                                    <th class="text-end">Old Bal</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Disc</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($_GET['from_date'], $_GET['to_date'])) {
                                $from_date = $_GET['from_date'];
                                $to_date = $_GET['to_date'];
                                $from_billno = $_GET['from_billno'] ?? '';
                                $to_billno = $_GET['to_billno'] ?? '';
                                $billFilter = '';
                                if($from_billno && $to_billno){
                                    $billFilter = " AND billNo BETWEEN '$from_billno' AND '$to_billno'";
                                }

                                $query = "SELECT * FROM bill WHERE DATE(due_month_timestamp) BETWEEN '$from_date' AND '$to_date' $billFilter ORDER BY DATE(due_month_timestamp) DESC";
                                $result = mysqli_query($con, $query);

                                if(mysqli_num_rows($result) > 0){
                                    $sn = 1;
                                    while($row = mysqli_fetch_assoc($result)){
                                        $sumOldBal += $row['oldMonthBal'];
                                        $sumPaid += $row['paid_amount'];
                                        $sumDisc += $row['discount'];
                                        $sumTotal += $row['Rs'];
                                        
                                        // Mode Badge Color
                                        $modeClass = 'bg-secondary';
                                        if(strtolower($row['pMode']) == 'cash') $modeClass = 'bg-success';
                                        else if(strtolower($row['pMode']) == 'credit') $modeClass = 'bg-warning text-dark';
                                        
                                        ?>
                                        <tr>
                                            <td class="text-secondary fw-bold"><?= $sn++; ?></td>
                                            <td class="fw-bold text-primary small"><?= $row['bill_by']; ?></td>
                                            <td class="small"><?= formatDate($row['date']); ?></td>
                                            <td class="small text-secondary fw-bold"><?= formatDate($row['due_month_timestamp']); ?></td>
                                            <td class="fw-bold font-monospace text-dark"><?= $row['billNo']; ?></td>
                                            <td><span class="badge bg-light text-dark border"><?= $row['mso']; ?></span></td>
                                            <td class="small font-monospace text-muted"><?= $row['stbno']; ?></td>
                                            <td class="fw-bold"><?= $row['name']; ?></td>
                                            <td class="small text-muted"><?= $row['phone']; ?></td>
                                            <td class="small text-muted text-truncate" style="max-width: 120px;" title="<?= $row['description']; ?>"><?= $row['description']; ?></td>
                                            <td><span class="badge badge-pill-custom <?= $modeClass; ?> bg-opacity-75"><?= strtoupper($row['pMode']); ?></span></td>
                                            <td class="text-end text-muted"><?= number_format($row['oldMonthBal'], 2); ?></td>
                                            <td class="text-end text-success fw-bold"><?= number_format($row['paid_amount'], 2); ?></td>
                                            <td class="text-end text-danger"><?= number_format($row['discount'], 2); ?></td>
                                            <td class="text-end text-primary fw-bolder fs-6"><?= number_format($row['Rs'], 2); ?></td>
                                            <td class="text-center">
                                                <a href="prtindivbillrpt.php?billid=<?= $row['bill_id']; ?>" target="_blank" class="btn btn-sm btn-light text-primary rounded-circle shadow-sm" title="Print Bill">
                                                    <i class="bi bi-printer-fill"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }
                            ?>
                            </tbody>
                            <tfoot style="background: #f8f9fa;">
                                <tr>
                                    <th colspan="11" class="text-end fw-bold text-dark text-uppercase">Grand Total</th>
                                    <th class="text-end fw-bold text-secondary" id="sumOldBal"><?= number_format($sumOldBal, 2) ?></th>
                                    <th class="text-end fw-bold text-success fs-6" id="sumPaid"><?= number_format($sumPaid, 2) ?></th>
                                    <th class="text-end fw-bold text-danger" id="sumDisc"><?= number_format($sumDisc, 2) ?></th>
                                    <th class="text-end fw-bold text-primary fs-5" id="sumTotal"><?= number_format($sumTotal, 2) ?></th>
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        $('#billTable').DataTable({
            dom: '<"d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2"<"d-flex align-items-center"B><"d-flex align-items-center bg-white p-1 rounded border"f>>rt<"d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2"ip>',
            buttons: [
                { extend: 'copy', className: 'btn btn-outline-secondary btn-sm', text: '<i class="bi bi-clipboard me-1"></i> Copy' },
                { extend: 'excel', className: 'btn btn-outline-success btn-sm', text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel' },
                { extend: 'pdf', className: 'btn btn-outline-danger btn-sm', text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF', orientation: 'landscape' },
                { extend: 'print', className: 'btn btn-outline-primary btn-sm', text: '<i class="bi bi-printer me-1"></i> Print' }
            ],
            language: {
                search: "",
                searchPlaceholder: "Search records..."
            },
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            pageLength: 25,
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api();
     
                // Remove formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '').replace(/<[^>]+>/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                var columns = [11, 12, 13, 14];

                columns.forEach(function(index) {
                    var total = api
                        .column( index )
                        .data()
                        .reduce( function (a, b) {
                            return a + intVal(b);
                        }, 0 );

                    // Update footer using API method which is safer than ID selector
                    $( api.column( index ).footer() ).html(
                        total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2})
                    );
                });
            }
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>
</html>
<?php 
} else {
    header("Location: logout.php");
}
?>
