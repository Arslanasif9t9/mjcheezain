@php
    $related = $related ?? null;
@endphp

<style>
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
</style>

<!-- Main Section Container (Targeted by JS and initially hidden) -->
    <section id="{{ $id }}-products-section" class="py-12 sm:py-16 px-2 sm:px-6 lg:px-8 max-w-full mx-auto relative">

        <!-- Category Title (Acts as the component header) -->
        <div id="{{ $id }}-title-wrap" class="{{$related == true ? 'hidden' : ''}} mb-6 sm:mb-8 text-center sm:text-left">
            <span class="section-kicker justify-center sm:justify-start">Just For You</span>
            <h2 id="{{ $id}}-title" class="text-2xl sm:text-3xl font-bold text-gray-900">
                <!-- Title injected by JS -->
            </h2>
            <div class="brand-divider brand-divider-left mx-auto sm:mx-0"></div>
        </div>

        <!-- Scroll Buttons -->
        <button id="{{ $id }}-scroll-left"
                class="absolute left-1 sm:left-2 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white shadow-md rounded-full p-2 z-10 hidden" aria-label="Scroll left">
            &#10094;
        </button>
        <button id="{{ $id }}-scroll-right"
                class="absolute right-1 sm:right-2 top-1/2 transform -translate-y-1/2 bg-white/90 hover:bg-white shadow-md rounded-full p-2 z-10 hidden" aria-label="Scroll right">
            &#10095;
        </button>

        <!-- Products Slider (horizontal left-right scroll) -->
        <div id="{{ $id }}-scroll-wrapper" class="overflow-x-auto scroll-smooth scrollbar-none snap-x snap-mandatory pb-2">
            <div id="{{ $id }}-product-grid" class="flex gap-2 sm:gap-6 w-max">
                <!-- Skeleton placeholders shown instantly; replaced by real product cards once the fetch resolves -->
                <div class="skeleton-shimmer rounded-xl h-56 sm:h-72 w-[47vw] sm:w-56 md:w-64 flex-shrink-0"></div>
                <div class="skeleton-shimmer rounded-xl h-56 sm:h-72 w-[47vw] sm:w-56 md:w-64 flex-shrink-0"></div>
                <div class="skeleton-shimmer rounded-xl h-56 sm:h-72 w-[47vw] sm:w-56 md:w-64 flex-shrink-0 hidden sm:block"></div>
                <div class="skeleton-shimmer rounded-xl h-56 sm:h-72 w-[47vw] sm:w-56 md:w-64 flex-shrink-0 hidden lg:block"></div>
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryProp = "{!! $category !!}";
            const id = `{{ $id }}`;
            const wrapper = document.getElementById(`${id}-scroll-wrapper`);
            const leftBtn = document.getElementById(`${id}-scroll-left`);
            const rightBtn = document.getElementById(`${id}-scroll-right`);

            try {
                loadCategoryProducts(categoryProp, id);
            } catch (err) {
                console.error("Error calling loadCategoryProducts:", err);
            }

            // --- Slider Scroll Logic ---
            function updateButtons() {
                if (!wrapper) return;
                const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
                leftBtn.classList.toggle('hidden', wrapper.scrollLeft <= 0);
                rightBtn.classList.toggle('hidden', wrapper.scrollLeft >= maxScroll - 5);
            }

            if (wrapper && leftBtn && rightBtn) {
                leftBtn.addEventListener('click', () => {
                    wrapper.scrollBy({ left: -wrapper.clientWidth, behavior: 'smooth' });
                    setTimeout(updateButtons, 400);
                });

                rightBtn.addEventListener('click', () => {
                    wrapper.scrollBy({ left: wrapper.clientWidth, behavior: 'smooth' });
                    setTimeout(updateButtons, 400);
                });

                wrapper.addEventListener('scroll', updateButtons);
                window.addEventListener('resize', updateButtons);

                // Products load asynchronously, so re-check button visibility
                // whenever the grid's children change (skeletons -> real cards)
                const grid = document.getElementById(`${id}-product-grid`);
                if (grid) {
                    new MutationObserver(updateButtons).observe(grid, { childList: true, subtree: true });
                }
                updateButtons();
            }
        });
    </script>
