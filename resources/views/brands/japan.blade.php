<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <script src="{{ asset('js/page-loader.js') }}?v={{ time() }}"></script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Japan - MJ Cheezain</title>
    <meta name="description" content="Premium auto parts, accessories, and performance parts" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=PT+Serif:ital,wght@0,400;0,700;1,400;1,700&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #e8461e;
            --primary-dark: #cc3a15;
            --secondary: #495057;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #f1f3f5 100%);
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }
        
        /* Glass Card Effect */
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(232, 70, 30, 0.1);
            box-shadow: 0 8px 32px -8px rgba(232, 70, 30, 0.15);
        }
        
        /* Product Card */
        .product-card {
            transition: all 0.3s ease;
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 1.5rem;
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -12px rgba(232, 70, 30, 0.3);
            border-color: var(--primary);
        }
        
        .product-img {
            transition: transform 0.5s ease;
        }
        
        .product-card:hover .product-img {
            transform: scale(1.05);
        }
        
        /* Filter Checkbox */
        .filter-check {
            accent-color: var(--primary);
            width: 16px;
            height: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        
        /* Badge Styling */
        .badge {
            font-size: 0.65rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }
        
        /* Condition Badges */
        .condition-new {
            background: #10b981;
            color: white;
        }
        
        .condition-used {
            background: #f59e0b;
            color: white;
        }
        
        .condition-refurbished {
            background: #6b7280;
            color: white;
        }
        
        /* Animation */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-up {
            animation: fadeUp 0.5s ease forwards;
            opacity: 0;
        }
        
        /* Loading Spinner */
        .spinner {
            width: 40px;
            height: 40px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Error Message */
        .error-message {
            background: #fee2e2;
            border: 1px solid #ef4444;
            color: #b91c1c;
            padding: 1rem;
            border-radius: 0.5rem;
            text-align: center;
        }
        
        /* Filter tag */
        .filter-tag {
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-tag button {
            color: #6b7280;
        }
        
        .filter-tag button:hover {
            color: var(--primary);
        }
    </style>
</head>
{{-- COMING SOON MODE: original body tag was <body class="min-h-screen"> — restore it when re-enabling the page --}}
<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <x-cosmetics.header :user="$user ?? null" :vendor="$vendor ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <!-- Hero Section -->
    <x-auto.hero-video />

    <!-- Hero video: fullscreen support (controls + playsinline + fullscreen button overlay) -->
    <script>
        (function () {
            var heroVideo = document.getElementById('background-video');
            var heroWrap = document.getElementById('video-hero');
            if (!heroVideo || !heroWrap) return;

            // Fullscreen-friendly attributes (autoplay/loop/muted stay untouched)
            heroVideo.setAttribute('controls', '');
            heroVideo.setAttribute('playsinline', '');
            heroVideo.setAttribute('webkit-playsinline', '');
            // Full width / cover on all screens (defensive — component already sets these)
            heroVideo.classList.add('w-full', 'object-cover');

            // Visible fullscreen button — bottom-right gradient circle
            var fsBtn = document.createElement('button');
            fsBtn.type = 'button';
            fsBtn.id = 'hero-fullscreen-btn';
            fsBtn.setAttribute('aria-label', 'Play video fullscreen');
            fsBtn.innerHTML = '<i class="fas fa-expand" aria-hidden="true"></i>';
            fsBtn.style.cssText = 'position:absolute;bottom:1rem;right:1rem;z-index:30;width:2.9rem;height:2.9rem;'
                + 'border-radius:9999px;border:none;cursor:pointer;color:#fff;font-size:1.05rem;'
                + 'display:flex;align-items:center;justify-content:center;'
                + 'background:linear-gradient(to right,#FF7DA0,#FFC275);'
                + 'box-shadow:0 4px 14px rgba(0,0,0,.35);transition:transform .2s ease;';
            fsBtn.addEventListener('mouseenter', function () { fsBtn.style.transform = 'scale(1.08)'; });
            fsBtn.addEventListener('mouseleave', function () { fsBtn.style.transform = 'scale(1)'; });

            fsBtn.addEventListener('click', function () {
                if (heroVideo.requestFullscreen) {
                    heroVideo.requestFullscreen();
                } else if (heroVideo.webkitRequestFullscreen) {
                    heroVideo.webkitRequestFullscreen();
                } else if (heroVideo.webkitEnterFullscreen) {
                    // iOS Safari: native fullscreen video player
                    heroVideo.webkitEnterFullscreen();
                }
            });

            heroWrap.appendChild(fsBtn);
        })();
    </script>

    {{-- ============================================================
         COMING SOON MODE — everything below the hero is temporarily
         disabled (NOT deleted). To bring the full page back, change
         @if(false) to @if(true) here AND in the script block below,
         and restore the original body tag noted above.
         ============================================================ --}}
    @if(true)
    <!-- Active Filters Tags -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        <div id="active-filters" class="flex flex-wrap items-center gap-2 min-h-10"></div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Mobile Filter Toggle -->
            <div class="lg:hidden flex justify-between items-center mb-4">
                <p id="mobile-count" class="text-gray-500 text-sm">Loading...</p>
                <button onclick="toggleSidebar()" class="border border-gray-200 text-sm px-4 py-2 rounded-lg flex items-center gap-2 hover:border-[#e8461e] hover:text-[#e8461e] transition-colors bg-white">
                    <i class="fas fa-sliders-h"></i>
                    Filters
                </button>
            </div>

            <!-- Mobile Filter Backdrop -->
            <div id="filter-overlay" class="hidden fixed inset-0 bg-black/50 z-40 lg:hidden" onclick="toggleSidebar()"></div>

            <!-- Sidebar Filters -->
            <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 sm:w-80 bg-white/95 backdrop-blur-md z-50 transform -translate-x-full transition-transform duration-300 ease-in-out p-6 shadow-2xl overflow-y-auto lg:static lg:w-80 lg:bg-transparent lg:shadow-none lg:p-0 lg:translate-x-0 lg:z-auto lg:block flex-shrink-0">
                <div class="glass-card p-6 rounded-2xl sticky top-24 space-y-6 max-h-[calc(100vh-8rem)] overflow-y-auto scrollbar-hide lg:border-none lg:shadow-none lg:bg-transparent">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-semibold flex items-center gap-2" style="font-family: 'Playfair Display', serif;">
                            <i class="fas fa-filter text-[#e8461e]"></i>
                            Filters
                        </h3>
                        <div class="flex items-center gap-2">
                            <button onclick="clearAllFilters()" id="clear-btn" class="text-sm text-[#e8461e] hover:underline hidden">Clear All</button>
                            <button onclick="toggleSidebar()" class="lg:hidden text-gray-500 hover:text-gray-800 p-1">
                                <i class="fas fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div id="filters-loading" class="text-center py-4">
                        <div class="spinner mx-auto mb-2"></div>
                        <p class="text-sm text-gray-500">Loading filters...</p>
                    </div>

                    <!-- Error State -->
                    <div id="filters-error" class="hidden text-center py-4 text-red-500">
                        <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                        <p class="text-sm">Failed to load filters</p>
                        <button onclick="retryLoad()" class="mt-2 text-xs bg-gray-200 px-3 py-1 rounded">Retry</button>
                    </div>

                    <!-- Filter Sections (will be populated by JS) -->
                    <div id="filter-sections" class="space-y-6 hidden">
                        <!-- Category -->
                        <div>
                            <h4 class="font-semibold mb-3 text-xs uppercase tracking-wider text-gray-400">Category</h4>
                            <div id="filter-category" class="space-y-2 max-h-48 overflow-y-auto"></div>
                        </div>

                        <!-- Subcategory -->
                        <div id="subcategory-section" class="hidden">
                            <h4 class="font-semibold mb-3 text-xs uppercase tracking-wider text-gray-400">Subcategory</h4>
                            <div id="filter-subcategory" class="space-y-2 max-h-48 overflow-y-auto"></div>
                        </div>

                        <!-- Brand -->
                        <div>
                            <h4 class="font-semibold mb-3 text-xs uppercase tracking-wider text-gray-400">Brand</h4>
                            <div id="filter-brand" class="space-y-2 max-h-48 overflow-y-auto"></div>
                        </div>

                        <!-- Condition -->
                        <div>
                            <h4 class="font-semibold mb-3 text-xs uppercase tracking-wider text-gray-400">Condition</h4>
                            <div id="filter-condition" class="space-y-2"></div>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <h4 class="font-semibold mb-3 text-xs uppercase tracking-wider text-gray-400">Price Range (Rs.)</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span id="price-min-display">0</span>
                                    <span>-</span>
                                    <span id="price-max-display">100000</span>
                                </div>
                                <input type="range" 
                                       id="price-min" 
                                       class="price-slider" 
                                       min="0" 
                                       max="100000" 
                                       step="500"
                                       value="0" />
                                <input type="range" 
                                       id="price-max" 
                                       class="price-slider" 
                                       min="0" 
                                       max="100000" 
                                       step="500"
                                       value="100000" />
                                <button onclick="applyPriceFilter()" class="w-full bg-gray-100 text-gray-700 py-2 rounded-lg text-sm hover:bg-gray-200 transition-colors">
                                    Apply Price Range
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Products Grid -->
            <div class="flex-1">
                <div class="hidden lg:flex items-center justify-between mb-6">
                    <p id="product-count" class="text-gray-500 text-sm">Loading products...</p>
                    <select id="sort-select" onchange="applyFilters()" class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-[#e8461e] bg-white">
                        <option value="default">Sort by: Latest</option>
                        <option value="price-low">Price: Low to High</option>
                        <option value="price-high">Price: High to Low</option>
                        <option value="name-asc">Name: A to Z</option>
                        <option value="name-desc">Name: Z to A</option>
                    </select>
                </div>

                <!-- Loading State -->
                <div id="products-loading" class="text-center py-16">
                    <div class="spinner mx-auto mb-4"></div>
                    <p class="text-gray-500">Loading products...</p>
                </div>

                <!-- Error State -->
                <div id="products-error" class="hidden text-center py-16">
                    <i class="fas fa-exclamation-circle text-5xl text-red-400 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Failed to load products</h3>
                    <p class="text-gray-400 mb-4">Please check your connection and try again</p>
                    <button onclick="retryLoad()" class="bg-[#e8461e] text-white px-6 py-2 rounded-lg hover:bg-[#cc3a15] transition-colors">
                        Retry
                    </button>
                </div>

                <!-- Products Grid -->
                <div id="product-grid" class="grid grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4 lg:gap-6 hidden"></div>

                <!-- No Results -->
                <div id="no-results" class="hidden text-center py-16">
                    <i class="fas fa-car text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">No parts found</h3>
                    <p class="text-gray-400 mb-4">Try adjusting your filters</p>
                    <button onclick="clearAllFilters()" class="bg-[#e8461e] text-white px-6 py-2 rounded-lg hover:bg-[#cc3a15] transition-colors">
                        Clear Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />

    <!-- Product Detail Modal -->
    <div id="modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal()"></div>
        <div class="absolute inset-4 md:inset-12 lg:inset-24 bg-white rounded-2xl overflow-y-auto shadow-2xl">
            <button onclick="closeModal()" class="absolute top-4 right-4 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors text-xl z-10">
                <i class="fas fa-times"></i>
            </button>
            <div id="modal-content" class="p-6 md:p-10"></div>
        </div>
    </div>
    @endif

    {{-- COMING SOON MODE: page scripts disabled with the markup above — change @if(false) to @if(true) to restore --}}
    @if(true)
    <script>
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        
        // API Base URL
        const API_BASE = window.location.origin + '/japan/api';

        // All products data (will be loaded once)
        let allProducts = [];
        
        // Filtered products (for display)
        let filteredProducts = [];

        // State Management
        let filters = {
            category: [],
            subcategory: [],
            brand: [],
            condition: [],
            price_min: 0,
            price_max: 100000
        };

        // Filter options (for building filter UI)
        let filterOptions = {
            categories: [],
            subcategories: [],
            brands: [],
            conditions: [],
            price_range: { min: 0, max: 100000 }
        };

        let sortBy = 'default';
        let searchQuery = '';

        // Function to log only fetching data
        function logFetchData(endpoint, result) {
            console.log(`Fetching data from: ${endpoint}`);
            console.log(result);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllData();
            setupEventListeners();
        });

        // Retry loading
        function retryLoad() {
            document.getElementById('products-error').classList.add('hidden');
            document.getElementById('filters-error').classList.add('hidden');
            document.getElementById('products-loading').classList.remove('hidden');
            document.getElementById('filters-loading').classList.remove('hidden');
            loadAllData();
        }

        // Load all products and filter options in one go
        async function loadAllData() {
            try {
                // Fetch all products
                const productsResponse = await fetch(API_BASE + '/products?all=1');
                const productsResult = await productsResponse.json();
                logFetchData(API_BASE + '/products?all=1', productsResult);

                // Fetch filter options
                const filtersResponse = await fetch(API_BASE + '/filters');
                const filtersResult = await filtersResponse.json();
                logFetchData(API_BASE + '/filters', filtersResult);

                if (productsResult.success && filtersResult.success) {
                    // Store all products
                    allProducts = productsResult.data || [];
                    filteredProducts = [...allProducts];
                    
                    // Store filter options
                    filterOptions = filtersResult.data || filterOptions;
                    
                    // Render filters
                    renderFilters();
                    
                    // Apply initial filters (shows all products)
                    applyFilters();
                    
                    // Hide loading and show content
                    document.getElementById('products-loading').classList.add('hidden');
                    document.getElementById('filters-loading').classList.add('hidden');
                    document.getElementById('product-grid').classList.remove('hidden');
                    document.getElementById('filter-sections').classList.remove('hidden');
                } else {
                    throw new Error('Failed to load data');
                }
            } catch (error) {
                console.error('Error loading data:', error);
                
                // Show error states
                document.getElementById('products-loading').classList.add('hidden');
                document.getElementById('filters-loading').classList.add('hidden');
                document.getElementById('products-error').classList.remove('hidden');
                document.getElementById('filters-error').classList.remove('hidden');
            }
        }

        // Render filter checkboxes from loaded filter options
        function renderFilters() {
            // Categories
            if (filterOptions.categories && filterOptions.categories.length > 0) {
                const categoryHtml = filterOptions.categories.map(cat => `
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               class="filter-check" 
                               data-key="category" 
                               data-value="${cat.slug || cat.id}" 
                               onchange="toggleFilter('category', '${cat.slug || cat.id}')" />
                        <span class="text-sm group-hover:text-[#e8461e] transition-colors flex-1">${cat.name || cat.category_name}</span>
                        <span class="text-xs text-gray-400">(${cat.product_count || 0})</span>
                    </label>
                `).join('');
                document.getElementById('filter-category').innerHTML = categoryHtml;
            }

            // Brands
            if (filterOptions.brands && filterOptions.brands.length > 0) {
                const brandHtml = filterOptions.brands.map(brand => `
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               class="filter-check" 
                               data-key="brand" 
                               data-value="${brand.brand}" 
                               onchange="toggleFilter('brand', '${brand.brand}')" />
                        <span class="text-sm group-hover:text-[#e8461e] transition-colors flex-1">${brand.brand}</span>
                        <span class="text-xs text-gray-400">(${brand.product_count || 0})</span>
                    </label>
                `).join('');
                document.getElementById('filter-brand').innerHTML = brandHtml;
            }

            // Conditions
            if (filterOptions.conditions && filterOptions.conditions.length > 0) {
                const conditionHtml = filterOptions.conditions.map(cond => `
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               class="filter-check" 
                               data-key="condition" 
                               data-value="${cond.condition}" 
                               onchange="toggleFilter('condition', '${cond.condition}')" />
                        <span class="text-sm group-hover:text-[#e8461e] transition-colors flex-1">${cond.condition}</span>
                        <span class="text-xs text-gray-400">(${cond.product_count || 0})</span>
                    </label>
                `).join('');
                document.getElementById('filter-condition').innerHTML = conditionHtml;
            }

            // Price range
            if (filterOptions.price_range) {
                const minPrice = filterOptions.price_range.min || 0;
                const maxPrice = filterOptions.price_range.max || 100000;
                
                document.getElementById('price-min').max = maxPrice;
                document.getElementById('price-max').max = maxPrice;
                document.getElementById('price-min').value = minPrice;
                document.getElementById('price-max').value = maxPrice;
                document.getElementById('price-min-display').textContent = 'Rs. ' + minPrice.toLocaleString();
                document.getElementById('price-max-display').textContent = 'Rs. ' + maxPrice.toLocaleString();
                
                filters.price_min = minPrice;
                filters.price_max = maxPrice;
            }
        }

        // Apply all filters (frontend only)
        function applyFilters() {
            // Start with all products
            filteredProducts = [...allProducts];

            // Apply category filter
            if (filters.category.length > 0) {
                filteredProducts = filteredProducts.filter(p => 
                    filters.category.includes(p.category_slug || p.category_id)
                );
            }

            // Apply subcategory filter
            if (filters.subcategory.length > 0) {
                filteredProducts = filteredProducts.filter(p => 
                    p.subcategory_id && filters.subcategory.includes(p.subcategory_id.toString())
                );
            }

            // Apply brand filter
            if (filters.brand.length > 0) {
                filteredProducts = filteredProducts.filter(p => 
                    filters.brand.includes(p.brand)
                );
            }

            // Apply condition filter
            if (filters.condition.length > 0) {
                filteredProducts = filteredProducts.filter(p => 
                    filters.condition.includes(p.condition)
                );
            }

            // Apply price filter
            filteredProducts = filteredProducts.filter(p => 
                p.price >= filters.price_min && p.price <= filters.price_max
            );

            // Apply search filter
            if (searchQuery.trim() !== '') {
                const query = searchQuery.toLowerCase().trim();
                filteredProducts = filteredProducts.filter(p => 
                    (p.name && p.name.toLowerCase().includes(query)) ||
                    (p.brand && p.brand.toLowerCase().includes(query)) ||
                    (p.model && p.model.toLowerCase().includes(query)) ||
                    (p.part_type && p.part_type.toLowerCase().includes(query)) ||
                    (p.category && p.category.toLowerCase().includes(query)) ||
                    (p.subcategory && p.subcategory.toLowerCase().includes(query))
                );
            }

            // Apply sorting
            applySorting();

            // Render products
            renderProducts();

            // Update counts
            updateCounts();

            // Update active filters display
            updateActiveFilters();

            // Update clear button
            updateClearButton();
        }

        // Apply sorting
        function applySorting() {
            switch(sortBy) {
                case 'price-low':
                    filteredProducts.sort((a, b) => (a.price || 0) - (b.price || 0));
                    break;
                case 'price-high':
                    filteredProducts.sort((a, b) => (b.price || 0) - (a.price || 0));
                    break;
                case 'name-asc':
                    filteredProducts.sort((a, b) => (a.name || '').localeCompare(b.name || ''));
                    break;
                case 'name-desc':
                    filteredProducts.sort((a, b) => (b.name || '').localeCompare(a.name || ''));
                    break;
                default:
                    // Default: keep as is (by ID or created_at)
                    filteredProducts.sort((a, b) => (b.id || 0) - (a.id || 0));
            }
        }

        // Render products grid with error handling for images
        function renderProducts() {
            const grid = document.getElementById('product-grid');
            const noResults = document.getElementById('no-results');

            if (filteredProducts.length === 0) {
                grid.innerHTML = '';
                grid.classList.add('hidden');
                noResults.classList.remove('hidden');
                return;
            }

            grid.classList.remove('hidden');
            noResults.classList.add('hidden');

            grid.innerHTML = filteredProducts.map((product, index) => {
                const condition = product.condition || 'New';
                const conditionClass = condition.toLowerCase().replace(' ', '-');
                const discount = product.original_price && product.original_price > product.price
                    ? Math.round((1 - product.price / product.original_price) * 100)
                    : null;

                return `
                <a href="/japan/${product.id}" class="block">
                    <div class="product-card animate-fade-up" style="animation-delay: ${index * 0.02}s">
                        <div class="relative overflow-hidden h-40 sm:h-56">
                            <img src="${product.image}"
                                 alt="${product.name || 'Product'}"
                                 class="product-img w-full h-full object-cover"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='/img/default_img.png';" />
                            <span class="badge condition-${conditionClass} absolute top-3 left-3">
                                ${condition}
                            </span>
                            ${discount ? `<span class="badge bg-green-500 text-white absolute top-3 right-3">-${discount}%</span>` : ''}
                        </div>
                        <div class="p-3 sm:p-5">
                            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">${product.category || 'Auto Part'}</p>
                            <h3 class="text-sm sm:text-lg font-semibold mb-2 leading-tight" style="font-family: 'Playfair Display', serif;">
                                ${product.name || 'Unnamed Product'}
                            </h3>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-sm font-medium">${product.brand || 'Generic'}</span>
                                <span class="text-xs text-gray-400">•</span>
                                <span class="text-xs text-gray-400">${product.model || 'Universal'}</span>
                            </div>
                            <div class="flex items-center justify-between flex-wrap gap-1">
                                <div>
                                    <span class="text-base sm:text-xl font-bold text-[#e8461e]">Rs. ${(product.price || 0).toLocaleString()}</span>
                                    ${product.original_price ? `<span class="text-sm text-gray-400 line-through ml-2">Rs. ${product.original_price.toLocaleString()}</span>` : ''}
                                </div>
                                <span class="text-xs px-3 py-1 rounded-full ${(product.quantity || 0) > 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'}">
                                    ${(product.quantity || 0) > 0 ? 'In Stock' : 'Out of Stock'}
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
                `;
            }).join('');
        }

        // Update subcategories based on selected categories
        function updateSubcategories() {
            const section = document.getElementById('subcategory-section');
            
            if (filters.category.length === 0 || !filterOptions.subcategories) {
                section.classList.add('hidden');
                filters.subcategory = [];
                return;
            }

            // Get subcategories for selected categories
            const relevantSubs = filterOptions.subcategories.filter(sub => 
                filters.category.includes(sub.category_slug || sub.category_id)
            );
            
            if (relevantSubs.length > 0) {
                const html = relevantSubs.map(sub => `
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" 
                               class="filter-check" 
                               data-key="subcategory" 
                               data-value="${sub.id}"
                               ${filters.subcategory.includes(sub.id.toString()) ? 'checked' : ''}
                               onchange="toggleFilter('subcategory', '${sub.id}')" />
                        <span class="text-sm group-hover:text-[#e8461e] transition-colors flex-1">${sub.name || sub.subcategory_name}</span>
                        <span class="text-xs text-gray-400">(${sub.product_count || 0})</span>
                    </label>
                `).join('');
                
                document.getElementById('filter-subcategory').innerHTML = html;
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
                filters.subcategory = [];
            }
        }

        // Toggle filter selection
        function toggleFilter(key, value) {
            const index = filters[key].indexOf(value);
            if (index > -1) {
                filters[key].splice(index, 1);
            } else {
                filters[key].push(value);
            }
            
            if (key === 'category') {
                updateSubcategories();
            }
            
            applyFilters();
        }

        // Apply price filter
        function applyPriceFilter() {
            filters.price_min = parseInt(document.getElementById('price-min').value);
            filters.price_max = parseInt(document.getElementById('price-max').value);
            applyFilters();
        }

        // Update price range display
        document.getElementById('price-min').addEventListener('input', function() {
            document.getElementById('price-min-display').textContent = 'Rs. ' + parseInt(this.value).toLocaleString();
        });

        document.getElementById('price-max').addEventListener('input', function() {
            document.getElementById('price-max-display').textContent = 'Rs. ' + parseInt(this.value).toLocaleString();
        });

        // Setup event listeners
        function setupEventListeners() {
            // No search functionality needed
        }

        // Sort change handler
        document.getElementById('sort-select').addEventListener('change', function() {
            sortBy = this.value;
            applySorting();
            renderProducts();
        });

        // Update product counts
        function updateCounts() {
            document.getElementById('product-count').textContent = `${filteredProducts.length} products found`;
            document.getElementById('mobile-count').textContent = `${filteredProducts.length} products`;
        }

        // Update active filters display
        function updateActiveFilters() {
            const container = document.getElementById('active-filters');
            let html = '';

            // Category tags
            filters.category.forEach(cat => {
                const category = filterOptions.categories?.find(c => (c.slug || c.id) === cat);
                if (category) {
                    html += `
                        <span class="filter-tag">
                            ${category.name || category.category_name}
                            <button onclick="removeFilter('category', '${cat}')"><i class="fas fa-times"></i></button>
                        </span>
                    `;
                }
            });

            // Subcategory tags
            filters.subcategory.forEach(subId => {
                const subcategory = filterOptions.subcategories?.find(s => s.id == subId);
                if (subcategory) {
                    html += `
                        <span class="filter-tag">
                            ${subcategory.name || subcategory.subcategory_name}
                            <button onclick="removeFilter('subcategory', '${subId}')"><i class="fas fa-times"></i></button>
                        </span>
                    `;
                }
            });

            // Brand tags
            filters.brand.forEach(brand => {
                html += `
                    <span class="filter-tag">
                        ${brand}
                        <button onclick="removeFilter('brand', '${brand}')"><i class="fas fa-times"></i></button>
                    </span>
                `;
            });

            // Condition tags
            filters.condition.forEach(cond => {
                html += `
                    <span class="filter-tag">
                        ${cond}
                        <button onclick="removeFilter('condition', '${cond}')"><i class="fas fa-times"></i></button>
                    </span>
                `;
            });

            // Price tag (if custom)
            if (filters.price_min > (filterOptions.price_range?.min || 0) || 
                filters.price_max < (filterOptions.price_range?.max || 100000)) {
                html += `
                    <span class="filter-tag">
                        Rs. ${filters.price_min.toLocaleString()} - Rs. ${filters.price_max.toLocaleString()}
                        <button onclick="resetPriceFilter()"><i class="fas fa-times"></i></button>
                    </span>
                `;
            }

            container.innerHTML = html;
        }

        // Remove specific filter
        function removeFilter(key, value) {
            const index = filters[key].indexOf(value);
            if (index > -1) {
                filters[key].splice(index, 1);
                
                // Uncheck the corresponding checkbox
                document.querySelectorAll(`.filter-check[data-key="${key}"][data-value="${value}"]`).forEach(cb => {
                    cb.checked = false;
                });
                
                if (key === 'category') {
                    updateSubcategories();
                }
                
                applyFilters();
            }
        }

        // Reset price filter to min/max
        function resetPriceFilter() {
            filters.price_min = filterOptions.price_range?.min || 0;
            filters.price_max = filterOptions.price_range?.max || 100000;
            
            document.getElementById('price-min').value = filters.price_min;
            document.getElementById('price-max').value = filters.price_max;
            document.getElementById('price-min-display').textContent = 'Rs. ' + filters.price_min.toLocaleString();
            document.getElementById('price-max-display').textContent = 'Rs. ' + filters.price_max.toLocaleString();
            
            applyFilters();
        }

        // Clear all filters
        function clearAllFilters() {
            filters = {
                category: [],
                subcategory: [],
                brand: [],
                condition: [],
                price_min: filterOptions.price_range?.min || 0,
                price_max: filterOptions.price_range?.max || 100000
            };
            
            searchQuery = '';
            
            // Uncheck all checkboxes
            document.querySelectorAll('.filter-check').forEach(checkbox => {
                checkbox.checked = false;
            });
            
            // Reset price sliders
            if (filterOptions.price_range) {
                document.getElementById('price-min').value = filterOptions.price_range.min;
                document.getElementById('price-max').value = filterOptions.price_range.max;
                document.getElementById('price-min-display').textContent = 'Rs. ' + filterOptions.price_range.min.toLocaleString();
                document.getElementById('price-max-display').textContent = 'Rs. ' + filterOptions.price_range.max.toLocaleString();
            }
            
            // Hide subcategory section
            document.getElementById('subcategory-section').classList.add('hidden');
            
            applyFilters();
        }

        // Update clear button visibility
        function updateClearButton() {
            const hasFilters = filters.category.length > 0 || 
                              filters.subcategory.length > 0 || 
                              filters.brand.length > 0 || 
                              filters.condition.length > 0 ||
                              filters.price_min > (filterOptions.price_range?.min || 0) ||
                              filters.price_max < (filterOptions.price_range?.max || 100000);
            
            document.getElementById('clear-btn').classList.toggle('hidden', !hasFilters);
        }

        // Toggle sidebar on mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('filter-overlay');
            const isOpen = sidebar.classList.contains('-translate-x-0');
            
            if (isOpen) {
                sidebar.classList.remove('-translate-x-0');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('-translate-x-0');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        // Open product modal
        async function openModal(productId) {
            try {
                const response = await fetch(`${API_BASE}/product/${productId}`);
                const result = await response.json();
                logFetchData(`${API_BASE}/product/${productId}`, result);

                if (result.success) {
                    const product = result.data;
                    renderModal(product);
                }
            } catch (error) {
                console.error('Error loading product details:', error);
            }
        }

        // Render modal content
        function renderModal(product) {
            const whatsappMessage = encodeURIComponent(
                `Hi! I'm interested in:\n\n` +
                `🔧 Product: ${product.name || ''}\n` +
                `🚗 Category: ${product.category || ''} - ${product.subcategory || ''}\n` +
                `🏷️ Brand: ${product.brand || ''}\n` +
                `💰 Price: Rs. ${(product.price || 0).toLocaleString()}\n\n` +
                `Please provide more details and availability.`
            );

            // Main image (first image or default)
            const mainImage = product.images && product.images.length > 0 
                ? (product.images.find(img => img.is_primary)?.url || product.images[0].url)
                : '{{ asset("img/default_img.png") }}';

            // Thumbnail images
            const thumbnails = product.images && product.images.length > 0 
                ? product.images.map(img => `
                    <img src="${img.url}" 
                         class="w-20 h-20 object-cover rounded-lg cursor-pointer border-2 hover:border-[#e8461e] transition-colors"
                         onerror="this.onerror=null; this.src='{{ asset("img/default_img.png") }}';"
                         onclick="document.getElementById('modal-main-image').src='${img.url}'" />
                `).join('')
                : '';

            const modalContent = document.getElementById('modal-content');
            modalContent.innerHTML = `
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div class="rounded-xl overflow-hidden bg-gray-100">
                            <img id="modal-main-image" 
                                 src="${mainImage}" 
                                 alt="${product.name || 'Product'}" 
                                 class="w-full h-96 object-cover"
                                 onerror="this.onerror=null; this.src='{{ asset("img/default_img.png") }}';" />
                        </div>
                        ${product.images && product.images.length > 1 ? `
                            <div class="flex gap-2 overflow-x-auto pb-2">
                                ${thumbnails}
                            </div>
                        ` : ''}
                        ${product.video ? `
                            <div class="mt-4">
                                <h4 class="font-semibold mb-2">Product Video</h4>
                                <video controls class="w-full rounded-lg">
                                    <source src="${product.video}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        ` : ''}
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-widest text-gray-400 mb-1">
                            ${product.category || ''} / ${product.subcategory || ''}
                        </p>
                        ${product.part_type ? `
                            <span class="badge bg-gray-200 text-gray-700 mb-3 inline-block">${product.part_type}</span>
                        ` : ''}
                        <h2 class="text-3xl font-bold mb-3" style="font-family: 'Playfair Display', serif;">
                            ${product.name || 'Unnamed Product'}
                        </h2>
                        <div class="flex items-center gap-4 mb-4">
                            <span class="badge condition-${(product.condition || 'new').toLowerCase()}">${product.condition || 'New'}</span>
                            <span class="text-sm text-gray-500">
                                <i class="fas fa-map-marker-alt mr-1"></i> ${product.location || 'Location not specified'}
                            </span>
                        </div>
                        <div class="mb-6">
                            <span class="text-3xl font-bold text-[#e8461e]">Rs. ${(product.price || 0).toLocaleString()}</span>
                            ${product.original_price ? `
                                <span class="text-lg text-gray-400 line-through ml-3">Rs. ${product.original_price.toLocaleString()}</span>
                                <span class="ml-2 text-green-500 font-semibold text-sm">Save Rs. ${(product.original_price - product.price).toLocaleString()}</span>
                            ` : ''}
                        </div>
                        <div class="grid grid-cols-2 gap-4 mb-6 text-sm">
                            <div>
                                <p class="text-gray-400">Brand</p>
                                <p class="font-medium">${product.brand || 'Generic'}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Model</p>
                                <p class="font-medium">${product.model || 'Universal'}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Made In</p>
                                <p class="font-medium">${product.made_in || 'Not specified'}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Quantity</p>
                                <p class="font-medium">${product.quantity || 0} in stock</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Shipping</p>
                                <p class="font-medium">${product.shipping_method || 'Standard'}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Delivery</p>
                                <p class="font-medium">${product.shipping_time || '3-5 business days'}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">${product.description || 'No description available.'}</p>
                        
                        ${product.faults && product.faults.length > 0 ? `
                            <div class="mb-6 p-4 bg-yellow-50 rounded-lg">
                                <h4 class="font-semibold text-yellow-800 mb-2 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Product Faults
                                </h4>
                                ${product.faults.map(fault => `
                                    <div class="mb-3 pb-3 border-b border-yellow-200 last:border-0">
                                        <p class="text-sm text-yellow-700">${fault.description || ''}</p>
                                        ${fault.image ? `
                                            <img src="${fault.image}" class="mt-2 h-20 rounded" onerror="this.onerror=null; this.style.display='none';" />
                                        ` : ''}
                                    </div>
                                `).join('')}
                            </div>
                        ` : ''}
                        
                        ${product.return_policy ? `
                            <div class="mb-6 p-4 bg-pink-50 rounded-lg">
                                <h4 class="font-semibold text-[#C94A72] mb-2">Return Policy</h4>
                                <p class="text-sm text-[#E85D85]">${product.return_policy}</p>
                            </div>
                        ` : ''}
                        
                        <a href="https://wa.me/1234567890?text=${whatsappMessage}" 
                           target="_blank" 
                           class="block w-full bg-green-500 hover:bg-green-600 text-white text-center font-bold py-4 rounded-xl transition-colors text-lg">
                            <i class="fab fa-whatsapp mr-2"></i>
                            Order via WhatsApp
                        </a>
                        <p class="text-center text-xs text-gray-400 mt-2">
                            <i class="fas fa-truck mr-1"></i>
                            Free delivery available in major cities
                        </p>
                    </div>
                </div>
            `;

            document.getElementById('modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        // Close modal
        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>

    <script src="{{ asset('js/product-card.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ time() }}"></script>
    @endif

    <script src="{{ asset('js/search.js') }}?v={{ time() }}"></script>
    <x-customer.global-nav />
</body>
</html>