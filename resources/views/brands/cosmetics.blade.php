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

    <script src="{{ asset('js/search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/product-card.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ time() }}"></script>
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

        @include('../products.category', ['category' => 'Perfumes & Fragrances', 'id' => 'perfumes'])
        @include('../products.category', ['category' => 'Gifts & General Items', 'id' => 'gifts'])
        @include('../products.category', ['category' => 'Home & Living', 'id' => 'home'])
    </main>
    <x-footer />

    <script src="{{ asset('js/search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/product-card.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ time() }}"></script>
@endsection