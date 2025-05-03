<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Variz;

class VarizTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ایجاد 10 رکورد واریز با استفاده از VarizFactory
        Variz::factory()->count(10)->create();
    }
}
