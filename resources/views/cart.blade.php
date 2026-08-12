@extends('layouts.structure')
@section('title', 'Cart')
@section('style')
@endsection

@section('body')
    <x-cosmetics.header :user="$user ?? null" :vendor="$vendor ?? null" :profile="$profile ?? null" :dashboardPage="$dashboardPage ?? null" :imgPath="$imgPath ?? null" />

    <div id="main" class="mx-auto py-6 sm:py-10 px-2 sm:px-6 lg:px-8" style="max-width: 100rem;">
        <div class="bg-white p-4 sm:p-6 rounded-2xl shadow-md relative border-t-4" style="border-image: linear-gradient(to right, #FF7DA0, #FFC275) 1;">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Cart</h1>
                <button id="clearCartBtn" class="text-sm text-red-500 hover:text-red-700 font-medium">Clear Cart List</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items Section -->
                <div class="lg:col-span-2">
                    <div id="cartContainer" class="space-y-6">
                        <!-- Cart items will be loaded here dynamically -->
                        <div class="text-center py-8">
                            <div class="text-gray-400 text-6xl mb-4">🛒</div>
                            <p class="text-gray-500 text-lg">Your cart is empty</p>
                        </div>
                    </div>
                </div>

                <!-- Fixed Pricing Section -->
                <div class="lg:col-span-1">
                    <div class="sticky top-28 bg-gray-50 p-6 rounded-lg shadow-inner border border-gray-200">
                        <h2 class="text-xl font-bold mb-4 border-b pb-3 text-gray-800">Pricing & Shipping Fee</h2>

                        <div class="space-y-3 text-gray-600 mb-4">
                            <div class="flex justify-between">
                                <span class="font-medium">Subtotal</span>
                                <span id="subtotal" class="font-semibold">Rs. 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Shipping Fee</span>
                                <span id="shippingFee" class="font-semibold">Rs. 0</span>
                            </div>
                        </div>

                        <div class="border-t border-gray-300 pt-4 mt-4">
                            <div class="flex justify-between text-xl sm:text-2xl font-bold text-gray-900">
                                <span>Total</span>
                                <span id="totalAmount" class="text-umart-blue">Rs. 0</span>
                            </div>
                        </div>

                        @php
                            // Admin Controls: WhatsApp-only ordering swaps this button's
                            // job, so it says what it actually does.
                            $cartWaOn = ($whatsappBuyNow['enabled'] ?? false) && ($whatsappBuyNow['number'] ?? '') !== '';
                        @endphp
                        <button id="checkoutBtn" class="w-full mt-6 text-white py-3 rounded-full font-bold text-base sm:text-lg tracking-wide transition duration-300 shadow-md hover:shadow-xl hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 disabled:hover:shadow-md flex items-center justify-center gap-2"
                            @if($cartWaOn)
                                style="background:#25D366; box-shadow: 0 6px 18px rgba(37,211,102,.3);"
                            @else
                                style="background: linear-gradient(115deg, #FF7DA0 0%, #FFC275 100%); box-shadow: 0 6px 18px rgba(255, 125, 160, 0.3);"
                            @endif
                            disabled>
                            @if($cartWaOn)
                                <i class="fa-brands fa-whatsapp text-xl"></i> ORDER ON WHATSAPP
                            @else
                                PROCEED TO CHECKOUT
                            @endif
                        </button>
                        
                        <div class="mt-4 text-center">
                            <a href="/" class="inline-block text-umart-blue hover:text-umart-blue/80 font-medium text-sm">
                                ← Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-footer />
    </div>

    <script src="{{ asset('js/search.js') }}?v={{ @filemtime(public_path('js/search.js')) ?: 1 }}"></script>
    <script>
        // Store cart state in memory for quick updates
        let cartState = {
            items: [],
            totals: {
                subtotal: 0,
                shipping_fee: 0,
                total: 0
            }
        };

        // Load cart items on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadCartItems();
        });

        // Function to load cart items from server
        async function loadCartItems() {
            try {
                const response = await fetch('/cart/items');
                const data = await response.json();
                
                if (data.success) {
                    console.log(cartState)
                    cartState.items = data.items;
                    cartState.totals = data.totals;
                    
                    if (data.items.length > 0) {
                        renderCartItems();
                    } else {
                        showEmptyCart();
                    }
                } else {
                    showEmptyCart();
                }
            } catch (error) {
                console.error('Error loading cart items:', error);
                showEmptyCart();
            }
        }

        // Function to render cart items from state
        function renderCartItems() {
            const cartContainer = document.getElementById('cartContainer');
            updatePricingSummary();

            if (cartState.items.length === 0) {
                showEmptyCart();
                return;
            }

            // Generate cart items HTML
            let cartItemsHTML = '';
            
            cartState.items.forEach(item => {
                const itemTotal = (item.price * item.quantity).toFixed(2);
                cartItemsHTML += `
                    <div class="flex items-start border-b border-pink-100 pb-4 hover:bg-pink-50/40 p-2 rounded-xl transition duration-200 gap-3 sm:gap-5" data-cart-id="${item.id}">
                        <!-- Left: bigger image with quantity control BELOW it -->
                        <div class="flex-shrink-0 flex flex-col items-center gap-2.5">
                            <div class="w-28 h-28 sm:w-36 sm:h-36 bg-gray-50 border border-pink-100 rounded-xl flex items-center justify-center overflow-hidden shadow-sm">
                                ${item.image_path ?
                                    `<img src="/storage/vendor/products/images/${item.image_path}" alt="${item.product_name}" class="w-full h-full object-cover hover:scale-105 transition duration-300">` :
                                    '<span class="text-gray-400 text-xs">No Image</span>'
                                }
                            </div>
                            <div class="flex items-center bg-white border border-pink-200 rounded-full shadow-sm overflow-hidden">
                                <button onclick="updateQuantityImmediately(${item.id}, -1)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-pink-50 hover:text-umart-blue font-bold transition duration-200 quantity-decrease-${item.id} ${item.quantity <= 1 ? 'opacity-50 cursor-not-allowed' : ''}">&minus;</button>
                                <span class="px-3 py-1 border-x border-pink-100 font-semibold text-sm quantity-display-${item.id}">${item.quantity}</span>
                                <button onclick="updateQuantityImmediately(${item.id}, 1)" class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-pink-50 hover:text-umart-blue font-bold transition duration-200 quantity-increase-${item.id}">+</button>
                            </div>
                        </div>
                        <!-- Right: product details -->
                        <div class="flex-grow min-w-0">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-800 hover:text-umart-blue transition duration-200 line-clamp-2">${item.product_name}</h2>
                            <p class="text-sm text-gray-500 mt-1">Price: <span class="font-semibold text-gray-800">Rs. ${item.price}</span></p>
                            <div class="flex items-center mt-2">
                                <div class="flex text-star-yellow text-sm">
                                    ${generateStarRating(item.rating || 4)}
                                </div>
                                <span class="text-xs text-gray-500 ml-2">(${item.review_count || 0} Reviews)</span>
                            </div>
                            <div class="flex items-center justify-between mt-3 sm:mt-4">
                                <div class="text-lg sm:text-xl font-bold item-total-${item.id}" style="color: #E85D85;">
                                    Rs. ${itemTotal}
                                </div>
                                <button onclick="removeItemImmediately(${item.id})" class="text-gray-400 hover:text-red-500 transition duration-200 p-1.5 rounded-full hover:bg-red-50" title="Remove Item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            cartContainer.innerHTML = cartItemsHTML;
        }

        // Function to update pricing summary from state
        function updatePricingSummary() {
            const subtotalElement = document.getElementById('subtotal');
            const shippingFeeElement = document.getElementById('shippingFee');
            const totalAmountElement = document.getElementById('totalAmount');
            const checkoutBtn = document.getElementById('checkoutBtn');

            if (cartState.items.length === 0) {
                subtotalElement.textContent = 'Rs. 0';
                shippingFeeElement.textContent = 'Rs. 0';
                totalAmountElement.textContent = 'Rs. 0';
                checkoutBtn.disabled = true;
            } else {
                subtotalElement.textContent = `Rs. ${cartState.totals.subtotal.toFixed(2)}`;
                shippingFeeElement.textContent = `Rs. ${cartState.totals.shipping_fee.toFixed(2)}`;
                totalAmountElement.textContent = `Rs. ${cartState.totals.total.toFixed(2)}`;
                checkoutBtn.disabled = false;
            }
        }

        // Function to calculate totals from items
        function calculateTotalsFromState() {
            const subtotal = cartState.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            
            // Calculate shipping fee (example: $5 per item, max $50)
            const shippingFee = Math.min(cartState.items.length * 5, 50);
            
            cartState.totals = {
                subtotal: subtotal,
                shipping_fee: shippingFee,
                total: subtotal + shippingFee
            };
        }

        // Function to generate star rating HTML
        function generateStarRating(rating) {
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= rating) {
                    stars += '<span>★</span>';
                } else {
                    stars += '<span class="text-gray-300">★</span>';
                }
            }
            return stars;
        }

        // Function to show empty cart
        function showEmptyCart() {
            const cartContainer = document.getElementById('cartContainer');
            
            cartContainer.innerHTML = `
                <div class="text-center py-12">
                    <div class="text-gray-300 text-8xl mb-6">🛒</div>
                    <p class="text-gray-500 text-xl font-medium mb-4">Your cart is empty</p>
                    <p class="text-gray-400 mb-8">Add some products to your cart</p>
                    <a href="/products/all-page" class="inline-block bg-umart-blue hover:bg-umart-blue/90 text-white px-8 py-3 rounded-lg font-semibold text-lg transition duration-300 shadow-md hover:shadow-lg">
                        Continue Shopping
                    </a>
                </div>
            `;
            
            updatePricingSummary();
        }

        // Function to remove item immediately from UI
        function removeItemImmediately(cartId) {
            if (!confirm('Are you sure you want to remove this item from cart?')) {
                return;
            }

            // Remove from local state immediately
            const itemIndex = cartState.items.findIndex(item => item.id == cartId);
            if (itemIndex !== -1) {
                cartState.items.splice(itemIndex, 1);
                calculateTotalsFromState();
                
                // Update UI immediately
                const itemElement = document.querySelector(`[data-cart-id="${cartId}"]`);
                if (itemElement) {
                    itemElement.style.opacity = '0.5';
                    itemElement.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        if (cartState.items.length === 0) {
                            showEmptyCart();
                        } else {
                            renderCartItems();
                        }
                    }, 300);
                }
                
                // Show notification
                showNotification('Item removed from cart', 'success');
                
                // Update database in background (no need to wait)
                updateDatabaseInBackground(() => removeFromCartDB(cartId));
            }
        }

        // Function to update quantity immediately in UI
        function updateQuantityImmediately(cartId, change) {
            const item = cartState.items.find(item => item.id == cartId);
            if (!item) return;

            const newQuantity = item.quantity + change;
            
            // Validate minimum quantity
            if (newQuantity < 1) {
                removeItemImmediately(cartId);
                return;
            }
            
            // Validate maximum quantity (optional - you can set a limit)
            if (newQuantity > 99) {
                showNotification('Maximum quantity reached', 'error');
                return;
            }

            // Update local state immediately
            item.quantity = newQuantity;
            calculateTotalsFromState();
            
            // Update UI immediately
            const quantityDisplay = document.querySelector(`.quantity-display-${cartId}`);
            const decreaseBtn = document.querySelector(`.quantity-decrease-${cartId}`);
            const itemTotal = document.querySelector(`.item-total-${cartId}`);
            
            if (quantityDisplay) {
                // Add visual feedback
                quantityDisplay.classList.add('scale-110', 'text-umart-blue');
                setTimeout(() => {
                    quantityDisplay.classList.remove('scale-110', 'text-umart-blue');
                }, 300);
                
                quantityDisplay.textContent = newQuantity;
                
                // Update disable state for decrease button
                if (decreaseBtn) {
                    if (newQuantity <= 1) {
                        decreaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        decreaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
                
                // Update item total
                if (itemTotal) {
                    const newTotal = (item.price * newQuantity).toFixed(2);
                    itemTotal.textContent = `Rs. ${newTotal}`;
                    itemTotal.classList.add('text-green-600');
                    setTimeout(() => {
                        itemTotal.classList.remove('text-green-600');
                    }, 500);
                }
                
                // Update pricing summary
                updatePricingSummary();
                
                // Show notification for large changes
                if (Math.abs(change) > 1) {
                    showNotification(`Quantity updated to ${newQuantity}`, 'success');
                }
            }
            
            // Update database in background (no need to wait)
            updateDatabaseInBackground(() => updateQuantityInDB(cartId, newQuantity));
        }

        // Function to clear entire cart immediately
        document.getElementById('clearCartBtn').addEventListener('click', async function() {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                return;
            }

            // Clear local state immediately
            cartState.items = [];
            calculateTotalsFromState();
            
            // Update UI immediately with animation
            const cartContainer = document.getElementById('cartContainer');
            cartContainer.style.opacity = '0.5';
            cartContainer.style.transform = 'scale(0.95)';
            
            setTimeout(() => {
                showEmptyCart();
                cartContainer.style.opacity = '1';
                cartContainer.style.transform = 'scale(1)';
            }, 300);
            
            showNotification('Cart cleared successfully', 'success');
            
            // Update database in background
            updateDatabaseInBackground(() => clearCartInDB());
        });

        // Background database operations (run without blocking UI)
        async function updateDatabaseInBackground(operation) {
            // Run operation in background
            setTimeout(async () => {
                try {
                    await operation();
                } catch (error) {
                    console.error('Background database update failed:', error);
                    // Optional: Show error notification or retry logic
                }
            }, 100); // Small delay to ensure UI updates first
        }

        async function removeFromCartDB(cartId) {
            try {
                await fetch(`/cart/remove/${cartId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Failed to remove from DB:', error);
            }
        }

        async function updateQuantityInDB(cartId, quantity) {
            try {
                await fetch(`/cart/update/${cartId}`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ quantity: quantity })
                });
            } catch (error) {
                console.error('Failed to update quantity in DB:', error);
            }
        }

        async function clearCartInDB() {
            try {
                await fetch('/cart/clear', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Failed to clear cart in DB:', error);
            }
        }

        // Checkout button handler — guests get the auth popup instead of the checkout page
        const isLoggedIn = @json(auth()->check());
        const CART_WHATSAPP = @json($cartWaOn ? ($whatsappBuyNow['number'] ?? '') : '');
        const CUSTOMER_LOGIN_OPEN = @json($siteAccess['customer_login'] ?? true);

        // Builds the WhatsApp order text from what the shopper is actually
        // looking at, line by line.
        function buildWhatsappOrder() {
            const items = (cartState.items || []);
            const lines = items.map(i => {
                const qty = Number(i.quantity) || 1;
                const unit = Number(i.price) || 0;
                return `- ${i.product_name} (x${qty}) — Rs. ${(unit * qty).toLocaleString()}`;
            });
            const total = items.reduce((s, i) => s + (Number(i.price) || 0) * (Number(i.quantity) || 1), 0);

            return 'Hi! I want to order:\n\n'
                + (lines.length ? lines.join('\n') : 'My cart items')
                + `\n\nTotal: Rs. ${total.toLocaleString()}`
                + `\n\n${window.location.origin}/cart`;
        }

        document.getElementById('checkoutBtn').addEventListener('click', function() {
            // WhatsApp mode comes FIRST — guests must be able to order too, and
            // the cart already works without an account.
            if (CART_WHATSAPP) {
                window.open('https://wa.me/' + CART_WHATSAPP + '?text=' + encodeURIComponent(buildWhatsappOrder()), '_blank');
                return;
            }

            if (!isLoggedIn) {
                // Sign-in switched off? Then there's no login page to send them to.
                if (!CUSTOMER_LOGIN_OPEN) {
                    showNotification('Ordering is currently unavailable. Please contact us.', 'error');
                    return;
                }
                const loginUrl = '/login-user?type=customer-login&page=checkout';
                if (typeof window.apOpen === 'function') {
                    window.apOpen(loginUrl);
                } else {
                    window.location.href = loginUrl;
                }
                return;
            }
            // Sync any pending changes before checkout
            syncCartToDatabase().then(() => {
                window.location.href = '/checkout';
            });
        });

        // Sync cart state to database before leaving page
        async function syncCartToDatabase() {
            // This ensures all background updates are complete
            // You can add a loading indicator here if needed
            return Promise.resolve(); // For now, just return resolved promise
        }

        // Notification function
        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;
            notification.textContent = message;
            notification.style.transform = 'translateX(100%)';
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Prevent accidental page leave if there are pending updates
        window.addEventListener('beforeunload', function(e) {
            // Optional: You can add logic here to warn user if there are unsaved changes
            // For now, we're just syncing in background, so no warning needed
        });
    </script>
@endsection