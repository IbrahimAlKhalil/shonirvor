<?php

use App\Models\ContentType;
use Illuminate\Database\Seeder;

class ContentsTableSeeder extends Seeder
{
    public function run()
    {
        /********** Content Type ***********/
        $registrationInstruction = new ContentType(['name' => 'registration-instruction']);
        $registrationInstruction->save();
        $registrationInstruction->contents()->create(['data' => null]);


        /********** Slider ***********/
        $homeSlider = new ContentType(['name' => 'slider']);
        $homeSlider->save();
        $homeSlider->contents()->createMany([
            ['data' => json_encode([
                'image' => 'default/home-slider/1.jpg',
                'link' => 'https://www.google.com',
            ])],
            ['data' => json_encode([
                'image' => 'default/home-slider/2.jpg',
                'link' => 'https://www.google.com',
            ])],
            ['data' => json_encode([
                'image' => 'default/home-slider/3.jpg',
                'link' => 'https://www.google.com',
            ])]
        ]);
    }
}