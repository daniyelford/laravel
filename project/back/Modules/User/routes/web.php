<?php

use Illuminate\Support\Facades\Route;
use Modules\User\app\Http\Controllers\UserController;
use Modules\User\app\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('user', [UserController::class, 'index'])->name('user');


// Route::middleware(['auth', 'verified'])->group(function () {
//     Route::resource('users', UserController::class)->names('users');
// });
