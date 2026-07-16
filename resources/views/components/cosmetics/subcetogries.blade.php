@php
    // Admin-managed categories flagged for the cosmetics page (falls back to the core list).
    $cosmeticCategories = \App\Support\CategoryCatalog::forCosmetics();

    // Keep the original PNG artwork where a category name matches the old tiles;
    // everything else gets a brand-gradient emoji circle.
    $tileImages = [
        'Cosmetics'             => 'img/categories/cosmetics.png',
        'Skincare'              => 'img/categories/skincare.png',
        'Haircare'              => 'img/categories/haircare.png',
        'Fragrances'            => 'img/categories/fragrances.png',
        'Perfumes & Fragrances' => 'img/categories/fragrances.png',
        'Accessories'           => 'img/categories/accessories.png',
    ];

    // Real approved-product counts per category (one grouped query, fail-safe).
    try {
        $productCounts = \Illuminate\Support\Facades\DB::table('vendor_products')
            ->where('position', 'approved')
            ->select('category', \Illuminate\Support\Facades\DB::raw('count(*) c'))
            ->groupBy('category')
            ->pluck('c', 'category');
    } catch (\Throwable $e) {
        $productCounts = collect();
    }
@endphp

    <section class="py-12 px-4 sm:px-6 lg:px-8 mx-auto" style="max-width: 100vw">
        <div class="mx-auto text-center">

            <!-- Heading -->
            <h2 class="font-serif text-3xl md:text-5xl font-extrabold text-gray-900 mb-2">
                Shop by Beauty Category
            </h2>

            <!-- Subtitle -->
            <p class="text-sm sm:text-lg text-gray-600 mb-8 sm:mb-12">
                MJ Whitening Cream — soft, smooth, and naturally bright skin
            </p>

            <style>
                .scrollbar-none { -ms-overflow-style: none; scrollbar-width: none; }
                .scrollbar-none::-webkit-scrollbar { display: none; }
            </style>

            <!-- Category Slider (horizontal left-right scroll) -->
            <div class="flex overflow-x-auto gap-4 pb-3 snap-x snap-mandatory scrollbar-none scroll-smooth">

                @foreach ($cosmeticCategories as $cat)
                    @php
                        $tileImg = $tileImages[$cat->name] ?? null;
                        $count = (int) ($productCounts[$cat->name] ?? 0);
                        $subsPreview = implode(' · ', array_slice($cat->subcategories, 0, 3));
                    @endphp

                    <a href="/products/all-page?category={{ urlencode($cat->name) }}"
                       class="group w-[65vw] sm:w-64 md:w-72 flex-shrink-0 snap-start cursor-pointer block">
                        <div class="relative h-48 sm:h-80 md:h-96 rounded-2xl overflow-hidden shadow-lg bg-gray-900">
                            @if ($tileImg)
                                <img src="{{ asset($tileImg) }}"
                                     alt="{{ $cat->name }}"
                                     class="w-full h-full object-cover absolute inset-0
                                            transition duration-500 ease-in-out
                                            group-hover:scale-110">
                            @else
                                <div class="absolute inset-0 flex items-center justify-center"
                                     style="background: linear-gradient(160deg, #23232b 0%, #15151b 100%);">
                                    <span class="flex items-center justify-center w-20 h-20 sm:w-28 sm:h-28 rounded-full text-4xl sm:text-6xl shadow-lg
                                                 transition duration-500 ease-in-out group-hover:scale-110"
                                          style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">{{ $cat->emoji }}</span>
                                </div>
                            @endif
                        </div>
                        <h3 class="mt-2 sm:mt-3 text-base sm:text-lg md:text-xl font-bold text-gray-900 truncate">{{ $cat->name }}</h3>
                        @if ($subsPreview !== '')
                            <p class="text-[11px] sm:text-xs md:text-sm text-gray-500 line-clamp-2 mt-0.5">{{ $subsPreview }}</p>
                        @endif
                        @if ($count > 0)
                            <span class="block text-[10px] sm:text-xs md:text-sm font-semibold text-gray-400 mt-0.5">{{ $count }} Product{{ $count === 1 ? '' : 's' }}</span>
                        @endif
                    </a>
                @endforeach

            </div>

        </div>
    </section>
