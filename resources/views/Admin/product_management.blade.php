<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Product Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/css/style.css') }}">

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
            /* margin-left: var(--sidebar-width); */
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
<body class="bg-gray-100 text-gray-800">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <x-admin.sidebar />

    <!-- Page Content -->
    <div id="content">

        <!-- Main Content -->
        <div class="main-content">            
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
                        <h3 class="card-value">{{ $total }}</h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-warning-light">
                        <i class="fas fa-clock text-warning"></i>
                        <h6 class="card-title">Pending Approval</h6>
                        <h3 class="card-value">
                            {{ $pending }}
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-success-light">
                        <i class="fas fa-check-circle text-success"></i>
                        <h6 class="card-title">Approved Products</h6>
                        <h3 class="card-value">
                            {{ $approved }}
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-danger-light">
                        <i class="fas fa-times-circle text-danger"></i>
                        <h6 class="card-title">Rejected Products</h6>
                        <h3 class="card-value">
                            {{ $rejected }}
                        </h3>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                    <div class="summary-card bg-info-light">
                        <i class="fas fa-exclamation-triangle text-info"></i>
                        <h6 class="card-title">Out of Stock</h6>
                        <h3 class="card-value">
                            {{ $out }}
                        </h3>
                    </div>
                </div>
                {{-- <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
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
                </div> --}}
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
                                       value="" aria-label="Search products">
                                <input type="hidden" name="position" value="">
                                <input type="hidden" name="vendor" value="">
                                <input type="hidden" name="category" value="">
                            </div>
                        </form>
                        
                        <!-- position Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="positionFilterDropdown" 
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> position: Pending
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="positionFilterDropdown">
                                <li><a class="dropdown-item" href="?position=all">All Products</a></li>
                                <li><a class="dropdown-item" href="?position=0">Pending</a></li>
                                <li><a class="dropdown-item" href="?position=1">Approved</a></li>
                                <li><a class="dropdown-item" href="?position=2">Rejected</a></li>
                                <li><a class="dropdown-item" href="?position=3">Disabled</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" id="bulkActionForm">
                        {{-- <div class="d-flex justify-content-between align-items-center mb-3">
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
                        </div> --}}
                        
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="40px">ID</th>
                                        <th class="sortable" data-sort="name">Product</th>
                                        <th class="sortable" data-sort="vendor_name">Vendor</th>
                                        <th class="sortable" data-sort="category">Category</th>
                                        <th class="sortable" data-sort="position">position</th>
                                        <th class="sortable" data-sort="quantity">Stock</th>
                                        <th class="sortable>" data-sort="selling_price">Price</th>
                                        <th class="sortable" data-sort="rating">Rating</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($products) > 0)
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{ $product->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        {{-- @php
                                                            // Get primary image for the product
                                                            $image = $product->primary_image ?? 'https://via.placeholder.com/100';
                                                        @endphp
                                                        <img src="{{ asset('vendor/' . $image) }}" class="product-thumb me-2"> --}}
                                                        <span>{{ $product->name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ DB::table('vendor_basic_info')->where('user_id', $product->user_id)->value('store_name') }}</td>
                                                <td>{{ $product->category }}</td>
                                                <td>
                                                    @php
                                                        $position_class = '';
                                                        $position_text = '';
                                                        switch ($product->position) {
                                                            case "pending":
                                                                $position_class = 'position-pending';
                                                                $position_text = 'Pending';
                                                                break;
                                                            case "approved":
                                                                $position_class = 'position-approved';
                                                                $position_text = 'Approved';
                                                                break;
                                                            case "rejected":
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
                                                    @endphp
                                                    <span class="position-badge {{ $position_class }}">{{ $position_text }}</span>
                                                </td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>Rs. {{ number_format($product->selling_price) }}</td>
                                                <td>{{ $product->rating ? number_format($product->rating, 1) . '/5' : 'N/A' }}</td>
                                                <td>
                                                    <a href="/product/{{ $product->id }}" target="_blank">
                                                        <button type="button" class="btn btn-sm btn-info action-btn" title="View" data-bs-toggle="modal" data-bs-target="#productDetailsModal" 
                                                                onclick="">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </a>
                                                    
                                                    @if($product->position != "approved")
                                                        <button type="button" class="btn btn-sm btn-success action-btn" title="Approve" 
                                                                onclick="changeProductPosition({{ $product->id }}, 2)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif

                                                    @if($product->position != "rejected")
                                                        <button type="button" class="btn btn-sm btn-danger action-btn" title="Reject" 
                                                                onclick="changeProductPosition({{ $product->id }}, 3)">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @endif

                                                    @if($product->position != "disable")
                                                        <button type="button" class="btn btn-sm btn-warning action-btn" title="Disable" 
                                                                onclick="changeProductPosition({{ $product->id }}, 4)">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-success action-btn" title="Enable" 
                                                                onclick="changeProductPosition({{ $product->id }}, 2)">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    @endif

                                                    <button type="button" class="btn btn-sm btn-secondary action-btn" title="Delete" 
                                                            onclick="confirmDelete({{ $product->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center">No products found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </form>
                    
                    <!-- Pagination -->
                    {{-- <nav aria-label="Page navigation" class="mt-3">
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
                    </nav> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    {{-- <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
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
    </div> --}}

    <!-- Product Details Modal -->
    {{-- <div class="modal fade" id="productDetailsModal" tabindex="-1" aria-labelledby="productDetailsModalLabel" aria-hidden="true">
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
    </div> --}}

    <!-- position Change Form (hidden) -->
    {{-- <form id="positionChangeForm" method="POST" style="display: none;">
        <input type="hidden" name="product_id" id="positionProductId">
        <input type="hidden" name="new_position" id="newpositionValue">
        <input type="submit" name="change_position" id="posBtn" value="submit">
    </form> --}}
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

        // Function to change product position
        function changeProductPosition(productId, position) {
            let action = "";
            let positionText = "";
            
            // Define action and position text based on position value
            switch(position) {
                case 2:
                    action = "approve";
                    positionText = "approved";
                    break;
                case 3:
                    action = "reject";
                    positionText = "rejected";
                    break;
                case 4:
                    action = "disable";
                    positionText = "disabled";
                    break;
                default:
                    action = "update";
                    positionText = "updated";
            }
            
            // Show confirmation alert
            if(confirm(`Are you sure you want to ${action} this product?`)) {
                // Show loading state
                const button = event.target.closest('.action-btn');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Send AJAX request
                fetch('/admin/change-product-position', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        position: positionText
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert(data.message);
                        // Reload the page to reflect changes
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        // Reset button
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating product position');
                    // Reset button
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                });
            }
        }

        // Function to confirm and delete product
        function confirmDelete(productId) {
            if(confirm('Are you sure you want to delete this product? This action cannot be undone!')) {
                // Show loading state
                const button = event.target.closest('.action-btn');
                const originalHTML = button.innerHTML;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                button.disabled = true;
                
                // Send AJAX request
                fetch('/admin/delete-product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert(data.message);
                        // Remove the product row from table or reload page
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        // Reset button
                        button.innerHTML = originalHTML;
                        button.disabled = false;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting product');
                    // Reset button
                    button.innerHTML = originalHTML;
                    button.disabled = false;
                });
            }
        }
    </script>
</body>
</html>