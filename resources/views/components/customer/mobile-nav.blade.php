@php
    $currentPath = request()->path();
    
    // Check active tab
    $isHome = str_starts_with($currentPath, 'customer/dashboard');
    $isOrders = str_starts_with($currentPath, 'customer/orders');
    $isWishlist = str_starts_with($currentPath, 'customer/wishlist');
    $isNotifications = str_starts_with($currentPath, 'customer/notifications');
    $isProfile = str_starts_with($currentPath, 'customer/profile') || str_starts_with($currentPath, 'customer/profile/edit') || str_starts_with($currentPath, 'customer/edit-profile');
    
    // Fetch unread notifications count
    $unreadNotifications = 0;
    if (Auth::check()) {
        $unreadNotifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->where('is_read', 0)
            ->count();
    }
@endphp

<!-- Mobile Bottom Navigation Bar -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200/80 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] z-[9999] px-2 py-2 flex justify-around items-center pb-safe">
    
    <!-- Tab 1: Home (Dashboard) -->
    <a href="/customer/dashboard" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $isHome ? 'text-[#FF7DA0]' : 'text-gray-400 hover:text-gray-600' }}">
        <div class="relative flex flex-col items-center">
            <i class="fas fa-home text-lg"></i>
            <span class="text-[10px] font-semibold mt-1">Home</span>
            @if($isHome)
                <div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>
            @endif
        </div>
    </a>

    <!-- Tab 2: Cart (My Orders) -->
    <a href="/customer/orders" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $isOrders ? 'text-[#FF7DA0]' : 'text-gray-400 hover:text-gray-600' }}">
        <div class="relative flex flex-col items-center">
            <i class="fas fa-shopping-bag text-lg"></i>
            <span class="text-[10px] font-semibold mt-1">Orders</span>
            @if($isOrders)
                <div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>
            @endif
        </div>
    </a>

    <!-- Tab 3: Wishlist -->
    <a href="/customer/wishlist" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $isWishlist ? 'text-[#FF7DA0]' : 'text-gray-400 hover:text-gray-600' }}">
        <div class="relative flex flex-col items-center">
            <i class="fas fa-heart text-lg"></i>
            <span class="text-[10px] font-semibold mt-1">Wishlist</span>
            @if($isWishlist)
                <div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>
            @endif
        </div>
    </a>

    <!-- Tab 4: Notifications -->
    <a href="/customer/notifications" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline relative {{ $isNotifications ? 'text-[#FF7DA0]' : 'text-gray-400 hover:text-gray-600' }}">
        <div class="relative flex flex-col items-center">
            <i class="fas fa-bell text-lg"></i>
            <!-- Unread Badge count -->
            @if($unreadNotifications > 0)
                <span class="absolute -top-1.5 -right-2 bg-red-500 text-white text-[9px] font-bold w-4.5 h-4.5 rounded-full flex items-center justify-center shadow-sm border border-white">
                    {{ $unreadNotifications }}
                </span>
            @endif
            <span class="text-[10px] font-semibold mt-1">Alerts</span>
            @if($isNotifications)
                <div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>
            @endif
        </div>
    </a>

    <!-- Tab 5: Profile -->
    <a href="/customer/profile" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $isProfile ? 'text-[#FF7DA0]' : 'text-gray-400 hover:text-gray-600' }}">
        <div class="relative flex flex-col items-center">
            <i class="fas fa-user text-lg"></i>
            <span class="text-[10px] font-semibold mt-1">Profile</span>
            @if($isProfile)
                <div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>
            @endif
        </div>
    </a>

</div>

<!-- CSS style for safe-area layout adaptation in modern browser chrome -->
<style>
    .pb-safe {
        padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
    }
</style>

{{-- MJ Guide chatbot (site-wide; @once inside prevents duplicates) --}}
<x-mj-guide />
