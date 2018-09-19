<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['name', 'is_confirmed'];

    public function inds()
    {
        return $this->morphedByMany(Ind::class, 'sub_categoriable');
    }

    public function orgs()
    {
        return $this->morphedByMany(Org::class, 'sub_categoriable');
    }

    /**
     * @param $serviceType string
     * @return object|null
     * */

    public static function getAll($serviceType)
    {
        $result = null;

        if ($serviceType == 'ind' || $serviceType == 'org') {
            $categoryIds = ServiceType::where('name', $serviceType)
                ->first()
                ->categories()
                ->where('is_confirmed', 1)
                ->pluck('id')
                ->toArray();
            $result = SubCategory::whereIn('id', $categoryIds);
        }

        return $result;
    }
}
