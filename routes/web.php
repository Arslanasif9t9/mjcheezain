<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorController;




// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/brands/cosmetics', [ProductController::class, 'cosmetics'])->name('brands.cosmetics');

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Search route
// Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/search-products', [SearchController::class, 'searchProducts'])->name('search.products');

Route::get('/products/savings', [ProductController::class, 'biggestSavings']);
Route::get('/products/category/{category}', [ProductController::class, 'byCategory']);

Route::middleware(['auth'])->group(function () {
    Route::get('/customer/dashboard', [CustomerController::class, 'dashboard'])
        ->name('customer.dashboard');
    Route::get('/vendor/dashboard', [VendorController::class, 'dashboard'])
        ->name('vendor.dashboard');
});