<!-- resources/views/components/categories.blade.php -->


<!-- Category Section -->
    <section class="py-12 px-2 md:px-6 lg:px-8 mx-auto" style="max-width: 100vw">
        <div class="mx-auto text-center">
            
            <!-- Heading -->
            <h2 class="font-serif text-3xl md:text-5xl font-extrabold text-gray-900 mb-2">
                Shop by Category 
            </h2>
            
            <!-- Subtitle -->
            <p class="text-sm sm:text-lg text-gray-600 mb-8 sm:mb-12">
                Explore our selected collections from premium local and global brands.
            </p>
 
            <!-- Category Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 px-1 md:px-0">
 
                <!-- 1. Cosmetics (Featured wide card on mobile) -->
                <a href="{{ url('cosmetics') }}" class="group relative h-40 sm:h-64 md:h-80 col-span-2 md:col-span-1 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/cosmetics.png') }}" 
                         alt="Cosmetics" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Cosmetics</h3>
                    </div>
                </a>
 
                <!-- 2. Skincare -->
                <a href="{{ url('cosmetics') }}" class="group relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/skincare.png') }}" 
                         alt="Skincare" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Skincare</h3>
                    </div>
                </a>
 
                <!-- 3. Haircare -->
                <a href="{{ url('cosmetics') }}" class="group relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/haircare.png') }}" 
                         alt="Haircare" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Haircare</h3>
                    </div>
                </a>
 
                <!-- 4. Fragrances -->
                <a href="{{ url('cosmetics') }}" class="group relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/fragrances.png') }}" 
                         alt="Fragrances" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Fragrances</h3>
                    </div>
                </a>
                
                <!-- 5. Accessories -->
                <a href="{{ url('cosmetics') }}" class="group relative h-40 sm:h-64 md:h-80 rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">
                    <img src="{{ asset('img/categories/accessories.png') }}" 
                         alt="Accessories" 
                         class="w-full h-full object-cover absolute inset-0 
                                opacity-60
                                transition duration-500 ease-in-out 
                                group-hover:scale-110 group-hover:opacity-75">
                    <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                        <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">Accessories</h3>
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
