<?php

use App\Models\Income;
use App\Models\Package;
use App\Models\PackageType;
use Faker\Generator as Faker;

$factory->define(Income::class, function (Faker $faker) {

    $packageType = PackageType::find(array_random([1, 2, 3, 4, 6]));

    $package = Package::where('package_type_id', $packageType->id)
        ->inRandomOrder()->first();

    $incomeableType = explode('-', $packageType->name, 2)[0];

    if ($incomeableType == 'ad') {
        $incomeableId = rand(1, 5);
    } else {
        $incomeableId = rand(1, 200);
    }

    return [
        'package_id' => $package->id,
        'payment_method_id' => rand(1, 2),
        'incomeable_id' => $incomeableId,
        'incomeable_type' => $incomeableType,
        'from' => rand(1000, 9999),
        'transactionId' => $faker->regexify('[a-z0-9]{10}'),
        'approved' => rand(0, 1)
    ];
});
