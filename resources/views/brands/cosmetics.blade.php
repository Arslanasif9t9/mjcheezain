@extends('layouts.structure')
@section('title', 'Cosmetics')

@section('style')

@endsection

@section('body')
    <x-cosmetics.header />
    <x-cosmetics.hero-video />
    <x-cosmetics.subcetogries />
    @include('../products.category', ['category' => 'Fitness & Gym Equipment', 'id' => 'gym'])
    @include('../products.category', ['category' => 'Auto Parts & Accessories', 'id' => 'auto'])
    @include('../products.category', ['category' => 'Car Tools & Maintenance', 'id' => 'car'])
    <x-footer />

    <script src="{{ asset('js/category_fetch.js') }}"></script>
@endsection