<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


/*Route::get('/', function () {
    return view('welcome');
});*/

//1º
Auth::routes(['verify' => true]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home'); 
Route::get('/verificado', [App\Http\Controllers\HomeController::class, 'verificado'])->name('verificado');
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'show'])->name('profile');

