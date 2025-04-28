<?php
namespace Database\Factories;

use App\Models\BardashtAzAccount;
use App\Models\UserAccount;
use App\Models\UserCart;
use Illuminate\Database\Eloquent\Factories\Factory;

class BardashtAzAccountFactory extends Factory
{
    protected $model = BardashtAzAccount::class;

    public function definition()
    {
        return [
            'user_account_id' => UserAccount::factory(),  // ایجاد یک UserAccount جدید
            'user_cart_id' => UserCart::factory(),  // ایجاد یک UserCart جدید
            'meghdar' => $this->faker->randomFloat(2, 100, 5000),
            'time' => now(),
            'vaziate_entghal_b_hesab_karbar' => $this->faker->randomElement(['pending', 'completed', 'failed']),
        ];
    }
}
