<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    public function properties() {

        return $this->hasMany(PackageValue::class)
            ->join('package_properties', 'package_values.package_property_id', 'package_properties.id')
            ->select('package_values.id', 'package_values.package_id', 'package_values.value', 'package_properties.name');
    }
}
