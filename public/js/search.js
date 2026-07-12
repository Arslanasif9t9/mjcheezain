// resources/js/search.js
const inputs = document.querySelectorAll('#search-input, #search-input-mobile');
const main = document.getElementById('main');
const allMain = document.querySelectorAll('main');

let debounceTimer;

inputs.forEach(input => {
    input.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        
        const searchTerm = input.value.trim();
        
        if (searchTerm.length < 2) {
            return;
        }

        // Sync values across desktop and mobile inputs
        inputs.forEach(otherInput => {
            if (otherInput !== input) {
                otherInput.value = searchTerm;
            }
        });
        
        debounceTimer = setTimeout(() => {
            allMain.forEach(m => m.classList.add('hidden'));
            const mainContainer = document.getElementById('main');
            if (mainContainer) mainContainer.classList.remove('hidden');
            searchProducts(searchTerm);
        }, 300);
    });
});

async function searchProducts(searchTerm) {
    try {
        // Show loading spinner with your design theme

        main.innerHTML = `
            <div class="py-10 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
                <h2 class="font-serif text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Search Results
                </h2>
                
                <p class="text-lg text-gray-600 mb-12">
                    Searching for "${searchTerm}"...
                </p>
                
                <div class="flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    <span class="ml-3 text-lg text-gray-600">Loading products...</span>
                </div>
            </div>
        `;
        
        const response = await fetch(`/api/search?q=${encodeURIComponent(searchTerm)}`);
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        
        const products = await response.json();
        console.log(products)
        displayProducts(products, searchTerm);
        
    } catch (error) {
        console.error('Error:', error);
        main.innerHTML = `
            <div class="py-10 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
                <h2 class="font-serif text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Search Results
                </h2>
                
                <div class="text-center py-12">
                    <div class="text-red-500 text-lg font-semibold mb-2">Error fetching products</div>
                    <div class="text-gray-600">Please try again later</div>
                </div>
            </div>
        `;
    }
}

function displayProducts(products, searchTerm) {
    if (!products || products.length === 0) {
        main.innerHTML = `
            <div class="py-10 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
                <h2 class="font-serif text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                    Search Results
                </h2>
                
                <p class="text-lg text-gray-600 mb-12">
                    No products found for "${searchTerm}"
                </p>
                
                <div class="text-center py-12">
                    <div class="text-gray-500 text-lg font-semibold mb-2">No products found</div>
                    <div class="text-gray-600">Try different search terms</div>
                </div>
            </div>
        `;
        return;
    }
    
    main.innerHTML = `
        <div class="py-10 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
            <h2 class="font-serif text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Search Results
            </h2>
            
            <p class="text-lg text-gray-600 mb-12">
                Found ${products.length} products for "${searchTerm}"
            </p>
            
            <div id="product-grid" 
                 class="grid gap-6 
                        grid-cols-1          /* 1 column on small mobile */
                        sm:grid-cols-2       /* 2 columns on tablet */
                        lg:grid-cols-3       /* 3 columns on small desktop */
                        xl:grid-cols-4       /* 4 columns on large desktop */
                        auto-rows-fr">
                <!-- Product cards will be injected here -->
            </div>
        </div>
    `;
    
    const grid = document.getElementById('product-grid');
    
    products.forEach(product => {
        const discountPercentage = product.original_price > product.selling_price 
            ? Math.round(((product.original_price - product.selling_price) / product.original_price) * 100)
            : 0;
        
        // Use primary image if available, otherwise use placeholder
        const productImage = product.primary_image || product.video || 
            `https://placehold.co/400x400/f0f0f0/333333?text=${encodeURIComponent(product.name.substring(0, 20))}`;
        
        const card = document.createElement('div');
        card.className = 'bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition duration-300 transform hover:-translate-y-1';
        /*
        card.innerHTML = `
            <div class="relative overflow-hidden">
                ${product.video && !product.primary_image ? `
                    <video class="w-full h-48 object-cover transition duration-500 ease-in-out group-hover:scale-105 brightness-90"
                           poster="${product.primary_image || 'https://placehold.co/400x300/f0f0f0/333333?text=Product+Video'}"
                           controls>
                        <source src="${product.video}" type="video/mp4">
                    </video>
                ` : `
                    <img src="https://arslan.mjcheezain.com/storage/vendor/products/images/${productImage}" 
                         alt="${product.name}" 
                         class="w-full h-48 object-cover transition duration-500 ease-in-out group-hover:scale-105 brightness-90">
                `}
                
                <!-- Discount Badge -->
                ${discountPercentage > 0 ? `
                    <span class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold rounded-full bg-red-500 text-white shadow-md">
                        ${discountPercentage}% OFF
                    </span>
                ` : ''}
                
                <!-- Verified Badge for high-rated products -->
                ${product.rating >= 4.5 ? `
                    <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full bg-yellow-400 text-gray-900 shadow-md">
                        ⭐ Top Rated
                    </span>
                ` : ''}
            </div>

            <div class="p-4">
                <!-- Product Name and Category -->
                <h3 class="text-xl font-bold text-gray-900 truncate mb-1">${product.name}</h3>
                <p class="text-sm text-gray-600 mb-2 truncate">
                    ${product.category} • ${product.subcategory || 'General'}
                </p>
                
                <!-- Price Section -->
                <div class="flex items-center mb-3">
                    <span class="text-2xl font-bold text-gray-900">$${product.selling_price}</span>
                    ${discountPercentage > 0 ? `
                        <span class="ml-2 text-sm text-gray-500 line-through">$${product.original_price}</span>
                        <span class="ml-2 text-xs text-gray-400">MRP: $${product.mrp || product.original_price}</span>
                    ` : ''}
                </div>
                
                <!-- Brand & Model -->
                ${product.brand ? `
                    <div class="flex items-center text-sm text-gray-700 mb-2">
                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span class="font-medium">${product.brand}</span>
                        ${product.model ? `<span class="text-gray-500 ml-2">• ${product.model}</span>` : ''}
                    </div>
                ` : ''}
                
                <!-- Location -->
                <div class="flex items-center text-sm text-gray-600 mb-3">
                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>${product.location || 'Location not specified'}</span>
                </div>

                <!-- Stats -->
                <div class="flex justify-between items-center text-sm text-gray-600 border-t border-gray-100 pt-3 mt-3">
                    <div class="flex items-center">
                        ${product.rating ? `
                            <span class="text-yellow-500 text-lg mr-1">★</span>
                            <span class="font-semibold">${product.rating}/5</span>
                        ` : `
                            <span class="text-gray-400 text-lg mr-1">☆</span>
                            <span class="font-semibold text-gray-400">Not rated</span>
                        `}
                    </div>
                    
                    <div class="flex items-center text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        <span>${product.quantity} available</span>
                    </div>
                    
                    <div class="flex items-center text-gray-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>${product.pcondition || 'New'}</span>
                    </div>
                </div>
                
                <!-- Short Description -->
                ${product.description ? `
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-sm text-gray-500 line-clamp-2">${product.description}</p>
                    </div>
                ` : ''}
            </div>

            <!-- Action Buttons -->
            <div class="px-4 pb-4 pt-0">
                <div class="flex space-x-2">
                    <button onclick="location.href = '/product/${product.id}'" class="flex-1 text-center py-2 border border-gray-900 text-gray-900 font-semibold rounded-lg hover:bg-gray-900 hover:text-white transition duration-300">
                        View Details
                    </button>
                </div>
            </div>
        `;
        */

        // /*
        card.innerHTML = `
        <div class="relative">
            <a href="/product/${product.id}" class="relative no-underline block">
                <div class="absolute top-3 right-3 flex space-x-3 z-[9]">
                    <!-- Heart Icon -->
                    <button id="heart-btn" data-product-id="${product.id}" onclick="event.preventDefault(); event.stopPropagation();" class="p-2 bg-white rounded-full shadow-md hover:bg-gray-100 active:scale-95 transition">
                        <svg id="heart-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-5 h-5 sm:w-6 sm:h-6 text-gray-700">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z"></path>
                        </svg>
                    </button>
                </div>
                <div class="relative overflow-hidden aspect-[4/3]">
                    <!-- Product Image -->
                    <img src="https://arslan.mjcheezain.com/storage/vendor/products/images/${productImage || 'default.jpg'}" 
                        alt="${product.name || 'Product'}" loading="lazy"
                        class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:scale-125">
                    
                    <!-- Discount Badge -->
                    ${discountPercentage > 0 ? `
                        <span class="absolute top-3 left-3 px-3 py-1 text-xs font-semibold rounded-full bg-red-500 text-white shadow-md">
                            ${discountPercentage}% OFF
                        </span>
                    ` : ''}
                </div>

                <div class="p-3 sm:p-4">
                    <!-- Product Name -->
                    <h3 class="text-base sm:text-lg font-bold text-gray-900 mb-1 truncate">${product.name || 'Product Name'}</h3>
                    
                    <!-- Description -->
                    <p class="text-xs sm:text-sm text-gray-600 h-9 sm:h-10 overflow-hidden mb-2">
                        ${product.description || 'No description available'}
                    </p>
                    
                    <!-- Price & Rating -->
                    <div class="flex justify-between items-baseline my-2 sm:my-3">
                        <!-- Price -->
                        <div class="flex flex-col">
                            <span class="text-lg sm:text-xl font-extrabold text-gray-900">
                                $${(product.selling_price*1.17 || 0).toFixed(2)}
                            </span>
                            ${discountPercentage > 0 && product.original_price ? `
                                <span class="text-xs sm:text-sm text-gray-500 line-through">$${product.original_price}</span>
                            ` : ''}
                        </div>
                        
                        <!-- Rating -->
                        <div class="flex items-center">
                            <span class="text-sm sm:text-base font-semibold">${product.rating ? product.rating : '0.0'}</span>
                            <span class="text-yellow-500 text-base sm:text-lg ml-1">★</span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Quick View Button (kept outside the <a> to avoid invalid nested anchors, which broke tap targets on mobile) -->
            <div class="px-3 pb-3 sm:px-4 sm:pb-4">
                <button onclick="window.location.href='/product/${product.id}'" class="px-4 py-2.5 text-sm font-semibold text-white bg-gray-900 rounded-lg w-full 
                            hover:bg-gray-700 active:bg-black transition duration-300 shadow-md">
                    Quick View
                </button>
            </div>
        </div>
        `;
        
        grid.appendChild(card);
    });
}