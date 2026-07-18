@extends('layouts.structure')
@section('title', 'product')
@section('style')
    <style>
        /* Custom scrollbar for webkit browsers */
        .thumbnails-container::-webkit-scrollbar {
            width: 6px;
        }
        .thumbnails-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        .thumbnails-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        .thumbnails-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Smooth transitions */
        .thumb-full, .thumb {
            transition: all 0.2s ease;
        }
        
        /* Fade in animation for modal */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .modal-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Hide scrollbar for thumbnail slider */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Add to Cart Animations */
        @keyframes slideUp {
            from { transform: translateY(100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        @keyframes bounce {
            0%, 20%, 53%, 80%, 100% {
                transform: translate3d(0,0,0);
            }
            40%, 43% {
                transform: translate3d(0,-15px,0);
            }
            70% {
                transform: translate3d(0,-7px,0);
            }
            90% {
                transform: translate3d(0,-3px,0);
            }
        }
        
        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }
        
        .animate-pulse-custom {
            animation: pulse 0.5s ease-in-out;
        }
        
        .animate-bounce-custom {
            animation: bounce 0.8s ease-in-out;
        }
    </style>
@endsection

@section('body')
    @php
    // dd($productData);
        // Decode the JSON properly - this is the key fix!
        // $productDataArray = json_decode($productData, true);
        $productDataArray = $productData;
        
        // Get the product data from the 'data' key
        $productInfo = $productDataArray;
        
        // Extract product details directly - no fallbacks
        $productId = $productInfo['id'];
        $productName = $productInfo['name'];
        $productCategory = $productInfo['category'];
        $productCategoryId = $productInfo['category_id'];
        $productSubcategory = $productInfo['subcategory'];
        $productSubcategoryId = $productInfo['subcategory_id'];
        $productPartType = $productInfo['part_type'];
        $productBrand = $productInfo['brand'];
        $productModel = $productInfo['model'];
        $productMadeIn = $productInfo['made_in'];
        $productCondition = $productInfo['condition'];
        $productPrice = $productInfo['price'];
        $productOriginalPrice = $productInfo['original_price'];
        $productQuantity = $productInfo['quantity'];
        $productDescription = $productInfo['description'];
        $productShippingMethod = $productInfo['shipping_method'];
        $productShippingTime = $productInfo['shipping_time'];
        $productReturnPolicy = $productInfo['return_policy'];
        $productLocation = $productInfo['location'];
        $productImages = $productInfo['images'];
        $productVideo = $productInfo['video'];
        $productFaults = $productInfo['faults'];
        
        // Extract image URLs
        $imageUrls = [];
        foreach ($productImages as $img) {
            $imageUrls[] = $img['url'];
        }
        
        // Get primary image (first image)
        $primaryImage = $imageUrls[0];
        
        // Calculate GST and discount
        $gst = $productPrice * 1.17;
        $discount = 0;
        if ($productOriginalPrice > $gst) {
            $discount = round((($productOriginalPrice - $gst) / $productOriginalPrice) * 100);
        }
        
        // Stock status
        $stockStatus = '';
        $stockColor = '';
        $stockIcon = '';

        if ($productQuantity >= 10) {
            $stockStatus = 'In Stock';
            $stockColor = 'text-green-600';
            $stockIcon = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />';
        } elseif ($productQuantity > 0 && $productQuantity < 10) {
            $stockStatus = 'Limited Stock';
            $stockColor = 'text-yellow-600';
            $stockIcon = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-11a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V7z" clip-rule="evenodd" />';
        } else {
            $stockStatus = 'Out of Stock';
            $stockColor = 'text-red-600';
            $stockIcon = '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />';
        }
        
        // Vendor data
        $vendorName = $vendor->full_name;
        $vendorVerified = $vendor->verified;
        $vendorProfilePicture = $vendor->profile_picture;
        $vendorUserId = $vendor->user_id;
        
        // Check if current user is the product owner
        $isOwner = ($user && $user->user_id == $productInfo['vendor_id']);
    @endphp

    <x-cosmetics.header :user="$user" :vendor="$vendor" :profile="$profile" :dashboardPage="$dashboardPage" :imgPath="$imgPath" />

    <div id="main" class="mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <!-- Product Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 mb-12">

            <!-- Left Column: Images -->
            <div class="col-span-3 relative">
                <!-- Main Image with icons -->
                <div class="relative">
                    <img id="main-image"
                        src="{{ $primaryImage }}" 
                        class="border-2 border-pink-200 w-full h-[72vh] aspect-square object-contain rounded-lg overflow-hidden">
                    
                    <!-- Icons -->
                    <div class="absolute top-3 right-3 flex space-x-3">
                        <!-- Fullscreen Icon -->
                        <button id="fullscreen-btn" class="p-2 bg-white rounded-full shadow-md hover:bg-gray-100 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-6 h-6 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8 3H5a2 2 0 00-2 2v3m0 8v3a2 2 0 002 2h3m8-16h3a2 2 0 012 2v3m0 8v3a2 2 0 01-2 2h-3" />
                            </svg>
                        </button>
                        <!-- Heart Icon -->
                        <button id="heart-btn" data-product-id="{{ $productId }}" class="p-2 bg-white rounded-full shadow-md hover:bg-gray-100 transition">
                            <svg id="heart-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2" class="w-6 h-6 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.682l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Thumbnails Slider -->
                <div class="relative w-full mt-4">
                    <button id="scroll-left"
                        class="absolute left-0 top-1/2 -translate-y-1/2 bg-[#E85D85] text-white p-2 rounded-full z-10 hover:bg-[#C94A72] transition-colors">
                        &#10094;
                    </button>

                    <div id="image-slider"
                        class="grid grid-flow-col auto-cols-[19%] gap-2 overflow-x-auto scroll-smooth no-scrollbar px-8">
                        <!-- Thumbnails will be dynamically added here -->
                    </div>

                    <div class="bg-white absolute right-0 top-1/2 " style="height: 60px; width: 25px; margin-top: -30px; display: flex; justify-content: center; align-items: center;">
                        <button id="scroll-right" style="margin-top: 30px"
                            class="-translate-y-1/2 bg-[#E85D85] text-white p-2 rounded-full z-10 hover:bg-[#C94A72] transition-colors">
                            &#10095;
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Product Info & CTA -->
            <div class="mt-4 md:mt-32 col-span-2 relative">
                <h1 class="text-3xl font-bold mb-2">{{ $productName }}</h1>

                <!-- Discount Badge -->
                @if ($productOriginalPrice > $gst && $discount > 0)
                    <div class="absolute top-12 right-20 bg-red-600 text-white px-4 py-2 rounded-xl shadow-lg 
                                text-lg font-bold transform translate-x-4 -translate-y-4 z-10">
                        🔥 {{ $discount }}% OFF
                    </div>
                @endif
      
                <!-- Ratings & Verification -->
                <div class="flex items-center space-x-4 text-sm mb-4">
                    <div class="flex items-center space-x-1">
                        <span class="font-semibold">4.8</span>
                        <span class="text-gray-500">(125 reviews)</span>
                    </div>
                    
                    @if($vendorVerified)
                        <div class="flex items-center text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            Verified Seller
                        </div>
                    @else
                        <div class="flex items-center text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3-11a1 1 0 00-1.414-1.414L10 7.586 8.414 6A1 1 0 107 7.414L8.586 9 7 10.586A1 1 0 108.414 12L10 10.414 11.586 12A1 1 0 0013 10.586L11.414 9 13 7.414z" clip-rule="evenodd"/>
                            </svg>
                            Vendor Unverified
                        </div>
                    @endif
                </div>

                <!-- Price -->
                <p class="text-3xl font-extrabold text-gray-900 mb-4">
                    Rs. {{ number_format($gst, 2) }}
                    @if ($productOriginalPrice > $gst)
                        <br>
                        <small class="font-normal text-md"><del>Rs. {{ number_format($productOriginalPrice, 2) }}</del></small>
                    @endif
                </p>
                
                <!-- Vendor Info -->
                <div class="mb-4">
                    <p class="text-sm text-gray-500">Sold by <span class="font-semibold text-[#E85D85] hover:text-[#C94A72] cursor-pointer">{{ $vendorName }}</span></p>
                </div>
                
                <!-- Stock Status -->
                <div class="flex items-center {{ $stockColor }} font-semibold mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                        {!! $stockIcon !!}
                    </svg>
                    {{ $stockStatus }} ({{ $productQuantity }} available)
                </div>

                <!-- Quantity Selector -->
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Quantity</label>
                    <div class="flex w-32 border border-gray-300 rounded-lg overflow-hidden" id="quantityBtns">
                        <button id="decrease-qty" class="w-10 h-10 flex items-center justify-center text-xl text-gray-600 hover:bg-gray-100">-</button>
                        <input id="quantity" type="text" value="1" class="w-12 h-10 text-center border-x border-gray-300 focus:outline-none" readonly>
                        <button id="increase-qty" class="w-10 h-10 flex items-center justify-center text-xl text-gray-600 hover:bg-gray-100">+</button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 mb-6">
                    <button id="addToCartBtn" 
                    @if ($isOwner || $productQuantity <= 0)
                        style="background-color: #9ca3af; cursor: not-allowed; opacity: 0.6"
                        disabled
                    @else
                        onclick="addToCart()"
                    @endif 
                        class="flex-1 py-3 px-6 bg-black text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95 flex items-center justify-center"
                        >
                        <i class="fas fa-cart-plus mr-2"></i>
                        Add to Cart
                    </button>
                    <button class="flex-1 py-3 px-6 border border-[#E85D85] text-[#E85D85] font-semibold rounded-lg hover:bg-pink-50 transition duration-150"
                    @if ($isOwner || $productQuantity <= 0)
                        style="background-color: #9ca3af; cursor: not-allowed; opacity: 0.6"
                        disabled
                    @else
                        id="wh-btn"
                    @endif 
                    >
                        Buy Now
                    </button>
                </div>

                <!-- Success Message -->
                <div id="successMessage" class="mt-4 text-center text-green-600 font-semibold transition-all duration-300 opacity-0 transform translate-y-4">
                    <i class="fas fa-check-circle mr-2"></i>
                    Item added to cart successfully!
                </div>

                <script>
                    document.getElementById("wh-btn")?.addEventListener("click", function () {
                        const quantity = document.getElementById('quantity');
                        location.href = "/product/{{ $productId }}/buy/" + quantity.value;
                    });
                </script>

                <!-- Delivery & Payment Info -->
                <div class="space-y-3 text-sm">
                    <div class="flex items-center text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#E85D85]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M13 8V4.835a1 1 0 01.325-.758l2.25-2.25a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-2.25 2.25a1 1 0 01-.758.325H16M3 9h11a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6a1 1 0 011-1zm0 0l-1.5 7" /></svg>
                        Delivery charges included in price
                    </div>
                    <div class="flex items-center text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#E85D85]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
                        Estimated Delivery: <span class="font-semibold text-gray-800 ml-1">{{ $productShippingTime }} days</span>
                    </div>
                    <div class="flex items-center justify-between text-gray-700">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#E85D85]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $productReturnPolicy }}
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-[#E85D85]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.172a1 1 0 000-1.414L2.83 2.83a1 1 0 00-1.414 1.414L18.182 20.618a1 1 0 001.414-1.414L9.828 8.414a1 1 0 00-1.414 0L6.414 6.414a1 1 0 00-1.414 0z" /></svg>
                            100% Original Product
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Cart Summary -->
        <div id="cartSummary" class="fixed bottom-0 left-0 w-full h-20 bg-white shadow-lg border-t border-gray-200 flex items-center justify-between px-6 transform translate-y-full transition-transform duration-500 z-50">
            <div class="flex items-center">
                <div id="cartIcon" class="relative mr-4">
                    <i class="fas fa-shopping-cart text-2xl text-purple-600"></i>
                    <span id="itemCount" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold">0</span>
                </div>
                <div>
                    <p class="font-semibold text-gray-800"><span id="totalItems">0</span> items in cart</p>
                    <p class="text-sm text-gray-600">Total: Rs. <span id="totalPrice">0.00</span></p>
                </div>
            </div>
            <button id="viewCartBtn" class="bg-gradient-to-r from-[#FF7DA0] to-[#FFC275] text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300 hover:scale-105 hover:shadow-md">
                View Cart
            </button>
        </div>

        <!-- Enhanced Fullscreen Modal -->
        <div id="fullscreen-modal"
            class="hidden fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center modal-fade-in">
            <div class="relative flex flex-col md:flex-row items-center max-w-6xl w-full mx-4">
                <!-- Close Button -->
                <button id="close-fullscreen"
                    class="absolute -top-12 right-0 md:top-4 md:right-4 bg-white text-black rounded-full p-2 hover:bg-gray-200 transition-colors z-10 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Main Image Container -->
                <div class="flex-1 flex items-center justify-center p-4">
                    <img id="fullscreen-image" src="{{ $primaryImage }}"
                        class="max-h-[85vh] w-full rounded-xl object-contain shadow-2xl">
                </div>

                <!-- Thumbnail Container with Navigation -->
                <div class="relative mt-4 md:mt-0 md:ml-6 flex flex-row md:flex-col">
                    <!-- Up Arrow (for vertical layout) -->
                    <button id="thumb-up" class="hidden md:flex absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white rounded-full p-1 hover:bg-gray-700 transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                    
                    <!-- Thumbnail List -->
                    <div id="thumbnails-container" class="thumbnails-container flex md:flex-col space-x-2 md:space-x-0 md:space-y-2 overflow-x-auto md:overflow-y-auto max-h-24 md:max-h-80 p-2">
                        <!-- Thumbnails will be dynamically added here -->
                    </div>
                    
                    <!-- Down Arrow (for vertical layout) -->
                    <button id="thumb-down" class="hidden md:flex absolute -bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white rounded-full p-1 hover:bg-gray-700 transition-colors z-10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <script src="{{ asset('js/fav.js') }}"></script>
        <script>
            // Add to Cart functionality
            async function addToCart() {
                const button = document.getElementById('addToCartBtn');
                const successMessage = document.getElementById('successMessage');
                const cartSummary = document.getElementById('cartSummary');
                const itemCount = document.getElementById('itemCount');
                const totalItems = document.getElementById('totalItems');
                const totalPriceElement = document.getElementById('totalPrice');
                const cartIcon = document.getElementById('cartIcon');
                const quantity = document.getElementById('quantity');
                
                try {
                    const response = await fetch('/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            product_id: {{ $productId }},
                            quantity: quantity.value
                        })
                    });

                    const data = await response.json();
                    console.log(data);

                    if (data.success) {
                        // Update cart display
                        itemCount.textContent = data.cart_count;
                        totalItems.textContent = data.cart_count;
                        totalPriceElement.textContent = data.cart_total.toFixed(2);
                        
                        // Show cart summary with animation
                        cartSummary.classList.remove('translate-y-full');
                        cartSummary.classList.add('animate-slide-up');
                        
                        // Cart icon bounce animation
                        cartIcon.classList.add('animate-bounce-custom');
                        
                        // Show success message
                        successMessage.classList.remove('opacity-0', 'translate-y-4');
                        successMessage.classList.add('opacity-100', 'translate-y-0');

                        button.disabled = true;
                        button.style.backgroundColor = "#9ca3af";
                        button.style.cursor = "not-allowed";
                        button.style.opacity = "0.6";
                        
                        // Hide success message after 2 seconds
                        setTimeout(() => {
                            successMessage.classList.remove('opacity-100', 'translate-y-0');
                            successMessage.classList.add('opacity-0', 'translate-y-4');
                        }, 2000);
                    } else {
                        alert('Failed to add product to cart');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error adding product to cart');
                }
                
                // Reset button animation
                setTimeout(() => {
                    button.classList.remove('animate-pulse-custom');
                }, 500);
                
                // Reset cart icon animation
                setTimeout(() => {
                    cartIcon.classList.remove('animate-bounce-custom');
                }, 800);
            }
            
            // View Cart button functionality
            document.getElementById('viewCartBtn').addEventListener('click', function() {
                window.location.href = '/cart';
            });

            // Load cart summary on page load
            async function loadCartSummary() {
                try {
                    const response = await fetch('/cart/summary');
                    const data = await response.json();

                    const itemCount = document.getElementById('itemCount');
                    const totalItems = document.getElementById('totalItems');
                    const totalPriceElement = document.getElementById('totalPrice');
                    const cartSummary = document.getElementById('cartSummary');

                    if (data.cart_count > 0) {
                        itemCount.textContent = data.cart_count;
                        totalItems.textContent = data.cart_count;
                        totalPriceElement.textContent = data.cart_total.toFixed(2);
                        cartSummary.classList.remove('translate-y-full');
                    }
                } catch (error) {
                    console.error('Error loading cart summary:', error);
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                loadCartSummary();
                // Elements
                const slider = document.getElementById('image-slider');
                const leftBtn = document.getElementById('scroll-left');
                const rightBtn = document.getElementById('scroll-right');
                const mainImage = document.getElementById('main-image');
                const fullscreenBtn = document.getElementById('fullscreen-btn');
                const fullscreenModal = document.getElementById('fullscreen-modal');
                const fullscreenImage = document.getElementById('fullscreen-image');
                const closeFullscreen = document.getElementById('close-fullscreen');
                const thumbUpBtn = document.getElementById('thumb-up');
                const thumbDownBtn = document.getElementById('thumb-down');
                const thumbnailsContainer = document.getElementById('thumbnails-container');
                const decreaseQtyBtn = document.getElementById('decrease-qty');
                const increaseQtyBtn = document.getElementById('increase-qty');
                const quantityInput = document.getElementById('quantity');
                
                // Images from backend
                const images = @json($imageUrls);
                console.log('Images loaded:', images);
                
                // Initialize thumbnails for main gallery
                function initThumbnails() {
                    slider.innerHTML = '';
                    
                    images.forEach((image, index) => {
                        const thumb = document.createElement('img');
                        thumb.src = image;
                        thumb.className = 'thumb aspect-square object-cover border-2 border-pink-200 rounded-md w-32 h-16 lg:h-32 cursor-pointer hover:opacity-75 transition flex-shrink-0';
                        
                        // Set first thumbnail as active
                        if (index === 0) {
                            thumb.classList.add('border-[#E85D85]', 'border-opacity-80');
                        }
                        
                        thumb.addEventListener('click', function() {
                            // Update main image
                            mainImage.src = this.src;
                            
                            // Update active thumbnail
                            document.querySelectorAll('.thumb').forEach(t => {
                                t.classList.remove('border-[#E85D85]', 'border-opacity-80');
                            });
                            this.classList.add('border-[#E85D85]', 'border-opacity-80');
                        });
                        
                        slider.appendChild(thumb);
                    });
                }
                
                // Initialize thumbnails for fullscreen modal
                function initFullscreenThumbnails() {
                    thumbnailsContainer.innerHTML = '';
                    
                    images.forEach((image, index) => {
                        const thumb = document.createElement('img');
                        thumb.src = image;
                        thumb.className = 'thumb-full w-20 h-20 object-cover rounded-md border-2 border-transparent cursor-pointer hover:border-white flex-shrink-0';
                        
                        // Set first thumbnail as active
                        if (index === 0) {
                            thumb.classList.add('border-white', 'border-opacity-80');
                        }
                        
                        thumb.addEventListener('click', function() {
                            // Update fullscreen image
                            fullscreenImage.src = this.src;
                            
                            // Update active thumbnail
                            document.querySelectorAll('.thumb-full').forEach(t => {
                                t.classList.remove('border-white', 'border-opacity-80');
                            });
                            this.classList.add('border-white', 'border-opacity-80');
                        });
                        
                        thumbnailsContainer.appendChild(thumb);
                    });
                }
                
                // Scroll thumbnails in fullscreen modal
                function scrollThumbnails(direction) {
                    const scrollAmount = 100;
                    
                    if (window.innerWidth >= 768) {
                        // Vertical scrolling for desktop
                        thumbnailsContainer.scrollBy({
                            top: direction * scrollAmount,
                            behavior: 'smooth'
                        });
                    } else {
                        // Horizontal scrolling for mobile
                        thumbnailsContainer.scrollBy({
                            left: direction * scrollAmount,
                            behavior: 'smooth'
                        });
                    }
                }
                
                // Event listeners
                if (rightBtn) {
                    rightBtn.addEventListener('click', () => {
                        slider.scrollBy({ left: slider.clientWidth * 0.5, behavior: 'smooth' });
                    });
                }
                
                if (leftBtn) {
                    leftBtn.addEventListener('click', () => {
                        slider.scrollBy({ left: -slider.clientWidth * 0.5, behavior: 'smooth' });
                    });
                }
                
                // Fullscreen open
                if (fullscreenBtn) {
                    fullscreenBtn.addEventListener('click', () => {
                        fullscreenImage.src = mainImage.src;
                        fullscreenModal.classList.remove('hidden');
                        document.body.style.overflow = 'hidden';
                        initFullscreenThumbnails();
                    });
                }
                
                // Fullscreen close
                if (closeFullscreen) {
                    closeFullscreen.addEventListener('click', () => {
                        fullscreenModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    });
                }
                
                // Fullscreen thumbnail navigation
                if (thumbUpBtn) {
                    thumbUpBtn.addEventListener('click', function() {
                        scrollThumbnails(-1);
                    });
                }
                
                if (thumbDownBtn) {
                    thumbDownBtn.addEventListener('click', function() {
                        scrollThumbnails(1);
                    });
                }
                
                // Quantity selector
                if (decreaseQtyBtn) {
                    decreaseQtyBtn.addEventListener('click', function() {
                        let currentQty = parseInt(quantityInput.value);
                        if (currentQty > 1 && currentQty <= {{ $productQuantity }}) {
                            quantityInput.value = currentQty - 1;
                        }
                    });
                }
                
                if (increaseQtyBtn) {
                    increaseQtyBtn.addEventListener('click', function() {
                        let currentQty = parseInt(quantityInput.value);
                        if (currentQty < {{ $productQuantity }}) {
                            quantityInput.value = currentQty + 1;
                        }
                    });
                }
                
                // Close modal when clicking outside the content
                if (fullscreenModal) {
                    fullscreenModal.addEventListener('click', function(e) {
                        if (e.target === fullscreenModal) {
                            fullscreenModal.classList.add('hidden');
                            document.body.style.overflow = '';
                        }
                    });
                }
                
                // Handle escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && fullscreenModal && !fullscreenModal.classList.contains('hidden')) {
                        fullscreenModal.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                });
                
                // Adjust layout based on screen size
                function adjustLayout() {
                    if (window.innerWidth < 768) {
                        // Mobile layout
                        if (thumbUpBtn) thumbUpBtn.classList.add('hidden');
                        if (thumbDownBtn) thumbDownBtn.classList.add('hidden');
                    } else {
                        // Desktop layout
                        if (thumbUpBtn) thumbUpBtn.classList.remove('hidden');
                        if (thumbDownBtn) thumbDownBtn.classList.remove('hidden');
                    }
                }
                
                // Initial setup
                initThumbnails();
                adjustLayout();
                
                // Adjust layout on window resize
                window.addEventListener('resize', adjustLayout);
            });
        </script>

        <!-- Tabs Section -->
        <div class="mb-12 grid grid-cols-1 md:grid-cols-2">
            
            <div>
                <!-- Tab Headers/Buttons -->
                <div class="flex border-b border-gray-200 text-gray-500 text-lg space-x-8">
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
                        <p id="productText">{{ $productDescription }}</p>
                        <button
                            id="toggleBtn"
                            class="mt-2 text-[#E85D85] font-medium hover:underline hidden">
                            Read more
                        </button>
                    </div>
                    <script>
                        const textElement = document.getElementById("productText");
                        const toggleBtn = document.getElementById("toggleBtn");

                        if (textElement && toggleBtn) {
                            const fullText = textElement.innerText;
                            const words = fullText.split(" ");
                            const limit = 500;

                            if (words.length > limit) {
                                const shortText = words.slice(0, limit).join(" ") + "...";
                                let isExpanded = false;

                                textElement.innerText = shortText;
                                toggleBtn.classList.remove("hidden");

                                toggleBtn.addEventListener("click", () => {
                                    if (!isExpanded) {
                                        textElement.innerText = fullText;
                                        toggleBtn.innerText = "Read less";
                                    } else {
                                        textElement.innerText = shortText;
                                        toggleBtn.innerText = "Read more";
                                    }
                                    isExpanded = !isExpanded;
                                });
                            }
                        }
                    </script>

                    <!-- Tab 2: Specifications Content -->
                    <div id="content-specifications" class="tab-content hidden">
                        <h3 class="text-xl font-semibold mb-4">Technical Specifications</h3>
                        <table class="w-full text-left border-collapse">
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Category</th>
                                <td class="py-2">{{ $productCategory }} (ID: {{ $productCategoryId }})</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Subcategory</th>
                                <td class="py-2">{{ $productSubcategory }} (ID: {{ $productSubcategoryId }})</td>
                            </tr>
                            @if($productPartType)
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Part Type</th>
                                <td class="py-2">{{ $productPartType }}</td>
                            </tr>
                            @endif
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Brand</th>
                                <td class="py-2">{{ $productBrand }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Model</th>
                                <td class="py-2">{{ $productModel }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Condition</th>
                                <td class="py-2">{{ $productCondition }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Made In</th>
                                <td class="py-2">{{ $productMadeIn }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Location</th>
                                <td class="py-2">{{ $productLocation }}</td>
                            </tr>
                            <tr class="border-b">
                                <th class="py-2 text-gray-500 font-normal w-1/4">Shipping Method</th>
                                <td class="py-2">{{ ucfirst($productShippingMethod) }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Tab 3: Reviews Content -->
                    <div id="content-reviews" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            
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
                                @if($vendorProfilePicture)
                                    <img src="{{ asset('storage/vendor/profile/' . $vendorProfilePicture) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-lg flex items-center">
                                    {{ $vendorName }}
                                    <span class="ml-2 h-2 w-2 bg-primary-blue rounded-full"></span>
                                    <span class="ml-1 text-sm text-primary-blue">{{ $vendorVerified ? "Verified" : "Unverified" }}</span>
                                </p>
                                @if($vendorUserId)
                                    <a href="/vendor-products/{{ $vendorUserId }}" class="text-sm text-primary-blue hover:text-[#C94A72] transition duration-150">
                                        View all products from this vendor &rarr;
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Video Element -->
            @if($productVideo)
            <div class="w-full flex justify-center items-center py-10 bg-gradient-to-b from-pink50 to-white">
                <div class="relative w-full max-w-3xl rounded-2xl overflow-hidden shadow-2xl border-4 border-pink-200 hover:border-pink-400 transition-all duration-300">
                    <video
                        controls
                        poster="{{ asset('img/default_img.png') }}"
                        class="w-full rounded-2xl h-[366px]">
                        <source src="{{ $productVideo }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>

                    <!-- Optional overlay label -->
                    <div class="absolute top-3 left-3 bg-pink-600 text-white text-sm font-semibold px-3 py-1 rounded-full shadow-md">
                        Watch Now
                    </div>
                </div>
            </div>
            @endif

        </div>

        <div class="mb-4">
            <div class="flex justify-between align-center">
                <h3 class="text-xl font-semibold mt-8 mb-0">Customer Reviews</h3>
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
                    <div class="w-8 h-8 rounded-full bg-pink-100 overflow-hidden">
                        
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

            <!-- Example Review 3 -->
            <div class="border-t pt-4 mt-4 space-y-2">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-pink-100 overflow-hidden">
                        
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
        @if($productCategory)
            @include('../products.category', ['category' => $productCategory, 'id' => 'gym', 'related' => true])
        @endif

    </div>

    <x-footer />

    <script src="{{ asset('js/search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/product-card.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/category_fetch.js') }}?v={{ time() }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-blue': '#E85D85',
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