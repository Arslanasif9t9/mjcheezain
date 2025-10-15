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

            // Function to fetch products
            function fetchProducts(page = 1, append = false) {
                if (isLoading) return;
                
                isLoading = true;
                if (!append) {
                    currentPage = 1;
                    productsGrid.innerHTML = '';
                }
                
                const searchTerm = searchInput.value.trim();
                const category = categorySelect.value;
                
                fetch(`search-in-front.php?search=${encodeURIComponent(searchTerm)}&category=${encodeURIComponent(category)}&page=${page}`)
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
                        if (hasMore) {
                            loadMoreButton.classList.remove('hidden');
                        } else {
                            loadMoreButton.classList.add('hidden');
                        }
                        
                        searchResults.classList.remove('hidden');
                        isLoading = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        isLoading = false;
                    });
            }

            // Function to display products
            function displayProducts(products, append) {
                if (!append) {
                    productsGrid.innerHTML = '';
                }
                
                products.forEach(product => {
                    const timeAgo = formatTimeAgo(product.updated_at);
                    const discount = product.mrp > product.price ? 
                        `<div class="flex items-center gap-2">
                            <p class="text-gray-500 line-through text-sm">${product.mrp} PKR</p>
                            <p class="text-[#c50] font-bold">${product.price} PKR</p>
                        </div>
                        <p class="text-green-600 text-sm">Save ${product.mrp - product.price} PKR</p>` : 
                        `<p class="text-[#c50] font-bold">${product.price} PKR</p>`;
                    
                    const productElement = document.createElement('a');
                    productElement.href = `product-details.php?id=${product.id}`;
                    productElement.className = 'd-inline-block';
                    productElement.innerHTML = `
                        <img src="./vendor/${product.image}" width="100%" height="100%" alt="${product.name}" class="h-40 object-cover">
                        <p class="my-1 h-8 overflow-hidden leading-4 line-clamp-2 text-sm">${product.name}</p>
                        ${discount}
                        <p class="my-1 text-xs text-gray-500">Updated ${timeAgo}</p>
                    `;
                    
                    productsGrid.appendChild(productElement);
                });
            }

            // Format time ago
            function formatTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);
                
                if (diffInSeconds < 60) return `${diffInSeconds} seconds ago`;
                if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes ago`;
                if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours ago`;
                return `${Math.floor(diffInSeconds / 86400)} days ago`;
            }

            // Event listeners
            searchButton.addEventListener('click', () => fetchProducts());
            searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') fetchProducts();
            });
            
            loadMoreButton.addEventListener('click', () => {
                currentPage++;
                fetchProducts(currentPage, true);
            });
        });