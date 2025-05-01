<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Auth\LoginController;
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
Route::post('/send-login-code', [LoginController::class, 'sendLoginCode'])->name('send-login-code');
Route::get('/verify', [LoginController::class, 'showVerifyForm'])->name('verify');
Route::post('/verify-code', [LoginController::class, 'verifyLoginCode'])->name('verify-code');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register-request', [LoginController::class, 'registerRequest'])->name('register-request');

use Illuminate\Http\Request;
use Laragear\WebAuthn\WebAuthn;
Route::get('/webauthn/register', function () {
    return Inertia::render('WebAuthnRegister');
})->name('webauthn.register');
Route::post('/webauthn/options', function (Request $request, WebAuthn $webauthn) {
    return $webauthn->generateCreate(
        user: auth()->user(),
        userVerification: 'required',
    );
});
Route::post('/webauthn/verify', function (Request $request, WebAuthn $webauthn) {
    $webauthn->validateCreate($request);
    return response()->json(['success' => true]);
});
Route::get('/webauthn/login', function () {
    return Inertia::render('WebAuthnLogin');
})->name('webauthn.login');
Route::post('/webauthn/login/options', function (Request $request, WebAuthn $webauthn) {
    return $webauthn->generateRequest();
});
Route::post('/webauthn/login/verify', function (Request $request, WebAuthn $webauthn) {
    $user = $webauthn->validateRequest($request);
    auth()->login($user);
    session(['login' => true]);
    return response()->json(['success' => true]);
});

use App\Http\Controllers\Auth\LogoutController;
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

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
