<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\SmsAuthController;
use App\Http\Controllers\WalletController;
Route::post('/auth/send-code', [SmsAuthController::class, 'sendCode']);
Route::post('/auth/verify-code', [SmsAuthController::class, 'verifyCode']);
Route::get('/login-sms', fn () => Inertia::render('Auth/SmsLogin'));
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
Route::post('/send-code', [AuthController::class, 'sendCode']);
Route::post('/verify-code', [AuthController::class, 'verifyCode']);
Route::get('/posts', function () {
    return Inertia::render('Posts');
});

Route::middleware('auth')->group(function () {
    Route::get('/wallet', function () {
        return Inertia::render('Wallet/Index');
    })->name('wallet.index');
    Route::get('/user/wallet', [WalletController::class, 'show'])->name('wallet.show');
    Route::get('/user/wallet/balance', [WalletController::class, 'getBalance'])->name('wallet.balance');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
