<?php

use App\Models\Ind;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndWorkMethodTableSeeder extends Seeder
{
    public function run()
    {
        $inds = Ind::select('id')->get();

        $data = [];

        foreach ($inds as $ind) {

            foreach ($ind->subCategories as $subCategory) {

                $usedWorkMethodIds = [];
                for ($i = 1; $i <= rand(1, 4); $i++) {

                    $workMethodIds = range(1, 4);
                    $nonUsedWorkMethodIds = array_diff($workMethodIds, $usedWorkMethodIds);
                    $workMethodId = randomElement($nonUsedWorkMethodIds);

                    array_push($data, [
                        'ind_id' => $ind->id,
                        'work_method_id' => $workMethodId,
                        'sub_category_id' => $subCategory->id,
                        'rate' => randomElement([50, 10, 60, 80, 90, 40, 300, 560, 200, 560, 990, 5000])
                    ]);

                    array_push($usedWorkMethodIds, $workMethodId);

                }

            }

        }

        DB::table('ind_work_method')->insert($data);
    }
}
