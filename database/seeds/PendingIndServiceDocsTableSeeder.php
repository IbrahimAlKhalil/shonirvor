<?php

use App\Models\PendingIndService;
use App\Models\PendingIndServiceDoc;
use Illuminate\Database\Seeder;

class PendingIndServiceDocsTableSeeder extends Seeder
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
                    'doc' => "default/service-docs/$i.jpg",
                ]);

            }
        }

        PendingIndServiceDoc::insert($data);
    }
}
