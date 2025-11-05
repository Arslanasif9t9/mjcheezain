@extends('layouts.structure')
@section('title', 'product')

@section('body')
    <x-cosmetics.header />

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Product Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            
            <!-- Left Column: Images -->
            <div>
                <!-- Main Image Placeholder -->
                <img src="{{ asset('img/default_img.png') }}" class="border-2 border-blue-900 w-full aspect-square flex items-center justify-center text-white text-2xl font-bold mb-4 rounded-lg overflow-hidden">
                
                <div class="relative w-full">
                <!-- Left Arrow -->
                <button id="scroll-left" 
                    class="absolute left-0 top-1/2 -translate-y-1/2 bg-blue-900 text-white p-2 rounded-full z-10 hover:bg-blue-800">
                    &#10094;
                </button>

                <!-- Image Container -->
                <div id="image-slider" class="grid grid-flow-col auto-cols-[20%] gap-3 overflow-x-auto scroll-smooth no-scrollbar px-8">
                    @for ($i = 0; $i < 10; $i++)
                        <img src="{{ asset('img/default_img.png') }}" 
                            class="aspect-square object-cover border-2 border-blue-900 rounded-md">
                    @endfor
                </div>

                <!-- Right Arrow -->
                <button id="scroll-right" 
                    class="absolute right-0 top-1/2 -translate-y-1/2 bg-blue-900 text-white p-2 rounded-full z-10 hover:bg-blue-800">
                    &#10095;
                </button>
            </div>

            <script>
                const slider = document.getElementById('image-slider');
                const leftBtn = document.getElementById('scroll-left');
                const rightBtn = document.getElementById('scroll-right');

                rightBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: slider.clientWidth * 0.2, behavior: 'smooth' }); // move one image
                });

                leftBtn.addEventListener('click', () => {
                    slider.scrollBy({ left: -slider.clientWidth * 0.2, behavior: 'smooth' }); // move one image back
                });
            </script>

            <style>
                /* Hide scrollbar but keep scroll functionality */
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
                .no-scrollbar {
                    -ms-overflow-style: none;
                    scrollbar-width: none;
                }
            </style>


            </div>

            <!-- Right Column: Product Info & CTA -->
            <div class="mt-32">
                <h1 class="text-3xl font-bold mb-2">MJ Whitening Cream 50ml</h1>
                
                <!-- Ratings & Verification -->
                <div class="flex items-center space-x-4 text-sm mb-4">
                    <div class="flex items-center space-x-1">
                        <span class="font-semibold">4.8</span>
                        <span class="text-gray-500">(125 reviews)</span>
                    </div>
                    <div class="flex items-center text-verified-green">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        Verified Seller
                    </div>
                </div>

                <!-- Price -->
                <p class="text-3xl font-extrabold text-gray-900 mb-4">PKR 1,950</p>
                
                <!-- Vendor Info -->
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Sold by <span class="font-semibold text-primary-blue hover:text-blue-700 cursor-pointer">GlowSkin Official</span></p>
                </div>
                
                <!-- Stock Status -->
                <div class="flex items-center text-verified-green font-semibold mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                    In Stock (ready to ship)
                </div>

                <!-- Quantity Selector -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Quantity</label>
                    <div class="flex w-32 border border-gray-300 rounded-lg overflow-hidden">
                        <button class="w-10 h-10 flex items-center justify-center text-xl text-gray-600 hover:bg-gray-100">-</button>
                        <input type="text" value="1" class="w-12 h-10 text-center border-x border-gray-300 focus:outline-none" readonly>
                        <button class="w-10 h-10 flex items-center justify-center text-xl text-gray-600 hover:bg-gray-100">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 mb-6">
                    <button class="flex-1 py-3 px-6 bg-dark-bg text-white font-semibold rounded-lg hover:bg-gray-700 transition duration-150">
                        Add to Cart
                    </button>
                    <button class="flex-1 py-3 px-6 border border-primary-blue text-primary-blue font-semibold rounded-lg hover:bg-blue-50 transition duration-150">
                        Buy Now
                    </button>
                </div>

                <!-- Delivery & Payment Info -->
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 8V4.835a1 1 0 01.325-.758l2.25-2.25a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-2.25 2.25a1 1 0 01-.758.325H16M3 9h11a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6a1 1 0 011-1zm0 0l-1.5 7" /></svg>
                        Free delivery over PKR 2,000
                    </div>
                    <div class="flex items-center text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        Estimated <span class="font-semibold text-dark-bg ml-1">2-3 days</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-700">
                        <div class="flex items-center">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            7-Day Return Policy
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.172a1 1 0 000-1.414L2.83 2.83a1 1 0 00-1.414 1.414L18.182 20.618a1 1 0 001.414-1.414L9.828 8.414a1 1 0 00-1.414 0L6.414 6.414a1 1 0 00-1.414 0z" /></svg>
                            100% Original Product Guarantee
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Section -->
        <div class="mb-12 grid grid-cols-1 md:grid-cols-2">
            
            <div>
                <!-- Tab Headers/Buttons -->
                <div class="flex border-b border-gray-200 text-gray-500 text-lg space-x-8">
                    <!-- NOTE: The 'tab-button' class and onClick event are essential for JS functionality -->
                    <button id="tab-description-btn" onclick="switchTab('description')" 
                            class="tab-button border-b-2 border-primary-blue text-gray-900 font-semibold px-1 pb-3 transition duration-150">
                        Description
                    </button>
                    <button id="tab-specifications-btn" onclick="switchTab('specifications')" 
                            class="tab-button border-b-2 border-transparent px-1 pb-3 transition duration-150 hover:text-gray-900">
                        Specifications
                    </button>
                    <button id="tab-reviews-btn" onclick="switchTab('reviews')" 
                            class="tab-button border-b-2 border-transparent px-1 pb-3 transition duration-150 hover:text-gray-900">
                        Reviews
                    </button>
                    <button id="tab-vendorinfo-btn" onclick="switchTab('vendorinfo')" 
                            class="tab-button border-b-2 border-transparent px-1 pb-3 transition duration-150 hover:text-gray-900">
                        Vendor Info
                    </button>
                </div>

                <!-- Tab Content Panels -->
                <div class="pt-8">
                    
                    <!-- Tab 1: Description Content (Default Active) -->
                    <div id="content-description" class="tab-content">
                        <h3 class="text-xl font-semibold mb-4">Product Details</h3>
                        <ul class="list-disc list-inside space-y-2 ml-4 text-gray-700">
                            <li>Brightens and evens skin tone.</li>
                            <li>Reduces the appearance of dark spots and hyperpigmentation.</li>
                            <li>Provides deep hydration for a supple feel.</li>
                            <li>Formulated with natural extracts and advanced brightening agents.</li>
                            <li>Suitable for all skin types.</li>
                        </ul>
                    </div>

                    <!-- Tab 2: Specifications Content -->
                    <div id="content-specifications" class="tab-content hidden">
                        <h3 class="text-xl font-semibold mb-4">Technical Specifications</h3>
                        <table class="w-full text-left border-collapse">
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Brand</th>
                                <td class="py-2">MJ Cosmetics</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Volume</th>
                                <td class="py-2">50ml</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Skin Type</th>
                                <td class="py-2">All</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Key Ingredients</th>
                                <td class="py-2">Niacinamide, Vitamin C, Licorice Extract</td>
                            </tr>
                            <tr>
                                <th class="py-2 text-gray-500 font-normal w-1/4">Made In</th>
                                <td class="py-2">Pakistan</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 3: Reviews Content -->
                    <div id="content-reviews" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            
                            {{-- <!-- Review Summary -->
                            <div class="md:col-span-1">
                                <div class="text-center">
                                    <div class="text-star-yellow text-4xl mb-2">
                                        <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9734;</span>
                                    </div>
                                    <p class="text-gray-500">Based on 125 reviews</p>
                                </div>
                            </div> --}}

                            <!-- Rating Breakdown -->
                            <div class="md:col-span-2 space-y-2">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">5 Star</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-star-yellow h-2 rounded-full" style="width: 80%;"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">80%</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">4 Star</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-star-yellow h-2 rounded-full" style="width: 15%;"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">15%</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">3 Star</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-star-yellow h-2 rounded-full" style="width: 3%;"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">3%</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">2 Star</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-star-yellow h-2 rounded-full" style="width: 2%;"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">2%</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm">1 Star</span>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-star-yellow h-2 rounded-full" style="width: 0%;"></div>
                                    </div>
                                    <span class="text-sm text-gray-500">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Vendor Info Content -->
                    <div id="content-vendorinfo" class="tab-content hidden">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                                
                            </div>
                            <div>
                                <p class="font-semibold text-lg flex items-center">
                                    GlowSkin Official 
                                    <span class="ml-2 h-2 w-2 bg-primary-blue rounded-full"></span>
                                    <span class="ml-1 text-sm text-primary-blue">Verified</span>
                                </p>
                                <a href="#" class="text-sm text-primary-blue hover:text-blue-700 transition duration-150">
                                    View all products from this vendor &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Element - Autoplay, loop, and muted are mandatory for background videos -->
            <div class="w-full flex justify-center items-center py-10 bg-gradient-to-b from-pink-50 to-white">
                <div class="relative w-full max-w-3xl rounded-2xl overflow-hidden shadow-2xl border-4 border-pink-200 hover:border-pink-400 transition-all duration-300">
                    <video
                        controls
                        poster="{{ asset('img/video-poster.jpg') }}"
                        class="w-full rounded-2xl object-cover">
                        <source src="{{ asset('video/cosmetics.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Optional overlay label -->
                    <div class="absolute top-3 left-3 bg-pink-600 text-white text-sm font-semibold px-3 py-1 rounded-full shadow-md">
                        Watch Now
                    </div>
                </div>
            </div>

        </div>

        <div class="mb-4">
            <div class="flex justify-between align-center">
                <h3 class="text-xl font-semibold mt-8 mb-0">Customer Reviews</h3>
                <div class="flex justify-end">
                    <button class="text-primary-blue border border-primary-blue py-2 px-4 rounded-lg hover:bg-blue-50 transition duration-150">
                        Write a Review
                    </button>
                </div>
            </div>

            <!-- Example Review 1 -->
            <div class="border-t pt-4 mt-4 space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gray-200 overflow-hidden">
                        
                    </div>
                    <div>
                        <div class="text-star-yellow text-lg">
                            <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9733;</span>
                        </div>
                        <span class="text-xs text-verified-green font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            Verified Purchase
                        </span>
                    </div>
                </div>
                <p class="text-gray-700">Amazing product! Saw results in just a week.</p>
            </div>

            <!-- Example Review 2 -->
            <div class="border-t pt-4 mt-4 space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-blue-100 overflow-hidden">
                        
                    </div>
                    <div>
                        <div class="text-star-yellow text-lg">
                            <span>&#9733;</span><span>&#9733;</span><span>&#9733;</span><span>&#9734;</span><span>&#9734;</span>
                        </div>
                        <span class="text-xs text-verified-green font-medium flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            Verified Purchase
                        </span>
                    </div>
                </div>
                <p class="text-gray-700">Good hydration, but took a little longer for brightening effects.</p>
            </div>
        </div>

        <!-- You Might Also Like Section -->
        <h2 class="text-2xl font-bold text-center mb-6">Related Products</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 w-full">
            <img src="{{ asset('img/default_img.png') }}" class="w-full aspect-square object-cover border-2 border-blue-900 rounded-lg">
            <img src="{{ asset('img/default_img.png') }}" class="w-full aspect-square object-cover border-2 border-blue-900 rounded-lg">
            <img src="{{ asset('img/default_img.png') }}" class="w-full aspect-square object-cover border-2 border-blue-900 rounded-lg">
            <img src="{{ asset('img/default_img.png') }}" class="w-full aspect-square object-cover border-2 border-blue-900 rounded-lg">
            <img src="{{ asset('img/default_img.png') }}" class="w-full aspect-square object-cover border-2 border-blue-900 rounded-lg">
        </div>


    </div>

    <x-footer />



    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#3b82f6',
                        'dark-bg': '#1f2937',
                        'star-yellow': '#FFC700',
                        'verified-green': '#10b981',
                    }
                }
            }
        }

        /**
         * JavaScript for Tab Switching
         * Toggles the visibility of content panels and updates the active tab style.
         */
        function switchTab(tabId) {
            // 1. Get all tabs and content
            const tabs = document.querySelectorAll('.tab-button');
            const contents = document.querySelectorAll('.tab-content');

            // 2. Deactivate all tabs and hide all content
            tabs.forEach(tab => {
                tab.classList.remove('border-primary-blue', 'text-gray-900', 'font-semibold');
                tab.classList.add('border-transparent', 'text-gray-500');
            });

            contents.forEach(content => {
                content.classList.add('hidden');
            });

            // 3. Activate the clicked tab and show the corresponding content
            const activeTab = document.getElementById(`tab-${tabId}-btn`);
            const activeContent = document.getElementById(`content-${tabId}`);
            
            if (activeTab && activeContent) {
                activeTab.classList.add('border-primary-blue', 'text-gray-900', 'font-semibold');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
                activeContent.classList.remove('hidden');
            }
        }

        // Set the default tab to active on page load
        window.onload = function() {
            switchTab('description');
        };
    </script>
@endsection