<?php
namespace Database\Factories;

use App\Models\Variz;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class VarizFactory extends Factory
{
    protected $model = Variz::class;

    public function definition()
    {
        return [
            'user_account_id' => UserAccount::factory(),  // ایجاد یک UserAccount جدید
            'meghdar' => $this->faker->randomFloat(2, 500, 10000),
            'factor_pardakht' => $this->faker->uuid,
            'time' => now(),
        ];
    }
}
