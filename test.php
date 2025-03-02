<?php

// require_once 'dbconfig.php';
// require_once 'component.php';

// echo json_encode(getUserGroupBillPayModeData('2024-12-05', '23A001', 'cash'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select2 with API Example</title>
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Select2 Bootstrap 5 Theme -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
</head>
<body>
    <select id="customerAreaName" class="form-select" style="width: 300px;" required>
        <option value="">Select customer area</option>
    </select>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#customerAreaName').select2({
                placeholder: 'Select Customer Area',
                allowClear: true,
                width: '100%',
                theme: 'bootstrap5',
                minimumInputLength: 0, // Require at least 1 character to start searching
                ajax: {
                    url: './api/customer/v1/fetch_customer_area.php',
                    method: 'GET',
                    dataType: 'json',
                    delay: 250, // Delay in milliseconds before sending the request
                    data: function(params) {
                        return {
                            q: params.term // Send the search query to the API
                        };
                    },
                    processResults: function(response) {
                        console.log("API Response:", response); // Debug the API response

                        // Check if the API returned valid data
                        if (response.status && Array.isArray(response.data) && response.data.length > 0) {
                            return {
                                results: response.data.map(area => ({
                                    id: area.customer_area_id, // Use the ID from the API
                                    text: area.customer_area_name + ' (' + area.customer_area_code + ')' // Display name and code
                                }))
                            };
                        } else {
                            return {
                                results: [] // Return empty results if no data is found
                            };
                        }
                    },
                    cache: true // Cache results to reduce API calls
                }
            });
        });
    </script>
</body>
</html>