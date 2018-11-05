<?php

use App\Models\Ind;
use Illuminate\Database\Seeder;

class IndsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Ind::class, 200)->create();
    }
}
