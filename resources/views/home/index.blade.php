@extends('layouts.app')

@section('title', 'MJCheezain — Shop Fashion, Beauty, Auto Parts & More Online in Pakistan')
@section('meta_description', 'Shop quality fashion, cosmetics, auto parts, accessories and more on MJCheezain — Pakistan\'s online marketplace with trusted vendors and fast delivery.')
@section('canonical', url('/a456'))

@section('content')
    <!-- Search Results Section -->
    <section id="searchResults" class="bg-white p-4 m-auto mt-4 hidden">
        <h2 class="font-bold mb-4">Search Results</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4" id="productsGrid"></div>
        <div class="flex justify-center mt-4">
            <button id="loadMore" class="bg-gray-700 text-white px-4 py-2 rounded hidden">Load More</button>
        </div>
    </section>

    <!-- Your additional content here -->
@endsection