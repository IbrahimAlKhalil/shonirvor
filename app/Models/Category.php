<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['name', 'is_confirmed', 'image'];


    /**********************/
    /***** Relations *****/
    /**********************/

    public function type()
    {
        return $this->belongsTo(ServiceType::class, 'service_type_id');
    }

    public function indServices()
    {
        return $this->hasMany(Ind::class);
    }

    public function orgServices()
    {
        return $this->hasMany(Org::class);
    }

    public function subCategories($status = null)
    {
        $result = $this->hasMany(SubCategory::class);

        switch ($status) {
            case 'confirmed':
                $result = $result->where('is_confirmed', '=', 1);
                break;
            case 'requested':
                $result = $result->where('is_confirmed', '=', 0);
        }

        return $result;
    }


    /******************/
    /***** Scopes *****/
    /******************/

    /**
     * TODO:: This method will removed. When uses of this method will replace with onlyInd(), onlyOrg(), onlyConfirmed(), onlyPending()
     *
     * @param $serviceType
     * @param int $isConfirmed
     * @return null
     */
    public static function getAll($serviceType, $isConfirmed = 1)
    {
        $result = null;

        if ($serviceType == 'ind' || $serviceType == 'org') {
            $result = ServiceType::where('name', $serviceType)
                ->first()
                ->categories()
                ->where('is_confirmed', $isConfirmed);
        } else {
            $result = Category::where('is_confirmed', $isConfirmed);
        }

        return $result;
    }

    public function scopeOnlyInd($query)
    {
        return $query->where('categories.service_type_id', 1);
    }

    public function scopeOnlyOrg($query)
    {
        return $query->where('categories.service_type_id', 2);
    }

    public function scopeOnlyConfirmed($query)
    {
        return $query->where('categories.is_confirmed', 1);
    }

    public function scopeOnlyPending($query)
    {
        return $query->where('categories.is_confirmed', 0);
    }
}
