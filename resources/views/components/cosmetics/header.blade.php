@props(['user', 'vendor', 'profile', 'dashboardPage', 'imgPath'])
<!-- The 'sticky top-0' classes make the header stay at the top when scrolling -->
    <style>
        @media (max-width: 767px) {
            .mobile-gradient-header {
                background: linear-gradient(to right, #FF7DA0, #FFC275) !important;
                box-shadow: none !important;
            }
        }
    </style>
<header class="sticky top-0 z-50 bg-white mobile-gradient-header shadow-md">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3">
        <div class="flex items-center justify-between">
            
            <!-- Left Section: Logo/Brand -->
            <div class="flex items-center flex-shrink-0">
                <img src="{{ asset('img/short_logo.jpeg') }}" class="w-10 h-6 sm:w-16 md:w-32 sm:h-8 object-contain">
                <div class="ml-2">
                    <span class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">MJ Cheezain</span>
                    <p class="hidden sm:block text-gray-400" style="font-size: 0.6rem; font-weight: 400; margin-top: -2px;">Elegance in every choice</p>
                </div>
            </div>
            
            <!-- Center Section: Search Bar (Desktop only) -->
            <div class="hidden md:flex flex-grow justify-center mx-4 lg:mx-16">
                <div class="w-full max-w-2xl relative">
                    <input 
                        type="text" 
                        id="search-input"
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
            </div>

            <!-- Right Section: Navigation Links & Auth -->
            <div class="flex items-center space-x-3 flex-shrink-0">
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="/" class="text-gray-700 hover:text-blue-600 font-semibold p-2 rounded-lg transition duration-200">Home</a>
                    <a href="/product-listing" class="text-gray-700 hover:text-blue-600 font-semibold p-2 rounded-lg transition duration-200">Product Listings</a>
                    
                    @if($vendor ?? null)
                        <a href="/vendor-products/{{ $vendor->user_id }}" class="px-4 py-2 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition text-sm">
                            View Store
                        </a>
                    @endif
                </nav>

                <!-- User Profile / Guest Menu (Desktop Only) -->
                <div class="hidden md:block">
                    @auth
                        <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                    @else
                        <x-guest-menu />
                    @endauth
                </div>

                <!-- Mobile Action Buttons -->
                <div class="flex items-center space-x-2 md:hidden">
                    @if($vendor ?? null)
                        <a href="/vendor-products/{{ $vendor->user_id }}" class="px-3 py-1.5 bg-black text-white text-xs font-semibold rounded-lg hover:bg-gray-800 transition">
                            Store
                        </a>
                    @endif
                    <button onclick="toggleMobileMenu()" class="text-[#000000] focus:outline-none p-2 rounded-lg hover:opacity-75 transition-colors" aria-label="Toggle Menu">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Search Bar -->
        <div class="md:hidden mt-3 pb-1">
            <div class="flex items-center w-full bg-[#FFFFFF] rounded-xl p-1.5 pl-3 pr-1.5 shadow-inner border border-white/20">
                <input
                    type="text"
                    placeholder="Search products..."
                    class="w-full bg-transparent text-sm text-gray-800 focus:outline-none placeholder-gray-500"
                    id="search-input-mobile"
                >
                <button type="button" onclick="const input = document.getElementById('search-input-mobile'); if (input) { input.dispatchEvent(new Event('input')); }" class="bg-[#C57614] hover:bg-[#A35F0E] text-white p-2 rounded-lg flex items-center justify-center transition-colors" aria-label="Submit Search">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Backdrop Overlay for Mobile Drawer -->
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-[98]" onclick="toggleMobileMenu()"></div>

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
            <a href="/product-listing" class="block text-gray-700 hover:text-gray-900 font-semibold py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-150">
                <i class="fa-solid fa-list mr-2 text-gray-400"></i> Product Listings
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
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsBtn = document.getElementById('mobile-products-btn');
    let isScrolled = false;

    function handleScroll() {
        if (!productsBtn) return;
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