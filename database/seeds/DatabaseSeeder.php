<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ProductionSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        $this->call(IndsTableSeeder::class);
        $this->call(OrgsTableSeeder::class);
        $this->call(SubCategoriablesTableSeeder::class);
        $this->call(WorkImagablesTableSeeder::class);
    }
}
