    <section class="py-12 px-4 sm:px-6 lg:px-8 mx-auto" style="max-width: 100vw">
        <div class="mx-auto text-center">

            <!-- Heading -->
            <h2 class="font-serif text-3xl md:text-5xl font-extrabold text-gray-900 mb-2">
                Shop by Beauty Category
            </h2>

            <!-- Subtitle -->
            <p class="text-sm sm:text-lg text-gray-600 mb-8 sm:mb-12">
                MJ Whitening Cream — soft, smooth, and naturally bright skin
            </p>

            <style>
                .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
                .scrollbar-none::-webkit-scrollbar { display: none; }
            </style>

            <!-- Category Slider (horizontal left-right scroll) -->
            <div class="flex overflow-x-auto gap-4 pb-3 snap-x snap-mandatory scrollbar-none scroll-smooth">

                <!-- 1. Cosmetics -->
                <a href="#" class="group w-[65vw] sm:w-64 md:w-72 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                        <img src="{{ asset('img/categories/cosmetics.png') }}"
                             alt="Cosmetics"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl font-bold text-gray-900 truncate">Cosmetics</h3>
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 line-clamp-2 mt-0.5">Premium makeup & beauty essentials</p>
                    <span class="block text-[10px] sm:text-xs md:text-sm font-semibold text-gray-400 mt-0.5">1250+ Products</span>
                </a>

                <!-- 2. Skincare -->
                <a href="#" class="group w-[65vw] sm:w-64 md:w-72 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                        <img src="{{ asset('img/categories/skincare.png') }}"
                             alt="Skincare"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl font-bold text-gray-900 truncate">Skincare</h3>
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 line-clamp-2 mt-0.5">Luxury treatments & serums</p>
                    <span class="block text-[10px] sm:text-xs md:text-sm font-semibold text-gray-400 mt-0.5">980+ Products</span>
                </a>

                <!-- 3. Haircare -->
                <a href="#" class="group w-[65vw] sm:w-64 md:w-72 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                        <img src="{{ asset('img/categories/haircare.png') }}"
                             alt="Haircare"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl font-bold text-gray-900 truncate">Haircare</h3>
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 line-clamp-2 mt-0.5">Professional styling & care</p>
                    <span class="block text-[10px] sm:text-xs md:text-sm font-semibold text-gray-400 mt-0.5">950+ Products</span>
                </a>

                <!-- 4. Fragrances -->
                <a href="#" class="group w-[65vw] sm:w-64 md:w-72 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                        <img src="{{ asset('img/categories/fragrances.png') }}"
                             alt="Fragrances"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl font-bold text-gray-900 truncate">Fragrances</h3>
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 line-clamp-2 mt-0.5">Exclusive perfumes & colognes</p>
                    <span class="block text-[10px] sm:text-xs md:text-sm font-semibold text-gray-400 mt-0.5">540+ Products</span>
                </a>

                <!-- 5. Accessories -->
                <a href="#" class="group w-[65vw] sm:w-64 md:w-72 flex-shrink-0 snap-start cursor-pointer block">
                    <div class="relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                        <img src="{{ asset('img/categories/accessories.png') }}"
                             alt="Accessories"
                             class="w-full h-full object-cover absolute inset-0
                                    transition duration-500 ease-in-out
                                    group-hover:scale-110">
                    </div>
                    <h3 class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl font-bold text-gray-900 truncate">Accessories</h3>
                    <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 line-clamp-2 mt-0.5">Fine jewelry & style</p>
                    <span class="block text-[10px] sm:text-xs md:text-sm font-semibold text-gray-400 mt-0.5">820+ Products</span>
                </a>

            </div>

        </div>
    </section>
