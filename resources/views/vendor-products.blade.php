@extends('layouts.structure')
@section('title', 'Vendor Products')

@section('style')
    <style>
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .aspect-w-4 {
            position: relative;
        }
        .aspect-w-4::before {
            content: '';
            display: block;
            padding-top: 75%; /* 4:3 Aspect Ratio */
        }
        .aspect-h-3 {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
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

        <!-- Products Section -->
        <section id="vendor-products-section" class="py-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">All Products ({{ $products->count() }})</h2>
            
            @if($products->count() > 0)
                <!-- Products Grid -->
                <div id="vendor-product-grid" 
                     class="grid gap-6 
                            grid-cols-2
                            sm:grid-cols-3
                            lg:grid-cols-4
                            auto-rows-fr">
                    @foreach($products as $product)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden group hover:shadow-xl transition duration-300 product-card">
                            <div class="relative overflow-hidden aspect-w-4">
                                <div class="aspect-h-3">
                                    @if($product->primary_image)
                                        <img src="{{ asset('storage/vendor/products/images/' . $product->primary_image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-[210px] object-cover transition duration-300 ease-in-out group-hover:scale-110">
                                    @else
                                        <img src="https://via.placeholder.com/300x225/F3F4F6/6B7280?text=No+Image" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-[210px] object-cover transition duration-300 ease-in-out group-hover:scale-110">
                                    @endif
                                </div>
                            </div>

                            <div class="p-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-1 truncate">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 h-10 overflow-hidden">{{ Str::limit($product->description, 80) }}</p>
                                
                                <div class="flex justify-between items-baseline my-3">
                                    <span class="text-xl font-extrabold text-gray-900">${{ number_format($product->selling_price, 2) }}</span>
                                    <div class="flex items-center">
                                        <span class="font-semibold">4.5</span>
                                        <span class="text-yellow-500 text-lg mr-1"> ★</span>
                                    </div>                          
                                </div>
                                <!-- Quick View Button -->
                                <a href="/product/{{ $product->id }}">
                                    <button class="px-4 py-2 text-sm font-semibold text-white bg-gray-900 rounded-lg w-full 
                                                    hover:bg-gray-700 transition duration-300 shadow-md">
                                        Quick View
                                    </button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
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
        // No JavaScript needed for product rendering since we're using Blade
        // You can add any interactive functionality here if needed
    </script>
@endsection