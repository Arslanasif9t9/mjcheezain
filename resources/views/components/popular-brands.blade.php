<section class="popular-brands bg-white p-4 m-auto">
    <h2 class="font-bold">Popular Brands</h2>
    <div class="pupular-con relative">
        <button
            class="left-btn absolute left-[-15px] top-[50%] translate-y-[-50%] bg-white border-black border-2 rounded-full w-[40px] h-[40px]">
            <i class="fa-solid fa-arrow-left text-xl"></i>
        </button>

        <div class="w-100 border-[red] border-0 overflow-hidden">
            <div class="pb-con flex gap-8 overflow-x-auto w-max">
                @if(!empty($brands))
                    @foreach($brands as $brand)
                        <a href="{{ $brand['link'] ?? '#' }}" class="w-32">
                            <img 
                                src="{{ asset($brand['image']) }}" 
                                alt="{{ $brand['name'] ?? 'Brand' }}" 
                                width="128" 
                                height="128"
                                class="rounded-md border hover:opacity-80 transition"
                            >
                        </a>
                    @endforeach
                @else
                    <p class="text-gray-500">No brands available.</p>
                @endif
            </div>
        </div>

        <button
            class="right-btn absolute right-[-15px] top-[50%] translate-y-[-50%] bg-white border-black border-2 rounded-full w-[40px] h-[40px]">
            <i class="fa-solid fa-arrow-right text-xl"></i>
        </button>
    </div>
</section>
