<!DOCTYPE html>
<html lang="en">

<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | MJ Cheezain</title>
    <x-customer.theme />
</head>

<body>
    <div class="flex min-h-screen">
        <!-- Sidebar (desktop) -->
        <x-customer.sidebar :basic_info="$basic_info" />

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0">
            <x-customer.header title="Dashboard" subtitle="Here's what's happening with your orders" :basic_info="$basic_info" />

            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 pb-28 md:pb-8 page-enter">

                @php
                    $totalOrders = DB::table('orders')->where('user_id', $basic_info->user_id)->count();
                    // Order STATE lives in `fulfillment` (order placed|processing|shipping|delivered|cancelled)
                    $activeOrders = DB::table('orders')->where('user_id', $basic_info->user_id)->whereNotIn('fulfillment', ['delivered', 'cancelled'])->count();
                    $completedOrders = DB::table('orders')->where('user_id', $basic_info->user_id)->where('fulfillment', 'delivered')->count();
                    $wishlistCount = DB::table('favorites')->where('user_id', $basic_info->user_id)->count();
                @endphp

                <!-- Stat Cards -->
                <div class="grid grid-cols-3 gap-3 md:gap-5 mb-5 md:mb-8 -mt-2 md:mt-0 relative z-10">
                    <div class="app-card p-3.5 md:p-5 text-center md:text-left">
                        <div class="md:flex md:items-center">
                            <div class="w-10 h-10 md:w-12 md:h-12 mx-auto md:mx-0 rounded-2xl brand-gradient flex items-center justify-center brand-shadow md:mr-4">
                                <i class="fas fa-shopping-bag text-white text-sm md:text-base"></i>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <p class="text-xl md:text-2xl font-extrabold text-gray-800 leading-none">{{ $totalOrders }}</p>
                                <p class="text-[10px] md:text-xs text-gray-500 font-medium mt-1">Total Orders</p>
                            </div>
                        </div>
                    </div>
                    <div class="app-card p-3.5 md:p-5 text-center md:text-left">
                        <div class="md:flex md:items-center">
                            <div class="w-10 h-10 md:w-12 md:h-12 mx-auto md:mx-0 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-400 flex items-center justify-center shadow-lg shadow-orange-200 md:mr-4">
                                <i class="fas fa-truck text-white text-sm md:text-base"></i>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <p class="text-xl md:text-2xl font-extrabold text-gray-800 leading-none">{{ $activeOrders }}</p>
                                <p class="text-[10px] md:text-xs text-gray-500 font-medium mt-1">Active</p>
                            </div>
                        </div>
                    </div>
                    <div class="app-card p-3.5 md:p-5 text-center md:text-left">
                        <div class="md:flex md:items-center">
                            <div class="w-10 h-10 md:w-12 md:h-12 mx-auto md:mx-0 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-200 md:mr-4">
                                <i class="fas fa-check-circle text-white text-sm md:text-base"></i>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <p class="text-xl md:text-2xl font-extrabold text-gray-800 leading-none">{{ $completedOrders }}</p>
                                <p class="text-[10px] md:text-xs text-gray-500 font-medium mt-1">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mb-5 md:mb-8">
                    <h2 class="text-sm md:text-base font-bold text-gray-800 mb-3 px-1">Quick Actions</h2>
                    <div class="grid grid-cols-4 gap-3 md:gap-5">
                        <a href="/customer/orders" class="app-card p-3 md:p-5 flex flex-col items-center hover:-translate-y-1 transition-transform">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full brand-gradient-soft flex items-center justify-center">
                                <i class="fas fa-box-open text-brand text-sm md:text-lg"></i>
                            </div>
                            <span class="text-[10px] md:text-sm font-semibold text-gray-700 mt-2 text-center">Orders</span>
                        </a>
                        <a href="/customer/wishlist" class="app-card p-3 md:p-5 flex flex-col items-center hover:-translate-y-1 transition-transform">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full brand-gradient-soft flex items-center justify-center relative">
                                <i class="fas fa-heart text-brand text-sm md:text-lg"></i>
                                @if ($wishlistCount > 0)
                                    <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 brand-gradient text-white text-[9px] font-bold rounded-full flex items-center justify-center">{{ $wishlistCount }}</span>
                                @endif
                            </div>
                            <span class="text-[10px] md:text-sm font-semibold text-gray-700 mt-2 text-center">Wishlist</span>
                        </a>
                        <a href="/customer/addresses" class="app-card p-3 md:p-5 flex flex-col items-center hover:-translate-y-1 transition-transform">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full brand-gradient-soft flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-brand text-sm md:text-lg"></i>
                            </div>
                            <span class="text-[10px] md:text-sm font-semibold text-gray-700 mt-2 text-center">Addresses</span>
                        </a>
                        <a href="/customer/profile" class="app-card p-3 md:p-5 flex flex-col items-center hover:-translate-y-1 transition-transform">
                            <div class="w-10 h-10 md:w-12 md:h-12 rounded-full brand-gradient-soft flex items-center justify-center">
                                <i class="fas fa-user-cog text-brand text-sm md:text-lg"></i>
                            </div>
                            <span class="text-[10px] md:text-sm font-semibold text-gray-700 mt-2 text-center">Profile</span>
                        </a>
                    </div>
                </div>

                <!-- My Orders Section -->
                <div class="app-card p-4 md:p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-base md:text-xl font-bold text-gray-800">My Orders</h2>
                        <a href="/customer/orders" class="text-xs md:text-sm font-semibold text-brand hover:underline">View all <i class="fas fa-chevron-right text-[9px] ml-0.5"></i></a>
                    </div>

                    <!-- Desktop table -->
                    <div class="hidden md:block overflow-x-auto w-full">
                        <table class="min-w-full divide-y divide-pink-100">
                            <thead>
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Order ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Items</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-pink-50" id="ordersTableBody">
                                <!-- Orders will be dynamically loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile order cards -->
                    <div class="md:hidden space-y-3" id="ordersCardList">
                        <!-- Orders will be dynamically loaded here -->
                    </div>
                </div>

            </main>
            <x-customer.mobile-nav />
        </div>
    </div>

    <!-- order data -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const ordersTableBody = document.getElementById("ordersTableBody");
            const ordersCardList = document.getElementById("ordersCardList");

            function getStatusColor(status) {
                switch (status) {
                    case 'processing': return 'bg-amber-100 text-amber-700';
                    case 'shipped': return 'bg-purple-100 text-purple-700';
                    case 'delivered': return 'bg-emerald-100 text-emerald-700';
                    case 'cancelled': return 'bg-red-100 text-red-700';
                    default: return 'bg-pink-100 text-[#E85D85]';
                }
            }

            function loadOrders(status = 'all') {
                fetch(`/customer/get_orders?status=${status}`)
                    .then(res => res.json())
                    .then(data => {
                        ordersTableBody.innerHTML = "";
                        ordersCardList.innerHTML = "";

                        if (!data.length) {
                            const emptyHtml = `
                                <div class="text-center py-10">
                                    <div class="w-16 h-16 mx-auto rounded-full brand-gradient-soft flex items-center justify-center mb-3">
                                        <i class="fas fa-box-open text-2xl" style="color:#E85D85"></i>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium">No orders yet</p>
                                    <a href="/" class="inline-block mt-3 px-5 py-2 rounded-full text-white text-sm font-semibold brand-gradient brand-shadow">Start Shopping</a>
                                </div>`;
                            ordersTableBody.innerHTML = `<tr><td colspan="6">${emptyHtml}</td></tr>`;
                            ordersCardList.innerHTML = emptyHtml;
                            return;
                        }

                        data.slice(0, 6).forEach(order => {
                            const statusLabel = order.fulfillment ? order.fulfillment : 'Order Placed';
                            const statusCls = getStatusColor(order.fulfillment);

                            // Desktop row
                            const row = document.createElement("tr");
                            row.className = "hover:bg-pink-50/40 transition-colors";
                            row.innerHTML = `
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">ORD-${order.order_id}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">${order.order_date}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">${order.quantity}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">Rs. ${order.total_amount}</td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold ${statusCls}">${statusLabel}</span>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm"><a href="/customer/orders" class="font-semibold hover:underline" style="color:#E85D85">View</a></td>
                            `;
                            ordersTableBody.appendChild(row);

                            // Mobile card
                            const card = document.createElement("a");
                            card.href = "/customer/orders";
                            card.className = "block rounded-2xl border border-pink-100 bg-white p-4 active:scale-[0.98] transition-transform";
                            card.innerHTML = `
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center min-w-0">
                                        <div class="w-10 h-10 rounded-xl brand-gradient-soft flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="fas fa-box text-sm" style="color:#E85D85"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-gray-800 truncate">ORD-${order.order_id}</p>
                                            <p class="text-[11px] text-gray-400">${order.order_date} · ${order.quantity} item(s)</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-chevron-right text-gray-300 text-xs ml-2"></i>
                                </div>
                                <div class="flex items-center justify-between mt-3 pt-3 border-t border-pink-50">
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-semibold ${statusCls}">${statusLabel}</span>
                                    <span class="text-sm font-extrabold text-gray-800">Rs. ${order.total_amount}</span>
                                </div>
                            `;
                            ordersCardList.appendChild(card);
                        });
                    });
            }

            loadOrders();
        });
    </script>
</body>

</html>
