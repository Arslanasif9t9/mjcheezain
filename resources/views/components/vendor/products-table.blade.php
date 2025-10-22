@props(['products', 'activeTab'])

<!-- Products Table -->
<div class="bg-white shadow rounded p-4 overflow-x-auto">
    <table class="min-w-full text-left">
        <thead class="border-b bg-gray-50">
            <tr>
                <th class="p-4">ID</th>
                <th class="p-4">Image</th>
                <th class="p-4">Product Name</th>
                <th class="p-4">Category</th>
                <th class="p-4">Stock</th>
                <th class="p-4">Price</th>
                <th class="p-4">Status</th>
                <th class="p-4">Rating</th>
                <th class="p-4">Position</th>
                <th class="p-4">Action</th>
            </tr>
        </thead>
        <tbody>
            @if(count($products) > 0)
                @foreach($products as $product)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="p-4">
                            <a href="{{ route('products.show', $product->id) }}" target="_blank" 
                               class="text-blue-500 hover:text-blue-700">
                                {{ $product->id }}
                            </a>
                        </td>
                        <td class="p-4">
                            <a href="{{ route('products.show', $product->id) }}" target="_blank">
                                <img style="width: 50px !important; height: 50px !important; border-radius: 10px;" 
                                     src="{{ $product->primary_image ? asset($product->primary_image) : asset('img/default-product.jpg') }}" 
                                     alt="{{ $product->name }}"
                                     class="object-cover">
                            </a>
                        </td>
                        <td class="p-4 font-semibold">
                            <a href="{{ route('products.show', $product->id) }}" target="_blank"
                               class="hover:text-blue-600 transition">
                                <p class="h-8 overflow-hidden leading-4 line-clamp-2">
                                    {{ $product->name }}
                                </p>
                            </a>
                        </td>
                        <td class="p-4">{{ $product->category }}</td>
                        <td class="p-4">{{ $product->quantity }}</td>
                        <td class="p-4">${{ number_format($product->selling_price, 2) }}</td>
                        <td class="p-4">
                            @php
                                $status_class = '';
                                $status_text = '';
                                if ($product->quantity > 10) {
                                    $status_class = 'bg-green-100 text-green-700';
                                    $status_text = 'In Stock';
                                } elseif ($product->quantity > 0) {
                                    $status_class = 'bg-yellow-100 text-yellow-700';
                                    $status_text = 'Limited';
                                } else {
                                    $status_class = 'bg-red-100 text-red-700';
                                    $status_text = 'Out of Stock';
                                }
                            @endphp
                            <span class="{{ $status_class }} px-2 py-1 text-xs rounded">
                                {{ $status_text }}
                            </span>
                        </td>
                        <td class="p-4">
                            @php
                                $rating = $product->rating ?? 0;
                                $full_stars = floor($rating);
                                $half_star = ($rating - $full_stars) >= 0.5 ? 1 : 0;
                                $empty_stars = 5 - $full_stars - $half_star;
                            @endphp
                            
                            @for($i = 0; $i < $full_stars; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                            
                            @if($half_star)
                                <i class="fas fa-star-half-alt text-yellow-400"></i>
                            @endif
                            
                            @for($i = 0; $i < $empty_stars; $i++)
                                <i class="far fa-star text-yellow-400"></i>
                            @endfor
                        </td>
                        <td class="p-4">
                            @php
                                $position_map = [
                                    'online' => 'Online',
                                    'pending' => 'Pending',
                                    'offline' => 'Offline',
                                    'draft' => 'Draft'
                                ];
                            @endphp
                            {{ $position_map[$product->position] ?? ucfirst($product->position) }}
                        </td>
                        <td class="p-4 flex space-x-3">
                            <a href="{{ route('products.show', $product->id) }}" target="_blank" 
                               class="text-blue-500 hover:text-blue-700 transition" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form method="POST" action="{{ route('vendor.products.destroy', $product->id) }}" 
                                  class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Delete">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10" class="p-4 text-center text-gray-500">
                        No products found
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>