<!-- 
        MOCK DATA: This script block contains the JSON data 
        that would typically be fetched from an API endpoint.
    -->
    <script id="vendor-data" type="application/json">
        [
            {
                "id": 1,
                "name": "Lumière Paris",
                "tagline": "Luxury Skincare & Cosmetics",
                "location": "Paris, France",
                "rating": 4.9,
                "product_count": 156,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/8b7d79/f5f5f5?text=Lumière+Paris"
            },
            {
                "id": 2,
                "name": "Bella Essence",
                "tagline": "Premium Fragrances",
                "location": "Milan, Italy",
                "rating": 5,
                "product_count": 89,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/f0f0f0/333333?text=Bella+Essence"
            },
            {
                "id": 3,
                "name": "Luxe Tokyo",
                "tagline": "Asian Beauty Innovations",
                "location": "Tokyo, Japan",
                "rating": 4.8,
                "product_count": 203,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/333333/f5f5f5?text=Luxe+Tokyo"
            },
            {
                "id": 4,
                "name": "Maison Beauté",
                "tagline": "Artisanal Cosmetics",
                "location": "New York, USA",
                "rating": 4.9,
                "product_count": 124,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/8b7d79/f5f5f5?text=Maison+Beauté"
            },
            {
                "id": 5,
                "name": "Golden Glow",
                "tagline": "Organic Skincare Solutions",
                "location": "California, USA",
                "rating": 4.7,
                "product_count": 92,
                "is_verified": false,
                "image_url": "https://placehold.co/400x400/d4af37/333333?text=Golden+Glow"
            },
            {
                "id": 6,
                "name": "Aether & Co.",
                "tagline": "High-End Hair Products",
                "location": "London, UK",
                "rating": 5,
                "product_count": 45,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/5c5c5c/f0f0f0?text=Aether+%26+Co."
            },
            {
                "id": 7,
                "name": "Silk Road",
                "tagline": "Handmade Silk Accessories",
                "location": "Chengdu, China",
                "rating": 4.6,
                "product_count": 310,
                "is_verified": false,
                "image_url": "https://placehold.co/400x400/e9c46a/333333?text=Silk+Road"
            },
            {
                "id": 8,
                "name": "Urban Edge",
                "tagline": "Modern Apparel & Jewelry",
                "location": "Berlin, Germany",
                "rating": 4.8,
                "product_count": 188,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/34495e/f0f0f0?text=Urban+Edge"
            },
            {
                "id": 9,
                "name": "Pure Botanicals",
                "tagline": "Natural Bath & Body",
                "location": "Sydney, Australia",
                "rating": 4.9,
                "product_count": 76,
                "is_verified": true,
                "image_url": "https://placehold.co/400x400/c7f9cc/333333?text=Pure+Botanicals"
            },
            {
                "id": 10,
                "name": "The Vintage Spot",
                "tagline": "Curated Designer Finds",
                "location": "Paris, France",
                "rating": 4.5,
                "product_count": 55,
                "is_verified": false,
                "image_url": "https://placehold.co/400x400/a86464/f0f0f0?text=The+Vintage+Spot"
            }
        ]
    </script>

    <!-- Main Section Container (Will be hidden if no data is present) -->
    <section id="featured-vendors-section" class="py-16 px-8 sm:px-6 lg:px-8 max-w-full mx-auto hidden">

        <!-- Heading - Using font-serif utility class for an elegant look -->
            <h2 class="font-serif text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Featured Vendors
            </h2>
            
            <!-- Subtitle -->
            <p class="text-lg text-gray-600 mb-12">
                Discover trusted premium sellers from Pakistan and beyond.
            </p>
        
        <!-- Vendor Grid - Responsive 2-row layout (up to 5 columns) -->
        <div id="vendor-grid" 
             class="grid gap-6 
                    grid-cols-2          /* 2 columns on small mobile */
                    sm:grid-cols-3       /* 3 columns on tablet */
                    lg:grid-cols-4       /* 4 columns on small desktop */
                    {{-- xl:grid-cols-5       /* 5 columns on large desktop (2 rows for 10 vendors) */ --}}
                    auto-rows-fr">
            <!-- Vendor cards will be injected here by JavaScript -->
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const dataScript = document.getElementById('vendor-data');
            const section = document.getElementById('featured-vendors-section');
            const grid = document.getElementById('vendor-grid');

            // 1. Fetch and Parse Data
            let vendors = [];
            try {
                // In a real application, you would use fetch('/api/vendors') here.
                // For this example, we parse the JSON data from the script tag.
                const jsonText = dataScript.textContent.trim();
                vendors = JSON.parse(jsonText);
            } catch (error) {
                console.error("Error parsing vendor data:", error);
                // If data parsing fails, treat it as 0 vendors.
                vendors = [];
            }

            // 2. Conditional Visibility Check
            if (vendors.length === 0) {
                // If data receive 0 then whole section should not be visible (handled by hidden class)
                section.classList.add('hidden');
                return; 
            } else {
                // Show the section if vendors are present
                section.classList.remove('hidden');
            }
            
            // Limit vendors to 10 for the 2-row layout request
            const vendorsToDisplay = vendors.slice(0, 4);

            // 3. Render Vendor Cards
            vendorsToDisplay.forEach(vendor => {
                const card = document.createElement('div');
                
                // Tailwind classes for the card container (same style as the uploaded image)
                card.className = 'bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-2xl transition duration-300 transform hover:-translate-y-1';
                
                // HTML for a single vendor card
                card.innerHTML = `
                    <div class="relative h-32 sm:h-40 md:h-48 overflow-hidden bg-gray-50">
                        <img src="${vendor.image_url}" alt="${vendor.name} store front" 
                             class="w-full h-full object-cover transition duration-500 ease-in-out group-hover:scale-105 brightness-90">
                        ${vendor.is_verified ? `
                            <!-- Verified Badge -->
                            <span class="absolute top-2.5 right-2.5 px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full bg-yellow-400 text-gray-900 shadow-sm">
                                Verified
                            </span>
                        ` : ''}
                    </div>

                    <div class="p-3 sm:p-4">
                        <h3 class="text-sm sm:text-lg md:text-xl font-bold text-gray-900 truncate mb-0.5">${vendor.name}</h3>
                        <p class="text-[11px] sm:text-sm text-gray-500 mb-2 truncate">${vendor.tagline}</p>
                        
                        <!-- Location -->
                        <div class="flex items-center text-xs sm:text-sm text-gray-400">
                            <svg class="w-3.5 h-3.5 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.485L12 21.485l-5.657-5.002C3.343 13.485 2 11.235 2 9c0-5.523 4.477-10 10-10s10 4.477 10 10c0 2.235-1.343 4.485-3.343 6.485z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="truncate">${vendor.location}</span>
                        </div>

                        <!-- Stats -->
                        <div class="flex justify-between items-center text-xs sm:text-sm text-gray-500 border-t border-gray-100 pt-2 mt-2">
                            <div class="flex items-center">
                                <span class="text-yellow-500 text-base mr-0.5">★</span>
                                <span class="font-semibold">${vendor.rating}</span>
                            </div>
                            <div class="text-[10px] sm:text-xs text-gray-400">
                                ${vendor.product_count} products
                            </div>
                        </div>
                    </div>

                    <!-- Button at the bottom -->
                    <div class="px-3 pb-3 sm:px-4 sm:pb-4">
                         <a href="#" class="block w-full text-center py-1.5 sm:py-2 bg-gray-900 text-white text-xs sm:text-sm font-semibold rounded-lg hover:bg-gray-800 transition duration-200">
                            Visit Store
                        </a>
                    </div>
                `;
                grid.appendChild(card);
            });
        });
    </script>