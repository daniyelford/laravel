<?php
namespace Database\Factories;

use App\Models\UserMobile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserMobileFactory extends Factory
{
    protected $model = UserMobile::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),  // یک کاربر جدید ایجاد می‌کند
            'mobile' => $this->faker->unique()->phoneNumber,
        ];
    }
}

