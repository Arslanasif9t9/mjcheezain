@extends('layouts.structure')

{{--
    SEO: this template serves both /product-listing (no filter) and
    /products/all-page?category=... (the JS-driven filtered listing —
    HomeController::productList doesn't receive the category itself, the
    front-end JS reads it and calls the /products/all-page /products/category
    JSON APIs), so the title/description are built here straight from the
    query string via request(), which is always available regardless of
    what the controller passed in.
--}}
@php
    $__seoCategory = trim((string) request()->query('category', ''));
    $__seoTitle = $__seoCategory !== ''
        ? "{$__seoCategory} Products | MJCheezain"
        : 'All Products | MJCheezain';
    $__seoDescription = $__seoCategory !== ''
        ? "Browse {$__seoCategory} products from trusted vendors on MJCheezain — quality items, competitive prices, fast delivery across Pakistan."
        : 'Browse all products from trusted vendors on MJCheezain — fashion, cosmetics, accessories, and more, with quality items and fast delivery across Pakistan.';
@endphp

@section('title', $__seoTitle)
@section('meta_description', $__seoDescription)
{{-- Strip any extra query-string params (referrer tags, etc.) down to just
     the ?category= filter that actually changes the page's content. --}}
@section('canonical', $__seoCategory !== ''
    ? url('/products/all-page') . '?category=' . urlencode($__seoCategory)
    : url('/product-listing'))

@section('style')
    <style>
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('body')
    <x-cosmetics.header :user="$user ?? null" :vendor="$vendor ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />
    <main id="main">
        @include('../products.product-list', ['category' => 'Fitness & Gym Equipment', 'id' => 'gym'])
    </main>
    {{-- @include('../products.product-list', ['category' => 'Auto Parts & Accessories', 'id' => 'auto']) --}}
    {{-- @include('../products.product-list', ['category' => 'Car Tools & Maintenance', 'id' => 'car']) --}}
    <x-footer />

    <script src="{{ asset('js/search.js') }}?v={{ @filemtime(public_path('js/search.js')) ?: 1 }}"></script>
    {{-- <script src="{{ asset('js/category_fetch.js') }}"></script> --}}
@endsection