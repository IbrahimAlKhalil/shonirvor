<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    /**
     * @param $serviceType string
     * @return object|null
     * */

    public static function getAll($serviceType)
    {
        $result = null;

        if ($serviceType == 'ind' || $serviceType == 'org') {
            $result = ServiceType::where('name', $serviceType)
                ->first()
                ->categories()
                ->where('is_confirmed', 1)
                ->get();
        }

        return $result;
    }
}
