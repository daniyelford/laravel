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
            'register',
            'register-request',
            'webauthn/register',
            'webauthn/options',
            'webauthn/verify',
            'webauthn/login',
            'webauthn/login/options',
            'webauthn/login/verify',
        ];
        if ($request->is($exceptPaths) || $request->routeIs($exceptPaths)) {
            return $next($request);
        }
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
