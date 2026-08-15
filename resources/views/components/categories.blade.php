<!-- resources/views/components/categories.blade.php -->

@php
    // Admin-managed categories flagged for the home page (falls back to the core list).
    $homeCategories = \App\Support\CategoryCatalog::forHome();

    // Tile picture priority:
    //   1. whatever the admin uploaded on the Categories page ($cat->image)
    //   2. the bundled artwork for the original five categories (below)
    //   3. a brand-gradient emoji circle
    $tileImages = [
        'Cosmetics'             => 'img/categories/cosmetics.webp',
        'Skincare'              => 'img/categories/skincare.webp',
        'Haircare'              => 'img/categories/haircare.webp',
        'Fragrances'            => 'img/categories/fragrances.webp',
        'Perfumes & Fragrances' => 'img/categories/fragrances.webp',
        'Accessories'           => 'img/categories/accessories.webp',
    ];
@endphp

<!-- Category Section -->
    <section class="py-12 px-2 md:px-6 lg:px-8 mx-auto" style="max-width: 100vw">
        <div class="mx-auto text-center">

            <!-- Heading -->
            <span class="section-kicker justify-center">Curated For You</span>
            <h2 class="font-serif text-3xl md:text-5xl font-extrabold text-gray-900 mb-2">
                Shop by Category
            </h2>
            <div class="brand-divider"></div>

            <!-- Subtitle -->
            <p class="text-sm sm:text-lg text-gray-600 mt-4 mb-8 sm:mb-12">
                Explore our selected collections from premium local and global brands.
            </p>

            <!-- Category Grid (LOCKED: stays a grid, never a slider) -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 px-1 md:px-0">

                @foreach ($homeCategories as $cat)
                    @php $tileImg = !empty($cat->image) ? $cat->image : ($tileImages[$cat->name] ?? null); @endphp

                    <a href="/products/all-page?category={{ urlencode($cat->name) }}"
                       class="group card-hover-glow relative h-40 sm:h-64 md:h-80 {{ $loop->first ? 'col-span-2 md:col-span-1' : '' }} rounded-2xl overflow-hidden shadow-sm cursor-pointer block bg-gray-900">

                        @if ($tileImg)
                            <img src="{{ asset($tileImg) }}"
                                 alt="{{ $cat->name }}"
                                 width="600" height="600" loading="lazy" decoding="async"
                                 class="w-full h-full object-cover absolute inset-0
                                        opacity-60
                                        transition duration-500 ease-in-out
                                        group-hover:scale-110 group-hover:opacity-75">
                        @else
                            <div class="absolute inset-0 flex items-center justify-center"
                                 style="background: linear-gradient(160deg, #23232b 0%, #15151b 100%);">
                                <span class="flex items-center justify-center w-16 h-16 sm:w-24 sm:h-24 md:w-28 md:h-28 rounded-full text-3xl sm:text-5xl md:text-6xl shadow-lg
                                             transition duration-500 ease-in-out group-hover:scale-110"
                                      style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">{{ $cat->emoji }}</span>
                            </div>
                        @endif

                        <div class="absolute inset-0 flex flex-col justify-end p-3 sm:p-4 text-left bg-gradient-to-t from-black/85 via-black/20 to-transparent">
                            <h3 class="text-xs sm:text-base font-bold text-white tracking-wide m-0 drop-shadow-md truncate">{{ $cat->name }}</h3>
                            <span class="hidden sm:flex items-center gap-1 text-[11px] font-semibold text-white/0 group-hover:text-white/90 mt-1 transition-all duration-300 -translate-x-1 group-hover:translate-x-0">
                                Shop now <i class="fa-solid fa-arrow-right text-[10px]"></i>
                            </span>
                        </div>
                    </a>
                @endforeach

            </div>

        </div>
    </section>
