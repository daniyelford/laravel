<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAccount;

class UsersAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ایجاد 10 حساب کاربری با استفاده از UserAccountFactory
        UserAccount::factory()->count(10)->create();
    }
}
