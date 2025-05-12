<?php

namespace App\Http\Controllers\Users\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        Auth::logout();
        session()->forget(['login', 'login_mobile', 'user_account_id']);
        session()->flush();
        return Redirect::route('login');
    }
}
