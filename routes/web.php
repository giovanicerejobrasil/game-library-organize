<?php

use App\Http\Controllers\Login;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/login', [Login::class, 'show'])->name('login');
Route::post('/attemptlogin', [Login::class, 'attemptLogin'])->name('attemptLogin');
