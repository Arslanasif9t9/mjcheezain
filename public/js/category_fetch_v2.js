// categories fetch 
        const mockFetchProducts = async (categoryName) => {
            const urlEncodedName = encodeURIComponent(categoryName);
            let response = await fetch(`/products/category?name=${urlEncodedName}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
            response = await response.json();
            
            const allProducts = response.data || [];
            const imagesMap = response.images || {};

            // Return a limited array (max 8 products) and images map
            return new Promise(resolve => {
                // Simulate network delay
                setTimeout(() => {
                    if (allProducts.length < 1) {
                        let mockProducts = [];
                        
                        if (categoryName === 'Fitness & Gym Equipment') {
                            mockProducts = [
                                {
                                    id: 101,
                                    name: 'Premium Adjustable Dumbbell Set',
                                    description: 'High quality adjustable dumbbells ranging from 5 to 50 lbs.',
                                    selling_price: 120,
                                    original_price: 150,
                                    rating: 4.8,
                                    quantity: 15,
                                    pcondition: 'New',
                                    mock_image_url: 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=400'
                                },
                                {
                                    id: 102,
                                    name: 'Foldable Electric Treadmill',
                                    description: 'Smart space-saving treadmill with LCD display and 12 preset programs.',
                                    selling_price: 299,
                                    original_price: 399,
                                    rating: 4.7,
                                    quantity: 8,
                                    pcondition: 'New',
                                    mock_image_url: 'https://images.unsplash.com/photo-1541534741688-6078c6bfb5c5?w=400'
                                }
                            ];
                        } else if (categoryName === 'Bundle Sales') {
                            mockProducts = [
                                {
                                    id: 201,
                                    name: 'Complete Matte Makeup Bundle',
                                    description: 'All-in-one bundle featuring lipstick, primer, foundation, and eyeliner.',
                                    selling_price: 59,
                                    original_price: 80,
                                    rating: 4.9,
                                    quantity: 25,
                                    pcondition: 'New',
                                    mock_image_url: 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?w=400'
                                },
                                {
                                    id: 202,
                                    name: 'Organic Skincare Glow Kit',
                                    description: 'Includes vitamin C serum, daily moisturizer, and gentle facial cleanser.',
                                    selling_price: 45,
                                    original_price: 60,
                                    rating: 4.8,
                                    quantity: 30,
                                    pcondition: 'New',
                                    mock_image_url: 'https://images.unsplash.com/photo-1601049541289-9b1b7bbbfe19?w=400'
                                }
                            ];
                        } else if (categoryName === 'Auto Parts & Accessories') {
                            mockProducts = [
                                {
                                    id: 301,
                                    name: 'Premium Car Polisher & Detailing Kit',
                                    description: 'Complete professional detailing kit for polishing and waxing your vehicle.',
                                    selling_price: 35,
                                    original_price: 45,
                                    rating: 4.7,
                                    quantity: 12,
                                    pcondition: 'New',
                                    mock_image_url: 'https://images.unsplash.com/photo-1542282088-fe8426682b8f?w=400'
                                },
                                {
                                    id: 302,
                                    name: 'High-Intensity LED Headlight Bulbs',
                                    description: 'Ultra-bright headlights with cooling fan and easy plug-and-play installation.',
                                    selling_price: 24,
                                    original_price: 35,
                                    rating: 4.6,
                                    quantity: 40,
                                    pcondition: 'New',
                                    mock_image_url: 'https://images.unsplash.com/photo-1617788138017-80ad40651399?w=400'
                                }
                            ];
                        }
                        
                        resolve({ products: mockProducts, images: {} });
                    } else {
                        resolve({ products: allProducts.slice(0, 8), images: imagesMap });
                    }
                }, 100);
            });
        };

        // --- Component Logic Function ---
        const loadCategoryProducts = async (categoryName, id) => {
            const section = document.getElementById(`${id}-products-section`);
            const titleElement = document.getElementById(`${id}-title`);
            const grid = document.getElementById(`${id}-product-grid`);

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

                // 3. Render Product Cards into an off-DOM fragment, then swap in one go
                const fragment = document.createDocumentFragment();
                products.forEach(product => {
                    const productImages = images[product.id];
                    const hasImage = productImages && productImages.length > 0 && productImages[0].image_path;
                    const imgUrl = hasImage 
                        ? `/storage/vendor/products/images/${productImages[0].image_path}` 
                        : (product.mock_image_url || `/img/default_img.png`);

                    const card = document.createElement('div');
                    
                    // Card styling
                    card.className = 'card-hover-glow bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transition duration-300 min-w-0 flex flex-col justify-between relative';
                    
                    // Product Card HTML Structure
                    card.innerHTML = `
                        <!-- Wishlist Button -->
                        <div class="absolute top-2.5 right-2.5 z-10">
                            <button id="heart-btn" data-product-id="${product.id}" class="p-1.5 sm:p-2 bg-white/90 backdrop-blur-sm rounded-full shadow-md hover:bg-gray-100 transition active:scale-95" onclick="event.preventDefault(); event.stopPropagation();">
                                <svg id="heart-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" class="w-[18px] h-[18px] sm:w-5 sm:h-5 text-gray-700">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Product Link -->
                        <a href="/product/${product.id}" class="no-underline flex flex-col flex-grow">
                            <!-- Product Image -->
                            <div class="relative overflow-hidden aspect-[4/3] bg-gray-50">
                                <img src="${imgUrl}" alt="${product.name}" loading="lazy" onload="this.classList.add('is-loaded')"
                                    class="fade-in-img w-full h-28 sm:h-36 md:h-44 object-cover transition duration-500 ease-in-out group-hover:scale-105">
                            </div>

                            <!-- Product Details -->
                            <div class="p-2.5 sm:p-3.5 flex-grow flex flex-col justify-between">
                                <div>
                                    <h3 class="text-xs sm:text-base font-bold text-gray-900 mb-0.5 truncate group-hover:text-pink-600 transition-colors duration-200">${product.name}</h3>
                                    <p class="hidden sm:block text-[11px] sm:text-xs text-gray-500 h-5 sm:h-6 overflow-hidden line-clamp-1 leading-tight mb-2">${product.description}</p>
                                </div>
                                
                                <div>
                                    <!-- Price and Rating -->
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-1 my-1.5">
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
                        <div class="px-2.5 pb-2.5 sm:px-3.5 sm:pb-3.5">
                            <button onclick="window.location.href='/product/${product.id}'" class="btn-brand-gradient px-3 py-1 sm:py-1.5 text-[10px] sm:text-xs md:text-sm font-semibold rounded-lg w-full transition duration-200 shadow-sm">
                                Quick View
                            </button>
                        </div>
                    `;
                    fragment.appendChild(card);
                });

                // Swap the skeleton placeholders out for the finished cards in one go
                grid.innerHTML = '';
                grid.appendChild(fragment);

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
