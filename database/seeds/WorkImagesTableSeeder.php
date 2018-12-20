<?php

use App\Models\Ind;
use App\Models\Org;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class WorkImagesTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        $this->seed(Ind::class, 'ind', $faker);
        $this->seed(Org::class, 'org', $faker);
    }

    /**
     * @param $class Model|string
     * @param $workImagableType string
     * @param Faker $faker
     */
    private function seed($class, $workImagableType, $faker)
    {
        $serviceIds = $class::all()->pluck('id')->toArray();
        $workImagables = [];

        foreach ($serviceIds as $serviceId) {
            for ($i = 1; $i < 5; $i++) {
                array_push($workImagables, [
                    'path' => "seed/ind/$i.jpg",
                    'work_imagable_id' => $serviceId,
                    'work_imagable_type' => $workImagableType,
                    'description' => $faker->realText(100)
                ]);
            }
        }

        DB::table('work_images')->insert($workImagables);
    }
}
