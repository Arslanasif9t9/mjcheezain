// categories fetch 
        const mockFetchProducts = async (categoryName) => {
            categoryName = {
                name: categoryName
            };

            let response = await fetch(`/products/category`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(categoryName)
                });
            response = await response.json();
            
            const allProducts = response.data || [];
            const imagesMap = response.images || {};

            // Return a limited array (max 8 products) and images map
            return new Promise(resolve => {
                // Simulate network delay
                setTimeout(() => {
                    if (allProducts.length < 1) {
                        resolve({ products: [], images: {} }); // Simulate no products found
                    } else {
                        resolve({ products: allProducts.slice(0, 8), images: imagesMap }); // Return up to 8 products
                    }
                }, 100);
            });
        };

        // --- Component Logic Function ---
        const loadCategoryProducts = async (categoryName, id) => {
            const section = document.getElementById(`${id}-products-section`);
            const titleElement = document.getElementById(`${id}-title`);
            const grid = document.getElementById(`${id}-product-grid`);

            // Clear previous content
            grid.innerHTML = '';
            titleElement.textContent = '';

            try {
                const { products, images } = await mockFetchProducts(categoryName);
                
                if (products.length === 0) {
                    // 1. If no products are fetched, hide the section
                    console.log(`No products found for "${categoryName}". Section hidden.`);
                    section.classList.add('hidden');
                    return;
                }

                // 2. If products are found, set the title and show the section
                titleElement.textContent = categoryName;
                section.classList.remove('hidden');

                // Check if current hash matches this section
                if (window.location.hash === `#${id}-products-section`) {
                    setTimeout(() => {
                        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 200);
                }

                // 3. Render Product Cards
                products.forEach(product => {
                    const productImages = images[product.id];
                    const hasImage = productImages && productImages.length > 0 && productImages[0].image_path;
                    const imgUrl = hasImage 
                        ? `/storage/vendor/products/images/${productImages[0].image_path}` 
                        : `/img/default_img.png`;

                    const card = document.createElement('div');
                    
                    // Card styling
                    card.className = 'bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transition duration-300 min-w-0 flex flex-col justify-between relative';
                    
                    // Product Card HTML Structure
                    card.innerHTML = `
                        <!-- Wishlist Button -->
                        <div class="absolute top-2.5 right-2.5 z-10">
                            <button id="heart-btn" data-product-id="${product.id}" class="p-1.5 sm:p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-gray-100 transition active:scale-95" onclick="event.preventDefault(); event.stopPropagation();">
                                <svg id="heart-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-4.5 h-4.5 sm:w-5 sm:h-5 text-gray-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Product Link -->
                        <a href="/product/${product.id}" class="no-underline flex flex-col flex-grow">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden aspect-w-4 aspect-h-3 bg-gray-50">
                                <img src="${imgUrl}" alt="${product.name}" 
                                    class="w-full h-36 sm:h-48 md:h-[210px] object-cover transition duration-500 ease-in-out group-hover:scale-105">
                            </div>

                            <!-- Product Details -->
                            <div class="p-3 sm:p-4 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xs sm:text-base font-bold text-gray-900 mb-0.5 truncate group-hover:text-blue-600 transition-colors duration-200">${product.name}</h3>
                                    <p class="text-[11px] sm:text-xs text-gray-500 h-7 sm:h-10 overflow-hidden line-clamp-2 leading-tight mb-2">${product.description}</p>
                                </div>
                                
                                <div>
                                    <!-- Price and Rating -->
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 my-2">
                                        <span class="text-sm sm:text-base font-extrabold text-gray-900">Rs. ${(product.selling_price*1.17).toFixed(2)}</span>
                                        <div class="flex items-center text-gray-500">
                                            <span class="text-[10px] sm:text-xs font-semibold">4.9</span>
                                            <span class="text-yellow-400 text-xs sm:text-sm ml-0.5">★</span>
                                        </div>                          
                                    </div>
                                </div>
                            </div>
                        </a>

                        <!-- Action Button container (avoid nesting buttons in anchors) -->
                        <div class="px-3 pb-3 sm:px-4 sm:pb-4">
                            <button onclick="window.location.href='/product/${product.id}'" class="px-3 py-1.5 sm:py-2 text-[10px] sm:text-xs md:text-sm font-semibold text-white bg-gray-900 rounded-lg w-full hover:bg-gray-800 active:bg-black transition duration-200 shadow-sm">
                                Quick View
                            </button>
                        </div>
                    `;
                    grid.appendChild(card);
                });

            } catch (error) {
                console.error("Failed to load products:", error);
                section.classList.add('hidden'); // Hide on error
            }
        };

        // Smooth scroll for internal hash links
        function initSmoothScroll() {
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const href = this.getAttribute('href');
                    if (href === '#' || !href.startsWith('#')) return;
                    const targetElement = document.getElementById(href.substring(1));
                    if (targetElement) {
                        e.preventDefault();
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                        history.pushState(null, null, href);
                    }
                });
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSmoothScroll);
        } else {
            initSmoothScroll();
        }