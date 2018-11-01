<?php

use App\Models\Package;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagesTableSeeder extends Seeder
{
    public function run(Faker $faker)
    {
        factory(Package::class, 30)->create()->each(function ($package) use ($faker) {
            DB::table('package_values')->insert([
                [
                    'package_id' => $package->id,
                    'package_property_id' => 1,
                    'value' => $faker->city
                ],
                [
                    'package_id' => $package->id,
                    'package_property_id' => 2,
                    'value' => $faker->realText()
                ],
                [
                    'package_id' => $package->id,
                    'package_property_id' => 3,
                    'value' => rand(30, 1000)
                ],
                [
                    'package_id' => $package->id,
                    'package_property_id' => 4,
                    'value' => rand(100, 5000)
                ]
            ]);

            if ($package->package_type_id == 5) {
                DB::table('package_values')->insert([
                    [
                        'package_id' => $package->id,
                        'package_property_id' => 5,
                        'value' => rand(5, 30)
                    ],
                    [
                        'package_id' => $package->id,
                        'package_property_id' => 6,
                        'value' => rand(5, 50)
                    ],
                    [
                        'package_id' => $package->id,
                        'package_property_id' => 7,
                        'value' => rand(5, 50)
                    ],
                    [
                        'package_id' => $package->id,
                        'package_property_id' => 8,
                        'value' => rand(5, 50)
                    ],
                    [
                        'package_id' => $package->id,
                        'package_property_id' => 9,
                        'value' => rand(5, 50)
                    ],
                    [
                        'package_id' => $package->id,
                        'package_property_id' => 10,
                        'value' => 0
                    ]
                ]);
            }
        });
    }
}
