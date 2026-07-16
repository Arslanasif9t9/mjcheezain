@php
    $vnCurrentPath = request()->path();
    $vnIsDash = str_starts_with($vnCurrentPath, 'vendor/dashboard');
    $vnIsProducts = str_starts_with($vnCurrentPath, 'vendor/products');
    $vnIsOrders = str_starts_with($vnCurrentPath, 'vendor/orders') || str_starts_with($vnCurrentPath, 'vendor/returns') || str_starts_with($vnCurrentPath, 'vendor/replacements');
    $vnIsWithdraw = str_starts_with($vnCurrentPath, 'vendor/withdraw') || str_starts_with($vnCurrentPath, 'vendor/balance');
    $vnIsProfile = str_starts_with($vnCurrentPath, 'vendor/profile');
@endphp

<!-- Vendor Mobile Bottom Navigation Bar -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200/80 shadow-[0_-4px_16px_rgba(0,0,0,0.06)] z-[9999] px-2 py-2 flex justify-around items-center"
     style="padding-bottom: calc(0.5rem + env(safe-area-inset-bottom));">

    <a href="{{ route('vendor.dashboard') }}" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $vnIsDash ? 'text-[#FF7DA0]' : 'text-gray-400' }}">
        <i class="fas fa-chart-bar text-lg"></i>
        <span class="text-[10px] font-semibold mt-1">Dashboard</span>
        @if($vnIsDash)<div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>@endif
    </a>

    <a href="{{ route('vendor.products') }}" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $vnIsProducts ? 'text-[#FF7DA0]' : 'text-gray-400' }}">
        <i class="fas fa-box text-lg"></i>
        <span class="text-[10px] font-semibold mt-1">Products</span>
        @if($vnIsProducts)<div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>@endif
    </a>

    <!-- MJGuider tab (gradient chip; opens the full-screen chat) -->
    <button type="button" class="mjg-nav-open flex flex-col items-center justify-center flex-1 py-1 bg-transparent border-0 cursor-pointer" aria-label="Open MJGuider chat">
        <span class="relative w-9 h-9 -mt-2 rounded-full flex items-center justify-center text-white"
              style="background:linear-gradient(120deg,#FF7DA0,#FFC275); box-shadow:0 6px 14px rgba(232,93,133,.4);">
            <i class="fas fa-comment-dots text-base"></i>
            <i class="mjg-nav-dot" hidden style="position:absolute;top:-1px;right:-1px;width:12px;height:12px;background:#ef4444;border:2px solid #fff;border-radius:50%;"></i>
        </span>
        <span class="text-[10px] font-bold mt-1" style="color:#E85D85;">MJGuider</span>
    </button>

    <a href="{{ route('vendor.orders') }}" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $vnIsOrders ? 'text-[#FF7DA0]' : 'text-gray-400' }}">
        <i class="fas fa-shopping-cart text-lg"></i>
        <span class="text-[10px] font-semibold mt-1">Orders</span>
        @if($vnIsOrders)<div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>@endif
    </a>

    <a href="{{ route('vendor.withdraw') }}" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $vnIsWithdraw ? 'text-[#FF7DA0]' : 'text-gray-400' }}">
        <i class="fas fa-wallet text-lg"></i>
        <span class="text-[10px] font-semibold mt-1">Withdraw</span>
        @if($vnIsWithdraw)<div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>@endif
    </a>

    <a href="{{ route('vendor.profile') }}" class="flex flex-col items-center justify-center flex-1 py-1 transition-all duration-200 no-underline {{ $vnIsProfile ? 'text-[#FF7DA0]' : 'text-gray-400' }}">
        <i class="fas fa-user text-lg"></i>
        <span class="text-[10px] font-semibold mt-1">Profile</span>
        @if($vnIsProfile)<div class="w-1 h-1 rounded-full bg-[#FF7DA0] mt-0.5 animate-pulse"></div>@endif
    </a>
</div>
<!-- Spacer so the fixed bar never covers content on mobile -->
<div class="h-16 md:hidden" aria-hidden="true"></div>

{{-- MJ Guide chatbot (site-wide; @once inside prevents duplicates) --}}
<x-mj-guide />
