



        // categories fetch 
        const mockFetchProducts = (categoryName) => {
            console.log("Simulating API fetch for category:", categoryName);
            
            // Mock data structure
            const allProducts = [
                {
                    "name": "Gourmet Cheese Wheel",
                    "description": "A rich and creamy cheese perfect for any occasion.",
                    "price": "$25.99",
                    "image_url": "https://placehold.co/400x300/e0e0e0/333333?text=Cheese+1"
                },
                {
                    "name": "Assorted Cheese Platter",
                    "description": "A selection of premium cheeses to delight your palate.",
                    "price": "$45.00",
                    "image_url": "https://placehold.co/400x300/d0d0d0/333333?text=Cheese+2"
                },
                {
                    "name": "Classic Cheddar Block",
                    "description": "Perfect for sandwiches and snacking.",
                    "price": "$15.49",
                    "image_url": "https://placehold.co/400x300/c0c0c0/333333?text=Cheese+3"
                },
                {
                    "name": "Creamy Brie Wheel",
                    "description": "Soft and smooth, ideal for appetizers.",
                    "price": "$18.99",
                    "image_url": "https://placehold.co/400x300/b0b0b0/333333?text=Cheese+4"
                },
                {
                    "name": "Blue Cheese Wedge",
                    "description": "Distinctive marbled texture for a bold taste.",
                    "price": "$18.75",
                    "image_url": "https://placehold.co/400x300/a0a0a0/333333?text=Cheese+5"
                },
                {
                    "name": "Shredded Mozzarella",
                    "description": "Ideal for pizza toppings and melting.",
                    "price": "$12.99",
                    "image_url": "https://placehold.co/400x300/909090/333333?text=Cheese+6"
                },
                {
                    "name": "Smoked Gouda",
                    "description": "Rich, nutty flavor with a smoky finish.",
                    "price": "$22.50",
                    "image_url": "https://placehold.co/400x300/808080/333333?text=Cheese+7"
                },
                {
                    "name": "Goat Cheese Logs",
                    "description": "Tangy and soft, great for salads.",
                    "price": "$14.00",
                    "image_url": "https://placehold.co/400x300/707070/333333?text=Cheese+8"
                },
                {
                    "name": "Extra Product",
                    "description": "Should not be displayed as it exceeds the 8-product limit.",
                    "price": "$1.00",
                    "image_url": "https://placehold.co/400x300/606060/333333?text=Cheese+9"
                }
            ];

            // Return a limited array (max 8 products)
            return new Promise(resolve => {
                // Simulate network delay
                setTimeout(() => {
                    if (categoryName === "Empty Category") {
                        resolve([]); // Simulate no products found
                    } else {
                        resolve(allProducts.slice(0, 8)); // Return up to 8 products
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
            section.classList.add('hidden'); // Ensure it's hidden before checking data

            try {
                const products = await mockFetchProducts(categoryName);
                
                if (products.length === 0) {
                    // 1. If no products are fetched, the section remains hidden
                    console.log(`No products found for "${categoryName}". Section hidden.`);
                    return;
                }

                // 2. If products are found, set the title and show the section
                titleElement.textContent = categoryName;
                section.classList.remove('hidden');

                // 3. Render Product Cards
                products.forEach(product => {
                    const card = document.createElement('div');
                    
                    // Card styling
                    card.className = 'bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transition duration-300';
                    
                    // Product Card HTML Structure
                    card.innerHTML = `
                        <div class="relative overflow-hidden aspect-w-4 aspect-h-3">
                            <img src="${product.image_url}" alt="${product.name}" 
                                 class="w-full h-full object-cover transition duration-300 ease-in-out group-hover:scale-125">
                        </div>

                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-1 truncate">${product.name}</h3>
                            <p class="text-sm text-gray-600 h-10 overflow-hidden">${product.description}</p>
                            
                            <div class="flex justify-between items-baseline my-3">
                                <span class="text-xl font-extrabold text-gray-900">${product.price}</span>
                                <div class="flex items-center">
                                <span class="font-semibold">4.99 </span>
                                    <span class="text-yellow-500 text-lg mr-1"> ★</span>
                                </div>                          
                            </div>
                            <!-- Quick View Button -->
                            <button class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg w-full 
                                            hover:bg-gray-700 transition duration-300 shadow-md">
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