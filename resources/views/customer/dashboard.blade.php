@php
    // dd($basic_info);
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Dashboard | Multivendor Platform</title>
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./CDN tailwind.js"></script>
    <!-- font-awesome  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="./CDN tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Internal CSS */
        .order-status-processing {
            border-left: 4px solid #3b82f6;
        }

        .order-status-shipped {
            border-left: 4px solid #f59e0b;
        }

        .order-status-delivered {
            border-left: 4px solid #10b981;
        }

        .order-status-cancelled {
            border-left: 4px solid #ef4444;
        }

        .timeline-step.active {
            color: #10b981;
            border-color: #10b981;
        }

        .timeline-step.active .timeline-dot {
            background-color: #10b981;
        }

        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
        }

        /* Animation for notifications */
        @keyframes slideIn {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(0);
            }
        }

        .notification {
            animation: slideIn 0.3s ease-out;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <!-- Left side - Mobile menu and title -->
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                </div>

                <!-- Center - Search bar -->
                <div class="hidden flex-1 max-w-md mx-4"> <!-- md:flex -->
                    <div class="relative w-full">
                        <input type="text" placeholder="Search..."
                            class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                        <button class="absolute right-3 top-2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right side - Icons and user menu -->
                <div class="flex items-center space-x-4">
                    <!-- Notification dropdown -->
                    <div class="relative">
                        <button id="notification-button"
                            class="p-2 text-gray-500 rounded-full hover:bg-gray-100 relative focus:outline-none">
                            <i class="fas fa-bell"></i>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- Notification dropdown menu -->
                        <div id="notification-dropdown"
                            class="hidden absolute right-0 mt-2 w-72 bg-white rounded-md shadow-lg overflow-hidden z-10 border border-gray-200">
                            <div class="px-4 py-3 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-sm font-medium text-gray-700">Notifications</h3>
                            </div>
                            <div class="divide-y divide-gray-100">
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">New message</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">You received a new message from
                                        Sarah</div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">System update</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">Your system will be updated tonight
                                    </div>
                                </a>
                                <a href="#" class="block px-4 py-3 hover:bg-gray-50">
                                    <div class="text-sm font-medium text-gray-800">Payment received</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate">Your payment of $29.99 has been
                                        processed</div>
                                </a>
                            </div>
                            <div class="px-4 py-2 border-t border-gray-200 bg-gray-50 text-center">
                                <a href="./notifications.php"
                                    class="text-xs font-medium text-blue-600 hover:text-blue-800">See all
                                    notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    {{-- <div class="relative">
                        <button id="user-menu-button" class="flex items-center focus:outline-none">
                            <div class="mr-3 text-right hidden sm:block">
                                <span class="block text-sm font-medium text-gray-700"><?= $basic_info['first_name'] . " " . $basic_info['last_name']?></span>
                                <span class="block text-xs text-gray-500">Admin</span>
                            </div>
                            <div class="relative">
                                <img class="w-8 h-8 rounded-full" src="<?= $basic_info['profile_image']?>"
                                    alt="User">
                            </div>
                        </button>
                    </div> --}}
                </div>
            </header>

            <!-- Mobile Sidebar (hidden by default) -->
            <div id="mobile-sidebar" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50">
                <div class="fixed inset-y-0 left-0 w-64 bg-white">
                    <div class="flex items-center justify-between h-16 px-4 bg-blue-600">
                        <span class="text-white font-bold text-xl">cheezain</span>
                        <button id="close-sidebar" class="text-white focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="px-4 py-4">
                        <nav class="space-y-2">
                            <a href="./dashboard.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-900 rounded-lg sidebar-item hover:bg-gray-100 active"><i
                                    class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
                            <a href="./orders.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i
                                    class="fas fa-shopping-bag mr-3"></i>My Orders</a>
                            <a href="./wishlist.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i
                                    class="fas fa-heart mr-3"></i>Wishlist</a>
                            <a href="./addresses.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i
                                    class="fas fa-map-marker-alt mr-3"></i>Addresses</a>
                            <a href="./payments.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i
                                    class="fas fa-credit-card mr-3"></i>Payment Methods</a>
                            <a href="./support.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i
                                    class="fas fa-headset mr-3"></i>Support</a>
                            <a href="./profile.php"
                                class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i
                                    class="fas fa-user-cog mr-3"></i>Profile Settings</a>
                            <a href="#"
                                class="block px-4 py-2 text-sm font-medium text-red-600 rounded-lg sidebar-item hover:bg-red-50"><i
                                    class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Welcome Panel -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Welcome back!</h2>
                            <p class="text-gray-600">Here's what's happening with your orders today.</p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <!-- <button
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                                <i class="fas fa-plus mr-2"></i>New Order
                            </button> -->
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-100 rounded-full mr-4">
                                    <i class="fas fa-shopping-bag text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Orders</p>
                                    <p class="text-xl font-bold text-gray-800">
                                        {{ DB::table('orders')->where('user_id', $basic_info->user_id)->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-orange-100 rounded-full mr-4">
                                    <i class="fas fa-truck text-orange-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Active Orders</p>
                                    <p class="text-xl font-bold text-gray-800">
                                        {{ DB::table('orders')->where('user_id', $basic_info->user_id)->where('status', '!=', 'completed')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="flex items-center">
                                <div class="p-3 bg-green-100 rounded-full mr-4">
                                    <i class="fas fa-check-circle text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Completed</p>
                                    <p class="text-xl font-bold text-gray-800">
                                        {{ DB::table('orders')->where('user_id', $basic_info->user_id)->where('status', 'completed')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- My Orders Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-800">My Orders</h2>
                        <div class="relative">
                            <select id="orderFilter" class="appearance-none bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Orders</option>
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <i class="fas fa-chevron-down text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="id">
                                        Order ID <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="date">
                                        Date <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="items">
                                        Items <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="total">
                                        Total <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer sortable" data-column="status">
                                        Status <i class="fas fa-sort ml-1"></i>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="ordersTableBody">
                                <!-- Orders will be dynamically loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 flex justify-between items-center">
                        <p class="text-sm text-gray-600">Showing <span class="font-medium" id="showingStart">1</span> to <span class="font-medium" id="showingEnd">4</span> of <span class="font-medium" id="totalOrders">24</span> orders</p>
                        <div class="flex space-x-2" id="pagination">
                            <!-- Pagination will be dynamically loaded here -->
                        </div>
                    </div>
                </div>

                <!-- Quick Links Section -->
                <!-- <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <a href="#" class="bg-white rounded-lg shadow p-6 flex items-center hover:bg-gray-50">
                        <div class="p-3 bg-purple-100 rounded-full mr-4">
                            <i class="fas fa-heart text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Wishlist</h3>
                            <p class="text-sm text-gray-600">5 items saved</p>
                        </div>
                    </a>
                    <a href="#" class="bg-white rounded-lg shadow p-6 flex items-center hover:bg-gray-50">
                        <div class="p-3 bg-green-100 rounded-full mr-4">
                            <i class="fas fa-map-marker-alt text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Addresses</h3>
                            <p class="text-sm text-gray-600">2 saved addresses</p>
                        </div>
                    </a>
                    <a href="#" class="bg-white rounded-lg shadow p-6 flex items-center hover:bg-gray-50">
                        <div class="p-3 bg-blue-100 rounded-full mr-4">
                            <i class="fas fa-credit-card text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Payment Methods</h3>
                            <p class="text-sm text-gray-600">1 card saved</p>
                        </div>
                    </a>
                    <a href="#" class="bg-white rounded-lg shadow p-6 flex items-center hover:bg-gray-50">
                        <div class="p-3 bg-orange-100 rounded-full mr-4">
                            <i class="fas fa-headset text-orange-600"></i>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800">Support Center</h3>
                            <p class="text-sm text-gray-600">Need help?</p>
                        </div>
                    </a>
                </div> -->

                <!-- Recent Activity Section -->
                {{-- <div class="profile-card bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                                <button class="text-blue-600 hover:text-blue-800 focus:outline-none">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div class="flex">
                                    <div class="flex-shrink-0 mr-4">
                                        <div class="bg-<?= $color ?>-100 p-3 rounded-full">
                                            <i class="fas fa-<?= $icon ?> text-<?= $color ?>-600"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($activity['title']) ?></p>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars($activity['value']) ?> • <?= htmlspecialchars($activity['points']) ?></p>
                                        <p class="text-xs text-gray-400 mt-1"><?= $time_ago ?></p>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 focus:outline-none">
                                    View All Activity
                                </button>
                            </div>
                </div> --}}
            </main>
        </div>
    </div>



    <script src="../script/notification_dropdown.js"></script>
<!-- Internal JavaScript -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');

            document.querySelector('header button').addEventListener('click', function () {
                mobileSidebar.classList.remove('hidden');
            });

            closeSidebar.addEventListener('click', function () {
                mobileSidebar.classList.add('hidden');
            });
        });

        // Tracking modal functions
        function openTrackingModal(orderId) {
            document.getElementById('tracking-modal').classList.remove('hidden');
            document.getElementById('tracking-id').textContent = '#' + orderId;

            // Update timeline based on order status
            // This would be dynamic in a real application
            if (orderId === 'ORD-2023-002') {
                // Mark shipped as active for this order
                document.querySelectorAll('.timeline-step')[2].classList.add('active');
            } else if (orderId === 'ORD-2023-003') {
                // Mark all steps as active for delivered order
                document.querySelectorAll('.timeline-step').forEach(step => {
                    step.classList.add('active');
                });
            }
        }

        function closeTrackingModal() {
            document.getElementById('tracking-modal').classList.add('hidden');
            // Reset timeline when closing
            document.querySelectorAll('.timeline-step').forEach((step, index) => {
                if (index > 1) step.classList.remove('active');
            });
        }

        // Notification system (example)
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 ${type === 'info' ? 'border-blue-500' : 'border-green-500'}`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${type === 'info' ? 'fa-info-circle text-blue-500' : 'fa-check-circle text-green-500'}"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">${message}</p>
                    </div>
                    <div class="ml-4 pl-3 flex-shrink-0 flex">
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Example: Show welcome notification
        setTimeout(() => {
            showNotification('Welcome back to your dashboard! Check your recent orders.', 'info');
        }, 1000);
    </script>

    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>

    <!-- order data  -->
     <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ordersTableBody = document.getElementById("ordersTableBody");
            const filter = document.getElementById("orderFilter");

            function loadOrders(status = 'all') {
                fetch(`/customer/get_orders?status=${status}`)
                    .then(res => res.json())
                    .then(data => {
                        ordersTableBody.innerHTML = "";
                        if (!data.length) {
                            ordersTableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4 text-gray-500">No orders found.</td></tr>`;
                            return;
                        }

                        data.forEach(order => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#${order.order_id}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${order.order_date}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${order.quantity}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rs. ${order.total_amount}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex px-2 py-1 rounded text-xs font-semibold ${
                                        getStatusColor(order.fulfillment)
                                    }">${order.fulfillment}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 hover:underline cursor-pointer"><a href="./orders.php">View<a/></td>
                            `;
                            ordersTableBody.appendChild(row);
                        });

                        document.getElementById("showingStart").textContent = 1;
                        document.getElementById("showingEnd").textContent = data.length;
                        document.getElementById("totalOrders").textContent = data.length;
                    });
            }

            function getStatusColor(status) {
                switch (status) {
                    case 'processing': return 'bg-yellow-100 text-yellow-800';
                    case 'shipped': return 'bg-blue-100 text-blue-800';
                    case 'delivered': return 'bg-green-100 text-green-800';
                    case 'cancelled': return 'bg-red-100 text-red-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            }

            // Initial load
            loadOrders();

            // Filter handler
            filter.addEventListener("change", () => {
                loadOrders(filter.value);
            });
        });
    </script>

</body>

</html>