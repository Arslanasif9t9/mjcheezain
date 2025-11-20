@props(['basic_info' => null])

<div class="hidden md:flex md:flex-shrink-0">
    <div class="flex flex-col w-64 bg-white border-r border-gray-200">
        <div class="flex items-center justify-center h-16 px-4 bg-blue-600">
            <span class="text-white font-bold text-xl">mjcheezain</span>
        </div>                
        <div class="flex flex-col flex-grow px-4 py-4 overflow-y-auto">
            <div class="flex items-center px-4 py-3 mb-4 bg-gray-100 rounded-lg">
                <img class="w-10 h-10 rounded-full" src="{{ $basic_info->profile_image ?? asset('storage/default_profile.webp') }}" alt="User">
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">{{ $basic_info->first_name ?? '' }} {{ $basic_info->last_name ?? '' }}</p>
                    <p class="text-xs text-gray-500">Gold Member</p>
                </div>
            </div>
            
            <nav class="flex-1 space-y-2">
                <a href="./dashboard.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-900 rounded-lg sidebar-item hover:bg-gray-100 active">
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