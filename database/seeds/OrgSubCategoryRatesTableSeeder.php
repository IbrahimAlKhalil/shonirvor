<?php

use App\Models\Org;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrgSubCategoryRatesTableSeeder extends Seeder
{
    public function run()
    {
        $orgs = Org::select('id')->get();

        $data = [];

        foreach ($orgs as $org) {
            foreach ($org->subCategories as $subCategory) {
                array_push($data, [
                    'org_id' => $org->id,
                    'sub_category_id' => $subCategory->id,
                    'rate' => array_random(range(50, 5000, 10))
                ]);
            }
        }

        DB::table('org_sub_category_rates')->insert($data);

    }
}