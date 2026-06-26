@props(['user', 'profile', 'dashboardPage', 'imgPath'])

    {{-- <div id="app" class="min-h-screen"> --}}
        
        <!-- Navbar Header -->
        <header class="bg-white sticky top-0 z-50 border-b border-gray-100" style="margin-top: -20px">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
                
                <!-- Left: Hamburger button for Mobile -->
                <div class="flex items-center md:hidden">
                    <button onclick="toggleMobileMenu()" class="text-gray-700 focus:outline-none p-2 rounded-lg hover:bg-gray-100" aria-label="Toggle Menu">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>

                <!-- Search Bar (Left on Desktop, Hidden on Mobile header bar) -->
                <div class="hidden md:flex items-center w-full max-w-lg bg-gray-50 rounded-lg p-2 mr-6 shadow-inner">
                    <input type="text" placeholder="Search cosmetics, apparel, accessories..." 
                           class="w-full bg-transparent text-sm text-gray-700 focus:outline-none placeholder-gray-400" id="search-input">
                    <svg class="w-5 h-5 text-gray-500 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>

                <!-- Center Logo/Title for Mobile -->
                <div class="flex items-center md:hidden">
                    <span class="text-lg font-bold text-gray-900 tracking-tight">MJ Cheezain</span>
                </div>

                <!-- Dropdowns & Auth (Right) -->
                <div class="flex items-center space-x-3">
                    <!-- Navigation Links (Desktop only) -->
                    <nav class="hidden md:flex space-x-6 text-sm font-medium text-gray-600 mr-4 items-center">
                        <!-- Dropdown: Categories -->
                        <div class="relative">
                            <button onclick="toggleDropdown('categories-dropdown')" class="flex items-center hover:text-gray-900 transition duration-150 focus:outline-none">
                                Categories
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="categories-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cosmetics</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Skincare</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fragrances</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Accessories</a>
                            </div>
                        </div>

                        <a href="/customer/wishlist" class="hover:text-gray-900 transition duration-150">Wishlist</a>
                        <a href="/cart" class="hover:text-gray-900 transition duration-150">View Cart</a>

                        <!-- Dropdown: Notifications -->
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

                        <!-- Dropdown: More -->
                        <div class="relative">
                            <button onclick="toggleDropdown('more-dropdown')" class="flex items-center hover:text-gray-900 transition duration-150 focus:outline-none">
                                More
                                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div id="more-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Blog</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Help Center</a>
                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Partner Program</a>
                            </div>
                        </div>
                    </nav>

                    <!-- Profile / Guest auth menus -->
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                </div>
            </div>

            <!-- Mobile Subheader Search Bar (Visible only on Mobile) -->
            <div class="md:hidden px-4 pb-3 pt-1 border-t border-gray-50 bg-white">
                <div class="flex items-center w-full bg-gray-50 rounded-lg p-2 shadow-inner border border-gray-200">
                    <input type="text" placeholder="Search cosmetics, apparel, accessories..." 
                           class="w-full bg-transparent text-xs text-gray-700 focus:outline-none placeholder-gray-400" id="search-input-mobile">
                    <svg class="w-4 h-4 text-gray-400 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
        </header>

        <!-- Backdrop Overlay for Mobile Drawer -->
        <div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-[98] transition-opacity" onclick="toggleMobileMenu()"></div>

        <!-- Mobile Drawer Menu -->
        <div id="mobile-drawer" class="fixed inset-y-0 left-0 w-64 bg-white z-[99] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden border-r border-gray-100 flex flex-col justify-between">
            <div class="overflow-y-auto flex-1">
                <!-- Drawer Header -->
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <div class="flex items-center">
                        <img src="{{ asset('img/short_logo.jpeg') }}" class="w-8 h-8 rounded-full mr-2 object-cover">
                        <span class="font-bold text-gray-900">MJ Cheezain</span>
                    </div>
                    <button onclick="toggleMobileMenu()" class="text-gray-500 focus:outline-none p-2 rounded-lg hover:bg-gray-100">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <!-- Navigation Links inside Drawer -->
                <div class="p-4 space-y-4">
                    <a href="/" class="block text-gray-700 hover:text-gray-900 font-semibold py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-150">
                        <i class="fa-solid fa-house mr-2 text-gray-400"></i> Home
                    </a>
                    
                    <div class="border-t border-gray-100 my-2 pt-2">
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Categories</span>
                        <a href="/cosmetics" class="block text-gray-600 hover:text-gray-900 py-1.5 px-6 rounded-lg hover:bg-gray-50 transition duration-150">Cosmetics</a>
                        <a href="/auto-parts" class="block text-gray-600 hover:text-gray-900 py-1.5 px-6 rounded-lg hover:bg-gray-50 transition duration-150">Auto Parts</a>
                    </div>

                    <div class="border-t border-gray-100 my-2 pt-2">
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Quick Access</span>
                        <a href="/customer/wishlist" class="block text-gray-600 hover:text-gray-900 py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-150">
                            <i class="fa-solid fa-heart mr-2 text-gray-400"></i> Wishlist
                        </a>
                        <a href="/cart" class="block text-gray-600 hover:text-gray-900 py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-150">
                            <i class="fa-solid fa-cart-shopping mr-2 text-gray-400"></i> View Cart
                        </a>
                    </div>
                </div>
            </div>

            <!-- Drawer Footer Auth/Profile -->
            <div class="p-4 border-t border-gray-100 bg-gray-50">
                @auth
                    <div class="flex items-center space-x-3 mb-3">
                        <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/'.$imgPath) }}" alt="Profile">
                        <div class="min-w-0 flex-1">
                            <p class="font-bold text-sm text-gray-900 truncate">{{ $user->full_name ?? $user->username }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ $user->type }}</p>
                        </div>
                    </div>
                    <a href="{{ $dashboardPage }}" class="block w-full text-center py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition duration-150">
                        Go to Dashboard
                    </a>
                @else
                    <div class="space-y-2">
                        <a href="/login-user?type=customer-login&page={{ request()->path() }}" class="block w-full text-center py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-100 transition duration-150">
                            Log In
                        </a>
                        <a href="/login-user?type=customer-signup&page={{ request()->path() }}" class="block w-full text-center py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition duration-150">
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>

        <script>
            // Toggles main categories/profile dropdowns
            function toggleDropdown(elementId) {
                const dropdown = document.getElementById(elementId);
                dropdown.classList.toggle('hidden');
            }

            // Mobile menu drawer toggling
            function toggleMobileMenu() {
                const drawer = document.getElementById('mobile-drawer');
                const overlay = document.getElementById('mobile-overlay');
                const isOpen = !drawer.classList.contains('-translate-x-full');
                
                if (isOpen) {
                    drawer.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    drawer.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                }
            }

            // Close dropdowns when clicking outside
            document.addEventListener('click', (event) => {
                ['categories-dropdown', 'bell-dropdown', 'more-dropdown'].forEach(id => {
                    const dropdown = document.getElementById(id);
                    const button = document.querySelector(`[onclick*='${id}']`);
                    if (dropdown && button) {
                        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                            dropdown.classList.add('hidden');
                        }
                    }
                });
            });
        </script>

        <!-- Main Content Area -->
        <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            <!-- Title Section -->
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 mb-6 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex flex-col">
                    <img src="{{ asset('img/short_logo.jpeg') }}" class="w-32 h-8 object-contain" style="margin-top: -7px;">
                    <p class="text-gray-500" style="font-size: 0.6rem; font-weight: 400; margin-top: -2px;">Elegance in every choice</p>
                </div>
                <span class="hidden sm:inline text-gray-300 font-light">|</span>
                <span>MJ Cheezain</span>
            </h1>

            <!-- Filter Tags (Horizontally scrollable on Mobile, wrapped on Desktop) -->
            <div class="mb-10">
                <div class="flex flex-row overflow-x-auto pb-2 gap-2 whitespace-nowrap md:flex-wrap md:overflow-x-visible md:pb-0 scrollbar-none">
                    <button class="px-4 py-1.5 text-sm font-medium rounded-full bg-gray-900 text-white transition duration-200 shadow-sm">All</button>
                    <a href="/cosmetics" class="no-underline"><button class="px-4 py-1.5 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm"><span class="PFDI">MJ</span> Cosmetics</button></a>
                    <a href="/auto-parts" class="no-underline"><button class="px-4 py-1.5 text-sm font-medium rounded-full bg-white text-gray-700 hover:bg-gray-200 border border-gray-200 transition duration-200 shadow-sm"><span class="PFDI">Auto</span> parts</button></a>
                </div>
            </div>

            <!-- Content Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- 1. Large Featured Card (Responsive height) -->
                <div class="lg:col-span-2 relative h-[250px] sm:h-[350px] lg:h-[503px] rounded-2xl overflow-hidden shadow-xl group cursor-pointer transition transform duration-300">
                    <img src="{{ asset('img/hero-1.jpeg') }}" 
                         alt="Luxury Fragrance" class="w-full h-full object-cover brightness-95 group-hover:scale-110 transition duration-500">
                    
                    <!-- Content Overlay -->
                    <div class="absolute inset-0 flex flex-col justify-end p-6 sm:p-8 bg-gradient-to-t from-black/60 via-black/20 to-transparent">
                        <h2 class="text-2xl sm:text-4xl font-light text-white mb-3 tracking-wide drop-shadow-lg">
                            Indulge in Elegance!
                        </h2>
                        <button onclick="location.href='/cosmetics'" class="w-fit px-5 py-2.5 bg-white text-gray-900 text-sm font-bold rounded-xl shadow-lg hover:bg-gray-200 transition duration-300">
                            Shop Now
                        </button>
                    </div>
                </div>
                
                <!-- 2. Column of Smaller Cards -->
                <div class="lg:col-span-1 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-6">

                    <!-- Top Small Card: Lipstick -->
                    <div class="relative h-[160px] sm:h-[220px] lg:h-[240px] rounded-2xl overflow-hidden shadow-lg group cursor-pointer transition transform duration-300">
                        <img src="{{ asset('img/hero-2.jpeg') }}" 
                             alt="Red Lipstick" class="w-full h-full object-cover brightness-95 group-hover:scale-110 transition duration-500">
                        <div class="absolute top-0 left-0 p-4">
                            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 bg-white/80 backdrop-blur-sm px-3 py-1 rounded-lg">
                                Matte Finish
                            </h3>
                        </div>
                    </div>

                    <!-- Bottom Small Card: Apparel/Model -->
                    <div class="relative h-[160px] sm:h-[220px] lg:h-[240px] rounded-2xl overflow-hidden shadow-lg group cursor-pointer transition transform duration-300">
                        <img src="{{ asset('img/hero-3.jpeg') }}" 
                             alt="Summer Apparel" class="w-full h-full object-cover brightness-95 group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/50 to-transparent">
                            <h3 class="text-lg sm:text-xl font-bold text-white drop-shadow-md">
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