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
        <x-customer.sidebar :basic_info="$basic_info"/>
        
        <!-- Main Content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation --
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <!-- Left side - Mobile menu and title -->
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                </div>

                <!-- Center - Search bar -->
                <div class="hidden flex-1 max-w-md mx-4">
                    <div class="relative w-full">
                        <input type="text" placeholder="Search..."
                            class="w-full py-2 pl-4 pr-10 text-sm bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white">
                        <button class="absolute right-3 top-2 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Right side - Icons and user menu -->
                <div class="hidden flex items-center space-x-4">
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
                <div class="profile-card bg-white rounded-lg shadow overflow-hidden mb-6 h-[18rem]">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-48 relative" id="cover-container">
                        {{-- @if ($bannerImage) --}}
                        <img 
                            src="{{ asset('storage/customer/banner/' . $bannerImage) }}" 
                            class="
                                w-full 
                                h-48
                                object-cover 
                                object-center
                                rounded-t-xl
                                shadow-md
                                border-b border-gray-200
                            "
                            id="cover-image"
                        >
                        {{-- @endif --}}
                        
                        <!-- Camera Icon for Cover Photo -->
                        <div class="absolute top-2 right-2">
                            <button class="bg-black bg-opacity-50 hover:bg-opacity-70 text-white p-2 rounded-full transition-all duration-200"
                                    id="cover-camera-btn">
                                <i class="fas fa-camera"></i>
                            </button>
                            
                            <!-- Cover Photo Actions Dropdown -->
                            <div class="absolute right-0 mt-1 bg-white rounded-lg shadow-lg py-2 z-10 hidden w-40" 
                                id="cover-actions-dropdown">
                                <input type="file" 
                                    id="cover-upload-input" 
                                    accept="image/*" 
                                    class="hidden">
                                <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                        onclick="document.getElementById('cover-upload-input').click()">
                                    <i class="fas fa-upload mr-2"></i> Upload Photo
                                </button>
                                <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center"
                                        id="remove-cover-btn">
                                    <i class="fas fa-trash-alt mr-2"></i> Remove Photo
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-6 pb-6 -mt-12">
                        <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                            <div class="flex items-end">
                                <div class="relative">
                                    <img class="w-32 h-32 rounded-full border-4 border-white" 
                                        src="{{ asset('storage/customer/profile/' . $basic_info->profile_image) }}" 
                                        alt="Profile"
                                        id="profile-image">
                                    
                                    <!-- Camera Icon for Profile Picture -->
                                    <button class="absolute bottom-2 right-2 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full"
                                            id="profile-camera-btn">
                                        <i class="fas fa-camera text-sm"></i>
                                    </button>
                                    
                                    <!-- Profile Picture Actions Dropdown -->
                                    <div class="absolute bottom-[2rem] right-[-9rem] mb-2 bg-white rounded-lg shadow-lg py-2 z-10 hidden w-40"
                                        id="profile-actions-dropdown">
                                        <input type="file" 
                                            id="profile-upload-input" 
                                            accept="image/*" 
                                            class="hidden">
                                        <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center"
                                                onclick="document.getElementById('profile-upload-input').click()">
                                            <i class="fas fa-upload mr-2"></i> Upload Photo
                                        </button>
                                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center"
                                                id="remove-profile-btn">
                                            <i class="fas fa-trash-alt mr-2"></i> Remove Photo
                                        </button>
                                    </div>
                                    
                                    <!-- Verified Badge -->
                                    <span class="hidden absolute bottom-0 right-0 bg-green-500 rounded-full p-1 border-2 border-white">
                                        <i class="fas fa-check text-white text-xs"></i>
                                    </span>
                                </div>
                                
                                <div class="ml-6 mb-4">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $basic_info->first_name }} {{ $basic_info->last_name }}</h2>
                                    <p class="text-gray-600">Gold Member</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0">
                                <a href="/customer/profile/edit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 focus:outline-none">
                                    <i class="fas fa-user-edit mr-2"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    // Wait for DOM to load
                    document.addEventListener('DOMContentLoaded', function() {
                        // Elements
                        const coverCameraBtn = document.getElementById('cover-camera-btn');
                        const coverActionsDropdown = document.getElementById('cover-actions-dropdown');
                        const coverUploadInput = document.getElementById('cover-upload-input');
                        const removeCoverBtn = document.getElementById('remove-cover-btn');
                        const coverImage = document.getElementById('cover-image');
                        
                        const profileCameraBtn = document.getElementById('profile-camera-btn');
                        const profileActionsDropdown = document.getElementById('profile-actions-dropdown');
                        const profileUploadInput = document.getElementById('profile-upload-input');
                        const removeProfileBtn = document.getElementById('remove-profile-btn');
                        const profileImage = document.getElementById('profile-image');
                        
                        // Toggle dropdowns
                        coverCameraBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            coverActionsDropdown.classList.toggle('hidden');
                            profileActionsDropdown.classList.add('hidden');
                        });
                        
                        profileCameraBtn.addEventListener('click', function(e) {
                            e.stopPropagation();
                            profileActionsDropdown.classList.toggle('hidden');
                            coverActionsDropdown.classList.add('hidden');
                        });
                        
                        // Close dropdowns when clicking outside
                        document.addEventListener('click', function() {
                            coverActionsDropdown.classList.add('hidden');
                            profileActionsDropdown.classList.add('hidden');
                        });
                        
                        // Prevent dropdowns from closing when clicking inside
                        coverActionsDropdown.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                        
                        profileActionsDropdown.addEventListener('click', function(e) {
                            e.stopPropagation();
                        });
                        
                        // Handle cover photo upload
                        coverUploadInput.addEventListener('change', function(e) {
                            if (e.target.files.length > 0) {
                                uploadBannerImage(e.target.files[0]);
                            }
                        });
                        
                        // Handle profile picture upload
                        profileUploadInput.addEventListener('change', function(e) {
                            if (e.target.files.length > 0) {
                                uploadProfileImage(e.target.files[0]);
                            }
                        });
                        
                        // Handle cover photo removal
                        removeCoverBtn.addEventListener('click', function() {
                            removeBannerImage();
                            coverActionsDropdown.classList.add('hidden');
                        });
                        
                        // Handle profile picture removal
                        removeProfileBtn.addEventListener('click', function() {
                            removeProfileImage();
                            profileActionsDropdown.classList.add('hidden');
                        });
                        
                        // Function to upload profile image
                        function uploadProfileImage(file) {
                            // Create form data
                            const formData = new FormData();
                            formData.append('profile_image', file); // Must match controller field name
                            
                            // Show loading state
                            const originalSrc = profileImage.src;
                            profileImage.style.opacity = '0.5';
                            
                            // API endpoint
                            const apiUrl = '/api/upload-profile-image';
                            
                            // Send request
                            fetch(apiUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update image preview
                                    profileImage.src = data.image_url + '?t=' + new Date().getTime();
                                    
                                    // Show success message
                                    showNotification(data.message || 'Profile image uploaded successfully!', 'success');
                                } else {
                                    throw new Error(data.message || 'Upload failed');
                                }
                            })
                            .catch(error => {
                                console.error('Upload error:', error);
                                showNotification('Failed to upload image: ' + error.message, 'error');
                                profileImage.src = originalSrc;
                            })
                            .finally(() => {
                                profileImage.style.opacity = '1';
                                profileActionsDropdown.classList.add('hidden');
                            });
                        }
                        
                        // Function to upload banner image
                        function uploadBannerImage(file) {
                            // Create form data
                            const formData = new FormData();
                            formData.append('banner_image', file); // Must match controller field name
                            
                            // Show loading state
                            const originalSrc = coverImage.src;
                            coverImage.style.opacity = '0.5';
                            
                            // API endpoint
                            const apiUrl = '/api/upload-banner-image';
                            
                            // Send request
                            fetch(apiUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update image preview
                                    coverImage.src = data.image_url + '?t=' + new Date().getTime();
                                    
                                    // Show success message
                                    showNotification(data.message || 'Banner image uploaded successfully!', 'success');
                                } else {
                                    throw new Error(data.message || 'Upload failed');
                                }
                            })
                            .catch(error => {
                                console.error('Upload error:', error);
                                showNotification('Failed to upload image: ' + error.message, 'error');
                                coverImage.src = originalSrc;
                            })
                            .finally(() => {
                                coverImage.style.opacity = '1';
                                coverActionsDropdown.classList.add('hidden');
                            });
                        }
                        
                        // Function to remove profile image
                        function removeProfileImage() {
                            if (!confirm('Are you sure you want to remove your profile picture?')) {
                                return;
                            }
                            
                            // Show loading state
                            const originalSrc = profileImage.src;
                            profileImage.style.opacity = '0.5';
                            
                            // API endpoint
                            const apiUrl = '/api/remove-profile-image';
                            
                            // Send request
                            fetch(apiUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update image preview
                                    profileImage.src = data.image_url + '?t=' + new Date().getTime();
                                    
                                    showNotification(data.message || 'Profile image removed successfully!', 'success');
                                } else {
                                    throw new Error(data.message || 'Removal failed');
                                }
                            })
                            .catch(error => {
                                console.error('Remove error:', error);
                                showNotification('Failed to remove image: ' + error.message, 'error');
                                profileImage.src = originalSrc;
                            })
                            .finally(() => {
                                profileImage.style.opacity = '1';
                            });
                        }
                        
                        // Function to remove banner image
                        function removeBannerImage() {
                            if (!confirm('Are you sure you want to remove your cover photo?')) {
                                return;
                            }
                            
                            // Show loading state
                            const originalSrc = coverImage.src;
                            coverImage.style.opacity = '0.5';
                            
                            // API endpoint
                            const apiUrl = '/api/remove-banner-image';
                            
                            // Send request
                            fetch(apiUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({})
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Update image preview
                                    if (data.image_url) {
                                        coverImage.src = data.image_url + '?t=' + new Date().getTime();
                                    } else {
                                        // Set default cover image
                                        coverImage.src = "{{ asset('storage/customer/banner/default-banner.jpg') }}";
                                    }
                                    
                                    showNotification(data.message || 'Banner image removed successfully!', 'success');
                                } else {
                                    throw new Error(data.message || 'Removal failed');
                                }
                            })
                            .catch(error => {
                                console.error('Remove error:', error);
                                showNotification('Failed to remove image: ' + error.message, 'error');
                                coverImage.src = originalSrc;
                            })
                            .finally(() => {
                                coverImage.style.opacity = '1';
                            });
                        }
                        
                        // Helper function to show notifications
                        function showNotification(message, type) {
                            // Create notification element
                            const notification = document.createElement('div');
                            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                                type === 'success' ? 'bg-green-100 text-green-800 border border-green-200' : 
                                'bg-red-100 text-red-800 border border-red-200'
                            }`;
                            notification.innerHTML = `
                                <div class="flex items-center">
                                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                                    <span>${message}</span>
                                    <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.parentElement.remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `;
                            
                            // Add to document
                            document.body.appendChild(notification);
                            
                            // Auto remove after 5 seconds
                            setTimeout(() => {
                                if (notification.parentNode) {
                                    notification.parentNode.removeChild(notification);
                                }
                            }, 5000);
                        }
                    });
                </script>
                
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600 mb-1">
                            {{ DB::table('orders')->where('user_id', $basic_info->user_id)->count() }}
                        </div>
                        <p class="text-sm text-gray-600">Total Orders</p>
                    </div>
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-green-600 mb-1">
                            {{ DB::table('orders')->where('user_id', $basic_info->user_id)->where('status', 'completed')->count() }}
                        </div>
                        <p class="text-sm text-gray-600">Completed</p>
                    </div>
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-purple-600 mb-1">
                            {{ DB::table('favorites')->where('user_id', $basic_info->user_id)->count() }}
                        </div>
                        <p class="text-sm text-gray-600">Wishlist Items</p>
                    </div>
                    <div class="stats-card bg-white rounded-lg shadow p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600 mb-1">
                            {{ DB::table('orders')->where('user_id', $basic_info->user_id)->where('status', '!=', 'completed')->count() }}
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
                                    <a href="/customer/profile/edit"><i class="fas fa-edit"></i></a>
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm text-gray-500">Bio</p>
                                    <p class="text-gray-700 mt-1">{{ $basic_info->bio }}</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Email</p>
                                        <p class="text-gray-700 mt-1">{{ $basic_info->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Phone</p>
                                        <p class="text-gray-700 mt-1">{{ $basic_info->phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Birthday</p>
                                        <p class="text-gray-700 mt-1">{{ $basic_info->birthday }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Member Since</p>
                                        <p class="text-gray-700 mt-1"><?php echo date('F j, Y'); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        {{-- <div class="profile-card bg-white rounded-lg shadow p-6">
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
                        </div> --}}
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <!-- Membership Status -->
                        {{-- <div class="profile-card bg-white rounded-lg shadow p-6 mb-6">
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
                        </div> --}}
                        
                        <!-- Recent Reviews -->
                        <div class="profile-card bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
                                {{-- <button class="text-blue-600 hover:text-blue-800 focus:outline-none">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button> --}}
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
                            {{-- <div class="mt-4 text-center">
                                <button class="text-blue-600 text-sm font-medium hover:text-blue-800 focus:outline-none">
                                    View All Reviews
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    
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