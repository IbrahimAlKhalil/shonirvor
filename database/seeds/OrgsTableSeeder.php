<?php

use App\Models\Org;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrgsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        factory(Org::class, 200)->make()->each(function ($org) use($faker) {
            $org->slug()->make([
                'name' => $faker->slug(rand(1, 6)),
            ]);
        });;
    }
}
