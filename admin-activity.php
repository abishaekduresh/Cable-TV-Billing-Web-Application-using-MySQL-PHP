<?php 
session_start();
include "dbconfig.php";
include "component.php";
include 'preloader.php';

if (isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {    
    $session_username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'favicon.php'; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity Log</title>
    
    <!-- Premium UI Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #06d6a0;
            --danger-color: #ef476f;
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

        .main-container {
            padding: 1rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Card Styles */
        .custom-card {
            background: white;
            border-radius: 16px;
            border: none;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }

        .card-header-gradient {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.25rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-header-gradient h4 {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-dark);
        }

        /* Table Styling */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-custom th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            color: #64748b;
            padding: 1rem;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .table-custom td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.9rem;
            color: #334155;
        }
        .table-custom tr:hover {
            background: #f8fafc;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.8rem;
            margin-right: 0.75rem;
        }
    </style>
</head>
<body>

<?php
    include 'admin-menu-bar.php';
    echo '<br>';
    include 'admin-menu-btn.php';

    // Query to fetch the last 50 data from the database
    $query = "SELECT * FROM user_activity WHERE date = CURDATE() ORDER BY id DESC";
    $result = mysqli_query($con, $query);
?>

    <div class="main-container">
        
        <div class="custom-card">
            <div class="card-header-gradient">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="bi bi-activity text-primary fs-5"></i>
                    </div>
                    <div>
                        <h4 class="mb-0">User Activity Log</h4>
                        <small class="text-muted">Monitoring recent actions</small>
                    </div>
                </div>
                
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm">
                        <i class="bi bi-calendar-event me-2"></i><?php echo date('d M Y'); ?>
                    </span>
                    <button class="btn btn-sm btn-light border shadow-sm rounded-circle p-2" onclick="location.reload()" title="Refresh Data">
                        <i class="bi bi-arrow-clockwise text-primary"></i>
                    </button>
                </div>
            </div>
            
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action Performed</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(mysqli_num_rows($result) > 0) {
                                $serialNumber = 1;
                                while ($row = mysqli_fetch_assoc($result)) : 
                                    // Extract Initials for Avatar
                                    $initial = strtoupper(substr($row['userName'], 0, 1));
                            ?>
                                <tr>
                                    <td class="text-muted small ps-4"><?= $serialNumber++?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-clock text-muted me-2"></i>
                                            <span class="fw-bold text-dark"><?= date("h:i A", strtotime($row['time'])); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar shadow-sm"><?= $initial ?></div>
                                            <span class="fw-bold text-dark"><?= $row['userName']; ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-secondary"><?= $row['action']; ?></span>
                                    </td>
                                </tr>
                            <?php 
                                endwhile; 
                            } else {
                                echo '<tr><td colspan="4" class="text-center py-5 text-muted">No activity recorded today.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php include 'footer.php'?>
</body>
</html>

<?php } else {
    header("Location: index.php");
} ?>
