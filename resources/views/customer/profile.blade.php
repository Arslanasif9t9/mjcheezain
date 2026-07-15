<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | MJ Cheezain</title>
    <x-customer.theme />
    <style>
        .profile-card {
            transition: all 0.3s ease;
        }
        .profile-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(232, 93, 133, 0.12);
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
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>

        <!-- Main Content -->
        <div class="flex flex-col flex-1 min-w-0">
            <x-customer.header title="My Profile" subtitle="Your account details and preferences" :basic_info="$basic_info" />

            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-6 lg:p-8 pb-28 md:pb-8 page-enter">
                <!-- Profile Header -->
                <div class="profile-card app-card overflow-hidden mb-5 md:mb-6 -mt-2 md:mt-0 relative z-10">
                    <div class="brand-gradient h-36 md:h-48 relative" id="cover-container">
                        {{-- @if ($bannerImage) --}}
                        <img
                            src="{{ asset('storage/customer/banner/' . $bannerImage) }}"
                            class="w-full h-36 md:h-48 object-cover object-center rounded-t-xl shadow-md border-b border-pink-100"
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
                            <div class="flex flex-col sm:flex-row items-center sm:items-end text-center sm:text-left">
                                <div class="relative">
                                    <img class="w-32 h-32 rounded-full border-4 border-white mx-auto" 
                                        src="{{ asset('storage/customer/profile/' . $basic_info->profile_image) }}" 
                                        alt="Profile"
                                        id="profile-image">
                                    
                                    <!-- Camera Icon for Profile Picture -->
                                    <button class="absolute bottom-2 right-2 brand-gradient brand-shadow text-white p-2 rounded-full"
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
                                
                                <div class="ml-0 sm:ml-6 mt-4 sm:mt-0 mb-4">
                                    <h2 class="text-2xl font-bold text-gray-900">{{ $basic_info->first_name }} {{ $basic_info->last_name }}</h2>
                                    <p class="text-brand font-semibold text-sm"><i class="fas fa-crown text-xs mr-1"></i>Gold Member</p>
                                </div>
                            </div>
                            
                            <div class="mt-4 md:mt-0 w-full md:w-auto text-center md:text-right">
                                <a href="/customer/profile/edit" class="inline-block w-full md:w-auto px-5 py-2.5 brand-gradient brand-shadow text-white rounded-full text-sm font-semibold hover:opacity-90 focus:outline-none">
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
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-5 md:mb-6">
                    <div class="stats-card app-card p-3.5 md:p-4 text-center">
                        <div class="text-xl md:text-2xl font-extrabold text-brand mb-0.5">
                            {{ DB::table('orders')->where('user_id', $basic_info->user_id)->count() }}
                        </div>
                        <p class="text-[11px] md:text-sm text-gray-500 font-medium">Total Orders</p>
                    </div>
                    <div class="stats-card app-card p-3.5 md:p-4 text-center">
                        <div class="text-xl md:text-2xl font-extrabold text-emerald-500 mb-0.5">
                            {{ DB::table('orders')->where('user_id', $basic_info->user_id)->where('status', 'completed')->count() }}
                        </div>
                        <p class="text-[11px] md:text-sm text-gray-500 font-medium">Completed</p>
                    </div>
                    <div class="stats-card app-card p-3.5 md:p-4 text-center">
                        <div class="text-xl md:text-2xl font-extrabold text-purple-500 mb-0.5">
                            {{ DB::table('favorites')->where('user_id', $basic_info->user_id)->count() }}
                        </div>
                        <p class="text-[11px] md:text-sm text-gray-500 font-medium">Wishlist Items</p>
                    </div>
                    <div class="stats-card app-card p-3.5 md:p-4 text-center">
                        <div class="text-xl md:text-2xl font-extrabold text-amber-500 mb-0.5">
                            {{ DB::table('orders')->where('user_id', $basic_info->user_id)->where('status', '!=', 'completed')->count() }}
                        </div>
                        <p class="text-[11px] md:text-sm text-gray-500 font-medium">Active Orders</p>
                    </div>
                </div>
                
                <!-- Profile Content -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column -->
                    <div class="lg:col-span-2">
                        <!-- About Section -->
                        <div class="profile-card app-card p-6 mb-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">About</h3>
                                <button class="text-brand hover:opacity-70 focus:outline-none">
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

                        <!-- Saved Addresses Section (Theme matching home screen) -->
                        <div class="profile-card app-card p-6 mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center space-x-3">
                                    <div class="bg-pink-100 p-2.5 rounded-lg text-[#FF7DA0]">
                                        <i class="fas fa-map-marker-alt text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">Saved Addresses</h3>
                                        <p class="text-xs text-gray-500">Manage your shipping and billing locations</p>
                                    </div>
                                </div>
                                <a href="/customer/addresses" class="inline-block text-center px-4 py-2 bg-gradient-to-r from-[#FF7DA0] to-[#FFC275] hover:opacity-90 text-white rounded-lg text-sm font-semibold shadow-sm transition-all">
                                    Manage Addresses
                                </a>
                            </div>
                        </div>
                        
                        <!-- Recent Activity -->
                        {{-- <div class="profile-card app-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Activity</h3>
                                <button class="text-brand hover:opacity-70 focus:outline-none">
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
                                    'order_placed' => ['icon' => 'shopping-bag', 'color' => 'pink'],
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
                                <button class="text-[#E85D85] text-sm font-medium hover:text-[#C94A72] focus:outline-none">
                                    View All Activity
                                </button>
                            </div>
                        </div> --}}
                    </div>
                    
                    <!-- Right Column -->
                    <div>
                        <!-- Membership Status -->
                        {{-- <div class="profile-card app-card p-6 mb-6">
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
                            <button class="w-full mt-4 px-4 py-2 bg-[#E85D85] text-white rounded-lg hover:bg-[#C94A72] focus:outline-none">
                                Learn More
                            </button>
                        </div> --}}
                        
                        <!-- Recent Reviews -->
                        <div class="profile-card app-card p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Recent Reviews</h3>
                                {{-- <button class="text-brand hover:opacity-70 focus:outline-none">
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
                                <button class="text-[#E85D85] text-sm font-medium hover:text-[#C94A72] focus:outline-none">
                                    View All Reviews
                                </button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </main>
            <x-customer.mobile-nav />
        </div>
    </div>


    
    <script>
        
        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `notification fixed top-4 right-4 p-4 rounded-lg shadow-lg bg-white border-l-4 ${
                type === 'info' ? 'border-[#FF7DA0]' : 'border-green-500'
            } z-50`;
            notification.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas ${
                            type === 'info' ? 'fa-info-circle text-[#FF7DA0]' : 'fa-check-circle text-green-500'
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