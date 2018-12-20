<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpenseTypesSeeder extends Seeder
{
    public function run()
    {
        DB::table('expense_types')->insert([
            ['name' => 'referrer']
        ]);
    }
}
