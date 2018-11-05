<?php

use App\Models\Ad;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class AdsTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        for ($i=1; $i<6; $i++) {
            $ad = new Ad();
            $ad->user_id = rand(1, 200);
            $ad->image = 'seed/biggapon/'.$i.'.jpg';
            $ad->expire = rand(0, 1) ? $faker->dateTimeBetween('-3 years', '3 years') : null;
            $ad->url = 'https://'.$faker->domainName;
            $ad->save();
        }
    }
}