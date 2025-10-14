<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/in', function () {
    return view('index');
});

// Route::view('/in','index');