<!-- Hide scrollbar for better look -->
<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<section id="{{ $id }}-products-section" class="py-16 px-4 sm:px-6 lg:px-8 max-w-full mx-auto hidden relative">

    <!-- Category Title -->
    <h2 id="{{ $id }}-title" class="text-3xl font-bold text-gray-900 mb-8 sm:text-left"></h2>

    <!-- Scroll Buttons -->
    <button id="{{ $id }}-scroll-left"
            class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white shadow-md rounded-full p-2 z-10 hidden">
        &#10094;
    </button>
    <button id="{{ $id }}-scroll-right"
            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/80 hover:bg-white shadow-md rounded-full p-2 z-10 hidden">
        &#10095;
    </button>

    <!-- Scrollable Container -->
    <div class="relative overflow-hidden">
        <div id="{{ $id }}-scroll-wrapper" class="overflow-x-auto scroll-smooth scrollbar-hide">
            <div id="{{ $id }}-product-grid"
                 class="flex space-x-12 w-max">
                <!-- Product cards injected by JS -->
            </div>
        </div>
    </div>
</section>

    <script>
       document.addEventListener('DOMContentLoaded', () => {
            const categoryProp = `{{ $category }}`.replaceAll('&amp;', '&');
            const id = `{{ $id }}`;
            const grid = document.getElementById(`${id}-product-grid`);
            const wrapper = document.getElementById(`${id}-scroll-wrapper`);
            const leftBtn = document.getElementById(`${id}-scroll-left`);
            const rightBtn = document.getElementById(`${id}-scroll-right`);

            loadCategoryProducts(categoryProp, id);

            // --- Scroll Logic ---
            function updateButtons() {
                const maxScroll = wrapper.scrollWidth - wrapper.clientWidth;
                leftBtn.classList.toggle('hidden', wrapper.scrollLeft <= 0);
                rightBtn.classList.toggle('hidden', wrapper.scrollLeft >= maxScroll - 5);
            }

            leftBtn.addEventListener('click', () => {
                wrapper.scrollBy({ left: -wrapper.clientWidth * 1, behavior: 'smooth' });
                setTimeout(updateButtons, 400);
            });

            rightBtn.addEventListener('click', () => {
                wrapper.scrollBy({ left: wrapper.clientWidth * 1, behavior: 'smooth' });
                setTimeout(updateButtons, 400);
            });

            wrapper.addEventListener('scroll', updateButtons);
            window.addEventListener('resize', updateButtons);
            setTimeout(updateButtons, 1000);
        });
    </script>

