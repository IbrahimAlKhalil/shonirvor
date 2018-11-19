<?php

use Illuminate\Database\Seeder;
use App\Models\OrgAdditionalPrice;

class OrgAdditionalPricesTableSeeder extends Seeder
{
    public function run()
    {
        factory(OrgAdditionalPrice::class, 200)->create();
    }
}
