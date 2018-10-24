<?php

use App\Models\Reference;
use Illuminate\Database\Seeder;

class ReferencesTableSeeder extends Seeder
{
    public function run()
    {
        factory(Reference::class, 20)->create();
    }
}
