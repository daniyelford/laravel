<?php

// database/factories/UserFactory.php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName,
            'family' => $this->faker->lastName,
            'code_mely' => $this->faker->unique()->numerify('###########'),
        ];
    }
}
