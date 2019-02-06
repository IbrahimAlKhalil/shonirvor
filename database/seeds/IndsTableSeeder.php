<?php

use App\Models\Ind;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class IndsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create()->unique();
        factory(Ind::class, 200)->create()->each(function ($ind) use ($faker) {
            static $count = 0;
            $c = ++$count;

            $ind->slug()->create([
                'name' => "ind-$c-" . $faker->slug(rand(1, 6)),
            ]);
        });
    }
}
