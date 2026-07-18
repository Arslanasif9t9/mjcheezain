@props(['product'])

<div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
    <img src="{{ $product->image_path ? asset('storage/vendor/products/images/' . $product->image_path) : asset('img/default_img.png') }}"
         alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-lg mb-3">
    <h3 class="text-lg font-semibold truncate">{{ $product->name }}</h3>
    <p class="text-gray-600 mb-1">Price: Rs {{ number_format($product->selling_price, 2) }}</p>
    <p class="text-gray-500 text-sm">Qty: {{ $product->quantity }}</p>
    <p class="text-xs mt-1 {{ $product->position === 'approved' ? 'text-green-600' : 'text-yellow-600' }}">
        Status: {{ ucfirst($product->position) }}
    </p>
</div>
