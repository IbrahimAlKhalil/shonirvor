<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /***** Creating Roles *****/

        DB::table('roles')->insert([
            ['name' => 'superadmin'],
            ['name' => 'dealer'],
            ['name' => 'ind-service'],
            ['name' => 'org-service'],
        ]);

        /***** Service Provider Contract Method *****/
        DB::table('work_methods')->insert([
            ['method' => 'hourly'],
            ['method' => 'daily'],
            ['method' => 'monthly']
        ]);

        /***** Payment Method *****/
        DB::table('payment_methods')->insert([
            ['method' => 'bkash']
        ]);

    }
}