<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAddress;

class UsersAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ایجاد 10 آدرس کاربری با استفاده از UserAddressFactory
        UserAddress::factory()->count(10)->create();
    }
}

