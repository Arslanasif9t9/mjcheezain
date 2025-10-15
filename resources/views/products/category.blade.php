<section class="bg-white p-4 mx-auto my-4">
    <h2 class="font-bold mb-4">{{ $category }}</h2>

    <div class="flex gap-4 overflow-x-auto flex-wrap">
        @forelse($products as $product)
            <a href="{{ url('product/'.$product->id) }}" class="w-[200px] flex-shrink-0">
                <img src="{{ $product->primaryImage->image_path ?? asset('img/default_img.png') }}" 
                     alt="{{ $product->name }}" class="rounded-md h-[200px] w-full object-cover">
                <p class="my-1 truncate">{{ $product->name }}</p>
                <p class="font-semibold text-[#c50]">{{ $product->selling_price }} PKR</p>
                <p class="text-sm text-gray-500">{{ $product->updated_at->diffForHumans() }}</p>
            </a>
        @empty
            <p>No products found in this category.</p>
        @endforelse
    </div>
</section>
