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
                "image_url": "https://images.unsplash.com/photo-1556228720-195a672e8a03?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 2,
                "name": "Bella Essence",
                "tagline": "Premium Fragrances",
                "location": "Milan, Italy",
                "rating": 5,
                "product_count": 89,
                "is_verified": true,
                "image_url": "https://images.unsplash.com/photo-1541643600914-78b084683601?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 3,
                "name": "Luxe Tokyo",
                "tagline": "Asian Beauty Innovations",
                "location": "Tokyo, Japan",
                "rating": 4.8,
                "product_count": 203,
                "is_verified": true,
                "image_url": "https://images.unsplash.com/photo-1598440947619-2c35fc9aa908?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 4,
                "name": "Maison Beauté",
                "tagline": "Artisanal Cosmetics",
                "location": "New York, USA",
                "rating": 4.9,
                "product_count": 124,
                "is_verified": true,
                "image_url": "https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 5,
                "name": "Golden Glow",
                "tagline": "Organic Skincare Solutions",
                "location": "California, USA",
                "rating": 4.7,
                "product_count": 92,
                "is_verified": false,
                "image_url": "https://images.unsplash.com/photo-1608248597279-f99d160bfcbc?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 6,
                "name": "Aether & Co.",
                "tagline": "High-End Hair Products",
                "location": "London, UK",
                "rating": 5,
                "product_count": 45,
                "is_verified": true,
                "image_url": "https://images.unsplash.com/photo-1562322140-8baeececf3df?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 7,
                "name": "Silk Road",
                "tagline": "Handmade Silk Accessories",
                "location": "Chengdu, China",
                "rating": 4.6,
                "product_count": 310,
                "is_verified": false,
                "image_url": "https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 8,
                "name": "Urban Edge",
                "tagline": "Modern Apparel & Jewelry",
                "location": "Berlin, Germany",
                "rating": 4.8,
                "product_count": 188,
                "is_verified": true,
                "image_url": "https://images.unsplash.com/photo-1513094735237-8f2714d57c13?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 9,
                "name": "Pure Botanicals",
                "tagline": "Natural Bath & Body",
                "location": "Sydney, Australia",
                "rating": 4.9,
                "product_count": 76,
                "is_verified": true,
                "image_url": "https://images.unsplash.com/photo-1540555700478-4be289fbecef?q=80&w=400&auto=format&fit=crop"
            },
            {
                "id": 10,
                "name": "The Vintage Spot",
                "tagline": "Curated Designer Finds",
                "location": "Paris, France",
                "rating": 4.5,
                "product_count": 55,
                "is_verified": false,
                "image_url": "https://images.unsplash.com/photo-1594913785162-e678537db36f?q=80&w=400&auto=format&fit=crop"
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
             class="grid gap-3 md:gap-6 
                    grid-cols-2          /* 2 columns on small mobile */
                    sm:grid-cols-3       /* 3 columns on tablet */
                    lg:grid-cols-4       /* 4 columns on small desktop */
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
                const card = document.createElement('a');
                card.href = `/vendor-products/${vendor.id}`;
                
                // Tailwind classes for the card container
                card.className = 'bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden group transition-all duration-300 transform hover:-translate-y-1 block no-underline';
                
                // HTML for a single vendor card
                card.innerHTML = `
                    <div class="relative h-28 sm:h-32 md:h-36 overflow-hidden bg-gray-50">
                        <img src="${vendor.image_url}" alt="${vendor.name} store front" 
                             class="w-full h-full object-cover transition duration-700 ease-in-out group-hover:scale-105 brightness-95">
                        
                        <!-- Rating Badge Overlay -->
                        <div class="absolute bottom-2 left-2 bg-white/95 backdrop-blur-sm px-1.5 py-0.5 rounded text-[10px] font-bold text-gray-800 shadow-sm flex items-center space-x-1">
                            <span class="text-amber-500">★</span>
                            <span>${vendor.rating}</span>
                        </div>

                        ${vendor.is_verified ? `
                            <!-- Verified Badge Overlay -->
                            <span class="absolute top-2 right-2 px-1.5 py-0.5 text-[9px] font-bold rounded bg-blue-600 text-white shadow-sm flex items-center space-x-0.5">
                                <i class="fa-solid fa-circle-check"></i>
                            </span>
                        ` : ''}
                    </div>

                    <div class="p-3">
                        <h3 class="text-sm font-bold text-gray-900 truncate group-hover:text-pink-600 transition duration-150 mb-0.5">${vendor.name}</h3>
                        <p class="text-[10px] text-gray-400 font-medium truncate flex items-center m-0">
                            <i class="fa-solid fa-location-dot mr-1"></i>
                            <span>${vendor.location}</span>
                            <span class="mx-1">•</span>
                            <span>${vendor.product_count} items</span>
                        </p>
                    </div>
                `;
                grid.appendChild(card);
            });
        });
    </script>