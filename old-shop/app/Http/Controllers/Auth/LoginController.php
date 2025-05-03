<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User, UserAccount, UserMobile, Verification};
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Cache\RateLimiter;
use App\Http\Controllers\Api\SmsController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    protected $rateLimiter;
    protected $smsService;

    public function __construct(RateLimiter $rateLimiter, SmsController $smsController)
    {
        $this->smsService = $smsController;
        $this->rateLimiter = $rateLimiter;
    }

    public function showLoginForm()
    {
        return Inertia::render('Login');
    }

    public function sendLoginCode(Request $request)
    {
        try {
            // اعتبارسنجی ورودی موبایل
            $request->validate([
                'mobile' => ['required', 'regex:/^09\d{9}$/']
            ]);
    
            $mobile = $request->mobile;
    
            // محدودیت تعداد درخواست‌ها
            $key = "send-code-{$mobile}";
            if ($this->rateLimiter->tooManyAttempts($key, 2)) {
                return back()->withErrors(['mobile' => 'شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.']);
            }
    
            // تولید و ذخیره کد تایید
            $code = rand(10000, 99999);
            Verification::updateOrCreate(['mobile' => $mobile], ['code' => $code]);
    
            // ارسال پیامک کد تایید
            $this->smsService->sendSmsVerification($mobile, $code);
    
            // ثبت درخواست در محدودیت‌ها (Rate Limit)
            $this->rateLimiter->hit($key, 120);
    
            // ذخیره موبایل در سشن برای مرحله تایید
            session(['login_mobile' => $mobile]);
    
            // بازگشت به صفحه تایید با ارسال موبایل و پیام
            return redirect()->route('verify')->with(['mobile' => $mobile, 'message' => 'کد ارسال شد.']);
    
        } catch (\Exception $e) {
            // در صورت بروز هرگونه خطا
            \Log::error('Error sending login code: ' . $e->getMessage());
            return back()->withErrors(['mobile' => 'متاسفانه مشکلی پیش آمده است. لطفاً دوباره تلاش کنید.']);
        }
    }
    
    // public function sendLoginCode(Request $request)
    // {
    //     $request->validate([
    //         'mobile' => ['required', 'regex:/^09\d{9}$/']
    //     ]);

    //     $mobile = $request->mobile;

    //     // Rate limit
    //     $key = "send-code-{$mobile}";
    //     if ($this->rateLimiter->tooManyAttempts($key, 2)) {
    //         return back()->withErrors(['mobile' => 'شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.']);
    //     }

    //     // Generate & Save Code
    //     $code = rand(10000, 99999);
    //     Verification::updateOrCreate(['mobile' => $mobile], ['code' => $code]);
    //     $this->smsService->sendSmsVerification($mobile, $code);
    //     $this->rateLimiter->hit($key, 120);

    //     // Store mobile in session for verify step
    //     session(['login_mobile' => $mobile]);

    //     return redirect()->route('verify')->with(['mobile' => $mobile, 'message' => 'کد ارسال شد.']);
    // }

    public function showVerifyForm()
    {
        $mobile = session('login_mobile');
        if (!$mobile) {
            return redirect()->route('login');
        }
        return Inertia::render('Verify', [
            'mobile' => $mobile,
            'message' => session('message'),
        ]);
    }

    public function verifyLoginCode(Request $request)
    {
        $request->validate(['code' => 'required']);

        $mobile = session('login_mobile');
        if (!$mobile) {
            \Log::info("در mobile");
            
            return redirect()->route('login');
        }

        $attempts = Cache::get('verify_attempts_' . $mobile, 0);
        if ($attempts >= 10) {
            \Log::info("در attempts");

            return back()->withErrors(['code' => 'تعداد تلاش‌های شما تمام شده است. لطفاً بعداً دوباره امتحان کنید.']);
        }

        $verify = Verification::where('mobile', $mobile)->where('code', $request->code)->first();
        if (!$verify) {
            \Log::info("در verify");

            Cache::put('verify_attempts_' . $mobile, $attempts + 1, now()->addMinutes(10));
            return back()->withErrors(['code' => 'کد نادرست است.']);
        }

        // Valid Code
        $verify->delete();
        Cache::forget('verify_attempts_' . $mobile);

        // Find/Create User Mobile
        $userMobile = UserMobile::firstOrCreate(['mobile' => $mobile]);

        // Find/Create Account
        $userAccount = UserAccount::firstOrCreate(
            ['user_mobile_id' => $userMobile->id],
            ['mojodi_account' => 0]
        );

        session(['user_account_id' => $userAccount->id]);

        // If user exists
        if ($userMobile->user_id && ($user = User::find($userMobile->user_id))) {
            auth()->login($user);
            \Log::info("در find user");

            session(['login' => true]);
            return redirect()->route('dashboard');
        }
        \Log::info("در normal reqister$userMobile->id");

        // Else go to register
        return redirect()->route('register')->with(['mobile' => $userMobile->id]);
    }

    public function showRegisterForm()
    {
        $mobile = session('login_mobile');
        \Log::info("show register form $mobile");
        if (!$mobile) {
            return redirect()->route('login');
        }
        return Inertia::render('Register', ['mobile' => $mobile]);
    }

    public function registerRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'mobile' => 'required|exists:users_mobile,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $account = UserAccount::find(session('user_account_id'));
        $userMobile = UserMobile::find($request->mobile);

        if (!$userMobile || !$account) {
            return redirect()->route('login')->withErrors(['code' => 'موبایل یافت نشد.']);
        }

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('users', 'public');
            $account->update(['image' => $image]);
        }

        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'code_mely' => null,
        ]);

        $userMobile->update(['user_id' => $user->id]);

        auth()->login($user);
        session(['login' => true]);

        return redirect()->route('dashboard');
    }

    public function dashboard()
    {
        return Inertia::render('Dashboard');
    }
}
