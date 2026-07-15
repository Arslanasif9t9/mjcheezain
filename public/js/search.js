// resources/js/search.js
const inputs = document.querySelectorAll('#search-input, #search-input-mobile, #search-input-inline');
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
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#E85D85]"></div>
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
                 class="grid gap-3 sm:gap-5 grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 auto-rows-fr">
                <!-- Product cards will be injected here -->
            </div>
        </div>
    `;
    
    const grid = document.getElementById('product-grid');

    // Load the shared ss10-style card builder if it isn't on this page yet
    const renderCards = () => {
        products.forEach(product => {
            const imgUrl = product.primary_image
                ? `/storage/vendor/products/images/${product.primary_image}`
                : '/img/default_img.png';
            grid.appendChild(window.buildProductCard(product, imgUrl, 'grid'));
        });
    };

    if (window.buildProductCard) {
        renderCards();
    } else {
        const s = document.createElement('script');
        s.src = '/js/product-card.js';
        s.onload = renderCards;
        document.head.appendChild(s);
    }
}
