<?php

declare(strict_types=1);

use App\Http\Controllers\Login;
use App\Http\Controllers\Logout;
use App\Http\Controllers\SignUp;
use App\Livewire\AddGame;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/login', [Login::class, 'show'])->name('login');
Route::post('/attemptlogin', [Login::class, 'attemptLogin'])->name('attemptLogin');
Route::get('/cadastrar', [SignUp::class, 'show'])->name('register');
Route::post('/attemptsignup', [SignUp::class, 'attemptSignUp'])->name('attemptSignUp');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/jogos/adicionar', AddGame::class)->name('games.create');
    Route::post('/logout', [Logout::class, 'logout'])->name('logout');
});
