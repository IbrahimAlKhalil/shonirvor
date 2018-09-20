<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Seeds\BdgeocodeSeeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->call(BdgeocodeSeeder::class);

        /***** Creating Roles *****/
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'ind'],
            ['name' => 'org'],
        ]);

        /***** Service Provider Contract Method *****/
        DB::table('work_methods')->insert([
            ['name' => 'hourly'],
            ['name' => 'daily'],
            ['name' => 'monthly']
        ]);

        /***** Service Type *****/
        DB::table('service_types')->insert([
            ['name' => 'ind'],
            ['name' => 'org']
        ]);

    }
}