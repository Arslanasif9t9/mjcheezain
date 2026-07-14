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
        </div>

        <!-- Category Chips (ss4-style tab row, horizontally scrollable) -->
        <div id="category-chips" class="flex overflow-x-auto whitespace-nowrap scrollbar-none gap-5 sm:gap-7 border-b border-gray-100 mb-4 sm:mb-6 px-1 text-sm sm:text-base text-gray-500">
            <button data-cat="" class="chip-active flex-shrink-0 pb-2.5 transition-colors" onclick="filterByCategory(this, '')">All</button>
            <!-- category chips injected by JS -->
        </div>

        <!-- Products Masonry (ss4-style: staggered columns, cards of varying heights) -->
        <div id="product-list" class="columns-2 sm:columns-3 lg:columns-4 xl:columns-5 gap-2 sm:gap-4">
            <!-- Skeletons shown while loading (staggered heights to match the masonry feel) -->
            <div class="skeleton-shimmer rounded-lg h-56 mb-2 break-inside-avoid"></div>
            <div class="skeleton-shimmer rounded-lg h-40 mb-2 break-inside-avoid"></div>
            <div class="skeleton-shimmer rounded-lg h-48 mb-2 break-inside-avoid hidden sm:block"></div>
            <div class="skeleton-shimmer rounded-lg h-64 mb-2 break-inside-avoid hidden lg:block"></div>
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
            const response = await fetch('/products/all', {
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();

            allListingProducts = result.data || [];
            allListingImages = result.images || {};

            buildCategoryChips(allListingProducts);
            renderListing(allListingProducts);
        } catch (error) {
            console.error('Failed to load products:', error);
            document.getElementById('product-list').innerHTML = '';
            document.getElementById('list-empty').classList.remove('hidden');
        }
    }

    function buildCategoryChips(products) {
        const chipRow = document.getElementById('category-chips');
        const categories = [...new Set(products.map(p => p.category).filter(Boolean))];
        categories.forEach(cat => {
            const btn = document.createElement('button');
            btn.className = 'flex-shrink-0 pb-2.5 hover:text-gray-900 transition-colors';
            btn.textContent = cat;
            btn.setAttribute('data-cat', cat);
            btn.onclick = function () { filterByCategory(this, cat); };
            chipRow.appendChild(btn);
        });
    }

    function filterByCategory(chipEl, category) {
        document.querySelectorAll('#category-chips button').forEach(b => b.classList.remove('chip-active'));
        chipEl.classList.add('chip-active');
        const filtered = category ? allListingProducts.filter(p => p.category === category) : allListingProducts;
        renderListing(filtered);
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

        // Varying image ratios so columns stagger up/down like ss4 (not perfect rows)
        const RATIOS = ['aspect-[3/4]', 'aspect-square', 'aspect-[4/5]', 'aspect-[3/3.9]', 'aspect-[5/4]', 'aspect-[3/4.4]'];

        products.forEach((product, index) => {
            const productImages = allListingImages[product.id];
            const hasImage = productImages && productImages.length > 0 && productImages[0].image_path;
            const imgUrl = hasImage
                ? `/storage/vendor/products/images/${productImages[0].image_path}`
                : '/img/default_img.png';

            // Same price maths as the single-product page (selling price + 17% GST)
            const price = product.selling_price * 1.17;
            const hasDiscount = product.mrp && parseFloat(product.mrp) > price;
            const discountPct = hasDiscount ? Math.round(((product.mrp - price) / product.mrp) * 100) : 0;

            const ratio = RATIOS[index % RATIOS.length];

            const card = document.createElement('a');
            card.href = `/product/${product.id}`;
            card.className = 'listing-card bg-white border border-gray-100 rounded-lg overflow-hidden no-underline block mb-2 sm:mb-4 break-inside-avoid';

            card.innerHTML = `
                <!-- Edge-to-edge image with a varying ratio for the masonry stagger -->
                <div class="${ratio} bg-gray-50 overflow-hidden">
                    <img src="${imgUrl}" alt="${product.name}" loading="lazy"
                         class="w-full h-full object-cover transition duration-500 ease-in-out hover:scale-105">
                </div>
                <div class="px-2 pt-2 pb-2.5 sm:px-3 sm:pt-2.5 sm:pb-3">
                    <!-- Name: two lines, left aligned -->
                    <h3 class="text-[13px] sm:text-sm text-gray-800 leading-snug line-clamp-2 min-h-[2.4em] m-0">${product.name}</h3>

                    <!-- Bold price row -->
                    <div class="flex items-baseline gap-1.5 mt-1.5 flex-wrap">
                        <span class="text-base sm:text-lg font-extrabold text-gray-900 whitespace-nowrap">Rs. ${price.toFixed(0)}</span>
                        ${hasDiscount ? `<span class="text-[11px] sm:text-xs text-gray-400 line-through whitespace-nowrap">Rs. ${parseFloat(product.mrp).toFixed(0)}</span>` : ''}
                    </div>

                    <!-- Promo line (ss4-style red/pink accent) -->
                    ${hasDiscount
                        ? `<p class="text-[11px] sm:text-xs font-semibold mt-1 m-0" style="color: #E85D85;">🔥 ${discountPct}% OFF</p>`
                        : `<p class="text-[11px] sm:text-xs text-gray-400 mt-1 m-0"><span class="text-yellow-400">★</span> 4.9 rated</p>`
                    }
                </div>
            `;
            fragment.appendChild(card);
        });

        grid.innerHTML = '';
        grid.appendChild(fragment);
    }

    document.addEventListener('DOMContentLoaded', loadAllProducts);
</script>
