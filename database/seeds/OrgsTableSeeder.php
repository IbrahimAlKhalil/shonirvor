<?php

use App\Models\Org;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Sandofvega\Bdgeocode\Models\District;
use Sandofvega\Bdgeocode\Models\Thana;
use Sandofvega\Bdgeocode\Models\Union;

class OrgsTableSeeder extends Seeder
{
    public function run()
    {
        $categoryIds = Category::getAll('org')->pluck('id')->toArray();
        $districtIds = District::pluck('id')->toArray();
        $thanaIds = Thana::pluck('id')->toArray();
        $unionIds = Union::pluck('id')->toArray();

        factory(User::class, rand(15, 20))->create()->each(function ($user) use ($categoryIds, $districtIds, $thanaIds, $unionIds) {
            $user->roles()->attach('2');

            factory(Org::class, rand(1, 3))->create([
                'user_id' => $user->id,
                'category_id' => randomElement($categoryIds),
                'district_id' => randomElement($districtIds),
                'thana_id' => randomElement($thanaIds),
                'union_id' => randomElement($unionIds)
            ]);
        });
    }
}
