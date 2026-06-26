@php
    $related = $related ?? null;
@endphp

<!-- Main Section Container (Targeted by JS and initially hidden) -->
    <section id="{{ $id }}-products-section" class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
        
        <!-- Category Title (Acts as the component header) -->
        <h2 id="{{ $id}}-title" class="{{$related == true ? 'hidden' : ''}} text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8 sm:text-left text-center">
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

        document.addEventListener('DOMContentLoaded', () => {
            const categoryProp = `{{ $category }}`.replaceAll('&amp;', '&'); 
            const id = `{{ $id }}`;
            
            loadCategoryProducts(categoryProp, id);

        });
    </script>


