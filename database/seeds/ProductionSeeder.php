<?php

use App\Models\ContentType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Sandofvega\Bdgeocode\Seeds\BdgeocodeSeeder;

class ProductionSeeder extends Seeder
{
    public function run()
    {
        $this->call(BdgeocodeSeeder::class);

        /***** Creating Roles *****/
        DB::table('roles')->insert([
            ['name' => 'admin']
        ]);

        /***** Service Provider Contract Method *****/
        DB::table('work_methods')->insert([
            ['name' => 'ঘন্টা ভিত্তিক'],
            ['name' => 'দৈনিক'],
            ['name' => 'মাসিক']
        ]);

        /***** Service Type *****/
        DB::table('service_types')->insert([
            ['name' => 'ind'],
            ['name' => 'org']
        ]);


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
                'order' => 1
            ])],
            ['data' => json_encode([
                'image' => 'default/home-slider/2.jpg',
                'link' => 'https://www.google.com',
                'order' => 2
            ])],
            ['data' => json_encode([
                'image' => 'default/home-slider/3.jpg',
                'link' => 'https://www.google.com',
                'order' => 3
            ])]
        ]);

    }
}