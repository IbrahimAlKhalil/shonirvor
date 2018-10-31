<?php

use App\Models\Ind;
use App\Models\Category;
use App\Models\User;
use App\Models\Village;
use Illuminate\Database\Seeder;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Division;

class IndsTableSeeder extends Seeder
{

    public function run()
    {
        $categoryIds = Category::getAll('ind')->pluck('id')->toArray();
        $divisionIds = Division::pluck('id')->toArray();
        $districtIds = District::pluck('id')->toArray();
        $thanaIds = Thana::pluck('id')->toArray();
        $unionIds = Union::pluck('id')->toArray();
        $villageIds = Village::pluck('id')->toArray();

        factory(User::class, mt_rand(15, 20))->create()->each(function ($user) use ($categoryIds, $divisionIds, $districtIds, $thanaIds, $unionIds, $villageIds) {

            factory(Ind::class, mt_rand(1, 3))->create([
                'user_id' => $user->id,
                'category_id' => array_random($categoryIds),
                'division_id' => array_random($divisionIds),
                'district_id' => array_random($districtIds),
                'thana_id' => array_random($thanaIds),
                'union_id' => array_random($unionIds),
                'village_id' => array_random($villageIds)
            ]);
        });
    }
}
