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

            if ($package->package_type_id == 5) {

                // generating default properties value
                $defaultReferrer = (int) ! $defaultReferrerExist = DB::table('package_values')
                    ->select('package_values.package_id',
                        'package_values.package_property_id',
                        'package_values.value',
                        'packages.package_type_id')
                    ->join('packages', 'package_values.package_id', 'packages.id')
                    ->where([
                        ['package_values.package_property_id', 10],
                        ['packages.package_type_id', 5],
                        ['package_values.value', 1]
                    ])
                    ->exists();
                if ($defaultReferrerExist) $defaultReferrer = null;

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
                        'value' => $defaultReferrer
                    ]
                ]);
            }

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

        });


        // If no referrer package created
        if ( ! DB::table('packages')->where('package_type_id', 5)->exists()) {

            $packageId = DB::table('packages')->insertGetId(['package_type_id' => 5]);

            DB::table('package_values')->insert([
                [
                    'package_id' => $packageId,
                    'package_property_id' => 1,
                    'value' => $faker->city
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 2,
                    'value' => $faker->realText()
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 3,
                    'value' => rand(30, 1000)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 4,
                    'value' => rand(100, 5000)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 5,
                    'value' => rand(5, 30)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 6,
                    'value' => rand(5, 50)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 7,
                    'value' => rand(5, 50)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 8,
                    'value' => rand(5, 50)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 9,
                    'value' => rand(5, 50)
                ],
                [
                    'package_id' => $packageId,
                    'package_property_id' => 10,
                    'value' => 1
                ]
            ]);

        }

    }
}
