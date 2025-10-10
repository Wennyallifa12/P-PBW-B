<?php

use Illuminate\Support\Facades\Route;

Route::get('/welcome', function () {
    return view('Welcome');
});

Route::get('/tentang-kita', function () {
    return view('About');
});

Route::get('/kontak-kita', function () {
    return view('Kontak');
});