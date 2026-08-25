<?php

use App\Http\Controllers\Login;
use App\Http\Controllers\SignUp;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/login', [Login::class, 'show'])->name('login');
Route::post('/attemptlogin', [Login::class, 'attemptLogin'])->name('attemptLogin');
Route::get('/cadastrar', [SignUp::class, 'show'])->name('register');
Route::post('/attemptsignup', [SignUp::class, 'attemptSignUp'])->name('attemptSignUp');
