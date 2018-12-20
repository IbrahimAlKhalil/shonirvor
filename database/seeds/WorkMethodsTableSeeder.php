<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkMethodsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('work_methods')->insert([
            ['name' => 'ঘন্টা ভিত্তিক'],
            ['name' => 'দৈনিক'],
            ['name' => 'মাসিক'],
            ['name' => 'চুক্তি ভিত্তিক']
        ]);
    }
}
