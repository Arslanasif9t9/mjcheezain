<section class="bg-white p-4 mx-auto my-4">
    <h2 class="font-bold mb-4">Biggest Savings</h2>

    <div class="flex gap-6 overflow-x-auto">
        @forelse($products as $product)
            <a href="{{ url('product/'.$product->id) }}" class="w-[200px] flex-shrink-0">
                <img src="{{ $product->primaryImage->image_path ?? asset('img/default_img.png') }}" 
                     alt="{{ $product->name }}" class="rounded-md h-[200px] w-full object-cover">
                <p class="my-1 truncate">{{ $product->name }}</p>
                <p class="text-[#c50] font-bold">{{ $product->mrp }} PKR</p>
                <p class="text-green-600">Save {{ $product->selling_price - $product->mrp }} PKR</p>
                <p class="text-sm text-gray-500">{{ $product->updated_at->diffForHumans() }}</p>
            </a>
        @empty
            <p>No discounted products found.</p>
        @endforelse
    </div>
</section>
