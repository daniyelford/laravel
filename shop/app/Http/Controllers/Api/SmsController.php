<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function sendSmsVerification($mobile, $code)
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
        // if ($response->successful()) {
        //     return true;
        // } else {
        //     throw new \Exception("خطا در ارسال پیامک");
        // }
        return true;
    }
}
