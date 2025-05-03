<?php

namespace Database\Factories;

use App\Models\UserCart;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserCartFactory extends Factory
{
    protected $model = UserCart::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),  // ایجاد یک User جدید
            'shoamre_shaba' => $this->faker->unique()->numerify('##########'),
            'shomare_cart' => $this->faker->unique()->numerify('##########'),
        ];
    }
}

