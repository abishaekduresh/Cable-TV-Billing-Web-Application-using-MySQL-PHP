<?php
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
      
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role'])) {
        $session_username = $_SESSION['username']; 
        ?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Bill STB No</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
            --text-dark: #2b2d42;
            --bg-light: #f8f9fa;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --font-family-sans-serif: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }

        body {
            font-family: var(--font-family-sans-serif);
            background-color: #f3f4f6;
            color: var(--text-dark);
        }

        /* Custom Card Styles */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            margin-bottom: 24px;
            overflow: hidden;
            transition: transform 0.2s ease;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            padding: 1rem 1.5rem;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header-gradient h5 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-size: 1.1rem;
        }
        
        /* Tabs Styles Match */
        .nav-tabs {
            border-bottom: 2px solid #e5e7eb;
            padding: 0.5rem 1rem 0;
            background: white;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6b7280 !important; /* Forced dark color */
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            margin-bottom: -2px;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .nav-tabs .nav-link:hover {
            color: var(--primary-color) !important;
            border-color: transparent;
            background-color: rgba(67, 97, 238, 0.05);
        }
        .nav-tabs .nav-link.active {
            color: var(--primary-color) !important;
            background: white;
            border-bottom: 3px solid var(--primary-color);
        }

        /* Form Controls */
        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6b7280;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control, .form-select {
            border-radius: 8px;
            padding: 0.6rem 1rem;
            border: 1px solid #e5e7eb;
            font-weight: 500;
            font-size: 0.9rem;
            color: var(--text-dark);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(67, 97, 238, 0.25);
            transition: all 0.3s;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(67, 97, 238, 0.35);
        }
        
        /* Other Components */
         .textarea-container {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            height: 100%;
        }
        .result-textarea {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            color: #374151;
        }
        .count-badge {
            background: var(--primary-color);
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        
        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
            text-transform: uppercase;
        }
    </style>

</head>
<body>
    
    <?php if($_SESSION['role'] == 'admin'){
        include 'admin-menu-bar.php';
        include 'admin-menu-btn.php';
    }else{
        include 'menu-bar.php';
        include 'sub-menu-btn.php';
    }
    ?>
    
    <div class="container-fluid px-4 mt-4 mb-5">
        <div class="custom-card">
            <div class="card-header-gradient">
                <h5><i class="fas fa-file-export me-2"></i>Data Import & Export</h5>
                <small class="text-white-50">Generate reports and export data</small>
            </div>
            
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#indiv-bill" role="tab">
                        <i class="fas fa-user me-2"></i>Individual Bill
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#group-bill" role="tab">
                        <i class="fas fa-users me-2"></i>Group Bill
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#dynamic-export" role="tab">
                        <i class="fas fa-magic me-2"></i>Dynamic Export
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="card-body p-4">
                <div class="tab-content">
                    
                    <!-- INDIV BILL TAB -->
                    <div class="tab-pane fade show active" id="indiv-bill" role="tabpanel">
                        <div class="row g-4">
                            <!-- Form Column -->
                            <div class="col-lg-5 order-lg-1 border-end-lg">
                                <h6 class="section-title"><i class="fas fa-filter me-2"></i>Search Filters</h6>
                                <form id="indivForm" method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="indivMSO" class="form-label">MSO</label>
                                                <select class="form-select shadow-sm" id="indivMSO" required>
                                                    <option value="VK" selected>VK</option>
                                                    <option value="GTPL">GTPL</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="indivNeededData" class="form-label">Data Type</label>
                                                <select class="form-select shadow-sm" id="indivNeededData" required>
                                                    <option value="stbno">STB No</option>
                                                    <option value="phone">Phone</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="indivFromDate" class="form-label">From Date</label>
                                                <input type="date" class="form-control shadow-sm" id="indivFromDate" name="indivFromDate" value="<?= $currentDate ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="indivToDate" class="form-label">To Date</label>
                                                <input type="date" class="form-control shadow-sm" id="indivToDate" name="indivToDate" value="<?= $currentDate ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="indivFromBillNo" class="form-label">From Bill No</label>
                                                <input type="number" class="form-control shadow-sm" id="indivFromBillNo" name="indivFromBillNo" placeholder="Start">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="indivToBillNo" class="form-label">To Bill No</label>
                                                <input type="number" class="form-control shadow-sm" id="indivToBillNo" name="indivToBillNo" placeholder="End">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="indivFormFlag" value="1"/>
                                    <div class="mt-4">
                                        <button type="submit" id="indivSubmitBtn" class="btn btn-primary-custom w-100 text-white">
                                            <i class="fas fa-search me-2"></i>Generate Report
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Result Column -->
                            <div class="col-lg-7 order-lg-2">
                                <span id="indivTextAreaSpan" class="text-danger fw-bold"></span>
                                <div id="indivTextAreaDiv" style="display: none;" class="h-100">
                                    <div class="textarea-container d-flex flex-column h-100 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list-alt me-2 text-primary"></i>Results</h6>
                                            <span id="indivStbCount" class="count-badge"></span>
                                        </div>
                                        <textarea class="form-control result-textarea flex-grow-1 mb-3" id="indivTextArea" rows="10" readonly></textarea>
                                        <div class="d-flex gap-2">
                                            <button type="button" id="indivCopyBtn" class="btn btn-outline-primary flex-grow-1">
                                                <i class="fas fa-copy me-2"></i>Copy to Clipboard
                                            </button>
                                            <a id="indiv-download-link" class="btn btn-success flex-grow-1 d-none" download>
                                                <i class="fas fa-file-excel me-2"></i>Download Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Placeholder when empty -->
                                <div id="indivPlaceholder" class="h-100 d-flex flex-column justify-content-center align-items-center text-muted p-5 bg-light rounded-3 border border-dashed">
                                    <i class="fas fa-file-alt fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-bold">Run a search to view results here</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GROUP BILL TAB -->
                    <div class="tab-pane fade" id="group-bill" role="tabpanel">
                        <div class="row g-4">
                            <!-- Form Column -->
                            <div class="col-lg-5 order-lg-1 border-end-lg">
                                <h6 class="section-title"><i class="fas fa-layer-group me-2"></i>Group Bill Search</h6>
                                <form id="groupForm" method="POST">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groupFromDate" class="form-label">From Date</label>
                                                <input type="date" class="form-control shadow-sm" id="groupFromDate" name="groupFromDate" value="<?= $currentDate ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groupToDate" class="form-label">To Date</label>
                                                <input type="date" class="form-control shadow-sm" id="groupToDate" name="groupToDate" value="<?= $currentDate ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groupFromBillNo" class="form-label">From Bill No</label>
                                                <input type="number" class="form-control shadow-sm" id="groupFromBillNo" name="groupFromBillNo" placeholder="Start">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="groupToBillNo" class="form-label">To Bill No</label>
                                                <input type="number" class="form-control shadow-sm" id="groupToBillNo" name="groupToBillNo" placeholder="End">
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="groupFormFlag" value="1"/>
                                    <div class="mt-4">
                                        <button type="submit" id="groupSubmitBtn" class="btn btn-primary-custom w-100 text-white">
                                            <i class="fas fa-search me-2"></i>Generate Report
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- Result Column -->
                            <div class="col-lg-7 order-lg-2">
                                <span id="groupTextAreaSpan" class="text-danger fw-bold"></span>
                                <div id="groupTextAreaDiv" style="display: none;" class="h-100">
                                    <div class="textarea-container d-flex flex-column h-100 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-list-alt me-2 text-primary"></i>Results</h6>
                                            <span id="groupStbCount" class="count-badge"></span>
                                        </div>
                                        <textarea class="form-control result-textarea flex-grow-1 mb-3" id="groupTextArea" rows="10" readonly></textarea>
                                        <div class="d-flex gap-2">
                                            <button type="button" id="groupCopyBtn" class="btn btn-outline-primary flex-grow-1">
                                                <i class="fas fa-copy me-2"></i>Copy to Clipboard
                                            </button>
                                            <a id="group-download-link" class="btn btn-success flex-grow-1 d-none" download>
                                                <i class="fas fa-file-excel me-2"></i>Download Excel
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                 <!-- Placeholder when empty -->
                                 <div id="groupPlaceholder" class="h-100 d-flex flex-column justify-content-center align-items-center text-muted p-5 bg-light rounded-3 border border-dashed">
                                    <i class="fas fa-file-invoice fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-bold">Run a search to view results here</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- DYNAMIC EXPORT TAB -->
                    <div class="tab-pane fade" id="dynamic-export" role="tabpanel">
                        <div class="row g-4">
                            <!-- Helper / Config Column -->
                            <div class="col-lg-4 border-end-lg">
                                <h6 class="section-title"><i class="fas fa-cogs me-2"></i>Export Configuration</h6>
                                <form id="dynamicForm">
                                    
                                    <!-- Source Selection -->
                                    <div class="mb-3">
                                        <label class="form-label">Data Source</label>
                                        <select class="form-select shadow-sm" id="dynSource" onchange="updateColumns()">
                                            <option value="" selected disabled>Select Source</option>
                                            <option value="customer">Customer List</option>
                                            <option value="indiv_bill">Individual Bills</option>
                                            <option value="group_bill">Group Bills</option>
                                        </select>
                                    </div>

                                    <!-- Filters Area (Dynamic based on source) -->
                                    <div id="dynFilters" class="mb-3 p-3 bg-white rounded border shadow-sm">
                                        <h6 class="text-primary small fw-bold text-uppercase mb-2">Filters</h6>
                                        
                                        <!-- Date Range (Initially Hidden) -->
                                        <div id="dynDateFilters" class="d-none">
                                            <div class="mb-2">
                                                <label class="form-label small mb-1 text-muted">From Date</label>
                                                <input type="date" class="form-control form-control-sm" id="dynFromDate" value="<?= $currentDate ?>">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small mb-1 text-muted">To Date</label>
                                                <input type="date" class="form-control form-control-sm" id="dynToDate" value="<?= $currentDate ?>">
                                            </div>
                                        </div>

                                        <!-- MSO Filter -->
                                        <div class="mb-2">
                                            <label class="form-label small mb-1 text-muted">MSO</label>
                                            <select class="form-select form-select-sm" id="dynMSO">
                                                <option value="all">All</option>
                                                <option value="VK">VK</option>
                                                <option value="GTPL">GTPL</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Column Selection -->
                                    <div class="mb-3">
                                        <label class="form-label">Select Columns to Export</label>
                                        <div class="checkbox-container p-2 shadow-sm" style="max-height: 200px; overflow-y: auto;" id="dynColumnContainer">
                                            <div class="text-muted small text-center p-3">Select a source first</div>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <span class="btn btn-link btn-sm p-0 text-decoration-none" onclick="selectAllCols(true)">Select All</span> / 
                                            <span class="btn btn-link btn-sm p-0 text-decoration-none" onclick="selectAllCols(false)">Clear</span>
                                        </div>
                                    </div>

                                    <!-- Output Format -->
                                    <div class="mb-4">
                                        <label class="form-label">Output Separator</label>
                                        <select class="form-select shadow-sm" id="dynSeparator">
                                            <option value="newline" selected>New Line (WhatsApp Friendly)</option>
                                            <option value="comma">Comma Separated</option>
                                            <option value="pipe">Pipe (|) Separated</option>
                                            <option value="tab">Tab Separated</option>
                                        </select>
                                    </div>

                                    <button type="submit" id="dynSubmitBtn" class="btn btn-primary-custom w-100 text-white">
                                        <i class="fas fa-bolt me-2"></i>Generate Dynamic Data
                                    </button>
                                </form>
                            </div>

                            <!-- Result Column -->
                            <div class="col-lg-8">
                                <span id="dynTextAreaSpan" class="text-danger fw-bold"></span>
                                <div id="dynTextAreaDiv" style="display: none;" class="h-100">
                                    <div class="textarea-container d-flex flex-column h-100 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0 fw-bold text-dark text-uppercase"><i class="fas fa-terminal me-2 text-primary"></i>Generated Output</h6>
                                            <span id="dynCount" class="count-badge">0 Rows</span>
                                        </div>
                                        <textarea class="form-control result-textarea flex-grow-1 mb-3" id="dynTextArea" rows="15" readonly></textarea>
                                        <div class="d-flex gap-2">
                                            <button type="button" id="dynCopyBtn" class="btn btn-outline-primary flex-grow-1">
                                                <i class="fas fa-copy me-2"></i>Copy to Clipboard
                                            </button>
                                            <button type="button" id="dynCleanBtn" class="btn btn-outline-warning flex-grow-1" title="Remove duplicates, empty lines, and non-10-digit numbers">
                                                <i class="fas fa-broom me-2"></i>Clean Numbers
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div id="dynPlaceholder" class="h-100 d-flex flex-column justify-content-center align-items-center text-muted p-5 bg-light rounded-3 border border-dashed">
                                    <i class="fas fa-magic fa-3x mb-3 text-secondary opacity-50"></i>
                                    <p class="mb-0 fw-bold">Configure your export options to view data</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	

    <!-- JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>		
			
            const indivTextAreaDiv = document.getElementById('indivTextAreaDiv');
            const indivPlaceholder = document.getElementById('indivPlaceholder');
            const indivTextAreaSpan = document.getElementById('indivTextAreaSpan');
            
            const groupTextAreaDiv = document.getElementById('groupTextAreaDiv');
            const groupPlaceholder = document.getElementById('groupPlaceholder');
            const groupTextAreaSpan = document.getElementById('groupTextAreaSpan');

        document.getElementById('indivForm').addEventListener('submit', function(event) {
            event.preventDefault(); 
            const indivNeededData = document.getElementById('indivNeededData').value;
			
			const indivSubmitBtn = document.getElementById('indivSubmitBtn');
            const originalBtnText = indivSubmitBtn.innerHTML;
			indivSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
			indivSubmitBtn.disabled = true;

            // Gather form data
            const indivFormData = {
                fromDate: document.getElementById('indivFromDate').value,
                toDate: document.getElementById('indivToDate').value,
                fromBillNo: document.getElementById('indivFromBillNo').value,
                toBillNo: document.getElementById('indivToBillNo').value,
                indivMSO: document.getElementById('indivMSO').value,
                flag: document.getElementById('indivFormFlag').value
            };
			
            // Send form data to the API using fetch
            fetch('api/v1/getIndivBillData.php', {
                method: 'POST', 
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(indivFormData)
            })
            .then(response => response.json())
            .then(data => {
                // Handle success
				if(data.status == '1'){
                    indivTextAreaDiv.style.display = 'block';
                    indivPlaceholder.style.display = 'none'; // Hide placeholder

                    // Check what data is needed (either 'stbno' or 'phone')
                    if (indivNeededData == 'stbno') {
                        indivTextAreaSpan.textContent = '';                        
                        // Convert the response to a comma-separated string
                        const displayData = data.result.map(item => item.stbno).join(',');                        
                        document.getElementById('indivTextArea').value = displayData;                        
                        document.getElementById('indivStbCount').textContent = "Count: " + data.result.length;
                    } else if (indivNeededData == 'phone') {
                        indivTextAreaSpan.textContent = '';                        
                        // Convert the response to a new-line separated string
                        const displayData = data.result.map(item => item.phone).join('\n');
                        document.getElementById('indivTextArea').value = displayData;                        
                        document.getElementById('indivStbCount').textContent = "Count: " + data.result.length;
                    }

					indivSubmitBtn.innerHTML = originalBtnText;
					indivSubmitBtn.disabled = false;
					
					const indivDownloadLink = document.getElementById('indiv-download-link');
					indivDownloadLink.href = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + data.file;
					indivDownloadLink.download = data.filename;
					indivDownloadLink.classList.remove('d-none'); 
					
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        text: 'Indiv Data generated successfully!',
                        showConfirmButton: false,
                        timer: 2000
                    });

				}else{
					// indivTextAreaDiv.style.display = 'none';
                    // indivPlaceholder.style.display = 'flex';
					// indivTextAreaSpan.textContent = data.error;
					indivSubmitBtn.innerHTML = originalBtnText;
					indivSubmitBtn.disabled = false;
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        text: data.error || 'No records found for the selected criteria.',
                        showConfirmButton: false,
                        timer: 2000
                    });
				}
            })
            .catch((error) => {
                // Handle error
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    text: 'An error occurred: ' + error,
                    showConfirmButton: false,
                    timer: 2000
                });
				console.error('Error:', error);
				indivSubmitBtn.innerHTML = originalBtnText;
				indivSubmitBtn.disabled = false;
            });
        });
		
		document.getElementById('indivCopyBtn').addEventListener('click', function() {
			const textArea = document.getElementById('indivTextArea');
            if(!textArea.value.trim()) return;

			textArea.select();
			document.execCommand('copy');

			this.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            this.classList.replace('btn-outline-primary', 'btn-success');
            
            setTimeout(() => {
				this.innerHTML = '<i class="fas fa-copy me-2"></i>Copy to Clipboard';
                this.classList.replace('btn-success', 'btn-outline-primary');
			}, 2000);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Copied to clipboard',
                showConfirmButton: false,
                timer: 2000
            });
		});

        document.getElementById('groupForm').addEventListener('submit', function(event) {
            event.preventDefault(); 
			
			const groupSubmitBtn = document.getElementById('groupSubmitBtn');
            const originalBtnText = groupSubmitBtn.innerHTML;
			groupSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
			groupSubmitBtn.disabled = true;

            // Gather form data
            const groupFormData = {
                fromDate: document.getElementById('groupFromDate').value,
                toDate: document.getElementById('groupToDate').value,
                fromBillNo: document.getElementById('groupFromBillNo').value,
                toBillNo: document.getElementById('groupToBillNo').value,
                flag: document.getElementById('groupFormFlag').value
            };
			
            // Send form data to the API using fetch
            fetch('api/v1/getGroupBillData.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(groupFormData)
            })
            .then(response => response.json())
            .then(data => {
                // Handle success
				if(data.status == '1'){
					groupTextAreaDiv.style.display = 'block';
                    groupPlaceholder.style.display = 'none';
					groupTextAreaSpan.textContent = '';
					
                    // Convert the response to a comma-separated string
					const displayData = data.result.map(item => item.stbNo).join(',');
					
                    document.getElementById('groupTextArea').value = displayData;
					document.getElementById('groupStbCount').textContent = "Count: "+displayData.split(',').length;
					
                    groupSubmitBtn.innerHTML = originalBtnText;
					groupSubmitBtn.disabled = false;

					const groupDownloadLink = document.getElementById('group-download-link');
					groupDownloadLink.href = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + data.file;
					groupDownloadLink.download = data.filename;
					groupDownloadLink.classList.remove('d-none');
					
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        text: 'Group Data generated successfully!',
                        showConfirmButton: false,
                        timer: 2000
                    });
				}else{
					groupTextAreaDiv.style.display = 'none';
                    groupPlaceholder.style.display = 'flex';
					groupTextAreaSpan.textContent = data.error;
					groupSubmitBtn.innerHTML = originalBtnText;
					groupSubmitBtn.disabled = false;
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        text: data.error || 'No records found for the selected criteria.',
                        showConfirmButton: false,
                        timer: 2000
                    });
				}
            })
            .catch((error) => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    text: 'An error occurred: ' + error,
                    showConfirmButton: false,
                    timer: 2000
                });
				console.error('Error:', error);
				groupSubmitBtn.innerHTML = originalBtnText;
				groupSubmitBtn.disabled = false;
            });
        });
		
		document.getElementById('groupCopyBtn').addEventListener('click', function() {
			const textArea = document.getElementById('groupTextArea');
            if(!textArea.value.trim()) return;

			textArea.select();
			document.execCommand('copy');

			this.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            this.classList.replace('btn-outline-primary', 'btn-success');
            
			setTimeout(() => {
				this.innerHTML = '<i class="fas fa-copy me-2"></i>Copy to Clipboard';
                this.classList.replace('btn-success', 'btn-outline-primary');
			}, 2000);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Copied to clipboard',
                showConfirmButton: false,
                timer: 2000
            });
		});

        /* ================= DYNAMIC EXPORT LOGIC ================= */
        
        const colDefinitions = {
            'customer': [
                { id: 'stbno', text: 'STB No' },
                { id: 'name', text: 'Name' },
                { id: 'phone', text: 'Phone' },
                { id: 'mso', text: 'MSO' },
                { id: 'customer_area_code', text: 'Area' },
                { id: 'amount', text: 'Amount' }
            ],
            'indiv_bill': [
                { id: 'billNo', text: 'Bill No' },
                { id: 'due_month_timestamp', text: 'Bill Date' },
                { id: 'stbno', text: 'STB No' },
                { id: 'name', text: 'Name' },
                { id: 'phone', text: 'Phone' },
                { id: 'mso', text: 'MSO' },
                { id: 'Rs', text: 'Amount' },
                { id: 'status', text: 'Status' }
            ],
            'group_bill': [
                { id: 'billNo', text: 'Bill No' },
                { id: 'date', text: 'Bill Date' },
                { id: 'group_id', text: 'Group Name' }, 
                { id: 'name', text: 'Name' },
                { id: 'stbNo', text: 'STB No' },
                { id: 'mso', text: 'MSO' }
            ]
        };

        function updateColumns() {
            const source = document.getElementById('dynSource').value;
            const container = document.getElementById('dynColumnContainer');
            const dateFilters = document.getElementById('dynDateFilters');
            
            container.innerHTML = '';
            
            // Show/Hide Date Filters based on source
            if(source === 'indiv_bill' || source === 'group_bill') {
                dateFilters.classList.remove('d-none');
            } else {
                dateFilters.classList.add('d-none');
            }

            if (!source || !colDefinitions[source]) {
                container.innerHTML = '<div class="text-muted small text-center p-3">Select a source first</div>';
                return;
            }

            colDefinitions[source].forEach(col => {
                const div = document.createElement('div');
                div.className = 'form-check';
                div.innerHTML = `
                    <input class="form-check-input dyn-col-check" type="checkbox" value="${col.id}" id="col_${col.id}">
                    <label class="form-check-label" for="col_${col.id}">${col.text}</label>
                `;
                container.appendChild(div);
            });
        }

        function selectAllCols(check) {
            document.querySelectorAll('.dyn-col-check').forEach(el => el.checked = check);
        }

        document.getElementById('dynamicForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const source = document.getElementById('dynSource').value;
            if(!source) {
                Swal.fire('Required', 'Please select a Data Source', 'warning');
                return;
            }

            const selectedCols = Array.from(document.querySelectorAll('.dyn-col-check:checked')).map(el => el.value);
            if(selectedCols.length === 0) {
                Swal.fire('Required', 'Please select at least one column to export', 'warning');
                return;
            }
            
            const btn = document.getElementById('dynSubmitBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Generating...';
            btn.disabled = true;

            const payload = {
                source: source,
                columns: selectedCols,
                separator: document.getElementById('dynSeparator').value,
                fromDate: document.getElementById('dynFromDate').value,
                toDate: document.getElementById('dynToDate').value,
                mso: document.getElementById('dynMSO').value
            };

            fetch('api/v1/getDynamicData.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.status == '1') {
                    document.getElementById('dynTextAreaDiv').style.display = 'block';
                    document.getElementById('dynPlaceholder').style.display = 'none';
                    document.getElementById('dynTextArea').value = data.formatted_text;
                    document.getElementById('dynCount').innerText = data.result_count + " Rows";
                    
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        text: 'Dynamic Data generated!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                } else {
                     Swal.fire('Error', data.error || 'Unknown error occurred', 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Failed to fetch data: ' + err, 'error');
                console.error(err);
            })
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            });
        });

        document.getElementById('dynCopyBtn').addEventListener('click', function() {
            const textArea = document.getElementById('dynTextArea');
            if(!textArea.value.trim()) return;
            
            textArea.select();
            document.execCommand('copy');
            
            this.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            this.classList.replace('btn-outline-primary', 'btn-success');
            
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy me-2"></i>Copy to Clipboard';
                this.classList.replace('btn-success', 'btn-outline-primary');
            }, 2000);

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                text: 'Copied to clipboard!',
                showConfirmButton: false,
                timer: 2000
            });
        });

        document.getElementById('dynCleanBtn').addEventListener('click', function() {
            const textArea = document.getElementById('dynTextArea');
            const rawValue = textArea.value;
            
            if(!rawValue.trim()) {
                Swal.fire('Empty', 'Nothing to clean.', 'info');
                return;
            }

            const lines = rawValue.split(/\r?\n/);
            const originalCount = lines.length;
            
            const cleanList = [];
            const duplicates = [];
            const invalidFormat = []; // Non-10-digit
            const seen = new Set();
            
            // Regex for exactly 10 digits
            const tenDigitRegex = /^\d{10}$/;

            lines.forEach(line => {
                const trimmed = line.trim();
                if(!trimmed) return; // Skip empty
                
                if (seen.has(trimmed)) {
                    duplicates.push(trimmed);
                } else if (!tenDigitRegex.test(trimmed)) {
                    invalidFormat.push(trimmed);
                    // Decide: do we keep invalid format lines? 
                    // User Request: "remove ... non 10 digit ... numbers"
                    // Implication: If it's not a 10 digit number, remove it.
                } else {
                    seen.add(trimmed);
                    cleanList.push(trimmed);
                }
            });

            // Update Text Area
            textArea.value = cleanList.join('\n');
            document.getElementById('dynCount').innerText = cleanList.length + " Rows";

            // Prepare Summary HTML
            let summaryHtml = `<div class="text-start">
                <p><strong>Original Rows:</strong> ${originalCount}</p>
                <p><strong>Cleaned Rows:</strong> ${cleanList.length}</p>
                <hr>
                <p class="text-danger mb-1"><strong>Removed (${duplicates.length + invalidFormat.length}):</strong></p>
                <ul style="max-height: 150px; overflow-y: auto; font-size: 0.9em;">`;

            if(duplicates.length > 0) {
                summaryHtml += `<li><span class="badge bg-warning text-dark">Duplicate</span> ${duplicates.length} items</li>`;
            }
            if(invalidFormat.length > 0) {
                 summaryHtml += `<li><span class="badge bg-danger">Invalid (Non-10-digit)</span> ${invalidFormat.length} items</li>`;
                 // Optional: List a few examples?
                 // invalidFormat.slice(0, 5).forEach(v => summaryHtml += `<li><small class="text-muted">${v}</small></li>`);
            }
            
            summaryHtml += `</ul></div>`;
            
            // If strictly detailed list is needed:
             if(invalidFormat.length > 0 || duplicates.length > 0){
                let details = "";
                if(duplicates.length > 0) details += `\nDuplicates:\n${duplicates.join(', ')}`;
                if(invalidFormat.length > 0) details += `\nInvalid:\n${invalidFormat.join(', ')}`;
                // console.log(details); 
             }

            Swal.fire({
                title: 'Cleanup Complete',
                html: summaryHtml,
                icon: 'success'
            });
        });

        // Toggle Clean Button Visibility
        const dynSeparator = document.getElementById('dynSeparator');
        const dynCleanBtn = document.getElementById('dynCleanBtn');

        function toggleCleanBtn() {
            if(dynSeparator.value === 'newline') {
                dynCleanBtn.style.display = 'block'; // or inline-block, but block inside flex item helpful or just remove display none 
                dynCleanBtn.classList.remove('d-none');
            } else {
                dynCleanBtn.classList.add('d-none');
            }
        }

        dynSeparator.addEventListener('change', toggleCleanBtn);
        // Initial check
        toggleCleanBtn();

    </script>

</body>
</html>
<br/>
<?php include 'footer.php'?>


<?php }else{
    header("Location: logout.php");
} ?>
