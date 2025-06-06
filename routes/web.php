<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signup;
use App\Http\Middleware\loginCheck;


// Route::get('/register', function () {
//     return view('register');
// });


// Route::get('/login', function() {
//     return view('login');
// });

Route::get('/register', [Signup::class, 'showRegister'])->name('register');
Route::post('/register', [Signup::class, 'register']);

Route::get('/login', [Signup::class, 'showLogin'])->name('login');
Route::post('/login', [Signup::class, 'login']);

Route::get('/dashboard', function () {
    return view('dashboard', ['user' => session('user')]);
})->name('dashboard')->middleware(loginCheck::class);

Route::get('/logout', [Signup::class, 'logout'])->name('logout');
// Route::view('/welcome', 'greet');
// Route::get('/', [signup::class, 'index'])->name('register');
// Route::post('/', [signup::class, 'index']);

Route::fallback(function () {
    return "<h1> Page Not Found</h1>"; 
});
