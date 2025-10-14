<?php
session_start();
require_once '../mydatabase/conn.php'; // Your database connection file

// Check if admin is logged in
// if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
//     header("Location: admin_login.php");
//     exit;
// }

// Handle actions (block/unblock, delete, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);
        
        switch ($_POST['action']) {
            case 'block':
                $stmt = $conn->prepare("UPDATE users SET status = 'blocked' WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Customer blocked successfully";
                break;
                
            case 'unblock':
                $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Customer unblocked successfully";
                break;
                
            case 'flag':
                $stmt = $conn->prepare("UPDATE users SET flagged = TRUE WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Customer flagged successfully";
                break;
                
            case 'unflag':
                $stmt = $conn->prepare("UPDATE users SET flagged = FALSE WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Customer unflagged successfully";
                break;
                
            case 'delete':
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ? AND type = 'customer'");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $_SESSION['message'] = "Customer deleted successfully";
                break;
                
            case 'add_note':
                $note = trim($_POST['note']);
                if (!empty($note)) {
                    $admin_id = $_SESSION['admin_id'];
                    $stmt = $conn->prepare("INSERT INTO customer_notes (user_id, admin_id, note) VALUES (?, ?, ?)");
                    $stmt->bind_param("iis", $user_id, $admin_id, $note);
                    $stmt->execute();
                    $_SESSION['message'] = "Note added successfully";
                }
                break;
        }
        
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// Get filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
$date_range = isset($_GET['date_range']) ? explode(' to ', $_GET['date_range']) : [];

// Build query to fetch customers
$query = "SELECT u.user_id, u.username, u.email, u.phone, u.status, u.flagged, u.created_at, 
                 cp.first_name, cp.last_name, cp.profile_image,
                 COUNT(o.id) as order_count,
                 SUM(o.total_amount) as total_spent
          FROM users u
          LEFT JOIN customer_profile cp ON u.user_id = cp.user_id
          LEFT JOIN orders o ON u.user_id = o.user_id
          WHERE u.type = 'customer'";

$params = [];
$types = '';

// Apply filters
if (!empty($search_term)) {
    $query .= " AND (u.username LIKE ? OR u.email LIKE ? OR cp.first_name LIKE ? OR cp.last_name LIKE ? OR u.phone LIKE ?)";
    $search_param = "%$search_term%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param, $search_param]);
    $types .= 'sssss';
}

if ($status_filter != 'all') {
    if ($status_filter == 'flagged') {
        $query .= " AND u.flagged = TRUE";
    } else {
        $query .= " AND u.status = ?";
        $params[] = $status_filter;
        $types .= 's';
    }
}

if (count($date_range) == 2) {
    $query .= " AND DATE(u.created_at) BETWEEN ? AND ?";
    $params[] = $date_range[0];
    $params[] = $date_range[1];
    $types .= 'ss';
}

$query .= " GROUP BY u.user_id";

// Prepare and execute query
$stmt = $conn->prepare($query);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$customers = $result->fetch_all(MYSQLI_ASSOC);

// Get stats for summary cards
$stats_query = "SELECT 
    COUNT(*) as total_customers,
    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_customers,
    SUM(CASE WHEN status = 'blocked' THEN 1 ELSE 0 END) as blocked_customers,
    SUM(flagged = TRUE) as flagged_customers
    FROM users WHERE type = 'customer'";
$stats_result = $conn->query($stats_query);
$stats = $stats_result->fetch_assoc();

$total_orders_query = "SELECT COUNT(*) as total_orders, SUM(total_amount) as total_spent FROM orders";
$total_orders_result = $conn->query($total_orders_query);
$order_stats = $total_orders_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Customer Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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
            <li>
                <a href="admin_vendors.php"><i class="fas fa-users-cog"></i> Vendors</a>
            </li>
            <li class="active">
                <a href="admin_customers.php"><i class="fas fa-users"></i> Customers</a>
            </li>
            <li>
                <a href="admin_products.php"><i class="fas fa-box"></i> Products</a>
            </li>
            <li>
                <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
            </li>
            <li>
                <a href="admin_returns.php"><i class="fas fa-exchange-alt"></i> Return Orders</a>
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
                            <li><a class="dropdown-item" href="#">Customer complaint</a></li>
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
                <h2 class="mb-0"><i class="fas fa-users me-2"></i> Customer Management</h2>
                <a href="add_customer.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add New Customer</a>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-primary-light">
                        <i class="fas fa-users text-primary"></i>
                        <h6 class="card-title">Total Customers</h6>
                        <h3 class="card-value"><?php echo number_format($stats['total_customers']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-success-light">
                        <i class="fas fa-check-circle text-success"></i>
                        <h6 class="card-title">Active Users</h6>
                        <h3 class="card-value"><?php echo number_format($stats['active_customers']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-danger-light">
                        <i class="fas fa-ban text-danger"></i>
                        <h6 class="card-title">Blocked Users</h6>
                        <h3 class="card-value"><?php echo number_format($stats['blocked_customers']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-warning-light">
                        <i class="fas fa-shopping-cart text-warning"></i>
                        <h6 class="card-title">Total Orders</h6>
                        <h3 class="card-value"><?php echo number_format($order_stats['total_orders']); ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-info-light">
                        <i class="fas fa-money-bill-wave text-info"></i>
                        <h6 class="card-title">Total Spent</h6>
                        <h3 class="card-value">Rs. <?php echo number_format($order_stats['total_spent'] ?? 0); ?></h3>
                    </div>
                </div>
                <!-- <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card" style="background-color: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                        <i class="fas fa-flag" style="color: #9b59b6;"></i>
                        <h6 class="card-title">Flagged Users</h6>
                        <h3 class="card-value"><?php echo number_format($stats['flagged_customers']); ?></h3>
                    </div> -->
                </div>
            </div>

            <!-- Customer Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0">All Customers</h5>
                    <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                        <!-- Search Form -->
                        <form method="get" class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="Search customers..." 
                                   value="<?php echo htmlspecialchars($search_term); ?>">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </form>
                        
                        <!-- Status Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="statusFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> Status: <?php echo ucfirst($status_filter); ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown">
                                <li><a class="dropdown-item" href="?status=all">All Customers</a></li>
                                <li><a class="dropdown-item" href="?status=active">Active</a></li>
                                <li><a class="dropdown-item" href="?status=blocked">Blocked</a></li>
                                <li><a class="dropdown-item" href="?status=flagged">Flagged</a></li>
                            </ul>
                        </div>
                        
                        <!-- Date Range Filter -->
                        <form method="get" class="input-group" style="width: 250px;">
                            <input type="hidden" name="status" value="<?php echo $status_filter; ?>">
                            <input type="hidden" name="search" value="<?php echo $search_term; ?>">
                            <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" name="date_range" id="dateRangeFilter" class="form-control" 
                                   placeholder="Date range" value="<?php echo isset($_GET['date_range']) ? $_GET['date_range'] : ''; ?>">
                            <button class="btn btn-outline-secondary" type="submit">Filter</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Join Date</th>
                                    <th>Total Orders</th>
                                    <th>Total Spent</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customers as $customer): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo !empty($customer['profile_image']) ? $customer['profile_image'] : 'https://via.placeholder.com/40'; ?>" 
                                                 width="32" height="32" class="rounded-circle me-2">
                                            <span><?php echo htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']); ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                                    <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                                    <td><?php echo date('Y-m-d', strtotime($customer['created_at'])); ?></td>
                                    <td><?php echo $customer['order_count']; ?></td>
                                    <td>Rs. <?php echo number_format($customer['total_spent'] ?? 0); ?></td>
                                    <td>
                                        <?php if ($customer['flagged']): ?>
                                            <span class="status-badge status-active">Active <i class="fas fa-flag text-danger ms-1" title="Flagged"></i></span>
                                        <?php else: ?>
                                            <span class="status-badge status-<?php echo $customer['status']; ?>">
                                                <?php echo ucfirst($customer['status']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info action-btn" title="View Profile" 
                                                data-bs-toggle="modal" data-bs-target="#customerDetailsModal"
                                                onclick="loadCustomerDetails(<?php echo $customer['user_id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <?php if ($customer['status'] == 'active'): ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $customer['user_id']; ?>">
                                                <input type="hidden" name="action" value="block">
                                                <button class="btn btn-sm btn-danger action-btn" title="Block" type="submit">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form method="post" style="display: inline;">
                                                <input type="hidden" name="user_id" value="<?php echo $customer['user_id']; ?>">
                                                <input type="hidden" name="action" value="unblock">
                                                <button class="btn btn-sm btn-warning action-btn" title="Unblock" type="submit">
                                                    <i class="fas fa-lock-open"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        
                                        <a href="customer_orders.php?user_id=<?php echo $customer['user_id']; ?>" 
                                           class="btn btn-sm btn-success action-btn" title="Orders">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
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

    <!-- Customer Details Modal -->
    <div class="modal fade" id="customerDetailsModal" tabindex="-1" aria-labelledby="customerDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="customerDetailsModalLabel">Customer Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="customerDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });

            // Initialize date range picker
            flatpickr("#dateRangeFilter", {
                mode: "range",
                dateFormat: "Y-m-d",
                allowInput: true
            });
        });

        // Load customer details via AJAX
        function loadCustomerDetails(userId) {
            fetch('get_customer_details.php?user_id=' + userId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('customerDetailsContent').innerHTML = data;
                })
                .catch(error => {
                    document.getElementById('customerDetailsContent').innerHTML = 
                        '<div class="alert alert-danger">Error loading customer details</div>';
                });
        }
    </script>
</body>
</html>