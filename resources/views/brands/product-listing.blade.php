@extends('layouts.structure')
@section('title', 'Cosmetics')

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
    <x-cosmetics.header />
    @include('../products.product-list', ['category' => 'Fitness & Gym Equipment', 'id' => 'gym'])
    {{-- @include('../products.product-list', ['category' => 'Auto Parts & Accessories', 'id' => 'auto']) --}}
    {{-- @include('../products.product-list', ['category' => 'Car Tools & Maintenance', 'id' => 'car']) --}}
    <x-footer />

    {{-- <script src="{{ asset('js/category_fetch.js') }}"></script> --}}
@endsection