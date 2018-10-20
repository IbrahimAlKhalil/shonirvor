<?php

use Illuminate\Database\Seeder;
use Sandofvega\Bdgeocode\Seeds\BdgeocodeSeeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->call(BdgeocodeSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(WorkMethodsTableSeeder::class);
        $this->call(ServiceTypesTableSeeder::class);
        $this->call(PackageTypesTableSeeder::class);
        $this->call(PackagePropertiesTableSeeder::class);
        $this->call(ContentsTableSeeder::class);
    }
}