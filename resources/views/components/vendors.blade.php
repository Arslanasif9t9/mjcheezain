    @php
        // Real featured vendors: sellers who have at least one APPROVED product.
        // Wrapped in try/catch so the home page can never 500 over this section.
        $featuredVendors = collect();
        try {
            $featuredVendors = \Illuminate\Support\Facades\DB::table('vendor_products as vp')
                ->join('users as u', 'vp.user_id', '=', 'u.user_id')
                ->leftJoin('vendor_basic_info as vbi', 'u.user_id', '=', 'vbi.user_id')
                ->where('vp.position', 'approved')
                ->where('u.type', 'vendor')
                ->groupBy('u.user_id', 'vbi.full_name', 'vbi.profile_picture', 'u.verified')
                ->select(
                    'u.user_id as id',
                    \Illuminate\Support\Facades\DB::raw("COALESCE(NULLIF(vbi.full_name, ''), 'MJ Store') as name"),
                    'vbi.profile_picture',
                    'u.verified as is_verified',
                    \Illuminate\Support\Facades\DB::raw('COUNT(vp.id) as product_count')
                )
                ->orderByDesc('product_count')
                ->limit(10)
                ->get()
                ->map(function ($v) {
                    return [
                        'id' => $v->id,
                        'name' => $v->name,
                        'location' => 'Pakistan',
                        'rating' => null,
                        'product_count' => (int) $v->product_count,
                        'is_verified' => (bool) $v->is_verified,
                        'image_url' => $v->profile_picture
                            ? asset('storage/vendor/profile/' . $v->profile_picture)
                            : asset('img/default_profile.webp'),
                    ];
                });
        } catch (\Throwable $e) {
            $featuredVendors = collect();
        }
    @endphp
    <script id="vendor-data" type="application/json">{!! $featuredVendors->toJson() !!}</script>

    <!-- Main Section Container (Will be hidden if no data is present) -->
    <section id="featured-vendors-section" class="py-10 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto hidden">

        <!-- Heading - Using font-serif utility class for an elegant look -->
            <span class="section-kicker">Trusted Sellers</span>
            <h2 class="font-serif text-3xl sm:text-4xl md:text-5xl font-extrabold text-gray-900 mb-2 sm:mb-2">
                Featured Vendors
            </h2>
            <div class="brand-divider brand-divider-left"></div>
            
            <!-- Subtitle -->
            <p class="text-sm sm:text-lg text-gray-600 mt-4 mb-8 sm:mb-12">
                Discover trusted premium sellers from Pakistan and beyond.
            </p>
        
        <!-- Vendor Grid - Responsive 2-row layout (up to 5 columns) -->
        <div id="vendor-grid" 
             class="grid gap-3 md:gap-6 
                    grid-cols-2          /* 2 columns on small mobile */
                    sm:grid-cols-3       /* 3 columns on tablet */
                    lg:grid-cols-4       /* 4 columns on small desktop */
                    auto-rows-fr">
            <!-- Skeleton placeholders shown instantly; JS below replaces this innerHTML once real vendor cards are ready -->
            <div class="skeleton-shimmer rounded-2xl h-40 sm:h-48"></div>
            <div class="skeleton-shimmer rounded-2xl h-40 sm:h-48"></div>
            <div class="skeleton-shimmer rounded-2xl h-40 sm:h-48 hidden sm:block"></div>
            <div class="skeleton-shimmer rounded-2xl h-40 sm:h-48 hidden lg:block"></div>
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

            // Clear the skeleton placeholders before rendering real cards
            grid.innerHTML = '';

            // 3. Render Vendor Cards
            vendorsToDisplay.forEach(vendor => {
                const card = document.createElement('a');
                card.href = `/vendor-products/${vendor.id}`;
                
                // Tailwind classes for the card container
                card.className = 'card-hover-glow bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden group transition-all duration-300 block no-underline';
                
                // HTML for a single vendor card
                card.innerHTML = `
                    <div class="relative h-28 sm:h-32 md:h-36 overflow-hidden bg-gray-50">
                        <img src="${vendor.image_url}" alt="${vendor.name} store front" loading="lazy" onload="this.classList.add('is-loaded')"
                             class="fade-in-img w-full h-full object-cover transition duration-700 ease-in-out group-hover:scale-105 brightness-95">
                        
                        ${(vendor.rating !== null && vendor.rating !== undefined && vendor.rating !== '') ? `
                        <!-- Rating Badge Overlay -->
                        <div class="absolute bottom-2 left-2 bg-white/95 backdrop-blur-sm px-1.5 py-0.5 rounded-full text-[10px] font-bold text-gray-800 shadow-sm flex items-center space-x-1">
                            <span class="text-amber-500">★</span>
                            <span>${vendor.rating}</span>
                        </div>
                        ` : ''}

                        ${vendor.is_verified ? `
                            <!-- Verified Badge Overlay -->
                            <span class="absolute top-2 right-2 px-1.5 py-0.5 text-[9px] font-bold rounded-full btn-brand-gradient shadow-sm flex items-center space-x-0.5">
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