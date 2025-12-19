@extends('layouts.structure')
@section('title', 'Vendor Products')

@section('style')
    <style>
        .product-card-list {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e5e7eb;
        }
        .product-card-list:hover {
            background-color: #f9fafb;
            transform: translateX(5px);
        }
        .aspect-w-4 {
            position: relative;
        }
        .aspect-w-4::before {
            content: '';
            display: block;
            padding-top: 100%;
        }
        .aspect-h-3 {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }
        .product-image-container {
            min-width: 200px;
            max-width: 200px;
        }
        @media (max-width: 768px) {
            .product-image-container {
                min-width: 120px;
                max-width: 120px;
            }
        }
    </style>
@endsection

@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />
    
    <main class="container mx-auto px-4 py-8">
        <!-- Vendor Info Section -->
        <section class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row items-center">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-orange-100 mb-4 md:mb-0 md:mr-6">
                    <img src="{{ asset($imgPath) }}" alt="Vendor Profile" class="w-full h-full object-cover">
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ $vendor->business_name ?? 'Vendor Business' }}
                    </h1>
                    <p class="text-gray-600 mt-2">
                        {{ $vendor->business_description ?? 'Premium products for all your needs' }}
                    </p>
                    <div class="flex items-center justify-center md:justify-start mt-3">
                        <div class="flex text-yellow-400 mr-2">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="text-gray-600">4.5 ({{ $products->count() }} products)</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section - List View -->
        <section id="vendor-products-section" class="py-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900">All Products ({{ $products->count() }})</h2>
                <div class="flex space-x-2">
                    {{-- <button id="grid-view-btn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300">
                        <i class="fas fa-th-large mr-2"></i> Grid
                    </button> --}}
                    <button id="list-view-btn" class="px-4 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-700 transition duration-300">
                        <i class="fas fa-list mr-2"></i> List
                    </button>
                </div>
            </div>
            
            @if($products->count() > 0)
                <!-- Products List -->
                <div id="vendor-product-list" class="bg-white rounded-xl shadow-sm overflow-hidden">
                    @foreach($products as $product)
                        <div class="product-card-list p-6 hover:bg-gray-50 transition duration-300">
                            <div class="flex flex-col md:flex-row md:items-center">
                                <!-- Product Image -->
                                <div class="product-image-container mb-4 md:mb-0 md:mr-6">
                                    <div class="aspect-w-4">
                                        <div class="aspect-h-3">
                                            @if($product->primary_image)
                                                <img src="{{ asset('storage/vendor/products/images/' . $product->primary_image) }}" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <img src="https://via.placeholder.com/300x300/F3F4F6/6B7280?text=No+Image" 
                                                     alt="{{ $product->name }}" 
                                                     class="w-full h-full object-cover rounded-lg">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Product Details -->
                                <div class="flex-1">
                                    <div class="flex flex-col md:flex-row md:items-start justify-between mb-4">
                                        <div class="md:mr-4">
                                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                                            <p class="text-gray-600 mb-3">{{ Str::limit($product->description, 150) }}</p>
                                            
                                            <!-- Product Tags/Categories -->
                                            @if($product->categories && $product->categories->count() > 0)
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    @foreach($product->categories->take(3) as $category)
                                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                                            {{ $category->name }}
                                                        </span>
                                                    @endforeach
                                                    @if($product->categories->count() > 3)
                                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                                            +{{ $product->categories->count() - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Price and Rating -->
                                        <div class="flex flex-col items-start md:items-end">
                                            <div class="mb-2">
                                                <span class="text-2xl font-extrabold text-gray-900">${{ number_format($product->selling_price, 2) }}</span>
                                                @if($product->original_price && $product->original_price > $product->selling_price)
                                                    <span class="text-sm text-gray-500 line-through ml-2">${{ number_format($product->original_price, 2) }}</span>
                                                    <span class="text-sm font-medium text-green-600 ml-2">
                                                        {{ round((($product->original_price - $product->selling_price) / $product->original_price) * 100) }}% OFF
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center mb-4 md:mb-0">
                                                <div class="flex text-yellow-400 mr-2">
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                </div>
                                                <span class="text-gray-600">4.5 (128 reviews)</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Actions -->
                                    <div class="flex flex-col md:flex-row md:items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center mb-4 md:mb-0">
                                            <div class="flex items-center mr-6">
                                                <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                                <span class="text-sm text-gray-600">In Stock</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-shipping-fast text-blue-500 mr-2"></i>
                                                <span class="text-sm text-gray-600">Free Shipping</span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-3">
                                            {{-- <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition duration-300 flex items-center">
                                                <i class="far fa-heart mr-2"></i> Wishlist
                                            </button> --}}
                                            <a href="/product/{{ $product->id }}">
                                                <button class="px-6 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg hover:bg-gray-700 transition duration-300 shadow-md flex items-center">
                                                    <i class="fas fa-eye mr-2"></i> View Details
                                                </button>
                                            </a>
                                            {{-- <button class="px-6 py-2 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition duration-300 shadow-md flex items-center">
                                                <i class="fas fa-shopping-cart mr-2"></i> Add to Cart
                                            </button> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination (if applicable) -->
                
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 text-gray-400">
                        <i class="fas fa-box-open text-6xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No Products Available</h3>
                    <p class="text-gray-500">This vendor hasn't added any products yet.</p>
                </div>
            @endif
        </section>
    </main>

    <x-footer />

    <script>
        // Toggle between grid and list view
        document.getElementById('grid-view-btn').addEventListener('click', function() {
            window.location.href = '';
        });

        document.getElementById('list-view-btn').addEventListener('click', function() {
            window.location.href = '';
        });

        // Add to cart functionality
        document.querySelectorAll('[class*="Add to Cart"]').forEach(button => {
            button.addEventListener('click', function() {
                const productId = this.dataset.productId;
                // Add your add to cart logic here
                console.log('Add product to cart:', productId);
                
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg';
                toast.textContent = 'Product added to cart!';
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.remove();
                }, 3000);
            });
        });
    </script>
@endsection