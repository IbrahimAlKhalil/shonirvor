<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    protected $dates = ['target_start_time', 'target_end_time'];

    /*********************/
    /***** Relations *****/
    /*********************/

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function service()
    {
        if ($this->service_type_id == 1) {

            return $this->belongsTo(Ind::class)->withTrashed();

        } elseif ($this->service_type_id == 2) {

            return $this->belongsTo(Org::class)->withTrashed();

        } else {

            abort(422, 'service_type_id is not exist.');

        }
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
