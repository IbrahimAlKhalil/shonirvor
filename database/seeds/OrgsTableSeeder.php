<?php

use App\Models\Org;
use Illuminate\Database\Seeder;

class OrgsTableSeeder extends Seeder
{
    public function run()
    {
        factory(Org::class, 100)->create();
    }
}
