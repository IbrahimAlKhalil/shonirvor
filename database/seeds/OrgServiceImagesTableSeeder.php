<?php

use App\Models\OrgService;
use App\Models\OrgServiceImage;
use Illuminate\Database\Seeder;

class OrgServiceImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = OrgService::pluck('id');
        $data = [];

        foreach ($ids as $id) {
            for ($i = 1; $i < 5; $i++) {

                array_push($data, [
                    'org_service_id' => $id,
                    'image' => "default/service-images/$i.jpg",
                ]);

            }
        }

        OrgServiceImage::insert($data);
    }
}
