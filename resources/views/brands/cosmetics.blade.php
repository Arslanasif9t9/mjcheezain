{{-- @extends('layouts.structure')
@section('title', 'Cosmetics')

@section('style')

@endsection

@section('body')
    <x-cosmetics.header :user="$user ?? null" :vendor="$vendor ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />
    <main id="main">
        <x-cosmetics.hero-video />
        <x-cosmetics.subcetogries />
        @include('../products.category', ['category' => 'Fitness & Gym Equipment', 'id' => 'gym'])
        @include('../products.category', ['category' => 'Auto Parts & Accessories', 'id' => 'auto'])
        @include('../products.category', ['category' => 'Car Tools & Maintenance', 'id' => 'car'])
    </main>
    <x-footer />

    <script src="{{ asset('js/search.js') }}?v={{ @filemtime(public_path('js/search.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/product-card.js') }}?v={{ @filemtime(public_path('js/product-card.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ @filemtime(public_path('js/category_fetch.js')) ?: 1 }}"></script>
@endsection --}}




@extends('layouts.structure')
@section('title', 'Cosmetics')

@section('style')
@endsection

@section('body')
    <x-cosmetics.transparent-header :user="$user ?? null" :vendor="$vendor ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />
    <main id="main">
        <x-cosmetics.hero-video />
        <x-cosmetics.subcetogries />

        {{-- Category filter chips: jump to the filtered product listing --}}
        @php $chipCategories = \App\Support\CategoryCatalog::forCosmetics(); @endphp
        <section class="px-4 sm:px-6 lg:px-8 pt-1 pb-6 mx-auto" style="max-width: 100vw">
            <div class="flex flex-row overflow-x-auto whitespace-nowrap gap-2 pb-2 scrollbar-none">
                <a href="/products/all-page"
                   class="flex-shrink-0 px-4 py-1.5 text-sm font-medium rounded-full text-white shadow-sm no-underline"
                   style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">All</a>
                @foreach ($chipCategories as $chipCat)
                    <a href="/products/all-page?category={{ urlencode($chipCat->name) }}"
                       class="flex-shrink-0 px-4 py-1.5 text-sm font-medium rounded-full bg-white text-gray-700 hover:text-pink-600 hover:border-pink-200 border border-gray-200 transition duration-200 shadow-sm no-underline">{{ $chipCat->emoji }} {{ $chipCat->name }}</a>
                @endforeach
            </div>
        </section>

        {{-- Driven by the admin Categories page ("COSMETICS" switch), same as the
             chips above — was a hardcoded list that ignored the toggle. --}}
        @foreach (\App\Support\CategoryCatalog::forCosmeticsStocked() as $cosCat)
            @include('../products.category', [
                'category' => $cosCat->name,
                'id'       => \Illuminate\Support\Str::slug($cosCat->name) ?: 'cat-' . $cosCat->id,
            ])
        @endforeach
    </main>
    <x-footer />

    <script src="{{ asset('js/search.js') }}?v={{ @filemtime(public_path('js/search.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/product-card.js') }}?v={{ @filemtime(public_path('js/product-card.js')) ?: 1 }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ @filemtime(public_path('js/category_fetch.js')) ?: 1 }}"></script>
@endsection