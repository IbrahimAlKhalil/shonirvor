<?php

use App\Models\IndService;
use App\Models\IndServiceImage;
use Illuminate\Database\Seeder;

class IndServiceImagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ids = IndService::pluck('id');
        $data = [];

        foreach ($ids as $id) {
            for ($i = 1; $i < 5; $i++) {

                array_push($data, [
                    'ind_service_id' => $id,
                    'image' => "default/service-images/$i.jpeg",
                ]);

            }
        }

        IndServiceImage::insert($data);
    }
}
