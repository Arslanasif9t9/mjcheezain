<?php
@include "../mydatabase/conn.php";
// Handle position change requests
// $position_message = "njsdn" . ($_POST['change_position']);
// echo (isset($_POST['change_position']));
if (isset($_POST['change_position'])) {
    $product_id = $_POST['product_id'];
    $new_position = $_POST['new_position'];
    
    // Update product position
    $stmt = $conn->prepare("UPDATE vendor_products SET position = ? WHERE id = ?");
    $stmt->bind_param("ii", $new_position, $product_id);
    
    if ($stmt->execute()) {
        $position_message = "Product position updated successfully!";
    } else {
        $position_message = "Error updating product position: " . $stmt->error;
    }
    $stmt->close();
}

// Handle bulk actions
if (isset($_POST['bulk_action'])) {
    $product_ids = $_POST['product_ids'];
    $action = $_POST['bulk_action_type'];
    
    if (!empty($product_ids)) {
        $ids = implode(",", $product_ids);
        
        switch ($action) {
            case 'approve':
                $position = 1;
                break;
            case 'reject':
                $position = 2;
                break;
            case 'disable':
                $position = 0;
                break;
            case 'delete':
                // First delete related records in other tables
                $conn->query("DELETE FROM vendor_product_images WHERE product_id IN ($ids)");
                $conn->query("DELETE FROM vendor_product_cards WHERE product_id IN ($ids)");
                $conn->query("DELETE FROM vendor_product_faults WHERE product_id IN ($ids)");
                // Then delete the products
                $conn->query("DELETE FROM vendor_products WHERE id IN ($ids)");
                $position_message = "Selected products deleted successfully!";
                break;
            default:
                $position = 1; // Default to approve
        }
        
        if ($action != 'delete') {
            $conn->query("UPDATE vendor_products SET position = $position WHERE id IN ($ids)");
            $position_message = "Bulk action completed successfully!";
        }
    }
}

// Get filter parameters
$position_filter = isset($_GET['position']) ? $_GET['position'] : 'all';
$vendor_filter = isset($_GET['vendor']) ? $_GET['vendor'] : 'all';
$category_filter = isset($_GET['category']) ? $_GET['category'] : 'all';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Build the base query
$query = "SELECT p.*, u.username AS vendor_name, vbi.store_name 
          FROM vendor_products p
          JOIN users u ON p.user_id = u.user_id
          JOIN vendor_basic_info vbi ON p.user_id = vbi.user_id
          WHERE 1=1";

// Add filters to the query
if ($position_filter != 'all') {
    $query .= " AND p.position = " . intval($position_filter);
}

if ($vendor_filter != 'all') {
    $query .= " AND p.user_id = " . intval($vendor_filter);
}

if ($category_filter != 'all') {
    $query .= " AND p.category = '" . $conn->real_escape_string($category_filter) . "'";
}

if (!empty($search_query)) {
    $query .= " AND (p.name LIKE '%" . $conn->real_escape_string($search_query) . "%' 
                OR p.brand LIKE '%" . $conn->real_escape_string($search_query) . "%'
                OR p.model LIKE '%" . $conn->real_escape_string($search_query) . "%')";
}

// Add sorting
$sort_by = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
$query .= " ORDER BY " . $conn->real_escape_string($sort_by) . " " . $conn->real_escape_string($sort_order);

// Pagination
$results_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $results_per_page;

// Get total count for pagination
$count_query = str_replace("SELECT p.*, u.username AS vendor_name, vbi.store_name", "SELECT COUNT(*) as total", $query);
$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $results_per_page);

// Add pagination to the main query
$query .= " LIMIT $offset, $results_per_page";

// Execute the main query
$result = $conn->query($query);

// Get vendors for filter dropdown
$vendors = $conn->query("SELECT u.user_id, vbi.store_name FROM users u JOIN vendor_basic_info vbi ON u.user_id = vbi.user_id");

// Get categories for filter dropdown
$categories = $conn->query("SELECT DISTINCT category FROM vendor_products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Product Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

        /* position Badges */
        .position-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .position-pending {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }

        .position-approved {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
        }

        .position-rejected {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
        }

        .position-disabled {
            background-color: rgba(108, 117, 125, 0.2);
            color: #6c757d;
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

        /* Product Image Thumbnail */
        .product-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
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

        /* Gallery Styles */
        .gallery-item {
            position: relative;
            margin-bottom: 15px;
        }
        .gallery-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
        }
        .gallery-item .delete-img {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(0,0,0,0.5);
            color: white;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        /* Sortable table headers */
        .sortable {
            cursor: pointer;
        }
        .sortable:hover {
            background-color: #f8f9fa;
        }
        .sortable.asc::after {
            content: " ↑";
        }
        .sortable.desc::after {
            content: " ↓";
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav id="sidebar" style="display: noe;">
        <div class="sidebar-header">
            <h3>Admin Panel</h3>
        </div>

        <ul class="list-unstyled components">
            <li>
                <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-users-cog"></i> Vendors</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-users"></i> Customers</a>
            </li>
            <li class="active">
                <a href="#"><i class="fas fa-box"></i> Products</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-shopping-cart"></i> Orders</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-exchange-alt"></i> Return Orders</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-file-contract"></i> Return Conditions</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-money-bill-wave"></i> Payments</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-hand-holding-usd"></i> Commission & Earnings</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-tags"></i> Category & Attributes</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-file-alt"></i> Content Management</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-envelope"></i> Messages / Disputes</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-chart-bar"></i> Reports & Analytics</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-bell"></i> Notification Manager</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-cog"></i> Admin Settings</a>
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
                            <li><a class="dropdown-item" href="#">New products pending approval</a></li>
                            <li><a class="dropdown-item" href="#">Low stock alerts</a></li>
                            <li><a class="dropdown-item" href="#">System update available</a></li>
                        </ul>
                    </div>

                    <div class="dropdown">
                        <a href="#" class="dropdown-toggle d-flex align-items-center text-dark text-decoration-none" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/40" width="32" height="32" class="rounded-circle me-2">
                            <span>Admin User</span>
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
            <?php if (isset($position_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $position_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-box me-2"></i> Product Management</h2>
                <div>
                    <!-- <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus me-2"></i> Add Product</button> -->
                    <!-- <button class="btn btn-outline-primary"><i class="fas fa-file-export me-2"></i> Export</button> -->
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-primary-light">
                        <i class="fas fa-boxes text-primary"></i>
                        <h6 class="card-title">Total Products</h6>
                        <h3 class="card-value"><?php echo $total_rows; ?></h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-warning-light">
                        <i class="fas fa-clock text-warning"></i>
                        <h6 class="card-title">Pending Approval</h6>
                        <h3 class="card-value">
                            <?php 
                                $pending_count = $conn->query("SELECT COUNT(*) FROM vendor_products WHERE position = 1")->fetch_row()[0];
                                echo $pending_count;
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-success-light">
                        <i class="fas fa-check-circle text-success"></i>
                        <h6 class="card-title">Approved Products</h6>
                        <h3 class="card-value">
                            <?php 
                                $approved_count = $conn->query("SELECT COUNT(*) FROM vendor_products WHERE position = 2")->fetch_row()[0];
                                echo $approved_count;
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-danger-light">
                        <i class="fas fa-times-circle text-danger"></i>
                        <h6 class="card-title">Rejected Products</h6>
                        <h3 class="card-value">
                            <?php 
                                $rejected_count = $conn->query("SELECT COUNT(*) FROM vendor_products WHERE position = 3")->fetch_row()[0];
                                echo $rejected_count;
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-info-light">
                        <i class="fas fa-exclamation-triangle text-info"></i>
                        <h6 class="card-title">Out of Stock</h6>
                        <h3 class="card-value">
                            <?php 
                                $out_of_stock = $conn->query("SELECT COUNT(*) FROM vendor_products WHERE quantity <= 4")->fetch_row()[0];
                                echo $out_of_stock;
                            ?>
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card" style="background-color: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                        <i class="fas fa-eye" style="color: #9b59b6;"></i>
                        <h6 class="card-title">Top Category</h6>
                        <h3 class="card-value">
                            <?php 
                                $top_category = $conn->query("SELECT category, COUNT(*) as count FROM vendor_products GROUP BY category ORDER BY count DESC LIMIT 1")->fetch_assoc();
                                echo $top_category ? $top_category['category'] : 'N/A';
                            ?>
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0">All Products</h5>
                    <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                        <!-- Search Form -->
                        <form method="GET" class="d-flex">
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search products..." 
                                       value="<?php echo htmlspecialchars($search_query); ?>" aria-label="Search products">
                                <input type="hidden" name="position" value="<?php echo htmlspecialchars($position_filter); ?>">
                                <input type="hidden" name="vendor" value="<?php echo htmlspecialchars($vendor_filter); ?>">
                                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category_filter); ?>">
                            </div>
                        </form>
                        
                        <!-- position Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="positionFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> position: 
                                <?php 
                                    switch($position_filter) {
                                        case '0': echo 'Pending'; break;
                                        case '1': echo 'Approved'; break;
                                        case '2': echo 'Rejected'; break;
                                        case '3': echo 'Disabled'; break;
                                        default: echo 'All'; 
                                    }
                                ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="positionFilterDropdown">
                                <li><a class="dropdown-item" href="?position=all">All Products</a></li>
                                <li><a class="dropdown-item" href="?position=0">Pending</a></li>
                                <li><a class="dropdown-item" href="?position=1">Approved</a></li>
                                <li><a class="dropdown-item" href="?position=2">Rejected</a></li>
                                <li><a class="dropdown-item" href="?position=3">Disabled</a></li>
                            </ul>
                        </div>
                        
                        <!-- Vendor Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="vendorFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-store me-1"></i> Vendor: 
                                <?php 
                                    if ($vendor_filter == 'all') {
                                        echo 'All';
                                    } else {
                                        $vendor_name = $conn->query("SELECT store_name FROM vendor_basic_info WHERE user_id = $vendor_filter")->fetch_row()[0];
                                        echo $vendor_name ? htmlspecialchars($vendor_name) : 'Unknown';
                                    }
                                ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="vendorFilterDropdown">
                                <li><a class="dropdown-item" href="?position=<?php echo $position_filter; ?>&vendor=all">All Vendors</a></li>
                                <?php while ($vendor = $vendors->fetch_assoc()): ?>
                                    <li><a class="dropdown-item" href="?position=<?php echo $position_filter; ?>&vendor=<?php echo $vendor['user_id']; ?>">
                                        <?php echo htmlspecialchars($vendor['store_name']); ?>
                                    </a></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                        
                        <!-- Category Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="categoryFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-tag me-1"></i> Category: 
                                <?php echo $category_filter == 'all' ? 'All' : htmlspecialchars($category_filter); ?>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="categoryFilterDropdown">
                                <li><a class="dropdown-item" href="?position=<?php echo $position_filter; ?>&category=all">All Categories</a></li>
                                <?php while ($category = $categories->fetch_assoc()): ?>
                                    <li><a class="dropdown-item" href="?position=<?php echo $position_filter; ?>&category=<?php echo urlencode($category['category']); ?>">
                                        <?php echo htmlspecialchars($category['category']); ?>
                                    </a></li>
                                <?php endwhile; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" id="bulkActionForm">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllProducts">
                                <label class="form-check-label" for="selectAllProducts">
                                    Select All
                                </label>
                            </div>
                            <div>
                                <select name="bulk_action_type" class="form-select form-select-sm d-inline-block w-auto me-2">
                                    <option value="approve">Approve</option>
                                    <option value="reject">Reject</option>
                                    <option value="disable">Disable</option>
                                    <option value="delete">Delete</option>
                                </select>
                                <button type="submit" name="bulk_action" class="btn btn-sm btn-primary">Apply</button>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="40px"></th>
                                        <th class="sortable <?php echo $sort_by == 'name' ? strtolower($sort_order) : ''; ?>" data-sort="name">Product</th>
                                        <th class="sortable <?php echo $sort_by == 'vendor_name' ? strtolower($sort_order) : ''; ?>" data-sort="vendor_name">Vendor</th>
                                        <th class="sortable <?php echo $sort_by == 'category' ? strtolower($sort_order) : ''; ?>" data-sort="category">Category</th>
                                        <th class="sortable <?php echo $sort_by == 'position' ? strtolower($sort_order) : ''; ?>" data-sort="position">position</th>
                                        <th class="sortable <?php echo $sort_by == 'quantity' ? strtolower($sort_order) : ''; ?>" data-sort="quantity">Stock</th>
                                        <th class="sortable <?php echo $sort_by == 'selling_price' ? strtolower($sort_order) : ''; ?>" data-sort="selling_price">Price</th>
                                        <th class="sortable <?php echo $sort_by == 'rating' ? strtolower($sort_order) : ''; ?>" data-sort="rating">Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while ($product = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td><input class="form-check-input product-checkbox" type="checkbox" name="product_ids[]" value="<?php echo $product['id']; ?>"></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php 
                                                            // Get primary image for the product
                                                            $image_query = $conn->query("SELECT image_path FROM vendor_product_images WHERE product_id = {$product['id']} AND is_primary = 1 LIMIT 1");
                                                            $image = $image_query->num_rows > 0 ? $image_query->fetch_assoc()['image_path'] : 'https://via.placeholder.com/100';
                                                        ?>
                                                        <img src="../vendor/<?php echo htmlspecialchars($image); ?>" class="product-thumb me-2">
                                                        <span><?php echo htmlspecialchars($product['name']); ?></span>
                                                    </div>
                                                </td>
                                                <td><?php echo htmlspecialchars($product['store_name']); ?></td>
                                                <td><?php echo htmlspecialchars($product['category']); ?></td>
                                                <td>
                                                    <?php 
                                                        $position_class = '';
                                                        $position_text = '';
                                                        switch ($product['position']) {
                                                            case "pending":
                                                                $position_class = 'position-pending';
                                                                $position_text = 'Pending';
                                                                break;
                                                            case "approved":
                                                                $position_class = 'position-approved';
                                                                $position_text = 'Approved';
                                                                break;
                                                            case "refected":
                                                                $position_class = 'position-rejected';
                                                                $position_text = 'Rejected';
                                                                break;
                                                            case "disable":
                                                                $position_class = 'position-disabled';
                                                                $position_text = 'Disabled';
                                                                break;
                                                            default:
                                                                $position_class = 'position-pending';
                                                                $position_text = 'Unknown';
                                                        }
                                                    ?>
                                                    <span class="position-badge <?php echo $position_class; ?>"><?php echo $position_text; ?></span>
                                                </td>
                                                <td><?php echo $product['quantity']; ?></td>
                                                <td>Rs. <?php echo number_format($product['selling_price']); ?></td>
                                                <td><?php echo $product['rating'] ? number_format($product['rating'], 1) . '/5' : 'N/A'; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info action-btn" title="View" data-bs-toggle="modal" data-bs-target="#productDetailsModal" 
                                                            onclick="loadProductDetails(<?php echo $product['id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    <?php if ($product['position'] != "approved"): ?>
                                                        <button type="button" class="btn btn-sm btn-success action-btn" title="Approve" 
                                                                onclick="changeProductposition(<?php echo $product['id']; ?>, 2)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($product['position'] != "rejected"): ?>
                                                        <button type="button" class="btn btn-sm btn-danger action-btn" title="Reject" 
                                                                onclick="changeProductposition(<?php echo $product['id']; ?>, 3)">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <?php if ($product['position'] != "disable"): ?>
                                                        <button type="button" class="btn btn-sm btn-warning action-btn" title="Disable" 
                                                                onclick="changeProductposition(<?php echo $product['id']; ?>, 4)">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="button" class="btn btn-sm btn-success action-btn" title="Enable" 
                                                                onclick="changeProductposition(<?php echo $product['id']; ?>, 2)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <button type="button" class="btn btn-sm btn-secondary action-btn" title="Delete" 
                                                            onclick="confirmDelete(<?php echo $product['id']; ?>)">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center">No products found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                    
                    <!-- Pagination -->
                    <nav aria-label="Page navigation" class="mt-3">
                        <ul class="pagination justify-content-center">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?position=<?php echo $position_filter; ?>&vendor=<?php echo $vendor_filter; ?>&category=<?php echo $category_filter; ?>&search=<?php echo urlencode($search_query); ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                                        <span aria-hidden="true">&laquo; Previous</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?position=<?php echo $position_filter; ?>&vendor=<?php echo $vendor_filter; ?>&category=<?php echo $category_filter; ?>&search=<?php echo urlencode($search_query); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($page < $total_pages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?position=<?php echo $position_filter; ?>&vendor=<?php echo $vendor_filter; ?>&category=<?php echo $category_filter; ?>&search=<?php echo urlencode($search_query); ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                                        <span aria-hidden="true">Next &raquo;</span>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="process_product.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category" required>
                                    <option value="">Select Category</option>
                                    <?php 
                                        $categories = $conn->query("SELECT DISTINCT category FROM vendor_products");
                                        while ($category = $categories->fetch_assoc()): 
                                    ?>
                                        <option value="<?php echo htmlspecialchars($category['category']); ?>">
                                            <?php echo htmlspecialchars($category['category']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Brand</label>
                                <input type="text" class="form-control" name="brand" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model</label>
                                <input type="text" class="form-control" name="model" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Condition</label>
                                <select class="form-select" name="condition" required>
                                    <option value="New">New</option>
                                    <option value="Used">Used</option>
                                    <option value="Refurbished">Refurbished</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" name="quantity" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Original Price (Rs.)</label>
                                <input type="number" class="form-control" name="original_price" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Delivery Charges (Rs.)</label>
                                <input type="number" class="form-control" name="delivery_charges" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Selling Price (Rs.)</label>
                                <input type="number" class="form-control" name="selling_price" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Method</label>
                                <input type="text" class="form-control" name="shipping_method" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shipping Time</label>
                                <input type="text" class="form-control" name="shipping_time" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" required>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Product Images</label>
                                <input type="file" class="form-control" name="product_images[]" multiple accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Product Details Modal -->
    <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productDetailsModalLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="productDetailsContent">
                    <!-- Content will be loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- position Change Form (hidden) -->
    <form id="positionChangeForm" method="POST" style="display: none;">
        <input type="hidden" name="product_id" id="positionProductId">
        <input type="hidden" name="new_position" id="newpositionValue">
        <input type="submit" name="change_position" id="posBtn" value="submit">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Toggle sidebar
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });

            // Select All Products
            document.getElementById('selectAllProducts').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.product-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            // Sortable table headers
            document.querySelectorAll('.sortable').forEach(header => {
                header.addEventListener('click', function() {
                    const sortField = this.getAttribute('data-sort');
                    const currentUrl = new URL(window.location.href);
                    const currentSort = currentUrl.searchParams.get('sort');
                    const currentOrder = currentUrl.searchParams.get('order');
                    
                    let newOrder = 'ASC';
                    if (currentSort === sortField && currentOrder === 'ASC') {
                        newOrder = 'DESC';
                    }
                    
                    currentUrl.searchParams.set('sort', sortField);
                    currentUrl.searchParams.set('order', newOrder);
                    window.location.href = currentUrl.toString();
                });
            });
        });

        // Change product position
        function changeProductposition(productId, newposition) {
            if (confirm('Are you sure you want to change the position of this product?')) {
                document.getElementById('positionProductId').value = productId;
                document.getElementById('newpositionValue').value = newposition;
                console.log(document.getElementById('newpositionValue').value);
                // document.getElementById('positionChangeForm').submit();
                document.getElementById('posBtn').click();
            }
        }

        // Confirm product deletion
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                // Create a form and submit it to delete the product
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                
                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = 'product_id';
                productIdInput.value = productId;
                
                const deleteInput = document.createElement('input');
                deleteInput.type = 'hidden';
                deleteInput.name = 'delete_product';
                deleteInput.value = '1';
                
                form.appendChild(productIdInput);
                form.appendChild(deleteInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Load product details via AJAX
        function loadProductDetails(productId) {
            const modalTitle = document.getElementById('productDetailsModalLabel');
            const modalContent = document.getElementById('productDetailsContent');
            
            // Show loading state
            modalContent.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="position"><span class="visually-hidden">Loading...</span></div></div>';
            
            // Fetch product details
            fetch('get_product_details.php?id=' + productId)
                .then(response => response.text())
                .then(data => {
                    modalContent.innerHTML = data;
                })
                .catch(error => {
                    modalContent.innerHTML = '<div class="alert alert-danger">Error loading product details</div>';
                    console.error('Error:', error);
                });
        }
    </script>
</body>
</html>