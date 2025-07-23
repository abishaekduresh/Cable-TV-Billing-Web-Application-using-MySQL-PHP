<?php 
   session_start();
   include "dbconfig.php";
   include 'preloader.php';
//   include 'component.php';
//   include 'component2.php';
      
    if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role'])) {
        $session_username = $_SESSION['username']; 
        ?>
  
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export Bill STB No</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
    

    <div class="container mt-1">
        <!-- Nav Tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#indiv-bill" data-bs-toggle="tab"><b>&nbsp;&nbsp;Indiv Bill&nbsp;&nbsp;</b></a>
            </li>
            <li class="nav-item">
				<a class="nav-link" href="#group-bill" data-bs-toggle="tab"><b>&nbsp;&nbsp;Group Bill&nbsp;&nbsp;</b></a>
            </li>
            <li class="nav-item">
				<a class="nav-link" href="#filterMobileNo" data-bs-toggle="tab"><b>&nbsp;&nbsp;Filter Mobile No&nbsp;&nbsp;</b></a>
            </li>
            <li class="nav-item">
				<a class="nav-link" href="new/pages/ec/compare.php" target="_blank"><b>&nbsp;&nbsp;Compare STB No&nbsp;&nbsp;</b></a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content mt-3">
            <div class="tab-pane fade show active" id="indiv-bill">
                <!--h3>Indiv Bill</h3-->
                <div class="container">
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">

                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                    <form id="indivForm" method="POST">
                                        <div class="row">
                                            <!-- From Date Field in the first column -->
                                            <div class="col-md-12">
                                                <div class="mb-3">
											    	<label for="indivMSO" class="form-label">Select an MSO: *</label>
													<select class="form-select" id="indivMSO" required>
													  <option value="VK" selected>VK</option>
													  <option value="GTPL">GTPL</option>
													</select>
												</div>
											</div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
											    	<!-- <label for="indivNeededData" class="form-label">Select an MSO: *</label> -->
													<select class="form-select" id="indivNeededData" required>
													  <option value="stbno">STB No</option>
													  <option value="phone">Phone</option>
													</select>
												</div>
											</div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="indivFromDate" class="form-label">From Date: *</label>
                                                    <input type="date" class="form-control" id="indivFromDate" name="indivFromDate" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                            <!-- To Date Field in the second column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="indivToDate" class="form-label">To Date: *</label>
                                                    <input type="date" class="form-control" id="indivToDate" name="indivToDate" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <!-- From Date Field in the first column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="indivFromBillNo" class="form-label">From Bill No:</label>
                                                    <input type="number" class="form-control" id="indivFromBillNo" name="indivFromBillNo" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                            <!-- To Date Field in the second column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="indivToBillNo" class="form-label">To Bill No:</label>
                                                    <input type="number" class="form-control" id="indivToBillNo" name="indivToBillNo" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                        </div>
										<input type="hidden" id="indivFormFlag" value="1"/>
                                        <button type="submit" id="indivSubmitBtn" class="btn btn-primary">Submit</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
							
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">
							<span id="indivTextAreaSpan"></span>
							<div id="indivTextAreaDiv">
								<div class="mb-3">
								  <label for="indivTextArea" class="form-label"></label>
								  <span id="indivStbCount"></span>
								  <textarea class="form-control" id="indivTextArea" rows="5" readonly></textarea>
								</div>
								  <button type="button" id="indivCopyBtn" class="btn btn-primary">Copy</button>
								  <a id="indiv-download-link" class="btn btn-success d-none" download>Download Excel</a>
							</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="group-bill">
                <!--h3>group Bill</h3-->
                <div class="container">
                    <div class="row">
                        <!-- First Column -->
                        <div class="col-md-6">

                            <div class="container">
                                <div class="row">
                                    <div class="col-md-12">
                                    <form id="groupForm" method="POST">
                                        <div class="row">
                                            <!-- From Date Field in the first column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="groupFromDate" class="form-label">From Date:</label>
                                                    <input type="date" class="form-control" id="groupFromDate" name="groupFromDate" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                            <!-- To Date Field in the second column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="groupToDate" class="form-label">To Date:</label>
                                                    <input type="date" class="form-control" id="groupToDate" name="groupToDate" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <!-- From Date Field in the first column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="groupFromBillNo" class="form-label">From Bill No:</label>
                                                    <input type="number" class="form-control" id="groupFromBillNo" name="groupFromBillNo" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                            <!-- To Date Field in the second column -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="groupToBillNo" class="form-label">To Bill No:</label>
                                                    <input type="number" class="form-control" id="groupToBillNo" name="groupToBillNo" value="<?= $currentDate ?>">
                                                </div>
                                            </div>
                                        </div>
										<input type="hidden" id="groupFormFlag" value="1"/>
                                        <button type="submit" id="groupSubmitBtn" class="btn btn-primary">Submit</button>
                                    </form>
                                    </div>
                                </div>
                            </div>
							
                        </div>

                        <!-- Second Column -->
                        <div class="col-md-6">
							<span id="groupTextAreaSpan"></span>
							<div id="groupTextAreaDiv">
								<div class="mb-3">
								  <label for="groupTextArea" class="form-label"></label>
								  <span id="groupStbCount"></span>
								  <textarea class="form-control" id="groupTextArea" rows="5" readonly></textarea>
								</div>
								  <button type="button" id="groupCopyBtn" class="btn btn-primary">Copy</button>
								  <a id="group-download-link" class="btn btn-success d-none" download>Download Excel</a>
							</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="filterMobileNo">
                <div class="container mt-5">
                    <h2 class="mb-4">Filter Numeric Mobile Numbers (Line-separated)</h2>
                    <div class="row mb-3">
                        <div class="col-md-6">
                        <label for="textarea1" class="form-label">Textarea 1 (Must keep these) Current Month</label>
                        <textarea id="textarea1" class="form-control" rows="10"></textarea>
                        <div class="mt-2">
                            <strong>Filtered Count (Textarea 1):</strong> <span id="count1">0</span>
                        </div>
                        </div>
                        <div class="col-md-6">
                        <label for="textarea2" class="form-label">Textarea 2 (Filter from this) Previous Month</label>
                        <textarea id="textarea2" class="form-control" rows="10"></textarea>
                        <div class="mt-2">
                            <strong>Filtered Count (Textarea 2):</strong> <span id="count2">0</span>
                        </div>
                        </div>
                    </div>
                    <button class="btn btn-primary" onclick="filterNumbers()">Filter</button>
                </div>
            </div>
        </div>
    </div>
	

    <!-- Bootstrap JS Bundle (including Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function filterNumbers() {
    const ta1 = document.getElementById("textarea1").value;
    const ta2 = document.getElementById("textarea2").value;

    // Function to validate if the value is a valid 10-digit number
    const isValidMobile = val => /^\d{10}$/.test(val);

    // Parse numbers from both text areas and clean them
    const list1 = ta1.split('\n').map(v => v.trim()).filter(v => v && isValidMobile(v));
    const list2 = ta2.split('\n').map(v => v.trim()).filter(v => v && isValidMobile(v));

    const resultSet = new Set();
    const result = [];

    // Remove numbers in Textarea 2 that are present in Textarea 1
    list2.forEach(num => {
      if (!list1.includes(num) && !resultSet.has(num)) {
        result.push(num);
        resultSet.add(num);
      }
    });

    // Update Textarea 2 with the filtered values
    document.getElementById("textarea2").value = result.join('\n');
    document.getElementById("count2").innerText = result.length;

    // Update Textarea 1 with filtered values (remove duplicates)
    const uniqueList1 = [...new Set(list1)];
    document.getElementById("textarea1").value = uniqueList1.join('\n');
    document.getElementById("count1").innerText = uniqueList1.length;
  }
</script>
    <script>		
			
            const indivTextAreaDiv = document.getElementById('indivTextAreaDiv');
            const indivTextAreaSpan = document.getElementById('indivTextAreaSpan');
            indivTextAreaDiv.style.display = 'none';
            const groupTextAreaDiv = document.getElementById('groupTextAreaDiv');
            const groupTextAreaSpan = document.getElementById('groupTextAreaSpan');
            groupTextAreaDiv.style.display = 'none';

        document.getElementById('indivForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting the normal way
            const indivNeededData = document.getElementById('indivNeededData').value;
			
			const indivSubmitBtn = document.getElementById('indivSubmitBtn');
			indivSubmitBtn.textContent = 'Processing...';
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
			
			console.log(JSON.stringify(indivFormData));

            // Send form data to the API using fetch
            fetch('api/v1/getIndivBillData.php', {
                method: 'POST', // Change this to 'PUT' or 'DELETE' as needed
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(indivFormData)
            })
            .then(response => response.json())
            .then(data => {
                // Handle success
				// console.log('Success:', data);
				if(data.status == '1'){
                    console.log(data);
                    indivTextAreaDiv.style.display = 'block';
                    // Check what data is needed (either 'stbno' or 'phone')
                    if (indivNeededData == 'stbno') {
                        indivTextAreaSpan.textContent = ' ';                        
                        // Convert the response to a comma-separated string (each stbno separated by commas)
                        const displayData = data.result.map(item => item.stbno).join(',');                        
                        // Set the value of the text area
                        document.getElementById('indivTextArea').value = displayData;                        
                        // Update the count of STB numbers
                        document.getElementById('indivStbCount').textContent = "Total STB No. Count: " + data.result.length;
                    } else if (indivNeededData == 'phone') {
                        indivTextAreaSpan.textContent = ' ';                        
                        // Convert the response to a new-line separated string (each phone on a new line)
                        const displayData = data.result.map(item => item.phone).join('\n');
                        // Set the value of the text area
                        document.getElementById('indivTextArea').value = displayData;                        
                        // Update the count of phone numbers
                        document.getElementById('indivStbCount').textContent = "Total Phone No. Count: " + data.result.length;
                    }

					indivSubmitBtn.textContent = 'Submit';
					indivSubmitBtn.disabled = false;
					
					const indivDownloadLink = document.getElementById('indiv-download-link');
					indivDownloadLink.href = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + data.file;
					indivDownloadLink.download = data.filename;
					indivDownloadLink.classList.remove('d-none'); // Show the download link
					indivDownloadLink.textContent = 'Download Excel'; // Set the button text
					
					// Output the result
					//console.log(displayData);
				}else{
					indivTextAreaDiv.style.display = 'none';
					indivTextAreaSpan.textContent = data.error;
					indivSubmitBtn.textContent = 'Submit';
					indivSubmitBtn.disabled = false;
				}
            })
            .catch((error) => {
                // Handle error
                alert(error);
				console.error('Error:', error);
				indivSubmitBtn.textContent = 'Submit';
				indivSubmitBtn.disabled = false;
            });
        });
		
		document.getElementById('indivCopyBtn').addEventListener('click', function() {
			// Get the text area element
			const textArea = document.getElementById('indivTextArea');

			// Select the text area content
			textArea.select();

			// Copy the selected text
			document.execCommand('copy');

			// Change the button text to 'Copied'
			this.textContent = 'Copied...';

			// Optional: Reset the button text after a few seconds
			setTimeout(() => {
				this.textContent = 'Copy';
				document.getElementById('indivStbCount').textContent = " ";
				document.getElementById('indivTextArea').value = " ";
				indivTextAreaDiv.style.display = 'none';
			}, 2000);
		});

        document.getElementById('groupForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form from submitting the normal way
			
			const groupSubmitBtn = document.getElementById('groupSubmitBtn');
			groupSubmitBtn.textContent = 'Processing...';
			groupSubmitBtn.disabled = true;

            // Gather form data
            const groupFormData = {
                fromDate: document.getElementById('groupFromDate').value,
                toDate: document.getElementById('groupToDate').value,
                fromBillNo: document.getElementById('groupFromBillNo').value,
                toBillNo: document.getElementById('groupToBillNo').value,
                flag: document.getElementById('groupFormFlag').value
            };
			
			console.log(JSON.stringify(groupFormData));

            // Send form data to the API using fetch
            fetch('api/v1/getGroupBillData.php', {
                method: 'POST', // Change this to 'PUT' or 'DELETE' as needed
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(groupFormData)
            })
            .then(response => response.json())
            .then(data => {
                // Handle success
				console.log('Success:', data);
				if(data.status == '1'){
					groupTextAreaDiv.style.display = 'block';
					groupTextAreaSpan.textContent = ' ';
					// Convert the response to a comma-separated string
					const displayData = data.result.map(item => item.stbNo).join(',');
					// Set the value of the text area
					document.getElementById('groupTextArea').value = displayData;
					document.getElementById('groupStbCount').textContent = "Total STB No. Count: "+displayData.split(',').length;
					groupSubmitBtn.textContent = 'Submit';
					groupSubmitBtn.disabled = false;

					const groupDownloadLink = document.getElementById('group-download-link');
					groupDownloadLink.href = 'data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64,' + data.file;
					groupDownloadLink.download = data.filename;
					groupDownloadLink.classList.remove('d-none'); // Show the download link
					groupDownloadLink.textContent = 'Download Excel'; // Set the button text
					
					// Output the result
					//console.log(displayData);
				}else{
					groupTextAreaDiv.style.display = 'none';
					groupTextAreaSpan.textContent = data.error;
					groupSubmitBtn.textContent = 'Submit';
					groupSubmitBtn.disabled = false;
				}
            })
            .catch((error) => {
                // Handle error
                alert(error);
				console.error('Error:', error);
				groupSubmitBtn.textContent = 'Submit';
				groupSubmitBtn.disabled = false;
            });
        });
		
		document.getElementById('groupCopyBtn').addEventListener('click', function() {
			// Get the text area element
			const textArea = document.getElementById('groupTextArea');

			// Select the text area content
			textArea.select();

			// Copy the selected text
			document.execCommand('copy');

			// Change the button text to 'Copied'
			this.textContent = 'Copied...';

			// Optional: Reset the button text after a few seconds
			setTimeout(() => {
				this.textContent = 'Copy';
				document.getElementById('groupStbCount').textContent = " ";
				document.getElementById('groupTextArea').value = " ";
				groupTextAreaDiv.style.display = 'none';
			}, 2000);
		});
    </script>

</body>
</html>
<br/>
<?php include 'footer.php'?>


<?php }else{
    header("Location: index.php");
} ?>
