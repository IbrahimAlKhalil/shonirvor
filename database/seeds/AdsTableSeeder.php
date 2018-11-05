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
            $ad->user_id = rand(1, 6);
            $ad->image = 'seed/biggapon/'.$i.'.jpg';
            $ad->expire = $faker->randomElement([null, now()->addDays(rand(10, 30))->format('Y-m-d H:i:s')]);
            $ad->url = 'https://www.google.com/';
            $ad->save();
        }
    }
}