<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController as Register;
use App\Http\Controllers\Auth\LoginController as Login;
use App\Http\Controllers\QuestsController as Quests;

Route::get('/', function () {
    return view('home');
})->middleware('auth')->name('home');

Route::view('/register', 'auth.register')->middleware('guest')->name('register');
Route::post('/register', [Register::class, 'register'])->middleware('guest')->name('register.post');

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [Login::class, 'login'])->middleware('guest');
Route::post('/logout', [Login::class, 'logout'])->middleware('auth')->name('logout');

Route::view('/quests', 'quests.quests')->middleware('auth', 'quests')->name('quests');
