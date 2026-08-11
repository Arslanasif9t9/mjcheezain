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
use App\Http\Controllers\ProfileImageController;
use App\Http\Controllers\Vendor\AutoPartsProductController;
use App\Http\Controllers\Vendor\AutoPartsProductShowController;
use App\Http\Controllers\MjGuideController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminOpsController;
use App\Http\Controllers\JapanController;
use App\Http\Controllers\JapanAdminController;
use App\Http\Controllers\AdminControlsController;

Route::prefix('japan')->name('japan.')->group(function () {
    Route::get('/', [JapanController::class, 'index'])->name('index');
    Route::get('/api/products', [JapanController::class, 'apiProducts'])->name('api.products');
    Route::get('/api/filters', [JapanController::class, 'apiFilters'])->name('api.filters');
});
Route::get('/japan/{id}', [JapanController::class, 'show'])->name('japan.show');

// Japan products — small standalone admin CRUD panel (separate session from /admin)
Route::prefix('japanadmin')->group(function () {
    Route::get('/login', [JapanAdminController::class, 'loginForm']);
    Route::post('/login', [JapanAdminController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/logout', [JapanAdminController::class, 'logout']);
});
Route::prefix('japanadmin')->middleware('japanadmin.auth')->group(function () {
    Route::get('/products', [JapanAdminController::class, 'index']);
    Route::post('/products', [JapanAdminController::class, 'store']);
    Route::post('/products/{id}/update', [JapanAdminController::class, 'update']);
    Route::post('/products/{id}/delete', [JapanAdminController::class, 'destroy']);
});

// Auto Parts Public Routes
Route::prefix('auto-parts')->name('auto-parts.')->group(function () {
    Route::get('/', [AutoPartsProductShowController::class, 'publicIndex'])->name('index');
    Route::get('/api/products', [AutoPartsProductShowController::class, 'apiProducts'])->name('api.products');
    Route::get('/api/filters', [AutoPartsProductShowController::class, 'apiFilters'])->name('api.filters');
    Route::get('/product/{id}', [AutoPartsProductShowController::class, 'publicShow'])->name('show');
});

Route::get('/auto/{id}', [AutoPartsProductShowController::class, 'singleShow']);


// Auto Parts Products Routes (vendor-only — must be logged in)
Route::middleware(['auth'])->prefix('vendor/products/autoparts')->name('vendor.products.autoparts.')->group(function () {
    Route::get('/create', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'destroy'])->name('destroy');
    Route::get('/index', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'index'])->name('index');
    Route::get('/subcategories/{categoryId}', [App\Http\Controllers\Vendor\AutoPartsProductController::class, 'getSubcategories'])->name('subcategories');
});

// Route::view('/autoparts', 'brands/autopart');

// Add this route
Route::get('/api/search', [SearchController::class, 'searchProducts']);
Route::view('/comming', 'comming-soon');
Route::post('/subscribe', [HomeController::class, 'subscribe']);

// Dev-only utilities: never exposed in production
if (app()->environment('local')) {
    Route::get('/test-mail', function () {
        try {
            $email = "arslanahmadt58@gmail.com";
            // Send OTP via email
            Mail::send('emails.otp', ['otp' => '1234'], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Your OTP Code');
            });
            return "Email sent successfully!";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    });

    Route::view('createDB', 'mydatabase/creation');
}


// Public routes
Route::get('/', function () {
    return view('brands.japan-coming-soon');
})->name('coming-soon');
Route::get('/a456', [HomeController::class, 'index'])->name('home');
Route::get('cosmetics', [HomeController::class, 'cosmetics']);
Route::get('/product-listing', [HomeController::class, 'productList']);
Route::get('/products/all-page', [HomeController::class, 'productList']); // filtered listing page (?category=)

Route::view('/login-user', 'home/login');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::view('/vendor-forgot-password', 'home/forgot');
Route::view('/customer-forgot-password', 'home/forgot');
// Forgot Password Routes
Route::post('/send-password-reset-otp', [AuthController::class, 'sendPasswordResetOtp']);
Route::post('/verify-password-reset-otp', [AuthController::class, 'verifyPasswordResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::get('/product/{id}', [HomeController::class, 'product']);
Route::get('/vendor-products/{id}', [HomeController::class, 'vendorProducts']);

// Footer pages go through the controller so the shared site header
// gets the logged-in user's data (profile image, dashboard link).
Route::get('/{page}', [HomeController::class, 'footerPage'])
    ->where('page', 'about|future-vision|contact-us|FAQs|vendor-zone|legal-policies|privacy-policy|cookie-policy|disclaimer');






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

// MJ Guide chatbot (public — guests, customers, vendors; stateless, history lives in the browser)
Route::post('/mj-guide/message', [MjGuideController::class, 'message'])
    ->middleware('throttle:15,1')
    ->name('mj-guide.message');

// Search route
// Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/search-products', [SearchController::class, 'searchProducts'])->name('search.products');

Route::get('/products/savings', [ProductController::class, 'biggestSavings']);
Route::get('/products/category', [ProductController::class, 'byCategory']);
Route::get('/products/all', [ProductController::class, 'allProducts']);



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
        Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
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

// customer Profile Image Routes
Route::middleware(['auth'])->group(function () {
    // Profile Image Routes
    Route::post('/api/upload-profile-image', [ProfileImageController::class, 'uploadProfileImage']);
    Route::post('/api/remove-profile-image', [ProfileImageController::class, 'removeProfileImage']);
    
    // Banner Image Routes
    Route::post('/api/upload-banner-image', [ProfileImageController::class, 'uploadBannerImage']);
    Route::post('/api/remove-banner-image', [ProfileImageController::class, 'removeBannerImage']);
});


Route::middleware(['auth'])->group(function () {
    Route::prefix('customer')->group(function () {
        Route::get('/notifications', [CustomerController::class, 'notifications'])->name('customer.notifications');
        Route::post('/notifications/read', [CustomerController::class, 'markAsRead'])->name('customer.notifications.read');
        Route::post('/notifications/{id}/read', [CustomerController::class, 'markAsRead'])->name('customer.notifications.read.single');
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
Route::middleware(['auth'])->prefix('vendor')->name('vendor.')->group(function () {
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
    Route::get('/product/{id}/reviews', [HomeController::class, 'loadMoreReviews']);
    Route::post('/mark-replacement-shipped/{replacementId}', [ProductRatingController::class, 'markReplacementShipped']);
    Route::post('/cancel-replacement/{replacementId}', [ProductRatingController::class, 'cancelReplacement']);
});



// Customer addresses — must be logged in (rows are keyed to the user)
Route::middleware(['auth'])->group(function () {
    Route::post('/customer/address/save', [AddressController::class, 'saveAddress']);
    Route::get('/customer/address/get/{id}', [AddressController::class, 'getAddress']);
    Route::get('/customer/address/set-default/{id}', [AddressController::class, 'setDefault'])->name('addresses.set_default');
    Route::get('/customer/address/delete/{id}', [AddressController::class, 'delete'])->name('addresses.delete');
});

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
    Route::post('/login', [AdminAuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

// Every admin page/action beyond login requires the admin session (see AdminAuth middleware)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    Route::get('/dashboard', [AdminAuthController::class, 'dashboard']);
    Route::get('/sales-data', [SalesController::class, 'getSalesData']);

    Route::get('/vendors', [AdminAuthController::class, 'vendors']);
    Route::post('/vendor/status', [VendorController::class, 'updateStatus'])->name('admin.vendor.status');
    Route::get('/vendor/details/{user_id}', [VendorController::class, 'getVendorDetails']);

    Route::get('/products', [AdminAuthController::class, 'products']);
    Route::post('/change-product-position', [ProductController::class, 'changeProductPosition']);
    Route::post('/delete-product', [ProductController::class, 'deleteProduct']);

    // Categories management + vendor suggestions
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories');
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::post('/categories/{id}/update', [AdminCategoryController::class, 'update']);
    Route::post('/categories/{id}/delete', [AdminCategoryController::class, 'destroy']);
    Route::post('/categories/{id}/subcategories', [AdminCategoryController::class, 'storeSub']);
    Route::post('/subcategories/{subId}/delete', [AdminCategoryController::class, 'destroySub']);
    Route::get('/category-suggestions', [AdminAuthController::class, 'categorySuggestions']);
    Route::post('/category-suggestions/{id}/approve', [AdminCategoryController::class, 'approveSuggestion']);
    Route::post('/category-suggestions/{id}/reject', [AdminCategoryController::class, 'rejectSuggestion']);

    // Site-wide controls (e.g. WhatsApp Buy Now)
    Route::get('/controls', [AdminControlsController::class, 'index'])->name('admin.controls');
    Route::post('/controls', [AdminControlsController::class, 'save']);

    // Operations: orders, withdraw requests, customers, returns & replacements oversight
    Route::get('/orders', [AdminOpsController::class, 'orders']);
    Route::post('/orders/update-status', [AdminOpsController::class, 'updateOrderStatus'])->name('admin.orders.status');
    Route::get('/withdraw-requests', [AdminOpsController::class, 'withdrawRequests'])->name('admin.withdraws');
    Route::post('/withdraw-requests/{id}/status', [AdminOpsController::class, 'updateWithdrawStatus']);
    Route::get('/customers', [AdminOpsController::class, 'customers'])->name('admin.customers');
    Route::get('/returns', [AdminOpsController::class, 'returns'])->name('admin.returns');
    Route::post('/returns/{id}/status', [AdminOpsController::class, 'updateReturnStatus']);
    Route::get('/replacements', [AdminOpsController::class, 'replacements'])->name('admin.replacements');
    Route::post('/replacements/{id}/status', [AdminOpsController::class, 'updateReplacementStatus']);
});