<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Management | MJCheezain Admin</title>
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

        /* Status Badges */
        .status-badge {
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-active { background-color: rgba(46, 204, 113, 0.15); color: #27ae60; }
        .status-pending { background-color: rgba(243, 156, 18, 0.15); color: #e67e22; }
        .status-blocked { background-color: rgba(231, 76, 60, 0.15); color: #e74c3c; }
        .status-unknown { background-color: rgba(149, 165, 166, 0.15); color: #7f8c8d; }

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

        /* Icon tints */
        .tint-pink { background: rgba(232, 93, 133, 0.12); color: #E85D85; }
        .tint-green { background: rgba(46, 204, 113, 0.12); color: #27ae60; }
        .tint-red { background: rgba(231, 76, 60, 0.12); color: #e74c3c; }
        .tint-amber { background: rgba(243, 156, 18, 0.12); color: #e67e22; }

        /* Search / filter controls */
        .form-control:focus, .btn:focus {
            border-color: #E85D85;
            box-shadow: 0 0 0 0.2rem rgba(232, 93, 133, 0.15);
        }

        /* Bootstrap modal must sit above the sticky sidebar (z-index 10001) */
        .modal-backdrop { z-index: 19999; }
        .modal { z-index: 20000; }

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
                        <h2 class="mb-0 fw-bold"><i class="fas fa-store me-2" style="color:#E85D85;"></i> Vendor Management</h2>
                        <p class="text-muted mb-0" style="font-size: 0.875rem;">Approve, block and review marketplace vendors</p>
                    </div>
                </div>

                <!-- Summary Cards -->
                <div class="row">
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                        <div class="summary-card">
                            <i class="fas fa-store tint-pink"></i>
                            <h6 class="card-title">Total Vendors</h6>
                            <h3 class="card-value">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                        <div class="summary-card">
                            <i class="fas fa-check-circle tint-green"></i>
                            <h6 class="card-title">Active</h6>
                            <h3 class="card-value">{{ $activeUsers }}</h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                        <div class="summary-card">
                            <i class="fas fa-ban tint-red"></i>
                            <h6 class="card-title">Blocked</h6>
                            <h3 class="card-value">{{ $blocked }}</h3>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-sm-6 mb-2">
                        <div class="summary-card">
                            <i class="fas fa-clock tint-amber"></i>
                            <h6 class="card-title">Pending</h6>
                            <h3 class="card-value">{{ $pendding }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Vendor Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">All Vendors</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <form method="get" class="input-group me-3" style="width: 280px;" onsubmit="return false;">
                                <input type="hidden" name="status" value="">
                                <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search vendors..." value="">
                                <button class="btn btn-outline-secondary" type="button" id="searchButton">Search</button>
                            </form>
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                                    <li><h6 class="dropdown-header">Filter by Status</h6></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-status="all">All Vendors</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-status="active"><span class="status-badge status-active me-2"></span> Active</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-status="pending"><span class="status-badge status-pending me-2"></span> Pending</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-status="blocked"><span class="status-badge status-blocked me-2"></span> Blocked</a></li>
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
                                <tbody id="vendorTableBody">
                                    @foreach ($vendors as $vendor)
                                        @php
                                            // Pre-fetched by the controller (keyed by user_id) — no per-row queries.
                                            $vendorBasic = $vendorsBasic[$vendor->user_id] ?? null;
                                            $productCount = $productCounts[$vendor->user_id] ?? 0;
                                            $orderCount = $orderCounts[$vendor->user_id] ?? 0;
                                            $earningsTotal = $vendorEarnings[$vendor->user_id] ?? 0;

                                            $fullName = $vendor->full_name ?? $vendorBasic->full_name ?? 'N/A';
                                            $email = $vendor->email ?? $vendorBasic->email ?? 'N/A';
                                            $phone = $vendor->phone ?? $vendorBasic->phone ?? 'N/A';
                                            $status = $vendor->status ?? $vendorBasic->status ?? 'unknown';
                                            $userId = $vendor->user_id ?? $vendorBasic->user_id ?? null;
                                            $storeName = $vendor->store_name ?? $vendorBasic->store_name ??
                                                    $vendor->business_name ?? $vendorBasic->business_name ?? 'N/A';
                                            $createdAt = $vendor->created_at ?? $vendorBasic->created_at ?? null;
                                        @endphp

                                        <tr id="vendor-row-{{ $userId }}">
                                            <td class="text-nowrap">USR-{{ str_pad($vendor->user_id, 6, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($vendor->created_at)->format('y') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/vendor/profile/' . ($vendorBasic->profile_picture ?? 'default_profile.webp')) }}"
                                                        width="32" height="32" class="rounded-circle me-2" style="object-fit: cover;">
                                                    <span>{{ $fullName }}</span>
                                                </div>
                                            </td>
                                            <td>{{ $storeName }}</td>
                                            <td>{{ $email }}</td>
                                            <td>{{ $phone }}</td>
                                            <td>
                                                <span class="status-badge status-{{ $status }}" id="status-badge-{{ $userId }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td class="text-nowrap">{{ $createdAt ? date('d M Y', strtotime($createdAt)) : 'N/A' }}</td>
                                            <td>{{ $productCount }}</td>
                                            <td>{{ $orderCount }}</td>
                                            <td class="text-nowrap">Rs. {{ number_format($earningsTotal) }}</td>
                                            <td id="action-buttons-{{ $userId }}" class="text-nowrap">
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
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Vendor Details Modal -->
    <div class="modal fade" id="vendorDetailsModal" tabindex="-1" aria-labelledby="vendorDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="border-radius: 1rem; border: 1px solid #FDE7EE;">
                <div class="modal-header brand-gradient text-white" style="border-radius: 1rem 1rem 0 0;">
                    <h5 class="modal-title fw-bold" id="vendorDetailsModalLabel"><i class="fas fa-store me-2"></i>Vendor Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
    // Search and Filter Functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Get all vendor rows from the table
        const vendorRows = Array.from(document.querySelectorAll('#vendorTableBody tr'));
        let currentFilter = 'all';
        let currentSearch = '';

        // Store original rows for resetting
        const originalRows = vendorRows.map(row => ({
            element: row,
            name: row.cells[1].textContent.toLowerCase(),
            store: row.cells[2].textContent.toLowerCase(),
            email: row.cells[3].textContent.toLowerCase(),
            phone: row.cells[4].textContent.toLowerCase(),
            status: row.cells[5].querySelector('.status-badge').textContent.trim(),
            id: row.cells[0].textContent.trim()
        }));

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');

        // Search when button is clicked
        if (searchButton) {
            searchButton.addEventListener('click', performSearch);
        }

        // Search as you type (debounced)
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('keyup', function(event) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    currentSearch = this.value.toLowerCase().trim();
                    filterAndSearch();
                }, 300);
            });
        }

        // Filter functionality
        const filterOptions = document.querySelectorAll('.filter-option');
        filterOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                e.preventDefault();
                currentFilter = this.getAttribute('data-status');
                updateFilterButtonText(this.textContent.trim());
                filterAndSearch();
            });
        });

        function performSearch() {
            currentSearch = searchInput.value.toLowerCase().trim();
            filterAndSearch();
        }

        function filterAndSearch() {
            const tbody = document.getElementById('vendorTableBody');

            // Clear current table content
            tbody.innerHTML = '';

            // Filter rows
            const filteredRows = originalRows.filter(row => {
                // Apply status filter
                if (currentFilter !== 'all' && row.status !== currentFilter) {
                    return false;
                }

                // Apply search filter
                if (currentSearch) {
                    const searchTerms = currentSearch.toLowerCase();
                    return (
                        row.name.includes(searchTerms) ||
                        row.store.includes(searchTerms) ||
                        row.email.includes(searchTerms) ||
                        row.phone.includes(searchTerms) ||
                        row.id.includes(searchTerms)
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
                        <td colspan="11" class="text-center py-4">
                            <div class="text-muted">
                                <i class="fas fa-search fa-2x mb-3" style="color:#E85D85;"></i>
                                <h5>No vendors found</h5>
                                <p>Try adjusting your search or filter</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        function updateFilterButtonText(text) {
            const filterButton = document.getElementById('filterDropdown');
            if (filterButton) {
                const cleanText = text.replace('●', '').trim();
                filterButton.innerHTML = `<i class="fas fa-filter"></i> ${cleanText}`;
            }
        }

        // Clear search and filter
        function clearFilters() {
            searchInput.value = '';
            currentSearch = '';
            currentFilter = 'all';
            updateFilterButtonText('Filter');
            filterAndSearch();
        }

        // Add clear button next to search
        function addClearButton() {
            const searchContainer = document.querySelector('.input-group');
            if (searchContainer) {
                const clearButton = document.createElement('button');
                clearButton.type = 'button';
                clearButton.className = 'btn btn-outline-secondary';
                clearButton.id = 'clearButton';
                clearButton.innerHTML = '<i class="fas fa-times"></i>';
                clearButton.title = 'Clear search and filters';
                clearButton.addEventListener('click', clearFilters);

                const searchBtn = document.getElementById('searchButton');
                searchContainer.insertBefore(clearButton, searchBtn.nextSibling);
            }
        }

        // Initialize
        addClearButton();
        filterAndSearch();
    });

    // Load vendor details via AJAX
    function loadVendorDetails(userId) {
        document.getElementById('vendorDetailsContent').innerHTML =
            '<div class="text-center py-5 text-muted"><i class="fas fa-spinner fa-spin fa-2x mb-3" style="color:#E85D85;"></i><p>Loading vendor details...</p></div>';

        fetch(`/admin/vendor/details/${userId}`)
            .then(response => response.json())
            .then(data => {
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
            confirmButtonColor: newStatus === 'active' ? '#28a745' : (actionText === 'reject' ? '#dc3545' : '#E85D85'),
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
</script>
</body>
</html>
