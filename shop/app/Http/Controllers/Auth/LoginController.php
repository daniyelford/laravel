<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UsersMobile;
use App\Models\Verification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Cache\RateLimiter;
use App\Http\Controllers\Api\SmsController;
use Inertia\Inertia;
class LoginController extends Controller
{ 
    protected $rateLimiter;
    protected $smsService;

    public function __construct(RateLimiter $rateLimiter, SmsController $smsController)
    {
        $this->smsService = $smsController;
        $this->rateLimiter = $rateLimiter;
    }

    public function sendLoginCode(Request $request) 
    {
        session()->flush();
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
        ]);
        if ($validator->fails()) {
            return Inertia::render('Login', [
                'errors' => ['mobile' => 'شماره موبایل معتبر نیست.']
            ]);
        }
        $mobile = $request->mobile;
        Verification::where('mobile', $mobile)->delete();
        $key = "send-code-{$mobile}";
        if ($this->rateLimiter->tooManyAttempts($key, 2)) {
            return Inertia::render('Login', [
                'errors' => ['mobile' => 'شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.']
            ]);
        }
        $code = rand(10000, 99999);
        Verification::updateOrCreate(
            ['mobile' => $mobile],
            ['code' => $code]
        );
        $this->smsService->sendSmsVerification($mobile, $code);
        $this->rateLimiter->hit($key, 120);
        session(['login_mobile' => $mobile]);
        return Inertia::render('Verify', [
            'message' => 'کد ارسال شد.'
        ]);
    }

    public function verifyLoginCode(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);
        $mobile = session('login_mobile');
        $attempts = Cache::get('verify_attempts_' . $mobile, 0);
        if ($attempts >= 10) {
            return Inertia::render('Login', [
                'errors' => ['code' => 'تعداد تلاش‌های شما تمام شده است. لطفاً بعداً دوباره امتحان کنید.']
            ]);
        }
        $verify = Verification::where('mobile', $mobile)->where('code', $request->code)->first();
        if (!$verify) {
            Cache::put('verify_attempts_' . $mobile, $attempts + 1, now()->addMinutes(10));
            return Inertia::render('Verify', [
                'errors' => ['code' => 'کد نادرست است.']
            ]);
        }
        $verify->delete();
        Cache::forget('verify_attempts_' . $mobile);
        $userMobile = UsersMobile::where('mobile', $mobile)->first();
        if (!$userMobile) {
            $add_mobile = UsersMobile::create(['user_id' => null, 'mobile' => $mobile]);
            $add_account = UserAccount::create(['user_mobile_id' => $add_mobile->id, 'mojodi_account' => 0]);
            session(['user_account_id' => $add_account->user_id]);
            return Inertia::render('Register', [
                'mobile' => $add_mobile->id
            ]);
        }
        $userAccount = UserAccount::where('user_mobile_id', $userMobile->id)->first();
        if (!$userAccount) {
            $userAccount = UserAccount::create([
                'user_mobile_id' => $userMobile->id,
                'mojodi_account' => 0
            ]);
        }
        session(['user_account_id' => $userAccount->id]);
        if (!empty($userMobile->user_id)) {
            $user=User::find($userMobile->user_id);
            if(!$user){
                session(['user_account_id' => $userAccount->id]);
                return Inertia::render('Register', [
                    'mobile' => $add_mobile->id
                ]);
            }
            session(['login'=>true]);
            auth()->login($user);
            return Inertia::render('Dashboard');
        }
        return Inertia::render('Register', [
            'mobile' => $userMobile->id,
        ]);
    }

    public function showVerifyForm()
    {
        return Inertia::render('Verify');
    }

    public function showLoginForm()
    {
        return Inertia::render('Login');
    }

    public function showRegisterForm()
    {
        return Inertia::render('Register');
    }

    public function registerRequest(Request $request)
    {
        $account = null;
        if (session()->has('user_account_id') && intval(session('user_account_id')) > 0) {
            $account = UserAccount::find(intval(session('user_account_id')));
        }

        if (!$account) {
            return Inertia::render('Register', [
                'errors' => ['code' => 'دسترسی نامعتبر است. لطفاً دوباره تلاش کنید.']
            ]);
        }
        $account=UserAccount::find(intval(session('user_account_id')));
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'mobile' => 'required|exists:users_mobile,id',
            'image' => 'nullable|image|max:2048',
        ]);
        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('users', 'public');
        }
        $userMobile = UsersMobile::find($request->mobile);
        if (!$userMobile || !$account) {
            return Inertia::render('Login', [
                'errors' => ['code' => 'موبایل یافت نشد.']
            ]);
        }
        $user = User::create([
            'name' => $request->name,
            'family' => $request->family,
            'code_mely' => null,
        ]);
        if(!empty($image)){
            $account->update(['image'=>$image]);
        }
        $userMobile->update(['user_id' => $user->id]);
        auth()->login($user);
        session(['login'=>true]);
        return Inertia::render('dashboard');
    }
}
