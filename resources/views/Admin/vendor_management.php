<?php
session_start();
require_once '../mydatabase/conn.php'; // Your database connection file

// Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header("Location: admin_login.php");
//     exit;
// }

// Handle actions (approve, block, delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        
        switch ($_POST['action']) {
            case 'approve':
                $stmt = $conn->prepare("UPDATE users SET status = 'active', verified = TRUE WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Vendor approved successfully";
                break;
                
            case 'block':
                $stmt = $conn->prepare("UPDATE users SET status = 'blocked' WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Vendor blocked successfully";
                break;
                
            case 'unblock':
                $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Vendor unblocked successfully";
                break;
                
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND type = 'vendor'");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Vendor deleted successfully";
                break;
                
            case 'approve_documents':
                $stmt = $conn->prepare("UPDATE vendor_documents SET status = 'approved', reviewed_at = NOW() WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Documents approved successfully";
                break;
        }
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query to fetch vendors
$query = "SELECT u.user_id, u.username, u.email, u.phone, u.status, u.verified, u.created_at, 
                 vbi.store_name, vbi.profile_picture, vbi.rating,
                 COUNT(DISTINCT vp.id) as product_count,
                 COUNT(DISTINCT o.id) as order_count,
                 SUM(o.total_amount) as total_earnings
          FROM users u
          JOIN vendor_basic_info vbi ON u.user_id = vbi.user_id
          LEFT JOIN vendor_products vp ON u.user_id = vp.user_id
          LEFT JOIN orders o ON vp.id = o.product_id
          WHERE u.type = 'vendor'";

$params = [];
$types = '';

// Apply filters
if (!empty($search_term)) {
    $query .= " AND (u.username LIKE ? OR u.email LIKE ? OR vbi.store_name LIKE ? OR u.phone LIKE ?)";
    $search_param = "%$search_term%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
    $types .= 'ssss';
}

if ($status_filter != 'all') {
    $query .= " AND u.status = ?";
    $params[] = $status_filter;
    $types .= 's';
}

$query .= " GROUP BY u.user_id";

// Prepare and execute query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$vendors = $result->fetch_all(MYSQLI_ASSOC);

// Get stats for summary cards
$stats_query = "SELECT 
    COUNT(*) as total_vendors,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_vendors,
    SUM(CASE WHEN status = 'blocked' THEN 1 ELSE 0 END) as blocked_vendors,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_vendors
    FROM users WHERE type = 'vendor'";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

$products_query = "SELECT COUNT(*) as total_products FROM vendor_products";
$products_result = $conn->query($products_query);
$products_stats = $products_result->fetch_assoc();

$earnings_query = "SELECT SUM(total_amount) as total_earnings FROM orders";
$earnings_result = $conn->query($earnings_query);
$earnings_stats = $earnings_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vendor Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        :root {
            --sidebar-width: 260px;
            --sidebar-bg: #2c3e50;
            --sidebar-color: #ecf0f1;
            --sidebar-active-bg: #34495e;
            --sidebar-hover-bg: #3d566e;
            --header-bg: #ffffff;
            --content-bg: #f8f9fa;
            --card-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            --primary-color: #3498db;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #1abc9c;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--content-bg);
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            position: absolute;
            left: 0;
            top: 0;
            /* height: 100vh; */
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            transition: all 0.3s;
            z-index: 1000;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.1);
        }

        #sidebar .sidebar-header {
            padding: 1.5rem 1rem;
            background: rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        #sidebar .sidebar-header h3 {
            margin-bottom: 0;
            font-weight: 600;
        }

        #sidebar ul.components {
            padding: 1rem 0;
        }

        #sidebar ul li {
            padding: 0.5rem 1.5rem;
        }

        #sidebar ul li a {
            padding: 0.75rem 1rem;
            color: var(--sidebar-color);
            text-decoration: none;
            display: block;
            border-radius: 5px;
            transition: all 0.3s;
        }

        #sidebar ul li a:hover {
            background: var(--sidebar-hover-bg);
            color: white;
        }

        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        #sidebar ul li.active > a {
            background: var(--sidebar-active-bg);
            font-weight: 500;
        }

        #sidebar ul li a .badge {
            float: right;
            margin-top: 3px;
        }

        /* Content Styles */
        #content {
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        .navbar {
            padding: 0.75rem 1.5rem;
            background: var(--header-bg);
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .main-content {
            padding: 2rem;
        }

        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border-radius: 10px 10px 0 0 !important;
        }

        /* Summary Cards */
        .summary-card {
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--card-shadow);
            background: white;
            transition: transform 0.3s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
        }

        .summary-card i {
            font-size: 2rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .summary-card .card-title {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .summary-card .card-value {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0;
        }

        /* Status Badges */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-active {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
        }

        .status-pending {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }

        .status-blocked {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
        }

        /* Table Styles */
        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            border-top: none;
            border-bottom: 1px solid #dee2e6;
            padding: 1rem 0.75rem;
            color: #495057;
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }

        /* Action Buttons */
        .action-btn {
            padding: 0.35rem 0.65rem;
            margin: 0 2px;
            font-size: 0.75rem;
            border-radius: 4px;
            transition: all 0.2s;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Custom Colors */
        .bg-primary-light {
            background-color: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
        }

        .bg-success-light {
            background-color: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }

        .bg-warning-light {
            background-color: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }

        .bg-danger-light {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }

        .bg-info-light {
            background-color: rgba(26, 188, 156, 0.1);
            color: var(--info-color);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            #sidebar {
                margin-left: -var(--sidebar-width);
            }
            #sidebar.active {
                margin-left: 0;
            }
            #content {
                width: 100%;
                margin-left: 0;
            }
            #content.active {
                margin-left: var(--sidebar-width);
                width: calc(100% - var(--sidebar-width));
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li class="active">
                <a href="admin_vendors.php"><i class="fas fa-users-cog"></i> Vendors</a>
            </li>
            <li>
                <a href="admin_customers.php"><i class="fas fa-users"></i> Customers</a>
            </li>
            <li>
                <a href="admin_products.php"><i class="fas fa-box"></i> Products</a>
            </li>
            <li>
                <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            </li>
            <li>
                <a href="admin_settings.php"><i class="fas fa-cog"></i> Admin Settings</a>
            </li>
        </ul>
    </nav>

    <!-- Page Content -->
    <div id="content">
        <!-- Top Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <button type="button" id="sidebarCollapse" class="btn btn-light d-lg-none">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="d-flex align-items-center ms-auto">
                    <div class="dropdown me-3">
                        <a href="#" class="dropdown-toggle text-dark" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#">New order received</a></li>
                            <li><a class="dropdown-item" href="#">Vendor approval needed</a></li>
                            <li><a class="dropdown-item" href="#">System update available</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle d-flex align-items-center text-dark text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/40" width="32" height="32" class="rounded-circle me-2">
                            <span><?php echo $_SESSION['admin_name'] ?? 'Admin'; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-users-cog me-2"></i> Vendor Management</h2>
                <a href="add_vendor.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add New Vendor</a>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-primary-light">
                        <i class="fas fa-users text-primary"></i>
                        <h6 class="card-title">Total Vendors</h6>
                        <h3 class="card-value"><?php echo number_format($stats['total_vendors']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-success-light">
                        <i class="fas fa-check-circle text-success"></i>
                        <h6 class="card-title">Active</h6>
                        <h3 class="card-value"><?php echo number_format($stats['active_vendors']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-danger-light">
                        <i class="fas fa-ban text-danger"></i>
                        <h6 class="card-title">Blocked</h6>
                        <h3 class="card-value"><?php echo number_format($stats['blocked_vendors']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-warning-light">
                        <i class="fas fa-clock text-warning"></i>
                        <h6 class="card-title">Pending</h6>
                        <h3 class="card-value"><?php echo number_format($stats['pending_vendors']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-info-light">
                        <i class="fas fa-boxes text-info"></i>
                        <h6 class="card-title">Products</h6>
                        <h3 class="card-value"><?php echo number_format($products_stats['total_products']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card" style="background-color: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                        <i class="fas fa-dollar-sign" style="color: #9b59b6;"></i>
                        <h6 class="card-title">Earnings</h6>
                        <h3 class="card-value">Rs. <?php echo number_format($earnings_stats['total_earnings'] ?? 0); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Vendor Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Vendors</h5>
                    <div class="d-flex">
                        <form method="get" class="input-group me-3" style="width: 250px;">
                            <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search vendors..." value="<?php echo htmlspecialchars($search_term); ?>">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </form>
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                <li><h6 class="dropdown-header">Filter by Status</h6></li>
                                <li><a class="dropdown-item" href="?status=all">All Vendors</a></li>
                                <li><a class="dropdown-item" href="?status=active"><span class="status-badge status-active me-2"></span> Active</a></li>
                                <li><a class="dropdown-item" href="?status=pending"><span class="status-badge status-pending me-2"></span> Pending</a></li>
                                <li><a class="dropdown-item" href="?status=blocked"><span class="status-badge status-blocked me-2"></span> Blocked</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Vendor Name</th>
                                    <th>Store Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Join Date</th>
                                    <th>Products</th>
                                    <th>Orders</th>
                                    <th>Earnings</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vendors as $vendor): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo !empty($vendor['profile_picture']) ? "../vendor/" . $vendor['profile_picture'] : '../vendor/uploads/default_profile.webp'; ?>" 
                                                 width="32" height="32" class="rounded-circle me-2">
                                            <span><?php echo htmlspecialchars($vendor['username']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($vendor['store_name']); ?></td>
                                    <td><?php echo htmlspecialchars($vendor['email']); ?></td>
                                    <td><?php echo htmlspecialchars($vendor['phone']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $vendor['status']; ?>">
                                            <?php echo ucfirst($vendor['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($vendor['created_at'])); ?></td>
                                    <td><?php echo $vendor['product_count']; ?></td>
                                    <td><?php echo $vendor['order_count']; ?></td>
                                    <td>Rs. <?php echo number_format($vendor['total_earnings'] ?? 0); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info action-btn" title="View Details" 
                                                data-bs-toggle="modal" data-bs-target="#vendorDetailsModal"
                                                onclick="loadVendorDetails(<?php echo $vendor['user_id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($vendor['status'] == 'active'): ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $vendor['user_id']; ?>">
                                                <input type="hidden" name="action" value="block">
                                                <button class="btn btn-sm btn-danger action-btn" title="Block" type="submit">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        <?php elseif ($vendor['status'] == 'pending'): ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $vendor['user_id']; ?>">
                                                <input type="hidden" name="action" value="approve">
                                                <button class="btn btn-sm btn-success action-btn" title="Approve" type="submit">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $vendor['user_id']; ?>">
                                                <input type="hidden" name="action" value="unblock">
                                                <button class="btn btn-sm btn-warning action-btn" title="Unblock" type="submit">
                                                    <i class="fas fa-lock-open"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <!-- <a href="vendor_management.php?status=pending" 
                                           class="btn btn-sm btn-warning action-btn" title="pending varification">
                                            <i class="fas fa-close"></i>
                                        </a> -->
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Details Modal -->
    <div class="modal fade" id="vendorDetailsModal" tabindex="-1" aria-labelledby="vendorDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorDetailsModalLabel">Vendor Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="vendorDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });
        });

        // Load vendor details via AJAX
        function loadVendorDetails(userId) {
            fetch('get_vendor_details.php?user_id=' + userId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('vendorDetailsContent').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('vendorDetailsContent').innerHTML = 
                        '<div class="alert alert-danger">Error loading vendor details</div>';
                });
        }
    </script>
</body>
</html>