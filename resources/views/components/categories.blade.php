<!-- resources/views/components/categories.blade.php -->

<section class="popular-brands bg-white p-4 m-auto">
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
</section>
