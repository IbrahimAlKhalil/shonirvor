<?php

use App\Models\Category;
use App\Models\Ind;

use App\Models\User;
use App\Models\WorkMethod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class IndsTableSeeder extends Seeder
{

    public function run()
    {
        $categoryIds = Category::getAll('ind')->pluck('id')->toArray();
        $divisionIds = Division::pluck('id')->toArray();
        $districtIds = District::pluck('id')->toArray();
        $thanaIds = Thana::pluck('id')->toArray();
        $unionIds = Union::pluck('id')->toArray();
        $workMethodIds = WorkMethod::pluck('id')->toArray();

        factory(User::class, rand(15, 20))->create()->each(function ($user) use ($categoryIds, $divisionIds, $districtIds, $thanaIds, $unionIds, $workMethodIds) {

            factory(Ind::class, rand(1, 3))->create([
                'user_id' => $user->id,
                'category_id' => randomElement($categoryIds),
                'division_id' => randomElement($divisionIds),
                'district_id' => randomElement($districtIds),
                'thana_id' => randomElement($thanaIds),
                'union_id' => randomElement($unionIds)
            ])->each(function ($ind) use ($workMethodIds) {

                // ind_work_method
                $data = [];
                foreach (randomElements($workMethodIds) as $workMethodId) {
                    array_push($data, [
                        'ind_id' => $ind->id,
                        'work_method_id' => $workMethodId,
                        'rate' => randomElement([500, 50, 60, 520, 100, 300, 150])
                    ]);
                }
                DB::table('ind_work_method')->insert($data);

            });
        });
    }
}
