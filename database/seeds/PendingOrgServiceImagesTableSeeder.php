<?php

use App\Models\PendingOrgService;
use App\Models\PendingOrgServiceImage;
use Illuminate\Database\Seeder;

class PendingOrgServiceImagesTableSeeder extends Seeder
{
    public function run()
    {
        $ids = PendingOrgService::pluck('id');
        $data = [];

        foreach ($ids as $id) {
            for ($i = 1; $i < 5; $i++) {

                array_push($data, [
                    'pending_org_service_id' => $id,
                    'image' => "default/service-images/$i.jpg",
                ]);

            }
        }

        PendingOrgServiceImage::insert($data);
    }
}
