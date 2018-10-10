<?php

use App\Models\Org;
use Illuminate\Database\Seeder;

class OrgSubCategoryRatesTableSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        Org::all()->each(function ($org) use ($data) {
            $org->subCategories->each(function ($subCategory) use ($org, $data) {
                array_push($data, [
                    'org_id' => $org->id,
                    'sub_category_id' => $subCategory->id,
                    'rate' => randomElement([50, 10, 60, 80, 90, 40, 300, 560, 200, 560, 990, 5000])
                ]);
            });
        });
    }
}
