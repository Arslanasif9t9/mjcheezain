@extends('layouts.structure')
@section('title', 'Product')
@section('style')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'umart-blue': '#0F4C81',
                        'light-bg': '#F4F7FF',
                        'star-yellow': '#FFC700',
                    }
                }
            }
        }
    </script>
@endsection

@section('body')
    <x-cosmetics.header />

    <div class="mx-auto py-10 px-4 sm:px-6 lg:px-8" style="max-width: 100rem;">


        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800">Cart</h1>
                <a href="#" class="text-sm text-red-500 hover:text-red-700 font-medium">Clear Cart List</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">

                    <div class="flex items-start border-b pb-4">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-20 h-20 bg-pink-100 border border-gray-200 rounded-lg flex items-center justify-center">
                                                            </div>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-lg font-semibold text-gray-800">Macbook pro 16"</h2>
                            <p class="text-sm text-gray-500">Color: Pink</p>
                            <div class="flex items-center mt-1">
                                <div class="flex text-star-yellow text-sm">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span class="text-gray-300">&#9733;</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-2">(12 Reviews)</span>
                            </div>
                            <div class="mt-2 text-xl font-bold text-gray-900">
                                $1099.00
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <button class="text-gray-400 hover:text-red-500 mb-4" title="Remove Item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button class="p-2 text-gray-600 hover:bg-gray-100">-</button>
                                <span class="px-3 border-x border-gray-300">3</span>
                                <button class="p-2 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start border-b pb-4">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-20 h-20 bg-blue-100 border border-gray-200 rounded-lg flex items-center justify-center">
                                                            </div>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-lg font-semibold text-gray-800">Macbook pro 16"</h2>
                            <p class="text-sm text-gray-500">Color: Blue</p>
                            <div class="flex items-center mt-1">
                                <div class="flex text-star-yellow text-sm">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-2">(12 Reviews)</span>
                            </div>
                            <div class="mt-2 text-xl font-bold text-gray-900">
                                $1099.00
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <button class="text-gray-400 hover:text-red-500 mb-4" title="Remove Item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button class="p-2 text-gray-600 hover:bg-gray-100">-</button>
                                <span class="px-3 border-x border-gray-300">1</span>
                                <button class="p-2 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-20 h-20 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center">
                                                            </div>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-lg font-semibold text-gray-800">Macbook pro 16"</h2>
                            <p class="text-sm text-gray-500">Color: Silver</p>
                            <div class="flex items-center mt-1">
                                <div class="flex text-star-yellow text-sm">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span class="text-gray-300">&#9733;</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-2">(12 Reviews)</span>
                            </div>
                            <div class="mt-2 text-xl font-bold text-gray-900">
                                $1099.00
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <button class="text-gray-400 hover:text-red-500 mb-4" title="Remove Item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button class="p-2 text-gray-600 hover:bg-gray-100">-</button>
                                <span class="px-3 border-x border-gray-300">1</span>
                                <button class="p-2 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-4">
                            <div class="w-20 h-20 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center">
                                                            </div>
                        </div>
                        <div class="flex-grow">
                            <h2 class="text-lg font-semibold text-gray-800">Macbook pro 16"</h2>
                            <p class="text-sm text-gray-500">Color: Silver</p>
                            <div class="flex items-center mt-1">
                                <div class="flex text-star-yellow text-sm">
                                    <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span class="text-gray-300">&#9733;</span>
                                </div>
                                <span class="text-xs text-gray-500 ml-2">(12 Reviews)</span>
                            </div>
                            <div class="mt-2 text-xl font-bold text-gray-900">
                                $1099.00
                            </div>
                        </div>
                        <div class="flex flex-col items-end">
                            <button class="text-gray-400 hover:text-red-500 mb-4" title="Remove Item">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>
                            <div class="flex items-center border border-gray-300 rounded-md">
                                <button class="p-2 text-gray-600 hover:bg-gray-100">-</button>
                                <span class="px-3 border-x border-gray-300">1</span>
                                <button class="p-2 text-gray-600 hover:bg-gray-100">+</button>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-1">
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                        <h2 class="text-xl font-bold mb-4 border-b pb-3 text-gray-800">Pricing & Shipping Fee</h2>

                        <div class="space-y-3 text-gray-600">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>$4400</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping Fee</span>
                                <span>$300</span>
                            </div>
                            {{-- <div class="flex justify-between">
                                <span>Shipping Fee (Door to Door)</span>
                                <span>$300</span>
                            </div>
                            <div class="flex justify-between text-green-600">
                                <span>Coupon (Extra Credit)</span>
                                <span>-$600</span>
                            </div> --}}
                        </div>

                        {{-- <div class="mt-6 border-t pt-4">
                            <div class="flex">
                                <input type="text" placeholder="Enter Coupon Code" class="flex-grow p-2 border border-gray-300 rounded-l-md focus:ring-umart-blue focus:border-umart-blue">
                                <button class="bg-umart-blue text-white p-2 rounded-r-md hover:bg-umart-blue/90 font-medium">Apply</button>
                            </div>
                        </div> --}}

                        <div class="mt-6 border-t pt-4">
                            <div class="flex justify-between text-2xl font-bold text-gray-900">
                                <span>Total</span>
                                <span>$4400</span>
                            </div>
                        </div>

                        <button class="w-full mt-6 bg-umart-blue text-white py-3 rounded-lg font-semibold text-lg hover:bg-umart-blue/90 transition duration-300">
                            PROCEED TO CHECKOUT
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <x-footer />

    </div>
@endsection