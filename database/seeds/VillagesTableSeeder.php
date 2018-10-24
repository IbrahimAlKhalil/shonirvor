<?php

use App\Models\Village;
use Illuminate\Database\Seeder;

class VillagesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Village::class, 50)->create();
    }
}
