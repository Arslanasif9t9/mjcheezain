
<!-- Main Section Container (Targeted by JS and initially hidden) -->
    <section id="{{ $id }}-products-section" class="py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto hidden">
        
        <!-- Category Title (Acts as the component header) -->
        <h2 id="{{ $id}}-title" class="text-3xl font-bold text-gray-900 mb-8 text-center sm:text-left">
            <!-- Title injected by JS -->
        </h2>
        
        <!-- Products Grid - Designed for 2 rows of 4 items -->
        <div id="{{ $id }}-product-grid" 
             class="grid gap-6 
                    grid-cols-2          /* 2 columns on small mobile */
                    sm:grid-cols-3       /* 3 columns on tablet */
                    lg:grid-cols-4       /* 4 columns on desktop (2 rows for 8 products) */
                    auto-rows-fr">
            <!-- Product cards will be injected here by JavaScript -->
        </div>

    </section>

    <script>
        // --- Mock API Fetch Function ---
        // Simulates fetching products based on the category name
        

        // --- Laravel Component Integration Simulation ---
        // On DOMContentLoaded, simulate receiving the "categoryName" prop 
        // and loading the data.

        document.addEventListener('DOMContentLoaded', () => {
            // SIMULATING LARAVEL PROP:
            // This is where a Laravel component would pass the category name.
            // Replace "Gourmet Cheeses" with your dynamic prop value.
            const categoryProp = `{{ $category }}`.replaceAll('&amp;', '&'); 
            const id = `{{ $id }}`;
            
            loadCategoryProducts(categoryProp, id);

            // OPTIONAL: Test case to see the section hide (uncomment to test)
            // loadCategoryProducts("Empty Category");
        });
    </script>



{{-- <section class="bg-white p-4 mx-auto my-4">
        <h2 class="font-bold mb-4">{{ $category }}</h2>

        <div class="flex gap-4 overflow-x-auto flex-wrap">
            @forelse($products as $product)
                <a href="{{ url('product/'.$product->id) }}" class="w-[200px] flex-shrink-0">
                    <img src="{{ $product->primaryImage->image_path ?? asset('img/default_img.png') }}" 
                        alt="{{ $product->name }}" class="rounded-md h-[200px] w-full object-cover">
                    <p class="my-1 truncate">{{ $product->name }}</p>
                    <p class="font-semibold text-[#c50]">{{ $product->selling_price }} PKR</p>
                    <p class="text-sm text-gray-500">{{ $product->updated_at->diffForHumans() }}</p>
                </a>
            @empty
                <p>No products found in this category.</p>
            @endforelse
        </div>
    </section> --}}
