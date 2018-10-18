<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public function values() {

        return $this->hasMany(PackageValue::class)
            ->join('package_properties', 'package_values.package_property_id', 'package_properties.id');

    }
}
