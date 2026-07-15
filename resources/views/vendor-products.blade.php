@extends('layouts.structure')
@section('title', 'Vendor Products')

@section('body')
    <x-cosmetics.header :user="$user ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <main class="container mx-auto px-3 sm:px-4 py-4 sm:py-8">
        <!-- Vendor Store Hero (brand gradient banner) -->
        <section class="rounded-2xl sm:rounded-3xl overflow-hidden mb-6 sm:mb-8 relative"
                 style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 10px 30px rgba(255, 125, 160, 0.25);">
            <!-- Decorative circles -->
            <div class="absolute -top-12 -right-12 w-44 h-44 bg-white/10 rounded-full pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>

            <div class="relative z-10 px-4 py-6 sm:px-8 sm:py-10 flex flex-col md:flex-row items-center gap-4 sm:gap-6">
                <div class="w-20 h-20 sm:w-28 sm:h-28 rounded-full overflow-hidden ring-4 ring-white/70 shadow-xl flex-shrink-0">
                    <img src="{{ asset($imgPath) }}" alt="Vendor Profile" class="w-full h-full object-cover">
                </div>
                <div class="text-center md:text-left min-w-0">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-white/25 backdrop-blur-sm text-white text-[10px] sm:text-xs font-bold rounded-full mb-1.5">
                        <i class="fa-solid fa-store text-[9px]"></i> OFFICIAL STORE
                    </span>
                    <h1 class="text-xl sm:text-3xl font-extrabold text-white truncate">
                        {{ $vendor->business_name ?? 'Vendor Business' }}
                    </h1>
                    <p class="text-white/85 text-xs sm:text-base mt-1 line-clamp-2">
                        {{ $vendor->business_description ?? 'Premium products for all your needs' }}
                    </p>
                    <div class="flex items-center justify-center md:justify-start gap-2 mt-3 flex-wrap">
                        <span class="inline-flex items-center gap-1 bg-white text-gray-800 text-[11px] sm:text-sm font-bold px-3 py-1 rounded-full shadow">
                            <span class="text-yellow-400">★</span> 4.5
                        </span>
                        <span class="inline-flex items-center gap-1 bg-white/25 backdrop-blur-sm text-white text-[11px] sm:text-sm font-semibold px-3 py-1 rounded-full">
                            <i class="fa-solid fa-box-open text-[10px]"></i> {{ $products->count() }} products
                        </span>
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
                <div class="text-center py-12 bg-white rounded-2xl" style="box-shadow: 0 6px 20px rgba(17, 24, 39, 0.06);">
                    <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, rgba(255,125,160,.12), rgba(255,194,117,.12));">
                        <i class="fas fa-box-open text-3xl" style="color: #E85D85;"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">No Products Available</h3>
                    <p class="text-gray-500 text-sm">This vendor hasn't added any products yet.</p>
                    <a href="/" class="inline-block mt-4 px-6 py-2.5 text-white text-sm font-semibold rounded-full no-underline" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 4px 14px rgba(255, 125, 160, 0.35);">Continue Shopping</a>
                </div>
            @endif
        </section>
    </main>

    <x-footer />
@endsection