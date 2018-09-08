<?php

use App\Models\OrgCategory;
use App\Models\OrgSubCategory;
use Illuminate\Database\Seeder;

class OrgCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(OrgCategory::class, rand(20, 40))->create()->each(function ($orgCategory) {
            $orgCategory->subCategories()->saveMany(factory(OrgSubCategory::class, rand(10, 20))->make());
        });
    }
}
