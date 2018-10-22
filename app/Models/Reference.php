<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    /*********************/
    /***** Relations *****/
    /*********************/

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /******************/
    /***** Scopes *****/
    /******************/

    public function scopeOnlyInd($query)
    {
        return $query->where('references.service_type_id', 1);
    }

    public function scopeOnlyOrg($query)
    {
        return $query->where('references.service_type_id', 2);
    }
}
