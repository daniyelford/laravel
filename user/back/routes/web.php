<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laragear\WebAuthn\Http\Routes as WebAuthnRoutes;
use Laragear\WebAuthn\Assertion\Creator\AssertionCreator;
use Laragear\WebAuthn\Assertion\Validator\AssertionValidator;
require base_path('routes/users.php');


// مسیرهای WebAuthn برای ثبت‌نام و ورود با استفاده از WebAuthnRoutes
WebAuthnRoutes::register()
    ->attest('webauthn/register')   // مسیر ثبت‌نام
    ->assert('webauthn/login')      // مسیر ورود
    ->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class); // حذف csrf middleware از مسیرهای WebAuthn

// مسیر WebAuthn برای ارسال options (به جای استفاده از `AssertionCreator`)
Route::post('/webauthn/options', function () {
    $creator = new AssertionCreator();
    $result = $creator->through(Auth::user());
    return response()->json($result);
});

// مسیر WebAuthn برای تایید ورود (به جای استفاده از `AssertionValidator`)
Route::post('/webauthn/verify', function (Request $request, AssertionValidator $validator) {
    $validator = new AssertionValidator();
    $result = $validator->through($request);
    return response()->json(['success' => true]);
});


