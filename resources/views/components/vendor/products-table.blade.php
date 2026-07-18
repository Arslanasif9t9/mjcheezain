@props(['products', 'activeTab'])

<!-- Products Table -->
<div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto w-full">
    <table class="min-w-[950px] w-full text-left">
        <thead class="border-b bg-gray-55/60">
            <tr class="text-xs font-semibold uppercase tracking-wider text-gray-500">
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
        <tbody id="product-table-body" style="font-size: 14px">
            @if(count($products) > 0)
                @foreach($products as $product)
                            @php
                                $position_map = [
                                    'online' => 'Online',
                                    'approved' => 'Online',
                                    'pending' => 'Pending',
                                    'offline' => 'Offline',
                                    'rejected' => 'Offline',
                                    'draft' => 'Draft'
                                ];
                                // echo $position_map[];
                            @endphp
                    <tr class="product-row border-b hover:bg-gray-50 transition" data-position="{{ $product->position ?? 'all' }}">
                        <td class="p-0 whitespace-nowrap">
                            <a href="/product/{{ $product->id }}" target="_blank" id="prd-id"
                               class="text-black hover:text-[#E85D85]">
                                PRD-{{ str_pad($product->id, 6, '0', STR_PAD_LEFT) }}-{{ \Carbon\Carbon::parse($product->updated_at)->format('y') }}
                            </a>
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <a href="/product/{{ $product->id }}" target="_blank">
                                <img style="width: 75px !important; height: 75px !important; border-radius: 10px;" 
                                     src="{{ $product->primary_image ? asset('storage/vendor/products/images/'.$product->primary_image) : asset('img/default_img.png') }}" 
                                     alt="{{ $product->name }}"
                                     class="object-cover">
                            </a>
                        </td>
                        <td class="p-2 font-semibold">
                            <a href="/product/{{ $product->id }}" target="_blank"
                               class="hover:text-[#E85D85] transition">
                                <p class="h-8 overflow-hidden leading-4 line-clamp-2">
                                    {{ $product->name }}
                                </p>
                            </a>
                        </td>
                        <td class="p-2 whitespace-nowrap">{{ $product->category }}</td>
                        <td class="p-2 whitespace-nowrap">{{ $product->quantity }}</td>
                        <td class="p-2 whitespace-nowrap">Rs. {{ number_format($product->selling_price*1.17, 2) }}</td>
                        <td class="p-2 whitespace-nowrap">
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
                        <td class="p-2 whitespace-nowrap">
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
                        <td class="p-2 whitespace-nowrap">
                            {{ $position_map[$product->position] ?? ucfirst($product->position) }}
                        </td>
                        <td class="p-2 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('vendor.products.edit', $product->id) }}" 
                                    class="w-8 h-8 rounded-lg bg-pink-50 text-[#E85D85] hover:bg-pink-100 transition flex items-center justify-center border border-pink-100 shadow-sm" title="Edit">
                                         <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('vendor.products.delete') }}" 
                                    class="delete-product-form inline m-0">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition flex items-center justify-center border border-red-100 shadow-sm" title="Delete">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
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
</div>


<script>
    // Delete product functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all delete forms
        const deleteForms = document.querySelectorAll('.delete-product-form');
        
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                deleteProduct(this);
            });
        });
        
        function deleteProduct(form) {
            if (!confirm('Are you sure you want to delete this product?')) {
                return;
            }
            
            const productId = form.querySelector('input[name="product_id"]').value;
            const url = form.getAttribute('action');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Show loading state
            const deleteButton = form.querySelector('button[type="submit"]');
            const originalHTML = deleteButton.innerHTML;
            deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            deleteButton.disabled = true;
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => {
                console.log(response);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                if (data.success) {
                    // Remove the product row from table
                    const productRow = form.closest('tr');
                    productRow.style.opacity = '0';
                    productRow.style.transition = 'opacity 0.3s ease';
                    
                    setTimeout(() => {
                        productRow.remove();
                        
                        // Show success message
                        showAlert('Product deleted successfully!', 'success');
                        
                        // Check if table is empty
                        checkEmptyTable();
                    }, 300);
                } else {
                    throw new Error(data.message || 'Failed to delete product');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert(error.message || 'Failed to delete product', 'error');
                
                // Reset button state
                deleteButton.innerHTML = originalHTML;
                deleteButton.disabled = false;
            });
        }
        
        function showAlert(message, type) {
            // Remove existing alerts
            const existingAlert = document.querySelector('.custom-alert');
            if (existingAlert) {
                existingAlert.remove();
            }
            
            // Create alert element
            const alert = document.createElement('div');
            alert.className = `custom-alert fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            alert.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>
                    <span>${message}</span>
                    <button class="ml-4" onclick="this.parentElement.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            document.body.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (alert.parentElement) {
                    alert.remove();
                }
            }, 5000);
        }
        
        function checkEmptyTable() {
            const tableBody = document.getElementById('product-table-body');
            if (!tableBody) return;
            const visibleRows = tableBody.querySelectorAll('tr[style*="display: table-row"], tr:not([style])');
            
            if (visibleRows.length === 0) {
                const emptyRow = document.createElement('tr');
                emptyRow.innerHTML = `
                    <td colspan="10" class="p-4 text-center text-gray-500">
                        No products found
                    </td>
                `;
                tableBody.appendChild(emptyRow);
            }
        }
    });
</script>