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

            // Return a limited array (max 8 products) and images map.
            // Real products only — no demo/mock fallback. Empty category
            // resolves empty so loadCategoryProducts hides the section
            // instead of showing placeholder cards.
            // (There used to be an artificial 100ms setTimeout here.)
            return { products: allProducts.slice(0, 8), images: imagesMap };
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
