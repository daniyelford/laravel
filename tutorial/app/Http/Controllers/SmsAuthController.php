<?php

namespace App\Http\Controllers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Verification;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiter;

class SmsAuthController extends Controller
{
    protected $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }
    public function sendCode(Request $request)
    {
        $request->validate([
            'mobile' => 'required|regex:/^09\d{9}$/'
        ]);
        $mobile = $request->mobile;
        Verification::where('mobile', $mobile)->delete();
        $key = "send-code-{$mobile}";
        if ($this->rateLimiter->tooManyAttempts($key, 2)) {
            throw ValidationException::withMessages([
                'mobile' => ['شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.']
            ]);
        }
        $code = rand(10000, 99999);
        Verification::updateOrCreate(
            ['mobile' => $request->mobile],
            ['code' => $code]
        );
        $this->sendSmsVerification($request->mobile, $code);
        $this->rateLimiter->hit($key, 120);
        return response()->json(['message' => 'کد ارسال شد']);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'mobile' => 'required|regex:/^09\d{9}$/',
            'code' => 'required'
        ]);
        $attempts = Cache::get('verify_attempts_' . $request->mobile, 0);

        if ($attempts >= 10) {
            return response()->json(['message' => 'تعداد تلاش‌های شما تمام شده است. لطفاً بعداً دوباره امتحان کنید.'], 422);
        }
        $verify = Verification::where('mobile', $request->mobile)->where('code', $request->code)->first();
        if (!$verify) {
            Cache::put('verify_attempts_' . $request->mobile, $attempts + 1, now()->addMinutes(10));
            throw ValidationException::withMessages([
                'code' => ['کد نادرست است.']
            ]);
        }
        $verify->delete();
        Cache::forget('verify_attempts_' . $request->mobile);
        $user = User::where('mobile', $request->mobile)->first();
        if (!$user) {
            return redirect()->route('register')->with([
                'mobile' => $request->mobile,
                'mobile_verified' => true,
            ]);
        }
        Auth::login($user);
        return redirect()->intended('/dashboard');
    }

    private function sendSmsVerification($mobile, $code)
    {
        \Log::info("در حال ارسال SMS به $mobile با کد $code");
        $response = Http::withToken(config('services.smsir.api_key'))
        ->post('https://api.sms.ir/v1/send/verify', [
            'mobile' => $mobile,
            'templateId' => config('services.smsir.template_id'),
            'parameters' => [
                ['name' => 'CODE', 'value' => $code],
            ],
        ]);
    }
}
