<?php

namespace Tests\Feature;

use App\Models\Verification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SmsAuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_should_send_sms_code_and_store_it_in_the_database()
    {
        $mobile = '09123456789';
        $code = rand(10000, 99999);

        // ارسال درخواست برای ارسال کد
        $response = $this->postJson('/api/send-code', ['mobile' => $mobile]);

        // بررسی موفقیت درخواست
        $response->assertStatus(200);
        $response->assertJson(['message' => 'کد ارسال شد']);

        // بررسی ذخیره شدن کد در دیتابیس
        $this->assertDatabaseHas('verifications', ['mobile' => $mobile]);

        // بررسی ارسال کد به API SMS.ir
        Http::assertSent(function ($request) use ($mobile, $code) {
            return $request->url() === 'https://api.sms.ir/sms/send' &&
                   $request->data()['to'] === $mobile &&
                   $request->data()['text'] === "Your verification code is: $code";
        });
    }

    #[Test]
    public function it_should_limit_the_number_of_requests_to_5_in_a_minute()
    {
        $mobile = '09123456789';

        // ارسال 5 درخواست موفق
        for ($i = 0; $i < 5; $i++) {
            $this->postJson('/api/send-code', ['mobile' => $mobile]);
        }

        // ارسال 6ام باید با خطا مواجه شود
        $response = $this->postJson('/api/send-code', ['mobile' => $mobile]);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'شما بیش از حد مجاز درخواست ارسال کد داشتید. لطفاً دقایقی دیگر تلاش کنید.']);
    }

    #[Test]
    public function it_should_verify_code_and_reset_attempts()
    {
        $mobile = '09123456789';
        $code = rand(10000, 99999);

        // ذخیره‌سازی کد در دیتابیس
        Verification::create(['mobile' => $mobile, 'code' => $code]);

        // ارسال درخواست برای تایید کد صحیح
        $response = $this->postJson('/api/verify-code', ['mobile' => $mobile, 'code' => $code]);

        // بررسی موفقیت تایید کد
        $response->assertStatus(200);
        $response->assertJson(['message' => 'ورود موفق!']);

        // بررسی پاک شدن کد از دیتابیس
        $this->assertDatabaseMissing('verifications', ['mobile' => $mobile]);
    }

    #[Test]
    public function it_should_block_user_after_10_failed_attempts()
    {
        $mobile = '09123456789';
        $attempts = 10;

        // شبیه‌سازی 10 تلاش ناموفق
        for ($i = 0; $i < $attempts; $i++) {
            $this->postJson('/api/verify-code', ['mobile' => $mobile, 'code' => 'wrongcode']);
        }

        // ارسال 11ام باید قفل بشه
        $response = $this->postJson('/api/verify-code', ['mobile' => $mobile, 'code' => 'wrongcode']);
        $response->assertStatus(422);
        $response->assertJson(['message' => 'تعداد تلاش‌های شما تمام شده است. لطفاً بعداً دوباره امتحان کنید.']);
    }
}