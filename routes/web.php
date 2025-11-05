<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;


Route::view('createDB', 'mydatabase/creation');


// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::view('cosmetics', 'brands/cosmetics');

Route::view('/about', 'footer/about');
Route::view('FAQs', 'footer/FAQs');





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
Route::get('/products/category/{category}', [ProductController::class, 'byCategory']);



Route::middleware(['auth'])->group(function () {
    // ----------------------------
    // 🟢 CUSTOMER ROUTES
    // ----------------------------
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        // Add other customer routes here
        // Example: Route::get('/orders', [CustomerController::class, 'orders'])->name('orders');
    });

    // ----------------------------
    // 🔵 VENDOR ROUTES
    // ----------------------------
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [VendorController::class, 'products'])->name('products');
        Route::get('/orders', [VendorController::class, 'orders'])->name('orders');
        Route::get('/withdraw', [VendorController::class, 'withdraw'])->name('withdraw');
        Route::get('/profile', [VendorController::class, 'profile'])->name('profile');
        Route::get('/profile-edit', [VendorController::class, 'profileEdit'])->name('profile.edit');

        Route::post('/products', [VendorController::class, 'products'])->name('products');
        Route::post('/products/create', [VendorController::class, 'productsCreate'])->name('products.create');
        Route::post('/products/id', [VendorController::class, 'pr'])->name('products.index');
        
        Route::post('/profile-edit/basic-info', [VendorController::class, 'updateBasicInfo'])->name('basic.update');
        Route::post('/profile-edit/store-detail', [VendorController::class, 'updateStoreDetail'])->name('store.update');
        Route::post('/profile-edit/address', [VendorController::class, 'updateAddress'])->name('address.update');
    });

});