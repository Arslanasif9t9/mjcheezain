@php
    // NOTE: the parent include already passes a $category variable — do not reuse that name.
    $activeCategories = \App\Support\CategoryCatalog::active();
    $selectedCategory = trim((string) request('category', ''));
@endphp
<script src="{{ asset('js/product-card.js') }}?v={{ @filemtime(public_path('js/product-card.js')) ?: 1 }}"></script>
<style>
    .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
    .scrollbar-none::-webkit-scrollbar { display: none; }

    /* ss4-style flat listing card */
    .listing-card {
        transition: box-shadow 0.25s ease, transform 0.25s ease;
    }
    .listing-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(255, 125, 160, 0.15);
    }

    /* Category chip active state */
    .chip-active {
        color: #111827;
        font-weight: 700;
        border-bottom: 3px solid #E85D85;
    }
</style>

<div class="mx-auto px-2 sm:px-4 py-6 sm:py-10">

    <section id="products-section" class="py-5 sm:py-8 px-2 sm:px-6 lg:px-8 mx-auto bg-white rounded-2xl shadow-sm relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[3px]" style="background: linear-gradient(to right, #FF7DA0, #FFC275);"></div>

        <!-- Title -->
        <div class="text-center mb-4 sm:mb-6 px-2">
            <span class="section-kicker justify-center">MJ Cheezain</span>
            <h2 id="section-title" class="font-serif text-2xl sm:text-4xl font-extrabold text-gray-900 mb-2">
                All Products
            </h2>
            <div class="brand-divider"></div>

            @if ($selectedCategory !== '')
                <!-- Active category chip (✕ clears the filter) -->
                <div class="mt-3 flex justify-center">
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold text-white shadow-sm"
                          style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">
                        Category: {{ $selectedCategory }}
                        <a href="/products/all-page" class="text-white/90 hover:text-white font-bold no-underline leading-none"
                           title="Clear category filter" aria-label="Clear category filter">&#10005;</a>
                    </span>
                </div>
            @endif
        </div>

        <!-- Category Chips (ss4-style tab row, horizontally scrollable; clicking reloads with ?category=) -->
        <div id="category-chips" class="flex overflow-x-auto whitespace-nowrap scrollbar-none gap-5 sm:gap-7 border-b border-gray-100 mb-4 sm:mb-6 px-1 text-sm sm:text-base text-gray-500">
            <a href="/products/all-page"
               class="{{ $selectedCategory === '' ? 'chip-active' : 'hover:text-gray-900' }} flex-shrink-0 pb-2.5 transition-colors no-underline">All</a>
            @foreach ($activeCategories as $chipCat)
                <a href="/products/all-page?category={{ urlencode($chipCat->name) }}"
                   class="{{ $selectedCategory === $chipCat->name ? 'chip-active' : 'hover:text-gray-900' }} flex-shrink-0 pb-2.5 transition-colors no-underline">{{ $chipCat->emoji }} {{ $chipCat->name }}</a>
            @endforeach
        </div>

        <!-- Products Grid (ss10-style: uniform cards, same size as every other section) -->
        <div id="product-list" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
            <!-- Skeletons shown while loading -->
            <div class="skeleton-shimmer rounded-2xl h-64"></div>
            <div class="skeleton-shimmer rounded-2xl h-64"></div>
            <div class="skeleton-shimmer rounded-2xl h-64 hidden sm:block"></div>
            <div class="skeleton-shimmer rounded-2xl h-64 hidden lg:block"></div>
        </div>

        <!-- Empty state -->
        <div id="list-empty" class="hidden text-center py-16">
            <div class="text-gray-300 text-6xl mb-4">🛍️</div>
            <p class="text-gray-500 text-lg">No products found</p>
        </div>

    </section>
</div>

<script>
    let allListingProducts = [];
    let allListingImages = {};

    async function loadAllProducts() {
        try {
            // Pass the page's ?category= filter through to the products endpoint.
            // Without it, /products/all behaves exactly as before (backward compatible).
            const pageParams = new URLSearchParams(window.location.search);
            const selectedCategory = (pageParams.get('category') || '').trim();
            const fetchUrl = selectedCategory
                ? '/products/all?category=' + encodeURIComponent(selectedCategory)
                : '/products/all';

            const response = await fetch(fetchUrl, {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            allListingProducts = result.data || [];
            allListingImages = result.images || {};

            renderListing(allListingProducts);
        } catch (error) {
            console.error('Failed to load products:', error);
            document.getElementById('product-list').innerHTML = '';
            document.getElementById('list-empty').classList.remove('hidden');
        }
    }

    function renderListing(products) {
        const grid = document.getElementById('product-list');
        const empty = document.getElementById('list-empty');

        if (!products.length) {
            grid.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        const fragment = document.createDocumentFragment();

        products.forEach((product) => {
            const productImages = allListingImages[product.id];
            const hasImage = productImages && productImages.length > 0 && productImages[0].image_path;
            const imgUrl = hasImage
                ? `/storage/vendor/products/images/${productImages[0].image_path}`
                : '/img/default_img.png';

            // ss10-style shared card (public/js/product-card.js)
            fragment.appendChild(window.buildProductCard(product, imgUrl, 'grid'));
        });

        grid.innerHTML = '';
        grid.appendChild(fragment);
    }

    document.addEventListener('DOMContentLoaded', loadAllProducts);
</script>
