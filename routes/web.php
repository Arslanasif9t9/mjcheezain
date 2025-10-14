<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/brands/cosmetics', [ProductController::class, 'cosmetics'])->name('brands.cosmetics');

// Authentication routes
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Search route
Route::get('/search', [ProductController::class, 'search'])->name('products.search');