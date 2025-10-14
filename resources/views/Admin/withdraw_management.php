<?php
    require_once '../mydatabase/conn.php';

    // Fetch withdrawal requests with vendor information
    $query = "SELECT wr.*, 
            u.full_name, u.email, u.phone,
            vbi.full_name as vendor_name, vbi.profile_picture,
            vb.total_balance as vendor_balance
            FROM withdrawal_requests wr
            JOIN users u ON wr.user_id = u.user_id
            JOIN vendor_basic_info vbi ON wr.user_id = vbi.user_id
            LEFT JOIN vendor_balance vb ON wr.user_id = vb.user_id
            ORDER BY wr.created_at DESC
            LIMIT 50";

    $result = $conn->query($query);
    $withdrawals = $result->fetch_all(MYSQLI_ASSOC);

    // Fetch counts for stats
    $statsQuery = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
                FROM withdrawal_requests";
    $statsResult = $conn->query($statsQuery);
    $stats = $statsResult->fetch_assoc();
?>

<!-- Then include the HTML part from above, replacing the static data with PHP loops -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Withdrawal Management</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .status-pending {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-processing {
            background-color: #dbeafe;
            color: #1d4ed8;
        }
        .status-completed {
            background-color: #dcfce7;
            color: #15803d;
        }
        .status-rejected {
            background-color: #fee2e2;
            color: #b91c1c;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans flex h-screen">
    <!-- Sidebar -->
    <aside class="w-[18vw] bg-gray-900 text-white flex flex-col">
        <div class="p-6 text-2xl font-bold border-b border-gray-800">E-COM</div>
        <nav class="flex-1 overflow-y-auto scrollbar-hide">
            <ul class="space-y-2 p-4">
                <li><a href="./admin_dashboard.html" class="block py-2 px-4 rounded hover:bg-gray-800"><i class="fa-solid fa-house"></i> Dashboard</a></li>
                <li><a href="./vendor_management.php" class="block py-2 px-4 rounded hover:bg-gray-800"><i class="fa-solid fa-users"></i> Vendors</a></li>
                <li><a href="./customer_management.php" class="block py-2 px-4 rounded hover:bg-gray-800"><i class="fa-solid fa-users"></i> Customers</a></li>
                <li><a href="./product_management.php" class="block py-2 px-4 rounded hover:bg-gray-800"><i class="fa-solid fa-box"></i> Products</a></li>
                <li><a href="./order_management.php" class="block py-2 px-4 rounded hover:bg-gray-800"><i class="fa-solid fa-truck"></i> Orders</a></li>
                <li><a href="./payments_management.php" class="block py-2 px-4 rounded hover:bg-gray-800"><i class="fa-solid fa-dollar-sign"></i> Customer Payments</a></li>
                <li><a href="./withdraw_management.php" class="block py-2 px-4 rounded bg-gray-800"><i class="fa-solid fa-money-bill-transfer"></i> Withdraw Requests</a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex justify-between items-center p-4 border-b">
                <h1 class="text-2xl font-semibold text-gray-800">Withdrawal Requests Management</h1>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                    <button class="p-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300">
                        <i class="fas fa-bell"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <img src="https://via.placeholder.com/40" alt="Admin" class="w-8 h-8 rounded-full">
                        <span class="font-medium">Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 p-4">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Total Requests</p>
                        <h3 class="text-2xl font-bold"><?php echo $stats['total'] ?? 0; ?></h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-file-invoice-dollar text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">All withdrawal requests</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Pending</p>
                        <h3 class="text-2xl font-bold"><?php echo $stats['pending'] ?? 0; ?></h3>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-clock text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Awaiting approval</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Processing</p>
                        <h3 class="text-2xl font-bold"><?php echo $stats['processing'] ?? 0; ?></h3>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-spinner text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Being processed</p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500">Completed</p>
                        <h3 class="text-2xl font-bold"><?php echo $stats['completed'] ?? 0; ?></h3>
                    </div>
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-check-circle text-xl"></i>
                    </div>
                </div>
                <p class="text-sm text-gray-500 mt-2">Successfully processed</p>
            </div>
        </div>

        <!-- Main Table -->
        <div class="p-4">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 class="text-lg font-semibold">Recent Withdrawal Requests</h2>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border rounded-md text-sm flex items-center">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <button class="px-3 py-1 border rounded-md text-sm flex items-center">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request ID</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Info</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($withdrawals as $withdrawal): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#WD-<?php echo $withdrawal['id']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="../vendor/<?php echo $withdrawal['profile_picture'] ?? 'https://via.placeholder.com/40'; ?>" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($withdrawal['vendor_name']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($withdrawal['email']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">PKR <?php echo number_format($withdrawal['amount'], 2); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php 
                                    $method = $withdrawal['withdrawal_method'];
                                    echo ucfirst(str_replace('_', ' ', $method)); 
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div><?php echo htmlspecialchars($withdrawal['account_number']); ?></div>
                                    <div class="text-xs text-gray-400"><?php echo htmlspecialchars($withdrawal['account_holder_name']); ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo date('Y-m-d', strtotime($withdrawal['created_at'])); ?></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php 
                                    $statusClass = 'status-' . $withdrawal['status'];
                                    $statusText = ucfirst($withdrawal['status']);
                                    ?>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="showWithdrawalDetails(<?php echo $withdrawal['id']; ?>)" class="text-blue-600 hover:text-blue-900 mr-3">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($withdrawal['status'] == 'pending'): ?>
                                        <button onclick="updateStatus(<?php echo $withdrawal['id']; ?>, 'processing')" class="text-green-600 hover:text-green-900 mr-3" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="updateStatus(<?php echo $withdrawal['id']; ?>, 'rejected')" class="text-red-600 hover:text-red-900" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php elseif ($withdrawal['status'] == 'processing'): ?>
                                        <button onclick="updateStatus(<?php echo $withdrawal['id']; ?>, 'completed')" class="text-green-600 hover:text-green-900 mr-3" title="Mark as Completed">
                                            <i class="fas fa-check-double"></i>
                                        </button>
                                    <?php elseif ($withdrawal['status'] == 'completed'): ?>
                                        <button class="text-gray-600 hover:text-gray-900" title="View Receipt">
                                            <i class="fas fa-receipt"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for withdrawal details -->
    <div id="withdrawalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900">Withdrawal Request Details</h3>
                <div id="modalContent" class="mt-4">
                    <!-- Content will be loaded via AJAX -->
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 flex justify-end">
                <button onclick="document.getElementById('withdrawalModal').classList.add('hidden')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        function showWithdrawalDetails(id) {
            // In a real implementation, you would fetch this data via AJAX
            fetch('get_withdrawal_details.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    const modalContent = document.getElementById('modalContent');
                    modalContent.innerHTML = `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Request ID</p>
                                    <p class="font-medium">#WD-${data.id}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Vendor</p>
                                    <p class="font-medium">${data.vendor_name}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Amount</p>
                                    <p class="font-medium">PKR ${data.amount}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Method</p>
                                    <p class="font-medium">${data.withdrawal_method.replace('_', ' ')}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Account Info</p>
                                    <p class="font-medium">${data.account_number}</p>
                                    <p class="text-xs text-gray-400">${data.account_holder_name}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Date</p>
                                    <p class="font-medium">${new Date(data.created_at).toLocaleDateString()}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="font-medium">${data.status.charAt(0).toUpperCase() + data.status.slice(1)}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Vendor Balance</p>
                                    <p class="font-medium">PKR ${data.vendor_balance ? data.vendor_balance : '0.00'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Contact</p>
                                    <p class="font-medium">${data.email}</p>
                                    <p class="text-xs text-gray-400">${data.phone}</p>
                                </div>
                            </div>
                        </div>
                    `;
                    document.getElementById('withdrawalModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to load withdrawal details');
                });
        }

        function updateStatus(id, status) {
            if (confirm(`Are you sure you want to ${status} this withdrawal request?`)) {
                fetch('update_withdrawal_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        requestId: id,
                        newStatus: status
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Status updated successfully');
                        location.reload(); // Refresh the page to see changes
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update status');
                });
            }
        }
    </script>
</body>
</html>