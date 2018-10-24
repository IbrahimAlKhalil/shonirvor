<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagePropertiesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('package_properties')->insert([
            ['name' => 'name'],
            ['name' => 'description'],
            ['name' => 'duration'],
            ['name' => 'fee'],
            ['name' => 'refer_target'],
            ['name' => 'refer_onetime_interest'],
            ['name' => 'refer_renew_interest'],
            ['name' => 'refer_fail_onetime_interest'],
            ['name' => 'refer_fail_renew_interest'],
            ['name' => 'is_default']
        ]);
    }
}
