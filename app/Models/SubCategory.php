<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $fillable = ['name', 'is_confirmed'];



    /**********************/
    /***** Relations *****/
    /**********************/

    public function inds()
    {
        return $this->morphedByMany(Ind::class, 'sub_categoriable')->where('is_pending', 0);
    }

    public function orgs()
    {
        return $this->morphedByMany(Org::class, 'sub_categoriable')->where('is_pending', 0);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }



    /******************/
    /***** Scopes *****/
    /******************/

    /**
     * TODO:: This method will removed. When uses of this method will replace with onlyInd(), onlyOrg(), onlyConfirmed(), onlyPending()
     *
     * @param $serviceType
     * @return null
     */
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

    public function scopeOnlyConfirmed($query)
    {
        return $query->where('sub_categories.is_confirmed', 1);
    }

    public function scopeOnlyPending($query)
    {
        return $query->where('sub_categories.is_confirmed', 0);
    }
}
