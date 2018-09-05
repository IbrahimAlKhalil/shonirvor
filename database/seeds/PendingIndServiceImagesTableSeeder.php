<?php

use App\Models\PendingIndService;
use App\Models\PendingIndServiceImage;
use Illuminate\Database\Seeder;

class PendingIndServiceImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = PendingIndService::pluck('id');
        $data = [];

        foreach ($ids as $id) {
            for ($i = 1; $i < 5; $i++) {

                array_push($data, [
                    'pending_ind_service_id' => $id,
                    'image' => "default/service-images/$i.jpeg",
                ]);

            }
        }

        PendingIndServiceImage::insert($data);
    }
}
