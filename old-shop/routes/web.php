<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserMobileController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\BardashtAzAccountController;
use App\Http\Controllers\VarizController;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use App\Http\Controllers\WebAuthn\WebAuthnLoginController;
use Illuminate\Http\Request;
use Laragear\WebAuthn\WebAuthn;
use Inertia\Inertia;

// Auth Routes
Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login/send-code', [LoginController::class, 'sendLoginCode'])->name('login.send-code');
Route::post('/login/verify-code', [LoginController::class, 'verifyLoginCode'])->name('login.verify-code');
Route::post('/login/resend-code', [LoginController::class, 'sendLoginCode'])->name('login.resend-code');

Route::get('/verify', [LoginController::class, 'showVerifyForm'])->name('verify');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register/request', [LoginController::class, 'registerRequest'])->name('register.request');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// WebAuthn Routes
Route::get('/webauthn/register', function () {
    return Inertia\Inertia::render('WebAuthnRegister');
})->name('webauthn.register');

Route::post('/webauthn/options', function (Request $request, WebAuthn $webauthn) {
    return $webauthn->generateCreate(user: auth()->user(), userVerification: 'required');
})->name('webauthn.options');

Route::post('/webauthn/verify', function (Request $request, WebAuthn $webauthn) {
    $webauthn->validateCreate($request);
    return response()->json(['success' => true]);
})->name('webauthn.verify');

Route::post('/webauthn/login/options', [WebAuthnLoginController::class, 'options'])->name('webauthn.login.options');
Route::post('/webauthn/login/verify', [WebAuthnLoginController::class, 'login'])->name('webauthn.login.verify');

// Protected Routes
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('dashboard');

    Route::apiResources([
        'users' => UserController::class,
        'users-mobile' => UserMobileController::class,
        'users-account' => UserAccountController::class,
        'users-address' => UserAddressController::class,
        'bardasht-az-account' => BardashtAzAccountController::class,
        'variz' => VarizController::class,
    ]);
});
