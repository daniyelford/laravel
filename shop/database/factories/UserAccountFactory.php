<?php
namespace Database\Factories;

use App\Models\UserAccount;
use App\Models\UserMobile;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAccountFactory extends Factory
{
    protected $model = UserAccount::class;

    public function definition()
    {
        return [
            'user_mobile_id' => UserMobile::factory(),  // ایجاد یک UserMobile جدید
            'mojodi_account' => $this->faker->randomFloat(2, 1000, 10000),
        ];
    }
}
