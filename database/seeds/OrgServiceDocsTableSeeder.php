<?php

use App\Models\OrgService;
use App\Models\OrgServiceDoc;
use Illuminate\Database\Seeder;

class OrgServiceDocsTableSeeder extends Seeder
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
                    'doc' => "default/service-docs/$i.jpg",
                ]);

            }
        }

        OrgServiceDoc::insert($data);
    }
}
