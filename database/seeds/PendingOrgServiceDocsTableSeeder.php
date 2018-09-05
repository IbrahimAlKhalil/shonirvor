<?php

use App\Models\PendingOrgService;
use App\Models\PendingOrgServiceDoc;
use Illuminate\Database\Seeder;

class PendingOrgServiceDocsTableSeeder extends Seeder
{
    public function run()
    {
        $ids = PendingOrgService::pluck('id');
        $data = [];

        foreach ($ids as $id) {
            for ($i = 1; $i < 5; $i++) {

                array_push($data, [
                    'pending_org_service_id' => $id,
                    'doc' => "default/service-docs/$i.jpg",
                ]);

            }
        }

        PendingOrgServiceDoc::insert($data);
    }
}
