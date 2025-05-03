<?php
namespace Database\Factories;

use App\Models\UserAddress;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserAddressFactory extends Factory
{
    protected $model = UserAddress::class;

    public function definition()
    {
        return [
            'user_account_id' => UserAccount::factory(),  // ایجاد یک UserAccount جدید
            'address' => $this->faker->address,
            'code_posty' => $this->faker->postcode,
            'lat' => $this->faker->latitude,
            'long' => $this->faker->longitude,
        ];
    }
}

