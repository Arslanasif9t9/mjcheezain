<!DOCTYPE html>
<html lang="en">

<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | MJCheezain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .brand-gradient { background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); }
        .stat-card {
            background: #ffffff;
            border-radius: 1rem;
            border: 1px solid #FDE7EE;
            box-shadow: 0 8px 24px rgba(232, 93, 133, 0.08);
            padding: 1.25rem;
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(232, 93, 133, 0.14); }
        .stat-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem; flex-shrink: 0;
        }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
    </style>
</head>

<body class="bg-[#FFF6F0] text-gray-800">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-admin.sidebar />

        <!-- Main Content -->
        <main class="flex-1 min-w-0 p-4 md:p-8 space-y-6">
            <!-- Page header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 pt-12 md:pt-0">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900"><i class="fa-solid fa-house text-[#E85D85] mr-2"></i>Dashboard</h1>
                    <p class="text-sm text-gray-500 mt-0.5">Overview of your marketplace performance</p>
                </div>
                <div class="text-sm text-gray-500 bg-white border border-pink-100 rounded-xl px-4 py-2 shadow-sm w-max">
                    <i class="fa-regular fa-calendar mr-1.5 text-[#E85D85]"></i>{{ now()->format('d M Y') }}
                </div>
            </div>

            <!-- Stat cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-pink-50 text-[#E85D85]"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Total Users</div>
                        <div class="text-xl font-bold text-gray-900">{{ $active_users }} <span class="text-xs font-medium text-gray-400">/ {{ $total_users }}</span></div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-orange-50 text-orange-500"><i class="fa-solid fa-store"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Vendors (active / total)</div>
                        <div class="text-xl font-bold text-gray-900">{{ $active_vendors }} <span class="text-xs font-medium text-gray-400">/ {{ $vendors }}</span></div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-blue-50 text-blue-500"><i class="fa-solid fa-truck"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Total Orders</div>
                        <div class="text-xl font-bold text-gray-900">{{ $orders }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon brand-gradient text-white"><i class="fa-solid fa-sack-dollar"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Total Sales (delivered)</div>
                        <div class="text-xl font-bold text-gray-900">Rs. {{ number_format($total_sales) }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-red-50 text-red-500"><i class="fa-solid fa-rotate-left"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Return Requests</div>
                        <div class="text-xl font-bold text-gray-900">{{ $total_returns }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-purple-50 text-purple-500"><i class="fa-solid fa-right-left"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Replacement Requests</div>
                        <div class="text-xl font-bold text-gray-900">{{ $total_replacements }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-amber-50 text-amber-500"><i class="fa-solid fa-wallet"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Pending Withdrawals</div>
                        <div class="text-xl font-bold text-gray-900">Rs. {{ number_format($pending_withdrawals) }}</div>
                    </div>
                </div>
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-teal-50 text-teal-500"><i class="fa-solid fa-box"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Pending Products</div>
                        <div class="text-xl font-bold text-gray-900">{{ $pending_products }}</div>
                    </div>
                </div>
            </div>

            <!-- Secondary row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="stat-card flex items-center gap-4">
                    <div class="stat-icon bg-gray-100 text-gray-500"><i class="fa-solid fa-xmark"></i></div>
                    <div>
                        <div class="text-xs text-gray-500 font-medium">Cancelled Orders</div>
                        <div class="text-xl font-bold text-gray-900">{{ $cancelled_orders }}</div>
                    </div>
                </div>
                <a href="/admin/withdraw-requests" class="stat-card flex items-center justify-between gap-4 no-underline hover:no-underline">
                    <div class="flex items-center gap-4">
                        <div class="stat-icon brand-gradient text-white"><i class="fa-solid fa-arrow-right"></i></div>
                        <div>
                            <div class="text-xs text-gray-500 font-medium">Quick Action</div>
                            <div class="text-base font-bold text-gray-900">Review withdraw requests</div>
                        </div>
                    </div>
                    <i class="fa-solid fa-chevron-right text-[#E85D85]"></i>
                </a>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-[2fr_3fr] gap-6">
                <div class="bg-white rounded-2xl border border-pink-100 p-6" style="box-shadow: 0 8px 24px rgba(232, 93, 133, 0.08);">
                    <div class="font-semibold mb-4 text-gray-900">Statistics — Last 6 Months Sales</div>
                    <div class="flex justify-center">
                        <canvas id="salesPieChart" class="max-w-full"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-pink-100 p-6" style="box-shadow: 0 8px 24px rgba(232, 93, 133, 0.08);">
                    <div class="font-semibold mb-4 text-gray-900">Monthly Orders — Last 6 Months</div>
                    <canvas id="ordersChart" height="120"></canvas>
                </div>
            </div>

            <footer class="text-center text-xs text-gray-400 py-4">&copy; {{ date('Y') }} MJCheezain — Admin Panel</footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('/admin/sales-data')
                .then(response => response.json())
                .then(chartData => {
                    // Pie chart
                    const pieCtx = document.getElementById('salesPieChart').getContext('2d');
                    new Chart(pieCtx, {
                        type: 'pie',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Orders',
                                data: chartData.data,
                                backgroundColor: [
                                    '#FF7DA0',
                                    '#FFC275',
                                    '#E85D85',
                                    '#FFD9A8',
                                    '#F9A8C0',
                                    '#FCEBDD'
                                ],
                                borderColor: '#ffffff',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: false,
                            plugins: {
                                legend: { position: 'top' }
                            }
                        }
                    });

                    // Bar chart
                    const barCtx = document.getElementById('ordersChart').getContext('2d');
                    new Chart(barCtx, {
                        type: 'bar',
                        data: {
                            labels: chartData.labels,
                            datasets: [{
                                label: 'Orders',
                                data: chartData.data,
                                backgroundColor: 'rgba(232, 93, 133, 0.65)',
                                borderRadius: 6,
                                barThickness: 40
                            }]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                legend: { display: true, position: 'top' }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { stepSize: 5 }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading chart data:', error);
                });
        });
    </script>
</body>

</html>
