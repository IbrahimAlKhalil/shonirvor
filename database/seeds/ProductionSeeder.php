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
            ['name' => 'dealer'],
            ['name' => 'ind-service'],
            ['name' => 'org-service'],
        ]);
    }
}