<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UsersTableSeeder::class,
            UsersMobileTableSeeder::class,
            UsersAccountTableSeeder::class,
            UsersAddressTableSeeder::class,
            UsersCartTableSeeder::class,
            BardashtAzAccountTableSeeder::class,
            VarizTableSeeder::class,
        ]);
    }
}
