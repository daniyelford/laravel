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
            $request->validate([
                'mobile' => ['required', 'regex:/^09\d{9}$/']
            ]);
            $mobile = $request->mobile;    
            $key = "send-code-{$mobile}";
            if ($this->rateLimiter->tooManyAttempts($key, 2)) {
                return back()->withErrors(['mobile' => 'شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.']);
            }    
            $code = rand(10000, 99999);
            Verification::updateOrCreate(['mobile' => $mobile], ['code' => $code]);    
            $this->smsService->sendSmsVerification($mobile, $code);    
            $this->rateLimiter->hit($key, 120);    
            session(['login_mobile' => $mobile]);    
            return redirect()->route('verify');
        } catch (\Exception $e) {
            return back()->withErrors(['mobile' => 'متاسفانه مشکلی پیش آمده است. لطفاً دوباره تلاش کنید.']);
        }
    }

    public function showVerifyForm()
    {
        $mobile = session('login_mobile');
        if (!$mobile) {
            return Inertia::render('Login', [
                'mobile' =>'mobile not exists for validate'
            ]);
        }
        return Inertia::render('Verify', [
            'mobile' => $mobile,
            'message' => 'کد ارسال شد.'
        ]);
    }

    public function verifyLoginCode(Request $request)
    {
        $request->validate(['code' => 'required']);
        $mobile = session('login_mobile');
        if (!$mobile) {
            return redirect()->route('login')->withErrors(['mobile' =>'mobile not exists']);
        }
        $attempts = Cache::get('verify_attempts_' . $mobile, 0);
        if ($attempts >= 10) {
            return redirect()->route('verify')->withErrors(['code' => 'تعداد تلاش‌های شما تمام شده است. لطفاً بعداً دوباره امتحان کنید.']);
        }
        $verify = Verification::where('mobile', $mobile)->where('code', $request->code)->first();
        if (!$verify) {
            Cache::put('verify_attempts_' . $mobile, $attempts + 1, now()->addMinutes(10));
            return redirect()->route('verify')->withErrors(['code' => 'کد نادرست است.']);
        }
        $verify->delete();
        Cache::forget('verify_attempts_' . $mobile);
        $userMobile = UserMobile::firstOrCreate(['mobile' => $mobile]);
        $userAccount = UserAccount::firstOrCreate(
            ['user_mobile_id' => $userMobile->id],
            ['mojodi_account' => 0]
        );
        session([
            'user_account_id' => $userAccount->id,
            'user_mobile_id' => $userMobile->id
        ]);
        if ($userMobile->user_id) {
            $user = User::find($userMobile->user_id);
            if(!$user){
                auth()->login($user);
                session(['login' => true]);
                return redirect()->route('dashboard');
            }else{
                return redirect()->route('register');
            }
        }
        return redirect()->route('register');
    }

    public function showRegisterForm()
    {
        if (session()->has('login_mobile') && !empty(session('login_mobile')) && 
        session()->has('user_account_id') && !empty(session('user_account_id')) &&
        session()->has('user_mobile_id') && !empty(session('user_mobile_id'))) {
            return Inertia::render('Register',[
                'verify'=>true,
                'mobile'=>session('login_mobile'),
                'mobileId'=>session('user_mobile_id')
            ]);
        }
        return Inertia::render('Login', [
            'errors' => ['massage' => 'register has error no mobile']
        ]);
    }

    public function registerRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'mobileId' => 'required|exists:users_mobile,id',
            'image' => 'nullable|image|max:2048',
        ]);
        $account = UserAccount::find(session('user_account_id'));
        $userMobile = UserMobile::find($request->mobileId);
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
