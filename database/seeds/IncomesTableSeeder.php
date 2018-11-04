<?php

use App\Models\Income;
use Illuminate\Database\Seeder;

class IncomesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Income::class, 100)->create();
    }
}
