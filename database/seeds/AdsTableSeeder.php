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
            $ad->image = 'seed/biggapon/'.$i.'.jpg';
            $ad->advertizer = $faker->company;
            $ad->url = 'https://www.google.com/';
            $ad->save();
        }
    }
}