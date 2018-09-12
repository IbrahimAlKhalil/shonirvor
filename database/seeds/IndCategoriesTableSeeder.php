<?php

use App\Models\IndSubCategory;
use Illuminate\Database\Seeder;

class IndCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\IndCategory::class, rand(20, 40))->create()->each(function ($indCategory) {
            $indCategory->subCategories()->saveMany(factory(IndSubCategory::class, rand(10, 20))->make());
        });
    }
}
