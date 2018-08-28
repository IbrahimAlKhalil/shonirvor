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
            ['name' => 'customer']
        ]);
    }
}