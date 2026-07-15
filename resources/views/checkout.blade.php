<!DOCTYPE html>
<html lang="en">
<head>
    <script src="{{ asset('js/img-fallback.js') }}"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | MJ Cheezain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', ui-sans-serif, system-ui, sans-serif; }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#E85D85',
                        secondary: '#FFC275',
                        accent: '#FF7DA0',
                        fashion: {
                            pink: '#ec4899',
                            purple: '#a855f7',
                            teal: '#14b8a6'
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <x-cosmetics.header :user="$user ?? null" :vendor="$vendor ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <!-- Checkout Section -->
    <section id="main" class="py-8">
        <div class="container mx-auto px-4">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Checkout Form -->
                <div class="lg:w-2/3">
                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="bg-white rounded-2xl p-4 sm:p-6 relative overflow-hidden" style="box-shadow: 0 6px 20px rgba(17, 24, 39, 0.06);">
                        @csrf
                        <div class="absolute top-0 left-0 w-full h-[3px]" style="background: linear-gradient(to right, #FF7DA0, #FFC275);"></div>
                        <h1 class="text-lg sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-5">Checkout</h1>

                        <!-- Saved Addresses -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <h2 class="text-sm sm:text-base font-bold text-gray-800 flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full text-white text-[11px] font-bold flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">1</span>
                                    Delivery Address
                                </h2>
                                <a href="/customer/addresses" class="text-primary hover:text-pink-600 text-xs sm:text-sm font-semibold">
                                    Manage
                                </a>
                            </div>

                            <div class="space-y-2">
                                @if($addresses->count() > 0)
                                    @foreach($addresses as $index => $address)
                                        <label class="flex items-start p-3 border border-pink-100 rounded-xl cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-pink-50/40 transition-colors duration-200">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" class="text-primary focus:ring-primary mt-0.5" {{ $address->is_default ? 'checked' : ($index == 0 ? 'checked' : '') }}>
                                            <div class="ml-2.5 flex-1 min-w-0">
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="flex items-center gap-1.5 min-w-0">
                                                        <span class="font-semibold text-gray-800 text-sm truncate">{{ $address->full_name }}</span>
                                                        <span class="px-1.5 py-0.5 bg-pink-100 text-primary text-[10px] font-bold rounded flex-shrink-0">{{ $address->address_type }}</span>
                                                        @if($address->is_default)
                                                            <i class="fas fa-star text-primary text-[10px] flex-shrink-0" title="Default"></i>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-gray-400 flex-shrink-0">{{ $address->phone }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-0.5 line-clamp-2 m-0">
                                                    {{ $address->address_line1 }}@if($address->address_line2), {{ $address->address_line2 }}@endif, {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}, {{ $address->country }}
                                                </p>
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <div class="p-4 border border-dashed border-pink-200 rounded-xl text-center">
                                        <p class="text-gray-500 text-sm mb-3">No addresses found. Please add a delivery address.</p>
                                        <a href="/customer/addresses" class="inline-flex items-center px-4 py-2 text-white text-sm font-semibold rounded-full hover:opacity-90" style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">
                                            <i class="fas fa-plus mr-2"></i> Add New Address
                                        </a>
                                    </div>
                                @endif

                                <!-- Option to add new address -->
                                <div id="new-address-section" class="hidden">
                                    <div class="p-4 border border-dashed border-pink-200 rounded-xl bg-pink-50/40">
                                        <h3 class="font-semibold text-gray-800 mb-3">Add New Address</h3>
                                        <div class="space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <select name="address_type" class="border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                                    <option value="Home">Home</option>
                                                    <option value="Work">Work</option>
                                                    <option value="Family">Family</option>
                                                    <option value="Apartment">Apartment</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <input type="text" name="full_name" placeholder="Full Name" class="border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                            </div>
                                            <input type="tel" name="phone" placeholder="Phone Number" class="w-full border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                            <textarea name="address_line1" placeholder="Address Line 1" rows="2" class="w-full border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300"></textarea>
                                            <input type="text" name="address_line2" placeholder="Address Line 2 (Optional)" class="w-full border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                <input type="text" name="city" placeholder="City" class="border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                                <input type="text" name="state" placeholder="State" class="border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                                <input type="text" name="zip_code" placeholder="Zip Code" class="border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                                <input type="text" name="country" placeholder="Country" value="Kuwait" class="border border-pink-100 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300">
                                            </div>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="cancelNewAddress()" class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50">Cancel</button>
                                                <button type="button" id="save-new-address" class="px-4 py-2 text-sm bg-primary text-white rounded hover:opacity-90">Save Address</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Use different address option -->
                                <label class="flex items-center p-3 border border-dashed border-pink-200 rounded-xl cursor-pointer hover:border-primary transition-colors duration-200" onclick="showNewAddressSection()">
                                    <input type="radio" name="address_id" value="new" class="text-primary focus:ring-primary">
                                    <div class="ml-2.5 flex-1">
                                        <span class="font-semibold text-gray-800 text-sm"><i class="fas fa-plus text-primary text-xs mr-1"></i> Use a different address</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <h2 class="text-sm sm:text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full text-white text-[11px] font-bold flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">2</span>
                                Payment Method
                            </h2>
                            <div class="space-y-2">
                                <!-- KNET Payment -->
                                <label class="flex items-center p-3 border border-pink-100 rounded-xl cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-pink-50/40 transition-colors duration-200">
                                    <input type="radio" name="payment_method" value="knet" class="text-primary focus:ring-primary" checked>
                                    <div class="ml-2.5 flex items-center gap-2.5 flex-1 min-w-0">
                                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <span class="text-blue-600 font-bold text-sm">K</span>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-semibold text-gray-800 text-sm">KNET</span>
                                            <p class="text-xs text-gray-400 m-0 truncate">Secure online payment</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Credit/Debit Card Payment -->
                                <label class="flex items-center p-3 border border-pink-100 rounded-xl cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-pink-50/40 transition-colors duration-200" onclick="showCreditCardForm()">
                                    <input type="radio" name="payment_method" value="credit_card" class="text-primary focus:ring-primary">
                                    <div class="ml-2.5 flex items-center gap-2.5 flex-1 min-w-0">
                                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-credit-card text-gray-600 text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-semibold text-gray-800 text-sm">Credit/Debit Card</span>
                                            <p class="text-xs text-gray-400 m-0 truncate">Visa, MasterCard, Amex</p>
                                        </div>
                                    </div>
                                </label>

                                <!-- Credit Card Form (Hidden by default) -->
                                <div id="credit-card-form" class="hidden p-4 border border-pink-100 rounded-xl bg-pink-50/30">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Card Number *
                                            </label>
                                            <div class="relative">
                                                <input 
                                                    type="text" 
                                                    name="card_number"
                                                    id="cardNumber"
                                                    maxlength="19"
                                                    placeholder="1234 5678 9012 3456"
                                                    class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm pl-12 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors duration-300"
                                                >
                                                <div class="absolute left-3 top-3 flex space-x-2">
                                                    <div class="w-8 h-5 bg-red-600 rounded-sm"></div>
                                                    <div class="w-8 h-5 bg-blue-600 rounded-sm"></div>
                                                    <div class="w-8 h-5 bg-yellow-600 rounded-sm"></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    Expiry Date *
                                                </label>
                                                <input 
                                                    type="text" 
                                                    name="card_expiry"
                                                    id="expiryDate"
                                                    maxlength="5"
                                                    placeholder="MM/YY"
                                                    class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors duration-300"
                                                >
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                    CVV *
                                                </label>
                                                <div class="relative">
                                                    <input 
                                                        type="password" 
                                                        name="card_cvv"
                                                        id="cvv"
                                                        maxlength="4"
                                                        placeholder="123"
                                                        class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm pr-12 focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors duration-300"
                                                    >
                                                    <button type="button" onclick="toggleCVV()" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Cardholder Name *
                                            </label>
                                            <input 
                                                type="text" 
                                                name="card_holder"
                                                id="cardholderName"
                                                placeholder="As shown on card"
                                                class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors duration-300"
                                            >
                                        </div>

                                        <div class="flex items-center">
                                            <input type="checkbox" name="save_card" id="saveCard" class="text-primary focus:ring-primary">
                                            <label for="saveCard" class="ml-2 text-sm text-gray-600">
                                                Save this card for future purchases
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cash on Delivery -->
                                <label class="flex items-center p-3 border border-pink-100 rounded-xl cursor-pointer hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-pink-50/40 transition-colors duration-200" onclick="hideCreditCardForm()">
                                    <input type="radio" name="payment_method" value="cod" class="text-primary focus:ring-primary">
                                    <div class="ml-2.5 flex items-center gap-2.5 flex-1 min-w-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-money-bill text-green-600 text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-semibold text-gray-800 text-sm">Cash on Delivery</span>
                                            <p class="text-xs text-gray-400 m-0 truncate">Pay when you receive your order</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-6">
                            <h2 class="text-sm sm:text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full text-white text-[11px] font-bold flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0, #FFC275);">3</span>
                                Contact Information
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Email Address *</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        required
                                        value="{{ Auth::user()->email }}"
                                        class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors"
                                    >
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 mb-1">Phone Number *</label>
                                    @php
                                        $defaultAddress = $addresses->count() > 0 ? ($addresses->where('is_default', 1)->first() ?? $addresses->first()) : null;
                                    @endphp
                                    <input
                                        type="tel"
                                        name="phone"
                                        id="phone"
                                        required
                                        value="{{ $defaultAddress->phone ?? '' }}"
                                        class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors"
                                    >
                                </div>
                            </div>

                            <!-- Billing same-as-shipping (kept for the JS toggle; billing form appears only if unchecked) -->
                            <div class="flex items-center mt-3">
                                <input type="checkbox" id="sameAsShipping" class="text-primary focus:ring-primary rounded" checked>
                                <label for="sameAsShipping" class="ml-2 text-xs text-gray-500">
                                    Billing address is same as shipping address
                                </label>
                            </div>
                            <div id="billing-address-section" class="hidden"></div>

                            <!-- Order notes (compact) -->
                            <textarea
                                name="order_notes"
                                id="orderNotes"
                                rows="2"
                                class="w-full border border-pink-100 rounded-xl px-3.5 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-pink-300 focus:border-pink-300 transition-colors mt-3"
                                placeholder="Order notes (optional) — any special instructions..."
                            ></textarea>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-5">
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" name="terms" required class="text-primary focus:ring-primary mt-0.5 rounded">
                                <label for="terms" class="ml-2 text-xs text-gray-500 leading-relaxed">
                                    I agree to the <a href="#" class="text-primary hover:underline font-semibold">Terms &amp; Conditions</a> and <a href="" class="text-primary hover:underline font-semibold">Privacy Policy</a>.
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-3 pt-4 border-t border-pink-50">
                            <a href="/cart" class="text-primary hover:text-pink-600 text-sm font-semibold transition-colors duration-300 flex items-center justify-center sm:justify-start gap-2 w-full sm:w-auto py-2">
                                <i class="fas fa-arrow-left text-xs"></i>
                                <span>Return to Cart</span>
                            </a>
                            <button type="submit" class="text-white px-8 py-3 rounded-full font-bold text-sm sm:text-base transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 flex items-center justify-center gap-2 w-full sm:w-auto" style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 6px 18px rgba(255, 125, 160, 0.35);">
                                <i class="fas fa-lock text-xs"></i>
                                <span>Pay Rs. {{ number_format($total, 2) }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-2xl p-4 sm:p-5 lg:sticky lg:top-24 relative overflow-hidden" style="box-shadow: 0 6px 20px rgba(17, 24, 39, 0.06);">
                        <div class="absolute top-0 left-0 w-full h-[3px]" style="background: linear-gradient(to right, #FF7DA0, #FFC275);"></div>
                        <h2 class="text-base sm:text-lg font-bold text-gray-800 mb-3">Order Summary</h2>

                        <!-- Order Items (compact rows) -->
                        <div class="space-y-2.5 mb-4 max-h-64 overflow-y-auto pr-1">
                            @if($cartItems->count() > 0)
                                @foreach($cartItems as $item)
                                    @php($item->selling_price *= 1.17)
                                    <div class="flex items-center gap-2.5 p-2 rounded-xl border border-pink-50 bg-pink-50/30">
                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden relative">
                                            @if($item->primary_image)
                                                <img src="{{ asset('storage/vendor/products/images/' . $item->primary_image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-xl">📦</span>
                                            @endif
                                            <span class="absolute -top-1 -right-1 w-4.5 h-4.5 min-w-[18px] px-0.5 text-white text-[10px] font-bold rounded-full flex items-center justify-center" style="background: linear-gradient(115deg, #FF7DA0, #E85D85);">{{ $item->cq }}</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold text-gray-800 text-xs sm:text-sm truncate m-0">{{ $item->name }}</h3>
                                            <p class="text-gray-400 text-[11px] m-0 truncate">{{ $item->brand ?? 'N/A' }} · Qty {{ $item->cq }}</p>
                                        </div>
                                        <span class="font-bold text-gray-800 text-xs sm:text-sm whitespace-nowrap">Rs. {{ number_format($item->selling_price * $item->cq, 0) }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-500 text-sm">Your cart is empty</p>
                                    <a href="/" class="text-primary hover:underline text-sm font-semibold">Continue Shopping</a>
                                </div>
                            @endif
                        </div>

                        <!-- Summary Details -->
                        <div class="space-y-2 mb-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Subtotal</span>
                                <span class="text-gray-800 font-medium">Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Shipping</span>
                                <span class="text-gray-800 font-medium">Rs. {{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tax</span>
                                <span class="text-gray-800 font-medium">Rs. {{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Discount</span>
                                <span class="text-green-600 font-medium">-Rs. {{ number_format($discount, 2) }}</span>
                            </div>
                            <div class="border-t border-pink-50 pt-2.5">
                                <div class="flex justify-between text-base font-bold">
                                    <span class="text-gray-800">Total</span>
                                    <span class="text-primary">Rs. {{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security + support strip (compact) -->
                        <div class="flex items-center justify-center gap-3 text-gray-400 text-[11px] pt-2 border-t border-pink-50 flex-wrap">
                            <span><i class="fas fa-lock mr-1"></i>SSL Secure</span>
                            <span><i class="fas fa-undo mr-1"></i>Easy Returns</span>
                            <span><i class="fas fa-headset mr-1"></i>24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <x-footer />

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Order Placed Successfully!</h3>
            <p class="text-gray-600 mb-4">Thank you for your purchase. Your order has been confirmed.</p>
            <div class="bg-gray-50 rounded-lg p-4 mb-4 text-left">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-600">Order Number:</span>
                    <span class="font-semibold text-gray-800">ORD-{{ strtoupper(uniqid()) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Estimated Delivery:</span>
                    <span class="font-semibold text-gray-800">{{ date('M d-e, Y', strtotime('+3 days')) }}</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeSuccessModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors duration-300">
                    Continue Shopping
                </button>
                <a href="/customer/orders" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 transition-colors duration-300 text-center">
                    Track Order
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/search.js') }}?v={{ time() }}"></script>
    <script>
        // Form submission
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const requiredFields = ['email', 'phone'];
            let isValid = true;
            
            requiredFields.forEach(field => {
                const input = document.getElementById(field);
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                    input.addEventListener('input', function() {
                        this.classList.remove('border-red-500');
                    });
                }
            });
            
            // Check if address is selected
            const addressSelected = document.querySelector('input[name="address_id"]:checked');
            // if (!addressSelected || addressSelected.value === 'new') {
            //     isValid = false;
            //     showMessage('Please select or add a delivery address', 'error');
            //     return;
            // }
            
            // Terms & Conditions validation
            if (!document.getElementById('terms').checked) {
                isValid = false;
                showMessage('Please agree to the Terms & Conditions', 'error');
                return;
            }
            
            // Credit card validation if selected
            const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            if (paymentMethod === 'credit_card') {
                const cardNumber = document.getElementById('cardNumber').value;
                const expiryDate = document.getElementById('expiryDate').value;
                const cvv = document.getElementById('cvv').value;
                const cardholderName = document.getElementById('cardholderName').value;
                
                if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
                    showMessage('Please fill in all credit card details', 'error');
                    return;
                }
                
                // Validate card number (basic validation)
                const cleanedCardNumber = cardNumber.replace(/\s/g, '');
                if (!/^\d{16}$/.test(cleanedCardNumber)) {
                    showMessage('Please enter a valid 16-digit card number', 'error');
                    return;
                }
                
                // Validate expiry date
                const expiryRegex = /^(0[1-9]|1[0-2])\/\d{2}$/;
                if (!expiryRegex.test(expiryDate)) {
                    showMessage('Please enter a valid expiry date (MM/YY)', 'error');
                    return;
                }
                
                // Validate CVV
                if (!/^\d{3,4}$/.test(cvv)) {
                    showMessage('Please enter a valid CVV (3 or 4 digits)', 'error');
                    return;
                }
            }
            
            if (!isValid) {
                showMessage('Please fill in all required fields', 'error');
                return;
            }
            
            // Email validation
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showMessage('Please enter a valid email address', 'error');
                return;
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing Payment...</span>';
            submitButton.disabled = true;
            
            // Submit form via AJAX
            const formData = new FormData(this);
            
            if (location.href.toLowerCase().includes('buy')) {
                formData.append('single_buy', 1);
            }
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
                // return;
                if (data.success) {
                    // Show success modal
                    document.getElementById('successModal').classList.remove('hidden');
                } else {
                    showMessage(data.message || 'Something went wrong', 'error');
                    submitButton.innerHTML = originalText;
                    submitButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Something went wrong. Please try again.', 'error');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });

        // Save new address via AJAX
        document.getElementById('save-new-address').addEventListener('click', function() {
            const formData = new FormData();
            const addressSection = document.getElementById('new-address-section');
            
            // Collect form data
            const inputs = addressSection.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                formData.append(input.name, input.value);
            });
            
            // Add user_id
            formData.append('user_id', '{{ Auth::id() }}');
            
            
            // Show loading
            const button = this;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            button.disabled = true;
            

            
            fetch('/customer/address/save', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to show new address
                    window.location.reload();
                } else {
                    showMessage(data.message || 'Failed to save address', 'error');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Something went wrong', 'error');
                button.innerHTML = originalText;
                button.disabled = false;
            });
        });

        // Close success modal
        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            window.location.href = '{{ route("home") }}';
        }

        // Function to show message
        function showMessage(message, type) {
            const messageEl = document.createElement('div');
            messageEl.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            messageEl.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(messageEl);
            
            setTimeout(() => {
                messageEl.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(messageEl);
                }, 300);
            }, 3000);
        }

        // Auto-format phone number
        document.getElementById('phone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.startsWith('965')) {
                value = '+' + value;
            }
            if (value.length > 4) {
                value = value.substring(0, 4) + ' ' + value.substring(4);
            }
            if (value.length > 9) {
                value = value.substring(0, 9) + ' ' + value.substring(9, 13);
            }
            e.target.value = value;
        });

        // Format card number
        document.getElementById('cardNumber').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 16);
            let formatted = '';
            for (let i = 0; i < value.length; i += 4) {
                formatted += value.substring(i, i + 4) + ' ';
            }
            e.target.value = formatted.trim();
        });

        // Format expiry date
        document.getElementById('expiryDate').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.substring(0, 4);
            if (value.length >= 2) {
                e.target.value = value.substring(0, 2) + '/' + value.substring(2);
            } else {
                e.target.value = value;
            }
        });

        // Toggle CVV visibility
        function toggleCVV() {
            const cvvInput = document.getElementById('cvv');
            const toggleButton = cvvInput.nextElementSibling;
            
            if (cvvInput.type === 'password') {
                cvvInput.type = 'text';
                toggleButton.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                cvvInput.type = 'password';
                toggleButton.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        // Show credit card form
        function showCreditCardForm() {
            document.getElementById('credit-card-form').classList.remove('hidden');
        }

        // Hide credit card form
        function hideCreditCardForm() {
            document.getElementById('credit-card-form').classList.add('hidden');
        }

        // Show new address section
        function showNewAddressSection() {
            document.getElementById('new-address-section').classList.remove('hidden');
            // Uncheck other address radios
            document.querySelectorAll('input[name="address_id"]').forEach(radio => {
                if (radio.value !== 'new') {
                    radio.checked = false;
                }
            });
        }

        // Cancel new address
        function cancelNewAddress() {
            document.getElementById('new-address-section').classList.add('hidden');
            // Check first address
            const firstAddress = document.querySelector('input[name="address_id"]:not([value="new"])');
            if (firstAddress) {
                firstAddress.checked = true;
            }
        }

        // Billing address toggle
        document.getElementById('sameAsShipping').addEventListener('change', function() {
            const billingSection = document.getElementById('billing-address-section');
            if (!this.checked) {
                billingSection.classList.remove('hidden');
            } else {
                billingSection.classList.add('hidden');
            }
        });
    </script>
</body>
</html>