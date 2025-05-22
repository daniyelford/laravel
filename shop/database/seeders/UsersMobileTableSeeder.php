<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserMobile;

class UsersMobileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ایجاد 10 شماره موبایل با استفاده از UserMobileFactory
        UserMobile::factory()->count(10)->create();
    }
}
