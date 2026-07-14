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
    <div id="cos-header-container" class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-3 transition-all duration-300">
        <div class="flex items-center justify-between">

            <!-- Left Section: Hamburger (mobile) + Logo/Brand -->
            <div class="flex items-center flex-shrink-0 min-w-0">
                <button onclick="toggleMobileMenu()" class="md:hidden text-[#000000] focus:outline-none p-1 mr-1.5 hover:opacity-75 transition-colors" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>
                <div id="cos-brand-wrap" class="flex items-center transition-all duration-300 overflow-hidden whitespace-nowrap">
                    <!-- Compact square logo on mobile, wide logo on tablet/desktop -->
                    <img src="{{ asset('img/logo-world-removebg-preview.png') }}" class="sm:hidden w-9 h-9 object-contain flex-shrink-0">
                    <img src="{{ asset('img/short_logo.jpeg') }}" class="hidden sm:block sm:w-16 md:w-32 sm:h-8 object-contain flex-shrink-0">
                    <div class="ml-2">
                        <span class="text-lg sm:text-2xl font-bold text-gray-900 tracking-tight">MJ Cheezain</span>
                        <p class="hidden sm:block text-gray-400" style="font-size: 0.6rem; font-weight: 400; margin-top: -2px;">Elegance in every choice</p>
                    </div>
                </div>
            </div>

            <!-- Inline Mobile Search: expands inside this same row when the search icon is tapped -->
            <div id="mobile-inline-search" class="md:hidden flex-1 min-w-0 mx-1.5 transition-all duration-300 ease-in-out overflow-hidden" style="max-width: 0px; opacity: 0; pointer-events: none;">
                <div class="flex items-center w-full bg-white rounded-full py-1 pl-3 pr-1 shadow-inner border border-white/20">
                    <input type="text" id="search-input-inline" placeholder="Search products..."
                           class="w-full min-w-0 bg-transparent text-sm text-gray-800 focus:outline-none placeholder-gray-500">
                    <button type="button" onclick="collapseMobileSearch()" class="text-gray-500 hover:text-gray-800 p-1.5 flex-shrink-0 focus:outline-none" aria-label="Close Search">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
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
                        <a href="/vendor-products/{{ $vendor->user_id }}" class="px-5 py-2 text-white font-bold rounded-full shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 text-sm" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i class="fa-solid fa-store mr-1.5"></i>View Store
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
                    <!-- Collapsed Search Icon (appears on scroll) -->
                    <button id="mobile-search-trigger" onclick="expandMobileSearch()" class="text-[#000000] focus:outline-none p-1.5 transition-all duration-300 overflow-hidden" style="max-width: 0px; opacity: 0; pointer-events: none;" aria-label="Search">
                        <i class="fa-solid fa-magnifying-glass text-base"></i>
                    </button>
                    @if($vendor ?? null)
                        <a href="/vendor-products/{{ $vendor->user_id }}" class="px-3.5 py-1.5 text-white text-xs font-bold rounded-full shadow-md hover:opacity-90 transition whitespace-nowrap" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%);">
                            <i class="fa-solid fa-store mr-1"></i>Store
                        </a>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Mobile Search Bar (full-width line — hides on scroll) -->
        <div id="mobile-search-line" class="md:hidden mt-3 pb-1 transition-all duration-300 ease-in-out overflow-hidden" style="max-height: 80px; opacity: 1;">
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
<div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-[98]" onclick="toggleMobileMenu()">
    <button class="absolute top-4 left-[17rem] text-white p-2 focus:outline-none" aria-label="Close Menu">
        <i class="fa-solid fa-xmark text-3xl"></i>
    </button>
</div>

<!-- Mobile Drawer Menu -->
<div id="mobile-drawer" class="fixed inset-y-0 left-0 w-64 bg-white z-[99] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden border-r border-gray-100 flex flex-col justify-between">
    <div class="overflow-y-auto flex-1">
        <!-- Drawer Header (ss1-style: Sign in top-right + Browse Brand) -->
        <div class="p-4 pb-5" style="background: linear-gradient(to right, #FF7DA0, #FFC275);">
            <div class="flex justify-end">
                @guest
                    <a href="/login-user?type=customer-login&page={{ request()->path() }}" class="flex items-center gap-1.5 text-gray-900 font-semibold text-sm no-underline hover:opacity-75 transition">
                        Sign in
                        <i class="fa-regular fa-user text-base"></i>
                    </a>
                @else
                    <a href="{{ $dashboardPage }}" class="flex items-center gap-1.5 text-gray-900 font-semibold text-sm no-underline hover:opacity-75 transition truncate max-w-[12rem]">
                        {{ $user->full_name ?? $user->username }}
                        <i class="fa-regular fa-user text-base"></i>
                    </a>
                @endguest
            </div>
            <div class="mt-3 flex items-center">
                <img src="{{ asset('img/short_logo.jpeg') }}" class="w-9 h-9 rounded-full mr-2.5 object-cover border border-white/40">
                <div>
                    <span class="block text-gray-900 font-bold text-base leading-tight">Browse</span>
                    <span class="block text-gray-900 font-extrabold text-2xl leading-tight">MJ Cheezain</span>
                </div>
            </div>
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

<script>
// Mobile scroll header: search line collapses on scroll into an icon;
// tapping the icon expands the input INLINE in the top row with a close X.
(function () {
    let cosSearchExpanded = false;

    function cosIsMobile() {
        const line = document.getElementById('mobile-search-line');
        return line && window.getComputedStyle(line).display !== 'none';
    }

    window.expandMobileSearch = function () {
        const inline = document.getElementById('mobile-inline-search');
        const trigger = document.getElementById('mobile-search-trigger');
        const brand = document.getElementById('cos-brand-wrap');
        if (inline) {
            inline.style.maxWidth = '100%';
            inline.style.opacity = '1';
            inline.style.pointerEvents = 'auto';
        }
        if (trigger) {
            trigger.style.maxWidth = '0px';
            trigger.style.opacity = '0';
            trigger.style.pointerEvents = 'none';
        }
        if (brand) {
            brand.style.maxWidth = '0px';
            brand.style.opacity = '0';
        }
        cosSearchExpanded = true;
        setTimeout(function () {
            const i = document.getElementById('search-input-inline');
            if (i && typeof i.focus === 'function') i.focus();
        }, 300);
    };

    window.collapseMobileSearch = function () {
        const inline = document.getElementById('mobile-inline-search');
        const trigger = document.getElementById('mobile-search-trigger');
        const brand = document.getElementById('cos-brand-wrap');
        if (inline) {
            inline.style.maxWidth = '0px';
            inline.style.opacity = '0';
            inline.style.pointerEvents = 'none';
        }
        if (trigger && cosIsMobile() && window.scrollY > 50) {
            trigger.style.maxWidth = '50px';
            trigger.style.opacity = '1';
            trigger.style.pointerEvents = 'auto';
        }
        if (brand) {
            brand.style.maxWidth = '500px';
            brand.style.opacity = '1';
        }
        cosSearchExpanded = false;
    };

    window.addEventListener('scroll', function () {
        if (!cosIsMobile()) return;
        const line = document.getElementById('mobile-search-line');
        const trigger = document.getElementById('mobile-search-trigger');
        const container = document.getElementById('cos-header-container');

        if (window.scrollY > 50) {
            if (container) {
                container.classList.remove('py-3');
                container.classList.add('py-1.5');
            }
            if (line) {
                line.style.maxHeight = '0px';
                line.style.opacity = '0';
                line.style.marginTop = '0px';
                line.style.pointerEvents = 'none';
            }
            if (!cosSearchExpanded && trigger) {
                trigger.style.maxWidth = '50px';
                trigger.style.opacity = '1';
                trigger.style.pointerEvents = 'auto';
            }
        } else {
            if (container) {
                container.classList.remove('py-1.5');
                container.classList.add('py-3');
            }
            if (line) {
                line.style.maxHeight = '80px';
                line.style.opacity = '1';
                line.style.marginTop = '';
                line.style.pointerEvents = 'auto';
            }
            if (trigger) {
                trigger.style.maxWidth = '0px';
                trigger.style.opacity = '0';
                trigger.style.pointerEvents = 'none';
            }
            if (cosSearchExpanded) window.collapseMobileSearch();
        }
    });

    // Tap anywhere outside the inline search closes it (when scrolled)
    document.addEventListener('click', function (e) {
        if (!cosSearchExpanded) return;
        const inline = document.getElementById('mobile-inline-search');
        const trigger = document.getElementById('mobile-search-trigger');
        if (inline && !inline.contains(e.target) && (!trigger || !trigger.contains(e.target))) {
            window.collapseMobileSearch();
        }
    });
})();
</script>

@once
    <x-auth-popup />
@endonce
