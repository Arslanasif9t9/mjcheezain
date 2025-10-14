<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods | Multivendor Platform</title>
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
        .payment-card {
            transition: all 0.3s ease;
        }

        .payment-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .default-payment {
            border-left: 4px solid #10b981;
        }

        .payment-form {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
        }

        .payment-form.open {
            max-height: 1000px;
        }

        /* Credit card styling */
        .card-front,
        .card-back {
            perspective: 1000px;
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .card-back {
            display: none;
        }

        .card-flipped .card-front {
            display: none;
        }

        .card-flipped .card-back {
            display: block;
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
        <div class="hidden md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64 bg-white border-r border-gray-200">
                <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
                    <span class="text-white font-bold text-xl">cheezain</span>
                </div>                
                <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
                    <div class="flex items-center px-4 py-3 mb-4 bg-gray-100 rounded-lg">
                        <img class="w-10 h-10 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg" alt="User">
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">John Doe</p>
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
                        <a href="./payments.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active">
                            <i class="fas fa-credit-card mr-3"></i>
                            Payment Methods
                        </a>
                        <a href="./support.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-headset mr-3"></i>
                            Support
                        </a>
                        <a href="./profile.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100">
                            <i class="fas fa-user-cog mr-3"></i>
                            Profile Settings
                        </a>
                    </nav>
                    
                    <div class="mt-auto mb-4">
                        <a href="#" class="flex items-center px-4 py-2 text-sm font-medium text-red-600 rounded-lg hover:bg-red-50">
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
                                <a href="./notifications.php" class="text-xs font-medium text-blue-600 hover:text-blue-800">See all
                                    notifications</a>
                            </div>
                        </div>
                    </div>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center focus:outline-none">
                            <div class="mr-3 text-right hidden sm:block">
                                <span class="block text-sm font-medium text-gray-700">John Doe</span>
                                <span class="block text-xs text-gray-500">Admin</span>
                            </div>
                            <div class="relative">
                                <img class="w-8 h-8 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg"
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
                            <a href="./payments.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100 active"><i class="fas fa-credit-card mr-3"></i>Payment Methods</a>
                            <a href="./support.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-headset mr-3"></i>Support</a>
                            <a href="./profile.php" class="block px-4 py-2 text-sm font-medium text-gray-700 rounded-lg sidebar-item hover:bg-gray-100"><i class="fas fa-user-cog mr-3"></i>Profile Settings</a>
                            <a href="#" class="block px-4 py-2 text-sm font-medium text-red-600 rounded-lg sidebar-item hover:bg-red-50"><i class="fas fa-sign-out-alt mr-3"></i>Logout</a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Payment Methods Header -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Saved Payment Methods</h2>
                        <p class="text-gray-600">Manage your payment options for faster checkout</p>
                    </div>
                    <button id="add-payment-btn"
                        class="mt-4 md:mt-0 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none flex items-center">
                        <i class="fas fa-plus mr-2"></i>Add New Payment Method
                    </button>
                </div>

                <!-- Add Payment Form (Initially Hidden) -->
                <div id="payment-form" class="payment-form bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Add Payment Method</h3>
                        <button id="close-form-btn" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="new-payment-form">
                        <!-- Payment Method Tabs -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="flex -mb-px space-x-8">
                                <button type="button" id="credit-card-tab"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                                    Credit/Debit Card
                                </button>
                                <button type="button" id="paypal-tab"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                    PayPal
                                </button>
                                <button type="button" id="bank-tab"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                                    Bank Transfer
                                </button>
                            </nav>
                        </div>

                        <!-- Credit Card Form (Default Visible) -->
                        <div id="credit-card-form" class="space-y-6">
                            <!-- Credit Card Preview -->
                            <div class="relative">
                                <div
                                    class="card-front bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-white shadow-lg">
                                    <div class="flex justify-between items-start mb-8">
                                        <div>
                                            <p class="text-xs opacity-80">Card Number</p>
                                            <p class="text-xl tracking-widest font-medium" id="card-number-preview">••••
                                                •••• •••• ••••</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <img src="https://via.placeholder.com/40x25?text=MC" alt="Card Type"
                                                class="h-6">
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-end">
                                        <div>
                                            <p class="text-xs opacity-80">Card Holder</p>
                                            <p class="text-sm font-medium uppercase" id="card-name-preview">FULL NAME
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs opacity-80">Expires</p>
                                            <p class="text-sm font-medium" id="card-expiry-preview">MM/YY</p>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="card-back bg-gradient-to-r from-gray-700 to-gray-900 rounded-lg p-6 text-white shadow-lg mt-4">
                                    <div class="h-8 bg-black mb-6"></div>
                                    <div class="flex items-center justify-end">
                                        <div class="bg-white rounded px-2 py-1">
                                            <p class="text-gray-900 text-xs font-bold" id="card-cvv-preview">•••</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" id="flip-card-btn"
                                    class="absolute top-4 right-4 text-white opacity-70 hover:opacity-100 focus:outline-none">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>

                            <!-- Credit Card Form Fields -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="card-number" class="block text-sm font-medium text-gray-700 mb-1">Card
                                        Number</label>
                                    <input type="text" id="card-number" name="card-number"
                                        placeholder="1234 5678 9012 3456"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        maxlength="19">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="card-name" class="block text-sm font-medium text-gray-700 mb-1">Name on
                                        Card</label>
                                    <input type="text" id="card-name" name="card-name" placeholder="John Doe"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="expiry-date"
                                        class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                                    <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                        maxlength="5">
                                </div>

                                <div>
                                    <label for="cvv" class="block text-sm font-medium text-gray-700 mb-1">Security Code
                                        (CVV)</label>
                                    <div class="relative">
                                        <input type="text" id="cvv" name="cvv" placeholder="•••"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-12"
                                            maxlength="4">
                                        <button type="button" id="cvv-help"
                                            class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <i class="fas fa-question-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal Form (Hidden by Default) -->
                        <div id="paypal-form" class="hidden space-y-6">
                            <div class="bg-gray-100 p-6 rounded-lg text-center">
                                <i class="fab fa-cc-paypal text-4xl text-blue-500 mb-4"></i>
                                <p class="text-gray-700 mb-4">You'll be redirected to PayPal to complete your payment
                                    method setup</p>
                                <button type="button"
                                    class="px-4 py-2 bg-yellow-400 text-white rounded-lg font-medium hover:bg-yellow-500 focus:outline-none">
                                    Connect PayPal Account
                                </button>
                            </div>
                        </div>

                        <!-- Bank Transfer Form (Hidden by Default) -->
                        <div id="bank-form" class="hidden space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="account-name"
                                        class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name</label>
                                    <input type="text" id="account-name" name="account-name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="bank-name" class="block text-sm font-medium text-gray-700 mb-1">Bank
                                        Name</label>
                                    <input type="text" id="bank-name" name="bank-name"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="account-number"
                                        class="block text-sm font-medium text-gray-700 mb-1">Account Number</label>
                                    <input type="text" id="account-number" name="account-number"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="routing-number"
                                        class="block text-sm font-medium text-gray-700 mb-1">Routing Number</label>
                                    <input type="text" id="routing-number" name="routing-number"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center">
                            <input type="checkbox" id="default-payment" name="default-payment"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="default-payment" class="ml-2 block text-sm text-gray-700">Set as default payment
                                method</label>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" id="cancel-form-btn"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none">
                                Save Payment Method
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Methods Cards -->
                <div class="space-y-4">
                    <!-- Default Payment Card -->
                    <div class="payment-card bg-white rounded-lg shadow default-payment">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start">
                                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                        <i class="fab fa-cc-visa text-3xl text-blue-800"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center mb-1">
                                            <span
                                                class="text-sm font-medium text-green-600 bg-green-100 px-2 py-1 rounded-full mr-2">Default</span>
                                            <h3 class="text-lg font-medium text-gray-900">Visa ending in 4242</h3>
                                        </div>
                                        <p class="text-gray-700">John Doe</p>
                                        <p class="text-gray-700">Expires 04/25</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="confirmDeletePayment('pay-1')"
                                        class="p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Other Payment Card -->
                    <div class="payment-card bg-white rounded-lg shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start">
                                    <div class="bg-gray-100 p-3 rounded-lg mr-4">
                                        <i class="fab fa-cc-mastercard text-3xl text-gray-800"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">Mastercard ending in 5555
                                        </h3>
                                        <p class="text-gray-700">John Doe</p>
                                        <p class="text-gray-700">Expires 12/24</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="setDefaultPayment('pay-2')"
                                        class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">
                                        Set Default
                                    </button>
                                    <button onclick="confirmDeletePayment('pay-2')"
                                        class="p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PayPal Payment Card -->
                    <div class="payment-card bg-white rounded-lg shadow">
                        <div class="p-6">
                            <div class="flex justify-between items-start">
                                <div class="flex items-start">
                                    <div class="bg-blue-100 p-3 rounded-lg mr-4">
                                        <i class="fab fa-cc-paypal text-3xl text-blue-500"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">PayPal Account</h3>
                                        <p class="text-gray-700">john.doe@example.com</p>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="setDefaultPayment('pay-3')"
                                        class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">
                                        Set Default
                                    </button>
                                    <button onclick="confirmDeletePayment('pay-3')"
                                        class="p-2 text-red-500 hover:text-red-700 focus:outline-none">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State (for demonstration) -->
                    <!-- <div class="text-center py-12">
                        <i class="fas fa-credit-card text-gray-300 text-5xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No saved payment methods</h3>
                        <p class="text-gray-500 mb-6">Add your payment methods for faster checkout</p>
                        <button id="show-empty-add-btn" class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 focus:outline-none">
                            <i class="fas fa-plus mr-2"></i>Add Payment Method
                        </button>
                    </div> -->
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

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-exclamation text-red-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Delete Payment Method
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete this payment method? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="deletePayment()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Delete
                    </button>
                    <button type="button" onclick="closeDeleteModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- CVV Help Modal -->
    <div id="cvv-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Security Code (CVV)
                                </h3>
                                <button onclick="closeCVVModal()"
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div class="mt-4">
                                <p class="text-sm text-gray-500 mb-4">
                                    The CVV is a 3 or 4 digit code printed on your card.
                                </p>
                                <div class="flex justify-center mb-4">
                                    <img src="https://via.placeholder.com/300x150?text=CVV+Location+Example"
                                        alt="CVV Location" class="rounded-lg border border-gray-200">
                                </div>
                                <ul class="text-sm text-gray-500 space-y-2">
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>For Visa/Mastercard/Discover: 3 digits on back of card</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check-circle text-green-500 mt-1 mr-2"></i>
                                        <span>For American Express: 4 digits on front of card</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeCVVModal()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Got it!
                    </button>
                </div>
            </div>
        </div>
    </div>

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

            // Initialize payment form toggle
            const addBtn = document.getElementById('add-payment-btn');
            const paymentForm = document.getElementById('payment-form');
            const closeFormBtn = document.getElementById('close-form-btn');
            const cancelFormBtn = document.getElementById('cancel-form-btn');
            const showEmptyAddBtn = document.getElementById('show-empty-add-btn');

            addBtn.addEventListener('click', function () {
                paymentForm.classList.add('open');
                // Reset form and show credit card tab by default
                document.getElementById('new-payment-form').reset();
                showFormTab('credit-card');
                // Scroll to form
                paymentForm.scrollIntoView({ behavior: 'smooth' });
            });

            if (showEmptyAddBtn) {
                showEmptyAddBtn.addEventListener('click', function () {
                    paymentForm.classList.add('open');
                    // Reset form
                    document.getElementById('new-payment-form').reset();
                    showFormTab('credit-card');
                });
            }

            closeFormBtn.addEventListener('click', function () {
                paymentForm.classList.remove('open');
            });

            cancelFormBtn.addEventListener('click', function () {
                paymentForm.classList.remove('open');
            });

            // Form tab switching
            document.getElementById('credit-card-tab').addEventListener('click', function () {
                showFormTab('credit-card');
            });

            document.getElementById('paypal-tab').addEventListener('click', function () {
                showFormTab('paypal');
            });

            document.getElementById('bank-tab').addEventListener('click', function () {
                showFormTab('bank');
            });

            // Credit card preview updates
            document.getElementById('card-number').addEventListener('input', function (e) {
                // Format card number with spaces
                let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                let formatted = value.replace(/(\d{4})/g, '$1 ').trim();
                e.target.value = formatted;

                // Update preview
                if (value.length > 0) {
                    const lastFour = value.slice(-4);
                    const masked = '•••• •••• •••• ' + lastFour;
                    document.getElementById('card-number-preview').textContent = masked;
                } else {
                    document.getElementById('card-number-preview').textContent = '•••• •••• •••• ••••';
                }

                // Detect card type (simplified for demo)
                if (/^4/.test(value)) {
                    document.querySelector('.card-front img').src = 'https://via.placeholder.com/40x25?text=VISA';
                } else if (/^5[1-5]/.test(value)) {
                    document.querySelector('.card-front img').src = 'https://via.placeholder.com/40x25?text=MC';
                } else {
                    document.querySelector('.card-front img').src = 'https://via.placeholder.com/40x25?text=CARD';
                }
            });

            document.getElementById('card-name').addEventListener('input', function (e) {
                document.getElementById('card-name-preview').textContent = e.target.value.toUpperCase() || 'FULL NAME';
            });

            document.getElementById('expiry-date').addEventListener('input', function (e) {
                // Format expiry date
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 2) {
                    value = value.slice(0, 2) + '/' + value.slice(2, 4);
                }
                e.target.value = value;

                // Update preview
                document.getElementById('card-expiry-preview').textContent = value || 'MM/YY';
            });

            document.getElementById('cvv').addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                e.target.value = value;
                document.getElementById('card-cvv-preview').textContent = '•'.repeat(value.length) || '•••';
            });

            // Card flip
            document.getElementById('flip-card-btn').addEventListener('click', function () {
                document.querySelector('.card-front').parentElement.classList.toggle('card-flipped');
            });

            // CVV help
            document.getElementById('cvv-help').addEventListener('click', function () {
                document.getElementById('cvv-modal').classList.remove('hidden');
            });

            // Form submission
            document.getElementById('new-payment-form').addEventListener('submit', function (e) {
                e.preventDefault();
                // Here you would handle the form submission to your backend
                // For demo purposes, we'll just show a notification and close the form
                showNotification('Payment method saved successfully', 'success');
                paymentForm.classList.remove('open');

                // In a real app, you would add the new payment method to the list
                // and potentially refresh the payment cards
            });
        });

        // Show the selected form tab
        function showFormTab(tabName) {
            // Hide all forms
            document.getElementById('credit-card-form').classList.add('hidden');
            document.getElementById('paypal-form').classList.add('hidden');
            document.getElementById('bank-form').classList.add('hidden');

            // Remove active class from all tabs
            document.getElementById('credit-card-tab').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('credit-card-tab').classList.add('border-transparent', 'text-gray-500');
            document.getElementById('paypal-tab').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('paypal-tab').classList.add('border-transparent', 'text-gray-500');
            document.getElementById('bank-tab').classList.remove('border-blue-500', 'text-blue-600');
            document.getElementById('bank-tab').classList.add('border-transparent', 'text-gray-500');

            // Show selected form and update tab
            if (tabName === 'credit-card') {
                document.getElementById('credit-card-form').classList.remove('hidden');
                document.getElementById('credit-card-tab').classList.add('border-blue-500', 'text-blue-600');
                document.getElementById('credit-card-tab').classList.remove('border-transparent', 'text-gray-500');
            } else if (tabName === 'paypal') {
                document.getElementById('paypal-form').classList.remove('hidden');
                document.getElementById('paypal-tab').classList.add('border-blue-500', 'text-blue-600');
                document.getElementById('paypal-tab').classList.remove('border-transparent', 'text-gray-500');
            } else if (tabName === 'bank') {
                document.getElementById('bank-form').classList.remove('hidden');
                document.getElementById('bank-tab').classList.add('border-blue-500', 'text-blue-600');
                document.getElementById('bank-tab').classList.remove('border-transparent', 'text-gray-500');
            }
        }

        // Payment management functions
        let paymentToDelete = null;

        function confirmDeletePayment(paymentId) {
            paymentToDelete = paymentId;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            paymentToDelete = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function deletePayment() {
            if (paymentToDelete) {
                // In a real app, you would make an API call to delete the payment method
                console.log('Deleting payment method:', paymentToDelete);
                showNotification('Payment method deleted', 'info');

                // Close the modal
                closeDeleteModal();

                // In a real app, you would remove the payment card from the DOM
                // or refresh the list from the server
            }
        }

        function setDefaultPayment(paymentId) {
            // In a real app, you would make an API call to set this as default
            console.log('Setting default payment method:', paymentId);
            showNotification('Default payment method updated', 'success');

            // For demo, we'll just update the UI
            document.querySelectorAll('.default-payment').forEach(el => {
                el.classList.remove('default-payment');
            });

            // Find the card with this ID and add the default class
            // This is simplified for the demo - in a real app you'd need proper selectors
            const card = document.querySelector(`[onclick*="${paymentId}"]`).closest('.payment-card');
            if (card) {
                card.classList.add('default-payment');
            }
        }

        function closeCVVModal() {
            document.getElementById('cvv-modal').classList.add('hidden');
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 ${type === 'info' ? 'border-blue-500' : 'border-green-500'
                } z-50`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${type === 'info' ? 'fa-info-circle text-blue-500' : 'fa-check-circle text-green-500'
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