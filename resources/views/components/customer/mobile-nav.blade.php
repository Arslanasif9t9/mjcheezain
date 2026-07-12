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

<!-- Mobile Bottom Navigation Bar (Floating Levelled Dock with Premium Micro-Animations) -->
<div class="md:hidden fixed bottom-4 left-0 right-0 z-[9999] px-4 pointer-events-none">
    <div class="max-w-md mx-auto bg-white/85 backdrop-blur-xl border border-white/30 rounded-3xl shadow-[0_16px_36px_rgba(0,0,0,0.1)] px-3 py-2.5 flex justify-around items-center pointer-events-auto">
        
        <!-- Tab 1: Home (Dashboard) -->
        <a href="/customer/dashboard" class="flex flex-col items-center justify-center flex-1 no-underline transition-all duration-300 active:scale-95">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 {{ $isHome ? 'bg-gradient-to-tr from-[#FF7DA0] to-[#FFC275] text-white shadow-md shadow-[#FF7DA0]/30 active-bounce scale-105' : 'bg-slate-50/80 border border-slate-100/50 text-slate-400 hover:bg-slate-100/80 hover:text-slate-600' }}">
                <i class="fas fa-home text-base"></i>
            </div>
            <span class="text-[9px] mt-1.5 tracking-wide transition-all duration-300 {{ $isHome ? 'font-bold text-[#FF7DA0] scale-105' : 'font-semibold text-slate-400' }}">Home</span>
        </a>

        <!-- Tab 2: Orders (Cart) -->
        <a href="/customer/orders" class="flex flex-col items-center justify-center flex-1 no-underline transition-all duration-300 active:scale-95">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 {{ $isOrders ? 'bg-gradient-to-tr from-[#FF7DA0] to-[#FFC275] text-white shadow-md shadow-[#FF7DA0]/30 active-bounce scale-105' : 'bg-slate-50/80 border border-slate-100/50 text-slate-400 hover:bg-slate-100/80 hover:text-slate-600' }}">
                <i class="fas fa-shopping-bag text-base"></i>
            </div>
            <span class="text-[9px] mt-1.5 tracking-wide transition-all duration-300 {{ $isOrders ? 'font-bold text-[#FF7DA0] scale-105' : 'font-semibold text-slate-400' }}">Orders</span>
        </a>

        <!-- Tab 3: Wishlist -->
        <a href="/customer/wishlist" class="flex flex-col items-center justify-center flex-1 no-underline transition-all duration-300 active:scale-95">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 {{ $isWishlist ? 'bg-gradient-to-tr from-[#FF7DA0] to-[#FFC275] text-white shadow-md shadow-[#FF7DA0]/30 active-bounce scale-105' : 'bg-slate-50/80 border border-slate-100/50 text-slate-400 hover:bg-slate-100/80 hover:text-slate-600' }}">
                <i class="fas fa-heart text-base"></i>
            </div>
            <span class="text-[9px] mt-1.5 tracking-wide transition-all duration-300 {{ $isWishlist ? 'font-bold text-[#FF7DA0] scale-105' : 'font-semibold text-slate-400' }}">Wishlist</span>
        </a>

        <!-- Tab 4: Notifications (Alerts) -->
        <a href="/customer/notifications" class="flex flex-col items-center justify-center flex-1 no-underline transition-all duration-300 active:scale-95">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 relative {{ $isNotifications ? 'bg-gradient-to-tr from-[#FF7DA0] to-[#FFC275] text-white shadow-md shadow-[#FF7DA0]/30 active-bounce scale-105' : 'bg-slate-50/80 border border-slate-100/50 text-slate-400 hover:bg-slate-100/80 hover:text-slate-600' }}">
                <i class="fas fa-bell text-base"></i>
                <!-- Badge count -->
                @if($unreadNotifications > 0)
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[8px] font-bold w-4 h-4 rounded-full flex items-center justify-center border border-white {{ $isNotifications ? 'animate-bounce' : '' }}">
                        {{ $unreadNotifications }}
                    </span>
                @endif
            </div>
            <span class="text-[9px] mt-1.5 tracking-wide transition-all duration-300 {{ $isNotifications ? 'font-bold text-[#FF7DA0] scale-105' : 'font-semibold text-slate-400' }}">Alerts</span>
        </a>

        <!-- Tab 5: Profile -->
        <a href="/customer/profile" class="flex flex-col items-center justify-center flex-1 no-underline transition-all duration-300 active:scale-95">
            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 {{ $isProfile ? 'bg-gradient-to-tr from-[#FF7DA0] to-[#FFC275] text-white shadow-md shadow-[#FF7DA0]/30 active-bounce scale-105' : 'bg-slate-50/80 border border-slate-100/50 text-slate-400 hover:bg-slate-100/80 hover:text-slate-600' }}">
                <i class="fas fa-user text-base"></i>
            </div>
            <span class="text-[9px] mt-1.5 tracking-wide transition-all duration-300 {{ $isProfile ? 'font-bold text-[#FF7DA0] scale-105' : 'font-semibold text-slate-400' }}">Profile</span>
        </a>

    </div>
</div>

<!-- Navigation Styles & Keyframes -->
<style>
    .pb-safe {
        padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));
    }
    
    @keyframes activeBounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-2px);
        }
    }
    
    .active-bounce i {
        animation: activeBounce 2s ease-in-out infinite;
    }
</style>
