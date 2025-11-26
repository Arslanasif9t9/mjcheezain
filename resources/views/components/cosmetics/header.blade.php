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
                    <span class="text-3xl font-bold md:font-extrabold text-gray-900 tracking-tight mt-[-7px]">MJ Cheezain</span>
                    <p class="mx-[11px] md:ml-[-117px] md:mt-[-5px]" style="font-size: 0.6rem; font-weight: 400;">Elegance in every choice</p>
                </div>
            </div>
            
            <a href="/vendor-products/{{ $vendor->user_id ?? null }}"
                id="mobile-products-btn"
                class="flex-1 w-32 py-3 px-0 bg-black text-center text-white font-semibold rounded-lg transition duration-150 md:hidden" 
                style="transform: scale(0.8); position: relative; top: 45px; left: 16px;">
                view all products
            </a>

            <!-- Center Section: Search Bar (Enabled) -->
            <div class="hidden md:flex flex-grow justify-center mx-4 lg:mx-16">
                <div class="w-full max-w-2xl relative">
                    <input 
                        type="text" 
                        placeholder="Search products..." 
                        class="w-full p-3 pl-10 border border-gray-300 rounded-full text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
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

            <!-- Right Section: Navigation Links (Enabled) -->
            <nav class="hidden md:flex items-center space-x-6 flex-shrink-0">
                <a href="/" class="text-gray-700 hover:text-blue-600 font-semibold p-2 rounded-lg transition duration-200">Home</a>
                <a href="/product-listing" class="text-gray-700 hover:text-blue-600 font-semibold p-2 rounded-lg transition duration-200">Product Listings</a>
                
                <!-- User Authentication Section -->
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                <!-- 🔹 END: Enabled Header Buttons -->
            </nav>
        </div>
        
        <!-- Mobile Search Bar (Enabled) -->
        <div class="md:hidden mt-3 relative">
            <input
                type="text"
                placeholder="Search products..."
                class="w-full p-3 pl-10 border border-gray-300 rounded-full text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
            >
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </div>
        </div>

        <!-- Mobile Navigation (Enabled) -->
        <nav class="md:hidden flex justify-around mt-3 border-t pt-3 space-x-4">
            <a href="/" class="text-sm text-gray-700 hover:text-blue-600 font-medium transition duration-200">Home</a>
            <a href="/product-listing" class="text-sm text-gray-700 hover:text-blue-600 font-medium transition duration-200">Products</a>
            <a href="/login" class="text-sm text-gray-700 hover:text-blue-600 font-medium transition duration-200">Login</a>
            <a href="/register" class="text-sm text-gray-700 hover:text-blue-600 font-medium transition duration-200">Sign Up</a>
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

    // Search functionality
    function initializeSearch() {
        const searchInputs = document.querySelectorAll('input[type="text"][placeholder*="Search"]');
        
        searchInputs.forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = this.value.trim();
                    if (searchTerm) {
                        // Redirect to product listing with search parameter
                        window.location.href = `/product-listing?search=${encodeURIComponent(searchTerm)}`;
                    }
                }
            });
        });
    }

    // Dropdown functionality for mobile
    function initializeDropdowns() {
        const dropdownButtons = document.querySelectorAll('.group > button');
        
        dropdownButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                const dropdown = this.nextElementSibling;
                const isHidden = dropdown.classList.contains('hidden');
                
                // Close all other dropdowns
                document.querySelectorAll('.group .absolute').forEach(drop => {
                    if (drop !== dropdown) {
                        drop.classList.add('hidden');
                    }
                });
                
                // Toggle current dropdown
                if (isHidden) {
                    dropdown.classList.remove('hidden');
                } else {
                    dropdown.classList.add('hidden');
                }
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.group .absolute').forEach(dropdown => {
                dropdown.classList.add('hidden');
            });
        });
    }

    // Initial check in case page loads with scroll
    handleScroll();
    
    // Listen for scroll events
    window.addEventListener('scroll', throttleScroll);
    
    // Initialize search and dropdowns
    initializeSearch();
    initializeDropdowns();
});
</script>