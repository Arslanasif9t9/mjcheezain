<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Vendor Management</title>
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
                    <h2 class="mb-0"><i class="fas fa-users-cog me-2"></i> Vendor Management</h2>
                    {{-- <a href="add_vendor.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i> Add New Vendor</a> --}}
                </div>
    
                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-primary-light">
                            <i class="fas fa-users text-primary"></i>
                            <h6 class="card-title">Total Vendors</h6>
                            <h3 class="card-value">
                                {{ $totalUsers }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-success-light">
                            <i class="fas fa-check-circle text-success"></i>
                            <h6 class="card-title">Active</h6>
                            <h3 class="card-value">
                                {{ $activeUsers }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-danger-light">
                            <i class="fas fa-ban text-danger"></i>
                            <h6 class="card-title">Blocked</h6>
                            <h3 class="card-value">
                                {{ $blocked }}
                            </h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-warning-light">
                            <i class="fas fa-clock text-warning"></i>
                            <h6 class="card-title">Pending</h6>
                            <h3 class="card-value">
                                {{ $pendding }}
                            </h3>
                        </div>
                    </div>
                    {{-- <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card bg-info-light">
                            <i class="fas fa-boxes text-info"></i>
                            <h6 class="card-title">Products</h6>
                            <h3 class="card-value">
                                {{ $products }}
                            </h3>
                        </div>
                    </div> --}}
                    {{-- <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
                        <div class="summary-card" style="background-color: rgba(155, 89, 182, 0.1); color: #9b59b6;">
                            <i class="fas fa-dollar-sign" style="color: #9b59b6;"></i>
                            <h6 class="card-title">Earnings</h6>
                            <h3 class="card-value">Rs. 
                                0
                            </h3>
                        </div>
                    </div> --}}
                </div>
    
                <!-- Vendor Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">All Vendors</h5>
                        <div class="d-flex">
                            <form method="get" class="input-group me-3" style="width: 250px;">
                                <input type="hidden" name="status" value="">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search vendors..." value="">
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
                        <!-- Success/Error Message Alert -->
                        <div id="statusMessage" class="alert d-none"></div>

                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
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
                                    @foreach ($vendors as $vendor)
                                        @php
                                            // Try to get vendor details from $vendors first, then fall back to $vendorsBasic
                                            $vendorData = $vendor;
                                            $vendorBasic = DB::table('vendor_basic_info')->where('user_id', $vendor->user_id)->first();
                                            $products = DB::table('vendor_products')->where('user_id', $vendor->user_id)->count();
                                            
                                            // Use basic info if main vendor data is missing certain fields
                                            $fullName = $vendor->full_name ?? $vendorBasic->full_name ?? 'N/A';
                                            $email = $vendor->email ?? $vendorBasic->email ?? 'N/A';
                                            $phone = $vendor->phone ?? $vendorBasic->phone ?? 'N/A';
                                            $status = $vendor->status ?? $vendorBasic->status ?? 'unknown';
                                            $userId = $vendor->user_id ?? $vendorBasic->user_id ?? null;
                                        @endphp

                                        <tr id="vendor-row-{{ $userId }}">
                                            <td>{{ $vendor->user_id }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/vendor/profile/' . ($vendorBasic->profile_picture ?? 'default_profile.webp')) }}" 
                                                        width="32" height="32" class="rounded-circle me-2">
                                                    <span>{{ $fullName }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $storeName = $vendor->store_name ?? $vendorBasic->store_name ?? 
                                                            $vendor->business_name ?? $vendorBasic->business_name ?? 'N/A';
                                                @endphp
                                                {{ $storeName }}
                                            </td>
                                            <td>{{ $email }}</td>
                                            <td>{{ $phone }}</td>
                                            <td>
                                                <span class="status-badge status-{{ $status }}" id="status-badge-{{ $userId }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $createdAt = $vendor->created_at ?? $vendorBasic->created_at ?? null;
                                                @endphp
                                                {{ $createdAt ? date('d M Y', strtotime($createdAt)) : 'N/A' }}
                                            </td>
                                            <td>{{ $products }}</td>
                                            <td>{{ DB::table('orders')->where('user_id', $vendor->user_id)->count() }}</td>
                                            <td>Rs. {{ DB::table('orders')->where('vendor_id', $vendor->user_id)->sum('total_amount') ?? 0 }}</td>
                                            <td id="action-buttons-{{ $userId }}">
                                                @if($userId)
                                                    <button class="btn btn-sm btn-info action-btn" title="View Details" 
                                                            data-bs-toggle="modal" data-bs-target="#vendorDetailsModal"
                                                            onclick="loadVendorDetails({{ $userId }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    
                                                    @if ($status == 'active')
                                                        <button class="btn btn-sm btn-danger action-btn" title="Block" 
                                                                onclick="confirmStatusChange({{ $userId }}, 'blocked', 'Block')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    @elseif ($status == 'pending')
                                                        <button class="btn btn-sm btn-success action-btn" title="Approve" 
                                                                onclick="confirmStatusChange({{ $userId }}, 'active', 'Approve')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-danger action-btn" title="Reject" 
                                                                onclick="confirmStatusChange({{ $userId }}, 'blocked', 'Reject')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    @elseif ($status == 'blocked')
                                                        <button class="btn btn-sm btn-warning action-btn" title="Unblock" 
                                                                onclick="confirmStatusChange({{ $userId }}, 'active', 'Unblock')">
                                                            <i class="fas fa-lock-open"></i>
                                                        </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No actions available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
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
        fetch(`/admin/vendor/details/${userId}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                
                if (data.success) {
                    document.getElementById('vendorDetailsContent').innerHTML = data.html;
                } else {
                    document.getElementById('vendorDetailsContent').innerHTML = 
                        `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('vendorDetailsContent').innerHTML = 
                    '<div class="alert alert-danger">Error loading vendor details</div>';
            });
    }

    // Show confirmation dialog for status change
    function confirmStatusChange(userId, newStatus, action) {
        const actionMap = {
            'active': 'approve',
            'blocked': action.toLowerCase() === 'reject' ? 'reject' : 'block'
        };
        
        const actionText = action.toLowerCase();
        const vendorName = document.querySelector(`#vendor-row-${userId} td:nth-child(2) span`).textContent;
        
        Swal.fire({
            title: `${action} Vendor`,
            html: `Are you sure you want to <strong>${actionText}</strong> vendor <strong>"${vendorName}"</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: newStatus === 'active' ? '#28a745' : (actionText === 'reject' ? '#dc3545' : '#ffc107'),
            cancelButtonColor: '#6c757d',
            confirmButtonText: `Yes, ${actionText}!`,
            cancelButtonText: 'Cancel',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return updateVendorStatus(userId, newStatus, actionMap[newStatus]);
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Success message will be shown in updateVendorStatus
            }
        });
    }

    // Update vendor status via AJAX
    async function updateVendorStatus(userId, newStatus, action) {
        try {
            const response = await fetch('{{ route("admin.vendor.status") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: newStatus,
                    action: action
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Update status badge
                const statusBadge = document.getElementById(`status-badge-${userId}`);
                statusBadge.className = `status-badge status-${newStatus}`;
                statusBadge.textContent = newStatus;

                // Update action buttons
                updateActionButtons(userId, newStatus);

                // Show success message
                showMessage(`Vendor ${action}ed successfully!`, 'success');
                
                return true;
            } else {
                throw new Error(data.message || 'Failed to update status');
            }
        } catch (error) {
            console.error('Error updating vendor status:', error);
            showMessage(`Error: ${error.message}`, 'error');
            return false;
        }
    }

    // Update action buttons based on new status
    function updateActionButtons(userId, newStatus) {
        const actionContainer = document.getElementById(`action-buttons-${userId}`);
        
        let buttonsHtml = `
            <button class="btn btn-sm btn-info action-btn" title="View Details" 
                    data-bs-toggle="modal" data-bs-target="#vendorDetailsModal"
                    onclick="loadVendorDetails(${userId})">
                <i class="fas fa-eye"></i>
            </button>
        `;

        if (newStatus === 'active') {
            buttonsHtml += `
                <button class="btn btn-sm btn-danger action-btn" title="Block" 
                        onclick="confirmStatusChange(${userId}, 'blocked', 'Block')">
                    <i class="fas fa-ban"></i>
                </button>
            `;
        } else if (newStatus === 'pending') {
            buttonsHtml += `
                <button class="btn btn-sm btn-success action-btn" title="Approve" 
                        onclick="confirmStatusChange(${userId}, 'active', 'Approve')">
                    <i class="fas fa-check"></i>
                </button>
                <button class="btn btn-sm btn-danger action-btn" title="Reject" 
                        onclick="confirmStatusChange(${userId}, 'blocked', 'Reject')">
                    <i class="fas fa-times"></i>
                </button>
            `;
        } else if (newStatus === 'blocked') {
            buttonsHtml += `
                <button class="btn btn-sm btn-warning action-btn" title="Unblock" 
                        onclick="confirmStatusChange(${userId}, 'active', 'Unblock')">
                    <i class="fas fa-lock-open"></i>
                </button>
            `;
        }

        actionContainer.innerHTML = buttonsHtml;
    }

    // Show status message
    function showMessage(message, type) {
        const messageDiv = document.getElementById('statusMessage');
        messageDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} d-block`;
        messageDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span>${message}</span>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.classList.add('d-none')"></button>
            </div>
        `;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            messageDiv.classList.add('d-none');
        }, 5000);
    }

    // Add SweetAlert2 for beautiful alerts (include this CDN in your head)
    // <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</script>
</body>
</html>