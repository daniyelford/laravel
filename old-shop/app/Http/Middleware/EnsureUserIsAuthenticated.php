<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureUserIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        $exceptPaths = [
            '/',
            'login',
            'login/send-code',
            'login/verify-code',
            'login/resend-code',
            'verify',
            'register',
            'register-request',
            'webauthn/register',
            'webauthn/options',
            'webauthn/verify',
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
