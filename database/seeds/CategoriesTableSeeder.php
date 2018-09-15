<?php

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    public function run()
    {
        factory(Category::class, 15)->create()->each(function ($category) {
            $category->subCategories()
                ->saveMany(factory(SubCategory::class, 15)->make());
        });
    }
}
