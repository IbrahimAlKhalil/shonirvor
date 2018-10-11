<?php

use App\Models\Ind;
use App\Models\SubCategory;
use App\Models\WorkMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndWorkMethodTableSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        Ind::all()->each(function ($ind) use ($data) {
            $ind->subCategories->each(function ($subCategory) use ($ind, $data) {
                $workMethods = randomElements(WorkMethod::pluck('id')->toArray(), rand(1, 4));

                foreach ($workMethods as $workMethod) {
                    array_push($data, [
                        'ind_id' => $ind->id,
                        'work_method_id' => $workMethod,
                        'sub_category_id' => $subCategory->id,
                        'rate' => randomElement([500, 50, 60, 520, 100, 300, 150])
                    ]);
                }
            });
        });
    }
}
