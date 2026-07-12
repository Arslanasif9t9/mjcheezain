@php
    $related = $related ?? null;
@endphp

<!-- Main Section Container (Targeted by JS and initially hidden) -->
    <section id="{{ $id }}-products-section" class="py-12 sm:py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto">
        
        <!-- Category Title (Acts as the component header) -->
        <div id="{{ $id }}-title-wrap" class="{{$related == true ? 'hidden' : ''}} mb-6 sm:mb-8 text-center sm:text-left">
            <span class="section-kicker justify-center sm:justify-start">Just For You</span>
            <h2 id="{{ $id}}-title" class="text-2xl sm:text-3xl font-bold text-gray-900">
                <!-- Title injected by JS -->
            </h2>
            <div class="brand-divider brand-divider-left mx-auto sm:mx-0"></div>
        </div>
        
        <!-- Products Grid - Designed for 2 rows of 4 items -->
        <div id="{{ $id }}-product-grid" 
             class="grid gap-6 
                    grid-cols-2          /* 2 columns on small mobile */
                    sm:grid-cols-3       /* 3 columns on tablet */
                    lg:grid-cols-4       /* 4 columns on desktop (2 rows for 8 products) */
                    auto-rows-fr">
            <!-- Skeleton placeholders shown instantly; replaced by real product cards once the fetch resolves -->
            <div class="skeleton-shimmer rounded-xl h-56 sm:h-72"></div>
            <div class="skeleton-shimmer rounded-xl h-56 sm:h-72"></div>
            <div class="skeleton-shimmer rounded-xl h-56 sm:h-72 hidden sm:block"></div>
            <div class="skeleton-shimmer rounded-xl h-56 sm:h-72 hidden lg:block"></div>
        </div>

    </section>

    <script>

        document.addEventListener('DOMContentLoaded', () => {
            const categoryProp = `{{ $category }}`.replaceAll('&amp;', '&'); 
            const id = `{{ $id }}`;
            
            loadCategoryProducts(categoryProp, id);

        });
    </script>


