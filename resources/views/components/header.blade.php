@props(['user', 'profile', 'dashboardPage', 'imgPath'])

    {{-- <div id="app" class="min-h-screen"> --}}
        
        <!-- Navbar Header -->
        <header class="bg-white sticky top-0 z-10" style="margin-top: -20px"> <!-- // shadow-sm border-b border-gray-200 -->
            <div class="max-w-full mx-auto px-8 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
                
                <!-- Search Bar (Left) -->
                <div class="flex items-center w-full max-w-lg bg-gray-50 rounded-lg p-2 mr-6 shadow-inner">
                    <input type="text" placeholder="Search cosmetics, apparel, accessories..." 
                           class="w-full bg-transparent text-sm text-gray-700 focus:outline-none placeholder-gray-400" id="search-input">
                    <!-- Search Icon (using SVG for simplicity) -->
                    <svg class="w-5 h-5 text-gray-500 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <!-- In the file 'E-commerce Footer:footer_section.html' -->
                <script>
                    // Since the original code was a full HTML page, and the query refers to a header, 
                    // I am including a simple, functional JavaScript block to toggle the dropdown visibility.
                    function toggleDropdown(elementId) {
                        const dropdown = document.getElementById(elementId);
                        dropdown.classList.toggle('hidden');
                    }

                    // Close dropdowns when clicking outside
                    document.addEventListener('click', (event) => {
                        ['categories-dropdown', 'bell-dropdown', 'more-dropdown'].forEach(id => {
                            const dropdown = document.getElementById(id);
                            const button = document.querySelector(`[onclick*='${id}']`);
                            if (dropdown && button) {
                                // Check if the click is outside the button and outside the dropdown
                                if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                                    dropdown.classList.add('hidden');
                                }
                            }
                        });
                    });
                </script>
                <!-- Actions (Right) -->
                <div class="flex items-center space-x-3 ml-6">
                    <!-- Navigation Links (Center) -->
                    <nav class="hidden md:flex space-x-8 text-sm font-medium text-gray-600 mr-8 items-center">
                        <!-- Dropdown 1: Categories -->
                        <div class="relative">
                            <button onclick="toggleDropdown('categories-dropdown')" class="flex items-center hover:text-gray-900 transition duration-150 focus:outline-none">
                                Categories
                                <!-- Down arrow icon -->
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="categories-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cosmetics</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Skincare</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fragrances</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Accessories</a>
                            </div>
                        </div>

                        <!-- Normal Links -->
                        <a href="/customer/wishlist" class="hover:text-gray-900 transition duration-150">Wishlist</a>
                        <a href="/cart" class="hover:text-gray-900 transition duration-150">View Card</a>

                        <!-- Dropdown 2: Bell Icon (Notifications) -->
                        <div class="relative">
                            <button onclick="toggleDropdown('bell-dropdown')" class="p-1 hover:text-gray-900 transition duration-150 focus:outline-none">
                                <i class="fa-solid fa-bell text-lg"></i>
                            </button>
                            <div id="bell-dropdown" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                                <p class="px-4 py-2 text-sm font-semibold text-gray-900 border-b">Notifications</p>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 truncate">Your order #1234 has shipped.</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 truncate">New discount available!</a>
                                <a href="#" class="block px-4 py-2 text-sm text-center text-indigo-600 hover:bg-gray-100">View All</a>
                            </div>
                        </div>

                        <!-- Dropdown 3: More -->
                        <div class="relative">
                            <button onclick="toggleDropdown('more-dropdown')" class="flex items-center hover:text-gray-900 transition duration-150 focus:outline-none">
                                More
                                <!-- Down arrow icon -->
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="more-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Blog</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Help Center</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Partner Program</a>
                            </div>
                        </div>
                        
                    </nav>
                    {{-- <button class="px-4 py-2 bg-gray-800 text-white text-sm font-semibold rounded-lg hover:bg-gray-700 transition duration-150 shadow-md">
                        Sign Out
                    </button> --}}
                    <!-- Avatar Placeholder -->
                     <!-- User Authentication Section -->
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="max-w-full mx-auto px-8 sm:px-6 lg:px-8 py-8">
            
            <!-- Title Section -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6 flex align-center">
                <div>
                    <img src="{{ asset('img/short_logo.jpeg') }}" class="w-32 h-8" style="margin-top: -7px;">
                    <p class="" style="font-size: 0.6rem; font-weight: 400; margin-top: -10px;">Elegance in every choice</p>
                </div>
                <span>MJ Cheezain</span>
            </h1>

            <!-- Filter Tags -->
            <div class="flex flex-wrap gap-2 mb-10">
                <!-- Utility function for button styles to keep it DRY (Note: x-init is decorative placeholder here) -->
                <script>
                    function filterButton(text, active = false) {
                        const baseClasses = "px-4 py-1 text-sm font-medium rounded-full transition duration-200 shadow-sm";
                        if (active) {
                            return `<button class="${baseClasses} bg-gray-900 text-white">${text}</button>`;
                        } else {
                            return `<button class="${baseClasses} bg-white text-gray-700 hover:bg-gray-200 border border-gray-200">${text}</button>`;
                        }
                    }
                </script>
                <div class="flex flex-wrap gap-2" x-init>
                    <button class="px-4 py-1 text-sm font-medium rounded-full bg-gray-900 text-white transition duration-200 shadow-sm">All</button>
                    <a href="cosmetics"><button class="px-4 py-1 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm"><span class="PFDI">MJ</span>Cosmetics</button></a>
                    <a href="auto-parts"><button class="px-4 py-1 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm"><span class="PFDI">Auto</span>parts</button></a>
                    {{-- <button class="px-4 py-1 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm">New Arrivals</button>
                    <button class="px-4 py-1 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm">Collections</button>
                    <button class="px-4 py-1 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm">Followed</button>
                    <button class="px-4 py-1 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm">View Activity</button> --}}
                </div>
            </div>

            <!-- Content Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- 1. Large Featured Card (Spans 2 columns on large screens) -->
                <div class="lg:col-span-2 relative h-[503px] rounded-2xl overflow-hidden shadow-xl group cursor-pointer transition transform duration-300">
                    <!-- Zoom Effect Applied Here -->
                    <img src="{{ asset('img/hero-1.jpeg') }}" 
                         alt="Luxury Fragrance" class="w-full h-full object-cover brightness-100 group-hover:brightness-100 group-hover:scale-125 transition duration-500">
                    
                    <!-- Content Overlay -->
                    <div class="absolute inset-0 flex flex-col justify-end p-8 bg-gradient-to-t from-black/50 to-transparent">
                        <h2 class="text-4xl font-light text-white mb-3 tracking-wide drop-shadow-lg">
                            Indulge in Elegance!
                        </h2>
                        <button class="w-fit px-6 py-3 bg-white text-gray-900 text-sm font-bold rounded-xl shadow-lg hover:bg-gray-200 transition duration-300">
                            Shop Now
                        </button>
                    </div>
                </div>
                
                <!-- 2. Column of Smaller Cards (Spans 1 column on large screens) -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Top Small Card: Lipstick -->
                    <div class="relative h-[240px] rounded-2xl overflow-hidden shadow-lg group cursor-pointer transition transform duration-300">
                         <!-- Zoom Effect Applied Here -->
                        <img src="{{ asset('img/hero-2.jpeg') }}" 
                             alt="Red Lipstick" class="w-full h-full object-cover brightness-100 group-hover:brightness-100 group-hover:scale-125 transition duration-500">
                        <!-- Optional Title Overlay -->
                        <div class="absolute top-0 left-0 p-5">
                            <h3 class="text-lg font-semibold text-gray-800 bg-white/70 backdrop-blur-sm px-3 py-1 rounded-lg">
                                Matte Finish
                            </h3>
                        </div>
                    </div>

                    <!-- Bottom Small Card: Apparel/Model -->
                    <div class="relative h-[240px] rounded-2xl overflow-hidden shadow-lg group cursor-pointer transition transform duration-300">
                         <!-- Zoom Effect Applied Here -->
                        <img src="{{ asset('img/hero-3.jpeg') }}" 
                             alt="Summer Apparel" class="w-full h-full object-cover brightness-100 group-hover:brightness-100 group-hover:scale-125 transition duration-500">
                        <!-- Optional Title Overlay -->
                        <div class="absolute inset-x-0 bottom-0 p-5">
                            <h3 class="text-xl font-bold text-white drop-shadow-md">
                                The Coastal Look
                            </h3>
                        </div>
                    </div>

                </div>

            </div>

        </main>

    {{-- </div> --}}



















{{-- <header class="bg-black text-white shadow-lg header-front position-sticky top-0" style="z-index: 1003;">
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

<hr class="my-2"> --}}