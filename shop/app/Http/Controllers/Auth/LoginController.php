<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAccount;
use App\Models\UserMobile;
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

    public function showLoginForm()
    {
        return Inertia::render('Login');
    }

    public function sendLoginCode(Request $request) 
    {
        session()->flush();
        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'regex:/^09\d{9}$/'],
        ]);
        if ($validator->fails()) {
            return redirect()->route('login')->withErrors([
                'mobile' =>'شماره موبایل معتبر نیست.']
            );
        }
        $mobile = $request->mobile;
        Verification::where('mobile', $mobile)->delete();
        $key = "send-code-{$mobile}";
        if ($this->rateLimiter->tooManyAttempts($key, 2)) {
            return redirect()->route('login')->withErrors([
                'mobile' => 'شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.'
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
        return redirect()->route('verify')->with([
            'message' => 'کد ارسال شد.'
        ]);
    }

    public function showVerifyForm()
    {
        if (session()->has('login_mobile') && !empty(session('login_mobile')))
            return Inertia::render('Verify');
        else
            return Inertia::render('Login', [
                'errors' => [
                    'mobile' =>'شماره موبایل معتبر نیست.'
                ]
            ]);
    }

    public function verifyLoginCode(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);
        $mobile = session('login_mobile');
        if(!$mobile){
            return redirect()->route('login')->withErrors(['mobile' => 'موبایل یافت نشد.']);
        }
        $attempts = Cache::get('verify_attempts_' . $mobile, 0);
        if ($attempts >= 10) {
            return redirect()->route('login')->withErrors(['mobile' =>
            'تعداد تلاش‌های شما تمام شده است. لطفاً بعداً دوباره امتحان کنید.'
        ]);
        }
        $verify = Verification::where('mobile', $mobile)->where('code', $request->code)->first();
        if (!$verify) {
            Cache::put('verify_attempts_' . $mobile, $attempts + 1, now()->addMinutes(10));
            return back()->withErrors(['code' =>
                'کد نادرست است.'
            ]);
        }
        $verify->delete();
        Cache::forget('verify_attempts_' . $mobile);
        $userMobile = UserMobile::where('mobile', $mobile)->first();
        if (!$userMobile) {
            $add_mobile = UserMobile::create(['user_id' => null, 'mobile' => $mobile]);
            $add_account = UserAccount::create(['user_mobile_id' => $add_mobile->id, 'mojodi_account' => 0]);
            session([
                'user_mobile_id'=>$add_mobile->id,
                'user_account_id' => $add_account->id
            ]);
            return redirect()->route('register');
        }
        $userAccount = UserAccount::where('user_mobile_id', $userMobile->id)->first();
        if (!$userAccount) {
            $userAccount = UserAccount::create([
                'user_mobile_id' => $userMobile->id,
                'mojodi_account' => 0
            ]);
        }
        if(!empty($userAccount->id)) session(['user_account_id' => $userAccount->id]);
        if(!empty($userMobile->id)) session(['user_mobile_id' => $userMobile->id]);
        if (!empty($userMobile->user_id)) {
            $user=User::find($userMobile->user_id);
            if(!$user){
                return redirect()->route('register');
            }
            session(['login'=>true]);
            auth()->login($user);
            return redirect()->route('dashboard');
        }
        return redirect()->route('register');
    }

    public function showRegisterForm()
    {
        if (session()->has('login_mobile') && !empty(session('login_mobile')) && 
        session()->has('user_account_id') && !empty(session('user_account_id')) &&
        session()->has('user_mobile_id') && !empty(session('user_mobile_id'))) {
            $mobile = session('login_mobile');
            return Inertia::render('Register',[
                'verify'=>true,
                'mobile'=>$mobile,
                'mobileId'=>session('user_mobile_id')
            ]);
        }
        return Inertia::render('Login', [
            'errors' => ['massage' => 'register has error no mobile']
        ]);
        
    }

    public function registerRequest(Request $request)
    {
        \Log::info("register 1");
        if (!(session()->has('login_mobile') && !empty(session('login_mobile'))  && 
        session()->has('user_account_id') && !empty(session('user_account_id')) &&
        session()->has('user_mobile_id') && !empty(session('user_mobile_id')))) {
            return redirect()->route('login')->withErrors(['mobile' => 'موبایل یافت نشد.']);
        }
        $mobileId = session('user_mobile_id');
        $account = UserAccount::find(intval(session('user_account_id')));
        if (!$account) {
            return redirect()->route('login')->withErrors(['mobile' => 'account not found.']);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'mobileId' => 'required|exists:users_mobile,id',
            'image' => 'nullable|image|max:2048',
        ]);
        if ($mobileId==$request->mobileId) {
            $image = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image')->store('users', 'public');
            }
            $userMobile = UserMobile::find($request->mobileId);
            if (!$userMobile || !$account) {
                return redirect()->route('login')->withErrors(['mobile' => 'register has error no mobile and has account??.']);
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
            return redirect()->route('dashboard');
        }
        return redirect()->route('login')->withErrors(['mobile' => 'register has error no mobile and has account??.']);
    }
}
