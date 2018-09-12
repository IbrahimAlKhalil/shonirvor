<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkMethod extends Model
{
    public function indServices() {
        return $this->belongsToMany(IndService::class, 'work_method_ind_service');
    }
}
