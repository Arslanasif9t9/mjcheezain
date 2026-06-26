<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings | Multivendor Platform</title>
    <!-- Tailwind CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
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
        .settings-section {
            transition: all 0.3s ease;
        }
        
        .settings-card {
            transition: all 0.2s ease;
        }
        .settings-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px -5px rgba(0, 0, 0, 0.1);
        }
        
        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }
        
        /* Animation for notifications */
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }
        
        .notification {
            animation: slideIn 0.3s ease-out;
        }
        
        /* File upload styling */
        .file-upload-input {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
        
        .file-upload-label {
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <x-customer.sidebar :basic_info="$basic_info"/>
        
        <!-- Main Content Area -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top Navigation -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200">
                <!-- Left side - Mobile menu and title -->
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-500 focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Profile Settings</h1>
                </div>
            </header>



            <main class="flex-1 overflow-y-auto p-4 md:p-6">
            
            <!-- Profile Settings Tabs -->
            {{-- <div class="border-b border-gray-200 mb-6">
                <nav class="flex -mb-px space-x-8 overflow-x-auto">
                    <a href="?tab=account" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm tab-active">
                        Account
                    </a>
                    <a href="?tab=security" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Security
                    </a>
                    <a href="?tab=notifications" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Notifications
                    </a>
                    <a href="?tab=actions" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Account Actions
                    </a>
                </nav>
            </div> --}}
            
            <!-- Tab Content -->
            <div class="tab-content">
                {{-- <?php if ($active_tab == 'account'): ?> --}}
                    <!-- Account Settings Section -->
                    <div class="settings-section bg-white rounded-lg shadow overflow-hidden mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
                            <p class="text-sm text-gray-500">Update your account's profile information and email address.</p>
                        </div>
                        
                        <div class="p-6">
                            <form id="profile-form" action="/customer/profile/save" method="POST" enctype="multipart/form-data">
                                <div class="flex flex-col md:flex-row">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $basic_info->user_id }}">
                                    <!-- Profile Picture -->
                                    <div class="md:w-1/3 mb-6 md:mb-0 md:pr-6">
                                        <div class="flex flex-col items-center">
                                            <div class="relative mb-4">
                                                <img id="profile-preview" src="{{ asset('storage/customer/profile/' . $basic_info->profile_image) }}" alt="Profile" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow">
                                                <div class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-2">
                                                    <input type="file" id="profile-upload" name="profile-upload" class="file-upload-input" accept="image/*">
                                                    <label for="profile-upload" class="file-upload-label">
                                                        <i class="fas fa-camera text-white"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <p class="text-sm text-gray-500 text-center">JPG, GIF or PNG. Max size of 2MB</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Profile Form -->
                                    <div class="md:w-2/3">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label for="first-name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                                <input type="text" id="first-name" name="first-name" value="{{ $basic_info->first_name }}" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            
                                            <div>
                                                <label for="last-name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                                <input type="text" id="last-name" name="last-name" value="{{ $basic_info->last_name }}" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            
                                            <div class="md:col-span-2">
                                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                                <input type="email" id="email" name="email" value="{{ $basic_info->email }}" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            
                                            <div>
                                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                                <input type="tel" id="phone" name="phone" value="{{ $basic_info->phone }}" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            
                                            <div>
                                                <label for="birthday" class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                                                <input type="date" id="birthday" name="birthday" value="{{ $basic_info->birthday }}" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                            </div>
                                            
                                            <div class="md:col-span-2">
                                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio</label>
                                                <textarea id="bio" name="bio" rows="3" 
                                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ $basic_info->bio }}</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6 flex justify-end">
                                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                
                {{-- <?php elseif ($active_tab == 'security'): ?>
                    <!-- Account Security Section -->
                    <div class="settings-section bg-white rounded-lg shadow overflow-hidden mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-medium text-gray-900">Security</h2>
                            <p class="text-sm text-gray-500">Update your password and secure your account.</p>
                        </div>
                        
                        <div class="p-6">
                            <form id="security-form">
                                <div class="space-y-6">
                                    <div>
                                        <label for="current-password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                        <div class="relative">
                                            <input type="password" id="current-password" name="current-password" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-10">
                                            <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="new-password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                        <div class="relative">
                                            <input type="password" id="new-password" name="new-password" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-10">
                                            <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                                    </div>
                                    
                                    <div>
                                        <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                                        <div class="relative">
                                            <input type="password" id="confirm-password" name="confirm-password" 
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 pr-10">
                                            <button type="button" class="absolute right-3 top-2 text-gray-400 hover:text-gray-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4 border-t border-gray-200">
                                        <h3 class="text-md font-medium text-gray-900 mb-3">Two-Factor Authentication</h3>
                                        <div class="settings-card bg-gray-50 p-4 rounded-lg flex items-center justify-between mb-4">
                                            <div class="flex items-center">
                                                <div class="bg-blue-100 p-3 rounded-full mr-4">
                                                    <i class="fas fa-shield-alt text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Two-Factor Authentication</p>
                                                    <p class="text-xs text-gray-500">Add an extra layer of security to your account</p>
                                                </div>
                                            </div>
                                            <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                                Enable
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                                            Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                
                <?php elseif ($active_tab == 'notifications'): ?>
                    <!-- Notification Preferences Section -->
                    <div class="settings-section bg-white rounded-lg shadow overflow-hidden mb-6">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-medium text-gray-900">Notification Preferences</h2>
                            <p class="text-sm text-gray-500">Manage how you receive notifications.</p>
                        </div>
                        
                        <div class="p-6">
                            <form id="notifications-form">
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-md font-medium text-gray-900 mb-3">Email Notifications</h3>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Order Updates</p>
                                                    <p class="text-xs text-gray-500">Order confirmations, shipping updates</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" checked class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Promotional Offers</p>
                                                    <p class="text-xs text-gray-500">Discounts, special offers</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" checked class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Account Activity</p>
                                                    <p class="text-xs text-gray-500">Password changes, security alerts</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" checked class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="pt-4 border-t border-gray-200">
                                        <h3 class="text-md font-medium text-gray-900 mb-3">Push Notifications</h3>
                                        <div class="space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Order Updates</p>
                                                    <p class="text-xs text-gray-500">Get real-time order notifications</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                            
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">Promotional Offers</p>
                                                    <p class="text-xs text-gray-500">Flash sales, limited-time offers</p>
                                                </div>
                                                <label class="relative inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" checked class="sr-only peer">
                                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end">
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                                            Save Preferences
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                
                <?php elseif ($active_tab == 'actions'): ?>
                    <!-- Account Actions Section -->
                    <div class="settings-section bg-white rounded-lg shadow overflow-hidden">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h2 class="text-lg font-medium text-gray-900">Account Actions</h2>
                            <p class="text-sm text-gray-500">Manage your account settings.</p>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="settings-card bg-gray-50 p-4 rounded-lg flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-red-100 p-3 rounded-full mr-4">
                                            <i class="fas fa-sign-out-alt text-red-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Logout of All Devices</p>
                                            <p class="text-xs text-gray-500">Sign out of all active sessions</p>
                                        </div>
                                    </div>
                                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                        Logout All
                                    </button>
                                </div>
                                
                                <div class="settings-card bg-gray-50 p-4 rounded-lg flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-yellow-100 p-3 rounded-full mr-4">
                                            <i class="fas fa-file-export text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Export Data</p>
                                            <p class="text-xs text-gray-500">Download all your personal data</p>
                                        </div>
                                    </div>
                                    <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none">
                                        Request Export
                                    </button>
                                </div>
                                
                                <div class="settings-card bg-gray-50 p-4 rounded-lg flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="bg-red-100 p-3 rounded-full mr-4">
                                            <i class="fas fa-trash-alt text-red-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Delete Account</p>
                                            <p class="text-xs text-gray-500">Permanently delete your account and data</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="confirmAccountDeletion()" class="px-4 py-2 bg-white border border-red-300 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 focus:outline-none">
                                        Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?> --}}
            </div>
        </main>
        </div>
    </div>
    
    <!-- Delete Account Modal -->
    <div id="delete-account-modal" class="hidden fixed inset-0 z-50 overflow-y-auto">
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
                                Delete Account
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to delete your account? This action cannot be undone. All your data will be permanently removed.
                                </p>
                            </div>
                            <div class="mt-4">
                                <label for="delete-confirm" class="block text-sm font-medium text-gray-700 mb-1">Type "DELETE" to confirm</label>
                                <input type="text" id="delete-confirm" name="delete-confirm" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="deleteAccount()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                        Delete Account
                    </button>
                    <button type="button" onclick="closeDeleteAccountModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>


    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Profile picture upload
            const profileUpload = document.getElementById('profile-upload');
            if (profileUpload) {
                profileUpload.addEventListener('change', function(e) {
                    if (e.target.files && e.target.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            const preview = document.getElementById('profile-preview');
                            if (preview) {
                                preview.src = event.target.result;
                            }
                        };
                        reader.readAsDataURL(e.target.files[0]);
                    }
                });
            }
            
            // Toggle password visibility
            document.querySelectorAll('[type="password"] + button').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');
                    if (input && icon) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            });
            
            // Form submissions
            const profileForm = document.getElementById('profile-form');
            if (profileForm) {
                profileForm.addEventListener('submit', function(e) {
                    showNotification('Profile updated successfully', 'success');
                });
            }
            
            const securityForm = document.getElementById('security-form');
            if (securityForm) {
                securityForm.addEventListener('submit', function(e) {
                    showNotification('Password updated successfully', 'success');
                });
            }
            
            const notificationsForm = document.getElementById('notifications-form');
            if (notificationsForm) {
                notificationsForm.addEventListener('submit', function(e) {
                    showNotification('Notification preferences saved', 'success');
                });
            }
        });
        
        // Account deletion functions
        function confirmAccountDeletion() {
            document.getElementById('delete-account-modal').classList.remove('hidden');
        }
        
        function closeDeleteAccountModal() {
            document.getElementById('delete-account-modal').classList.add('hidden');
            document.getElementById('delete-confirm').value = '';
        }
        
        function deleteAccount() {
            const confirmText = document.getElementById('delete-confirm').value;
            if (confirmText === 'DELETE') {
                // In a real app, you would make an API call to delete the account
                console.log('Account deletion confirmed');
                showNotification('Account deletion request received', 'info');
                closeDeleteAccountModal();
            } else {
                showNotification('Please type "DELETE" to confirm', 'info');
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

    <!-- Notification  -->
    <script src="../script/customer_notification.js"></script>
</body>
</html>