<?php
use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;

use App\Http\Controllers\Auth\LoginController;
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/send-login-code', [LoginController::class, 'sendLoginCode']);
Route::get('/verify', [LoginController::class, 'showVerifyForm'])->name('verify');
Route::post('/verify-code', [LoginController::class, 'verifyLoginCode']);
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register-request', [LoginController::class, 'registerRequest']);

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMobileController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\BardashtAzAccountController;
use App\Http\Controllers\VarizController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::apiResource('users', UserController::class);
    Route::apiResource('users-mobile', UserMobileController::class);
    Route::apiResource('users-account', UserAccountController::class);
    Route::apiResource('users-address', UserAddressController::class);
    Route::apiResource('bardasht-az-account', BardashtAzAccountController::class);
    Route::apiResource('variz', VarizController::class);
});