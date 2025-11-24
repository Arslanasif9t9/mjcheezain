@props(['user', 'vendor', 'profile', 'dashboardPage', 'imgPath'])
<!-- The 'sticky top-0' classes make the header stay at the top when scrolling -->
    <header class="sticky top-0 z-50 bg-white shadow-xl/50 shadow-gray-200/50">
        <div class="max-w-full mx-auto px-2 sm:px-6 lg:px-8 py-2 md:py-3">
            <div class="flex items-center justify-between">
                
                <!-- Left Section: Logo/Brand -->
                <div class="flex items-center flex-shrink-0">
                    <!-- Icon placeholder from the image (used Lucide 'Warehouse' for a store feel) -->
                    <img src="{{ asset('img/short_logo.jpeg') }}" class="w-16 md:w-32 h-4 md:h-8 mt-0 md:mt-[-15px]">
                    <div>
                        <span class="text-3xl font-bold md:font-extrabold text-gray-900 tracking-tight mt-[-7px]" style="">MJ Cheezain</span>
                        <p class="mx-[11px] ml-[-70px] md:ml-[-117px] md:mt-[-5px]" style="font-size: 0.6rem; font-weight: 400;;">Elegance in every choice</p>
                    </div>
                </div>
                <a href="/vendor-products/{{ $vendor->user_id ?? null }}"
                    id="mobile-products-btn"
                    class="flex-1 w-32 py-3 px-0 bg-black text-center text-white font-semibold rounded-lg transition duration-150 md:hidden" 
                    style="transform: scale(0.8); position: relative; top: 45px; left: 16px;">
                    view all products
                </a>

                <!-- Center Section: Search Bar (Hidden on mobile for better space) -->
                <div class="hidden md:flex flex-grow justify-center mx-4 lg:mx-16">
                    <div class="w-full max-w-2xl relative">
                        <input 
                        type="text" 
                        placeholder="Search is temporarily unavailable..." 
                        class="w-full p-3 pl-10 border border-gray-300 rounded-full text-gray-500 bg-gray-100 cursor-not-allowed" 
                        disabled
                        >
                        <!-- Search Icon -->
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        </div>
                    </div>
                </div>
                {{-- <div class="hidden md:flex flex-grow justify-center mx-4 lg:mx-16">
                    <div class="w-full max-w-2xl relative">
                        <input
                            type="text"
                            placeholder="Search thousands of products..."
                            class="w-full p-3 pl-10 border border-gray-200 rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition duration-150"
                        >
                        <!-- Search Icon -->
                        <i data-lucide="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    </div>
                </div> --}}

                <!-- Right Section: Navigation Links (Flex-shrink to prioritize search on wider screens) -->
                <nav class="hidden md:flex items-center space-x-6 flex-shrink-0">
                    <a href="/" class="text-gray-400 cursor-not-allowed font-semibold p-2 rounded-lg">Home</a>
                    <a href="/product-listing" class="text-gray-400 cursor-not-allowed font-semibold p-2 rounded-lg">Product Listings</a>
                    
                    <!-- 🔹 START: Disabled Header Buttons -->
                    <div class="text-right flex self-end bg-gray-300 text-gray-500 rounded-[5px] my-1 px-2 py-1 ml-auto">

                        <!-- 🔸 DISABLED SIGN UP DROPDOWN -->
                        <div class="relative group">
                            <button class="text-gray-500 px-3 py-2 text-sm font-medium flex items-center cursor-not-allowed" disabled>
                                <span class="hover-text">Sign Up &nbsp; <i class="fa fa-caret-down"></i></span>
                            </button>

                            <div class="absolute w-48 bg-gray-100 rounded-md shadow-lg py-1 z-10 hidden opacity-50" style="z-index: 100;">
                                <span class="block px-4 py-2 text-sm text-gray-400 text-left cursor-not-allowed">Customer Sign Up</span>
                                <span class="block px-4 py-2 text-sm text-gray-400 text-left cursor-not-allowed">Vendor Sign Up</span>
                            </div>
                        </div>

                        <div class="text-gray-400 mx-1">|</div>

                        <!-- 🔸 DISABLED LOGIN DROPDOWN -->
                        <div class="relative group">
                            <button class="text-gray-500 px-3 py-2 text-sm font-medium flex items-center cursor-not-allowed" disabled>
                                <span class="hover-text">Login &nbsp; <i class="fa fa-caret-down"></i></span>
                            </button>

                            <div class="absolute w-48 bg-gray-100 rounded-md shadow-lg py-1 z-10 hidden opacity-50" style="z-index: 100;">
                                <span class="block px-4 py-2 text-sm text-gray-400 text-left cursor-not-allowed">Customer Login</span>
                                <span class="block px-4 py-2 text-sm text-gray-400 text-left cursor-not-allowed">Vendor Login</span>
                            </div>
                        </div>
                    </div>
                    <!-- 🔹 END: Disabled Header Buttons -->
                </nav>
                {{-- <nav class="hidden md:flex items-center space-x-6 flex-shrink-0">
                    <a href="/" class="text-gray-600 hover:text-primary font-semibold transition duration-200 p-2 rounded-lg">Home</a>
                    <a href="/product-listing" class="text-gray-600 hover:text-primary font-semibold transition duration-200 p-2 rounded-lg">Product Listings</a>
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                </nav> --}}
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsBtn = document.getElementById('mobile-products-btn');
    let isScrolled = false;

    function handleScroll() {
        const scrollPosition = window.scrollY;
        
        if (scrollPosition > 50 && !isScrolled) {
            // User has scrolled down - remove positioning and add animation
            productsBtn.style.position = '';
            productsBtn.style.top = '';
            productsBtn.style.left = '';
            productsBtn.style.transform = 'scale(0.8)';
            productsBtn.style.transition = 'all 0.3s ease-out';
            
            // Optional: Add a subtle fade-in effect
            productsBtn.style.opacity = '0';
            setTimeout(() => {
                productsBtn.style.opacity = '1';
                productsBtn.style.transition = 'opacity 0.3s ease-out';
            }, 10);
            
            isScrolled = true;
            
        } else if (scrollPosition <= 50 && isScrolled) {
            // User scrolled back to top - restore original positioning
            productsBtn.style.position = 'relative';
            productsBtn.style.top = '45px';
            productsBtn.style.left = '16px';
            productsBtn.style.transform = 'scale(0.8)';
            productsBtn.style.transition = 'all 0.3s ease-out';
            
            isScrolled = false;
        }
    }

    // Throttle scroll events for better performance
    let scrollTimeout;
    function throttleScroll() {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(function() {
                scrollTimeout = null;
                handleScroll();
            }, 10);
        }
    }

    // Initial check in case page loads with scroll
    handleScroll();
    
    // Listen for scroll events
    window.addEventListener('scroll', throttleScroll);
});
</script>