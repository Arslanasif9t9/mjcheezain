<!-- resources/views/components/categories.blade.php -->


<!-- Category Section -->
    <section class="py-12 px-2 md:px-6 lg:px-8 mx-auto" style="max-width: 100vw">
        <div class="mx-auto text-center">

            <!-- Heading -->
            <span class="section-kicker justify-center">Curated For You</span>
            <h2 class="font-serif text-3xl md:text-5xl font-extrabold text-gray-900 mb-2">
                Shop by Category
            </h2>
            <div class="brand-divider"></div>

            <!-- Subtitle -->
            <p class="text-sm sm:text-lg text-gray-600 mt-4 mb-8 sm:mb-12">
                Explore our selected collections from premium local and global brands.
            </p>

            <!-- Category Slider (horizontal left-right scroll) -->
            <div class="flex overflow-x-auto gap-3 md:gap-4 px-1 md:px-0 pb-3 snap-x snap-mandatory scrollbar-none scroll-smooth">

                <!-- 1. Cosmetics -->
                <a href="{{ url('cosmetics') }}" class="group w-[60vw] sm:w-56 md:w-64 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm bg-gray-900">
                        <img src="{{ asset('img/categories/cosmetics.png') }}"
                             alt="Cosmetics"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-sm sm:text-base font-bold text-gray-900 tracking-wide truncate">Cosmetics</h3>
                </a>

                <!-- 2. Skincare -->
                <a href="{{ url('cosmetics') }}" class="group w-[60vw] sm:w-56 md:w-64 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm bg-gray-900">
                        <img src="{{ asset('img/categories/skincare.png') }}"
                             alt="Skincare"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-sm sm:text-base font-bold text-gray-900 tracking-wide truncate">Skincare</h3>
                </a>

                <!-- 3. Haircare -->
                <a href="{{ url('cosmetics') }}" class="group w-[60vw] sm:w-56 md:w-64 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm bg-gray-900">
                        <img src="{{ asset('img/categories/haircare.png') }}"
                             alt="Haircare"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-sm sm:text-base font-bold text-gray-900 tracking-wide truncate">Haircare</h3>
                </a>

                <!-- 4. Fragrances -->
                <a href="{{ url('cosmetics') }}" class="group w-[60vw] sm:w-56 md:w-64 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm bg-gray-900">
                        <img src="{{ asset('img/categories/fragrances.png') }}"
                             alt="Fragrances"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-sm sm:text-base font-bold text-gray-900 tracking-wide truncate">Fragrances</h3>
                </a>

                <!-- 5. Accessories -->
                <a href="{{ url('cosmetics') }}" class="group w-[60vw] sm:w-56 md:w-64 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm bg-gray-900">
                        <img src="{{ asset('img/categories/accessories.png') }}"
                             alt="Accessories"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-sm sm:text-base font-bold text-gray-900 tracking-wide truncate">Accessories</h3>
                </a>

            </div>

        </div>
    </section>
