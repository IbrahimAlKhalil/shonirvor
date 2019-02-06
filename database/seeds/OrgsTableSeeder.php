<?php

use App\Models\Org;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class OrgsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create()->unique();
        factory(Org::class, 200)->create()->each(function ($org) use ($faker) {
            static $count = 0;
            $c = ++$count;

            $org->slug()->create([
                'name' => "org-$c-" . $faker->slug(rand(1, 6)),
            ]);
        });


    }
}
