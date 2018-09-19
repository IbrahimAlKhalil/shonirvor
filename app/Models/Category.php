<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{

    protected $fillable = ['name', 'is_confirmed'];

    /**
     * @param $status
     * @return object
     * */

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
                ->where('is_confirmed', 1);
        }

        return $result;
    }
}
