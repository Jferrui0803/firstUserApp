<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Controllers\UserController;


Auth::routes(['verify' => true]);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return view('auth.login');
});

Route::get('/home', [HomeController::class, 'home'])->middleware('verified')->name('home');

Route::get('/superAdmin', [SuperAdminController::class, 'home'])->middleware('verified', SuperAdminMiddleware::class)->name('superAdmin.index');
Route::get('/admin', [AdminController::class, 'home'])->middleware([ 'verified', AdminMiddleware::class])->name('admin.index');

Route::patch('superAdmin/edit/{id}', [SuperAdminController::class, 'update'])->name('superAdmin.update');
Route::delete('/superAdmin/delete/{id}', [SuperAdminController::class, 'destroy'])->name('superAdmin.destroy');
Route::patch('superAdmin/verify/{id}', [SuperAdminController::class, 'verify'])->name('superAdmin.verify');
Route::patch('superAdmin/desVerify/{id}', [SuperAdminController::class, 'desVerify'])->name('superAdmin.desVerify');

Route::patch('admin/edit/{id}', [AdminController::class, 'update'])->name('admin.update');
Route::delete('/admin/delete/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
Route::patch('admin/verify/{id}', [AdminController::class, 'verify'])->name('admin.verify');
Route::patch('admin/desVerify/{id}', [AdminController::class, 'desVerify'])->name('admin.desVerify');


Route::middleware('auth','verified')->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update');
});


Route::post('/superadmin/store', [SuperAdminController::class, 'store'])->name('superAdmin.store');
Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');





