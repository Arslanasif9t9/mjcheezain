@props(['user', 'profile', 'dashboardPage', 'imgPath'])

<header class="bg-black text-white shadow-lg header-front position-sticky top-0" style="z-index: 1003;">
    <div class="container mx-auto items-center justify-between px-4 md:px-20 py-0">
        <div class="m-auto grid grid-cols-1 md:grid-cols-3 items-center">
            <img src="{{ asset('img/logo-mj7.png') }}" alt="Company Logo" class="h-16 w-28 justify-self-center md:justify-self-start opacity-0 hidden md:block">
            <img src="{{ asset('img/logo-ss.png') }}" alt="Company Logo" class="h-32 justify-self-center pr-0 md:mr-24 brightness-200">
            <p class="text-right text-sm hidden md:block">
                <span><i class="fa fa-phone"></i> 03**-*******</span> 
                &nbsp; &nbsp; &nbsp; &nbsp; 
                <span><i class="fa fa-envelope"></i> aqi*********@gmail.com</span>
            </p>
        </div>
    </div>
    
    <div class="text-left py-2 bg-gray-800 md:px-16 w-full">
        <p class="text-sm font-bold px-3">
            <a href="/cosmatics" style="color: whitesmoke; text-decoration: none;">
                <i class="fas fa-tools"></i> COSMETICS
            </a>
        </p>
    </div>
</header>

<!-- Search Bar -->
<div class="flex flex-col md:flex-row items-center justify-between space-x-4 px-2 md:mx-16 mt-8 rounded-[4px]">
    <x-search-bar />
    
    <!-- User Authentication Section -->
    @auth
        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
    @else
        <x-guest-menu />
    @endauth
</div>

<hr class="my-2">