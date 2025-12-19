<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\VendorReplacementController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\VendorReturnController;
// Add this route
Route::get('/api/search', [SearchController::class, 'searchProducts']);
Route::view('/comming', 'comming-soon');
Route::post('/subscribe', [HomeController::class, 'subscribe']);

Route::get('/test-mail', function() {
    try {
        $email = "arslanahmadt58@gmail.com";
        // Send OTP via email
            Mail::send('emails.otp', ['otp' => '1234'], function($message) use ($email) {
                $message->to($email)
                        ->subject('Your OTP Code');
            });
            return "Email sent successfully!";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});


Route::view('createDB', 'mydatabase/creation');


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('cosmetics', [HomeController::class, 'cosmetics']);
Route::get('/product-listing', [HomeController::class, 'productList']);

Route::view('/login-user', 'home/login');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::view('/vendor-forgot-password', 'home/forgot');
// Forgot Password Routes
Route::post('/send-password-reset-otp', [AuthController::class, 'sendPasswordResetOtp']);
Route::post('/verify-password-reset-otp', [AuthController::class, 'verifyPasswordResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::get('/product/{id}', [HomeController::class, 'product']);
Route::get('/vendor-products/{id}', [HomeController::class, 'vendorProducts']);

Route::view('/about', 'footer/about');
Route::view('/future-vision', 'footer/future-vision');
Route::view('/contact-us', 'footer/contact-us');
Route::view('/FAQs', 'footer/FAQs');
Route::view('/vendor-zone','footer/vendor-zone');
Route::view('/legal-policies', 'footer/legal-policies');
Route::view('/privacy-policy', 'footer/privacy-policy');
Route::view('/cookie-policy', 'footer/privacy-policy');
Route::view('/disclaimer', 'footer/privacy-policy');






// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::get('/login', function () {
    return redirect('/');
});
Route::get('/signup', function () {
    return redirect('/');
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Search route
// Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/search-products', [SearchController::class, 'searchProducts'])->name('search.products');

Route::get('/products/savings', [ProductController::class, 'biggestSavings']);
Route::post('/products/category', [ProductController::class, 'byCategory']);



Route::middleware(['auth'])->group(function () {
    // ----------------------------
    // 🟢 CUSTOMER ROUTES
    // ----------------------------
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/get_orders', [OrderController::class, 'getOrders']);
        Route::get('/profile', [CustomerController::class, 'profile'])->name('cprofile');
        Route::get('/profile/edit', [CustomerController::class, 'editProfile']);
        Route::post('/profile/save', [CustomerController::class, 'saveProfile']);
        Route::get('/addresses', [CustomerController::class, 'addresses']);
        Route::get('/wishlist', [CustomerController::class, 'wishlist']);
        // Add other customer routes here
        Example: Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    });
    
    // ----------------------------
    // 🔵 VENDOR ROUTES
    // ----------------------------
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [VendorController::class, 'products'])->name('products');
        // Route::get('/orders', [VendorController::class, 'orders'])->name('orders');
        Route::get('/withdraw', [VendorController::class, 'withdraw'])->name('withdraw');
        Route::get('/profile', [VendorController::class, 'profile'])->name('profile');
        Route::get('/profile-edit', [VendorController::class, 'profileEdit'])->name('profile.edit');

        Route::post('/products', [VendorController::class, 'products'])->name('products');
        Route::get('/products/create', [VendorController::class, 'productsCreate'])->name('products.create');
        Route::post('/products/store', [VendorController::class, 'storeProduct'])->name('products.store');
        Route::delete('/products/delete', [VendorController::class, 'deleteProduct'])->name('products.delete');
        Route::get('/products/edit/{id}', [VendorController::class, 'productsCreate'])->name('products.edit');
        Route::put('/products/update/{id}', [VendorController::class, 'updateProduct'])->name('products.update');
        Route::post('/products/id', [VendorController::class, 'pr'])->name('products.index');
        
        Route::post('/profile-edit/basic-info', [VendorController::class, 'updateBasicInfo'])->name('basic.update');
        Route::post('/profile-edit/store-detail', [VendorController::class, 'updateStoreDetail'])->name('store.update');
        Route::post('/profile-edit/address', [VendorController::class, 'updateAddress'])->name('address.update');

        Route::get('/orders', [VendorController::class, 'orders'])->name('orders');
        Route::post('/orders/update-status', [VendorController::class, 'updateOrderStatus'])->name('orders.update-status');
    });

});

Route::middleware(['auth'])->group(function () {
    Route::prefix('customer')->group(function () {
        Route::get('/notifications', [CustomerController::class, 'notifications'])->name('customer.notifications');
        Route::post('/notifications/{id}/read', [CustomerController::class, 'markAsRead'])->name('customer.notifications.read');
    });
    Route::prefix('vendor')->group(function () {
        Route::get('/notifications', [VendorController::class, 'notifications'])->name('vendor.notifications');
        Route::post('/notifications/{id}/read', [VendorController::class, 'markAsRead'])->name('vendor.notifications.read');
    });
});





use App\Http\Controllers\BalanceController;

// Vendor routes (assuming you have auth middleware)
Route::middleware(['auth'])->group(function () {
    // Withdrawal routes
    Route::get('/vendor/withdraw', [BalanceController::class, 'showWithdrawalPage'])
         ->name('vendor.withdraw');
    
    Route::post('/vendor/withdraw/process', [BalanceController::class, 'processWithdrawal'])
         ->name('vendor.withdraw.process');
    
    Route::get('/vendor/balance/details', [BalanceController::class, 'showBalanceDetails'])
         ->name('vendor.balance.details');
});







// Vendor Return Routes
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/returns', [VendorReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{id}', [VendorReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/update-status', [VendorReturnController::class, 'updateStatus'])->name('returns.update-status');
    Route::get('/returns/filter/{status}', [VendorReturnController::class, 'filter'])->name('returns.filter');
});

// Vendor Replacement Routes
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/replacements', [VendorReplacementController::class, 'index'])->name('replacements.index');
    Route::get('/replacements/{id}', [VendorReplacementController::class, 'show'])->name('replacements.show');
    Route::get('/replacements/filter/{status}', [VendorReplacementController::class, 'filter'])->name('replacements.filter');
    Route::post('/replacements/update-status', [VendorReplacementController::class, 'updateStatus'])->name('replacements.update-status');
});

// Customer Return Routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/create/{orderId}/{cartId}', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns', [ReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns/track/{returnId}', [ReturnController::class, 'track'])->name('returns.track');
    Route::post('/returns/cancel/{returnId}', [ReturnController::class, 'cancel'])->name('returns.cancel');
});


// Product rating routes
Route::middleware(['auth'])->group(function () {
    Route::post('/rate-product', [ProductRatingController::class, 'rateProduct']);
    Route::get('/get-rating/{productId}/{orderId}', [ProductRatingController::class, 'getRating']);
    Route::post('/submit-replace-request', [ProductRatingController::class, 'submitReplaceRequest']);
    Route::post('/initiate-return', [ProductRatingController::class, 'initiateReturn']);
    Route::post('/cancel-order', [ProductRatingController::class, 'cancelOrder']);

    // Replacement tracking routes
    Route::get('/get-replacement-tracking/{replacementId}', [ProductRatingController::class, 'getReplacementTracking']);
    Route::post('/mark-replacement-shipped/{replacementId}', [ProductRatingController::class, 'markReplacementShipped']);
    Route::post('/cancel-replacement/{replacementId}', [ProductRatingController::class, 'cancelReplacement']);
});



Route::post('/customer/address/save', [AddressController::class, 'saveAddress']);
Route::get('/customer/address/get/{id}', [AddressController::class, 'getAddress']);
Route::get('/customer/address/set-default/{id}', [AddressController::class, 'setDefault'])->name('addresses.set_default');
Route::get('/customer/address/delete/{id}', [AddressController::class, 'delete'])->name('addresses.delete');

Route::post('/favorites/toggle', [FavoriteController::class, 'toggleFavorite']);
Route::get('/favorites/check/{product_id}', [FavoriteController::class, 'checkFavorite']);
Route::get('/wishlist/get', [FavoriteController::class, 'getWishlist']);

Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/summary', [CartController::class, 'getCartSummary'])->name('cart.summary');
Route::get('/cart', [CartController::class, 'indexPage']);
Route::get('/cart/items', [CartController::class, 'getCartItems'])->name('cart.items');
Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::put('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

Route::get('/product/{id}/buy/{q}', [CartController::class, 'buy']);


Route::get('/checkout', [CheckoutController::class, 'checkout']);
Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');




Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'loginForm']);
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard']);
    Route::get('/sales-data', [SalesController::class, 'getSalesData']);

    Route::get('/vendors', [AdminAuthController::class, 'vendors']);
    Route::post('/admin/vendor/status', [VendorController::class, 'updateStatus'])->name('admin.vendor.status');
    Route::get('/vendor/details/{user_id}', [VendorController::class, 'getVendorDetails']);

    Route::get('/products', [AdminAuthController::class, 'products']);
    Route::post('/change-product-position', [ProductController::class, 'changeProductPosition']);
    Route::post('/delete-product', [ProductController::class, 'deleteProduct']);
});