<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - AlKuwait</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#f97316',
                        accent: '#f59e0b',
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
                    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6">
                        @csrf
                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Checkout</h1>

                        <!-- Saved Addresses -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">Select Delivery Address</h2>
                                <a href="/customer/addresses" class="text-primary hover:text-blue-700 text-sm font-medium">
                                    <i class="fas fa-plus mr-1"></i> Manage Addresses
                                </a>
                            </div>
                            
                            <div class="space-y-3">
                                @if($addresses->count() > 0)
                                    @foreach($addresses as $index => $address)
                                        <label class="flex items-start p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-300">
                                            <input type="radio" name="address_id" value="{{ $address->id }}" class="text-primary focus:ring-primary mt-1" {{ $address->is_default ? 'checked' : ($index == 0 ? 'checked' : '') }}>
                                            <div class="ml-3 flex-1">
                                                <div class="flex justify-between items-start mb-2">
                                                    <div>
                                                        <span class="font-medium text-gray-800">{{ $address->full_name }}</span>
                                                        <span class="ml-2 px-2 py-1 
                                                            @if($address->address_type == 'Home') bg-blue-100 text-blue-800
                                                            @elseif($address->address_type == 'Work') bg-green-100 text-green-800
                                                            @elseif($address->address_type == 'Family') bg-purple-100 text-purple-800
                                                            @else bg-yellow-100 text-yellow-800
                                                            @endif text-xs rounded">
                                                            {{ $address->address_type }}
                                                        </span>
                                                    </div>
                                                    <span class="text-sm text-gray-600">{{ $address->phone }}</span>
                                                </div>
                                                <p class="text-sm text-gray-600">
                                                    {{ $address->address_line1 }}<br>
                                                    @if($address->address_line2)
                                                        {{ $address->address_line2 }}<br>
                                                    @endif
                                                    {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}<br>
                                                    {{ $address->country }}
                                                </p>
                                                @if($address->is_default)
                                                    <div class="mt-2 flex items-center text-sm text-primary">
                                                        <i class="fas fa-star mr-2"></i>
                                                        <span>Default Address</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <div class="p-4 border border-dashed border-gray-300 rounded-lg text-center">
                                        <p class="text-gray-600 mb-3">No addresses found. Please add a delivery address.</p>
                                        <a href="/customer/addresses" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700">
                                            <i class="fas fa-plus mr-2"></i> Add New Address
                                        </a>
                                    </div>
                                @endif

                                <!-- Option to add new address -->
                                <div id="new-address-section" class="hidden">
                                    <div class="p-4 border border-dashed border-primary rounded-lg bg-blue-50">
                                        <h3 class="font-semibold text-gray-800 mb-3">Add New Address</h3>
                                        <div class="space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <select name="address_type" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                                    <option value="Home">Home</option>
                                                    <option value="Work">Work</option>
                                                    <option value="Family">Family</option>
                                                    <option value="Apartment">Apartment</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                                <input type="text" name="full_name" placeholder="Full Name" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                            </div>
                                            <input type="tel" name="phone" placeholder="Phone Number" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                            <textarea name="address_line1" placeholder="Address Line 1" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm"></textarea>
                                            <input type="text" name="address_line2" placeholder="Address Line 2 (Optional)" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                <input type="text" name="city" placeholder="City" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                                <input type="text" name="state" placeholder="State" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                                <input type="text" name="zip_code" placeholder="Zip Code" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                                <input type="text" name="country" placeholder="Country" value="Kuwait" class="border border-gray-300 rounded px-3 py-2 text-sm">
                                            </div>
                                            <div class="flex justify-end space-x-2">
                                                <button type="button" onclick="cancelNewAddress()" class="px-4 py-2 text-sm border border-gray-300 rounded hover:bg-gray-50">Cancel</button>
                                                <button type="button" id="save-new-address" class="px-4 py-2 text-sm bg-primary text-white rounded hover:bg-blue-700">Save Address</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Use different address option -->
                                {{-- @if($addresses->count() > 0) --}}
                                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-300" onclick="showNewAddressSection()">
                                        <input type="radio" name="address_id" value="new" class="text-primary focus:ring-primary">
                                        <div class="ml-3 flex-1">
                                            <span class="font-medium text-gray-800">Use a different address</span>
                                            <p class="text-sm text-gray-600">Add a new delivery address</p>
                                        </div>
                                    </label>
                                {{-- @endif --}}
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Method</h2>
                            <div class="space-y-3">
                                <!-- KNET Payment -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-300">
                                    <input type="radio" name="payment_method" value="knet" class="text-primary focus:ring-primary" checked>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 font-bold text-lg">K</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-800">KNET</span>
                                                <p class="text-sm text-gray-600">Secure online payment through KNET gateway</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Credit/Debit Card Payment -->
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-300" onclick="showCreditCardForm()">
                                    <input type="radio" name="payment_method" value="credit_card" class="text-primary focus:ring-primary">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-credit-card text-gray-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-800">Credit/Debit Card</span>
                                                <p class="text-sm text-gray-600">Pay with Visa, MasterCard, or American Express</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Credit Card Form (Hidden by default) -->
                                <div id="credit-card-form" class="hidden p-4 border border-gray-300 rounded-lg bg-gray-50">
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
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 pl-12 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
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
                                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
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
                                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
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
                                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
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
                                <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:border-primary transition-colors duration-300" onclick="hideCreditCardForm()">
                                    <input type="radio" name="payment_method" value="cod" class="text-primary focus:ring-primary">
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-money-bill text-green-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-800">Cash on Delivery</span>
                                                <p class="text-sm text-gray-600">Pay when you receive your order</p>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Billing Information -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Billing Information</h2>
                            <div class="flex items-center mb-4">
                                <input type="checkbox" id="sameAsShipping" class="text-primary focus:ring-primary" checked>
                                <label for="sameAsShipping" class="ml-2 text-sm text-gray-600">
                                    Billing address is same as shipping address
                                </label>
                            </div>
                            <div id="billing-address-section" class="hidden">
                                <!-- Billing form fields would go here -->
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address *
                                    </label>
                                    <input 
                                        type="email" 
                                        name="email"
                                        id="email"
                                        required
                                        value="{{ Auth::user()->email }}"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
                                    >
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number *
                                    </label>
                                    @if($addresses->count() > 0)
                                        @php
                                            $defaultAddress = $addresses->where('is_default', 1)->first() ?? $addresses->first();
                                        @endphp
                                        <input 
                                            type="tel" 
                                            name="phone"
                                            id="phone"
                                            required
                                            value="{{ $defaultAddress->phone }}"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
                                        >
                                    @else
                                        <input 
                                            type="tel" 
                                            name="phone"
                                            id="phone"
                                            required
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
                                        >
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Order Notes (Optional)</h2>
                            <textarea 
                                name="order_notes"
                                id="orderNotes"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-colors duration-300"
                                placeholder="Any special instructions for your order..."
                            ></textarea>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="mb-8">
                            <div class="flex items-start">
                                <input type="checkbox" id="terms" name="terms" required class="text-primary focus:ring-primary mt-1">
                                <label for="terms" class="ml-2 text-sm text-gray-600">
                                    I agree to the <a href="#" class="text-primary hover:underline">Terms & Conditions</a> and <a href="" class="text-primary hover:underline">Privacy Policy</a>. I understand that my personal data will be processed in accordance with the Privacy Policy.
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                            <a href="/cart" class="text-primary hover:text-blue-700 font-semibold transition-colors duration-300 flex items-center justify-center sm:justify-start space-x-2 w-full sm:w-auto">
                                <i class="fas fa-arrow-left"></i>
                                <span>Return to Cart</span>
                            </a>
                            <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors duration-300 flex items-center justify-center space-x-2 w-full sm:w-auto">
                                <i class="fas fa-lock"></i>
                                <span>Pay Rs. {{ number_format($total, 2) }}</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="lg:w-1/3">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-24">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h2>
                        
                        <!-- Order Items -->
                        <div class="space-y-4 mb-6 max-h-80 overflow-y-auto">
                            @if($cartItems->count() > 0)
                                @foreach($cartItems as $item)
                                    @php($item->selling_price *= 1.17)
                                    <div class="flex items-center space-x-3">
                                        <div class="w-16 h-16 bg-gray100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                            @if($item->primary_image)
                                                <img src="{{ asset('storage/vendor/products/images/' . $item->primary_image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-2xl">📦</span>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-center my-1">
                                                <h3 class="font-bold text-gray-800 text-sm truncate">{{ $item->name }}</h3>
                                                <span class="font-bold text-gray-800 text-sm">Rs. {{ number_format($item->selling_price * $item->cq, 2) }}</span>
                                            </div>
                                            
                                            <p class="text-gray-600 text-xs">Brand: {{ $item->brand ?? 'N/A' }}</p>
                                            <p class="text-gray-600 text-xs">Model: {{ $item->model ?? 'N/A' }}</p>
                                            <p class="text-gray-600 text-xs">Condition: {{ $item->pcondition ?? 'N/A' }}</p>
                                            <p class="text-gray-600 text-xs">Quantity: {{ $item->cq ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <p class="text-gray-600">Your cart is empty</p>
                                    <a href="/" class="text-primary hover:underline text-sm">Continue Shopping</a>
                                </div>
                            @endif
                        </div>

                        <!-- Summary Details -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} items)</span>
                                <span class="text-gray-800">Rs. {{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Shipping</span>
                                <span class="text-gray-800">Rs. {{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Tax (5%)</span>
                                <span class="text-gray-800">Rs. {{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Discount</span>
                                <span class="text-green-600">-Rs. {{ number_format($discount, 2) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between text-lg font-semibold">
                                    <span class="text-gray-800">Total</span>
                                    <span class="text-gray-800">Rs. {{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Security Assurance -->
                        <div class="mt-4 text-center">
                            <div class="flex justify-center space-x-4 text-gray-500 text-xs">
                                <div class="flex items-center">
                                    <i class="fas fa-lock mr-1"></i>
                                    <span>SSL Secure</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt mr-1"></i>
                                    <span>256-bit Encryption</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Support Section -->
                    <div class="bg-white rounded-xl shadow-sm p-6 mt-6">
                        <h3 class="font-semibold text-gray-800 mb-3">Need Help?</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-phone mr-3 text-primary"></i>
                                <span>+965 1234 5678</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-envelope mr-3 text-primary"></i>
                                <span>support@alkuwait.com</span>
                            </div>
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock mr-3 text-primary"></i>
                                <span>24/7 Customer Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust Badges -->
    <section class="py-8 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="flex flex-col items-center">
                    <i class="fas fa-shield-alt text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Secure Payment</h3>
                    <p class="text-gray-600 text-xs">256-bit SSL encryption</p>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-undo text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Easy Returns</h3>
                    <p class="text-gray-600 text-xs">14-day return policy</p>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-truck text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">Free Shipping</h3>
                    <p class="text-gray-600 text-xs">On orders over Rs. 10</p>
                </div>
                <div class="flex flex-col items-center">
                    <i class="fas fa-headset text-2xl text-primary mb-2"></i>
                    <h3 class="font-semibold text-gray-800 text-sm">24/7 Support</h3>
                    <p class="text-gray-600 text-xs">Dedicated customer care</p>
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
                <a href="/customer/orders" class="flex-1 px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 text-center">
                    Track Order
                </a>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/search.js') }}"></script>
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