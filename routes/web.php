<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\Register;

Route::get('/', function () {
    return view('welcome');
});
Route::view("/register", "auth.register")
->middleware("guest")
->name("register");
Route::post("/register", Register::class)
->middleware("guest");
