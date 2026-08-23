<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::view('/login', 'login')->name('login');
Route::post('/login', function () {
    dd('oi');
})->name('login.store');
