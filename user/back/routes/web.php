<?php
 
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request;
// use WebAuthn\WebAuthn;
use Laragear\WebAuthn\Facades\WebAuthn;
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
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureUserIsAuthenticated;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/', [LoginController::class, 'showLoginForm'])->name('home');
Route::post('/send-login-code', [LoginController::class, 'sendLoginCode'])->name('send-login-code');
Route::get('/verify', [LoginController::class, 'showVerifyForm'])->name('verify');
Route::post('/verify-code', [LoginController::class, 'verifyLoginCode'])->name('verify-code');
Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
Route::post('/register-request', [LoginController::class, 'registerRequest'])->name('register-request');

Route::get('/webauthn/register', function () {
    return Inertia::render('WebAuthnRegister');
})->name('webauthn.register');
Route::post('/webauthn/options', function (Request $request, WebAuthn $webauthn) {
    return $webauthn->generateCreate(
        user: Auth::user(),
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
    Auth::login($user);
    session(['login' => true]);
    return response()->json(['success' => true]);
});

Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
Route::middleware([EnsureUserIsAuthenticated::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::apiResource('users', UserController::class);
    Route::apiResource('users-mobile', UserMobileController::class);
    Route::apiResource('users-account', UserAccountController::class);
    Route::apiResource('users-address', UserAddressController::class);
    Route::apiResource('bardasht-az-account', BardashtAzAccountController::class);
    Route::apiResource('variz', VarizController::class);
});
