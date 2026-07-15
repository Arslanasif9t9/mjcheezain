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

                    // ss10-style shared card (public/js/product-card.js)
                    const card = window.buildProductCard(product, imgUrl, 'slider');
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
