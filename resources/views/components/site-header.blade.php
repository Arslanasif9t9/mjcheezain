@props(['user' => null, 'vendor' => null, 'profile' => null, 'dashboardPage' => null, 'imgPath' => null])

{{-- Shared site header: the home-page header used across the whole site.
     Pass :vendor to show the "View Store" button (e.g. single product pages). --}}

    <style>
        @media (max-width: 767px) {
            .mobile-gradient-header {
                background: linear-gradient(to right, #FF7DA0, #FFC275) !important;
            }
        }
        .font-serif-italic {
            font-family: 'PT Serif', Georgia, serif;
            font-style: italic;
        }

        /* Smooth mobile header scroll animation:
           search bar glides up + gently fades, brand name slides in with a
           tiny delay so the two motions feel choreographed, not snappy. */
        #mobile-search-wrapper {
            transition: max-height .22s cubic-bezier(.4, 0, .2, 1),
                        opacity .16s ease,
                        transform .22s cubic-bezier(.4, 0, .2, 1),
                        margin-top .22s cubic-bezier(.4, 0, .2, 1);
            will-change: max-height, opacity, transform;
        }
        #mobile-search-wrapper.search-collapsed {
            transform: translateY(-10px) scale(.97);
            margin-top: 0;
        }
        #mobile-brand-wrapper {
            transition: max-width .2s cubic-bezier(.4, 0, .2, 1),
                        opacity .15s ease,
                        transform .2s cubic-bezier(.4, 0, .2, 1);
            transform: translateX(-6px);
        }
        #mobile-brand-wrapper.brand-visible {
            transition-delay: .05s;
            transform: translateX(0);
        }

        /* Compact header on scroll: much shorter, and the hamburger jumps to
           the right side next to the search icon while the brand name sits
           flush left. */
        #header-container {
            transition: padding .22s cubic-bezier(.4, 0, .2, 1);
        }
        #header-container.header-compact {
            padding-top: .3rem !important;
            padding-bottom: .3rem !important;
        }
        #header-container.header-compact #mobile-menu-btn {
            order: 7;
        }
    </style>

    <!-- Navbar Header -->
    <header class="mobile-gradient-header md:bg-none md:bg-white sticky top-0 left-0 right-0 w-full z-50 border-b border-pink-600/10 md:border-gray-100 transition-all duration-300">
        <div id="header-container" class="max-w-full mx-auto px-2 md:px-6 lg:px-8 py-3.5 md:py-3 flex justify-between items-center transition-all duration-300">

            <!-- Search Bar (Left on Desktop) -->
            <div class="hidden md:flex items-center w-full max-w-lg bg-gray-50 rounded-lg p-2 mr-6 shadow-inner">
                <input type="text" placeholder="Search cosmetics, apparel, accessories..."
                       class="w-full bg-transparent text-sm text-gray-700 focus:outline-none placeholder-gray-400" id="search-input">
                <svg class="w-5 h-5 text-gray-500 cursor-pointer" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>

            <!-- Desktop Links & Auth (Right) -->
            <div class="hidden md:flex items-center space-x-3 ml-auto">
                <nav class="flex space-x-6 text-sm font-medium text-gray-600 mr-4 items-center">
                    <div class="relative">
                        <button onclick="toggleDropdown('categories-dropdown-desktop')" class="flex items-center hover:text-gray-900 transition duration-150 focus:outline-none">
                            Categories
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="categories-dropdown-desktop" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Cosmetics</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Skincare</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Fragrances</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Accessories</a>
                        </div>
                    </div>

                    <a href="/customer/wishlist" class="hover:text-gray-900 transition duration-150">Wishlist</a>
                    <a href="/cart" class="hover:text-gray-900 transition duration-150">View Cart</a>

                    <div class="relative">
                        <button onclick="toggleDropdown('bell-dropdown-desktop')" class="p-1 hover:text-gray-900 transition duration-150 focus:outline-none">
                            <i class="fa-solid fa-bell text-lg"></i>
                        </button>
                        <div id="bell-dropdown-desktop" class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                            <p class="px-4 py-2 text-sm font-semibold text-gray-900 border-b">Notifications</p>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 truncate">Your order #1234 has shipped.</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 truncate">New discount available!</a>
                            <a href="#" class="block px-4 py-2 text-sm text-center text-[#E85D85] hover:bg-gray-100">View All</a>
                        </div>
                    </div>

                    <div class="relative">
                        <button onclick="toggleDropdown('more-dropdown-desktop')" class="flex items-center hover:text-gray-900 transition duration-150 focus:outline-none">
                            More
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <div id="more-dropdown-desktop" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 z-10 hidden border border-gray-100">
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Blog</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Help Center</a>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Partner Program</a>
                        </div>
                    </div>
                </nav>

                @auth
                    <x-user-profile :user="$user" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />
                @else
                    <x-guest-menu />
                @endauth
            </div>

            <!-- Mobile Header Layout (Visible only on Mobile) -->
            <div id="mobile-search-container" class="flex md:hidden flex-col w-full space-y-2.5 transition-all duration-300">
                <!-- Top Row: Hamburger + Brand Name + Auth Links.
                     All items are direct flex children so CSS `order` can move
                     the hamburger to the right side in the compact (scrolled) state. -->
                <div class="flex items-center w-full px-1 gap-1.5">
                    <!-- Hamburger Menu Toggle Button (moves to the right on scroll) -->
                    <button id="mobile-menu-btn" onclick="toggleMobileMenu()" class="text-[#000000] focus:outline-none p-1 hover:opacity-75 transition-colors" aria-label="Toggle Menu">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Brand Text: hidden by default, sits flush left on scroll -->
                    <div id="mobile-brand-wrapper" class="flex items-center transition-all duration-300 opacity-0 max-w-0 overflow-hidden pointer-events-none">
                        <span class="font-serif-italic font-bold text-gray-900 text-sm whitespace-nowrap">MJ Cheezain</span>
                    </div>

                    <!-- Inline Search: expands inside this same row when the search icon is tapped -->
                    <div id="mobile-inline-search" class="flex-1 min-w-0 mx-1.5 transition-all duration-300 ease-in-out overflow-hidden" style="max-width: 0px; opacity: 0; pointer-events: none; flex-grow: 999;">
                        <div class="flex items-center w-full bg-white rounded-full py-1 pl-3 pr-1 shadow-inner border border-white/20">
                            <input type="text" id="search-input-inline" placeholder="Search products..."
                                   class="w-full min-w-0 bg-transparent text-sm text-gray-800 focus:outline-none placeholder-gray-500">
                            <button type="button" onclick="collapseMobileSearch()" class="text-gray-500 hover:text-gray-800 p-1.5 flex-shrink-0 focus:outline-none" aria-label="Close Search">
                                <i class="fa-solid fa-xmark text-base"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Spacer: keeps the right-side group pushed to the right edge -->
                    <div class="flex-1"></div>

                    <!-- Right side: Store button + Search trigger (on scroll) + Sign Up / Login / Profile dropdowns -->
                    <div class="flex items-center text-xs sm:text-sm font-semibold text-gray-800 space-x-1.5">
                        <!-- Collapsed Search Icon (Visible on Scroll) -->
                        <button id="mobile-search-trigger" onclick="expandMobileSearch()" class="text-[#000000] focus:outline-none p-1.5 transition-all duration-300" style="max-width: 0px; opacity: 0; pointer-events: none; overflow: hidden;" aria-label="Search">
                            <i class="fa-solid fa-magnifying-glass text-base"></i>
                        </button>

                        <!-- Auth links wrapper (hidden on scroll) -->
                        <div id="mobile-auth-wrapper" class="flex items-center space-x-1.5 transition-all duration-300 opacity-1 pointer-events-auto">
                            @guest
                                <!-- Sign Up Dropdown -->
                                <div class="relative">
                                    <button onclick="toggleDropdown('mobile-signup-dropdown')" class="flex items-center text-gray-900 hover:text-pink-600 transition-colors focus:outline-none py-1">
                                        Sign Up
                                        <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div id="mobile-signup-dropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50">
                                        <a href="{{ url('login-user?type=customer-signup&page=' . request()->path()) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors no-underline">Customer Register</a>
                                        <a href="{{ url('login-user?type=vendor-signup&page=' . request()->path()) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors no-underline">Vendor Register</a>
                                    </div>
                                </div>

                                <span class="text-gray-400 font-light">|</span>

                                <!-- Login Dropdown -->
                                <div class="relative">
                                    <button onclick="toggleDropdown('mobile-login-dropdown')" class="flex items-center text-gray-900 hover:text-pink-600 transition-colors focus:outline-none py-1">
                                        Login
                                        <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div id="mobile-login-dropdown" class="hidden absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50">
                                        <a href="{{ url('login-user?type=customer-login&page=' . request()->path()) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors no-underline">Customer Login</a>
                                        <a href="{{ url('login-user?type=vendor-login&page=' . request()->path()) }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors no-underline">Vendor Login</a>
                                    </div>
                                </div>
                            @else
                                <!-- Logged in state: profile picture (app-like), dropdown on tap -->
                                <div class="relative">
                                    <button onclick="toggleDropdown('mobile-user-dropdown')" class="flex items-center focus:outline-none py-0.5" aria-label="Account menu">
                                        <img src="{{ $imgPath ? asset('storage/'.$imgPath) : asset('storage/default_profile.webp') }}" alt="Account"
                                             class="w-8 h-8 rounded-full object-cover ring-2 ring-white/80 shadow-md">
                                        <svg class="w-3 h-3 ml-1 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                    <div id="mobile-user-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 border border-gray-100 z-50">
                                        <div class="px-4 py-2 border-b border-gray-100 flex items-center gap-2.5">
                                            <img src="{{ $imgPath ? asset('storage/'.$imgPath) : asset('storage/default_profile.webp') }}" alt=""
                                                 class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-gray-900 truncate m-0">{{ $user->full_name ?? ($user->username ?? 'My Account') }}</p>
                                                <p class="text-[10px] text-gray-400 capitalize m-0">{{ $user->type ?? '' }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ url(Auth::user()->type . '/dashboard') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors no-underline"><i class="fa-solid fa-gauge-high mr-2 text-gray-400"></i>Dashboard</a>
                                        <a href="{{ url('logout') }}" class="block px-4 py-2 text-xs text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors no-underline"><i class="fa-solid fa-sign-out-alt mr-2 text-gray-400"></i>Logout</a>
                                    </div>
                                </div>
                            @endguest
                        </div>
                    </div>
                </div>

                <!-- Bottom Row: Search Bar Wrapper (Takes full space below) -->
                <div id="mobile-search-wrapper" class="w-full transition-all duration-300 ease-in-out origin-top overflow-hidden" style="max-height: 80px; opacity: 1; pointer-events: auto;">
                    <div class="flex items-center w-full bg-[#FFFFFF] rounded-xl p-1.5 pl-3 pr-1.5 shadow-inner border border-white/20">
                        <input type="text" placeholder="Search cosmetics, apparel, accessories..."
                               class="w-full bg-transparent text-sm text-gray-800 focus:outline-none placeholder-gray-500" id="search-input-mobile">
                        <button type="button" onclick="const input = document.getElementById('search-input-mobile'); if (input) { input.dispatchEvent(new Event('input')); }" class="bg-[#C57614] hover:bg-[#A35F0E] text-white p-2 rounded-lg flex items-center justify-center transition-colors" aria-label="Submit Search">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Backdrop Overlay for Mobile Drawer (close X sits on the overlay) -->
    <div id="mobile-overlay" class="hidden fixed inset-0 bg-black/50 z-[10000]" onclick="toggleMobileMenu()">
        <button class="absolute top-4 left-[17rem] text-white p-2 focus:outline-none" aria-label="Close Menu">
            <i class="fa-solid fa-xmark text-3xl"></i>
        </button>
    </div>

    <!-- Mobile Drawer Menu -->
    <div id="mobile-drawer" class="fixed inset-y-0 left-0 w-64 bg-white z-[10001] shadow-2xl transform -translate-x-full transition-transform duration-300 ease-in-out md:hidden border-r border-gray-100 flex flex-col justify-between">
        <div class="overflow-y-auto flex-1">
            <!-- Drawer Header (gradient: Sign in top-right + Browse Brand) -->
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
                <div class="mt-3">
                    <span class="block text-gray-900/80 font-semibold text-[11px] uppercase tracking-[0.25em] leading-tight">Browse</span>
                    <span class="block text-gray-900 font-bold text-3xl leading-tight" style="font-family: 'PT Serif', Georgia, serif; font-style: italic; letter-spacing: 0.5px;">MJ Cheezain</span>
                    <div class="mt-1.5 w-12 h-[3px] rounded-full bg-white/60"></div>
                </div>
            </div>

            <!-- Navigation Links inside Drawer -->
            <div class="p-4 space-y-4">
                <a href="/" class="block text-gray-700 hover:text-gray-900 font-semibold py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-150 no-underline">
                    <i class="fa-solid fa-house mr-2 text-gray-400"></i> Home
                </a>
                <a href="/product-listing" class="block text-gray-700 hover:text-gray-900 font-semibold py-2 px-3 rounded-lg hover:bg-gray-50 transition duration-150 no-underline">
                    <i class="fa-solid fa-list mr-2 text-gray-400"></i> Product Listings
                </a>

                <div class="border-t border-gray-100 my-2 pt-2">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Categories</span>
                    <a href="/cosmetics" class="block text-gray-600 hover:text-gray-900 py-1.5 px-6 rounded-lg hover:bg-gray-50 transition duration-150 no-underline">Cosmetics</a>
                    <a href="/auto-parts" class="block text-gray-600 hover:text-gray-900 py-1.5 px-6 rounded-lg hover:bg-gray-50 transition duration-150 no-underline">Auto Parts</a>
                </div>

                <div class="border-t border-gray-100 my-2 pt-2">
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-2">Quick Access</span>
                    <a href="/customer/wishlist" class="block text-gray-600 hover:text-gray-900 py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-150 no-underline">
                        <i class="fa-solid fa-heart mr-2 text-gray-400"></i> Wishlist
                    </a>
                    <a href="/cart" class="block text-gray-600 hover:text-gray-900 py-2 px-6 rounded-lg hover:bg-gray-50 transition duration-150 no-underline">
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
                <a href="{{ $dashboardPage }}" class="block w-full text-center py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition duration-150 no-underline">
                    Go to Dashboard
                </a>
            @else
                <div class="space-y-2">
                    <a href="/login-user?type=customer-login&page={{ request()->path() }}" class="block w-full text-center py-2 border border-gray-300 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-100 transition duration-150 no-underline">
                        Log In
                    </a>
                    <a href="/login-user?type=customer-signup&page={{ request()->path() }}" class="block w-full text-center py-2 bg-gray-900 text-white text-sm font-semibold rounded-lg hover:bg-gray-800 transition duration-150 no-underline">
                        Register
                    </a>
                </div>
            @endauth
        </div>
    </div>

    <script>
        // Toggles dropdowns on desktop
        function toggleDropdown(elementId) {
            const dropdown = document.getElementById(elementId);
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        // Toggles mobile menu drawer
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-drawer');
            const overlay = document.getElementById('mobile-overlay');
            if (drawer && overlay) {
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
        }

        // Mobile Search Expand/Collapse Logic
        let isSearchExpanded = false;

        // Opens the search INLINE inside the top row (icon turns into an input + close X)
        function expandMobileSearch() {
            const inline = document.getElementById('mobile-inline-search');
            const trigger = document.getElementById('mobile-search-trigger');
            const brandWrapper = document.getElementById('mobile-brand-wrapper');

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
            if (brandWrapper) {
                brandWrapper.style.maxWidth = '0px';
                brandWrapper.style.opacity = '0';
                brandWrapper.style.pointerEvents = 'none';
            }
            isSearchExpanded = true;

            setTimeout(() => {
                const inlineInput = document.getElementById('search-input-inline');
                if (inlineInput && typeof inlineInput.focus === 'function') {
                    inlineInput.focus();
                }
            }, 300);
        }

        function collapseMobileSearch() {
            const inline = document.getElementById('mobile-inline-search');
            const trigger = document.getElementById('mobile-search-trigger');
            const brandWrapper = document.getElementById('mobile-brand-wrapper');

            if (inline) {
                inline.style.maxWidth = '0px';
                inline.style.opacity = '0';
                inline.style.pointerEvents = 'none';
            }
            if (trigger && window.scrollY > 50) {
                trigger.style.maxWidth = '50px';
                trigger.style.opacity = '1';
                trigger.style.pointerEvents = 'auto';
            }
            if (brandWrapper && window.scrollY > 50) {
                brandWrapper.style.maxWidth = '250px';
                brandWrapper.style.opacity = '1';
                brandWrapper.style.pointerEvents = 'auto';
            }
            isSearchExpanded = false;
        }

        // Header elevation shadow on scroll (adds depth once content scrolls underneath)
        window.addEventListener('scroll', function() {
            const header = document.querySelector('header.mobile-gradient-header');
            if (!header) return;
            if (window.scrollY > 10) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });

        // Mobile header scroll animation — smooth and jitter-free.
        // rAF-throttled so style writes happen at most once per frame, and a
        // hysteresis band (collapse past 70px, expand under 25px) so the header
        // never flickers when the user hovers around a single threshold.
        const HEADER_COLLAPSE_AT = 70;
        const HEADER_EXPAND_AT = 25;
        let headerCompact = null;
        let scrollTickPending = false;

        function applyHeaderState(compact) {
            if (headerCompact === compact) return;
            headerCompact = compact;

            const wrapper = document.getElementById('mobile-search-wrapper');
            const trigger = document.getElementById('mobile-search-trigger');
            const headerContainer = document.getElementById('header-container');
            const brandWrapper = document.getElementById('mobile-brand-wrapper');
            const authWrapper = document.getElementById('mobile-auth-wrapper');

            if (compact) {
                if (headerContainer) {
                    headerContainer.classList.add('header-compact');
                }
                // The full-width search line glides up and fades out
                if (wrapper) {
                    wrapper.classList.add('search-collapsed');
                    wrapper.style.maxHeight = '0px';
                    wrapper.style.opacity = '0';
                    wrapper.style.pointerEvents = 'none';
                }
                if (!isSearchExpanded) {
                    collapseMobileSearch();
                }
                if (brandWrapper && !isSearchExpanded) {
                    brandWrapper.classList.add('brand-visible');
                    brandWrapper.style.maxWidth = '250px';
                    brandWrapper.style.opacity = '1';
                    brandWrapper.style.pointerEvents = 'auto';
                }
                if (authWrapper) {
                    authWrapper.style.maxWidth = '0px';
                    authWrapper.style.opacity = '0';
                    authWrapper.style.pointerEvents = 'none';
                }
            } else {
                if (headerContainer) {
                    headerContainer.classList.remove('header-compact');
                }
                isSearchExpanded = false;
                const inlineSearch = document.getElementById('mobile-inline-search');
                if (inlineSearch) {
                    inlineSearch.style.maxWidth = '0px';
                    inlineSearch.style.opacity = '0';
                    inlineSearch.style.pointerEvents = 'none';
                }
                if (wrapper) {
                    wrapper.classList.remove('search-collapsed');
                    wrapper.style.maxHeight = '80px';
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';
                }
                if (trigger) {
                    trigger.style.maxWidth = '0px';
                    trigger.style.opacity = '0';
                    trigger.style.pointerEvents = 'none';
                }
                if (brandWrapper) {
                    brandWrapper.classList.remove('brand-visible');
                    brandWrapper.style.maxWidth = '0px';
                    brandWrapper.style.opacity = '0';
                    brandWrapper.style.pointerEvents = 'none';
                }
                if (authWrapper) {
                    authWrapper.style.maxWidth = '200px';
                    authWrapper.style.opacity = '1';
                    authWrapper.style.pointerEvents = 'auto';
                }
            }
        }

        // Direction-aware WITHOUT jitter:
        // Collapsing/expanding the sticky header changes its height, which
        // nudges scrollY and used to re-trigger the opposite state — the
        // searchbar visibly bounced up/down on tiny scrolls. Two guards fix it:
        //   1. ACCUMULATED direction: the user must scroll >= 14px in ONE
        //      direction before the state may flip (single-pixel blips reset).
        //   2. TRANSITION LOCK: after any state change, direction flips are
        //      ignored for 400ms so the animation itself can't re-trigger us.
        let lastScrollY = window.scrollY || 0;
        let scrollAcc = 0;
        let stateLockUntil = 0;

        window.addEventListener('scroll', function() {
            if (scrollTickPending) return;
            scrollTickPending = true;
            requestAnimationFrame(function() {
                scrollTickPending = false;
                const mobileSearchContainer = document.getElementById('mobile-search-container');
                const isMobile = mobileSearchContainer && window.getComputedStyle(mobileSearchContainer).display !== 'none';
                if (!isMobile) return;

                const scrollY = window.scrollY || window.pageYOffset || document.documentElement.scrollTop;
                const dy = scrollY - lastScrollY;
                lastScrollY = scrollY;

                // Hard positions always win (top of page = full header)
                if (scrollY < HEADER_EXPAND_AT) {
                    applyHeaderState(false);
                    scrollAcc = 0;
                    return;
                }

                // During the collapse/expand animation, ignore direction noise
                if (performance.now() < stateLockUntil) return;

                // Accumulate movement; a direction change resets the counter
                if ((dy > 0 && scrollAcc < 0) || (dy < 0 && scrollAcc > 0)) scrollAcc = 0;
                scrollAcc += dy;

                if (scrollAcc > 14 && scrollY > HEADER_COLLAPSE_AT && headerCompact !== true) {
                    applyHeaderState(true);
                    scrollAcc = 0;
                    stateLockUntil = performance.now() + 400;
                } else if (scrollAcc < -14 && headerCompact !== false) {
                    applyHeaderState(false);
                    scrollAcc = 0;
                    stateLockUntil = performance.now() + 400;
                }
            });
        }, { passive: true });

        // Collapse search if clicking outside of it when scrolled down
        document.addEventListener('click', function(event) {
            const searchContainer = document.getElementById('mobile-search-container');
            if (searchContainer && !searchContainer.contains(event.target)) {
                if (isSearchExpanded && window.scrollY > 50) {
                    collapseMobileSearch();
                }
            }
        });
    </script>

@once
    <x-auth-popup />
@endonce
