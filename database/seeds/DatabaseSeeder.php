<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ProductionSeeder::class);
        $this->call(VillagesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(IndsTableSeeder::class);
        $this->call(OrgsTableSeeder::class);
        $this->call(SubCategoriablesTableSeeder::class);
        $this->call(IndWorkMethodTableSeeder::class);
        $this->call(OrgSubCategoryRatesTableSeeder::class);
        $this->call(WorkImagesTableSeeder::class);
        $this->call(AdsTableSeeder::class);
        $this->call(NoticesTableSeeder::class);
        $this->call(FeedbacksTableSeeder::class);
    }
}