<!-- Search Bar -->
<div class="flex items-center w-full max-w-2xl">
    <div class="grid grid-cols-7 search-bar w-full max-w-2xl">
        <input type="text" id="searchInput" placeholder="Explore autoparts, Perfume, Fashionable...."
            class="w-[66vw] col-span-4 px-4 py-2 outline-none input rounded-[4px]" />
        <select id="categorySelect" class="col-span-2 px-2 border-l border-gray-300 text-sm text-gray-700">
            <option>All Categories</option>
            <option>Auto Parts</option>
            <option>Cars & Vehicles</option>
            <option>Women's Fashion</option>
            <option>Men's Accessories</option>
            <option>Fitness Equipment</option>
            <option>Perfumes & Fragrances</option>
        </select>
        <button id="searchButton" class="bg-gray-700 text-white px-4 flex items-center justify-center rounded-[4px]">
            <i class="fas fa-search"></i>
        </button>
    </div>
</div>

<!-- Search Results Section -->
{{-- <section id="searchResults" class="bg-white p-4 m-auto mt-4 hidden">
    <h2 class="font-bold mb-4">Search Results</h2>
    <div class="grid grid-cols-5 gap-4" id="productsGrid"></div>
    <div class="flex justify-center mt-4">
        <button id="loadMore" class="bg-gray-700 text-white px-4 py-2 rounded hidden">Load More</button>
    </div>
</section> --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const searchButton = document.getElementById('searchButton');
    const searchResults = document.getElementById('searchResults');
    const productsGrid = document.getElementById('productsGrid');
    const loadMoreButton = document.getElementById('loadMore');

    let currentPage = 1;
    let isLoading = false;
    let hasMore = false;

    function fetchProducts(page = 1, append = false) {
        if (isLoading) return;

        isLoading = true;
        if (!append) {
            currentPage = 1;
            productsGrid.innerHTML = '';
        }

        const searchTerm = searchInput.value.trim();
        const category = categorySelect.value;

        fetch(`{{ route('search.products') }}?search=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(category)}&page=${page}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error(data.error);
                    return;
                }

                if (!append && data.products.length === 0) {
                    productsGrid.innerHTML = '<p class="col-span-5 text-center">No products found</p>';
                } else {
                    displayProducts(data.products, append);
                }

                hasMore = data.hasMore;
                loadMoreButton.classList.toggle('hidden', !hasMore);
                searchResults.classList.remove('hidden');
                isLoading = false;
            })
            .catch(error => {
                console.error('Error:', error);
                isLoading = false;
            });
    }

    function displayProducts(products, append) {
        if (!append) productsGrid.innerHTML = '';

        products.forEach(product => {
            const timeAgo = formatTimeAgo(product.updated_at);
            const discount = product.mrp > product.selling_price ? `
                <div class="flex items-center gap-2">
                    <p class="text-gray-500 line-through text-sm">${product.mrp} Rs.</p>
                    <p class="text-[#c50] font-bold">${product.selling_price} Rs.</p>
                </div>
                <p class="text-green-600 text-sm">Save ${product.mrp - product.selling_price} Rs.</p>
            ` : `<p class="text-[#c50] font-bold">${product.selling_price} Rs.</p>`;

            const productElement = document.createElement('a');
            productElement.href = `/product/${product.id}`;
            productElement.className = 'd-inline-block';
            productElement.innerHTML = `
                <img src="/vendor/${product.image}" width="100%" height="100%" alt="${product.name}" class="h-40 object-cover">
                <p class="my-1 h-8 overflow-hidden leading-4 line-clamp-2 text-sm">${product.name}</p>
                ${discount}
                <p class="my-1 text-xs text-gray-500">Updated ${timeAgo}</p>
            `;
            productsGrid.appendChild(productElement);
        });
    }

    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
        return `${Math.floor(diffInSeconds / 86400)} days ago`;
    }

    searchButton.addEventListener('click', () => fetchProducts());
    searchInput.addEventListener('keypress', (e) => { if (e.key === 'Enter') fetchProducts(); });
    loadMoreButton.addEventListener('click', () => { currentPage++; fetchProducts(currentPage, true); });
});
</script>
