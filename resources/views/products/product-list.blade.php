<div class="mx-auto px-4 py-12">
        {{-- <h1 class="text-3xl font-bold text-center mb-8">Product List Display</h1> --}}
        
        <!-- Main Section Container -->
        <section id="products-section" class="py-12 px-4 sm:px-6 lg:px-8  mx-auto bg-white rounded-lg shadow-md">
            
            <!-- Category Title -->
            <h2 id="section-title" class="text-3xl font-bold text-gray-900 mb-8 border-b pb-4">
                Gourmet Cheeses
            </h2>
            
            <!-- Products List - Vertical layout -->
            <div id="product-list" class="space-y-6">
                <!-- Product cards will be injected here by JavaScript -->
            </div>

        </section>
    </div>

    <script>
        // Mock product data
        const mockProducts = [
            {
                id: 1,
                name: "Aged Cheddar",
                description: "Rich and sharp, aged for 12 months to develop a complex flavor profile.",
                price: 18.99,
                image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=150&h=150&fit=crop&crop=center",
                rating: 4.5,
                inStock: true
            },
            {
                id: 2,
                name: "Brie de Meaux",
                description: "Soft, creamy French cheese with a delicate, buttery flavor.",
                price: 24.50,
                image: "https://images.unsplash.com/photo-1567306301408-9b74779a11af?w=150&h=150&fit=crop&crop=center",
                rating: 4.8,
                inStock: true
            },
            {
                id: 3,
                name: "Gouda",
                description: "Semi-hard cheese with a rich, unique flavor and smooth texture.",
                price: 15.75,
                image: "https://images.unsplash.com/photo-1541529086526-db283c563270?w=150&h=150&fit=crop&crop=center",
                rating: 4.3,
                inStock: false
            },
            {
                id: 4,
                name: "Roquefort",
                description: "A famous blue cheese from France with a sharp, tangy flavor.",
                price: 28.25,
                image: "https://images.unsplash.com/photo-1563720223485-8d8e7d2c8c7b?w=150&h=150&fit=crop&crop=center",
                rating: 4.7,
                inStock: true
            },
            {
                id: 5,
                name: "Manchego",
                description: "Spanish cheese made from sheep's milk with a distinctive flavor.",
                price: 22.40,
                image: "https://images.unsplash.com/photo-1552767059-ce182ead6c1b?w=150&h=150&fit=crop&crop=center",
                rating: 4.4,
                inStock: true
            },
            {
                id: 6,
                name: "Mozzarella di Bufala",
                description: "Fresh Italian cheese made from buffalo milk, perfect for caprese.",
                price: 16.90,
                image: "https://images.unsplash.com/photo-1603561591411-07134e71a2a9?w=150&h=150&fit=crop&crop=center",
                rating: 4.6,
                inStock: true
            },
            {
                id: 7,
                name: "Gruyère",
                description: "Hard yellow cheese from Switzerland with a slightly nutty flavor.",
                price: 19.75,
                image: "https://images.unsplash.com/photo-1594489573251-376b3ed19b8c?w=150&h=150&fit=crop&crop=center",
                rating: 4.5,
                inStock: false
            },
            {
                id: 8,
                name: "Camembert",
                description: "Soft, creamy, surface-ripened cheese with a rich, buttery flavor.",
                price: 21.30,
                image: "https://images.unsplash.com/photo-1586201375761-83865001e31c?w=150&h=150&fit=crop&crop=center",
                rating: 4.7,
                inStock: true
            }
        ];

        // Function to render products in list format
        function renderProductList(products, containerId) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            
            products.forEach(product => {
                const productCard = document.createElement('div');
                productCard.className = 'product-card bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row';
                
                // Generate star rating
                const stars = generateStarRating(product.rating);
                
                productCard.innerHTML = `
                    <div class="md:w-1/4">
                        <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover">
                    </div>
                    <div class="p-6 md:w-3/4 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start">
                                <h3 class="text-xl font-semibold text-gray-900">${product.name}</h3>
                                <span class="text-lg font-bold text-indigo-700">$${product.price.toFixed(2)}</span>
                            </div>
                            <div class="flex items-center mt-2">
                                ${stars}
                                <span class="text-sm text-gray-500 ml-2">${product.rating}</span>
                            </div>
                            <p class="text-gray-600 mt-3">${product.description}</p>
                        </div>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="${product.inStock ? 'text-green-600' : 'text-red-600'} font-medium">
                                ${product.inStock ? '<i class="fas fa-check-circle mr-1"></i> In Stock' : '<i class="fas fa-times-circle mr-1"></i> Out of Stock'}
                            </span>
                            <button ${!product.inStock ? 'disabled' : ''} 
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors ${!product.inStock ? 'opacity-50 cursor-not-allowed' : ''}">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                `;
                
                container.appendChild(productCard);
            });
        }

        // Function to generate star rating HTML
        function generateStarRating(rating) {
            let stars = '';
            const fullStars = Math.floor(rating);
            const hasHalfStar = rating % 1 !== 0;
            
            // Full stars
            for (let i = 0; i < fullStars; i++) {
                stars += '<i class="fas fa-star text-yellow-400"></i>';
            }
            
            // Half star
            if (hasHalfStar) {
                stars += '<i class="fas fa-star-half-alt text-yellow-400"></i>';
            }
            
            // Empty stars
            const emptyStars = 5 - Math.ceil(rating);
            for (let i = 0; i < emptyStars; i++) {
                stars += '<i class="far fa-star text-yellow-400"></i>';
            }
            
            return stars;
        }

        // Initialize the product list when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            renderProductList(mockProducts, 'product-list');
        });
    </script>