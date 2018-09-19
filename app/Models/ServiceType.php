<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * @param $type string
     * @return object|$this
     * */

    public static function getThe($type)
    {
        $result = null;

        switch ($type) {
            case 'ind':
                $result = ServiceType::where('name', 'ind');
                break;
            case 'org':
                $result = ServiceType::where('name', 'ind');
        }

        return $result->first();
    }
}
