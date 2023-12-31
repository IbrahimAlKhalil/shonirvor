<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function properties()
    {
        return $this->hasMany(PackageValue::class)
            ->join('package_properties', 'package_values.package_property_id', 'package_properties.id')
            ->select('package_values.id', 'package_values.package_id', 'package_values.value', 'package_properties.name');
    }

    public function type()
    {
        return $this->belongsTo(PackageType::class, 'package_type_id');
    }

    public function scopeOnlyInd($query)
    {
        return $query->where('package_type_id', 1);
    }

    public function scopeOnlyOrg($query)
    {
        $query->where('package_type_id', 2);
    }

    public function scopeOnlyAd($query)
    {
        $query->where('package_type_id', 6);
    }
}
