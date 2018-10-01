<?php

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdsTableSeeder extends Seeder
{
    public function run()
    {
        for ($i=1; $i<6; $i++) {
            $ad = new Ad();
            $ad->image = 'seed/biggapon/'.$i.'.jpg';
            $ad->url = 'https://www.google.com/';
            $ad->save();
        }
    }
}