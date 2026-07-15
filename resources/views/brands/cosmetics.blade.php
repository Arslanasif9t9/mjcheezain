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
        @include('../products.category', ['category' => 'Perfumes & Fragrances', 'id' => 'perfumes'])
        @include('../products.category', ['category' => 'Gifts & General Items', 'id' => 'gifts'])
        @include('../products.category', ['category' => 'Home & Living', 'id' => 'home'])
    </main>
    <x-footer />

    <script src="{{ asset('js/search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ time() }}"></script>
@endsection