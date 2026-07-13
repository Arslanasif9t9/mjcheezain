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
<<<<<<< Updated upstream
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 px-1 md:px-0">
 
                <!-- 1. Cosmetics (Featured wide card on mobile) -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 col-span-2 md:col-span-1 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/cosmetics.png') }}" 
=======
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-3">

                <!-- 1. Cosmetics (Dark background) -->
                <!-- group class allows hover effects on the card to affect the inner image -->
                <a href="#" class="group relative h-48 sm:h-64 md:h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <!-- Image with all hover effects via utility classes -->
                    {{-- <img src="https://placehold.co/400x600/1a1a1a/f5e8b4?text=Cosmetics"  --}}
                    <img src="{{ asset('img/short_logo.jpeg') }}" 
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
 
                <!-- 2. Skincare -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/skincare.png') }}" 
=======

                <!-- 2. Skincare (Wood/Natural background) -->
                <a href="#" class="group relative h-48 sm:h-64 md:h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/7a574a/f0f0f0?text=Skincare" 
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
 
                <!-- 3. Haircare -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/haircare.png') }}" 
=======

                <!-- 3. Haircare (Light blue/Pastel background) -->
                <a href="#" class="group relative h-48 sm:h-64 md:h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/b3d4e0/333333?text=Haircare" 
>>>>>>> Stashed changes
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
<<<<<<< Updated upstream
 
                <!-- 4. Fragrances -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/fragrances.png') }}" 
=======

                <!-- 4. Fragrances (Orange/Brown background) -->
                <a href="#" class="group relative h-48 sm:h-64 md:h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/4c301c/f7e6c3?text=Fragrances" 
>>>>>>> Stashed changes
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
                
<<<<<<< Updated upstream
                <!-- 5. Accessories -->
                <a href="{{ url('cosmetics') }}" class="group card-hover-glow relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/accessories.png') }}" 
=======
                <!-- 5. Accessories (Gold/Light background) -->
                <a href="#" class="group relative h-48 sm:h-64 md:h-96 rounded-2xl overflow-hidden shadow-xl cursor-pointer block">
                    <img src="https://placehold.co/400x600/f5e5c7/4a3a1f?text=Accessories" 
>>>>>>> Stashed changes
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






{{-- <section class="popular-brands bg-white p-4 m-auto">
    <h2 class="font-bold">Categories</h2>
    <div class="pupular-con relative">
        <div class="cat-con flex gap-8 overflow-x-auto">
            @php
                $categories = [
                    ['name' => 'Fashion', 'img' => asset('img/fashion.png')],
                    ['name' => 'Autoparts', 'img' => asset('img/auroparts.png')],
                    ['name' => 'GYM', 'img' => asset('img/gym.png')],
                    ['name' => 'Jewellery', 'img' => asset('img/Jewellery.jpeg')],
                    ['name' => 'Fragrance', 'img' => asset('img/perfume.jpeg')],
                    ['name' => 'Vehicles', 'img' => asset('img/vehicle.jpeg')],
                    ['name' => 'Decoration', 'img' => asset('img/decoration.jpeg')],
                    ['name' => 'Furniture', 'img' => asset('img/ferniture.png')],
                    ['name' => 'Food', 'img' => asset('img/food.jpeg')],
                    ['name' => 'Shoes', 'img' => asset('img/shoes.jpeg')],
                ];
            @endphp

            @foreach ($categories as $cat)
                <a href="#" class="w-32 flex flex-col items-center">
                    <img src="{{ $cat['img'] }}" alt="{{ $cat['name'] }}" class="h-[80px] w-[80px] object-cover rounded-md">
                    <span class="text-center mt-2">{{ $cat['name'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section> --}}
