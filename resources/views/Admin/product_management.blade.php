<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management | MJCheezain Admin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FFF6F0;
            overflow-x: hidden;
        }

        .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }

        .main-content { padding: 2rem; }
        @media (max-width: 767px) { .main-content { padding: 4.5rem 1rem 1.5rem; } }

        /* Cards */
        .card {
            border: 1px solid #FDE7EE;
            border-radius: 1rem;
            box-shadow: 0 8px 24px rgba(232, 93, 133, 0.08);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #FDE7EE;
            font-weight: 600;
            padding: 1.25rem 1.5rem;
            border-radius: 1rem 1rem 0 0 !important;
        }

        /* Summary Cards */
        .summary-card {
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            background: white;
            border: 1px solid #FDE7EE;
            box-shadow: 0 8px 24px rgba(232, 93, 133, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(232, 93, 133, 0.14);
        }

        .summary-card i {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            width: 44px; height: 44px;
            display: inline-flex; align-items: center; justify-content: center;
            border-radius: 12px;
        }

        .summary-card .card-title {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.35rem;
            font-weight: 500;
        }

        .summary-card .card-value {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 0;
            color: #111827;
        }

        /* Icon tints */
        .tint-pink { background: rgba(232, 93, 133, 0.12); color: #E85D85; }
        .tint-green { background: rgba(46, 204, 113, 0.12); color: #27ae60; }
        .tint-red { background: rgba(231, 76, 60, 0.12); color: #e74c3c; }
        .tint-amber { background: rgba(243, 156, 18, 0.12); color: #e67e22; }
        .tint-teal { background: rgba(26, 188, 156, 0.12); color: #16a085; }

        /* Table */
        .table { margin-bottom: 0; }
        .table th {
            font-weight: 600;
            border-top: none;
            border-bottom: 1px solid #FDE7EE;
            padding: 1rem 0.75rem;
            color: #6b7280;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            white-space: nowrap;
        }
        .table td { padding: 0.75rem; vertical-align: middle; font-size: 0.875rem; }
        .table-hover tbody tr:hover { background-color: rgba(255, 125, 160, 0.05); }

        /* Action Buttons */
        .action-btn {
            padding: 0.35rem 0.65rem;
            margin: 0 2px;
            font-size: 0.75rem;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .action-btn:hover { transform: translateY(-1px); box-shadow: 0 2px 6px rgba(232, 93, 133, 0.2); }

        /* Position (approval status) badges */
        .position-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
            white-space: nowrap;
        }
        .position-pending { background-color: rgba(243, 156, 18, 0.15); color: #e67e22; }
        .position-approved { background-color: rgba(46, 204, 113, 0.15); color: #27ae60; }
        .position-rejected { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; }
        .position-disabled { background-color: rgba(149, 165, 166, 0.2); color: #7f8c8d; }
        .position-draft { background-color: rgba(107, 114, 128, 0.15); color: #6b7280; }

        /* Search / filter controls */
        .form-control:focus, .btn:focus {
            border-color: #E85D85;
            box-shadow: 0 0 0 0.2rem rgba(232, 93, 133, 0.15);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #FFF6F0; }
        ::-webkit-scrollbar-thumb { background: #F9C7D5; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #E85D85; }
    </style>
</head>
<body class="text-gray-800">
<div class="flex min-h-screen">
    <!-- Sidebar -->
    <x-admin.sidebar />

    <!-- Page Content -->
    <div id="content" class="flex-1 min-w-0">

        <!-- Main Content -->
        <div class="main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="mb-0 fw-bold"><i class="fas fa-box me-2" style="color:#E85D85;"></i> Product Management</h2>
                    <p class="text-muted mb-0" style="font-size: 0.875rem;">Approve, reject and moderate marketplace products</p>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-xl col-md-4 col-sm-6 mb-2">
                    <div class="summary-card">
                        <i class="fas fa-boxes-stacked tint-pink"></i>
                        <h6 class="card-title">Total Products</h6>
                        <h3 class="card-value">{{ $total }}</h3>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 mb-2">
                    <div class="summary-card">
                        <i class="fas fa-clock tint-amber"></i>
                        <h6 class="card-title">Pending Approval</h6>
                        <h3 class="card-value">{{ $pending }}</h3>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 mb-2">
                    <div class="summary-card">
                        <i class="fas fa-check-circle tint-green"></i>
                        <h6 class="card-title">Approved</h6>
                        <h3 class="card-value">{{ $approved }}</h3>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 mb-2">
                    <div class="summary-card">
                        <i class="fas fa-times-circle tint-red"></i>
                        <h6 class="card-title">Rejected</h6>
                        <h3 class="card-value">{{ $rejected }}</h3>
                    </div>
                </div>
                <div class="col-xl col-md-4 col-sm-6 mb-2">
                    <div class="summary-card">
                        <i class="fas fa-triangle-exclamation tint-teal"></i>
                        <h6 class="card-title">Out of Stock</h6>
                        <h3 class="card-value">{{ $out }}</h3>
                    </div>
                </div>
            </div>

            <!-- Product Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="mb-0">All Products</h5>
                    <div class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                        <!-- Search Form -->
                        <form method="GET" class="d-flex" onsubmit="return false;">
                            <div class="input-group" style="width: 280px;">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search products..."
                                    value="" aria-label="Search products">
                                <button type="button" id="searchButton" class="btn btn-outline-secondary">Search</button>
                                <button type="button" id="clearButton" class="btn btn-outline-secondary" title="Clear search">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </form>

                        <!-- Position Filter Dropdown -->
                        <div class="dropdown">
                            <button class="btn btn-light dropdown-toggle" type="button" id="positionFilterDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-filter me-1"></i> Status: All
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="positionFilterDropdown">
                                <li><a class="dropdown-item filter-option" href="#" data-status="all">All Products</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-status="pending">Pending</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-status="approved">Approved</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-status="rejected">Rejected</a></li>
                                <li><a class="dropdown-item filter-option" href="#" data-status="disable">Disabled</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="40px">ID</th>
                                    <th>Product</th>
                                    <th>Vendor</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">

                                @if(count($autoProducts) > 0)
                                    @foreach($autoProducts as $product)
                                        <tr>
                                            <td>{{ \App\Support\RefId::product($product->id) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span>{{ $product->product_name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $storeNames[$product->vendor_id] ?? 'N/A' }}</td>
                                            <td>Autoparts</td>
                                            <td>
                                                @php
                                                    switch ($product->status) {
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
                                            <td class="text-nowrap">Rs. {{ number_format($product->selling_price) }}</td>
                                            <td>N/A</td>
                                            <td class="text-nowrap">
                                                <a href="/product/{{ $product->id }}" target="_blank">
                                                    <button type="button" class="btn btn-sm btn-info action-btn" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </a>

                                                @if($product->status != "approved")
                                                    <button type="button" class="btn btn-sm btn-success action-btn" title="Approve"
                                                            onclick="changeProductPosition({{ $product->id }}, 2, true)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif

                                                @if($product->status != "rejected")
                                                    <button type="button" class="btn btn-sm btn-danger action-btn" title="Reject"
                                                            onclick="changeProductPosition({{ $product->id }}, 3, true)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif

                                                @if($product->status != "disable")
                                                    <button type="button" class="btn btn-sm btn-warning action-btn" title="Disable"
                                                            onclick="changeProductPosition({{ $product->id }}, 4, true)">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-success action-btn" title="Enable"
                                                            onclick="changeProductPosition({{ $product->id }}, 2, true)">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif

                                                <button type="button" class="btn btn-sm btn-secondary action-btn" title="Delete"
                                                        onclick="confirmDelete({{ $product->id }}, true)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                                @if(count($products) > 0)
                                    @foreach($products as $product)
                                        @php $isDraft = ($product->position === 'draft'); @endphp
                                        <tr>
                                            <td>{{ \App\Support\RefId::product($product->id) }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span>{{ $product->name }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $storeNames[$product->user_id] ?? 'N/A' }}</td>
                                            <td>{{ $product->category }}</td>
                                            <td>
                                                @php
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
                                                        case "draft":
                                                            $position_class = 'position-draft';
                                                            $position_text = 'Draft (vendor)';
                                                            break;
                                                        default:
                                                            $position_class = 'position-pending';
                                                            $position_text = 'Unknown';
                                                    }
                                                @endphp
                                                <span class="position-badge {{ $position_class }}">{{ $position_text }}</span>
                                            </td>
                                            <td>{{ $product->quantity }}</td>
                                            <td class="text-nowrap">Rs. {{ number_format($product->selling_price) }}</td>
                                            <td>{{ $product->rating ? number_format($product->rating, 1) . '/5' : 'N/A' }}</td>
                                            <td class="text-nowrap">
                                                <a href="/product/{{ $product->id }}" target="_blank">
                                                    <button type="button" class="btn btn-sm btn-info action-btn" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </a>

                                                @if($isDraft)
                                                    {{-- Drafts are vendor-private: no moderation actions --}}
                                                    <button type="button" class="btn btn-sm btn-secondary action-btn" title="Delete"
                                                            onclick="confirmDelete({{ $product->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @else
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
                                                @endif
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
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to change product position
        function changeProductPosition(productId, position, auto = false) {
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
                        position: positionText,
                        autopart: auto
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
        function confirmDelete(productId, auto = false) {
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
                        product_id: productId,
                        autopart: auto
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

        // Search and Filter Functionality for Products
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize search and filter functionality
            initProductSearchFilter();

            function initProductSearchFilter() {
                // Get all product rows from the table
                const productRows = Array.from(document.querySelectorAll('#productTableBody tr'));
                let currentFilter = 'all';
                let currentSearch = '';

                // Store original rows data for filtering
                const originalRows = productRows.map(row => {
                    const cells = row.cells;
                    const statusBadge = cells[4] ? cells[4].querySelector('.position-badge') : null;

                    return {
                        element: row,
                        id: cells[0] ? cells[0].textContent.trim() : '',
                        name: cells[1] ? cells[1].textContent.toLowerCase() : '',
                        vendor: cells[2] ? cells[2].textContent.toLowerCase() : '',
                        category: cells[3] ? cells[3].textContent.toLowerCase() : '',
                        status: statusBadge ? statusBadge.textContent.toLowerCase() : '',
                        stock: cells[5] ? cells[5].textContent.trim() : '',
                        price: cells[6] ? cells[6].textContent.trim() : '',
                        rating: cells[7] ? cells[7].textContent.toLowerCase() : ''
                    };
                });

                // DOM Elements
                const searchInput = document.getElementById('searchInput');
                const searchButton = document.getElementById('searchButton');
                const clearButton = document.getElementById('clearButton');
                const filterOptions = document.querySelectorAll('.filter-option');
                const filterDropdown = document.getElementById('positionFilterDropdown');

                // Search functionality
                if (searchButton) {
                    searchButton.addEventListener('click', performSearch);
                }

                if (searchInput) {
                    searchInput.addEventListener('keyup', function(event) {
                        if (event.key === 'Enter') {
                            performSearch();
                        }
                    });

                    // Real-time search as you type (debounced)
                    let searchTimeout;
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(() => {
                            currentSearch = this.value.toLowerCase().trim();
                            applyFilter(currentFilter);
                        }, 300);
                    });
                }

                if (clearButton) {
                    clearButton.addEventListener('click', clearSearch);
                }

                // Filter functionality
                if (filterOptions) {
                    filterOptions.forEach(option => {
                        option.addEventListener('click', function(e) {
                            e.preventDefault();
                            const status = this.getAttribute('data-status');
                            applyFilter(status);

                            // Update dropdown button text
                            const statusText = this.textContent.trim();
                            updateFilterButtonText(statusText);
                        });
                    });
                }

                function performSearch() {
                    currentSearch = searchInput.value.toLowerCase().trim();
                    applyFilter(currentFilter);
                }

                function clearSearch() {
                    searchInput.value = '';
                    currentSearch = '';
                    applyFilter(currentFilter);
                }

                function applyFilter(status) {
                    currentFilter = status;
                    const tbody = document.getElementById('productTableBody');

                    // Clear current table content
                    tbody.innerHTML = '';

                    // Filter rows based on search and status
                    const filteredRows = originalRows.filter(row => {
                        // Apply status filter
                        if (currentFilter !== 'all') {
                            if (currentFilter === 'pending' && row.status !== 'pending') return false;
                            if (currentFilter === 'approved' && row.status !== 'approved') return false;
                            if (currentFilter === 'rejected' && row.status !== 'rejected') return false;
                            if (currentFilter === 'disable' && row.status !== 'disabled') return false;
                        }

                        // Apply search filter
                        if (currentSearch) {
                            const searchTerm = currentSearch.toLowerCase();
                            return (
                                row.id.includes(searchTerm) ||
                                row.name.includes(searchTerm) ||
                                row.vendor.includes(searchTerm) ||
                                row.category.includes(searchTerm) ||
                                row.status.includes(searchTerm) ||
                                row.price.includes(searchTerm) ||
                                row.rating.includes(searchTerm)
                            );
                        }

                        return true;
                    });

                    // Add filtered rows back to table
                    if (filteredRows.length > 0) {
                        filteredRows.forEach(row => {
                            tbody.appendChild(row.element);
                        });
                    } else {
                        // Show "no results" message
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-search fa-2x mb-3" style="color:#E85D85;"></i>
                                        <h5>No products found</h5>
                                        <p>Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }
                }

                function updateFilterButtonText(text) {
                    if (filterDropdown) {
                        // Clean the text (remove any existing status badge)
                        const cleanText = text.replace('●', '').trim();
                        const statusText = cleanText === 'All Products' ? 'All' : cleanText;
                        filterDropdown.innerHTML = `<i class="fas fa-filter me-1"></i> Status: ${statusText}`;
                    }
                }
            }
        });
    </script>
</body>
</html>
