<?php 
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Http\Controllers\{
    Users\Auth\LoginController,
    Users\Auth\LogoutController,
    DashboardController,
    Users\UserController,
    Users\UserMobileController,
    Users\UserAccountController,
    Users\UserAddressController,
    Users\BardashtAzAccountController,
    Users\VarizController
};

// روت‌های لاگین و ثبت‌نام
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
Route::post('/send-login-code', [LoginController::class, 'sendLoginCode'])->name('send-login-code');
Route::get('/verify', [LoginController::class, 'showVerifyForm'])->name('verify');
Route::post('/verify-code', [LoginController::class, 'verifyLoginCode'])->name('verify-code');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register-request', [LoginController::class, 'registerRequest'])->name('register-request');

// روت برای لاگ‌اوت
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// روت‌های محافظت شده که نیاز به احراز هویت دارند
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::apiResource('users', UserController::class);
    Route::apiResource('users-mobile', UserMobileController::class);
    Route::apiResource('users-account', UserAccountController::class);
    Route::apiResource('users-address', UserAddressController::class);
    Route::apiResource('bardasht-az-account', BardashtAzAccountController::class);
    Route::apiResource('variz', VarizController::class);
});