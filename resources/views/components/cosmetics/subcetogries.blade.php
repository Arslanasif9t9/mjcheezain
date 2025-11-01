    <section class="py-20 px-8 sm:px-6 lg:px-8" style="max-width: 100vw">
        <div class="mx-auto text-center">
            
            <!-- Heading - Using font-serif utility class for an elegant look -->
            <h2 class="font-serif text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Shop by Beauty Category 
            </h2>
            
            <!-- Subtitle -->
            <p class="text-lg text-gray-600 mb-12">
                MJ Whitening Cream — soft, smooth, and naturally bright skin
            </p>

            <!-- Category Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">

                <!-- 1. Cosmetics (Dark background) -->
                <!-- group class allows hover effects on the card to affect the inner image -->
                <a href="#" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <!-- Image with all hover effects via utility classes -->
                    {{-- <img src="https://placehold.co/400x600/1a1a1a/f5e8b4?text=Cosmetics"  --}}
                    <img src="{{ asset('img/short_logo.jpeg') }}" 
                         alt="Cosmetics" 
                         class="w-full h-full object-cover absolute inset-0 
                                brightness-[.6] 
                                transition duration-500 ease-in-out 
                                group-hover:scale-105 
                                group-hover:brightness-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-left">
                        <h3 class="text-2xl font-bold text-white mb-2 drop-shadow-md">Cosmetics</h3>
                        <p class="text-sm text-gray-200 mb-4 drop-shadow-sm">Premium makeup & beauty essentials</p>
                        <span class="text-sm font-semibold text-white/80">1250+ Products</span>
                    </div>
                </a>

                <!-- 2. Skincare (Wood/Natural background) -->
                <a href="#" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/7a574a/f0f0f0?text=Skincare" 
                         alt="Skincare" 
                         class="w-full h-full object-cover absolute inset-0 
                                brightness-[.7] 
                                transition duration-500 ease-in-out 
                                group-hover:scale-105 
                                group-hover:brightness-80">
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-left">
                        <h3 class="text-2xl font-bold text-white mb-2 drop-shadow-md">Skincare</h3>
                        <p class="text-sm text-gray-200 mb-4 drop-shadow-sm">Luxury treatments & serums</p>
                        <span class="text-sm font-semibold text-white/80">980+ Products</span>
                    </div>
                </a>

                <!-- 3. Haircare (Light blue/Pastel background) -->
                <a href="#" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/b3d4e0/333333?text=Haircare" 
                         alt="Haircare" 
                         class="w-full h-full object-cover absolute inset-0 
                                brightness-[.8] 
                                transition duration-500 ease-in-out 
                                group-hover:scale-105 
                                group-hover:brightness-90">
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-left">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2 drop-shadow-md">Haircare</h3>
                        <p class="text-sm text-gray-700 mb-4 drop-shadow-sm">Professional styling & care</p>
                        <span class="text-sm font-semibold text-gray-700/80">950+ Products</span>
                    </div>
                </a>

                <!-- 4. Fragrances (Orange/Brown background) -->
                <a href="#" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/4c301c/f7e6c3?text=Fragrances" 
                         alt="Fragrances" 
                         class="w-full h-full object-cover absolute inset-0 
                                brightness-[.6] 
                                transition duration-500 ease-in-out 
                                group-hover:scale-105 
                                group-hover:brightness-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-left">
                        <h3 class="text-2xl font-bold text-white mb-2 drop-shadow-md">Fragrances</h3>
                        <p class="text-sm text-gray-200 mb-4 drop-shadow-sm">Exclusive perfumes & colognes</p>
                        <span class="text-sm font-semibold text-white/80">540+ Products</span>
                    </div>
                </a>
                
                <!-- 5. Accessories (Gold/Light background) -->
                <a href="#" class="group relative h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/f5e5c7/4a3a1f?text=Accessories" 
                         alt="Accessories" 
                         class="w-full h-full object-cover absolute inset-0 
                                brightness-[.7] 
                                transition duration-500 ease-in-out 
                                group-hover:scale-105 
                                group-hover:brightness-80">
                    <div class="absolute inset-0 flex flex-col justify-end p-6 text-left">
                        <h3 class="text-2xl font-bold text-white mb-2 drop-shadow-md">Accessories</h3>
                        <p class="text-sm text-gray-200 mb-4 drop-shadow-sm">Fine jewelry & style</p>
                        <span class="text-sm font-semibold text-white/80">820+ Products</span>
                    </div>
                </a>

            </div>
            
        </div>
    </section>
