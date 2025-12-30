<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>All Orders | Vendor Dashboard</title>
    
    <!-- Meta for CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .badge:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }
        
        .status-dropdown {
            position: fixed;
            z-index: 9999;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            width: 320px;
            display: none;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.95);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
        }
        
        .status-dropdown.open {
            display: block;
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
        
        .status-dropdown-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }
        
        .status-dropdown-body {
            padding: 16px;
        }
        
        .status-option {
            padding: 12px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            border-radius: 8px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .status-option:hover {
            background-color: #f3f4f6;
        }
        
        .status-option.selected {
            background-color: #e0f2fe;
            border-left: 4px solid #0ea5e9;
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9998;
            display: none;
            backdrop-filter: blur(3px);
        }
        
        .modal-overlay.open {
            display: block;
        }
        
        body.modal-open {
            overflow: hidden;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .order-row {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .close-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            background: none;
            border: none;
            font-size: 20px;
            color: #6b7280;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .close-btn:hover {
            color: #374151;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar Component -->
        <x-vendor.sidebar 
            :profilePicture="$vendorBasicInfo->profile_picture ?? 'default_profile.webp'"
            :fullName="$vendorBasicInfo->full_name ?? auth()->user()->full_name"
            :profile_visibility="$vendorBasicInfo->profile_visibility ?? 1"
            page='Orders'
        />

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div class="w-1/3">
                    <div class="relative">
                        <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-gray-400"></i>
                        <input id="orderSearch" type="text" placeholder="Search orders..." class="border border-gray-300 px-10 py-2 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                    </div>
                </div>
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800">All Cart Orders</h1>
                    <p class="text-sm text-gray-600 mt-1">Orders from your products</p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-white p-6 rounded-lg card">
                    <p class="text-gray-600 text-sm mb-2">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg card">
                    <p class="text-gray-600 text-sm mb-2">Active Orders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $activeOrders }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg card">
                    <p class="text-gray-600 text-sm mb-2">Delivered Orders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $deliveredOrders }}</p>
                </div>
                <div class="bg-white p-6 rounded-lg card">
                    <p class="text-gray-600 text-sm mb-2">Paid Orders</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $paidOrders }}</p>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-lg shadow">
                <div class="overflow-x-auto min-w-[600px] max-w-full">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-sm text-gray-500">
                                <th class="px-6 py-3 font-medium">ID</th>
                                <th class="px-6 py-3 font-medium">Customer</th>
                                <th class="px-6 py-3 font-medium">Product</th>
                                <th class="px-6 py-3 font-medium">Quantity</th>
                                <th class="px-6 py-3 font-medium">Price</th>
                                <th class="px-6 py-3 font-medium">Total</th>
                                <th class="px-6 py-3 font-medium">Status</th>
                                <th class="px-6 py-3 font-medium">Order Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100" id="ordersTableBody">
                            @forelse($orders as $order)
                                @php
                                    $totalAmount = $order->price * $order->quantity;
                                    $status = $order->status ?? 'order placed';
                                    
                                    $statusColors = [
                                        'order placed' => 'bg-gray-500',
                                        'processing' => 'bg-blue-500',
                                        'shipping' => 'bg-purple-500',
                                        'delivered' => 'bg-green-500',
                                        'cancelled' => 'bg-red-500',
                                        'paid' => 'bg-green-500'
                                    ];
                                    
                                    $statusColor = $statusColors[$status] ?? 'bg-gray-500';
                                    
                                    $customer = DB::table('customer_profile')
                                        ->where('user_id', $order->user_id)
                                        ->first();
                                    
                                    $product = DB::table('vendor_products')
                                        ->where('id', $order->product_id)
                                        ->first();
                                @endphp
                                
                                <tr class="order-row hover:bg-gray-50" data-order-id="{{ $order->id }}">
                                    <td class="whitespace-nowrap px-6 py-4 font-medium text-black">ORD-{{ $order->id }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if($customer)
                                            {{ $customer->first_name }} {{ $customer->last_name }}
                                        @else
                                            User {{ $order->user_id }}
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if($product)
                                            <div class="flex items-center space-x-2">
                                                @php
                                                    $productImage = DB::table('vendor_product_images')
                                                        ->where('product_id', $order->product_id)
                                                        ->where('is_primary', 1)
                                                        ->first();
                                                @endphp
                                                @if($productImage)
                                                    <img src="{{ asset('storage/vendor/products/images/' . $productImage->image_path) }}" 
                                                         class="w-8 h-8 rounded object-cover" 
                                                         alt="{{ $product->name }}">
                                                @endif
                                                <span class="font-medium">{{ $product->name ?? 'Product #' . $order->product_id }}</span>
                                            </div>
                                        @else
                                            P-{{ $order->product_id }}
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">{{ $order->quantity }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-medium">${{ number_format($order->price, 2) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 font-bold text-green-600">${{ number_format($totalAmount, 2) }}</td>
                                    
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="relative inline-block">
                                            <span class="badge {{ $statusColor }} text-white status-badge cursor-pointer"
                                                  data-order-id="{{ $order->id }}"
                                                  data-current-status="{{ $status }}"
                                                  onclick="openStatusModal('{{ $order->id }}', '{{ $status }}')">
                                                {{ ucfirst($status) }}
                                                <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-2"></i>
                                        <p class="text-lg">No orders found</p>
                                        <p class="text-sm">When customers add your products to cart, they'll appear here.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Status Modal -->
    <div id="statusModalOverlay" class="modal-overlay"></div>
    
    <div id="statusModal" class="status-dropdown">
        <button class="close-btn" onclick="closeStatusModal()">
            <i class="fas fa-times"></i>
        </button>
        
        <div class="status-dropdown-header">
            <h3 class="text-lg font-bold text-gray-800">Update Order Status</h3>
            <p class="text-sm text-gray-600 mt-1">Order: <span id="modalOrderId" class="font-medium"></span></p>
        </div>
        
        <div class="status-dropdown-body">
            <div class="status-option" data-status="order placed" onclick="selectStatus('order placed')">
                <div class="flex items-center">
                    <span class="badge bg-gray-500 text-white mr-3">Order Placed</span>
                    <span>Order has been placed by customer</span>
                </div>
                <i class="fas fa-check text-green-500 hidden selected-icon"></i>
            </div>
            
            <div class="status-option" data-status="processing" onclick="selectStatus('processing')">
                <div class="flex items-center">
                    <span class="badge bg-blue-500 text-white mr-3">Processing</span>
                    <span>Preparing order for shipment</span>
                </div>
                <i class="fas fa-check text-green-500 hidden selected-icon"></i>
            </div>
            
            <div class="status-option" data-status="shipping" onclick="selectStatus('shipping')">
                <div class="flex items-center">
                    <span class="badge bg-purple-500 text-white mr-3">Shipping</span>
                    <span>Order is out for delivery</span>
                </div>
                <i class="fas fa-check text-green-500 hidden selected-icon"></i>
            </div>
            
            <div class="status-option" data-status="delivered" onclick="selectStatus('delivered')">
                <div class="flex items-center">
                    <span class="badge bg-green-500 text-white mr-3">Delivered</span>
                    <span>Order has been delivered</span>
                </div>
                <i class="fas fa-check text-green-500 hidden selected-icon"></i>
            </div>
            
            <div class="status-option" data-status="cancelled" onclick="selectStatus('cancelled')">
                <div class="flex items-center">
                    <span class="badge bg-red-500 text-white mr-3">Cancelled</span>
                    <span>Order has been cancelled</span>
                </div>
                <i class="fas fa-check text-green-500 hidden selected-icon"></i>
            </div>
            
            <div class="mt-6 pt-4 border-t border-gray-200">
                <button id="updateStatusBtn" onclick="updateOrderStatus()" 
                        class="w-full py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                        disabled>
                    Update Status
                </button>
                <button onclick="closeStatusModal()" 
                        class="w-full py-3 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition mt-3">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Current order being edited
            let currentOrderId = null;
            let currentOrderStatus = null;
            let selectedStatus = null;
            
            // Search functionality
            const searchInput = document.getElementById('orderSearch');
            const tableRows = document.querySelectorAll('#ordersTableBody tr');
            
            if (searchInput && tableRows.length > 0) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    tableRows.forEach(row => {
                        if (row.querySelector('td')) {
                            const rowText = row.textContent.toLowerCase();
                            row.style.display = rowText.includes(searchTerm) ? '' : 'none';
                        }
                    });
                });
            }
            
            // Close modal when clicking overlay
            document.getElementById('statusModalOverlay').addEventListener('click', closeStatusModal);
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeStatusModal();
                }
            });
        });
        
        // Open status modal
        function openStatusModal(orderId, currentStatus) {
            currentOrderId = orderId;
            currentOrderStatus = currentStatus;
            selectedStatus = null;
            
            // Set order ID in modal
            document.getElementById('modalOrderId').textContent = `ORD-${orderId}`;
            
            // Reset selection
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('selected');
                option.querySelector('.selected-icon').classList.add('hidden');
            });
            
            // Highlight current status
            const currentOption = document.querySelector(`.status-option[data-status="${currentStatus}"]`);
            if (currentOption) {
                currentOption.classList.add('selected');
                currentOption.querySelector('.selected-icon').classList.remove('hidden');
            }
            
            // Disable update button initially
            document.getElementById('updateStatusBtn').disabled = true;
            
            // Show modal
            document.getElementById('statusModalOverlay').classList.add('open');
            document.getElementById('statusModal').classList.add('open');
            document.body.classList.add('modal-open');
        }
        
        // Close status modal
        function closeStatusModal() {
            document.getElementById('statusModalOverlay').classList.remove('open');
            document.getElementById('statusModal').classList.remove('open');
            document.body.classList.remove('modal-open');
            
            currentOrderId = null;
            currentOrderStatus = null;
            selectedStatus = null;
        }
        
        // Select status option
        function selectStatus(status) {
            selectedStatus = status;
            
            // Update UI
            document.querySelectorAll('.status-option').forEach(option => {
                option.classList.remove('selected');
                option.querySelector('.selected-icon').classList.add('hidden');
            });
            
            const selectedOption = document.querySelector(`.status-option[data-status="${status}"]`);
            if (selectedOption) {
                selectedOption.classList.add('selected');
                selectedOption.querySelector('.selected-icon').classList.remove('hidden');
            }
            
            // Enable/disable update button
            const updateBtn = document.getElementById('updateStatusBtn');
            if (status === currentOrderStatus) {
                updateBtn.disabled = true;
                updateBtn.textContent = 'Update Status';
            } else {
                updateBtn.disabled = false;
                updateBtn.textContent = `Update to ${status.charAt(0).toUpperCase() + status.slice(1)}`;
            }
        }
        
        // Update order status
        function updateOrderStatus() {
            if (!currentOrderId || !selectedStatus || selectedStatus === currentOrderStatus) {
                return;
            }
            
            const badge = document.querySelector(`.status-badge[data-order-id="${currentOrderId}"]`);
            const updateBtn = document.getElementById('updateStatusBtn');
            
            // Show loading state
            const originalText = updateBtn.innerHTML;
            updateBtn.innerHTML = '<i class="fas fa-spinner spinner mr-2"></i> Updating...';
            updateBtn.disabled = true;
            
            // Update badge immediately for better UX
            if (badge) {
                const statusColors = {
                    'order placed': 'bg-gray-500',
                    'processing': 'bg-blue-500',
                    'shipping': 'bg-purple-500',
                    'delivered': 'bg-green-500',
                    'cancelled': 'bg-red-500',
                    'paid': 'bg-green-500'
                };
                
                badge.className = `badge ${statusColors[selectedStatus]} text-white status-badge cursor-pointer`;
                badge.innerHTML = `${selectedStatus.charAt(0).toUpperCase() + selectedStatus.slice(1)} <i class="fas fa-chevron-down ml-1 text-xs"></i>`;
                badge.setAttribute('data-current-status', selectedStatus);
            }
            
            // Send AJAX request
            fetch("{{ route('vendor.orders.update-status') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    order_id: currentOrderId,
                    status: selectedStatus
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showNotification('Status updated successfully!', 'success');
                    
                    // Close modal after delay
                    setTimeout(() => {
                        closeStatusModal();
                    }, 1000);
                    
                    // Update stats if needed (you can add logic here)
                    console.log(`Status updated to: ${selectedStatus}`);
                } else {
                    throw new Error(data.message || 'Update failed');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Revert badge if failed
                if (badge) {
                    const statusColors = {
                        'order placed': 'bg-gray-500',
                        'processing': 'bg-blue-500',
                        'shipping': 'bg-purple-500',
                        'delivered': 'bg-green-500',
                        'cancelled': 'bg-red-500',
                        'paid': 'bg-green-500'
                    };
                    
                    badge.className = `badge ${statusColors[currentOrderStatus]} text-white status-badge cursor-pointer`;
                    badge.innerHTML = `${currentOrderStatus.charAt(0).toUpperCase() + currentOrderStatus.slice(1)} <i class="fas fa-chevron-down ml-1 text-xs"></i>`;
                    badge.setAttribute('data-current-status', currentOrderStatus);
                }
                
                showNotification('Failed to update status. Please try again.', 'error');
                updateBtn.innerHTML = originalText;
                updateBtn.disabled = false;
            })
            .finally(() => {
                // Reset update button if modal is still open
                if (document.getElementById('statusModal').classList.contains('open')) {
                    updateBtn.innerHTML = 'Update Status';
                    updateBtn.disabled = (selectedStatus === currentOrderStatus);
                }
            });
        }
        
        // Show notification
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white font-medium z-[10000] transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentElement) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
</body>
</html>