<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('service_types')->insert([
            ['name' => 'ind'],
            ['name' => 'org']
        ]);
    }
}
