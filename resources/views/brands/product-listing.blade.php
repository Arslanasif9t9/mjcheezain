@extends('layouts.structure')
@section('title', 'Product Listing | MJ Cheezain')

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