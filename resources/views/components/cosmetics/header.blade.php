@props(['user', 'profile', 'dashboardPage', 'imgPath'])
<!-- The 'sticky top-0' classes make the header stay at the top when scrolling -->
    <header class="sticky top-0 z-50 bg-white shadow-xl/50 shadow-gray-200/50">
        <div class="max-w-full mx-auto px-2 sm:px-6 lg:px-8 py-2 md:py-3">
            <div class="flex items-center justify-between">
                
                <!-- Left Section: Logo/Brand -->
                <div class="flex items-center flex-shrink-0">
                    <!-- Icon placeholder from the image (used Lucide 'Warehouse' for a store feel) -->
                    <img src="{{ asset('img/short_logo.jpeg') }}" class="w-16 h-4">
                    <div>
                        <span class="text-3xl font-bold md:font-extrabold text-gray-900 tracking-tight" style="margin-top: -7px;">MJ Cheezain</span>
                        <p class="" style="font-size: 0.6rem; font-weight: 400; margin: 0 11px;">Elegance in every choice</p>
                    </div>
                </div>
                <button 
                        class="flex-1 w-32 py-3 px-0 bg-black text-white font-semibold rounded-lg transition duration-150 md:hidden" 
                        style="transform: scale(0.8); position: relative; top: 45px; left: 16px;">
                    view all products
                </button>

                <!-- Center Section: Search Bar (Hidden on mobile for better space) -->
                <div class="hidden md:flex flex-grow justify-center mx-4 lg:mx-16">
                    <div class="w-full max-w-2xl relative">
                        <input
                            type="text"
                            placeholder="Search thousands of products..."
                            class="w-full p-3 pl-10 border border-gray-200 rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                        >
                        <!-- Search Icon -->
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    </div>
                </div>

                <!-- Right Section: Navigation Links (Flex-shrink to prioritize search on wider screens) -->
                <nav class="hidden md:flex items-center space-x-6 flex-shrink-0">
                    <a href="/" class="text-gray-600 hover:text-primary font-semibold transition duration-200 p-2 rounded-lg">Home</a>
                    <a href="/product-listing" class="text-gray-600 hover:text-primary font-semibold transition duration-200 p-2 rounded-lg">Product Listings</a>
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                </nav>
            </div>
            
            <!-- Mobile Search Bar (Shown below logo on small screens) -->
            <div class="hidden mt-3 relative">
                <input
                    type="text"
                    placeholder="Search thousands of products..."
                    class="w-full p-3 pl-10 border border-gray-200 rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                >
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
            </div>

            <!-- Mobile Navigation (Simple, optional, could be a hamburger menu instead) -->
            <nav class="hidden flex justify-around mt-3 border-t pt-3 space-x-4">
                <a href="#home" class="text-sm text-gray-600 hover:text-primary font-medium">Home</a>
                <a href="#products" class="text-sm text-gray-600 hover:text-primary font-medium">Products</a>
                <a href="#checkout" class="text-sm text-gray-600 hover:text-primary font-medium">Checkout</a>
            </nav>
        </div>
    </header>