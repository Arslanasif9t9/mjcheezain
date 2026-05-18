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
require_once 'address_functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $temp = isset($_POST['add_address']);
    if (isset($_POST['add_address'])) {
        // Add new address
        $data = [
            'address_type' => $_POST['address_type'],
            'full_name' => $_POST['full-name'],
            'phone' => $_POST['phone'],
            'address_line1' => $_POST['address-line1'],
            'address_line2' => $_POST['address-line2'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zip_code' => $_POST['zip-code'],
            'country' => $_POST['country'],
            'is_default' => isset($_POST['default-address'])
        ];
        
        if (addCustomerAddress($user_id, $data)) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Address added successfully'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to add address'];
        }
    } 
    elseif (isset($_POST['update_address'])) {
        // Update existing address
        $address_id = $_POST['address_id'];
        $data = [
            'address_type' => $_POST['address_type'],
            'full_name' => $_POST['full-name'],
            'phone' => $_POST['phone'],
            'address_line1' => $_POST['address-line1'],
            'address_line2' => $_POST['address-line2'],
            'city' => $_POST['city'],
            'state' => $_POST['state'],
            'zip_code' => $_POST['zip-code'],
            'country' => $_POST['country'],
            'is_default' => isset($_POST['default-address'])
        ];
        
        if (updateCustomerAddress($address_id, $user_id, $data)) {
            $_SESSION['notification'] = ['type' => 'success', 'message' => 'Address updated successfully'];
        } else {
            $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to update address'];
        }
    }
    
    header('Location: addresses.php');
    exit();
}

// Handle GET requests (delete, set default)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete':
            if (isset($_GET['id'])) {
                if (deleteCustomerAddress($_GET['id'], $user_id)) {
                    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Address deleted successfully'];
                } else {
                    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to delete address'];
                }
            }
            break;
        case 'set_default':
            if (isset($_GET['id'])) {
                if (setDefaultAddress($_GET['id'], $user_id)) {
                    $_SESSION['notification'] = ['type' => 'success', 'message' => 'Default address updated'];
                } else {
                    $_SESSION['notification'] = ['type' => 'error', 'message' => 'Failed to update default address'];
                }
            }
            break;
    }
    
    header('Location: addresses.php');
    exit();
}

// Get all addresses for the customer
$addresses = getCustomerAddresses($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses | Multivendor Platform</title>
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
        .sidebar-item.active {
            background-color: #f3f4f6;
            border-right: 3px solid #3b82f6;
            color: #3b82f6;
        }
        .address-card {
            transition: all 0.3s ease;
        }
        .address-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        
        .default-address {
            border-left: 4px solid #10b981;
        }
        
        .address-form {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
            display: none;
        }
        
        .address-form.open {
            display: block;
            max-height: 1000px;
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
                        <a href="./addresses.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active">
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
                            <a href="./orders.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-shopping-bag mr-3"></i>My Orders</a>
                            <a href="./wishlist.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-heart mr-3"></i>Wishlist</a>
                            <a href="./addresses.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active"><i class="fas fa-map-marker-alt mr-3"></i>Addresses</a>
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
                <!-- Address Management Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Saved Addresses</h2>
                        <p class="text-gray-600">Manage your shipping addresses for faster checkout</p>
                    </div>
                    <button id="add-address-btn" class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add New Address
                    </button>
                </div>
                
                <!-- Add/Edit Address Form -->
                <div id="address-form" class="address-form bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900" id="form-title">Add New Address</h3>
                        <button id="close-form-btn" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <form id="new-address-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                        <input type="hidden" id="address_id" name="address_id" value="">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="address_type" class="block text-sm font-medium text-gray-700 mb-1">Address Type</label>
                                <input type="text" id="address_type" name="address_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Home, Work, etc." >
                            </div>
                            
                            <div>
                                <label for="full-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="full-name" name="full-name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="address-line1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                <input type="text" id="address-line1" name="address-line1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="address-line2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2 (Optional)</label>
                                <input type="text" id="address-line2" name="address-line2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="city" name="city" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                <input type="text" id="state" name="state" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="zip-code" class="block text-sm font-medium text-gray-700 mb-1">ZIP/Postal Code</label>
                                <input type="text" id="zip-code" name="zip-code" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                <select id="country" name="country" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" >
                                    <option value="">Select Country</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                    <option value="UK">United Kingdom</option>
                                    <option value="AU">Australia</option>
                                    <option value="IN">India</option>
                                    <!-- More countries would be added here -->
                                </select>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center">
                            <input type="checkbox" id="default-address" name="default-address" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="default-address" class="ml-2 block text-sm text-gray-700">Set as default shipping address</label>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" id="cancel-form-btn" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit" name="add_address" id="submit-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none">
                                Save Address
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Address Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php if (empty($addresses)): ?>
                        <div class="col-span-full text-center py-12">
                            <i class="fas fa-map-marker-alt text-gray-300 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-1">No saved addresses</h3>
                            <p class="text-gray-500 mb-6">Add your addresses for faster checkout</p>
                            
                            <button id="show-empty-add-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none">
                                <i class="fas fa-plus mr-2"></i>Add Address
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($addresses as $address): ?>
                            <div class="address-card bg-white rounded-lg shadow <?= $address['is_default'] ? 'default-address' : '' ?>">
                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <?php if ($address['is_default']): ?>
                                                <div class="flex items-center mb-2">
                                                    <span class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full mr-2">Default</span>
                                                    <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($address['address_type']) ?></h3>
                                                </div>
                                            <?php else: ?>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2"><?= htmlspecialchars($address['address_type']) ?></h3>
                                            <?php endif; ?>
                                            <p class="text-gray-700"><?= htmlspecialchars($address['full_name']) ?></p>
                                            <p class="text-gray-700"><?= htmlspecialchars($address['address_line1']) ?></p>
                                            <?php if (!empty($address['address_line2'])): ?>
                                                <p class="text-gray-700"><?= htmlspecialchars($address['address_line2']) ?></p>
                                            <?php endif; ?>
                                            <p class="text-gray-700"><?= htmlspecialchars($address['city']) ?>, <?= htmlspecialchars($address['state']) ?> <?= htmlspecialchars($address['zip_code']) ?></p>
                                            <p class="text-gray-700"><?= htmlspecialchars($address['country']) ?></p>
                                            <p class="text-gray-700 mt-2">Phone: <?= htmlspecialchars($address['phone']) ?></p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <?php if (!$address['is_default']): ?>
                                                <a href="addresses.php?action=set_default&id=<?= $address['id'] ?>" class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">
                                                    Set Default
                                                </a>
                                            <?php endif; ?>
                                            <button onclick="openEditForm(<?= $address['id'] ?>)" class="p-2 text-blue-600 hover:text-blue-800 focus:outline-none">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="addresses.php?action=delete&id=<?= $address['id'] ?>" onclick="return confirm('Are you sure you want to delete this address?')" class="p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Address
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this address? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="deleteAddress()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
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
            
            // Initialize address form toggle
            const addBtn = document.getElementById('add-address-btn');
            const addressForm = document.getElementById('address-form');
            const closeFormBtn = document.getElementById('close-form-btn');
            const cancelFormBtn = document.getElementById('cancel-form-btn');
            const showEmptyAddBtn = document.getElementById('show-empty-add-btn');
            
            addBtn.addEventListener('click', function() {
                addressForm.classList.add('open');
                // Reset form
                document.getElementById('new-address-form').reset();
                // Scroll to form
                addressForm.scrollIntoView({ behavior: 'smooth' });
            });
            
            if (showEmptyAddBtn) {
                showEmptyAddBtn.addEventListener('click', function() {
                    addressForm.classList.add('open');
                    // Reset form
                    document.getElementById('new-address-form').reset();
                });
            }
            
            closeFormBtn.addEventListener('click', function() {
                addressForm.classList.remove('open');
            });
            
            cancelFormBtn.addEventListener('click', function() {
                addressForm.classList.remove('open');
            });
            
            // Form submission
            document.getElementById('new-address-form').addEventListener('submit', function(e) {
                // e.preventDefault();
                // Here you would handle the form submission to your backend
                // For demo purposes, we'll just show a notification and close the form
                showNotification('Address saved successfully', 'success');
                addressForm.classList.remove('open');
                
                // In a real app, you would add the new address to the list
                // and potentially refresh the address cards
                // document.getElementById('new-address-form').submit();
            });
        });
        
        // Address management functions
        let addressToDelete = null;
        
        function openEditForm(addressId) {
            // In a real app, this would populate the form with the address data
            const addressForm = document.getElementById('address-form');
            addressForm.classList.add('open');
            
            // Scroll to form
            addressForm.scrollIntoView({ behavior: 'smooth' });
            
            // For demo, we'll just show which address we're editing
            console.log('Editing address:', addressId);
        }
        
        function confirmDeleteAddress(addressId) {
            addressToDelete = addressId;
            document.getElementById('delete-modal').classList.remove('hidden');
        }
        
        function closeDeleteModal() {
            addressToDelete = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }
        
        function deleteAddress() {
            if (addressToDelete) {
                // In a real app, you would make an API call to delete the address
                console.log('Deleting address:', addressToDelete);
                showNotification('Address deleted', 'info');
                
                // Close the modal
                closeDeleteModal();
                
                // In a real app, you would remove the address card from the DOM
                // or refresh the list from the server
            }
        }
        
        function setDefaultAddress(addressId) {
            // In a real app, you would make an API call to set this as default
            console.log('Setting default address:', addressId);
            showNotification('Default address updated', 'success');
            
            // For demo, we'll just update the UI
            document.querySelectorAll('.default-address').forEach(el => {
                el.classList.remove('default-address');
            });
            
            // Find the card with this ID and add the default class
            // This is simplified for the demo - in a real app you'd need proper selectors
            const card = document.querySelector(`[onclick*="${addressId}"]`).closest('.address-card');
            if (card) {
                card.classList.add('default-address');
            }
        }
        
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
    <!-- Update your JavaScript to handle editing -->
    <script>
        // Function to populate the edit form
        function openEditForm(addressId) {
            // In a real app, you would fetch the address data via AJAX
            // For this example, we'll use the data from PHP
            const addressData = <?= json_encode(array_column($addresses, null, 'id')) ?>;
            const address = addressData[addressId];
            
            if (address) {
                document.getElementById('form-title').textContent = 'Edit Address';
                document.getElementById('address_id').value = address.id;
                document.getElementById('address_type').value = address.address_type;
                document.getElementById('full-name').value = address.full_name;
                document.getElementById('phone').value = address.phone;
                document.getElementById('address-line1').value = address.address_line1;
                document.getElementById('address-line2').value = address.address_line2 || '';
                document.getElementById('city').value = address.city;
                document.getElementById('state').value = address.state;
                document.getElementById('zip-code').value = address.zip_code;
                document.getElementById('country').value = address.country;
                document.getElementById('default-address').checked = address.is_default == 1;
                
                // Change the form action
                const form = document.getElementById('new-address-form');
                form.removeAttribute('name');
                const submitBtn = document.getElementById('submit-btn');
                submitBtn.name = 'update_address';
                submitBtn.textContent = 'Update Address';
                
                // Show the form
                document.getElementById('address-form').classList.add('open');
                document.getElementById('address-form').scrollIntoView({ behavior: 'smooth' });
            }
        }
        
        // Reset form when adding new address
        document.getElementById('add-address-btn').addEventListener('click', function() {
            document.getElementById('form-title').textContent = 'Add New Address';
            document.getElementById('new-address-form').reset();
            document.getElementById('address_id').value = '';
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.name = 'add_address';
            submitBtn.textContent = 'Save Address';
            document.getElementById('address-form').classList.add('open');
            document.getElementById('address-form').scrollIntoView({ behavior: 'smooth' });
        });
    </script>

    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>
</html>