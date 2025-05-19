<?php

namespace Modules\User\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redirect;

class EnsureUserAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check())
            return Redirect::route('login');
        
        return $next($request);
    }
}
