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
            width: 100%;
        }
        @media (min-width: 768px) {
            .product-image-container {
                min-width: 200px;
                max-width: 200px;
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
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">All Products ({{ $products->count() }})</h2>
            </div>

            @if($products->count() > 0)
                <!-- Products Grid (ss10-style cards, same size as every other section) -->
                <div id="vendor-product-list" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-5">
                    @foreach($products as $product)
                        @php
                            $cardPrice = $product->selling_price * 1.17;
                            $cardMrp = (float) ($product->mrp ?? $product->original_price ?? 0);
                            $cardHasDiscount = $cardMrp && $cardMrp > $cardPrice;
                            $cardDiscountPct = $cardHasDiscount ? round((($cardMrp - $cardPrice) / $cardMrp) * 100) : 0;
                        @endphp
                        <div class="product-card-ss10 bg-white rounded-2xl overflow-hidden group flex flex-col relative w-full" style="box-shadow: 0 6px 20px rgba(17, 24, 39, 0.08);">
                            @if($cardHasDiscount)
                                <div class="absolute top-2.5 left-2.5 z-10 px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-bold text-white" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 3px 10px rgba(255,125,160,.4);">-{{ $cardDiscountPct }}%</div>
                            @endif
                            <a href="/product/{{ $product->id }}" class="no-underline flex flex-col flex-grow">
                                <div class="aspect-[16/10] bg-gray-50 overflow-hidden">
                                    <img src="{{ $product->primary_image ? asset('storage/vendor/products/images/' . $product->primary_image) : asset('img/default_img.png') }}"
                                         alt="{{ $product->name }}" loading="lazy"
                                         class="w-full h-full object-cover transition duration-500 ease-in-out group-hover:scale-105">
                                </div>
                                <div class="px-3.5 pt-3 sm:px-4 sm:pt-3.5">
                                    <h3 class="text-[15px] sm:text-lg font-bold text-gray-900 truncate leading-snug m-0 group-hover:text-[#E85D85] transition-colors">{{ $product->name }}</h3>
                                    <p class="flex items-center gap-1 text-xs sm:text-sm text-gray-400 mt-1 mb-0 truncate">
                                        <i class="fas fa-map-marker-alt text-[#FF7DA0] text-[11px]"></i>
                                        <span class="truncate">{{ $product->category ?? 'MJ Cheezain' }}</span>
                                        <span class="flex-shrink-0">&nbsp;•&nbsp;<span class="text-yellow-400">★</span> 4.9</span>
                                    </p>
                                    <div class="flex items-baseline gap-1.5 mt-1.5 flex-wrap">
                                        <span class="text-base sm:text-lg font-extrabold text-gray-900 whitespace-nowrap">Rs. {{ number_format($cardPrice, 0) }}</span>
                                        @if($cardHasDiscount)
                                            <span class="text-[11px] sm:text-xs text-gray-400 line-through whitespace-nowrap">Rs. {{ number_format($cardMrp, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <div class="px-3.5 pb-3.5 pt-2.5 sm:px-4 sm:pb-4 mt-auto">
                                <button onclick="window.location.href='/product/{{ $product->id }}'"
                                        class="w-full py-2 sm:py-2.5 text-[11px] sm:text-sm font-semibold text-white rounded-full transition duration-200 hover:opacity-90 active:scale-[0.98]"
                                        style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 12px rgba(255, 125, 160, 0.35);">
                                    Quick View
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
                <style>
                    .product-card-ss10{transition:transform .25s ease, box-shadow .25s ease;}
                    .product-card-ss10:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(232,93,133,.16)!important;}
                </style>

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
        const gridBtn = document.getElementById('grid-view-btn');
        if (gridBtn) {
            gridBtn.addEventListener('click', function() {
                window.location.href = '';
            });
        }

        const listBtn = document.getElementById('list-view-btn');
        if (listBtn) {
            listBtn.addEventListener('click', function() {
                window.location.href = '';
            });
        }

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