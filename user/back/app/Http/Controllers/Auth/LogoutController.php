<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        auth()->logout();
        session()->forget(['login', 'login_mobile', 'user_account_id']);
        session()->flush();
        return redirect()->route('login');
    }
}
