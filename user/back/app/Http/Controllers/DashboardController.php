<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index()
    {
        if (session()->has('user_account_id') && session('login') === true) {
            return Inertia::render('Dashboard', [
                'user' => auth()->user(),
            ]);
        } else {
            return Inertia::render('Login');
        }
    }
}
