<?php

use App\Models\Ind;
use App\Models\Org;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkImagablesTableSeeder extends Seeder
{
    public function run()
    {
        $this->seed(Ind::class, 'ind');
        $this->seed(Org::class, 'org');
    }

    /**
     * @param $class Model|string
     * @param $workImagableType string
     * */

    private function seed($class, $workImagableType)
    {
        $serviceIds = $class::all()->pluck('id')->toArray();
        $workImagables = [];

        foreach ($serviceIds as $serviceId) {
            for ($i = 1; $i < 6; $i++) {
                array_push($workImagables, [
                    'path' => "seed/ind/$i.jpg",
                    'work_imagable_id' => $serviceId,
                    'work_imagable_type' => $workImagableType
                ]);
            }
        }
        DB::table('work_images')->insert($workImagables);
    }
}
