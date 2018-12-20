<?php

use App\Models\Package;

$factory->define(Package::class, function () {
    return [
        'package_type_id' => rand(1, 6)
    ];
});
