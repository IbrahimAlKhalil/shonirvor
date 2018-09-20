<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        /***** Creating Roles *****/
        DB::table('roles')->insert([
            ['name' => 'superadmin'],
            ['name' => 'ind'],
            ['name' => 'org'],
        ]);

        /***** Service Provider Contract Method *****/
        DB::table('work_methods')->insert([
            ['name' => 'ঘন্টা ভিত্তিক'],
            ['name' => 'দৈনিক'],
            ['name' => 'মাসিক']
        ]);

        /***** Service Type *****/
        DB::table('service_types')->insert([
            ['name' => 'ind'],
            ['name' => 'org']
        ]);

    }
}