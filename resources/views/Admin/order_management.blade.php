<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Order Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="{{ asset('css/css/style.css') }}">
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

        .status-pending {
            background-color: rgba(243, 156, 18, 0.2);
            color: var(--warning-color);
        }

        .status-processing {
            background-color: rgba(52, 152, 219, 0.2);
            color: var(--primary-color);
        }

        .status-shipped {
            background-color: rgba(26, 188, 156, 0.2);
            color: var(--info-color);
        }

        .status-delivered {
            background-color: rgba(46, 204, 113, 0.2);
            color: var(--success-color);
        }

        .status-cancelled {
            background-color: rgba(231, 76, 60, 0.2);
            color: var(--danger-color);
        }

        .status-returned {
            background-color: rgba(155, 89, 182, 0.2);
            color: #9b59b6;
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
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <x-admin.sidebar />

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
                                <li><a class="dropdown-item" href="#">Order #10521 shipped</a></li>
                                <li><a class="dropdown-item" href="#">Return request received</a></li>
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
                    <h2 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Order Management</h2>
                    <div>
                        <button class="btn btn-outline-primary me-2"><i class="fas fa-file-export me-2"></i> Export Orders</button>
                        <button class="btn btn-primary"><i class="fas fa-plus me-2"></i> Create Order</button>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-primary-light">
                            <i class="fas fa-shopping-cart text-primary"></i>
                            <h6 class="card-title">Total Orders</h6>
                            <h3 class="card-value">{{ $total }}</h3>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-warning-light">
                            <i class="fas fa-clock text-warning"></i>
                            <h6 class="card-title">Pending Orders</h6>
                            <h3 class="card-value">{{ $pending }}</h3>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-info-light">
                            <i class="fas fa-truck text-info"></i>
                            <h6 class="card-title">Shipped Orders</h6>
                            <h3 class="card-value"> {{ $shipped }} </h3>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-success-light">
                            <i class="fas fa-check-circle text-success"></i>
                            <h6 class="card-title">Delivered Orders</h6>
                            <h3 class="card-value"> {{ $approved }} </h3>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-danger-light">
                            <i class="fas fa-times-circle text-danger"></i>
                            <h6 class="card-title">Cancelled Orders</h6>
                            <h3 class="card-value"> {{$rejected}} </h3>
                        </div>
                    </div>
                    <!-- <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card" style="background-color: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                            <i class="fas fa-exchange-alt" style="color: #9b59b6;"></i>
                            <h6 class="card-title">Return Requests</h6>
                            <h3 class="card-value"></h3>
                        </div>
                    </div> -->
                </div>

                <!-- Order Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">All Orders</h5>
                        <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                            <!-- Search Input -->
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" id="orderSearch" class="form-control" placeholder="Search orders..." 
                                    aria-label="Search orders" aria-describedby="basic-addon1">
                            </div>
                            
                            <!-- Status Filter Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="statusFilterDropdown" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i> Status: All
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="statusFilterDropdown">
                                    <li><a class="dropdown-item filter-status" href="#" data-status="all">All Orders</a></li>
                                    <li><a class="dropdown-item filter-status" href="#" data-status="pending">Pending</a></li>
                                    <li><a class="dropdown-item filter-status" href="#" data-status="processing">Processing</a></li>
                                    <li><a class="dropdown-item filter-status" href="#" data-status="shipped">Shipped</a></li>
                                    <li><a class="dropdown-item filter-status" href="#" data-status="delivered">Delivered</a></li>
                                    <li><a class="dropdown-item filter-status" href="#" data-status="cancelled">Cancelled</a></li>
                                    <li><a class="dropdown-item filter-status" href="#" data-status="returned">Returned</a></li>
                                </ul>
                            </div>
                            
                            <!-- Date Range Filter -->
                            <div class="input-group" style="width: 250px;">
                                <span class="input-group-text bg-white"><i class="fas fa-calendar-alt"></i></span>
                                <input type="text" id="dateRangeFilter" class="form-control" placeholder="Date range">
                                <button class="btn btn-outline-secondary clear-date" type="button"><i class="fas fa-times"></i></button>
                            </div>
                            
                            <!-- Vendor Filter Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="vendorFilterDropdown" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-store me-1"></i> Vendor: All
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="vendorFilterDropdown">
                                    <li><a class="dropdown-item filter-vendor" href="#" data-vendor="all">All Vendors</a></li>
                                    <li><a class="dropdown-item filter-vendor" href="#" data-vendor="malik">Malik AutoParts</a></li>
                                    <li><a class="dropdown-item filter-vendor" href="#" data-vendor="speedx">SpeedX Hub</a></li>
                                    <li><a class="dropdown-item filter-vendor" href="#" data-vendor="autozone">AutoZone.pk</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Vendor</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Date</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($carts)
                                        @foreach ($carts as $cart)
                                            <tr>
                                                <td>ORD-{{ $cart->id }} </td>
                                                <td> {{ $cart->user_id }} </td> <!-- customer name -->
                                                <td> {{$cart->product_id}} </td> <!-- vendor store -->
                                                <td>
                                                    
                                                </td>
                                                <td>Rs. </td>
                                                <td>date</td>
                                                <td>paymth</td>
                                                <td>
                                                    <button class="btn btn-sm btn-info action-btn view-order" 
                                                        data-order-id="{{ $cart->id }}" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#orderDetailsModal"
                                                        title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>


                                                    
                                                        <button class="btn btn-sm btn-primary action-btn" title="Update">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger action-btn" title="Cancel">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    
                                                        <button class="btn btn-sm btn-primary action-btn" title="Track">
                                                            <i class="fas fa-truck"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning action-btn" title="Refund">
                                                            <i class="fas fa-exchange-alt"></i>
                                                        </button>
                                                    
                                                        <button class="btn btn-sm btn-success action-btn" title="Invoice">
                                                            <i class="fas fa-file-invoice"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-warning action-btn" title="Return">
                                                            <i class="fas fa-exchange-alt"></i>
                                                        </button>
                                                    
                                                        <button class="btn btn-sm btn-secondary action-btn" title="Restore">
                                                            <i class="fas fa-undo"></i>
                                                        </button>
                                                    
                                                        <button class="btn btn-sm btn-success action-btn" title="Refund">
                                                            <i class="fas fa-money-bill-wave"></i>
                                                        </button>
                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="8" class="text-center">No orders found.</td>
                                        </tr>
                                    @endif
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
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order #10520 Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Customer Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Customer Name</label>
                                            <input type="text" class="form-control" value="Ali Khan" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Phone Number</label>
                                            <input type="text" class="form-control" value="0300-1234567" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Email Address</label>
                                            <input type="text" class="form-control" value="ali@gmail.com" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Order Date</label>
                                            <input type="text" class="form-control" value="2025-07-10 14:30" readonly>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="form-label">Shipping Address</label>
                                            <textarea class="form-control" rows="2" readonly>House #123, Street 5, Lahore, Pakistan</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Items</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Vendor</th>
                                                    <th>Price</th>
                                                    <th>Qty</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/50" width="40" height="40" class="me-2">
                                                            <span>Toyota Brake Pad</span>
                                                        </div>
                                                    </td>
                                                    <td>Malik AutoParts</td>
                                                    <td>Rs. 4,500</td>
                                                    <td>1</td>
                                                    <td>Rs. 4,500</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="https://via.placeholder.com/50" width="40" height="40" class="me-2">
                                                            <span>Oil Filter</span>
                                                        </div>
                                                    </td>
                                                    <td>Malik AutoParts</td>
                                                    <td>Rs. 900</td>
                                                    <td>1</td>
                                                    <td>Rs. 900</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-end">Subtotal:</td>
                                                    <td>Rs. 5,400</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end">Shipping:</td>
                                                    <td>Rs. 200</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end">Discount:</td>
                                                    <td>- Rs. 0</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                                    <td class="fw-bold">Rs. 5,600</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Notes</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Customer Note</label>
                                        <textarea class="form-control" rows="2" readonly>Please deliver before 5 PM</textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Admin Notes</label>
                                        <textarea class="form-control" rows="3">Customer called to confirm delivery time</textarea>
                                    </div>
                                    <button class="btn btn-primary">Save Notes</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Order ID:</span>
                                        <strong>#10520</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Status:</span>
                                        <strong><span class="status-badge status-pending">Pending</span></strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Payment Method:</span>
                                        <strong>Cash on Delivery</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Payment Status:</span>
                                        <strong>Pending</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping Method:</span>
                                        <strong>Standard Delivery</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span>Tracking Number:</span>
                                        <strong>TRK-10520</strong>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <strong>Rs. 5,400</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Shipping:</span>
                                        <strong>Rs. 200</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Discount:</span>
                                        <strong>Rs. 0</strong>
                                    </div>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <strong>Rs. 5,600</strong>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Status Timeline</h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-dot active"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Order Placed</h6>
                                                <p class="text-muted small mb-0">2025-07-10 14:30</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Processing</h6>
                                                <p class="text-muted small mb-0">Not started</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Shipped</h6>
                                                <p class="text-muted small mb-0">Not started</p>
                                            </div>
                                        </div>
                                        <div class="timeline-item">
                                            <div class="timeline-dot"></div>
                                            <div class="timeline-content">
                                                <h6 class="mb-1">Delivered</h6>
                                                <p class="text-muted small mb-0">Not started</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Order Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Update Status</label>
                                        <select class="form-select mb-2">
                                            <option>Pending</option>
                                            <option>Processing</option>
                                            <option>Shipped</option>
                                            <option>Delivered</option>
                                            <option>Cancelled</option>
                                        </select>
                                        <button class="btn btn-primary w-100">Update</button>
                                    </div>
                                    
                                    <button class="btn btn-success w-100 mb-2"><i class="fas fa-file-invoice me-2"></i> Generate Invoice</button>
                                    <button class="btn btn-warning w-100 mb-2"><i class="fas fa-exchange-alt me-2"></i> Process Return</button>
                                    <button class="btn btn-danger w-100"><i class="fas fa-times me-2"></i> Cancel Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Changes</button>
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
                onClose: function(selectedDates) {
                    if (selectedDates.length === 2) {
                        filterTable();
                    }
                }
            });

            // Search Functionality
            const orderSearch = document.getElementById('orderSearch');
            const orderTable = document.querySelector('.table');
            const tableRows = orderTable.querySelectorAll('tbody tr');
            
            orderSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
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
            
            // Vendor Filter
            const vendorFilterItems = document.querySelectorAll('.filter-vendor');
            let currentVendorFilter = 'all';
            
            vendorFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    currentVendorFilter = this.getAttribute('data-vendor');
                    document.querySelector('#vendorFilterDropdown').innerHTML = 
                        `<i class="fas fa-store me-1"></i> Vendor: ${this.textContent}`;
                    
                    filterTable();
                });
            });
            
            // Combined Filter Function
            function filterTable() {
                const searchTerm = orderSearch.value.toLowerCase();
                
                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    const statusCell = row.querySelector('.status-badge');
                    const status = statusCell ? statusCell.textContent.toLowerCase() : '';
                    const vendorCell = row.cells[2]; // Vendor column
                    const vendor = vendorCell ? vendorCell.textContent.toLowerCase() : '';
                    
                    // Search filter
                    const searchMatch = searchTerm === '' || rowText.includes(searchTerm);
                    
                    // Status filter
                    const statusMatch = currentStatusFilter === 'all' || 
                                      (currentStatusFilter === 'pending' && status.includes('pending')) ||
                                      (currentStatusFilter === 'processing' && status.includes('processing')) ||
                                      (currentStatusFilter === 'shipped' && status.includes('shipped')) ||
                                      (currentStatusFilter === 'delivered' && status.includes('delivered')) ||
                                      (currentStatusFilter === 'cancelled' && status.includes('cancelled')) ||
                                      (currentStatusFilter === 'returned' && status.includes('returned'));
                    
                    // Vendor filter
                    const vendorMatch = currentVendorFilter === 'all' || 
                                      vendor.includes(currentVendorFilter);
                    
                    // Show/hide based on all filters
                    if (searchMatch && statusMatch && vendorMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // console.log(data);
            document.querySelectorAll('.view-order').forEach(button => {
                // console.log(data);
                button.addEventListener('click', function () {
                    const orderId = this.getAttribute('data-order-id');

                    fetch('get_order_details.php?order_id=' + orderId)
                        .then(response => response.json())
                        .then(data => {
                            // Fill modal fields
                            console.log(4121);
                            document.getElementById('orderDetailsModalLabel').innerText = `Order #${data.order.id} Details`;
                            
                            // Example: fill inputs
                            document.querySelector('input[value="Ali Khan"]').value = data.customer.full_name;
                            document.querySelector('input[value="0300-1234567"]').value = data.customer.phone;
                            document.querySelector('input[value="ali@gmail.com"]').value = data.customer.email;
                            document.querySelector('input[value="2025-07-10 14:30"]').value = data.order.order_date;
                            document.querySelector('textarea[readonly]').value = data.shipping.address;

                            // Example: dynamically load order items
                            const tbody = document.querySelector('#orderDetailsModal tbody');
                            tbody.innerHTML = '';
                            data.items.forEach(item => {
                                tbody.innerHTML += `
                                    <tr>
                                        <td><div class="d-flex align-items-center">
                                            <img src="${item.image}" width="40" height="40" class="me-2">
                                            <span>${item.name}</span></div></td>
                                        <td>${item.vendor}</td>
                                        <td>Rs. ${item.price}</td>
                                        <td>${item.qty}</td>
                                        <td>Rs. ${item.total}</td>
                                    </tr>`;
                            });

                            // Update totals
                            document.querySelector('tfoot').innerHTML = `
                                <tr><td colspan="4" class="text-end">Subtotal:</td><td>Rs. ${data.order.subtotal}</td></tr>
                                <tr><td colspan="4" class="text-end">Shipping:</td><td>Rs. ${data.order.shipping}</td></tr>
                                <tr><td colspan="4" class="text-end">Discount:</td><td>- Rs. ${data.order.discount}</td></tr>
                                <tr><td colspan="4" class="text-end fw-bold">Total:</td><td class="fw-bold">Rs. ${data.order.total}</td></tr>
                            `;
                        });
                });
            });
        });
        </script>

</body>
</html>