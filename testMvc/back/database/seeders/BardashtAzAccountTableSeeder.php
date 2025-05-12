<?php 
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BardashtAzAccount;

class BardashtAzAccountTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ایجاد 10 رکورد برداشت از حساب با استفاده از BardashtAzAccountFactory
        BardashtAzAccount::factory()->count(10)->create();
    }
}
