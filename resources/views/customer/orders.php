<?php @include "./redirect_vendor.php"; ?>
<?php 
    // session_start();
    $user_id = $_SESSION['user_id']; 

    @include "../mydatabase/conn.php";
    $sql = "SELECT * FROM `customer_profile` WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $basic_info = $result->fetch_assoc();
?>

<?php
    $sql = "SELECT * FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $orders = $stmt->get_result();
    // $orders = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Multivendor Platform</title>
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
        .order-card {
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

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

        .order-status-returned {
            border-left: 4px solid #8b5cf6;
        }

        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }

        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
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

        /* Animation for order cards */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-card {
            animation: fadeIn 0.3s ease-out forwards;
        }



        .invoice-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            overflow-y: auto;
        }
        .invoice-content {
            background-color: white;
            margin: 2rem auto;
            width: 80%;
            max-width: 800px;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-gray-50">
        <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
                    <span class="text-white font-bold text-xl">cheezain</span>
                </div>                
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                    <div class="flex items-center px-4 py-3 mb-4 bg-gray-100 rounded-lg">
                        <img class="w-10 h-10 rounded-full" src="<?php echo $basic_info['profile_image'] ?>" alt="User">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900"><?php echo $basic_info['first_name'] . " " . $basic_info['last_name']; ?></p>
                            <p class="text-xs text-gray-500">Gold Member</p>
                        </div>
                    </div>
                    
                    <nav class="flex-1 space-y-2">
                        <a href="./dashboard.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-900 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Dashboard
                        </a>
                        <a href="./orders.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active">
                            <i class="fas fa-shopping-bag mr-3"></i>
                            My Orders
                        </a>
                        <a href="./wishlist.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-heart mr-3"></i>
                            Wishlist
                        </a>
                        <a href="./addresses.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-map-marker-alt mr-3"></i>
                            Addresses
                        </a>
                        <!-- <a href="./payments.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-credit-card mr-3"></i>
                            Payment Methods
                        </a>
                        <a href="./support.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-headset mr-3"></i>
                            Support
                        </a> -->
                        <a href="./profile.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-user-cog mr-3"></i>
                            Profile Settings
                        </a>
                    </nav>
                    
                    <div class="mt-auto mb-4">
                        <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50" id="logoutBtn">
                            <i class="fas fa-sign-out-alt mr-3"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
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
                <div class="hidden md:flex flex-1 max-w-md mx-4">
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
                    <div class="relative">
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
                    </div>
                </div>
                <script src="../script/notification_dropdown.js"></script>
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
                            <a href="./dashboard.php" class="block px-4 py-2 text-sm font-medium text-gray-900 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-tachometer-alt mr-3"></i>Dashboard</a>
                            <a href="./orders.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item active"><i class="fas fa-shopping-bag mr-3"></i>My Orders</a>
                            <a href="./wishlist.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-heart mr-3"></i>Wishlist</a>
                            <a href="./addresses.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-map-marker-alt mr-3"></i>Addresses</a>
                            <a href="./payments.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-credit-card mr-3"></i>Payment Methods</a>
                            <a href="./support.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-headset mr-3"></i>Support</a>
                            <a href="./profile.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-user-cog mr-3"></i>Profile Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm font-medium text-red-600 rounded-lg sidebar-item hover:bg-red-50"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Order Filter Tabs -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="nav-tabs flex -mb-px overflow-x-auto">
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm tab-active">
                                All Orders (8)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Processing (2)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Shipped (1)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Delivered (4)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Cancelled (1)
                            </a>
                            <a href="#" class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                Returns (0)
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Search and Filter -->
                <div class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <div class="relative mb-4 md:mb-0 md:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Search orders...">
                        </div>
                        
                        <div class="flex space-x-3">
                            <div class="relative">
                                <select class="appearance-none bg-gray-100 border border-gray-300 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option>Last 30 days</option>
                                    <option>Last 3 months</option>
                                    <option>Last 6 months</option>
                                    <option>Last year</option>
                                    <option>All time</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                            
                            <!-- <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none flex items-center">
                                <i class="fas fa-filter mr-2"></i>
                                Filters
                            </button> -->
                        </div>
                    </div>
                </div>
                
                <!-- Orders List -->
                <div class="space-y-4">
                    <?php if ($orders && $orders->num_rows > 0): ?>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <!-- Order Card 1 -->
                        <div class="order-card bg-white rounded-lg shadow order-status-processing">
                        <div class="p-5">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                <div class="mb-4 md:mb-0">
                                    <div class="flex items-center">
                                        <?php
                                            $shipping_color = [
                                                'pending' => 'yellow',
                                                'unfulfillment' => 'red',
                                                'processing' => "blue",
                                                'shipped' => 'yellow',
                                                'delivered' => 'green',
                                                'cancelled' => 'red'
                                            ];
                                        ?>
                                        <h3 class="text-lg font-medium text-gray-900">Order #ORD-<?= $order['id'] ?></h3>
                                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-<?= $shipping_color[$order['fulfillment']]?>-100 text-<?= $shipping_color[$order['fulfillment']]?>-800"><?= $order['fulfillment'] ?></span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1"><?= $order['order_date']?></p>
                                </div>
                                <div class="flex items-center">
                                    <p class="text-lg font-semibold text-gray-900"><?= $order['total_amount']?></p>
                                    <button class="ml-4 p-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </div>
                            </div>
    
                            <?php
                                $sql = "SELECT vp.*, vpi.image_path
                                        FROM vendor_products vp
                                        JOIN vendor_product_images vpi 
                                        ON vpi.product_id = vp.id
                                        WHERE vp.id = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $order['product_id']);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $product = $result->fetch_assoc();

                                $vendor_id = $product['user_id'];
                            ?>
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="flex flex-col md:flex-row md:items-center">
                                    <div class="flex-1 mb-4 md:mb-0">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Items</h4>
                                        <div class="flex items-center space-x-3">
                                            <img src="../vendor/<?= $product['image_path']?>" alt="Product" class="w-16 h-16 rounded-lg object-cover">
                                            <p class="w-64 h-12 overflow-hidden line-clamp-2"><?= $product['name'];?></p>
                                            <!-- <span class="text-sm text-gray-500">+2 more</span> -->
                                        </div>
                                    </div>
                                    <?php
                                        $sql = "SELECT o.*, ca.*
                                                FROM orders o
                                                JOIN customer_addresses ca
                                                ON o.shipping_address_id = ca.id
                                                WHERE o.id = ?";
                                        $stmt = $conn->prepare($sql);
                                        $stmt->bind_param("i", $order['id']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $address = $result->fetch_assoc();
                                    ?>
                                    <div class="md:ml-6 md:pl-6 md:border-l md:border-gray-200">
                                        <h4 class="text-sm font-medium text-gray-900 mb-2">Delivery Address</h4>
                                        <p class="text-sm text-gray-600"><?= $address['address_line1']?></p>
                                    </div>
                                    <div class="md:ml-6 md:pl-6 md:border-l md:border-gray-200 mt-4 md:mt-0">
                                        <div class="flex space-x-3">
                                            <button id="trackBtn" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none flex items-center">
                                                <i class="fas fa-truck mr-2"></i>Track
                                            </button>
                                            <a href="?order_id=<?php echo $order['id']; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none flex items-center">
                                                <i class="fas fa-file-invoice mr-2"></i>Invoice
                                            </a>
                                            <!-- <a class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Pagination -->
                <!-- <div class="mt-6 flex justify-between items-center">
                    <p class="text-sm text-gray-600">Showing <span class="font-medium">1</span> to <span class="font-medium">4</span> of <span class="font-medium">8</span> orders</p>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">1</button>
                        <button class="px-3 py-1 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300">2</button>
                        <button class="px-3 py-1 bg-gray-200 rounded-lg text-gray-700 hover:bg-gray-300">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div> -->
            </main>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const tabs = document.querySelectorAll('nav.nav-tabs a');
                    const searchInput = document.querySelector('input[placeholder="Search orders..."]');
                    const orderCards = document.querySelectorAll('.order-card');

                    // 1. Tab Filtering
                    tabs.forEach(tab => {
                        tab.addEventListener('click', e => {
                            e.preventDefault();
                            tabs.forEach(t => t.classList.remove('tab-active', 'border-blue-600', 'text-blue-600'));
                            tab.classList.add('tab-active', 'border-blue-600', 'text-blue-600');

                            const selected = tab.textContent.toLowerCase().split(' ')[32];
                            orderCards.forEach(card => {
                                if (selected === 'all') {
                                    card.style.display = '';
                                } else {
                                    card.style.display = card.classList.contains(`order-status-${selected}`) ? '' : 'none';
                                }
                            });
                        });
                    });

                    // 2. Search Orders
                    searchInput.addEventListener('input', function () {
                        const keyword = this.value.toLowerCase();
                        orderCards.forEach(card => {
                            const text = card.textContent.toLowerCase();
                            card.style.display = text.includes(keyword) ? '' : 'none';
                        });
                    });

                    // 3. Arrow Down Toggle for Tracking
                    document.querySelectorAll('#trackBtn').forEach(button => {
                        button.addEventListener('click', function () {
                            const orderCard = this.closest('.order-card');
                            const orderId = orderCard.querySelector('h3').textContent.trim();
                            const existing = orderCard.querySelector('.tracking-bar');
                            if (existing) {
                                existing.remove();
                                // this.classList.toggle('rotate-180');
                                return;
                            }

                            // this.classList.toggle('rotate-180');

                            const status = orderCard.querySelector('span').textContent.trim().toLowerCase();
                            const steps = ['pending', 'processing', 'shipped', 'delivered'];
                            const activeIndex = steps.indexOf(status);

                            const progressHTML = `
                                <div class="tracking-bar my-6 py-4 px-16 border-t border-gray-200">
                                    <div class="w-full flex justify-between items-center text-sm font-medium text-gray-500">
                                        ${steps.map((step, i) => `
                                            <div class="flex-1 flex flex-col items-center">
                                                <div class="relative mb-2 w-full flex items-center justify-center">
                                                    <div class="absolute w-full h-1 ${i <= activeIndex ? 'bg-green-500' : 'bg-gray-200'} rounded"></div>
                                                    <div class="z-10 w-4 h-4 bg-white border-2 ${i <= activeIndex ? 'border-green-500' : 'border-gray-300'} rounded-full"></div>
                                                </div>
                                                <span class="${i <= activeIndex ? 'text-green-600' : ''} capitalize">${step}</span>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            `;

                            orderCard.insertAdjacentHTML('beforeend', progressHTML);
                        });
                    });

                    // 4. Sort Orders (Optional Enhancement)
                    document.querySelectorAll('.sortable').forEach(header => {
                        header.addEventListener('click', () => {
                            const column = header.dataset.column;
                            const rows = Array.from(orderCards);
                            const isAsc = header.classList.toggle('asc');

                            rows.sort((a, b) => {
                                const valA = a.querySelector(`[data-col="${column}"]`)?.textContent.trim().toLowerCase() || '';
                                const valB = b.querySelector(`[data-col="${column}"]`)?.textContent.trim().toLowerCase() || '';
                                return isAsc ? valA.localeCompare(valB) : valB.localeCompare(valA);
                            });

                            const parent = rows[0].parentNode;
                            rows.forEach(row => parent.appendChild(row));
                        });
                    });

                });
            </script>
        </div>
    </div>

    <!-- logout modal  -->
    <div id="logoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-md w-full mx-4">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Are you sure you want to logout?</h2>
            <p class="text-gray-600 mb-6">You'll need to sign in again to access your account.</p>
            <div class="flex justify-end space-x-3">
                <button id="cancelBtn"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button id="confirmLogoutBtn" type="button"
                    class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 transition">
                    <a href="../logout.php">Yes, Logout</a>
                </button>
            </div>
        </div>
    </div>
    <script src="../script/logout.js"></script>


<?php
    // Verify the order belongs to this vendor
    // $vendor_id = $orders->fetch_assoc();
    // $vendor_id = $vendor_id['vendor_id'];
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
    $verify_query = "SELECT id FROM orders WHERE id = ? AND vendor_id = ?";
    $stmt = $conn->prepare($verify_query);
    $stmt->bind_param("ii", $order_id, $vendor_id);
    $stmt->execute();
    $verify_result = $stmt->get_result();

    if ($verify_result->num_rows === 0) {
        die("Order not found or unauthorized access");
    }

    // Fetch order details
    $order_invoice = "SELECT o.*, 
                u.full_name AS customer_name, 
                u.email AS customer_email,
                u.phone AS customer_phone,
                cp.profile_image AS customer_image,
                vp.name AS product_name,
                vp.description AS product_description,
                vp.selling_price AS unit_price,
                vp.delivery_charges,
                vpi.image_path AS product_image,
                ca.address_line1, ca.address_line2, ca.city, ca.state, ca.zip_code, ca.country,
                ca.full_name AS shipping_name, ca.phone AS shipping_phone,
                p.transaction_ref AS payment_id
                FROM orders o
                JOIN users u ON o.user_id = u.user_id
                LEFT JOIN customer_profile cp ON o.user_id = cp.user_id
                JOIN vendor_products vp ON o.product_id = vp.id
                LEFT JOIN vendor_product_images vpi ON vp.id = vpi.product_id AND vpi.is_primary = 1
                JOIN customer_addresses ca ON o.shipping_address_id = ca.id
                JOIN payments p ON o.id = p.order_id
                WHERE o.id = ?";

    $stmt = $conn->prepare($order_invoice);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $order = $stmt->get_result()->fetch_assoc();
?>
  <!-- Invoice Modal -->
<div id="invoiceModal" class="invoice-modal">
    <div class="invoice-content">
        <div class="p-6">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Order Invoice</h2>
                    <p class="text-gray-600">#<?= $order_id ?></p>
                </div>
                <div class="text-right">
                    <p class="text-gray-600">Order Date: <?= date('M d, Y', strtotime($order['order_date'])) ?></p>
                    <p class="text-gray-600">Last Updated: <?= date('M d, Y', strtotime($order['updated_at'])) ?></p>
                </div>
            </div>

            <!-- Customer and Shipping Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Customer Information</h3>
                    <div class="flex items-center space-x-3 mb-2">
                        <img src="../customer/<?= $order['customer_image'] ?: 'https://i.pravatar.cc/40?img=1' ?>" 
                             class="w-10 h-10 rounded-full">
                        <span class="font-medium"><?= htmlspecialchars($order['customer_name']) ?></span>
                    </div>
                    <p class="text-gray-600"><?= htmlspecialchars($order['customer_email']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['customer_phone']) ?></p>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="font-semibold text-lg mb-2">Shipping Address</h3>
                    <p class="text-gray-600"><?= htmlspecialchars($order['shipping_name']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['address_line1']) ?></p>
                    <?php if (!empty($order['address_line2'])): ?>
                        <p class="text-gray-600"><?= htmlspecialchars($order['address_line2']) ?></p>
                    <?php endif; ?>
                    <p class="text-gray-600"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?></p>
                    <p class="text-gray-600"><?= htmlspecialchars($order['zip_code']) ?>, <?= htmlspecialchars($order['country']) ?></p>
                    <p class="text-gray-600">Phone: <?= htmlspecialchars($order['shipping_phone']) ?></p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h3 class="font-semibold text-lg mb-4">Order Items</h3>
                <div class="bg-gray-50 rounded-lg overflow-hidden">
                    <div class="p-4 border-b flex items-center">
                        <div class="w-16 h-16 bg-gray-200 rounded-md overflow-hidden mr-4">
                            <?php if (!empty($order['product_image'])): ?>
                                <img src="../vendor/<?= $order['product_image'] ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <i class="fas fa-image fa-lg"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium"><?= htmlspecialchars($order['product_name']) ?></h4>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars(substr($order['product_description'], 0, 100)) ?>...</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">Quantity: <?= $order['quantity'] ?></p>
                            <p class="font-medium">Rs. <?= number_format($order['unit_price'], 2) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-semibold text-lg mb-4">Order Summary</h3>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>Rs. <?= number_format($order['unit_price'] * $order['quantity'], 2) ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Delivery Charges:</span>
                        <span>Rs. <?= number_format($order['delivery_charges'], 2) ?></span>
                    </div>
                    <div class="flex justify-between border-t pt-2 font-bold">
                        <span>Total Amount:</span>
                        <span>Rs. <?= number_format($order['total_amount'], 2) ?></span>
                    </div>
                    <div class="flex justify-between border-t pt-2 font-bold">
                        <span>Payment ID:</span>
                        <span><?= $order['payment_id']?></span>
                    </div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="font-semibold text-lg mb-2">Fulfillment Status</h3>
                    <form id="fulfillmentForm">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <div class="flex items-center space-x-2">
                            <select name="fulfillment" class="border rounded px-3 py-2 flex-1" disabled>
                                <option value="pending" <?= $order['fulfillment'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="unfulfillment" <?= $order['fulfillment'] == 'unfulfillment' ? 'selected' : '' ?>>Unfulfilled</option>
                                <option value="processing" <?= $order['fulfillment'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['fulfillment'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['fulfillment'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['fulfillment'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </div>
                    </form>
                </div>
                <!-- <div>
                    <h3 class="font-semibold text-lg mb-2">Get Payment</h3>
                    <form id="paymentFo" action="update_payment.php" method="POST" class="flex items-center gap-2">
                        <input type="hidden" name="order_id" value="<?= $order_id ?>">
                        <div class="flex items-center space-x-2">
                            <span class="badge <?= $order['status'] == 'paid' ? 'bg-green-500' : 'bg-yellow-500' ?> text-white">
                                <?= ucfirst($order['status']) ?>
                            </span>
                            <input type="text" name="payment_id" class="border rounded px-3 py-2 flex-1 w-full" placeholder="Enter Payment ID">
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                Paid
                            </button>
                        </div>
                    </form>
                </div> -->
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <!-- <button onclick="updateFulfillment()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Fulfillment
                </button> -->
                <button onclick="hideInvoice()" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Show invoice modal
    function showInvoice(orderId) {
        $('#invoiceModal').fadeIn();
    }
    setTimeout(() => {
        showInvoice(<?php echo $order_id; ?>);
    }, 500);

    // Hide invoice modal
    function hideInvoice() {
        $('#invoiceModal').fadeOut();
    }

    // Update fulfillment status
    function updateFulfillment() {
        $.ajax({
            url: 'update_fulfillment.php',
            method: 'POST',
            data: $('#fulfillmentForm').serialize(),
            success: function(response) {
                // alert('Fulfillment status updated successfully');
                location.reload(); // Refresh to see changes
            },
            error: function() {
                alert('Failed to update fulfillment status');
            }
        });
    }

    // Update payment status
    function updatePayment() {
        $.ajax({
            url: 'update_payment.php',
            method: 'POST',
            data: $('#paymentForm').serialize(),
            success: function(response) {
                // alert('Fulfillment status updated successfully');
                location.reload(); // Refresh to see changes
            },
            error: function() {
                alert('Failed to update payment status');
            }
        });
    }

    // Close modal when clicking outside
    // $(document).mouseup(function(e) {
    //     var container = $(".invoice-content");
    //     if (!container.is(e.target) && container.has(e.target).length === 0) {
    //         hideInvoice();
    //     }
    // });
</script>

    
    <!-- Internal JavaScript -->
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileSidebar = document.getElementById('mobile-sidebar');
            const closeSidebar = document.getElementById('close-sidebar');
            
            document.querySelector('header button').addEventListener('click', function() {
                mobileSidebar.classList.remove('hidden');
            });
            
            closeSidebar.addEventListener('click', function() {
                mobileSidebar.classList.add('hidden');
            });
        });
        
        // Tab functionality
        // document.querySelectorAll('nav a').forEach(tab => {
        //     tab.addEventListener('click', function(e) {
        //         e.preventDefault();
        //         document.querySelectorAll('nav a').forEach(t => t.classList.remove('tab-active'));
        //         this.classList.add('tab-active');
        //     });
        // });
        
        // Tracking modal functions
        function openTrackingModal(orderId) {
            document.getElementById('tracking-modal').classList.remove('hidden');
            document.getElementById('tracking-id').textContent = '#' + orderId;
            
            // Update timeline based on order status
            // This would be dynamic in a real application
            if (orderId === 'ORD-2023-004') {
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
        
        // Order card expand/collapse functionality
        document.querySelectorAll('.order-card button:has(.fa-chevron-down)').forEach(button => {
            button.addEventListener('click', function() {
                const card = this.closest('.order-card');
                const details = card.querySelector('.border-t');
                
                if (details.classList.contains('hidden')) {
                    details.classList.remove('hidden');
                    this.innerHTML = '<i class="fas fa-chevron-up"></i>';
                } else {
                    details.classList.add('hidden');
                    this.innerHTML = '<i class="fas fa-chevron-down"></i>';
                }
            });
        });
    </script>

    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>

</html>