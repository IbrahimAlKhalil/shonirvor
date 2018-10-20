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
            ['name' => 'মাসিক'],
            ['name' => 'চুক্তি ভিত্তিক']
        ]);

        /***** Service Type *****/
        DB::table('service_types')->insert([
            ['name' => 'ind'],
            ['name' => 'org']
        ]);


        /***** Package Types *****/
        DB::table('package_types')->insert([
            ['name' => 'ind-service'],
            ['name' => 'org-service'],
            ['name' => 'ind-top-service'],
            ['name' => 'org-top-service'],
            ['name' => 'referrer'],
            ['name' => 'ad']
        ]);

        /***** Package Properties *****/
        DB::table('package_properties')->insert([
            ['name' => 'name'],
            ['name' => 'description'],
            ['name' => 'duration'],
            ['name' => 'fee'],
            ['name' => 'refer_target'],
            ['name' => 'refer_onetime_interest'],
            ['name' => 'refer_renew_interest']
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