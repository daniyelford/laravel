<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // اعتبارسنجی اطلاعات ورودی
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'mobile' => 'required|string|size:11|unique:users', // اعتبارسنجی موبایل
        ]);
    
        // ساخت کاربر جدید
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile, // اضافه کردن موبایل
        ]);
    
        // ایجاد کیف پول برای کاربر
        Wallet::create([
            'user_id' => $user->id,
            'balance' => 0, // موجودی اولیه
        ]);
    
        // اجرای رویداد ثبت‌نام
        event(new Registered($user));
    
        // ورود خودکار به سیستم
        Auth::login($user);
    
        // ریدایرکت به داشبورد
        return redirect(route('dashboard', absolute: false));
    }
}
