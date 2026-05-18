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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | Multivendor Platform</title>
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
    <style>
        /* Internal CSS */
        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
        }
        .profile-card {
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card {
            transition: all 0.3s ease;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        
        .badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        /* Animation for notifications */
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        .notification {
            animation: slideIn 0.3s ease-out;
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
                        <a href="./orders.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
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
                        <a href="./profile.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active">
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
                            <a href="./orders.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-shopping-bag mr-3"></i>My Orders</a>
                            <a href="./wishlist.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-heart mr-3"></i>Wishlist</a>
                            <a href="./addresses.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-map-marker-alt mr-3"></i>Addresses</a>
                            <a href="./payments.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-credit-card mr-3"></i>Payment Methods</a>
                            <a href="./support.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-headset mr-3"></i>Support</a>
                            <a href="./profile.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active"><i class="fas fa-user-cog mr-3"></i>Profile Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm font-medium text-red-600 rounded-lg sidebar-item hover:bg-red-50"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Profile Header -->
                <div class="profile-card bg-white rounded-lg shadow overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-32"></div>
                    <div class="px-6 pb-6 -mt-16">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                            <div class="flex items-end">
                                <div class="relative">
                                    <img class="w-32 h-32 rounded-full border-4 border-white" src="<?php echo $basic_info['profile_image']; ?>" alt="Profile">
                                    <span class="absolute bottom-0 right-0 bg-green-500 rounded-full p-1 border-2 border-white">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                </div>
                                <div class="ml-6 mb-4">
                                    <h2 class="text-2xl font-bold text-gray-900"><?php echo $basic_info['first_name'] . " " . $basic_info['last_name']; ?></h2>
                                    <p class="text-gray-600">Gold Member</p>
                                    <!-- <div class="flex items-center mt-2">
                                        <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                        <span class="text-sm text-gray-600">New York, USA</span>
                                    </div> -->
                                </div>
                            </div>
                            <div class="mt-4 md:mt-0">
                                <!-- <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none mr-2">
                                    <i class="fas fa-share-alt mr-2"></i>Share Profile
                                </button> -->
                                <a href="./edit-profile.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none">
                                    <i class="fas fa-user-edit mr-2"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            <?php
                                            $sql = "SELECT COUNT(orders.id) AS total_orders FROM users JOIN orders ON users.user_id = orders.user_id WHERE users.type = 'customer' GROUP BY users.user_id ORDER BY total_orders DESC";
                                            $result = $conn->query($sql);
                                            while ($row = $result->fetch_assoc()) 
                                                echo $row['total_orders'];
                            ?>
                        </div>
                        <p class="text-sm text-gray-600">Total Orders</p>
                    </div>
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            <?php
                                            $sql = "SELECT COUNT(orders.id) AS delivered_orders 
                                                    FROM users 
                                                    JOIN orders ON users.user_id = orders.user_id 
                                                    WHERE users.type = 'customer' 
                                                    AND orders.fulfillment = 'delivered'";
                                                    
                                            $result = $conn->query($sql);
                                            if ($row = $result->fetch_assoc()) 
                                                echo $row['delivered_orders'];
                            ?>
                        </div>
                        <p class="text-sm text-gray-600">Completed</p>
                    </div>
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600 mb-1">5</div>
                        <p class="text-sm text-gray-600">Wishlist Items</p>
                    </div>
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600 mb-1">
                            <?php
                                            $sql = "SELECT COUNT(orders.id) AS active_orders 
                                                    FROM users 
                                                    JOIN orders ON users.user_id = orders.user_id 
                                                    WHERE users.type = 'customer' 
                                                    AND orders.fulfillment NOT IN ('delivered', 'cancelled')";
                                                    
                                            $result = $conn->query($sql);
                                            if ($row = $result->fetch_assoc()) 
                                                echo $row['active_orders'];
                            ?>
                        </div>
                        <p class="text-sm text-gray-600">Active Orders</p>
                    </div>
                </div>
                
                <!-- Profile Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2">
                        <!-- About Section -->
                        <div class="profile-card bg-white rounded-lg shadow p-6 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">About</h3>
                                <button class="text-blue-600 hover:text-blue-800 focus:outline-none">
                                    <a href="./edit-profile.php"><i class="fas fa-edit"></i></a>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Bio</p>
                                    <p class="text-gray-700 mt-1"><?php echo $basic_info['bio']; ?></p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="text-gray-700 mt-1"><?php echo $basic_info['email']; ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="text-gray-700 mt-1"><?php echo $basic_info['phone']; ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Birthday</p>
                                        <p class="text-gray-700 mt-1"><?php echo $basic_info['birthday']; ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Member Since</p>
                                        <p class="text-gray-700 mt-1"><?php echo date('F j, Y'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        <div class="profile-card bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                                <button class="text-blue-600 hover:text-blue-800 focus:outline-none">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <?php
                                // Database connection
                                require_once '../mydatabase/conn.php';
                                
                                // Get current customer ID (you'll need to set this based on your auth system)
                                $customer_id = $_SESSION['user_id'] ?? 0;
                                
                                // Query to get recent activities
                                $query = "SELECT * FROM customer_recent_activity 
                                        WHERE user_id = ? 
                                        ORDER BY created_at DESC 
                                        LIMIT 4";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("i", $customer_id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                // Icons and colors for different activity types
                                $activity_icons = [
                                    'order_placed' => ['icon' => 'shopping-bag', 'color' => 'blue'],
                                    'order_delivered' => ['icon' => 'check-circle', 'color' => 'green'],
                                    'wishlist' => ['icon' => 'heart', 'color' => 'purple'],
                                    'review' => ['icon' => 'star', 'color' => 'yellow']
                                ];
                                
                                // Display activities
                                while ($activity = $result->fetch_assoc()) {
                                    $icon = $activity_icons[$activity['activity_type']]['icon'] ?? 'bell';
                                    $color = $activity_icons[$activity['activity_type']]['color'] ?? 'gray';
                                    
                                    // Calculate time ago
                                    $created_at = new DateTime($activity['created_at']);
                                    $now = new DateTime();
                                    $interval = $created_at->diff($now);
                                    
                                    if ($interval->y > 0) {
                                        $time_ago = $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval->m > 0) {
                                        $time_ago = $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval->d > 0) {
                                        $time_ago = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval->h > 0) {
                                        $time_ago = $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
                                    } elseif ($interval->i > 0) {
                                        $time_ago = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
                                    } else {
                                        $time_ago = 'Just now';
                                    }
                                ?>
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
                        </div>
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <!-- Membership Status -->
                        <div class="profile-card bg-white rounded-lg shadow p-6 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Membership Status</h3>
                                <span class="px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full badge">
                                    Gold Member
                                </span>
                            </div>
                            <div class="mb-4">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-yellow-400 h-2.5 rounded-full" style="width: 75%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>750/1000 points</span>
                                    <span>Gold Tier</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 mb-4">You're 250 points away from Platinum status. Enjoy these benefits:</p>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Free shipping on all orders</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Exclusive member discounts</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Early access to sales</span>
                                </li>
                                <li class="flex items-start">
                                    <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                    <span>Priority customer support</span>
                                </li>
                            </ul>
                            <button class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                                Learn More
                            </button>
                        </div>
                        
                        <!-- Recent Reviews -->
                        <div class="profile-card bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
                                <button class="text-blue-600 hover:text-blue-800 focus:outline-none">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex items-center mb-1">
                                        <div class="flex text-yellow-400 mr-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">Wireless Earbuds</span>
                                    </div>
                                    <p class="text-sm text-gray-600">"Great sound quality and battery life. Very comfortable for long listening sessions."</p>
                                    <p class="text-xs text-gray-400 mt-1">3 days ago</p>
                                </div>
                                <div>
                                    <div class="flex items-center mb-1">
                                        <div class="flex text-yellow-400 mr-2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">Smart Fitness Watch</span>
                                    </div>
                                    <p class="text-sm text-gray-600">"Good features but the battery could last longer. Accurate heart rate monitoring."</p>
                                    <p class="text-xs text-gray-400 mt-1">1 week ago</p>
                                </div>
                            </div>
                            <div class="mt-4 text-center">
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 focus:outline-none">
                                    View All Reviews
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
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
        
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 ${
                type === 'info' ? 'border-blue-500' : 'border-green-500'
            } z-50`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${
                            type === 'info' ? 'fa-info-circle text-blue-500' : 'fa-check-circle text-green-500'
                        }"></i>
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
    </script>

    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>
</html>