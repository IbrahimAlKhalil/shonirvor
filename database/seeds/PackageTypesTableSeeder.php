<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageTypesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('package_types')->insert([
            ['name' => 'ind-service'],
            ['name' => 'org-service'],
            ['name' => 'ind-top-service'],
            ['name' => 'org-top-service'],
            ['name' => 'referrer'],
            ['name' => 'ad']
        ]);
    }
}
