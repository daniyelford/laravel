<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureUserIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $exceptPaths = [
            'login',
            '/',
            'send-login-code',
            'verify',
            'verify-code',
            'register-request',
            'register',
            'webauthn/register',
            'webauthn/options',
            'webauthn/verify',
            'webauthn/login',
            'webauthn/login/options',
            'webauthn/login/verify',
        ];
        if (session('login') !== true) {
            if ($request->is($exceptPaths)) {
                return $next($request);
            }
            return redirect()->route('login');
        }
        return $next($request);
    }
}
