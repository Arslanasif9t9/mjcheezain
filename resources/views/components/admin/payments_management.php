<?php
@include "../mydatabase/conn.php";

// Get payment counts by status
$stmt = $conn->query("SELECT 
        COUNT(*) AS total_payments,
        SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) AS pending_payments,
        SUM(CASE WHEN payment_status = 'verified' THEN 1 ELSE 0 END) AS verified_payments,
        SUM(CASE WHEN payment_status = 'rejected' THEN 1 ELSE 0 END) AS rejected_payments
        FROM payments;");
$counts = $stmt->fetch_assoc();

// Get all payments with related order and product info
$stmt = $conn->query("SELECT 
        p.*, 
        o.id AS order_id, 
        o.total_amount, 
        o.payment_method,
        o.order_date,
        vp.name AS product_name,
        vp.selling_price,
        u.username AS customer_username,
        cp.first_name AS customer_name,
        v.username AS vendor_username,
        vbi.store_name AS vendor_store
    FROM payments p
    JOIN orders o ON p.order_id = o.id
    JOIN vendor_products vp ON o.product_id = vp.id
    JOIN users u ON o.user_id = u.user_id
    JOIN customer_profile cp ON o.user_id = cp.user_id
    JOIN users v ON o.vendor_id = v.user_id
    JOIN vendor_basic_info vbi ON o.vendor_id = vbi.user_id
    ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Payment Management</title>
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
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
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

        .status-pending {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }

        .status-verified {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
        }

        .status-rejected {
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

        /* Timeline Styles */
        .timeline {
            position: relative;
            padding-left: 1.5rem;
        }
        .timeline:before {
            content: '';
            position: absolute;
            left: 7px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }
        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }
        .timeline-item:last-child {
            padding-bottom: 0;
        }
        .timeline-dot {
            position: absolute;
            left: -1.5rem;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: #6c757d;
            top: 4px;
        }
        .timeline-dot.active {
            background: var(--success-color);
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.3);
        }
        .timeline-content {
            padding-left: 0.5rem;
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

        /* Payment Receipt Preview */
        .receipt-preview {
            max-width: 100%;
            max-height: 200px;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .receipt-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-users-cog"></i> Vendors</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-users"></i> Customers</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-box"></i> Products</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-shopping-cart"></i> Orders</a>
            </li>
            <li class="active">
                <a href="#"><i class="fas fa-money-bill-wave"></i> Payments</a>
                <ul class="sub-menu ps-3" style="list-style: none;">
                    <li><a href="#"><i class="fas fa-circle-notch fa-xs me-2"></i> All Payments</a></li>
                    <li><a href="#"><i class="fas fa-circle-notch fa-xs me-2"></i> Pending Verification</a></li>
                    <li><a href="#"><i class="fas fa-circle-notch fa-xs me-2"></i> Verified Payments</a></li>
                    <li><a href="#"><i class="fas fa-circle-notch fa-xs me-2"></i> Rejected Payments</a></li>
                </ul>
            </li>
            <li>
                <a href="#"><i class="fas fa-hand-holding-usd"></i> Vendor Payouts</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-file-contract"></i> Return Conditions</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-exchange-alt"></i> Return Orders</a>
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
                            <li><a class="dropdown-item" href="#">New payment received</a></li>
                            <li><a class="dropdown-item" href="#">Payment #10521 verified</a></li>
                            <li><a class="dropdown-item" href="#">Payout request received</a></li>
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Payment Management</h2>
                <div>
                    <button class="btn btn-outline-primary me-2"><i class="fas fa-file-export me-2"></i> Export Payments</button>
                    <button class="btn btn-primary"><i class="fas fa-plus me-2"></i> Create Payment</button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-primary-light">
                        <i class="fas fa-money-bill-wave text-primary"></i>
                        <h6 class="card-title">Total Payments</h6>
                        <h3 class="card-value"><?php echo $counts['total_payments'] ?></h3>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-warning-light">
                        <i class="fas fa-clock text-warning"></i>
                        <h6 class="card-title">Pending Verification</h6>
                        <h3 class="card-value"><?php echo $counts['pending_payments'] ?></h3>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-success-light">
                        <i class="fas fa-check-circle text-success"></i>
                        <h6 class="card-title">Verified Payments</h6>
                        <h3 class="card-value"><?php echo $counts['verified_payments'] ?></h3>
                    </div>
                </div>
                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-danger-light">
                        <i class="fas fa-times-circle text-danger"></i>
                        <h6 class="card-title">Rejected Payments</h6>
                        <h3 class="card-value"><?php echo $counts['rejected_payments'] ?></h3>
                    </div>
                </div>
            </div>

            <!-- Payment Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0">All Payments</h5>
                    <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                        <!-- Search Input -->
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                            <input type="text" id="paymentSearch" class="form-control" placeholder="Search payments..." 
                                   aria-label="Search payments" aria-describedby="basic-addon1">
                        </div>
                        
                        <!-- Status Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="statusFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> Status: All
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown">
                                <li><a class="dropdown-item filter-status" href="#" data-status="all">All Payments</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="pending">Pending</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="verified">Verified</a></li>
                                <li><a class="dropdown-item filter-status" href="#" data-status="rejected">Rejected</a></li>
                            </ul>
                        </div>
                        
                        <!-- Date Range Filter -->
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                            <input type="text" id="dateRangeFilter" class="form-control" placeholder="Date range">
                            <button class="btn btn-outline-secondary clear-date" type="button"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <!-- Payment Method Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="methodFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-credit-card me-1"></i> Method: All
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="methodFilterDropdown">
                                <li><a class="dropdown-item filter-method" href="#" data-method="all">All Methods</a></li>
                                <li><a class="dropdown-item filter-method" href="#" data-method="bank_transfer">Bank Transfer</a></li>
                                <li><a class="dropdown-item filter-method" href="#" data-method="cash_on_delivery">Cash on Delivery</a></li>
                                <li><a class="dropdown-item filter-method" href="#" data-method="credit_card">Credit Card</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Vendor</th>
                                    <th>Product</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($stmt && $stmt->num_rows > 0): ?>
                                    <?php while ($row = $stmt->fetch_assoc()): ?>
                                        <tr>
                                            <td>#<?= htmlspecialchars($row['id']) ?></td>
                                            <td>#<?= htmlspecialchars($row['order_id']) ?></td>
                                            <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                            <td><?= htmlspecialchars($row['vendor_store']) ?></td>
                                            <td><?= htmlspecialchars($row['product_name']) ?></td>
                                            <td>Rs. <?= number_format($row['amount'], 2) ?></td>
                                            <td>
                                                <?php
                                                    $status = strtolower($row['payment_status']);
                                                    echo "<span class='status-badge status-$status'>" . ucfirst($status) . "</span>";
                                                ?>
                                            </td>
                                            <td><?= date('Y-m-d', strtotime($row['transaction_date'])) ?></td>
                                            <td><?= strtoupper(str_replace('_', ' ', $row['payment_method'])) ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-info action-btn view-payment" 
                                                    data-payment-id="<?= $row['id'] ?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#paymentDetailsModal"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <?php if ($status == 'pending'): ?>
                                                    <button class="btn btn-sm btn-success action-btn verify-payment" 
                                                        data-payment-id="<?= $row['id'] ?>"
                                                        title="Verify">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger action-btn reject-payment" 
                                                        data-payment-id="<?= $row['id'] ?>"
                                                        title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php elseif ($status == 'verified'): ?>
                                                    <button class="btn btn-sm btn-primary action-btn" title="Receipt">
                                                        <i class="fas fa-receipt"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-warning action-btn" title="Refund">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                <?php elseif ($status == 'rejected'): ?>
                                                    <button class="btn btn-sm btn-secondary action-btn reject-payment" title="Restore">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center">No payments found.</td>
                                    </tr>
                                <?php endif; ?>
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

    <!-- Payment Details Modal -->
    <div class="modal fade" id="paymentDetailsModal" tabindex="-1" aria-labelledby="paymentDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentDetailsModalLabel">Payment #<span id="paymentId"></span> Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Payment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Payment ID</label>
                                        <input type="text" class="form-control" id="modalPaymentId" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Order ID</label>
                                        <input type="text" class="form-control" id="modalOrderId" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Status</label>
                                        <input type="text" class="form-control" id="modalPaymentStatus" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <input type="text" class="form-control" id="modalPaymentMethod" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Amount</label>
                                        <input type="text" class="form-control" id="modalAmount" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Transaction Date</label>
                                        <input type="text" class="form-control" id="modalTransactionDate" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Bank Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Bank Name</label>
                                        <input type="text" class="form-control" id="modalBankName" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Account Holder</label>
                                        <input type="text" class="form-control" id="modalAccountHolder" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Transaction Reference</label>
                                        <input type="text" class="form-control" id="modalTransactionRef" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Customer</label>
                                        <input type="text" class="form-control" id="modalCustomer" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Vendor</label>
                                        <input type="text" class="form-control" id="modalVendor" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product</label>
                                        <input type="text" class="form-control" id="modalProduct" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Product Price</label>
                                        <input type="text" class="form-control" id="modalProductPrice" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Order Date</label>
                                        <input type="text" class="form-control" id="modalOrderDate" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Payment Receipt</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="" id="modalReceiptImage" class="receipt-preview img-fluid mb-3" 
                                         data-bs-toggle="modal" data-bs-target="#receiptModal">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" id="verifyPaymentBtn">
                                            <i class="fas fa-check me-2"></i> Verify Payment
                                        </button>
                                        <button class="btn btn-danger" id="rejectPaymentBtn">
                                            <i class="fas fa-times me-2"></i> Reject Payment
                                        </button>
                                        <button class="btn btn-secondary" id="pendingPaymentBtn" style="display: none;">
                                            <i class="fas fa-clock me-2"></i> Mark as Pending
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Notes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Receipt Modal (Full Size) -->
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img src="" id="fullReceiptImage" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="#" id="downloadReceipt" class="btn btn-primary" download>
                        <i class="fas fa-download me-2"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            document.getElementById('sidebarCollapse').addEventListener('click', function() {
                document.getElementById('sidebar').classList.toggle('active');
                document.getElementById('content').classList.toggle('active');
            });

            // Initialize date range picker
            flatpickr("#dateRangeFilter", {
                mode: "range",
                dateFormat: "Y-m-d",
                onClose: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        filterTable();
                    }
                }
            });

            // Search Functionality
            const paymentSearch = document.getElementById('paymentSearch');
            const paymentTable = document.querySelector('.table');
            const tableRows = paymentTable.querySelectorAll('tbody tr');
            
            paymentSearch.addEventListener('input', function() {
                filterTable();
            });
            
            // Status Filter
            const statusFilterItems = document.querySelectorAll('.filter-status');
            let currentStatusFilter = 'all';
            
            statusFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentStatusFilter = this.getAttribute('data-status');
                    document.querySelector('#statusFilterDropdown').innerHTML = 
                        `<i class="fas fa-filter me-1"></i> Status: ${this.textContent}`;
                    
                    filterTable();
                });
            });
            
            // Method Filter
            const methodFilterItems = document.querySelectorAll('.filter-method');
            let currentMethodFilter = 'all';
            
            methodFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentMethodFilter = this.getAttribute('data-method');
                    document.querySelector('#methodFilterDropdown').innerHTML = 
                        `<i class="fas fa-credit-card me-1"></i> Method: ${this.textContent}`;
                    
                    filterTable();
                });
            });
            
            // Combined Filter Function
            function filterTable() {
                const searchTerm = paymentSearch.value.toLowerCase();
                const dateRange = document.getElementById('dateRangeFilter').value;
                
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const statusCell = row.querySelector('.status-badge');
                    const status = statusCell ? statusCell.textContent.toLowerCase() : '';
                    const methodCell = row.cells[8]; // Method column
                    const method = methodCell ? methodCell.textContent.toLowerCase().replace(/\s/g, '_') : '';
                    const dateCell = row.cells[7]; // Date column
                    const date = dateCell ? dateCell.textContent.trim() : '';
                    
                    // Search filter
                    const searchMatch = searchTerm === '' || rowText.includes(searchTerm);
                    
                    // Status filter
                    const statusMatch = currentStatusFilter === 'all' || 
                                    status.includes(currentStatusFilter);
                    
                    // Method filter
                    const methodMatch = currentMethodFilter === 'all' || 
                                    method.includes(currentMethodFilter);
                    
                    // Date range filter
                    let dateMatch = true;
                    if (dateRange) {
                        const dates = dateRange.split(' to ');
                        if (dates.length === 2) {
                            const startDate = new Date(dates[0]);
                            const endDate = new Date(dates[1]);
                            const rowDate = new Date(date);
                            dateMatch = rowDate >= startDate && rowDate <= endDate;
                        }
                    }
                    
                    // Show/hide based on all filters
                    if (searchMatch && statusMatch && methodMatch && dateMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
            
            // Date range clear button
            document.querySelector('.clear-date').addEventListener('click', function() {
                document.getElementById('dateRangeFilter').value = '';
                filterTable();
            });

            // Payment Details Modal
            const viewPaymentButtons = document.querySelectorAll('.view-payment');
            viewPaymentButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const paymentId = this.getAttribute('data-payment-id');
                    fetchPaymentDetails(paymentId);
                });
            });

            // Verify/Reject Payment Buttons
            const verifyButtons = document.querySelectorAll('.verify-payment');
            verifyButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const paymentId = this.getAttribute('data-payment-id');
                    updatePaymentStatus(paymentId, 'verified');
                });
            });

            const rejectButtons = document.querySelectorAll('.reject-payment');
            rejectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const paymentId = this.getAttribute('data-payment-id');
                    updatePaymentStatus(paymentId, 'rejected');
                });
            });

            // Modal Verify/Reject Buttons
            document.getElementById('verifyPaymentBtn')?.addEventListener('click', function() {
                const paymentId = document.getElementById('modalPaymentId').value;
                updatePaymentStatus(paymentId, 'verified');
            });

            document.getElementById('rejectPaymentBtn')?.addEventListener('click', function() {
                const paymentId = document.getElementById('modalPaymentId').value;
                updatePaymentStatus(paymentId, 'rejected');
            });

            // Fetch payment details for modal
            function fetchPaymentDetails(paymentId) {
                fetch(`get_payment_details.php?id=${paymentId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const payment = data.payment;
                            
                            // Update modal fields
                            document.getElementById('paymentId').textContent = payment.id;
                            document.getElementById('modalPaymentId').value = payment.id;
                            document.getElementById('modalOrderId').value = payment.order_id;
                            document.getElementById('modalPaymentStatus').value = payment.payment_status;
                            document.getElementById('modalPaymentMethod').value = payment.payment_method;
                            document.getElementById('modalAmount').value = `Rs. ${payment.amount}`;
                            document.getElementById('modalTransactionDate').value = payment.transaction_date;
                            
                            // Bank details
                            document.getElementById('modalBankName').value = payment.bank_name || 'N/A';
                            document.getElementById('modalAccountHolder').value = payment.account_holder || 'N/A';
                            document.getElementById('modalTransactionRef').value = payment.transaction_reference || 'N/A';
                            
                            // Order details
                            document.getElementById('modalCustomer').value = payment.customer_name;
                            document.getElementById('modalVendor').value = payment.vendor_store;
                            document.getElementById('modalProduct').value = payment.product_name;
                            document.getElementById('modalProductPrice').value = `Rs. ${payment.selling_price.toFixed(2)}`;
                            document.getElementById('modalOrderDate').value = payment.order_date;
                            
                            // Receipt image
                            const receiptImg = document.getElementById('modalReceiptImage');
                            const fullReceiptImg = document.getElementById('fullReceiptImage');
                            const downloadLink = document.getElementById('downloadReceipt');
                            
                            if (payment.receipt_image) {
                                receiptImg.src = `../uploads/payments/${payment.receipt_image}`;
                                fullReceiptImg.src = `../uploads/payments/${payment.receipt_image}`;
                                downloadLink.href = `../uploads/payments/${payment.receipt_image}`;
                            } else {
                                receiptImg.src = 'https://via.placeholder.com/400x200?text=No+Receipt+Uploaded';
                                fullReceiptImg.src = 'https://via.placeholder.com/800x400?text=No+Receipt+Uploaded';
                                downloadLink.href = '#';
                            }
                            
                            // Show/hide action buttons based on status
                            const status = payment.payment_status.toLowerCase();
                            const verifyBtn = document.getElementById('verifyPaymentBtn');
                            const rejectBtn = document.getElementById('rejectPaymentBtn');
                            const pendingBtn = document.getElementById('pendingPaymentBtn');
                            
                            verifyBtn.style.display = status === 'pending' ? 'block' : 'none';
                            rejectBtn.style.display = status === 'pending' ? 'block' : 'none';
                            pendingBtn.style.display = status !== 'pending' ? 'block' : 'none';
                        } else {
                            alert('Error fetching payment details: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while fetching payment details');
                    });
            }

            // Update payment status
            function updatePaymentStatus(paymentId, status) {
                if (!confirm(`Are you sure you want to ${status} this payment?`)) {
                    return;
                }
                
                fetch('update_payment_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        status: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(`Payment ${status} successfully!`);
                        location.reload(); // Refresh the page to update the UI
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating payment status');
                });
            }

            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

</body>
</html>