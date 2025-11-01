<!-- The 'sticky top-0' classes make the header stay at the top when scrolling -->
    <header class="sticky top-0 z-50 bg-white shadow-xl/50 shadow-gray-200/50">
        <div class="max-w-full mx-auto px-8 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center justify-between">
                
                <!-- Left Section: Logo/Brand -->
                <div class="flex items-center flex-shrink-0">
                    <!-- Icon placeholder from the image (used Lucide 'Warehouse' for a store feel) -->
                     <img src="{{ asset('img/short_logo.jpeg') }}" class="w-24 h-8">
                    <span class="text-2xl font-extrabold text-gray-900 tracking-tight">MJ Cheezdin</span>
                </div>

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
                    <a href="#products" class="text-gray-600 hover:text-primary font-semibold transition duration-200 p-2 rounded-lg">Product Listings</a>
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                </nav>
            </div>
            
            <!-- Mobile Search Bar (Shown below logo on small screens) -->
            <div class="md:hidden mt-3 relative">
                <input
                    type="text"
                    placeholder="Search thousands of products..."
                    class="w-full p-3 pl-10 border border-gray-200 rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                >
                <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
            </div>

            <!-- Mobile Navigation (Simple, optional, could be a hamburger menu instead) -->
            <nav class="md:hidden flex justify-around mt-3 border-t pt-3 space-x-4">
                <a href="#home" class="text-sm text-gray-600 hover:text-primary font-medium">Home</a>
                <a href="#products" class="text-sm text-gray-600 hover:text-primary font-medium">Products</a>
                <a href="#checkout" class="text-sm text-gray-600 hover:text-primary font-medium">Checkout</a>
            </nav>
        </div>
    </header>