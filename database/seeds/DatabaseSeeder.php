<?php

use Illuminate\Database\Seeder;
use Sandofvega\Bdgeocode\Seeds\BdgeocodeSeeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(ProductionSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(PendingIndServiceDocsTableSeeder::class);
        $this->call(PendingIndServiceImagesTableSeeder::class);
        $this->call(PendingOrgServiceDocsTableSeeder::class);
        $this->call(PendingOrgServiceImagesTableSeeder::class);
        $this->call(IndServiceDocsTableSeeder::class);
        $this->call(IndServiceImagesTableSeeder::class);
        $this->call(OrgServiceDocsTableSeeder::class);
        $this->call(OrgServiceImagesTableSeeder::class);
        $this->call(BdgeocodeSeeder::class);
    }
}
