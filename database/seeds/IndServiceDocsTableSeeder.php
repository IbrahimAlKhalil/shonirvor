<?php

use App\Models\IndService;
use App\Models\IndServiceDoc;
use Illuminate\Database\Seeder;

class IndServiceDocsTableSeeder extends Seeder
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
                    'doc' => "default/service-docs/$i.jpg",
                ]);

            }
        }

        IndServiceDoc::insert($data);
    }
}
