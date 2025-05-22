<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserCart;

class UsersCartTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ایجاد 10 سبد خرید با استفاده از UserCartFactory
        UserCart::factory()->count(10)->create();
    }
}
