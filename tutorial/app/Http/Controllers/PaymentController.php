<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // اعتبارسنجی ورودی
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $amount = $request->input('amount');  // مقدار پرداخت
        $callbackUrl = route('payment.callback');  // URL callback شما

        try {
            // درخواست به درگاه پرداخت
            $client = new \SoapClient('https://<bank-url>/payment.wsdl');
            $response = $client->RequestPayment([
                'LoginAccount' => env('BANK_TERMINAL_ID'),
                'Amount' => $amount,
                'OrderId' => time(),
                'CallBackUrl' => $callbackUrl,
            ]);

            if ($response->Status != 0) {
                return Inertia::render('Payment/Failed', [
                    'error' => 'خطا در درخواست پرداخت',
                ]);
            }

            // در صورت موفقیت، ریدایرکت به درگاه پرداخت
            return Inertia::render('Payment/Redirect', [
                'payment_url' => "https://<bank-url>/StartPay/{$response->Token}"
            ]);

        } catch (\Exception $e) {
            return Inertia::render('Payment/Failed', [
                'error' => 'مشکلی پیش آمده، لطفاً دوباره تلاش کنید.',
            ]);
        }
    }

    public function callback(Request $request)
    {
        // بررسی نتیجه تراکنش
        $token = $request->input('token');
        $status = $request->input('status');

        // بررسی اعتبار تراکنش
        $client = new \SoapClient('https://<bank-url>/payment.wsdl');
        $verification = $client->VerifyPayment([
            'Token' => $token,
        ]);

        if ($verification->Status == 0 && $status == 'OK') {
            // تراکنش موفق
            return Inertia::render('Payment/Success', [
                'message' => 'پرداخت با موفقیت انجام شد.',
            ]);
        }

        return Inertia::render('Payment/Failed', [
            'error' => 'پرداخت ناموفق بود، لطفاً دوباره تلاش کنید.',
        ]);
    }
}


