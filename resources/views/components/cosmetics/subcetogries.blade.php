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
 
            <!-- Category Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
 
                <!-- 1. Cosmetics (Featured wide card on mobile) -->
                <a href="#" class="group relative h-48 sm:h-80 md:h-96 col-span-2 md:col-span-1 rounded-2xl overflow-hidden shadow-lg cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/cosmetics.png') }}" 
                         alt="Cosmetics" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6 text-left bg-gradient-to-t from-black to-transparent">
                        <h3 class="text-base sm:text-xl md:text-2xl font-bold text-white mb-1 sm:mb-2 drop-shadow-md">Cosmetics</h3>
                        <p class="text-[11px] sm:text-xs md:text-sm text-gray-200 mb-2 sm:mb-4 line-clamp-2 drop-shadow-sm">Premium makeup & beauty essentials</p>
                        <span class="text-[10px] sm:text-xs md:text-sm font-semibold text-gray-300">1250+ Products</span>
                    </div>
                </a>
 
                <!-- 2. Skincare -->
                <a href="#" class="group relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/skincare.png') }}" 
                         alt="Skincare" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6 text-left bg-gradient-to-t from-black to-transparent">
                        <h3 class="text-base sm:text-xl md:text-2xl font-bold text-white mb-1 sm:mb-2 drop-shadow-md">Skincare</h3>
                        <p class="text-[11px] sm:text-xs md:text-sm text-gray-200 mb-2 sm:mb-4 line-clamp-2 drop-shadow-sm">Luxury treatments & serums</p>
                        <span class="text-[10px] sm:text-xs md:text-sm font-semibold text-gray-300">980+ Products</span>
                    </div>
                </a>
 
                <!-- 3. Haircare -->
                <a href="#" class="group relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/haircare.png') }}" 
                         alt="Haircare" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6 text-left bg-gradient-to-t from-black to-transparent">
                        <h3 class="text-base sm:text-xl md:text-2xl font-bold text-white mb-1 sm:mb-2 drop-shadow-md">Haircare</h3>
                        <p class="text-[11px] sm:text-xs md:text-sm text-gray-200 mb-2 sm:mb-4 line-clamp-2 drop-shadow-sm">Professional styling & care</p>
                        <span class="text-[10px] sm:text-xs md:text-sm font-semibold text-gray-300">950+ Products</span>
                    </div>
                </a>
 
                <!-- 4. Fragrances -->
                <a href="#" class="group relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/fragrances.png') }}" 
                         alt="Fragrances" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6 text-left bg-gradient-to-t from-black to-transparent">
                        <h3 class="text-base sm:text-xl md:text-2xl font-bold text-white mb-1 sm:mb-2 drop-shadow-md">Fragrances</h3>
                        <p class="text-[11px] sm:text-xs md:text-sm text-gray-200 mb-2 sm:mb-4 line-clamp-2 drop-shadow-sm">Exclusive perfumes & colognes</p>
                        <span class="text-[10px] sm:text-xs md:text-sm font-semibold text-gray-300">540+ Products</span>
                    </div>
                </a>
                
                <!-- 5. Accessories -->
                <a href="#" class="group relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/accessories.png') }}" 
                         alt="Accessories" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6 text-left bg-gradient-to-t from-black to-transparent">
                        <h3 class="text-base sm:text-xl md:text-2xl font-bold text-white mb-1 sm:mb-2 drop-shadow-md">Accessories</h3>
                        <p class="text-[11px] sm:text-xs md:text-sm text-gray-200 mb-2 sm:mb-4 line-clamp-2 drop-shadow-sm">Fine jewelry & style</p>
                        <span class="text-[10px] sm:text-xs md:text-sm font-semibold text-gray-300">820+ Products</span>
                    </div>
                </a>
 
            </div>
            
        </div>
    </section>
