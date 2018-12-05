<?php

use App\Models\Ind;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class IndsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        factory(Ind::class, 200)->make()->each(function ($ind) use ($faker) {
            $ind->slug()->make([
                'name' => $faker->slug(rand(1, 6)),
            ]);
        });
    }
}
