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
 
            <!-- Category Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 px-1 md:px-0">
 
                <!-- 1. Cosmetics (Featured wide card on mobile) -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 col-span-2 md:col-span-1 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/cosmetics.png') }}" 
                         alt="Cosmetics" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Cosmetics</h3>
                        <span class="hidden sm:flex items-center gap-1 text-[11px] font-semibold text-white/0 group-hover:text-white/90 mt-1 transition-all duration-300 -translate-x-1 group-hover:translate-x-0">
                            Shop now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>
 
                <!-- 2. Skincare -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/skincare.png') }}" 
                         alt="Skincare" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Skincare</h3>
                        <span class="hidden sm:flex items-center gap-1 text-[11px] font-semibold text-white/0 group-hover:text-white/90 mt-1 transition-all duration-300 -translate-x-1 group-hover:translate-x-0">
                            Shop now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>
 
                <!-- 3. Haircare -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/haircare.png') }}" 
                         alt="Haircare" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Haircare</h3>
                        <span class="hidden sm:flex items-center gap-1 text-[11px] font-semibold text-white/0 group-hover:text-white/90 mt-1 transition-all duration-300 -translate-x-1 group-hover:translate-x-0">
                            Shop now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>
 
                <!-- 4. Fragrances -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/fragrances.png') }}" 
                         alt="Fragrances" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Fragrances</h3>
                        <span class="hidden sm:flex items-center gap-1 text-[11px] font-semibold text-white/0 group-hover:text-white/90 mt-1 transition-all duration-300 -translate-x-1 group-hover:translate-x-0">
                            Shop now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>
                
                <!-- 5. Accessories -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/accessories.png') }}" 
                         alt="Accessories" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Accessories</h3>
                        <span class="hidden sm:flex items-center gap-1 text-[11px] font-semibold text-white/0 group-hover:text-white/90 mt-1 transition-all duration-300 -translate-x-1 group-hover:translate-x-0">
                            Shop now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </a>
 
            </div>
            
        </div>
    </section>
